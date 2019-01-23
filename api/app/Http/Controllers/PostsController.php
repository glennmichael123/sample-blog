<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{

    protected $limit = 10;

    /**
     * Display all posts
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? $this->limit;

        $posts = Post::paginate($limit);

        return PostResource::collection($posts);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  Illuminate\Http\Request
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title'   => 'required|string',
            'content' => 'required|string',
            'image'   => 'required|string',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return response([
                    "message" => "The given data was invalid.",
                    "errors"  => $validator->errors(),
                ], 422);
        }

        $post = Post::createPost($request->all(), Auth::user()->id);

        return new PostResource($post);
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
            return response(["data" => new PostResource($post)]);
        } catch (\Exception $e) {
            return response(["message" => "No query results for model [App\\Post]."], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
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

        return response(["data" => new PostResource($posts)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Post::find($post->id)
          ->delete();

        return response(["status" => "record deleted successfully"], 200);
    }
}
