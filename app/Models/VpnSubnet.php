<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class VpnSubnet extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'vpn_user_subnets';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model timestamps enability
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model fillable columns
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'subnet',
    ];

    /**
     * Model event handler method
     * 
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($vpnSubnet) {
            $vpnSubnet->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create static method existsIn() to query only container subnets
     * 
     * @param mixed \App\Models\Container|string  $container
     * @return Illuminate\Database\Eloquent\Builder 
     */
    public function scopeExistsIn($container)
    {
        if (is_string($container)) {
            $container = Container::findOrFail($container);
        }

        return $query->where('container_id', $container->id);
    }

    /**
     * Check if subnet already exists in container
     * 
     * @param mixed  $container
     * @param string  $subnet
     * @return bool
     */
    public static function subnetExist($container, string $subnet)
    {
        if (! is_string($container)) {
            $container = $container->id;
        }

        return self::where('container_id', $container)
            ->where('subnet', $subnet)
            ->exists();
    }

    /**
     * Get VPN Subnet Container
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get VPN Users using this model
     */
    public function vpnUsers()
    {
        return $this->hasMany(VpnUser::class, 'vpn_subnet', 'subnet');
    }
}