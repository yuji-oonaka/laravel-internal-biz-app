<?php

namespace App\Services;

use App\Repositories\RequestRepositoryInterface;
use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
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
    public function getAllRequests(): Collection
    {
        // 修正前: return $this->requestRepository::all();
        // 修正後: アロー演算子を使用
        return $this->requestRepository->all();
    }
    /**
     * 申請作成ロジック
     */
    public function createRequest(int $userId, array $data): Request
    {
        $data['user_id'] = $userId;
        // ステータスはマイグレーションで 'draft' がデフォルトだが、
        // ビジネスルールとしてここで明示的に設定する場合もある
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
