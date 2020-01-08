<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function createUser($data)
    {
        $user   = array('name' => $data['name'], 
                        'email' => $data['email'], 
                        'password' => bcrypt($data['password'])
                    );

        $userId = self::create($user)->id;
        
        return self::find($userId);
    }

    public function accessTokens()
    {
        return $this->hasMany('App\OauthAccessToken');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'creator_id');
    }
}
