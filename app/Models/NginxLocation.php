<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Models\Container;

class NginxLocation extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'nginx_locations';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model timestamp enability
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model primary key incrementing enability
     * 
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'nginx_location',
        'nginx_config',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($nginxLocation) {
            $nginxLocation->id = Uuid::generate()->string;
    	});
    }

    public function getLocationAttribute()
    {
        return $this->attributes['nginx_location'];
    }

    public function setLocationAttribute(string $location)
    {
        $this->attributes['nginx_location'] = $location;
    }

    public function getConfigAttribute()
    {
        $config = $this->attributes['nginx_config'];
        return base64_decode($config);
    }

    public function setConfigAttribute(string $config)
    {
        if (! base64_decode($config, true)) {
            $config = base64_encode($config);
        }

        $this->attributes['nginx_config'] = $config;
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public static function findInContainer(Container $container, string $location)
    {
        $nginxLocation = self::where('container_id', $container->id)
            ->where('location', $location)
            ->first();
        return $nginxLocation;
    }
}