<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Models\User;

use App\Observers\SubnetIpObserver;

class SubnetIp extends Model
{
    use Searchable;

    protected $table = 'subnet_ips';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'subnet_id',
        'assigned_user_id',
        'comment',
    ];

    protected $searchable = [
        'ip_binary',
        'comment',
    ];

    protected $hidden = [
        'ip_binary'
    ];

    protected $casts = [
        'is_forbidden' => 'boolean',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(SubnetIpObserver::class);

    	self::creating(function ($subnetIP) {
            $subnetIP->id = Uuid::generate()->string;
    	});
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class);
    }

    public function container()
    {
        return $this->hasOne(Container::class, 'subnet_ip_id');
    }

    public function scopeAssignable($query)
    {
        return $query->where('is_forbidden', false)->whereNull('assigned_user_id');
    }

    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    public function getIpAddressAttribute()
    {
        return inet_ntop($this->attributes['ip_binary']);
    }

    public function getIsNotForbiddenAttribute()
    {
        return (! $this->attributes['is_forbidden']);
    }

    public function getIsUsableAttribute()
    {
        return 
            (! $this->attributes['assigned_user_id']) &&
            (! $this->attributes['is_forbidden']);
    }

    public function assignTo(User $user)
    {
        $this->attributes['assigned_user_id'] = $user->id;
        return $this->save();
    }

    public function revokeUser()
    {
        $this->attributes['assigned_user_id'] = null;
        return $this->save();
    }

    public function setForbidden()
    {
        $this->attributes['is_forbidden'] = true;
        return $this->save();
    }
}