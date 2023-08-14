<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
  public function createFollow(User $user){
    //you can follow yourself
    if($user->id == auth()->user()->id){
        return back()->with('fail', 'you cant follow yourself');
    }  

    //you cant follow somebody twice
    $existCheck = Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->count();

    if($existCheck){
        return back()->with('fail', 'you already followed that user');
    }

    $newFollow = new Follow;
    $newFollow->user_id = auth()->user()->id;
    $newFollow->followeduser = $user->id;
    $newFollow->save();

    return back()->with('success', 'approved the following');

  }
  public function removeFollow(User $user){
    Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->delete();
    return back()->with('success', 'you are not following more this user');
  }
}
