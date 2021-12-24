<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\PrecreatedContainerObserver as Observer;
use App\Enums\PrecreatedContainer\PrecreatedContainerStatus as Status;

class PrecreatedContainer extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'precreated_containers';

    /**
     * Model primary key
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
     * Default array format of precreated_container_data
     * 
     * @var array
     */
    const DEFAULT_DATA_FORMAT = [
        'meta_container' => [],
        'meta_container_properties' => [],
        'meta_container_additional_properties' => [],
    ];

    /**
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'precreated_container_data',
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
     * Create callable function of "waiting()"
     * This callable function will return only pre-created container
     * which has status of waiting
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaiting(Builder $query)
    {
        return $query->where('status', Status::Waiting);
    }

    /**
     * Create callable method of "waitingQueue()"
     * This callable method will order the populated results
     * from the waiting list of pre-created containers
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaitingQueue(Builder $query)
    {
        return $query->where('status', Status::Waiting)
            ->orderBy('waiting_since');
    }

    /**
     * Get order of the pre-created container
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get created container by this pre-created container
     */
    public function container()
    {
        return $this->hasOne(Container::class);
    }

    /**
     * Create callable attribute of "precreated_container_data"
     * This callable attribute will convert encoded json "precreated_container_data"
     * as array
     * 
     * @return array
     */
    public function getPrecreatedContainerDataAttribute()
    {
        $defaultFormat = self::DEFAULT_DATA_FORMAT;

        $json = $this->attributes['precreated_container_data'];
        if (! $embryo = json_decode_array($json)) {
            return $defaultFormat;
        }

        // Format the result as default format
        foreach ($defaultFormat as $key => $value) {
            if (! isset($embryo[$key])) {
                $embryo[$key] = $value;
            }
        }

        return $embryo;
    }

    /**
     * Create settable attribute of "precreated_container_data"
     * This settable attribute will set the value of "precreated_container_data"
     * using array and set that value with json
     * 
     * @param  array  $preContainerData
     */
    public function setPrecreatedContainerDataAttribute(array $preContainerData)
    {
        $metaData = self::DEFAULT_DATA_FORMAT;
        foreach ($metaData as $key => $defaultValue) {
            $metaData[$key] = isset($preContainerData[$key]) ?
                $preContainerData[$key] :
                $defaultValue;
        }

        $this->attributes['precreated_container_data'] = json_encode($metaData, true);
    }

    /**
     * Create callable attribute of "meta_container"
     * This callable attribute will return array containing embryo
     * of soon-will-be-created container
     * 
     * @return  array
     */
    public function getMetaContainerAttribute()
    {
        $json = $this->attributes['precreated_container_data'];
        if (! $embryo = json_decode_array($json)) {
            return [];
        }

        return isset($embryo['meta_container']) ?
            $embryo['meta_container'] : [];
    }

    /**
     * Create settable attribute of "meta_container"
     * This settable attribute will save data for the embryo of
     * newly created container
     * 
     * @param  array  $containerData
     * @return void
     */
    public function setMetaContainerAttribute(array $containerData)
    {
        $metaContainer = new Container($containerData);

        $embryo = json_decode_array($this->attributes['precreated_container_data']);
        $embryo['meta_container'] = $metaContainer->toArray();
        $this->attributes['precreated_container_data'] = json_encode($embryo, true);
    }

    /**
     * Create callable attribute of "meta_container_properties"
     * This callable attribute will return data of soon-will-be-created
     * container's properties including but not limited to disk size, disk array,
     * breakpoints and any others specified in the enums
     * 
     * @return array
     */
    public function getMetaContainerPropertiesAttribute()
    {
        $json = $this->attributes['precreated_container_data'];
        if (! $embryo = json_decode_array($json)) {
            return [];
        }

        return isset($embryo['meta_container_properties']) ?
            $embryo['meta_container_properties'] :
            [];
    }

    /**
     * Create settable attribute of "meta_container_properties"
     * This settable attribute will return the meta container properties
     * for embryo of the will-be-created container
     * 
     * @param  array  $metaProps
     * @return void
     */
    public function setMetaContainerPropertiesAttribute(array $metaProps)
    {
        $json = $this->attributes['precreated_container_data'];
        $embryo = json_decode_array($json) ?: [];
        $embryo['meta_container_properties'] = $metaProps;

        $this->precreated_container_data = $embryo;
    }

    /**
     * Create callable attribute of "meta_container_additional_properties"
     * This callable attribute will return data of soon-will-be-created
     * container's additional properties like meta container property but with
     * any subscription, when subscription ends, the additional property will be 
     * destroyed and sum of value of container property will be reduced accordingly.
     * 
     * @return  array
     */
    public function getMetaContainerAdditionalPropertiesAttribute()
    {
        $json = $this->attributes['precreated_container_data'];
        if (! $embryo = json_decode_array($json)) {
            return [];
        }

        return isset($embryo['meta_container_additional_properties']) ?
            $embryo['meta_container_additional_properties'] :
            [];
    }

    /**
     * Create settable attribute of "meta_container_additional_properties"
     * This settable attribute will set the value of "meta_container_additional_properties"
     * This will contain all the service addon added into the pre-created container and
     * when container is created using pre-created container, this added properties
     * will be having subscription. When the subscription is ended, this additional properties
     * will be destroyed and the sum of container property value will be reduced accordingly.
     * 
     * @param  array  $metaAdditionalProps
     * @return void
     */
    public function setMetaContainerAdditionalPropertiesAttribute(array $metaAdditionalProps)
    {
        $json = $this->attributes['precreated_container_data'];
        $embryo = json_decode_array($json) ?: [];
        $embryo['meta_container_additional_properties'] = $metaAdditionalProps;

        $this->precreated_container_data = $embryo;
    }

    /**
     * Add property that will be set up into container
     * when pre-created embryo is processed and to be created
     * as real container.
     * 
     * Setting the existing property will change it's value
     * 
     * @param  int   $propertyType
     * @param  mixed $value
     * @param  bool  $recursive
     * @return array
     */
    public function addMetaContainerProperty(int $propertyType, $value, bool $recursive = true)
    {
        // Meta properties of the soon-will-be-created container
        $metaProps = $this->meta_container_properties;

        // To-be-added property
        $addedProp = [
            'property_type' => $propertyType,
            'property_value' => $value,
        ];
        
        // Check if certain prop type is already set
        $foundIndex = array_search($propertyType, array_column($metaProps, 'property_type'));
        if ($foundIndex !== null) { 
            
            /**
             * If the addition is recursive (param 3 = true), 
             * just add or merge the value based on variable type.
             * The actions as follows at the switch case.
             * 
             * Possible types: 
             * - numeric (integer, float, double)
             * - array
             * - string
             */
            if ($recursive) {
                switch (gettype($addedProp['property_value'])) {
                    /**
                     * If `integer` or `float` or `double`, 
                     * just add both value as one
                     */
                    case 'integer':
                        $addedProp['property_value'] += $value;
                        break;
                    case 'float':
                        $addedProp['property_value'] += $value;
                        break;
                    case 'double':
                        $addedProp['property_value'] += $value;
                        break;

                    /**
                     * If `array`, just merge the array as one
                     */
                    case 'array':
                        array_merge($addedProp['property_value'], $value);
                        break;

                    /**
                     * If `string`, just concatenate them
                     */
                    case 'string':
                        $addedProp['property_value'] .= $value;
                        break;
                    
                    /**
                     * If nothing match, do nothing
                     */
                    default:
                        // Do nothing...
                        break;
                }

            }
            
            unset($metaProps[$foundIndex]);
        }

        // Add new prop to the list of meta props
        array_push($metaProps, $addedProp);
        
        return $this->meta_container_properties = $metaProps; // Replace with updated value
    }

    /**
     * Remove property that will not be set up into container
     * by supplying the value of `property_type` enum integer
     * 
     * @param  int   $propertyType
     * @return array
     */
    public function removeMetaContainerProperty(int $propertyType)
    {
        $metaProps = $this->meta_container_properties;

        // Search removed index and unset if found
        $column = array_column($metaProps, 'property_type');
        $foundIndex = array_search($propertyType, $column);
        if ($foundIndex !== null) {
            if (isset($metaProps[$foundIndex])) 
                unset($metaProps[$foundIndex]);
        }

        return $this->meta_container_properties = $metaProps;
    }

    /**
     * Add additional property that will be set up into the pre-created container
     * and later on the subscription will follow into this property.
     * When subscription of this property ends, this current additional property
     * will be destroyed and the additional value to certain property will be reduced
     * 
     * @param  \App\Models\ServiceAddon  $addon
     * @return array
     */
    public function addMetaContainerAdditionalProperty(ServiceAddon $addon)
    {
        $metaAdditionalProps = $this->meta_container_additional_properties;
        array_push($metaAdditionalProps, [
            'property_type' => $propertyType,
            'property_value' => $value,
            'subscribeable_id' => $addon->id,
            'subscribeable_type' => get_class($addon),
        ]);
        return $this->meta_container_additional_properties = $metaAdditionalProps;
    }

    /**
     * Apply any service to pre-created container
     * This method will guess how to treat any kind of service
     * 
     * @param  mixed  $service
     * @return array
     */
    public function applyService($service)
    {
        switch (get_class($service)) {
            /**
             * If order item is class type service plan:
             * - Apply service plan to pre-created container
             */
            case ServicePlan::class:
                return $embryo->applyServicePlan($service);
                break;

            /**
             * If order item class type is service addon
             * - Apply service addon to pre-created container
             */
            case ServiceAddon::class:
                return $embryo->applyServiceAddon($service);
                break;
            
            /**
             * If order item class type has never been specified
             * - Skip it, and move on to the next item.
             */
            default:
                return $this->meta_container_properties;
                break;
        }
    }

    /**
     * Get all service plan items which is each of them
     * contains the container properties and it's VALUE.
     * By the acquired value, that will be container properties
     * to begin with as the configurations of the container data in the server
     * 
     * @param  \App\Models\ServicePlan  $plan
     * @return array
     */
    public function applyServicePlan(ServicePlan $plan)
    {
        foreach ($plan->items as $item) {
            $type = $item->property_type;
            $value = $item->property_value;
            $this->addMetaContainerProperty($type, $value, true); // Recursive adding
        }

        return $this->meta_container_properties;
    }

    /**
     * Get the service addon container property target and value
     * and set or add the value into embryo meta container property.
     * 
     * @param \App\Models\ServiceAddon  $addon
     * @return bool 
     */
    public function applyServiceAddon(ServiceAddon $addon)
    {
        return $this->addMetaContainerAdditionalProperty($addon);
    }

    /**
     * Set status of the pre-created container as prepared
     * 
     * @param  bool  $save
     * @return bool
     */
    public function setPrepared(bool $save = true)
    {
        $this->attributes['status'] = Status::Prepared;
        $this->attributes['prepared_at'] = now();
        return $save ? $this->save() : true;
    }

    /**
     * Set status to "Created"
     * 
     * @param  bool  $save
     * @return  bool
     */
    public function setCreated(bool $save = true)
    {
        $this->attributes['status'] = Status::Created;
        $this->attributes['container_created_at'] = now();
        return $save ? $this->save() : true;
    }

    /**
     * Set status to "Waiting"
     * 
     * @param  bool  $save
     * @return bool
     */
    public function setWaiting(bool $save = true)
    {
        $this->attributes['status'] = Status::Waiting;
        $this->attributes['waiting_since'] = now();
        return $save ? $this->save() : true;
    }

    /**
     * Get the firstly waiting pre-created container.
     * 
     * @return self|null
     */
    public static function shiftQueue()
    {
        return self::waiting()->orderBy('waiting_since')->first();
    }
}