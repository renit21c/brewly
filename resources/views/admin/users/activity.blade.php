@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Activity Log</h2>
        <form class="flex gap-2" method="GET">
            <select name="user_id" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Users</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-2 bg-cafe-gold text-white rounded-lg hover:bg-opacity-90 transition">Filter</button>
        </form>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-lg shadow p-6">
        @if($logs->isEmpty())
        <p class="text-gray-500 text-center py-12">No activity logs found</p>
        @else
        <div class="space-y-4">
            @foreach($logs as $log)
            <div class="flex gap-4 pb-4 border-b last:border-b-0 hover:bg-gray-50 p-3 rounded transition">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-cafe-gold bg-opacity-10">
                        @if($log->action === 'login')
                            <svg class="w-5 h-5 text-cafe-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        @elseif($log->action === 'logout')
                            <svg class="w-5 h-5 text-cafe-rust" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        @elseif($log->action === 'shift_opened')
                            <svg class="w-5 h-5 text-cafe-peach" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        @elseif($log->action === 'shift_closed')
                            <svg class="w-5 h-5 text-cafe-latte" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @elseif($log->action === 'void_item')
                            <svg class="w-5 h-5 text-cafe-rust" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2h12a2 2 0 110 4H7a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v10"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-cafe-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-900 font-semibold">
                                <span class="text-cafe-gold">{{ $log->user->name }}</span>
                                <span class="text-gray-600 font-normal">{{ $log->action }}</span>
                            </p>
                            @if($log->description)
                            <p class="text-gray-600 text-sm mt-1">{{ $log->description }}</p>
                            @endif
                            @if($log->ip_address)
                            <p class="text-gray-400 text-xs mt-1">IP: {{ $log->ip_address }}</p>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-gray-500 text-sm">{{ $log->created_at->diffForHumans() }}</p>
                            <p class="text-gray-400 text-xs">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
