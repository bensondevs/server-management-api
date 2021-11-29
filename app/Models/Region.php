<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Region extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'region_name',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($region) {
            $region->id = Uuid::generate()->string;
    	});
    }
}