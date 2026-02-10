<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // 追加

class NotificationController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // これで $user が User モデル（Notifiableを保持）だと認識されます
        $notifications = $user->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function read(string $id)
    {
        /** @var User $user */
        $user = Auth::user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
