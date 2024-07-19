<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidityYearProject implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $validityYear;

    public function __construct($validityYear)
    {
        $this->validityYear = $validityYear;
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
        $year = explode("-", $value)[0];
        return $year == $this->validityYear;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El año no coinciden con la vigencia';
    }
}
