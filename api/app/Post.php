<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'title', 'slug', 'content', 'user_id', 'image'
    ];

    protected $dates = ['deleted_at'];


    public static function createPost($data, $userId)
    {	
        $post   = array('title'   => $data['title'], 
                        'slug'    => str_slug($data['title']),
                        'image'   => $data['image'],
                        'content' => $data['content'],
                    	'user_id' => $userId
                    );

        $postId = self::create($post)->id;
        
        return self::find($postId);
    }

    public function comments()
    {
    	return $this->hasMany('App\Comment', 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getRouteKeyName()
	{
    	return 'slug';
	}
}
