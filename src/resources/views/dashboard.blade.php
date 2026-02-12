<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @if(auth()->user()->isAdmin())
                {{-- 管理者向け：承認待ち --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-400">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">承認待ちの申請（全体）</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['pending_count'] }} 件</div>
                        <div class="mt-4">
                            <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold">
                                確認する →
                            </a>
                        </div>
                    </div>
                </div>
                @else
                {{-- 一般ユーザー向け：下書き --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-gray-400">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">作成中の下書き</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['draft_count'] }} 件</div>
                    </div>
                </div>

                {{-- 一般ユーザー向け：申請中 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-400">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase">結果待ちの申請</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['pending_count'] }} 件</div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>