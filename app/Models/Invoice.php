<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'payment_information',
        'currency',
        'amount',
        'expiration_date',
        'payment_link',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($invoice) {
            $invoice->id = Uuid::generate()->string;
    	});
    }
}