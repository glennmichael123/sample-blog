<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

	protected $fillable = [
        'body', 'commentable_type', 'commentable_id', 'creator_id', 'user_id', 'parent_id'
    ];

    public static function createComment($data, $userId, $postId)
    {	
        $comment   = array('body'    => $data['body'], 
                        'commentable_type' => "App\Post",
                        'commentable_id' => $postId,
                    	'creator_id'     => $userId,
                    	'parent_id'      => $postId,
                    );

        $commentId = self::create($comment)->id;
        
        return self::find($commentId);
    }

    public function posts()
    {
    	return $this->belongsTo('App\Post');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
