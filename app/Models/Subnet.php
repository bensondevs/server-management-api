<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Models\SubnetIp;

use App\Observers\SubnetObserver;

use App\Enums\Subnet\SubnetStatus;

use IPTools\Network;

class Subnet extends Model
{
    use Searchable;

    protected $table = 'subnets';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'datacenter_id',
        'status',
        'subnet_mask',
    ];

    protected $searchable = [
        'subnet_mask',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(SubnetObserver::class);

    	self::creating(function ($subnet) {
            $subnet->id = Uuid::generate()->string;
    	});
    }

    public function scopeActive($query)
    {
        return $query->where('status', SubnetStatus::Active);
    }

    public function scopeWithCountIps($query)
    {
        return $query->withCount([
            'ips', 
            'ips as total_available_ips' => function ($ip) {
                $ip->whereNull('assigned_user_id');
            }
        ]);
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return SubnetStatus::getDescription($status);
    }

    public function ips()
    {
        return $this->hasMany(SubnetIp::class);
    }

    public function freeIps()
    {
        return $this->hasMany(SubnetIp::class)->whereNull('assigned_user_id');
    }

    public function datacenter()
    {
        return $this->belongsTo(Datacenter::class);
    }

    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    public function createIps()
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

    public function switchForbidden()
    {
        $this->attributes['is_forbidden'] = (! $this->attributes['is_forbidden']);
        return $this->save();
    }
}