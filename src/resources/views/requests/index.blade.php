<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('申請一覧') }}
            </h2>
            <a href="{{ route('requests.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                新規申請
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg">
                {{ session('status') }}
            </div>
            @endif
            <div class="mb-6 bg-white p-4 rounded shadow-sm">
                <form action="{{ route('requests.index') }}" method="GET" class="flex items-end gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ステータス</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">すべて</option>
                            @foreach(App\Enums\RequestStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button>
                        検索
                    </x-primary-button>
                    <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:underline pb-2">リセット</a>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タイトル</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">申請者</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">申請日</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($requests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{-- name ではなく追加した label() を使って日本語表示にします --}}
                                        {{ $request->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('Y-m-d H:i') }}</td>

                                {{-- アクション列を1つに統合 --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-3">
                                        {{-- 詳細リンク: href="#" を route() に修正 --}}
                                        @can('view', $request)
                                        <a href="{{ route('requests.show', $request->id) }}" class="text-indigo-600 hover:text-indigo-900">詳細</a>
                                        @endcan

                                        {{-- 編集リンク（必要な場合） --}}
                                        @can('update', $request)
                                        <a href="#" class="text-green-600 hover:text-green-900">編集</a>
                                        @endcan

                                        {{-- 承認ボタン --}}
                                        @can('approve', $request)
                                        <form action="{{ route('requests.approve', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-bold" onclick="return confirm('承認してもよろしいですか？')">
                                                承認
                                            </button>
                                        </form>
                                        @endcan

                                        {{-- 却下ボタン --}}
                                        @can('reject', $request)
                                        <form action="{{ route('requests.reject', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold" onclick="return confirm('却下してもよろしいですか？')">
                                                却下
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>