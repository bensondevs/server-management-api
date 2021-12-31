<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class SambaGroupUser extends Model
{
    use HasFactory;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'samba_group_users';

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
    protected $with = ['group', 'user'];

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'samba_group_id',
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

    	self::creating(function ($sambaGroupUser) {
            $sambaGroupUser->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get container of the samba group user
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get group that connected by this pivot
     */
    public function group()
    {
        return $this->belongsTo(SambaGroup::class, 'samba_group_id');
    }

    /**
     * Get user that connected by this pivot
     */
    public function user()
    {
        return $this->belongsTo(SambaUser::class, 'samba_user_id');
    }
}