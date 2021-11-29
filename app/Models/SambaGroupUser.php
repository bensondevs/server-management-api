<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaGroupUser extends Model
{
    protected $table = 'samba_group_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $with = ['group', 'user'];

    protected $fillable = [
        'container_id',
        'samba_group_id',
        'samba_user_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($sambaGroupUser) {
            $sambaGroupUser->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function group()
    {
        return $this->belongsTo(SambaGroup::class, 'samba_group_id');
    }

    public function user()
    {
        return $this->belongsTo(SambaUser::class, 'samba_user_id');
    }
}