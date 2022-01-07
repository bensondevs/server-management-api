<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\SambaShareGroupObserver as Observer;

class SambaShareGroup extends Model
{
    use HasFactory;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_share_groups';

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
    public $timestamps = true;
    
    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Relationship loaded each time model found
     * 
     * @var array
     */
    protected $with = ['group', 'share'];

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'samba_group_id',
        'samba_share_id',
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
     * Get container of the samba share group
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get groups connected by this pivot
     */
    public function group()
    {
        return $this->belongsTo(SambaGroup::class, 'samba_group_id');
    }

    /**
     * Get share connected by this pivot
     */
    public function share()
    {
        return $this->belongsTo(SambaShare::class, 'samba_share_id');
    }
}