<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class example extends Controller
{
    public function example(){
        $ourName = 'Barbie';
        $animals = ['dog', 'cat', 'dino'];
        return view('example',['allanimals' => $animals,'name'=> $ourName, 'catname' => "meow"]);
    }
    public function ejemplo(){
        return '<h1>Hello About</h1><a href="/example">link2</h1>';
    }
}
