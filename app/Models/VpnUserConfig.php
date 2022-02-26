<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Models\Container;

class VpnUserConfig extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'vpn_user_configs';

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
     * Model mass filable columns
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'username',
        'config_content',
    ];

    /**
     * Model event handler method
     * 
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($config) {
            $config->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable attribute of `decode_config_content`
     * This callable attribute will return base64 version of
     * `config_content` from the database
     * 
     * @return string
     */
    public function getDecodedConfigContentAttribute()
    {
        $content = $this->attributes['config_content'];
        return base64_decode($content);
    }

    /**
     * Get VPN User Config container
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get VPN User config from container
     * 
     * @static
     * @param  \App\Models\Container  $container
     * @param  string  $username
     * @return self
     */
    public static function findInContainer(Container $container, string $username)
    {
        $config = self::where('container_id', $container->id)
            ->where('username', $username)
            ->first();

        return $config;
    }
}