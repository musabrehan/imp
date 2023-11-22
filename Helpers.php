<?php

namespace App\Models\Traits\User;

use Illuminate\Support\Facades\Log;
use App\Enums\Fundbox\Status;
use App\Models\User;
use App\Models\Fundbox;
use App\Models\Question;
use App\Enums\User\Action;
use App\Events\BuyFundbox;
use App\Models\Transaction;
use App\Models\VirtualIban;
use App\Models\User2Fundbox;
use App\Events\ClosedFundbox;
use App\Services\FundboxPrice;
use App\Services\Zoho\Invoice;
use App\Services\Zoho\Journal;
use App\Enums\Transaction\Type;
use App\Enums\Transaction\State;
use App\Models\Unifonic\Wrapper;
use Illuminate\Support\Facades\DB;
use App\Enums\Transaction\Description;
use App\Enums\Question2Choiceanswer\Status as ChoiceAnswerStatus;
use App\Enums\Question\Type as QuestionType;

trait Helpers
{
    /*---------------------------- Helper Methods --------------------------------*/

    public static function withAmount($id = null)
    {
        if ($id != null) {
            return User::find($id)->with('buyed_fundboxes')->get()->map(function ($user) {
                $units_num = 0;
                foreach ($user->buyed_fundboxes as $box) {
                    $box_total = $box->unit_price * $box->pivot->units_num;
                    $units_num = $box_total + $units_num;
                }
                $user['amount'] = $units_num;
                return $user;
            });
        }
        return User::with('buyed_fundboxes')->get()->map(function ($user) {
            $units_num = 0;
            foreach ($user->buyed_fundboxes as $box) {
                $box_total = $box->unit_price * $box->pivot->units_num;
                $units_num = $box_total + $units_num;
            }
            $user['amount'] = $units_num;
            return $user;
        });
    }


    /**
     * Generate OTP
     *
     * @param string $msgType : 'login' , 'forget-password', 'register', 'buy-fundbox','withdraw-money'
     * @param string $type : 'unifonic' , 'yaqin'
     * @return void
     * @throws \ErrorException
     */
    public function generateOTP(string $msgType, string $type)
    {
        if (env('APP_ENV') === 'production') {
            if ($type == 'unifonic') {
                $this->unifonic($msgType);
            } elseif ($type == 'yaqin') {
                $this->yquin($msgType);
            } else {
                throw new \ErrorException('Invalid Type');
            }
        } else {
            $this->otp = $this->otp_generator();
            $this->otp_expires_at = now()->addMinutes($this->otp_counter());
            $this->otp_verified = false;
            $this->action = match ($msgType) {
                'login'             => Action::LOGIN->value,
                'forget-password'   => Action::FORGET_PASSWORD->value,
                'register'          => Action::REGISTER->value,
                'buy-fundbox'       => Action::BUY_FUND->value,
                'withdraw-money'    => Action::WITHDRAW_MONEY->value,
            };
            $this->save();
        }
    }

    public function resetOTP()
    {
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->otp_verified = false;
        $this->save();
    }

    /**
     * @param string $msgType
     * @return void
     */
    public function unifonic(string $msgType): void
    {
        $msg_text = __('Welcome to Rehan') . ' ';
        if ($msgType == 'login') {
            //for unifonic
            $this->action = Action::LOGIN->value;
            $msg_text .= __('Login OTP: ');
        } elseif ($msgType == 'forget-password') {
            //for unifonic
            $this->action = Action::FORGET_PASSWORD->value;
            $msg_text .= __('Forget Password OTP: ');
        } elseif ($msgType == 'register') {
            //for unifonic
            $this->action = Action::REGISTER->value;
            $msg_text .= __('Register OTP: ');
        } elseif ($msgType == 'buy-fundbox') {
            //for unifonic
            $this->action = Action::BUY_FUND->value;
            $msg_text .= __('Buy Fund OTP: ');
        } elseif ($msgType == 'withdraw-money') {
            $this->action = Action::WITHDRAW_MONEY->value;
            $msg_text .= __('Withdraw money OTP: ');
        } else {
            throw new \ErrorException('Invalid Message Type');
        }
        $this->otp = $this->otp_generator();
        $this->otp_expires_at = now()->addMinutes($this->otp_counter());
        $this->otp_verified = false;
        $msg = $msg_text . $this->otp;
        // try {
        Wrapper::sendConfirmationMessage($this->phone, $msg);
        // } catch (\Exception $exception) {
        //     throw ValidationException::withMessages([
        //         'phone_error'=>__("In")
        //     ]);
        // }
        $this->save();
    }

    /**
     * @param string $msgType
     * @return void
     */
    public function yquin(string $msgType): void
    {
        $main_reason = '';
        $params = [];
        // Never set main reason as translatable
        if ($msgType == 'register') {
            $main_reason = 'User Registration';
            $params['param1'] = __('User registration');
        } elseif ($msgType == 'payment') {
            $main_reason = 'payment process';
            $params['param1'] = __('payment process');
        } elseif ($msgType == 'updateInfo') {
            $main_reason = 'Update user information';
            $params['param1'] = __('Update user information');
        } else {
            throw new \ErrorException('Invalid Message Type');
        }
        $params['param2'] = __('us on support mail support@rehancapital.com');


        $yaqin_res = \App\Models\Absher\Wrapper::sendOtp(user: $this, main_reason: $main_reason, params: $params);

        $otp = $this->getValByKey($yaqin_res, 'verificationCode');
        $this->otp = $otp;
        $this->otp_expires_at = now()->addMinutes($this->otp_counter());
        $this->otp_verified = false;
        $this->action = Action::REGISTER->value;
        $this->save();
    }

    public function otp_counter()
    {
        return env('OTP_COUNTER') !== null && env('OTP_COUNTER') !== '' ? env('OTP_COUNTER') : 4;
    }

    public function otp_generator()
    {
        return env('APP_ENV') === 'local' ? 1234 : rand(1000, 9999);
    }

    /**
     * DEEP RELATIONSHIPS
     */

    public function buyed_my_fundboxes()
    {
        return $this->hasManyDeepFromRelations($this->directed_fundboxes(), (new Fundbox())->buyers());
    }

    public function buyFundbox($fundbox_id, $units)
    {
        set_time_limit(0);

        $fundbox = Fundbox::find($fundbox_id);
        DB::transaction(function () use ($fundbox, $units) {
            $new_id = User2Fundbox::count() > 0 ? User2Fundbox::max('id') + 1  : 1;

            $transaction = Transaction::create([
                'amount'        => FundboxPrice::get($fundbox->id, $units)->sub_total,
                'state'         => State::ACCEPTED,
                'type'          => Type::WITHDRAWAL,
                'description'   => Description::BUY_FUND,
                'user_id'       => $this->id,
                'fundbox_id'    => $fundbox->id,
            ]);

            $fees_transaction =  Transaction::create([
                'amount'        => FundboxPrice::get($fundbox->id, $units)->fees,
                'state'         => State::ACCEPTED,
                'type'          => Type::WITHDRAWAL,
                'description'   => Description::FEES,
                'user_id'       => $this->id,
                'fundbox_id'    => $fundbox->id,
            ]);

            $tax_transaction = Transaction::create([
                'amount'        => FundboxPrice::get($fundbox->id, $units)->tax,
                'state'         => State::ACCEPTED,
                'type'          => Type::WITHDRAWAL,
                'description'   => Description::TAX,
                'user_id'       => $this->id,
                'fundbox_id'    => $fundbox->id,
            ]);

            $fundbox->buyers()->attach($this->id, ['units_num' => $units, 'id' => $new_id, 'transaction_id' => $transaction->id]);

            if (app()->environment('production') ) {
                // event
                $invoice = (new Invoice())->create($fundbox->zoho_item_id, $this, $fundbox, $units);

                // logger('Invoice URL ::: '.$invoice);
                if ($invoice->code == 0) {
                    (new Journal())->createInvestJournal($transaction);
                }

                // $invoice_url = isset($invoice) && $invoice ? $invoice?->invoice?->invoice_url : null;
                $invoice_url = $invoice?->invoice?->invoice_url ?? null;
                //update Transaction
                if ($invoice_url) {
                    $transaction_updating =  $transaction->update([
                        'zoho_invoice_link' => $invoice_url,
                        'zoho_invoice_id' => $invoice->invoice->invoice_id
                    ]);
                }
                // event
              
                    try {
                        BuyFundbox::dispatch($fundbox, $units, /*$fundbox->duration,*/ $invoice_url);
                    } catch (\Exception $e) {
                        logger($e);
                        info($e);
                        Log::channel('error')->error($e->getMessage());
                    }
                
            }
        }, 5);


        if ($fundbox->available_units == 0) {
            $fundbox->update(['status' => Status::CLOSED, 'end_invest_date' => now()]);

            if (app()->environment('production') || env('is_musab')) {
                // event
                //todo: send email to admin
                try {
                    // Income Transfer To Main Bank Account
                    (new Journal())->mainToCollectionBank($fundbox);
                    // Fund Transfer To Fund Manager
                    (new Journal())->managerToCollection($fundbox);

                    ClosedFundbox::dispatch($fundbox);
                } catch (\Exception $e) {
                    info($e);
                    Log::channel('error')->error($e->getMessage());
                }
            }
        }
    }

    public function takeIban()
    {
        // $iban = VirtualIban::available()->first();
        $iban = VirtualIban::available()->where('id', $this->id)->first() ?? VirtualIban::available()->first();
        $this->iban_number = $iban->iban;
        $this->bban_number = $iban->Bban;
        $this->save();
        $iban->taken = true;
        $iban->save();
    }

    public function transactionMessage($type, $amount, $transaction)
    {
        return match ($type) {
            \App\Enums\Transaction\Type::CHARGE->value      => __('Your Wallet has been Successfully Charged in Rehan :amount has been deposited, your current balance is :wallet :time', [
                'amount' => $amount,
                'wallet' => $this->wallet,
                'time' => $transaction->created_at->format('Y-m-d H:i:s')
            ], 'ar'),

            \App\Enums\Transaction\Type::WITHDRAWAL->value  => __(':amount has been deducted has been successfully withdrawn from your wallet in Rehan . your current balance is :wallet We hope that you will follow up on our upcoming investment opportunities', [
                'amount' => $amount,
                'wallet' => $this->wallet
            ], 'ar'),
        };
    }

    public function isSuitable()
    {
        $all_questions = Question::doesntHave('related_parent')->get();
        $questions = Question::with(['choiceanswers', 'users' => fn ($q) => $q->where('user_id', $this->id)])
            ->whereHas('users', fn ($q) => $q->where('user_id', $this->id))->get();
        //check if user has answered all questions
        if ($all_questions->count() > $questions->count()) {
            return false;
        }
        $questions = $questions->map(function ($question) {
            if ($question->type == QuestionType::TEXT->value || $question->type == QuestionType::NUMBER->value) {
                return true;
            } else {
                $answer = $question->users->first()->pivot->answer;
                $choice_answer = $question->choiceanswers->where('id', $answer)->first();
                if ($choice_answer == null) {
                    return false;
                } else {
                    return $choice_answer->pivot->status == ChoiceAnswerStatus::SUITABLE;
                }
            }
        });


        if ($questions->isEmpty()) {
            return false;
        } else {
            return $questions->contains(false) ? false : true;
        }
    }
}
