<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ContainerUser extends Model
{
    protected $table = 'container_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        //
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($containerUser) {
            $containerUser->id = Uuid::generate()->string;
    	});
    }
}