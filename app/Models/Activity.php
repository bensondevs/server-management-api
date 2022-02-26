<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use UuidTrait;

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model primary key type cast
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'causer_id' => 'string',
        
    ];

    /**
     * Create callable attribute of `created_time`
     * This callable attribute will return the created at
     * time in format of `[H:i:s] M d, Y`
     * 
     * @return string
     */
    public function getCreatedTimeAttribute()
    {
        $createdAt = $this->attributes['created_at'];
        return carbon($createdAt)->format('[H:i:s] M d, Y');
    }
}
