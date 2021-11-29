<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaGroup extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_groups';

    /**
     * Model database primary key column name
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
     * Model default loaded relationships
     * 
     * @var array
     */
    protected $with = ['users', 'shares'];

    /**
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'container_id',
        'group_name',
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

    	self::creating(function ($sambaGroup) {
            $sambaGroup->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create from() static method to query only samba group from container
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\Container  $container 
     */
    public function scopeFrom(Builder $query, Container $container)
    {
        return $query->where('container_id', $container->id);
    }

    /**
     * Find samba group in container using name
     * 
     * @param \App\Models\Container  $container
     * @param string  $groupName
     * @return \App\Models\SambaGroup
     */
    public function findInContainer(Container $container, string $groupName)
    {
        return self::where('container_id', $container->id)
            ->where('group_name', $groupName)
            ->first();
    }

    /**
     * Add samba group to current group
     * 
     * @param \App\Models\SambaUser  $sambaUser
     * @return \App\Models\SambaGroup
     */
    public function addUser(SambaUser $sambaUser)
    {
        return SambaGroupUser::create([
            'container_id' => $this->attributes['container_id'],
            'samba_group_id' => $this->attributes['id'],
            'samba_user_id' => $sambaUser->id,
        ]);
    }

    /**
     * Remove samba user from current group
     * 
     * @param \App\Models\SambaUser  $sambaUser
     * @return bool
     */
    public function removeUser(SambaUser $sambaUser)
    {
        return SambaGroupUser::where('container_id', $sambaUser->container_id)
            ->where('username', $sambaUser->username)
            ->first()
            ->delete();
    }

    /**
     * Get container of the samba group
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get samba group accessible shares
     */
    public function shares()
    {
        return $this->belongsToMany(
            SambaShare::class, 
            SambaShareGroup::class
        );
    }

    /**
     * Get samba group users
     */
    public function users()
    {
        return $this->belongsToMany(
            SambaUser::class, 
            SambaGroupUser::class
        );
    }

    /**
     * Check group name exists in container
     * 
     * @param string  $groupName
     * @param \App\Models\Container  $container
     * @return bool
     */
    public static function isExistsInContainer(string $groupName, Container $container)
    {
        return self::where('container_id', $container->id)
            ->where('group_name', $groupName)
            ->exists();
    }
}