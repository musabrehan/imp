<?php

namespace App\Http\Controllers;

use App\Models\Upgraderequest;
use App\Events\NewUpgradeRequest;
use App\Enums\UpgradeRequest\Status;
use App\Http\Requests\API\UpgradeFormRequest;
use Exception;
use Illuminate\Http\Request;


class UpgradeController extends Controller
{
    public function index() {
        setSeoData(title: __('Rehan') .' | '. __('Expert Upgrade'));
        return view("site.upgrade-account.upgrade-form");
    }

    public function store(UpgradeFormRequest $request)
    {
        $exist =Upgraderequest::where('user_id',auth()->id())->where(function ($query){
            $query->where('status',Status::ACCEPTED->value)
                ->orwhere('status' ,Status::PENDING->value);
        })->exists();
        if( $exist ){
            return redirect()->back()->with( 'error' , __('You Have Sent Form Before we are reviewing and we will response soon'));
        }

        $upgradeRequest = Upgraderequest::create(['user_id' => auth()->id() ,'status' => Status::PENDING->value]);
        $files = collect($request->validated()['upgrade']);
        $files->each(function ($value,$key) use ($upgradeRequest) {

            $upgradeRequest->addMedia($value)->toMediaCollection($key,'public');
        });

        // event
        NewUpgradeRequest::dispatch($upgradeRequest, auth()->user());

        return redirect()->route('fundbox.index')->with('success',__('your request will be reviewed'));
    }


    // upgrade user account to view funds before release date //
    function upgradeUserAccount(Request $request) : mixed {
        try{
            // logger('Upgrading');
            auth()->user()->update(['is_upgraded'=> true , 'upgrade_date'=>now()]);
            return response()->json(['message'=> 'success'], 200,);
        }catch (Exception $e){
            return response()->json(['error'=> $e], 200,);
        }
    }
}
