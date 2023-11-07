<?php

namespace App\Rules\FundBox;

use App\Models\Fundbox;
use Illuminate\Contracts\Validation\Rule;

class AvailableFundBox implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public $fundbox)
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
        // for normal or pro users 
        // if fund is available go on
        if ($this->fundbox->available == true) {
            logger('Fund Box is available ');
            return true;
        } else
        //  if the fund not available 
        // check if user is PRO ? => complete purchase 
         if (auth()->user()->is_upgraded && $this->fundbox->end_date > now()) {
            logger('Fund Box is not  available but use is upgraded  ');
            return true;
        }
        // all conditions failed 
        logger('ALL FAILED');
        // return false;
        // return $this->fundbox->available == true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('This fund is not available');
    }
}
