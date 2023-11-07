<?php

namespace App\Rules\FundBox;

use App\Models\Fundbox;
use Illuminate\Contracts\Validation\Rule;

class MinMaxRules implements Rule
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
    public function passes($attribute,  $value)
    {
        if($this->fundbox->available_units < $this->fundbox->min_units){
            return (int)$value <= $this->fundbox->max_units && (int)$value <= (int)$this->fundbox->available_units;
        }else {
            return (int)$value >= $this->fundbox->min_units && (int)$value <= $this->fundbox->max_units && (int)$value <= (int)$this->fundbox->available_units;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->fundbox->available_units < $this->fundbox->min_units){
            return __('this value must be less than or equal to available units:') .' ' . $this->fundbox->available_units;
        }else {
            return __('this value must be between ').(int)$this->fundbox->min_units.__(' and ').(int)$this->fundbox->available_units .__(' and less than available units');
        }
    }
}
