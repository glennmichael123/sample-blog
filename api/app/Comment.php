<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{

    protected $fillable = [
        'body', 'commentable_type', 'commentable_id', 'creator_id', 'user_id', 'parent_id'
    ];

    /**
     * Save comment to database
     *
     * @param  \App\Comment  $data
     * @param  \App\User $userId
     * @param  \App\Post  $postId
     *
     * @return array
     */
    public static function createComment($data, $userId, $postId)
    {
        $comment = new Comment([
          'body' => $data['body'],
          'creator_id' => $userId,
        ]);

        $post = Post::find($postId);

        $commentId = $post->comments()
            ->save($comment)->id;

        return self::find($commentId);
    }

    /**
     * Post that this comment belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
    	return $this->morphTo();
    }

    /**
     * User that this comment belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
