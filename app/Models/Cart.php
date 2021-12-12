<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class Cart extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'carts';

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
        'user_id',
        'cartable_type',
        'cartable_id',
        'quantity',
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

    	self::creating(function ($cart) {
            $cart->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable method of "forUser(User $user)"
     * This callable method will query only cart which owned by a certain user
     * 
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User
     * @return  Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Get user owner of the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get cartable model
     */
    public function cartable()
    {
        return $this->morphTo('cartable');
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