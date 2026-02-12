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

    public function index(HttpRequest $httpRequest)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 検索条件（ステータスなど）を取得
        $filters = $httpRequest->only(['status']);

        // 【重要】管理者でない場合は、強制的に自分のIDをフィルターにセットする
        if (!$user->isAdmin()) {
            $filters['user_id'] = $user->id;
        }

        // 全ての取得ロジックをサービスに任せる
        $requests = $this->requestService->getAllRequests($filters)->withQueryString();

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
     * 新規作成画面を表示
     */
    public function create()
    {
        // resources/views/requests/create.blade.php を表示する
        return view('requests.create');
    }

    public function show(int $id)
    {
        // 変数名を $requestModel から $request に変更します
        $request = $this->requestService->getRequestById($id);

        if (!$request) {
            abort(404);
        }

        // 認可チェックも $request を使用
        $this->authorize('view', $request);

        // これで compact('request') が正常に動作します
        return view('requests.show', compact('request'));
    }

    /**
     * 承認処理
     */
    public function approve(int $id)
    {
        $requestModel = $this->requestService->getRequestById($id); // ServiceにfindById相当のメソッドが必要
        $this->authorize('approve', $requestModel);

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
        $requestModel = $this->requestService->getRequestById($id);
        $this->authorize('reject', $requestModel);

        if ($this->requestService->rejectRequest($id, Auth::id())) {
            return back()->with('status', '申請を却下しました。');
        }
        return back()->withErrors('却下処理に失敗しました。');
    }
}
