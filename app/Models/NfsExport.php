<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class NfsExport extends Model
{
    protected $table = 'nfs_exports';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $with = ['folder'];

    protected $fillable = [
        'container_id',
        'nfs_folder_id',
        'permissions',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($nfsExport) {
            $nfsExport->id = Uuid::generate()->string;
    	});
    }

    public function getIpAddressAttribute()
    {
        $ipBinary = $this->attributes['ip_binary'];
        return inet_ntop($ipBinary);
    }

    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    public function getPermissionsArrayAttribute()
    {
        $permissions = $this->attributes['permissions'];

        $permissionsArray = [];
        if (stripos($permissions, 'r') !== false) {
            array_push($permissionsArray, 'r');
        }
        
        if (stripos($permissions, 'w') !== false) {
            array_push($permissionsArray, 'w');
        }

        return $permissionsArray;
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function folder()
    {
        return $this->belongsTo(NfsFolder::class, 'nfs_folder_id', 'id');
    }
}