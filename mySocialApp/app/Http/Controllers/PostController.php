<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //show the create form
    public function showCreateForm(){
        return view('create-post');
    }


    //get the fields info
    public function createPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' =>'required',            
        ]);

        $incomingFields['title']= strip_tags($incomingFields['title']);
        $incomingFields['body']= strip_tags($incomingFields['body']);
        $incomingFields['user_id']= auth()->id();

        //attach the model with the fields
        $newPost = Post::create($incomingFields);

        return redirect("/post/{$newPost->id}")->with('success', 'post created successfully');
    }


    //when laravel do the lookout for you from the parameter, will check by the id number
    public function viewSinglePost(Post $post){   
        
        $post['body']= Str::markdown($post->body);
        // the parameter $post need to match the router {post} in order to access the info
        //we are calling that id $post
        return view("single-post",['post' => $post]);

    }
    //different way
    // public function deletePost(Post $post){
    //     $post->delete();
    //     return redirect('/profile/' . auth()->user()->username)->with('success', 'post deleted it sucessfully');
    // }
    //different way
    public function deletePost(Post $post){
        if(auth()->user()->cannot('delete', $post)){
            return redirect('/post/'. $post->id)->with('fail', 'You cant delete the post');
        }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'post deleted it sucessfully');
    }

    ///Update post

    public function showEditForm(Post $post){
        return view('edit-post',['post' => $post]);
    }

    public function realUpdate(Post $post, Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' =>'required',  
        ]);
        $incomingFields['title']= strip_tags($incomingFields['title']);
        $incomingFields['body']= strip_tags($incomingFields['body']);
        
        $post->update($incomingFields);
        return back()->with('success', 'Post Updated it!!!');

    }
}
