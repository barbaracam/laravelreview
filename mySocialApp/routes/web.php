<?php

use App\Events\chatMessage;
// use App\Http\Controllers\example;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Shows write page
//we are calling name login as in case a client is not auth we will take them to a page to login
Route::get('/',  [UserController::class, 'showCorrectHomepage'])->name('login');
// Route::get('/about',  [example::class, 'aboutPage']);

//Registration and login forms
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//Posts
//MustBeLogged is a middleware create bymyself the other by the system, you can choose
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('MustBeLogged');
Route::post('/create-post', [PostController::class, 'createPost'])->middleware('auth');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->middleware('auth');
Route::delete('/post/{post}', [PostController::class, 'deletePost']);
// Route::delete('/post/{post}', [PostController::class, 'deletePost'])->middleware('can:delete,post');
// lets nopw edit and need two routers
Route::get('post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('post/{post}', [PostController::class, 'realUpdate'])->middleware('can:update,post');
//search
Route::get('/search/{term}', [PostController::class, 'search']);


//Profile routes
//when match{}laravel will do the lookout for you, but as we dont want check by id, we will by username
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('auth');
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers'])->middleware('auth');
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing'])->middleware('auth');


//spa
Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
    Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw'])->middleware('auth');
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw'])->middleware('auth');
    Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw'])->middleware('auth');

});


//Gate Example
// Route::get('/admins-only', function(){
//     if(Gate::allows('visitAdminPages')){
//         return 'Yeah you are an admin';
//     }
//     return "You are not welcome here";
// });
//or
Route::get('/admins-only', function(){ return 'Yeah you are an admin';})->middleware('can:visitAdminPages');


//Avatar
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('MustBeLogged');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('MustBeLogged');

//follows
Route::post('create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('MustBeLogged');
Route::post('remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('MustBeLogged');


//Chat
//chat route
Route::post('/send-chat-message', function(Request $request){
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);
    if(!trim(strip_tags($formFields['textvalue']))){
        return response()->noContent(); 
    }
    
    //we are broadcasting a new instance of a chat message event to others
     broadcast(new ChatMessage(['username'=>auth()->user()->username, 'textvalue'=> strip_tags($request->textvalue), 'avatar'=> auth()->user()->avatar]))->toOthers();
    return response()->noContent();
})->middleware('MustBeLogged');

//spa

