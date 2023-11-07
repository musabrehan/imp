<?php

namespace App\Rules\FundBox;

use App\Models\Fundbox;
use Illuminate\Contracts\Validation\Rule;

class AvailableRules implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fundbox)
    {
        $this->fundbox = Fundbox::whereId($fundbox)->first();
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
        return $value <= $this->fundbox->available_units; // to check if the units are less than the available units
            // && $this->fundbox->available ==true ; // to check if the fundbox is available or not
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('this value must be less than or equal to') . ' ' .$this->fundbox->available_units . ' ' . __('and the fundbox units is available');
    }
}
