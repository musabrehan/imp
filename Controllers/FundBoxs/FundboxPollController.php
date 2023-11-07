<?php

namespace App\Http\Controllers\FundBoxs;

use App\Models\Poll;
use App\Models\User;
use App\Models\Fundbox;
use App\Enums\Poll\Status;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class FundboxPollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Fundbox $fundbox,Poll $poll)
    {
        abort_if($poll->status == Status::CLOSED->value,404);

        setSeoData(title:$poll->title);
        
        $delay           = $poll->end_date == null || Carbon::parse($poll->end_date) < now() ? null : now()->floatDiffInRealMinutes($poll->end_date);
        $selected_option = PollOption::where('poll_id',$poll->id)->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->first() ?? null;
        $total_votes     = User::whereHas('pollOptions',fn($q)=>$q->where('poll_id',$poll->id))->count(). ' ' . __('Votes');
        return view('site.funds.voting',compact('fundbox','poll','delay','total_votes','selected_option'));
    }

    public function update(Request $request, Fundbox $fundbox,Poll $poll)
    {
        abort_if($poll->status == Status::CLOSED->value,404);

        $option = PollOption::findOrFail($request->option);
        if(Poll::whereHas('options.users',fn($q)=>$q->where('user_id',auth()->user()->id))->where('id',$poll->id)->exists()){
            $message = __('You have already voted');
            $status  = 422;
        }else {
            $option->users()->attach(auth()->user()->id);
            $message = __('Your vote has been recorded');
            $status  = 200;
        }
        return response()->json([
            'message'=> $message,
            'total_votes'  => User::whereHas('pollOptions',fn($q)=>$q->where('poll_id',$poll->id))->count(). ' ' . __('Votes'),
            'options' => $poll->options->map(function($option){
                return [
                    'id'    => $option->id,
                    'votes' => $option->users->count(). ' ' . __('Votes'),
                    'percentage' => $option->users->count() > 0 
                        ? round(($option->users->count() / $option->poll->options->sum(fn($q)=>$q->users->count())) * 100) 
                        : 0,
                ];
            },$status),
        ]);
    }

    public function destroy(Fundbox $fundbox,Poll $poll)
    {
        abort_if($poll->status == Status::CLOSED->value,404);

        PollOption::where('poll_id',$poll->id)->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->first()->users()->detach(auth()->user()->id);

        return response()->json([
            'message'=> __('Your vote has been removed'),
            'total_votes'  => User::whereHas('pollOptions',fn($q)=>$q->where('poll_id',$poll->id))->count(). ' ' . __('Votes'),
        ]);
    }
}
