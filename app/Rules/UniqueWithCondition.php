<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueWithCondition implements Rule
{
    private $model;
    private $conditions;
    private $attribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, array $conditions, $attribute = '')
    {
        $this->model = $model;
        $this->conditions = $conditions;
        $this->attribute = $attribute;
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
        $model = $this->model;
        foreach ($this->conditions as $condition) {
            $column = $condition['column'];
            $operator = isset($condition['operator']) ?
                $condition['operator'] : '=';
            $queryValue = $condition['value'];

            $model = $model->where($column, $operator, $queryValue);
        }

        $attribute = $this->attribute ?: $attribute;
        $found = $model->where($attribute, $value)->count();

        return $found < 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The inputted value is already exist.';
    }
}
