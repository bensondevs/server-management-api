<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Models\Datacenter;

use App\Observers\ServerObserver;

class Server extends Model
{
    protected $table = 'servers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'server_name',
        'datacenter_id',
        'status',
    ];

    protected $hidden = [
        'ip_binary'
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(ServerObserver::class);

        self::retrieved(function ($server) {
            $server->complete_server_name = $server->complete_server_name;
        });

    	self::creating(function ($server) {
            $server->id = Uuid::generate()->string;
    	});
    }

    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    public function getIpAddressAttribute()
    {
        return inet_ntop($this->attributes['ip_binary']);
    }

    public function getCompleteServerNameAttribute()
    {
        $datacenter = Datacenter::with('region')->find(
            $this->attributes['datacenter_id']
        );
        $region = $datacenter->region;

        $completeName = '';
        if ($region) 
            $completeName .= $region->region_name . '-';

        if ($datacenter)
            $completeName .= $datacenter->datacenter_name . '-';
        $completeName .= $this->attributes['server_name'];

        return $completeName;
    }

    public function toggleStatus()
    {
        $this->attributes['status'] = ($this->attributes['status'] != 'active') ?
                'active' : 'inactive';
        return $this->save();
    }

    public function datacenter()
    {
        return $this->belongsTo(
            'App\Models\Datacenter', 
            'datacenter_id',
            'id'
        );
    }

    public function containers()
    {
        return $this->hasMany(
            'App\Models\Container',
            'server_id',
            'id'
        );
    }
}