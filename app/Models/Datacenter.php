<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Datacenter\DatacenterStatus;

class Datacenter extends Model
{
    use Searchable;

    protected $table = 'datacenters';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'datacenter_name',
        'client_datacenter_name',
        'location',
    ];

    protected $fillable = [
        'region_id',
        
        'datacenter_name',
        'client_datacenter_name',
        'location',
        'status',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($datacenter) {
            $datacenter->id = Uuid::generate()->string;
    	});
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return DatacenterStatus::getDescription($status);
    }

    public function switchStatus()
    {
        $status = (bool) $this->attributes['status'];
        $this->attributes['status'] = (! $status);

        return $this->save();
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function subnets()
    {
        return $this->hasMany(Subnet::class);
    }

    public function servers()
    {
        return $this->hasMany(Server::class);
    }
}