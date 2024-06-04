<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SingleEmailUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $userID;

    public function __construct($userID)
    {
        $this->userID = $userID;;
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
        return !\App\Models\User::where('email', $value)->exists() ||
            \App\Models\User::find($this->userID)->email == $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Este email ya tiene una cuenta asociada';
    }
}
