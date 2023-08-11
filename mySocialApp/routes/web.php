<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\example;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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


//Profile routes
//when match{}laravel will do the lookout for you, but as we dont want check by id, we will by username
Route::get('/profile/{user:username}', [UserController::class, 'profile'])->middleware('auth');

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

