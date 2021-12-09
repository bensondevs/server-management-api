<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Country extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'countries';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Enable timestamp for model execution
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
    	'country_name',
    	'country_json',
    ];

    /**
     * Model boot static method
     * This method handles event and hold event listener and observer
     * This is where Observer and Event Listener Class should be put
     * 
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($country) {
            $country->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable attribute of "detail"
     * This callable attribute will return the detail of saved JSON
     * as array
     * 
     * @return array
     */
    public function getDetailAttribute()
    {
        $json = $this->attributes['country_json'];
    	return json_decode($json, true);
    }
}