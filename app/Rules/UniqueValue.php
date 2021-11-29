<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueValue implements Rule
{
    protected $table;
    protected $column;
    protected $id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column, $id = '')
    {
        $this->table = $table;
        $this->column = $column;
        $this->id = $id;
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
        $found = db($this->table)
            ->where($this->column, $value)
            ->first();

        if (! $found) return true;

        // Allowed because ID is same and not changing value
        if ($found->id == $this->id)
            if ($found->{$this->column} == $value)
                return true;

        return $found;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value you are inserting is already exist in database.';
    }
}
