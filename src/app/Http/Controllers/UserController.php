<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 追加

class UserController extends Controller
{
    public function index()
    {
        // Auth::id() を使用することで、IDEのエラーを回避
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('status', 'ユーザーの状態を更新しました。');
    }
}
