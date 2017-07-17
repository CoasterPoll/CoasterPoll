<?php

namespace ChaseH\Http\Controllers\Sharing;

use ChaseH\Models\Sharing\LinkReport;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function report(Request $request) {
        $this->validate($request, [
            'reason' => 'required',
            'link' => 'required_without:comment',
            'comment' => 'required_without:link',
        ]);

        $report = LinkReport::create([
            'link_id' => $request->link,
            'comment_id' => $request->comment,
            'reason' => $request->reason,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message'=> "Thanks for letting us know!",
        ]);
    }

    public function view(Request $request) {
        $reports = LinkReport::select('reason')->where('link_id', $request->link)->orWhere('comment_id', $request->comment)->get();

        return response()->json($reports->toArray());
    }
}
