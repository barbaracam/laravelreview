<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class example extends Controller
{
    public function homepage(){
        $ourName = 'Barbie';
        $animals = ['dog', 'cat', 'dino'];
        return view('homepage',['allanimals' => $animals,'name'=> $ourName, 'catname' => "meow"]);
    }
    public function aboutPage(){
        return view('single-post');
    }
}
