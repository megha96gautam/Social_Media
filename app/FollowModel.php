<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'follower';
    protected $fillable = [
        'user_id', 'follower_id', 'request_status',
    ];
    public $timestamps = false;
}
