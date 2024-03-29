<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Post;
use Session;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //all posts
        $posts = Post::OrderBy('id', 'desc')->paginate(10);

        //return a view
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validating the data
        $this->validate($request, [
                'title'=>'required|max:191',
                'slug'=>'required|alpha_dash|min:5|max:191|unique:posts,slug',
                'body'=>'required'                
            ]);

        //store in database
        $post = new Post;

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->body = $request->body;

        $post->save();

        Session::flash('success', 'Пост был успешно сохранён!');

        //redirect to another page
        return redirect()->route('posts.show', $post->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //find the post
        $post = Post::find($id);

        //return the view
        return view('posts.edit')->withPost($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate
        $post = Post::find($id);
        if ($request->input('slug') == $post->slug) {
            $this->validate($request, [
                'title'=>'required|max:191',
                'body'=>'required'                
            ]);
        } else {
            $this->validate($request, [
                'title'=>'required|max:191',
                'slug'=>'required|alpha_dash|min:5|max:191|unique:posts,slug',
                'body'=>'required'                
            ]);
        }

        //save to db
        $post = Post::find($id);

        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->body = $request->input('body');
        
        $post->save();

        Session::flash('success', 'Изменения успешно сохранены!');

        //redirect
        return redirect()->route('posts.show', $post->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        $post->delete();

        Session::flash('success', 'Пост был успешно удалён!');

        return redirect()->route('posts.index');
    }
}
