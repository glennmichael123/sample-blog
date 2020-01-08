<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate(10);

        return response($posts, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|string',
        ]);

        if($validator->fails()) {
            return response([
                    "message" => "The given data was invalid.",
                    "errors" => $validator->errors(),
                ], 422);
        } else {
            $post = Post::createPost($request->all(), Auth::user()->id);

            return response($post, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        
        try {
            return response(["data" => $post], 200);
        } catch (\Exception $e) {
            return response(["message" => "No query results for model [App\\Post]."], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
           
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $posts = Post::find($post->id);

        $posts->image = $request->image;
        $posts->title = $request->title;
        $posts->slug  = str_slug($request->title);
        $posts->content = $request->content;

        $posts->save();

        return response(["data" => $posts], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $posts = Post::find($post->id);
        $posts->delete();

        return response(["status" => "record deleted successfully"], 200);
    }
}
