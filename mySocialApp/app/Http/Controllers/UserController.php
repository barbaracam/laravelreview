<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //login
    public function login(Request $request){
        $incomingFields = $request->validate([
            //the loginusername/loginpassword come from the form
            'loginusername' => 'required',         
            'loginpassword' => 'required',
        ]);

        //universal available function from laravel call auth
        if(auth()->attempt(['username' => $incomingFields['loginusername'],'password' => $incomingFields['loginpassword'] ])){
            $request->session()->regenerate();
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
            return view('homepage-in');
        } else {
            return view('homepage');
        }
    }

    //logout function
    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','You have Logout!!');
    }


    //Profile functions below
   public function profile(User $user){
    //below is a json example of the info we get
    // $thePosts = $user->posts()->get();
    // return $thePosts;
    //we provide username as well in the view array, if not will check by the id
    return view('profile-posts',['username'=> $user->username, 'posts'=> $user->posts()->latest()->get(), 'postCount'=> $user->posts()->count()]);
   }








}
