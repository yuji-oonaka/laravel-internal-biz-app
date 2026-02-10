<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Services\RequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(
        protected RequestService $requestService
    ) {}

    /**
     * 一覧表示
     */
    public function index()
    {
        $requests = $this->requestService->getAllRequests();
        return view('requests.index', compact('requests'));
    }

    /**
     * 作成画面
     */
    public function create()
    {
        return view('requests.create');
    }

    /**
     * 保存処理
     */
    public function store(StoreRequestRequest $request)
    {
        // バリデーション済みデータの取得
        $validated = $request->validated();

        // ログインユーザーIDと共にサービスへ渡す
        $this->requestService->createRequest(Auth::id(), $validated);

        return redirect()->route('requests.index')
            ->with('status', '申請を保存しました。');
    }
}
