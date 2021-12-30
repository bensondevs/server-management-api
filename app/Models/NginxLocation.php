<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\NginxLocationObserver as Observer;

class NginxLocation extends Model
{
    use HasFactory;

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

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'nginx_location',
        'nginx_config',
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
        self::observe(Observer::class);
    }

    /**
     * Create callable attribute of "location"
     * This attribute will return the NGINX location
     * 
     * @return  string
     */
    public function getLocationAttribute()
    {
        return $this->attributes['nginx_location'];
    }

    /**
     * Create callable attribute of "location"
     * This attribute will set the value for "nginx_location"
     * 
     * @param  string  $location
     * @return  void
     */
    public function setLocationAttribute(string $location)
    {
        $this->attributes['nginx_location'] = $location;
    }

    /**
     * Create callable attributw of "config"
     * This callable attribute will return config as array
     * 
     * @return  array
     */
    public function getConfigAttribute()
    {
        $config = $this->attributes['nginx_config'];
        return base64_decode($config);
    }

    /**
     * Create settable attribute of "config"
     * This settable attribute will set the value of
     * configuration
     * 
     * @param  string  $config
     * @return void
     */
    public function setConfigAttribute(string $config)
    {
        if (! base64_decode($config, true)) {
            $config = base64_encode($config);
        }

        $this->attributes['nginx_config'] = $config;
    }

    /**
     * Get container of the NGINX Location
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Find NGINX Location in certain container by location name
     * 
     * @param  \App\Models\Container  $container
     * @param  string  $location
     * @param  bool  $abortNotFound
     * @return  \App\Models\NginxLocation|null|abort 404
     */
    public static function findInContainer(
        Container $container, 
        string $location,
        bool $abortNotFound = false
    ) {
        $query = self::where('container_id', $container->id)->where('location', $location);
        return $abortNotFound ? $query->firstOrFail() : $query->first();
    }
}