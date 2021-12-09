<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Webpatser\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Traits\UuidTrait;

use App\Enums\User\UserAccountType;

class User extends Authenticatable
{
    use UuidTrait;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use CausesActivity;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * Model primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Key type of the table id
     * 
     * @return string
     */
    protected $keyType = 'string';

    /**
     * Model primary key incrementing. 
     * Set to TRUE if `id` is int, otherwise let it be FALSE
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_type',

        'first_name',
        'middle_name',
        'last_name',

        'country',
        'address',
        'vat_number',

        'username',
        'email',
        'password',

        'company_name',
        'subscribe_newsletter',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

        self::creating(function ($user) {
            $user->incrementing = false;
            $user->id = Uuid::generate()->string;
        });
    }

    /**
     * Create callable attribute of `full_name`
     * This callable attribute will return the full name of the user
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        $firstName = $this->attributes['first_name'];
        $middleName = $this->attributes['middle_name'];
        $lastName = $this->attributes['last_name'];

        // Construct full name
        $fullName = $middleName ?
            ($firstName . ' ' . $middleName) :
            ($firstName);
        $fullName .= ' ';
        $fullName .= $lastName;

        return $fullName;
    }

    /**
     * Create callable attribute of "account_type_description"
     * This callable attribute will return enum description of value
     * 
     * @return string
     */
    public function getAccountTypeDescryptionAttribute()
    {
        $type = $this->attributes['account_type'];
        return UserAccountType::getDescription($type);
    }

    /**
     * Create settable `unhashed_password` attribute
     * This settable attribute will allow insertion of plain string
     * and convert it as hashed password
     * 
     * @param string  $password
     * @return void
     */
    public function setUnhashedPasswordAttribute(string $password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Get containers of the users
     */
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * Generate token for the user
     * 
     * @return string
     */
    public function generateToken()
    {
        $token = $this->createToken(time())->plainTextToken;
        return $this->token = $token;
    }

    /**
     * Mark user email as verified
     * 
     * THIS FUNCTION DOES NOT SEND EMAIL 
     * TO VERIFY THE USER. JUST MARK `email_verified_at` WITH
     * CURRENT DATETIME
     * 
     * @return bool
     */
    public function verifyEmail()
    {
        $this->attributes['email_verified_at'] = now();
        return $this->save();
    }

    /**
     * Return HTML <a> element as string 
     * to redirect the viewer to user profile
     * 
     * @param  string  $htmlClass
     * @return string 
     */
    public function anchorName(string $htmlClass = 'btn btn-primary')
    {
        $route = route('dashboard.users.view', ['id' => $this->attributes['id']]);
        $fullName = $this->getFullNameAttribute(); 

        $anchor = '<a href="' . $route . '" class="' . $htmlClass . '">';
        $anchor .= $fullName;
        $anchor .= '</a>';

        return $anchor;
    }

    /**
     * Find user using email or username
     * 
     * @return self|null|abort 404
     */
    public static function findByIdentity(string $login, bool $abortNotFound = false)
    {
        $query = self::where('email', $login)
            ->orWhere('username', $login);
        return $abortNotFound ? 
            $query->firstOrFail() : 
            $query->first();
    }

    /**
     * Check password for user match
     * 
     * @return bool
     */
    public function isPasswordMatch(string $password)
    {
        return hashCheck($password, $user->password);
    }
}