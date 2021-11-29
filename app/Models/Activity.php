<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\UuidTrait;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use UuidTrait;
    protected $primaryKey = 'id';
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

    public function getCreatedTimeAttribute()
    {
        return carbon($this->attributes['created_at'])->format('[H:i:s] M d, Y');
    }
}
