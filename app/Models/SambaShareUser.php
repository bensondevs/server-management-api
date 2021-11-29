<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaShareUser extends Model
{
    protected $table = 'samba_share_users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $with = ['share', 'user'];

    protected $fillable = [
        'container_id',
        'samba_share_id',
        'samba_user_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($sambaShareUser) {
            $sambaShareUser->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function share()
    {
        return $this->belongsTo(SambaShare::class);
    }

    public function user()
    {
        return $this->belongsTo(SambaUser::class);
    }
}