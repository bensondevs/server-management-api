<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class PasswordReset extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'password_resets';

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
        'email',
        'token',
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
    }

    /**
     * Check if email and token is matched in the record
     * 
     * @static
     * @param  string  $email
     * @param  string  $token
     * @return bool
     */
    public static function match(string $email, string $token)
    {
        return self::whereEmail($email)
            ->whereToken($token)
            ->exists();
    }

    /**
     * Find token exist in the record
     * 
     * @param  string  $token
     * @return self
     */
    public static function findByToken(string $token)
    {
        return self::whereToken($token)->first();
    } 

    /**
     * Find token owned by a certain user
     * 
     * @param  static
     * @param  \App\Models\User|string  $user
     * @return self|null
     */
    public static function findByUser($user)
    {
        if (! $user instanceof User) {
            $user = User::findByIdentity($user, true);
        }

        $email = $user->email;
        return self::whereEmail($email)->first();
    }
}