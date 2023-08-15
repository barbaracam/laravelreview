<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //login
    public function login(Request $request){
        $incomingFields = $request->validate([
            //the loginusername/loginpassword come from the form in the layout
            'loginusername' => 'required',         
            'loginpassword' => 'required',
        ]);

        //universal available function from laravel call auth
        if(auth()->attempt(['username' => $incomingFields['loginusername'],'password' => $incomingFields['loginpassword'] ])){
            $request->session()->regenerate();
            event(new OurExampleEvent(['username'=> auth()->user()->username, 'action'=> 'login']));
            return redirect('/')->with('success','You have successful Login!!');
        }else {
            return redirect('/')->with('fail','invalid login');
        }  
    }

    // approve and insert data into database for registration
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:15', Rule::unique('users', 'username') ],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            // confirmed check match password
            'password' => ['required','min:6', 'confirmed'],
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        //after register lets login and then redirect
        auth()->login($user);

        return  redirect('/')->with('success', 'thanks for join us');
    }

    // if you login go to the feed, otherwise sign up
    public function showCorrectHomepage(){
        if(auth()->check()){
            //feedPosts() from the model user is the function
            //we change the get() by paginate()
            return view('homepage-in',['posts'=>auth()->user()->feedPosts()->latest()->paginate(3)]);
        } else {
            // if(Cache::has('postCount')){
            //     $postCount = Cache::get('postCount');
            // } else {
            //     sleep(5);
            //     $postCount = Post::count();
            //     Cache::put('postCount', $postCount, 20);
            // }
            $postCount = Cache::remember('postCount', 20, function(){
                // sleep(5);
                return Post::count();
            });
            return view('homepage', ['postCount'=> $postCount]);
        }
    }

    //logout function
    public function logout(){
        event(new OurExampleEvent(['username'=> auth()->user()->username, 'action'=> 'logout']));
        auth()->logout();        
        return redirect('/')->with('success','You have Logout!!');
    }

    private function getSharedData($user){
        //below is a json example of the info we get
        // $thePosts = $user->posts()->get();
        // return $thePosts;
        //we provide username as well in the view array, if not will check by the id
        $currentlyFollowing = 0;
    
        if(auth()->check()){
            //will check bolean, if it is already following
            $currentlyFollowing = Follow::where([['user_id','=',auth()->user()->id],['followeduser','=', $user->id]])->count();
        }

        View::share('sharedData', [ 'currentlyFollowing'=> $currentlyFollowing,'avatar'=>$user->avatar ,'username'=> $user->username, 'postCount'=> $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followingCount' => $user->followers()->count()]);

    }


    //Profile functions below
   public function profile(User $user){  

        $this->getSharedData($user); 
        return view('profile-posts',[ 'posts'=> $user->posts()->latest()->get()]);
   }

   public function showAvatarForm(){
        return view('avatar-form');
   } 

   public function storeAvatar(Request $request){
        $request->validate([
            //it says has to be require and image
            'avatar' => 'required|image|max:3000',        
        ]);
        // $request->file('avatar')->store('public/avatars');

        //create aun unique name for the file
        $user = auth()->user();
        $filename = $user->id.'_'. uniqid().'.jpg';
        
        // we opened the package installed Image::make() to resize pics like below   
        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        //storage the picture
        Storage::put('public/avatars/'.$filename, $imgData);

        $oldAvatar = $user->avatar;

        //added in the database
        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != '/fallback-avatar.jpg'){
            Storage::delete(str_replace('/storage/', 'public/',$oldAvatar ));
        }

        return back()->with('success', 'congrats on the update');

    }

    ///Function to profile/followers

    public function profileFollowers(User $user){

        $this->getSharedData($user);
        //debug code below
        // return $user->followers()->latest()->get(); 
        return view('profile-followers',[ 'followers'=> $user->followers()->latest()->get()]);
    }


    ///Function to profile/following
    public function profileFollowing(User $user){ 

        //the following() before user come from the name of the function in user model
        
        $this->getSharedData($user); 
        return view('profile-following',[ 'following'=> $user->following()->latest()->get()]);

    }


    //With Raw to obtain json data
    public function profileRaw(User $user){  

        return response()->json(['theHTML'=>view('profile-post-only', ['posts'=>$user->posts()->latest()->get()])->render(), 'doctitle'=>$user->username. " s' profile"]);

   }

   ///Function to profile/followers Raw

   public function profileFollowersRaw(User $user){

    return response()->json(['theHTML'=>view('profile-followers-only', ['followers'=>$user->followers()->latest()->get()])->render(), 'doctitle'=>$user->username. " s' followers"]);

    }


    ///Function to profile/following Raw
    public function profileFollowingRaw(User $user){ 
        return response()->json(['theHTML'=>view('profile-following-only', ['following'=>$user->following()->latest()->get()])->render(), 'doctitle'=> 'who ' . $user->username. " follows"]);

    }


}
