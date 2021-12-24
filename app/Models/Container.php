<?php

namespace App\Models;

use Illuminate\Support\Facades\{ DB, Cache };
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\ContainerObserver as Observer;
use App\Repositories\ContainerRepository;
use App\Models\{
    ContainerProperty as Property,
    ContainerAdditionalProperty as AdditionalProp
};
use App\Traits\{ Searchable, TrackInQueue };
use App\Traits\Container\{
    VpnTrait,
    NfsTrait,
    SambaTrait,
    NginxTrait
};
use App\Enums\Container\{
    ContainerStatus as Status,
    ContainerOnServerStatus as StatusOnServer
};
use App\Enums\ContainerProperty\{
    ContainerPropertyType as PropertyType
};

class Container extends Model
{
    use HasFactory;
    use Searchable, TrackInQueue;
    use VpnTrait, NfsTrait, SambaTrait, NginxTrait;

    /**
     * Model database table name
     * 
     * @var string
     */
    protected $table = 'containers';

    /**
     * Model database table primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp marking enability
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * ID Incrementing enability
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Database table searchable columns
     * 
     * @var array
     */
    protected $searchable = [
        'hostname',
        'client_email',
    ];

    /**
     * Database table fillable columns
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'server_id',
        'subnet_id',
        'subnet_ip_id',

        'hostname',
        'total_amount',
        'client_email',

        'current',
        'status',
        'status_on_server',

        'created_on_server_at',
        'system_installed_at',

        'vpn_status',
        'vpn_pid_numbers',
        'vpn_enability',

        'samba_smbd_status',
        'samba_nmbd_status',
        'samba_pid_numbers',
        'samba_smbd_enability',
        'samba_nmbd_enability',

        'nfs_status',
        'nfs_pid_numbers',
        'nfs_enability',

        'nginx_status',
        'nginx_pid_numbers',
        'nginx_enability',
    ];

    /**
     * Model event handler function
     * 
     * @return void 
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Generate Container ID based on IP Address
     * 
     * @param string  $ipAddress
     * @return string
     */
    public static function generate_id(string $ipAddress)
    {
        $tmp = explode('.', $ipAddress);
        $ip3 = str_repeat('0', 3 - strlen($tmp[2])) . $tmp[2];
        $ip4 = str_repeat('0', 3 - strlen($tmp[3])) . $tmp[3];

        return '9' . $ip3 . $ip4;
    }

    /**
     * Model static helper to generate string
     * 
     * @param string  $ipAddress
     * @return string
     */
    public static function generateId(string $ipAddress) 
    {
        return self::generate_id($ipAddress);
    }

    /**
     * Create ownedBy() static method to query based on container user
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\User  $user
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Create expired() static method to query only expired container
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired(Builder $query)
    {
        $now = carbon()->now()->toDatetimeString();
        return $query->where('expiration_date', '<=', $now);
    }

    /**
     * Create active() static method to query only active container
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereNotNull('system_installed_at');
    }

    /**
     * Create current() static method to query only user current selected container
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent(Builder $query)
    {
        return $query->where('current', true);
    }

    /**
     * Get enum description using status value
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Get assigned ip address in string
     * 
     * @return string
     */
    public function getAssignedIpAddressAttribute()
    {
        $subnetIpId = $this->attributes['subnet_ip_id'];
        $ip = SubnetIp::where('subnet_ip_id', $subnetIpId)->first();

        return $ip ? $ip->ip_address : null;
    }

    /**
     * Get user of the container
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get server where container is existing 
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Get container assigned subnet
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subnet()
    {
        return $this->belongsTo(Subnet::class);
    }

    /**
     * Get container assigned subnet ip
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subnetIp()
    {
        return $this->belongsTo(SubnetIp::class);
    }

    /**
     * Get container properties
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(ContainerProperty::class);
    }

    /**
     * Get order record that's creating this container
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get subscription of the container
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneMorph
     */
    public function subscription()
    {
        return $this->morphOne(Subscription::class, 'subscriber');
    }

    /**
     * Get attached service plan of container
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicePlan()
    {
        return $this->hasOneThrough(ServicePlan::class, Subscription::class);
    }

    /**
     * Get service plan of the container
     * 
     * @return \App\Models\ServicePlan
     */
    public function getServicePlan()
    {
        $subscription = Subscription::subscribedBy($this)
            ->active()
            ->first();
        if (! $subscription) return ServicePlan::first();

        $servicePlan = ServicePlan::where('subscribeable_type', $subscription->subscribeable_type)
            ->where('subscribeable_id', $subscription->subscribeable_id)
            ->first();

        return $servicePlan ?: ServicePlan::first();
    }

    /**
     * Check container expired status
     * 
     * @return bool
     */
    public function isExpired()
    {
        $expirationDate = $this->attributes['expiration_date'];
        $isExpired = carbon()->parse($expirationDate) < carbon()->now();

        return $isExpired;
    }

    /**
     * Create container on server
     * 
     * @return bool
     */
    public function createOnServer()
    {
        $repository = new ContainerRepository();
        $repository->setModel($this);
        return $repository->createOnServer();
    }

    /**
     * Set model to current container
     * 
     * @return bool
     */
    public function setCurrent()
    {
        $this->attributes['current'] = true;
        return $this->save();
    }

    /**
     * Check is container alive on server
     * 
     * @return bool
     */
    public function isCreatedOnServer()
    {
        $statusOnServer = $this->attributes['status_on_server'];

        return 
            ($statusOnServer >= StatusOnServer::Created) && 
            ($statusOnServer <= StatusOnServer::Inactive);
    }

    /**
     * Set property for container
     * 
     * @param  int  $propertyType
     * @param  mixed  $value
     * @return $this
     */
    public function setProperty(int $propertyType, $value)
    {
        $property = Property::of($this)
            ->withType($propertyType)
            ->first();
        if (! $property) {
            $property = new Property([
                'container_id' => $this->attributes['id'],
                'property_type' => $propertyType,
            ]);
        }
        $property->property_value = $value;
        $property->save();

        return $this;
    }

    /**
     * Get property of container
     * 
     * @param  int  $propertyType
     * @return \App\Models\ContainerProperty
     */
    public function getProperty(int $propertyType)
    {
        $property = Property::of($this)
            ->withType($propertyType)
            ->first();
        if (! $property) {
            $property = Property::create([
                'container_id' => $this->attributes['id'],
                'property_type' => $propertyType,
                'property_value' => 0,
            ]);
        }

        return $property;
    }

    /**
     * Set disk size of container
     * 
     * @param  float  $diskSize
     * @return $this
     */
    public function setDiskSize(float $diskSize)
    {
        $type = PropertyType::DiskSize;
        return $this->setProperty($type, $diskSize);
    }

    /**
     * Get disk size of container
     * 
     * @return float
     */
    public function getDiskSize()
    {
        $type = PropertyType::DiskSize;
        return $this->getProperty($type);
    }

    /**
     * Set disk array of container
     * 
     * @param  int  $diskArray
     * @return $this
     */
    public function setDiskArray(int $diskArray)
    {
        $type = PropertyType::DiskArray;
        return $this->setProperty($type, $diskArray);
    }

    /**
     * Get disk array of container
     * 
     * @return int
     */
    public function getDiskArray()
    {
        $type = PropertyType::DiskArray;
        return $this->getProperty($type);
    }

    /**
     * Set breakpoints of container
     * 
     * @param  int  $breakpoints
     * @return $this
     */
    public function setBreakpoints(int $breakpoints)
    {
        $type = PropertyType::Breakpoints;
        return $this->setProperty($type, $breakpoints);
    }

    /**
     * Get breakpoints of container
     * 
     * @return int
     */
    public function getBreakpoints()
    {
        $type = PropertyType::Breakpoints;
        return $this->setProperty($type);
    }

    /**
     * Set additional property to container
     * 
     * This will add the value of property
     * 
     * @param  int   $propertyType
     * @param  mixed $value
     * @return \App\Models\ContainerAdditionalProperty
     */
    public function setAdditionalProperty(int $propertyType, $value)
    {
        $property = $this->getProperty($propertyType);
        $additionalProp = $property->addAdditional($value);

        return $additionalProp;
    }

    /**
     * Activate the container
     * 
     * @return bool
     */
    public function activate()
    {
        $this->attributes['status'] = Status::Active;
        return $this->save();
    }
}