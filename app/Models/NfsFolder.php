<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class NfsFolder extends Model
{
    protected $table = 'nfs_folders';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'container_id',
        'folder_name',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($nfsFolder) {
            $nfsFolder->id = Uuid::generate()->string;
    	});
    }

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function exports()
    {
        return $this->hasMany(NfsExport::class);
    }
}