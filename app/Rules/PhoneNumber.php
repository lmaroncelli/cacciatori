<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
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
         // se il numero comincia con "+39" ed è lungo da 8 a 12 allora ok
          return preg_match('/^(\+)39/', $value) && strlen(substr($value,3)) >= 8 && strlen(substr($value,3)) <= 12;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Il numero deve cominciare con prefisso +39 ed essere tra 8 e 12 caratteri';
    }
}
