<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\RegionObserver as Observer;

class Region extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'regions';

    /**
     * Model primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model timestamp marking enability
     * Set to TRUE to set the value of `created_at` upon model create 
     * and `updated_at` upon model updating event 
     * 
     * @var bool 
     */
    public $timestamps = true;

    /**
     * Model primary key incrementing. 
     * Set to TRUE if `id` is int, otherwise let it be FALSE
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'region_name',
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
     * Get datacenters of current region
     */
    public function datacenters()
    {
        return $this->hasMany(Datacenter::class);
    }

    /**
     * Get servers of region through datacenters
     */
    public function servers()
    {
        return $this->hasManyThrough(Server::class, Datacenter::class);
    }

    /**
     * Select the best datacenter for the user.
     * The best datacenter can be decided by examining the amount of users
     * for each datacenter. This method will pick datacenter with the least users
     * and return it.
     * 
     * @return \App\Models\Datacenter
     */
    public function selectBestDatacenter()
    {
        return Datacenter::leastSelected()->of($this)->first();
    }
}