<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use IPTools\Network;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'datacenter_id',
        'server_id',
        'subnet_id',
        'hostname',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
    }

    public function generate_id($ipAddress)
    {
        $tmp = explode('.', $ipAddress);
        $ip3 = str_repeat('0', 3 - strlen($tmp[2])) . $tmp[2];
        $ip4 = str_repeat('0', 3 - strlen($tmp[3])) . $tmp[3];

        return '9' . $ip3 . $ip4;
    }

    public function getIpAddressAttribute()
    {
        return inet_ntop($this->attributes['ip_binary']);
    }

    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
        $this->attributes['id'] = $this->generate_id($ipAddress);
    }

    public function datacenter()
    {
        return $this->hasOne(
            'App\Models\Datacenter', 
            'id',
            'datacenter_id'
        );
    }

    public function server()
    {
        return $this->hasOne(
            'App\Models\Server',
            'id',
            'server_id'
        );
    }
}