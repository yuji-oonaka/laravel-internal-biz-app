<?php

namespace App\Services;

use App\Repositories\RequestRepositoryInterface;
use App\Models\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\RequestStatus;
use Carbon\Carbon;
use App\Notifications\RequestStatusChanged;

class RequestService
{
    // Repository Interface をインジェクション
    public function __construct(
        protected RequestRepositoryInterface $requestRepository // ここで型として使用
    ) {}

    /**
     * 申請一覧の取得ロジック
     */
    public function getAllRequests(array $filters = []): LengthAwarePaginator
    {
        // リポジトリの all() は既に Paginator を返すようになっているので、そのまま返せばOK
        return $this->requestRepository->all($filters);
    }
    /**
     * 申請作成ロジック
     */
    public function createRequest(int $userId, array $data): Request
    {
        $data['user_id'] = $userId;
        $data['status'] = RequestStatus::PENDING; // 一時的に DRAFT ではなく PENDING にする
        return $this->requestRepository->store($data);
    }

    /**
     * 申請の承認処理
     */
    public function approveRequest(int $requestId, int $adminId): bool
    {
        $request = $this->requestRepository->findById($requestId);

        if (!$request || $request->status !== RequestStatus::PENDING) {
            return false;
        }

        // 変数 $result に戻り値を代入するように修正
        $result = $this->requestRepository->updateStatus($requestId, [
            'status' => RequestStatus::APPROVED,
            'approved_by' => $adminId,
            'approved_at' => Carbon::now(),
        ]);

        if ($result) {
            // 申請者に通知を送信
            $request->user->notify(new RequestStatusChanged($request));
        }

        return $result;
    }

    /**
     * 申請の却下処理
     */
    public function rejectRequest(int $requestId, int $adminId): bool
    {
        $request = $this->requestRepository->findById($requestId);

        if (!$request || $request->status !== RequestStatus::PENDING) {
            return false;
        }

        // 変数 $result に戻り値を代入
        $result = $this->requestRepository->updateStatus($requestId, [
            'status' => RequestStatus::REJECTED,
            'approved_by' => $adminId,
            'approved_at' => Carbon::now(),
        ]);

        if ($result) {
            $request->user->notify(new RequestStatusChanged($request));
        }

        return $result;
    }

    public function getRequestById(int $id): ?\App\Models\Request
    {
        return $this->requestRepository->findById($id);
    }
}
