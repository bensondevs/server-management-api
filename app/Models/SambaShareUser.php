<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\SambaShareUserObserver as Observer;

class SambaShareUser extends Model
{
    use HasFactory;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_share_users';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Enable timestamp for model execution
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model loaded relationship on retrieved
     * 
     * @var array
     */
    protected $with = ['share', 'user'];

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'samba_share_id',
        'samba_user_id',
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
     * Get container of samba share user
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get share which connected by this pivot
     */
    public function share()
    {
        return $this->belongsTo(SambaShare::class, 'samba_share_id');
    }

    /**
     * Get user which connected by this pivot
     */
    public function user()
    {
        return $this->belongsTo(SambaUser::class, 'samba_user_id');
    }
}