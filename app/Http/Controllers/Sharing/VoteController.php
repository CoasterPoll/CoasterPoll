<?php

namespace ChaseH\Http\Controllers\Sharing;

use ChaseH\Helpers\CPID;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function vote(Request $request) {
        $thing = new CPID($request->thing);

        if($request->direction > 0) {
            $direction = 1;
        } elseif ($request->direction < 0) {
            $direction = -1;
        } else {
            $direction = 0;
        }

        if($thing->prefix == "Q") {
            $vote = $thing->thing->votes()->updateOrCreate([
                'voter_id' => Auth::id(),
                'comment_id' => $thing->thing->id,
                'link_id' => null,
            ]);
        } else {
            $vote = $thing->thing->votes()->updateOrCreate([
                'voter_id' => Auth::id(),
                'link_id' => $thing->thing->id,
                'comment_id' => null,
            ]);
        }

        if($vote->direction == $direction) {
            $vote->update([
                'direction' => 0,
            ]);

            $thing->thing->update([
                'score' => $thing->thing->score - $direction,
            ]);
        } elseif($vote->direction == $direction * -1) {
            $vote->update([
                'direction' => $direction,
            ]);

            $thing->thing->update([
                'score' => $thing->thing->score + ($direction * 2),
            ]);
        } else {
            $vote->update([
                'direction' => $direction,
            ]);

            $thing->thing->update([
                'score' => $thing->thing->score + $direction,
            ]);
        }

        return response()->json([
            'success' => true,
            'vote' => $vote->toArray(),
            'score' => $thing->thing->score,
        ]);
    }
}
