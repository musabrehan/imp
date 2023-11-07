<?php

namespace App\Rules\FundBox;

use App\Models\Fundbox;
use Illuminate\Contracts\Validation\Rule;

class NormalUserLimitRule implements Rule
{
    protected $fundbox;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fundbox)
    {
        $this->fundbox=Fundbox::find($fundbox);

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
        return auth()->user()->level == 1 or ( auth()->user()->level == 0 && $this->fundbox->unit_price * $value <= $this->fundbox->available_amount_for_user_in_box );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('you need to upgrade your account to be able to invest more than ') . $this->fundbox->available_amount_for_user_in_box . __('SAR');
    }
}
