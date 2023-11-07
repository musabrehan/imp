<?php

namespace App\Http\Controllers\FundBoxs;

use App\Models\User;
use App\Models\Fundbox;
use App\Models\UnitsRequest;
use App\Models\User2Fundbox;
use App\Rules\OTPExpiration;
use Illuminate\Http\Request;
use App\Traits\FundBoxHelper;
use App\Enums\UnitsRequest\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\FundBox\BuyFundBoxRequest;
use Illuminate\Support\Collection;

class FundBoxesController extends Controller
{
    use FundBoxHelper;

    public function __construct()
    {
        $this->middleware('accepted_terms')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        setSeoData(title: __('Rehan') . ' | ' . __('Funds'));
        $fundbox = new Collection();

        if (auth()->check() && auth()->user()->is_upgraded) {
            $fundboxes = Fundbox::whereIn('status', [1, 2, 3])
                // where('available', 1)->where(function ($query) {
                //     //status 0: hidden, 1: can view only , 2: invested , 3: closed , 4: expired
                //     //return only status 1,2,3
                //     $query->whereIn('status', [1, 2, 3]);
                // })
                ->orderBy('status', 'asc')->paginate(5);
        } else {

            $fundboxes = Fundbox::where('available', 1)->where(function ($query) {
                //status 0: hidden, 1: can view only , 2: invested , 3: closed , 4: expired
                //return only status 1,2,3
                $query->whereIn('status', [1, 2, 3]);
            })->orderBy('status', 'asc')->paginate(5);
        }

        return env('SHOW_COMMING_SOON') === true ? view('site.comming-soon') : view('site.funds.index', compact('fundboxes'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($slug)
    {
        $fundbox = new Collection();
        if (auth()->check() && auth()->user()->is_upgraded) {
            $fundbox = Fundbox::whereIn('status', [1, 2, 3])->where('slug', $slug)->firstOrFail();
        } else {
            $fundbox = Fundbox::whereIn('status', [1, 2, 3])->where('available', 1)->where('slug', $slug)->firstOrFail();
        }
        setSeoData(title: __('site.title') . ' | ' . $fundbox->name, description: $fundbox->description);
        return env('SHOW_COMMING_SOON') === true ? view('site.comming-soon') : view('site.funds.show', compact('fundbox'));
    }

    public function sendOtp()
    {
        $user = User::find(auth()->id());
        $user->generateOTP(msgType: 'buy-fundbox', type: 'unifonic');
        return response()->json(['msg' => __('OTP Sent')]);
    }

    public function confirmOtp(Request $request)
    {
        $user = User::find(auth()->id());
        $validator = validator($request->all(), [
            'otp' => ['required', 'in:' . $user->otp, new OTPExpiration($user)]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->first()]);
        }
        $user->update([
            'otp_verified' => true
        ]);
        return response()->json(['status' => 'success', 'msg' => __('OTP Verified')]);
    }

    public function buyFundBox(BuyFundBoxRequest $request)
    {
        auth()->user()->buyFundbox($request->fundbox, $request->units);

        return redirect()->route('dashboard')->withSuccess(__('Fund Bought Successfully'));
    }

    public function returnUnits(Fundbox $fundbox, Request $request)
    {
        $request->validate([
            'reason' => 'required'
        ]);

        if (UnitsRequest::where('user_id', auth()->id())->where('fundbox_id', $fundbox->id)->where('status', Status::PENDING)->exists()) {
            return redirect()->back()->with('error', __('You have already sent a request to return the units'));
        }

        abort_if($fundbox->created_at->addMonths($fundbox->expired_after_n_months) < now(), 404);

        $user2fund = User2Fundbox::where('user_id2', auth()->id())->where('fundbox_id', $fundbox->id)->first();

        abort_if($user2fund == null, 404);

        $this->returnUnitsRequest($fundbox, $request);

        return back()->withSuccess(__('Your Request Sent Successfully'));
    }
}
