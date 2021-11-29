<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use IPTools\Network;

use App\Observers\VpnUserObserver;

class VpnUser extends Model
{
    protected $table = 'vpn_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'username',
        'config_content',
        'vpn_subnet',
        'assigned_subnet_ip',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(VpnUserObserver::class);

    	self::creating(function ($vpnUser) {
            $vpnUser->id = isset($vpnUser->id) ? 
                $vpnUser->id :
                Uuid::generate()->string;
    	});
    }

    public function getDecodedConfigContentAttribute()
    {
        $content = $this->attributes['config_content'];
        return base64_decode($content);
    }

    public function setEncodedConfigContentAttribute(string $content)
    {
        if (! base64_decode($content)) {
            $content = base64_encode($content);
        }

        $this->attributes['config_content'] = $content;
    }

    public function setVpnSubnetAttribute(string $subnet)
    {
        $this->attributes['vpn_subnet'] = $subnet;

        if (! isset($this->attributes['id'])) {
            $this->attributes['id'] = generateUuid();
        }

        $id = $this->attributes['id'];
        $this->attributes['assigned_subnet_ip'] = $this->findFreeSubnetIp($id, $subnet);
    }

    public static function findFreeSubnetIp(string $id, string $subnet)
    {
        // Count the index of IP that is used
        $totalUnavailableIps = 1; // First Subnet IP is the container itself
        $totalUsedIps = self::where('container_id', $id)
            ->where('vpn_subnet', $subnet)
            ->count();
        $totalUnavailableIps += $totalUsedIps;

        // Loop through the hosts, and find the free IP
        $subnetIps = Network::parse($subnet)->hosts;
        foreach ($subnetIps as $index => $subnetIp) {
            if ($totalUnavailableIps < ($index + 1)) {
                return inet_ntop(inet_pton($subnetIp));
            }
        }

        // Not found?
        foreach ($subnetIps as $index => $subnetIp) {
            if ($index > 0) {
                return inet_ntop(inet_pton($subnetIp));
            }
        }
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public static function findInContainer(Container $container, string $username)
    {
        return self::where('container_id', $container->id)
            ->where('username', $username)
            ->first();
    }
}