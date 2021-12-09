<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Setting extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'settings';

    /**
     * Model primary key
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
        'key',
        'value',
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

    	self::creating(function ($setting) {
            $setting->id = Uuid::generate()->string;
    	});
    }

    /**
     * Collect rabbit mq settings
     * 
     * @param bool  $autoEnv
     * @return array
     */
    public static function rabbitMQSettings(bool $autoEnv = false)
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