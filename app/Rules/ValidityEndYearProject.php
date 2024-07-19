<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidityEndYearProject implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $startDate;

    public function __construct($startDate)
    {
        $this->startDate = $startDate;
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
        return $value > $this->startDate;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La fecha de fin no puede ser menor al de inicio';
    }
}
