<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubnetObserver as Observer;
use App\Enums\Subnet\SubnetStatus as Status;
use IPTools\Network;

class Subnet extends Model
{
    use Searchable;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'subnets';

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
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'datacenter_id',
        'status',
        'subnet_mask',
    ];

    /**
     * Model searchable columns
     * 
     * @var array
     */
    protected $searchable = [
        'subnet_mask',
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

    	self::creating(function ($subnet) {
            $subnet->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable function of "available()"
     * This callable function will query only subnet with status of
     * available
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('status', Status::Available);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable function will return enum description of the status
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Get IPs under this subnet
     */
    public function ips()
    {
        return $this->hasMany(SubnetIp::class);
    }

    /**
     * Get free IPs under this subnet
     */
    public function freeIps()
    {
        return $this->hasMany(SubnetIp::class)->whereNull('assigned_user_id');
    }

    /**
     * Get the datacenter of the subnet
     */
    public function datacenter()
    {
        return $this->belongsTo(Datacenter::class);
    }

    /**
     * Get the containers under the subnet
     */
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * Genearte IPs of the subnet according to network parse
     * 
     * @return bool
     */
    public function generateIps()
    {
        $subnetMask = $this->attributes['subnet_mask'];
        $hosts = Network::parse($subnetMask)->hosts;
        $availableIps = [];
        foreach ($hosts as $ip) {
            array_push($availableIps, [
                'id' => generateUuid(),
                'subnet_id' => $this->attributes['id'],
                'ip_binary' => inet_pton($ip),
                'comment' => '',
                'created_at' => carbon()->now(),
            ]);
        }

        return SubnetIp::insert($availableIps);
    }

    /**
     * Select one free ip of the current subnet's children.
     * 
     * If not found, this method will return NULL as result
     * 
     * @return \App\Models\SubnetIp|null 
     */
    public function selectFreeIp()
    {
        return SubnetIp::of($this)->free()->first();
    }

    /**
     * Check if subnet status is unavailable
     * 
     * @return bool
     */
    public function isUnavailable()
    {
        $status = $this->attributes['status'];
        return $status === Status::Unavailable;
    }

    /**
     * Check if subnet status is forbidden
     * 
     * @return bool
     */
    public function isForbidden()
    {
        $status = $this->attributes['status'];
        return $status === Status::Forbidden;
    }

    /**
     * Set the subnet as available
     * 
     * This will make the subnet assignable to certain user.
     * This also will retry the creation of pre-created containers
     * which in waiting list for reason of no subnet available
     * 
     * @return  bool
     */
    public function setAvailable()
    {
        $this->attributes['status'] = Status::Available;
        return $this->save();
    }

    /**
     * Set the subnet as forbidden.
     * 
     * This will prohibit the subnet to be used.
     * 
     * @return bool
     */
    public function setForbidden()
    {
        $this->attributes['status'] = Status::Forbidden;
        return $this->save();
    }
}