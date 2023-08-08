<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\example;

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

Route::get('/',  [example::class, 'homepage']);
Route::get('/about',  [example::class, 'aboutPage']);