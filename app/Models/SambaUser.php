<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\SambaUserObserver as Observer;

class SambaUser extends Model
{
    use HasFactory;
    
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_users';

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
        'username',
        'password',
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
     * Create callable method of 
     * `whereInContainer(\App\Models\Container $container)`
     * This callable method will only query SambaUser where belongs to a certain
     * specified container in the method parameter
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereInContainer(Builder $builder, Container $container)
    {
        return $builder->where('container_id', $container->id);
    }

    /**
     * Create callable method of `whereNotInGroup(\App\Models\SambaGroup $group)`
     * This callable method will return only user where's not belong to certain
     * group specified at the method parameter.
     * 
     * @param  Illuminate\Database\Eloquent\Builder  $builder
     * @param  \App\Models\SambaGroup  $group
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNotInGroup(Builder $builder, SambaGroup $group)
    {
        return $builder->whereHas('groups', function (Builder $query) use ($group) {
            $query->where('samba_groups.id', '!=', $group->id);
        });
    }

    /**
     * Get container of the samba user
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get shares owned by the user
     */
    public function shares()
    {
        return $this->belongsToMany(SambaShare::class, SambaShareUser::class);
    }

    /**
     * Get groups that joined by the user
     */
    public function groups()
    {
        return $this->belongsToMany(SambaGroup::class, SambaGroupUser::class);
    }

    /**
     * Get user group of the samba user
     */
    public function userGroup()
    {
        $username = $this->attributes['username'];
        return $this->hasOneThrough(SambaGroup::class, SambaGroupUser::class)
            ->where('group_name', $username);
    }

    /**
     * Create settable attribute of "unencrypted_password"
     * This settable attribute will set the plain string password
     * to encrypted password
     * 
     * @param  string  $password
     * @return  void
     */
    public function setUnencryptedPasswordAttribute(string $password)
    {
        $this->attributes['password'] = encryptString($password);
    }

    /**
     * Create callable attribute of "decrypted_password"
     * This callable attribute will get the encrypted password
     * as decrypted plain string password
     * 
     * @return  string
     */
    public function getDecryptedPasswordAttribute()
    {
        $encrypted = $this->attributes['password'];
        return decryptString($encrypted);
    }

    /**
     * Find samba user in container using certain username
     * 
     * @param  \App\Models\Container  $container
     * @param  string  $username
     * @param  bool  $abortNotFound
     * @return  \App\Models\SambaUser|null|abort 404
     */
    public static function findInContainer(
        Container $container, 
        string $username,
        bool $abortNotFound = false
    ) {
        $query = self::where('container_id', $container->id)->where('username', $username);
        return $abortNotFound ? $query->firstOrFail() : $query->first();
    }

    /**
     * Check if a certain user is exists in container
     * 
     * @param  \App\Models\Container  $container
     * @param  string  $username
     * @return  bool
     */
    public static function isExistsInContainer(Container $container, string $username)
    {
        $query = self::where('container_id', $container->id)->where('username', $username);
        return $query->exists();
    }
}