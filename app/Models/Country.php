<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
    	'country_name',
    	'country_json',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($country) {
            $country->id = Uuid::generate()->string;
    	});
    }

    public function getDetailAttribute()
    {
    	return json_decode($this->attributes['country_json']);
    }
}