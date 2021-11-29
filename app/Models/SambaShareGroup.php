<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SambaShareGroup extends Model
{
    protected $table = 'samba_share_groups';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $with = ['group', 'share'];

    protected $fillable = [
        'container_id',
        'samba_group_id',
        'samba_share_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($sambaShareGroup) {
            $sambaShareGroup->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function group()
    {
        return $this->belongsTo(SambaGroup::class);
    }

    public function share()
    {
        return $this->belongsTo(SambaShare::class);
    }
}