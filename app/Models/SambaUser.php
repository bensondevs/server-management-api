<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Observers\SambaUserObserver;

class SambaUser extends Model
{
    protected $table = 'samba_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'username',
        'password',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(SambaUserObserver::class);

    	self::creating(function ($sambaUser) {
            $sambaUser->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function shares()
    {
        return $this->belongsToMany(SambaShare::class, SambaShareUser::class);
    }

    public function groups()
    {
        return $this->belongsToMany(SambaGroup::class, SambaGroupUser::class);
    }

    public function userGroup()
    {
        return $this->hasOneThrough(SambaGroup::class, SambaGroupUser::class)
            ->where('group_name', $this->attributes['username']);
    }

    public function setUnencryptedPasswordAttribute(string $password)
    {
        $this->attributes['password'] = encryptString($password);
    }

    public function getDecryptedPasswordAttribute()
    {
        $encryptedPassword = $this->attributes['password'];
        return decryptString($encryptedPassword);
    }

    public static function findInContainer(Container $container, $username)
    {
        return self::where('container_id', $container->id)
            ->where('username', $username)
            ->first();
    }

    public static function isExistsInContainer(string $username, Container $container)
    {
        return self::where('container_id', $container->id)
            ->where('username', $username)
            ->count() > 0;
    }
}