<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Services\RequestService;
use App\Models\Request;
use App\Models\User; // 追加
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequestController extends Controller
{
    use AuthorizesRequests;

    // Intelephenseのためにプロパティを明示的に宣言
    protected RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user(); // auth()->user() より Auth::user() の方が型認識されやすい

        // 管理者は全件、一般社員は自分の分のみ
        $requests = $user->isAdmin()
            ? $this->requestService->getAllRequests()
            : $user->requests; // 後述するUserモデルへのリレーション追加が必要

        return view('requests.index', compact('requests'));
    }

    public function store(StoreRequestRequest $request)
    {
        // $this->authorize('create', Request::class); // Policy未定義メソッドならコメントアウト

        // auth()->id() の代わりに Auth::id() を使用
        $this->requestService->createRequest(Auth::id(), $request->validated());

        return redirect()->route('requests.index')->with('status', '申請を作成しました。');
    }

    /**
     * 承認処理
     */
    public function approve(int $id)
    {
        // Policyで管理者権限をチェック（後ほどPolicyに定義）
        $this->authorize('admin-only');

        if ($this->requestService->approveRequest($id, Auth::id())) {
            return back()->with('status', '申請を承認しました。');
        }

        return back()->withErrors('承認処理に失敗しました。');
    }

    /**
     * 却下処理
     */
    public function reject(int $id)
    {
        $this->authorize('admin-only');

        if ($this->requestService->rejectRequest($id, Auth::id())) {
            return back()->with('status', '申請を却下しました。');
        }

        return back()->withErrors('却下処理に失敗しました。');
    }
}
