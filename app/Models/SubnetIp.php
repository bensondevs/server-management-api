<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubnetIpObserver as Observer;
use App\Enums\SubnetIp\SubnetIpStatus as Status;

class SubnetIp extends Model
{
    use Searchable;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'subnet_ips';

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
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'subnet_id',
        'user_id',
        'status',
        'comment',
    ];

    /**
     * Model default hidden column
     * 
     * @var array
     */
    protected $hidden = [
        'ip_binary'
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
     * The assigned user of thie subnet ip
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Container using this subnet
     */
    public function container()
    {
        return $this->hasOne(Container::class);
    }

    /**
     * Create callable function of "free()"
     * This callable function will make model query only 
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFree(Builder $query)
    {
        return $query->where('status', Status::Free);
    }

    /**
     * Create settable attribute of "ip_address"
     * This settable attribute will set "ip_binary" using IP address
     * of string
     * 
     * @param string  $ipAddress
     * @return void
     */
    public function setIpAddressAttribute(string $ipAddress)
    {
        $this->attributes['ip_binary'] = inet_pton($ipAddress);
    }

    /**
     * Create callable attribute of "ip_address"
     * This callable attribute will get "ip_binary" value as string
     * which contain the value of IP Address
     * 
     * @return string
     */
    public function getIpAddressAttribute()
    {
        return inet_ntop($this->attributes['ip_binary']);
    }

    /**
     * Create callable attribute of "is_usable"
     * This callable attribute will return boolean status of 
     * subnet ip usability
     * 
     * @return bool
     */
    public function getIsUsableAttribute()
    {
        return $this->attributes['status'] == Status::Free;
    }

    /**
     * Assign current subnet ip to the user
     * 
     * @param \App\Models\User  $user
     * @return bool
     */
    public function assignTo(User $user)
    {
        $this->attributes['user_id'] = $user->id;
        $this->attributes['status'] = Status::Assigned;
        return $this->save();
    }

    /**
     * Revoke user from the subnet ip
     * 
     * This will set the subnet ip free
     * 
     * @return bool
     */
    public function revokeUser()
    {
        $this->attributes['user_id'] = null;
        $this->attributes['status'] = Status::Free;
        return $this->save();
    }

    /**
     * Set the subnet ip as forbidden
     * 
     * @return bool
     */
    public function setForbidden()
    {
        $this->attributes['status'] = Status::Forbidden;
        return $this->save();
    }

    /**
     * Set the subnet ip as free or assigned
     * 
     * @return bool
     */
    public function setUnforbidden()
    {
        $this->attributes['status'] = ($this->attributes['user_id']) ?
            Status::Assigned :
            Status::Free;
            
        return $this->save();
    }
}