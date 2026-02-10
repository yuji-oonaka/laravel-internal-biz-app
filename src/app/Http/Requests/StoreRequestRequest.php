<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    /**
     * 認可: 今回はログイン済みならOKとする（詳細はPolicyで制御）
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ];
    }

    /**
     * 項目名の日本語化（langファイルで一括管理も可能だが、個別定義もよく使う）
     */
    public function attributes(): array
    {
        return [
            'title'   => 'タイトル',
            'content' => '申請内容',
        ];
    }
}
