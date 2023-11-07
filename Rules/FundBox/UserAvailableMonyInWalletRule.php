<?php

namespace App\Rules\FundBox;

use App\Models\Fundbox;
use App\Models\User;
use App\Services\FundboxPrice;
use Illuminate\Contracts\Validation\Rule;

class UserAvailableMonyInWalletRule implements Rule
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
        $fund_price =  FundboxPrice::get($this->fundbox->id,$value);
        return auth()->user()->wallet >= $fund_price->total;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You don\'t have enough money in your wallet');
    }
}
