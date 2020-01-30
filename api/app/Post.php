<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'content', 'user_id', 'image'
    ];

    /**
     * For soft deletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Save user to database
     *
     * @param  \App\Post  $data
     *
     * @return array
     */
    public static function createPost($data)
    {
        $post = new Post([
          'title'   => $data['title'],
          'slug'    => str_slug($data['title']),
          'image'   => $data['image'],
          'content' => $data['content'],
        ]);

        $postId = auth()->user()
            ->posts()
            ->save($post)->id;

        return self::find($postId);
    }

    /**
     * Comments that this post has many of
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
    	return $this->morphMany('App\Comment', 'commentable');
    }

     /**
     * The user that this post belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
