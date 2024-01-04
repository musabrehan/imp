<?php

namespace App\Http\Controllers\Wallet;

use App\Enums\BankAccount\Accepted;
use App\Models\User;
use App\Models\Fundbox;
use App\Models\Bankaccount;
use App\Models\Transaction;
use App\Models\UnitsRequest;
use App\Rules\OTPExpiration;
use Illuminate\Http\Request;
use App\Enums\Transaction\Type;
use App\Enums\Transaction\State;
use App\Enums\Transaction\Status;
use App\Models\Bank\OnlinePayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Wallet\TransactionRequest;

class WalletController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        setSeoData(title: __('Rehan') . ' | ' . __('Wallet'));
        $bankAccountsWithTrashed = Bankaccount::where('user_id', auth()->id())->withTrashed()->get();
        $bankAccounts = Bankaccount::where('user_id', auth()->id())->where('accepted', '=', Accepted::ACCEPTED->value)->get();
        $transactions = Transaction::walletFilter()->where('user_id', auth()->id())->orderBy('created_at','desc')->paginate(5);
        $investments  = Fundbox::withAllAmountWhereBuyer(auth()->id());
        $user_units_requests = UnitsRequest::where('user_id', auth()->id())->where('status', \App\Enums\UnitsRequest\Status::PENDING->value)->count();

        return view('site.wallet.index', compact('bankAccounts', 'transactions', 'investments', 'user_units_requests'));
    }

    public function store(TransactionRequest $request)
    {

        // checking if request reached here 
        logger('storing withdraw request');

        $transaction = Transaction::create([
            'amount' => $request->value,
            'state' => State::PENDING->value,
            'type' => Type::WITHDRAWAL->value,
            'bankaccount_id' => $request->bankaccount_id,
            'user_id' => auth()->id(),
        ]);

        new OnlinePayment(auth()->user(), Bankaccount::find($request->bankaccount_id), $request->value, $transaction);

        return redirect()->route('wallet.index')->withSuccess(__('Transaction Sent Successfully'));
    }

    public function sendOtp()
    {
        // checking for otp sent 
        logger('Sending OTP');
        $user = User::find(auth()->id());
        $user->generateOTP(msgType: 'withdraw-money', type: 'unifonic');
        return response()->json(['msg' => __('OTP Sent')]);
    }

    public function confirmOtp(Request $request)
    {
        // checking for otp  conf 
        logger('Confirming OTP');

        $user = User::find(auth()->id());
        $request->validate([
            'otp' => ['required', 'in:' . $user->otp, new OTPExpiration($user)]
        ]);
        $user->update([
            'otp_verified' => true
        ]);
    }
}
