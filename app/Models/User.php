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

use App\Mail\Users\RegisterConfirmationMail;
use App\Jobs\SendMail;
use App\Observers\UserObserver as Observer;
use App\Enums\Currency;
use App\Enums\User\UserAccountType as AccountType;

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
     * Model primary key incrementing. 
     * Set to TRUE if `id` is int, otherwise let it be FALSE
     * 
     * @var bool
     */
    public $incrementing = false;

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
        self::observe(Observer::class);
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
        return AccountType::getDescription($type);
    }

    /**
     * Create callable attribute of "default_currency"
     * This callable attribute will return enum description of
     * user selection of default currency
     * 
     * @return string
     */
    public function getDefaultCurrencyAttribute()
    {
        $currency = $this->attributes['currency'];
        return Currency::getDescription($currency);
    }

    /**
     * Create callable attribute of "vat_size_percentage"
     * This callale attribute will return var size percentage in integer
     * of current user depends on the vat number of user
     * 
     * @return int
     */
    public function getVatSizePercentageAttribute()
    {
        if ($this->attributes['vat_number']) {
            //
        }

        return 0;
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
     * Get user carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get user orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get containers of the users
     */
    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    /**
     * Get subscriptions of user
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Guess which currency should apply to user
     * 
     * @return  int
     */
    public function guessCurrency()
    {
        $ipApiService = new App\Services\IpApiService();
        $currency = $ipApiService->getCurrency();

        return in_array($currency, ['USD', 'EUR']) ?
            Currency::getValues($currency) :
            Currency::USD;
    }

    /**
     * Check if user has verified their email
     * 
     * @return bool
     */
    public function emailIsVerified()
    {
        if (! isset($this->attributes['email_verified_at'])) {
            return false;
        }

        return $this->attributes['email_verified_at'] !== null;
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
     * Send email verification for user to verify
     * their email
     * 
     * @return void
     */
    public function sendEmailVerification()
    {
        $mail = new RegisterConfirmationMail($this);
        $send = new SendMail($mail, $this->attributes['email']);
        dispatch($send);
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
            ->orWhere('username', $login)
            ->orWhere('id', $login);
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
        return hash_check($password, $this->attributes['password']);
    }

    /**
     * Check user has permission to a container
     * 
     * @param  \App\Models\Container|string  $container
     * @param  string  $doSomething
     * @return bool
     */
    public function hasContainerPermission($container, string $doSomething)
    {
        if (! $container instanceof Container) {
            $container = Container::findOrFail($container);
        }

        if ($container->user_id !== $this->attributes['id']) {
            return false;
        }

        return $user->hasPermissionTo($doSomething);
    }
}