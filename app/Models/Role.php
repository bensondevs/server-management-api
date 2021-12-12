<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Traits\UuidTrait;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use UuidTrait;

    /**
     * Model table primary key
     * 
     * @var  string
     */
    protected $primaryKey = 'id';

    /**
     * Model primary key type
     * 
     * @var  string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];
}