<?php

namespace ChaseH\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function mark(Request $request) {
        if($request->input('id') == null) {
            $user = Auth::user();
            $user->unreadNotifications->markAsRead();

            Cache::forget('notif:'.Auth::id());
        } else {
            DB::table('notifications')->where('id', $request->input('id'))->update([
                'read_at' => Carbon::now(),
            ]);

            Cache::forget('notif:'.Auth::id());
        }

        return response()->json([
            'message' => "Successfully marked as read."
        ]);
    }

    public function all() {
        $notifications = Auth::user()->notifications()->where('created_at', '>=', Carbon::now()->subWeek())->get();

        return view('users.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function delete(Request $request) {
        try {
            $notification = Auth::user()->notifications()->where('id', $request->input('id'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $notification->delete();

        return response()->json([
            'message' => "It's gone!",
            'status' => "success",
        ]);
    }
}
