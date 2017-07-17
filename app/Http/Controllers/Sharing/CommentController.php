<?php

namespace ChaseH\Http\Controllers\Sharing;

use ChaseH\Helpers\CPID;
use ChaseH\Models\Sharing\Comment;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function submit(Request $request) {
        $cid = new CPID($request->commentable);
        if($cid == null) {
            return abort(404);
        }

        $this->validate($request, [
            'body' => 'max:65535',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'parent_id' => $request->get('parent', null),
            'body' => $request->get('body'),
        ]);

        $cid->thing->comments()->save($comment);
        $cid->thing->increment('comment_count');

        return back();
    }
}
