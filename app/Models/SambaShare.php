<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaShare extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_shares';

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
        'samba_directory_id',
        'samba_user_id',
        'share_name',
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

    	self::creating(function ($sambaShare) {
            $sambaShare->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get container of the samba share
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get groups under current samba share
     */
    public function groups()
    {
        return $this->belongsToMany(SambaGroup::class, SambaShareGroup::class);
    }

    /**
     * Get samba share users
     */
    public function users()
    {
        return $this->belongsToMany(SambaUser::class, SambaShareUser::class);
    }

    /**
     * Find samba share in container using share name
     * 
     * @param \App\Models\Container  $container
     * @param string  $shareName
     * @return \App\Models\SambaShare|null
     */
    public static function findInContainer(
        Container $container, 
        string $shareName, 
        bool $abortNotFound = false
    ) {
        $query = self::where('container_id', $container->id)
            ->where('share_name', $shareName);
        return $abortNotFound ? $query->firstOrFail() : $query->first();
    }
}