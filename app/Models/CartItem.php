<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\CartItemObserver as Observer;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'cart_items';

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
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'cart_id',
        'cart_itemable_id',
        'cart_itemable_type',
        'quantity',
        'sub_total',
        'discount',
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
     * Count sub total of the cart item
     * 
     * @return float
     */
    public function countSubTotal()
    {
        $itemable = $this->cart_itemable;

        $pricings = $itemable->pricings;
        $pricing = $pricings->where('currency', current_currency())->first();
        
        $price = $pricing->price;
        $quantity = $this->attributes['quantity'];
        $discount = $this->attributes['discount'];

        $subtotal = ($price * $quantity) - $discount;
        return $this->attributes['sub_total'] = $subtotal;
    }

    /**
     * Guess the cartable type inputted from the front end
     * 
     * @param  string  $clue
     * @return string
     */
    public static function guessType(string $clue)
    {
        switch (true) {
            case $clue == ServicePlan::class:
                return ServicePlan::class;
                break;

            case $clue == ServiceAddon::class:
                return ServiceAddon::class;
                break;

            case strtolower($clue) == 'service_plan':
                return ServicePlan::class;
                break;

            case strtolower($clue) == 'service_addon':
                return ServiceAddon::class;
                break;

            case strtolower($clue) == 'service plan':
                return ServicePlan::class;
                break;

            case strtolower($clue) == 'service addon':
                return ServiceAddon::class;
                break;
            
            default:
                ServicePlan::class;
                break;
        }
    }
}