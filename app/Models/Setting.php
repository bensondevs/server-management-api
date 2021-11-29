<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($setting) {
            $setting->id = Uuid::generate()->string;
    	});
    }

    public static function rabbitMQSettings($autoEnv = false)
    {
        $configurations = config('rabbitmq.default');

        if ($autoEnv) return $configurations;

        $settings = self::where('key', 'like', '%rabbitmq_%')->get();
        $connectionConfig = $settings->where('key', 'config_rabbitmq_connection')
            ->first()
            ->value;
        if ($connectionConfig == 'database') {
            $configurations = [];

            foreach ($settings as $setting) {
                $key = str_replace('rabbitmq_', '', $setting->key);
                $key = strtolower($key);

                $configurations[$key] = $setting->value;
            }
        }

        return $configurations;
    }
}