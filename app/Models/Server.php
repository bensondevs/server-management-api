<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use Znck\Eloquent\Traits\BelongsToThrough;

use App\Observers\ServerObserver as Observer;
use App\Enums\Server\ServerStatus as Status;

class Server extends Model
{
    use HasFactory;
    use BelongsToThrough;
    
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'servers';

    /**
     * Model database primary key column name
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
     * Model massive fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'server_name',
        'datacenter_id',
        'status',
    ];

    /**
     * Model hidden column value
     * 
     * @var arra
     */
    protected $hidden = [
        'ip_binary'
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
     * This callable methid will order result by amount of
     * container relation attached with this server.
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
     * Creata callable method of "of(Datacenter $datacenter)"
     * This callable method will query only server under specified datacenter
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Datacenter  $datacenter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOf(Builder $query, Datacenter $datacenter)
    {
        return $query->where('datacenter_id', $datacenter->id);
    }

    /**
     * Create settable attribute of "ip_address"
     * This settable attribute will set "ip_binary" using IP address
     * of string
     * 
     * @param string  $ipAddress
     * @return void
     */
    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    /**
     * Create callable attribute of "ip_address"
     * This callable attribute will get "ip_binary" value as string
     * which contain the value of IP Address
     * 
     * @return string
     */
    public function getIpAddressAttribute()
    {
        return inet_ntop($this->attributes['ip_binary']);
    }

    /**
     * Create callable attribute of "full_server_name"
     * This callable attribute will return full name of the server
     * with addition to it's prefix with datacenter name and region name
     *
     * @return string
     */
    public function getFullServerNameAttribute()
    {
        $fullName = $this->attributes['server_name'];

        if ($region = $this->region) {
            $fullName = $region->region_name . ' | ' . $fullName;
        }

        if ($datacenter = $this->datacenter) {
            $fullName = $datacenter->datacenter_name . ' | ' . $fullName;
        }

        return $fullName;
    }

    /**
     * Create callable attribute of "queue_server_name"
     * This callable attribute will return queue server name of server
     * with structure of {Region}-{Datacenter Name}-{Server Name}
     * 
     * @return string
     */
    public function getQueueServerNameAttribute()
    {
        $datacenter = $this->datacenter;
        
        $regionName = $datacenter->region->region_name;
        $datacenterName = $datacenter->datacenter_name;
        $serverName = $this->attributes['server_name'];

        return $regionName . '-' . $datacenterName . '-' . $serverName;
    }

    /**
     * Get region of the server
     */
    public function region()
    {
        return $this->belongsToThrough(Region::class, Datacenter::class);
    }

    /**
     * Get datacenter of the server
     */
    public function datacenter()
    {
        return $this->belongsTo(Datacenter::class);
    }

    /**
     * Get all containers inside the server
     */
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * Check if status of the server active
     * 
     * @return bool
     */
    public function isActive()
    {
        $status = $this->attributes['status'];
        return $status === Status::Active;
    }

    /**
     * Check if status of the server is inactive
     * 
     * @return bool
     */
    public function isInactive()
    {
        $status = $this->attributes['status'];
        return $status === Status::Inactive;
    }

    /**
     * Set server status
     * 
     * This function will not send any update to the real server
     * only recorded status in local database
     * 
     * @return bool|int
     */
    public function setStatus(int $status = 1, bool $save = true)
    {
        $this->attributes['status'] = $status;
        return $save ? $this->save() : $status;
    }
}