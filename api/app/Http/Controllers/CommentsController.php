<?php

namespace App\Http\Controllers;


use Auth;
use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \App\Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {
        $comments = Post::find($post->id)->comments;

        return response(["data" => CommentResource::collection($comments)], 200);
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
          'body' => 'required|string',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        $validator = $this->validator($request->all());

        try {
            if($validator->fails()) {
                return response([
                    "message" => "The given data was invalid.",
                    "errors"  => $validator->errors(),
                ], 422);
            } else {
                $comment = Comment::createComment($request->all(), Auth::user()->id, $post->id);

                return new CommentResource($comment);
            }
        } catch (\Exception $e) {
            return response([
                    "message"   => "No query results for model [Commentable\\Models\\Comment]",
                    "exception" => $e->getMessage(),
                ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @param  \App\Comment $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        $comments = Comment::find($comment->id);

        $comments->body = $request->body;

        $comments->save();

        return response(["data" => new CommentResource($comments)], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        Comment::find($comment->id)
          ->delete();

        return response(["status" => "record deleted successfully"], 200);
    }
}
