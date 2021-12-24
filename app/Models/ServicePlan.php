<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\HasPrice;

use App\Observers\ServicePlanObserver as Observer;
use App\Enums\{ 
    ContainerProperty\ContainerPropertyType as PropertyType,
    Currency
};

class ServicePlan extends Model
{
    use HasFactory;
    use HasPrice;

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
     * Model booted static method.
     * This method handles the global scope query of the model
     * 
     * @return void
     */
    protected static function booted()
    {
        //
    }

    /**
     * Get list of plan items
     */
    public function items()
    {
        return $this->hasMany(ServicePlanItem::class);
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
     * Set service plan item
     * 
     * @param  int   $propertyType
     * @param  mixed $value
     * @return bool
     */
    public function setItem(int $propertyType, $value)
    {
        $item = ServicePlanItem::firstOrNew([
            'property_type' => $propertyType,
            'service_plan_id' => $this->attributes['id'],
        ]);
        $item->property_value = $value;
        return $item->save();
    }

    /**
     * Set disk size in giga byte to the service plan
     * 
     * @param  int  $diskSize
     * @return bool
     */
    public function setDiskSize(int $diskSizeGb = 100)
    {
        return $this->setItem(PropertyType::DiskSize, $diskSizeGb);
    }

    /**
     * Set disk array of the service plan
     * 
     * @param  int  $diskArray
     * @return bool
     */
    public function setDiskArray(int $diskArray = 5)
    {
        return $this->setItem(PropertyType::DiskArray, $diskArray);
    }

    /**
     * Set breakpoints of the service plan
     * 
     * @param  int  $breakpoints
     * @return bool
     */
    public function setBreakpoints(int $breakpoints = 1)
    {
        return $this->setItem(PropertyType::Breakpoints, $breakpoints);
    }
}