<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use IPTools\Network;
use Webpatser\Uuid\Uuid;

use App\Observers\VpnUserObserver;

class VpnUser extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'vpn_users';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Enable timestamp for model execution
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'username',
        'config_content',
        'vpn_subnet',
        'assigned_subnet_ip',
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
        self::observe(VpnUserObserver::class);

    	self::creating(function ($vpnUser) {
            $vpnUser->id = isset($vpnUser->id) ? 
                $vpnUser->id : generateUuid();
    	});
    }

    /**
     * Create callable attribute of "decoded_config_content"
     * This callable attribute will return the decoded config content
     * of the VPN User
     * 
     * @return string
     */
    public function getDecodedConfigContentAttribute()
    {
        $content = $this->attributes['config_content'];
        return base64_decode($content);
    }

    /**
     * Create settable attribute of "encoded_config_content"
     * This settable attribute will set the value of the "config_content"
     * 
     */
    public function setEncodedConfigContentAttribute(string $content)
    {
        if (! base64_decode($content)) {
            $content = base64_encode($content);
        }

        $this->attributes['config_content'] = $content;
    }

    /**
     * Create settable attribute of "vpn_subnet"
     * This settable attribute will set the subnet of the vpn user
     * 
     * @param  string  $subnet
     * @return  void
     */
    public function setVpnSubnetAttribute(string $subnet)
    {
        $this->attributes['vpn_subnet'] = $subnet;

        if (! isset($this->attributes['id'])) {
            $this->attributes['id'] = generateUuid();
        }

        $id = $this->attributes['id'];
        $this->attributes['assigned_subnet_ip'] = $this->findFreeSubnetIp($id, $subnet);
    }

    /**
     * Get the container of the vpn user
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Find free subnet ip
     * 
     * @param  string  $id
     * @param  string  $subnet
     * @return  string|null
     */
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

    /**
     * Find VPN User in container with specified username
     * 
     * @param  \App\Models\Container  $container
     * @param  string  $username
     * @param  bool  $abortNotFound
     * @return 
     */
    public static function findInContainer(
        Container $container, 
        string $username,
        bool $abortNotFound = false
    ) {
        $query = self::where('container_id', $container->id)->where('username', $username)
        return $abortNotFound ? $query->firstOrFail() : $query->first();
    }
}