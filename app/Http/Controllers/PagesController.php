<?php

namespace App\Http\Controllers;

use App\Post;

class PagesController extends Controller
{
    public function getIndex() {
    	$posts = Post::orderBy('id', 'desc')->limit(4)->get();
        return view('pages/welcome')->withPosts($posts);
    }
    
    /* public function getAbout() {
        
    }
    
    public function getContact() {
        
    }
    
    public function postContact() {
        
    } */
}
