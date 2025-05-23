<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\NfsFolderObserver as Observer;

class NfsFolder extends Model
{
    use HasFactory;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'nfs_folders';

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
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'container_id',
        'folder_name',
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
     * Get container of the NFS Folder
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get exports using this folder
     */
    public function exports()
    {
        return $this->hasMany(NfsExport::class);
    }
}