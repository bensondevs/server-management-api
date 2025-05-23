<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\NfsExportObserver as Observer;

class NfsExport extends Model
{
    use HasFactory;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'nfs_exports';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Enable timestamp for model execution
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * The relationship that should be loaded each time
     * the model is retrieved
     * 
     * @return array
     */
    protected $with = ['folder'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['ip_binary'];

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'nfs_folder_id',
        'permissions',
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
        self::observe(Observer::class);
    }

    /**
     * Create callable attribute of "ip_address"
     * This callable attribute will return ip address
     * which stored in binary as string
     * 
     * @return string
     */
    public function getIpAddressAttribute()
    {
        $ipBinary = $this->attributes['ip_binary'];
        return inet_ntop($ipBinary);
    }

    /**
     * Create settable attribute of "ip_address"
     * This callable attribute will set the value of IP Binary
     * using the string of IP Address
     * 
     * @param  string  $ipAddress
     * @return  void
     */
    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    /**
     * Create callable attribute of "permissions_array"
     * This callable attribute will return array of permission
     * into this NFS Export
     * 
     * @return array
     */
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

    /**
     * Get container which NFS Export running
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get folder which shared through NFS Export
     */
    public function folder()
    {
        return $this->nfsFolder();
    }

    /**
     * Get alias of nfs folder
     */
    public function nfsFolder()
    {
        return $this->belongsTo(NfsFolder::class);
    }
}