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

class User extends Authenticatable
{
    use UuidTrait;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use CausesActivity;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = true;

    const ACCOUNT_TYPES = [
        [
            'label' => 'Personal',
            'value' => 'personal',
        ],
        [
            'label' => 'Business',
            'value' => 'business',
        ]
    ];

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
        'newsletter',
        'notes',
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

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            $user->incrementing = false;
            $user->id = Uuid::generate()->string;
        });
    }

    public function generateToken()
    {
        return $this->token = $this->createToken(time())->plainTextToken;
    }

    public function verifyEmail()
    {
        $this->attributes['email_verified_at'] = carbon()->now();
        return $this->save();
    }

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

    public function anchorName($htmlClass = '')
    {
        $route = route('dashboard.users.view', ['id' => $this->attributes['id']]);
        $fullName = $this->getFullNameAttribute(); 

        $anchor = '<a href="' . $route . '" class="' . $htmlClass . '">';
        $anchor .= $fullName;
        $anchor .= '</a>';

        return $anchor;
    }

    public function setUnhashedPasswordAttribute($unhashedPassword)
    {
        $this->attributes['password'] = bcrypt($unhashedPassword);
    }

    public function isAdministrator()
    {
        return $this->hasRole('administrator');
    }

    public function getVatSizePercentageAttribute()
    {
        if ($vatNumber = $this->attributes['vat_number']) {
            $vat = new \App\Repositories\VatRepository();

            if ($this->attributes['account_type'] == 'business')
                if (! $vat->isVatExist($vatNumber))
                    abort(422, 'Invalid VAT number, please update VAT number information');

            return $vat->getCountryRate($vatNumber);
        }

        return 0;
    }

    public function hasContainerPermission($containerId, $permission)
    {
        if ($user->hasRole('administrator')) {
            return true;
        }

        $container = Container::findOrFail($containerId);
        if ($this->attributes['id'] != $container->customer_id) {
            return false;
        }

        return $user->hasPermissionTo($permission);
    }

    public function containers()
    {
        return $this->hasMany(Container::class, 'customer_id');
    }
}