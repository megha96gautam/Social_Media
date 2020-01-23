<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendsModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'friends';
    protected $fillable = [
        'user_id', 'friend_id'
    ];
    public $timestamps = false;
}
