<?php

namespace App;

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
        $comment = array('body' => $data['body'],
                          'commentable_id'   => $postId,
                          'creator_id'       => $userId,
                          'parent_id'        => $postId,
                          'commentable_type' => "App\Post",
                        );

        $commentId = self::create($comment)->id;

        return self::find($commentId);
    }

    /**
     * Post that this comment belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
    	return $this->belongsTo('App\Post');
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
