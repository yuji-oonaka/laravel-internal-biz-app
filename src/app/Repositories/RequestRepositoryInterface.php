<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RequestRepositoryInterface
{
    /**
     * 条件付きで全件取得
     */
    public function all(array $filters = []): LengthAwarePaginator;

    /**
     * 特定のIDで1件取得
     */
    public function findById(int $id): ?Request;

    /**
     * 申請の保存
     */
    public function store(array $data): Request;

    /**
     * 申請の更新
     */
    public function update(int $id, array $data): bool;

    public function updateStatus(int $id, array $attributes): bool;

    
}
