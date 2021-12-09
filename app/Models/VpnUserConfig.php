<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Models\Container;

class VpnUserConfig extends Model
{
    protected $table = 'vpn_user_configs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'username',
        'config_content',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($config) {
            $config->id = Uuid::generate()->string;
    	});
    }

    public function getDecodedConfigContentAttribute()
    {
        $content = $this->attributes['config_content'];
        return base64_decode($content);
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public static function findInContainer(Container $container, string $username)
    {
        $config = self::where('container_id', $container->id)
            ->where('username', $username)
            ->first();

        return $config;
    }
}