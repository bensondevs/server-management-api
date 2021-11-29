<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Repositories\VatRepository;

class VatNumber implements Rule
{
    private $vat;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->vat = new VatRepository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->vat->isVatFormatCorrect($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The VAT number you inserted is not valid.';
    }
}
