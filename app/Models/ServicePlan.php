<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\ServicePlanObserver as Observer;
use App\Enums\{ 
    Currency, 
    ContainerProperty\ContainerPropertyType 
};

class ServicePlan extends Model
{
    use HasFactory;

    /**
     * The model table name
     * 
     * @var string
     */
    protected $table = 'service_plans';

    /**
     * The model primary key
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
        'plan_name',
        'plan_code',
        'description',

        'status',

        'duration_days',
    ];

    /**
     * Relationships that should be loaded
     * whenever the model is retrieved from database
     * 
     * @var array
     */
    protected $with = ['pricings'];

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
     * Get list of plan items
     */
    public function items()
    {
        return $this->hasMany(ServicePlanItem::class);
    }

    /**
     * Get pricing of the plan
     */
    public function pricings()
    {
        return $this->morphMany(Pricing::class, 'priceable');
    }

    /**
     * Hide service plan from the users
     * 
     * @return bool
     */
    public function hide()
    {
        $this->attributes['is_hidden'] = true;
        return $this->save();
    }

    /**
     * Unhide service plan from the users
     * 
     * @return bool
     */
    public function unhide()
    {
        $this->attributes['is_hidden'] = false;
        return $this->save();
    }

    /**
     * Get price of the service plan
     * 
     * @return  float
     */
    public function getPrice()
    {
        if (! $pricings = $this->pricings) {
            return 0;
        }

        $currency = current_currency();
        if (! $pricing = $pricings->where('currency', $currency)->first()) {
            return 0;
        }

        return $pricing->price;
    }

    /**
     * Set duration in certain unit of the service plan
     * 
     * @param  int    $count
     * @param  string $unit
     * @return bool
     */
    public function setDuration(int $count = 30, string $unit = 'days')
    {
        switch (strtolower($unit)) {
            case 'day':
                $days = $count;
                break;
            case 'days':
                $days = $count;
                break;

            case 'week':
                $days = $count * 7;
                break;
            case 'weeks':
                $days = $count * 7;
                break;

            case 'months':
                $days = $count * 30;
                break;
            case 'month':
                $dats = $count * 30;

            case 'year':
                $days = $count * 365;
                break;
            case 'years':
                $days = $count * 365;
                break;

            case 'decade':
                $days = $count * 3650;
                break;
            case 'decades':
                $days = $count * 3650;
                break;
            
            default:
                $days = $count;
                break;
        }

        $this->attributes['duration_days'] = $days;
        return $this->save();
    }

    /**
     * Set disk size in giga byte to the service plan
     * 
     * @param  int  $diskSize
     * @return bool
     */
    public function setDiskSize(int $diskSizeGb = 100)
    {
        $item = ServicePlanItem::firstOrNew([
            'property_type' => ContainerPropertyType::DiskSize,
            'service_plan_id' => $this->attributes['id'],
        ]);
        $item->property_value = $diskSizeGb;
        return $item->save();
    }

    /**
     * Set pricing for current service plan
     * 
     * @param  float  $price
     * @param  int  $currency
     */
    public function setPrice(float $price = 0, int $currency = Currency::EUR)
    {
        $pricing = Pricing::firstOrNew([
            'priceable_type' => get_class($this),
            'priceable_id' => $this->attributes['id'],
            'currency' => $currency,
        ]);
        $pricing->price = $price;
        return $pricing->save();
    }
}