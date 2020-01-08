<?php

namespace App\Http\Controllers;


use Auth;
use App\Post;
use App\Comment;
use Validator;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $comments = Post::find($post->id)->comments;

        return response(["data" => $comments], 200);
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
    public function store(Post $post, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        try {
            if($validator->fails()) {
                return response([
                    "message" => "The given data was invalid.",
                    "errors" => $validator->errors(),
                ], 422);
            } else {
                $comment = Comment::createComment($request->all(), Auth::user()->id, $post->id);

                return response($comment, 201);
            }
        } catch (\Exception $e) {
            return response([
                    "message" => "No query results for model [Commentable\\Models\\Comment]",
                    "exception" => $e->getMessage(),
                ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        $comments = Comment::find($comment->id);

        $comments->body = $request->body;

        $comments->save();

        return response(["data" => $comments], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        $comments = Comment::find($comment->id);
        $comments->delete();

        return response(["status" => "record deleted successfully"], 200);
    }
}
