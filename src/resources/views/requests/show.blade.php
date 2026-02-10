<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            申請詳細 #{{ $request->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="font-bold text-gray-700">タイトル</label>
                        <p class="text-lg">{{ $request->title }}</p>
                    </div>
                    <hr>
                    <div>
                        <label class="font-bold text-gray-700">内容</label>
                        <p class="whitespace-pre-wrap">{{ $request->content }}</p>
                    </div>
                    <hr>
                    <div>
                        <label class="font-bold text-gray-700">ステータス</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $request->status->label() }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('requests.index') }}" class="text-indigo-600 hover:underline">
                        ← 一覧に戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>