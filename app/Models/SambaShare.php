<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\SambaShareObserver as Observer;

class SambaShare extends Model
{
    use HasFactory;

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
        'share_name',
        'share_config_content',
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
     * Create callable attribute of "config_content"
     * This callable attribute will return samba share config content
     * as array
     * 
     * @return string
     */
    public function getConfigContentAttribute()
    {
        $content = $this->attributes['share_config_content'];
        return json_decode($content, true);
    }

    /**
     * Create settable attribute of "config_content"
     * This settable attribute will set the "samba_config_content" column
     * as json string using array as input
     * 
     * @param  array  $configContent
     * @return void
     */
    public function setConfigContentAttribute(array $configContent)
    {
        $this->attributes['share_config_content'] = json_encode($configContent);
    }

    /**
     * Create settable attribute of "permissions_attribute"
     * This settavle attribute will set the "permissions" column as
     * string of permissions using array of input
     * 
     * @param  array  $permissions
     * @return void
     */
    public function setPermissionsArrayAttribute(array $permissions)
    {
        $stringPermissions = '';
        foreach (array_unique($permissions) as $permission) {
            switch (strtolower($permission)) {
                case 'public':
                    $stringPermissions .= 'p';
                    break;

                case 'read':
                    $stringPermissions .= 'r';
                    break;

                case 'write':
                    $stringPermissions .= 'w';
                    break;

                case 'p':
                    $stringPermissions .= 'p';
                    break;

                case 'r':
                    $stringPermissions .= 'r';
                    break;

                case 'w':
                    $stringPermissions .= 'w';
                    break;
                
                default:
                    //
                    break;
            }
        }
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