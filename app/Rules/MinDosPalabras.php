<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinDosPalabras implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $explode = explode(" ", $value);
        if (count($explode)>1) { 
            return true; 
        } else {
            return false; 
        };
        //return strtoupper($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El nombre debe tener más de una palabra.';
    }
}
