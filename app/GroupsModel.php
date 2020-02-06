<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupsModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'groups';
    protected $fillable = [
        'group_id', 'group_name', 'group_owner_id',
    ];
    //public $timestamps = false;
}
