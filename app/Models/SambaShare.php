<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaShare extends Model
{
    /**
     * 
     */
    protected $table = 'samba_shares';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'samba_directory_id',
        'samba_user_id',
        'share_name',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($sambaShare) {
            $sambaShare->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public static function findInContainer(Container $container, $shareName)
    {
        return self::where('container_id', $container->id)
            ->where('share_name', $shareName)
            ->first();
    }

    public function groups()
    {
        return $this->belongsToMany(SambaGroup::class, SambaShareGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(SambaUser::class, SambaShareUser::class);
    }
}