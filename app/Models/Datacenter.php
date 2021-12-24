<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use App\Traits\Searchable;

use App\Observers\DatacenterObserver as Observer;
use App\Enums\Datacenter\DatacenterStatus as Status;

class Datacenter extends Model
{
    use Searchable;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'datacenters';

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
     * Column that searchable
     * 
     * @var array
     */
    protected $searchable = [
        'datacenter_name',
        'client_datacenter_name',
        'location',
    ];

    /**
     * Model fillable columns
     * 
     * @var array
     */
    protected $fillable = [
        'region_id',
        
        'datacenter_name',
        'client_datacenter_name',
        'location',
        'status',
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
     * Create callable method of "leastSelected()"
     * This callable method will query only datacenter with least amount
     * of container active inside
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeastSelected(Builder $query)
    {
        return $query->withCount('containers')
            ->orderByDesc('containers_count');
    }

    /**
     * Create callable method of "of(Region $region)"
     * This callable method will query only datacenters of specified
     * region.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOf(Builder $query, Region $region)
    {
        return $query->where('region_id', $region->id);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return the status description
     * from the enum
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Get region of the datacenter
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get list of subnets of the datacenter
     */
    public function subnets()
    {
        return $this->hasMany(Subnet::class);
    }

    /**
     * Get servers of the datacenter
     */
    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    /**
     * Get containers inside the datacenter
     */
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * Select server with least amount of users.
     * 
     * @return \App\Models\Server
     */
    public function selectBestServer()
    {
        return Server::leastSelected()->of($this)->first();
    }

    /**
     * Select subnet with least amount of users
     * 
     * @return \App\Models\Subnet
     */
    public function selectBestSubnet()
    {
        return Subnet::leastSelected()->of($this)->first();
    }
}