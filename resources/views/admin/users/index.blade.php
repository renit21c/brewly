@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Manage Users</h2>
        <a href="{{ route('users.create') }}" class="px-6 py-2 bg-cafe-gold text-white rounded-lg hover:bg-opacity-90 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add User
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-gold">
            <p class="text-gray-600 text-sm font-medium">Total Users</p>
            <p class="text-3xl font-bold text-cafe-gold mt-2">{{ count($users) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-peach">
            <p class="text-gray-600 text-sm font-medium">Admin Users</p>
            <p class="text-3xl font-bold text-cafe-peach mt-2">{{ collect($users)->filter(fn($u) => $u['user']->role === 'admin')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cafe-sky">
            <p class="text-gray-600 text-sm font-medium">Cashier Users</p>
            <p class="text-3xl font-bold text-cafe-sky mt-2">{{ collect($users)->filter(fn($u) => $u['user']->role === 'cashier')->count() }}</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">User List</h3>
        @if(count($users) === 0)
        <p class="text-gray-500 text-center py-8">No users found</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left text-gray-700">Email</th>
                        <th class="px-4 py-2 text-left text-gray-700">Role</th>
                        <th class="px-4 py-2 text-left text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-gray-700">Last Login</th>
                        <th class="px-4 py-2 text-left text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-800 font-medium">{{ $item['user']->name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item['user']->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $item['user']->role === 'admin' ? 'bg-cafe-gold text-white' : 'bg-cafe-sky text-white' }}">
                                {{ ucfirst($item['user']->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            @if($item['last_login'])
                                {{ $item['last_login']->created_at->format('M d, Y H:i') }}
                            @else
                                <span class="text-gray-400">Never</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('users.edit', $item['user']->id) }}" class="p-2 text-cafe-gold hover:bg-cafe-gold hover:bg-opacity-10 rounded transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button class="p-2 text-cafe-rust hover:bg-cafe-rust hover:bg-opacity-10 rounded transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
