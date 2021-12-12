<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\ContainerObserver;
use App\Repositories\ContainerRepository;

use App\Traits\{ Searchable, TrackInQueue };
use App\Traits\Container\{
    VpnTrait,
    NfsTrait,
    SambaTrait,
    NginxTrait
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

        'disk_size',
        'disk_array',
        'breakpoints',

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
        self::observe(ContainerObserver::class);

        self::creating(function ($serverContainer) {
            $subnetIp = SubnetIp::findOrFail($serverContainer->subnet_ip_id);
            $serverContainer->id = $serverContainer->generate_id($subnetIp->ip_address);
        });
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
     * @param \App\Models\User  $customer
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy(Builder $query, User $customer)
    {
        return $query->where('customer_id', $customer->id);
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
        return ContainerStatus::getDescription($status);
    }

    /**
     * Get order date in human date format M d, Y
     * 
     * @return string
     */
    public function getOrderHumanDateAttribute()
    {
        return carbon()
            ->parse($this->attributes['order_date'])
            ->format('M d, Y');
    }

    /**
     * Get activation date in human date format M d, Y
     * 
     * @return string
     */
    public function getActivationHumanDateAttribute()
    {
        return carbon()
            ->parse($this->attributes['activation_date'])
            ->format('M d, Y');
    }

    /**
     * Get expiration date in human date format M d, Y
     * 
     * @return string
     */
    public function getExpirationHumanDateAttribute()
    {
        return carbon()
            ->parse($this->attributes['expiration_date'])
            ->format('M d, Y');
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

        return $ip->ip_address;
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
     * Activate the container
     * 
     * @return bool
     */
    public function activate()
    {
        $this->attributes['status'] = ContainerStatus::Active;
        return $this->save();
    }
}