@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <h2 class="text-3xl font-bold text-gray-800">Settings</h2>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Business Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Business Settings</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Business Name</label>
                    <input type="text" name="app_name" value="{{ $settings['app_name'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold" readonly>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Business Phone</label>
                    <input type="tel" name="business_phone" value="{{ $settings['business_phone'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Business Email</label>
                    <input type="email" name="business_email" value="{{ $settings['business_email'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Business Address</label>
                    <textarea name="business_address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">{{ $settings['business_address'] }}</textarea>
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Financial Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Currency</label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                        <option value="IDR" {{ $settings['currency'] === 'IDR' ? 'selected' : '' }}>IDR (Indonesian Rupiah)</option>
                        <option value="USD" {{ $settings['currency'] === 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                        <option value="SGD" {{ $settings['currency'] === 'SGD' ? 'selected' : '' }}>SGD (Singapore Dollar)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Service Charge (%)</label>
                    <input type="number" name="service_charge_rate" value="{{ $settings['service_charge_rate'] }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">System Settings</h3>
            
            <div>
                <label class="block text-gray-700 font-medium mb-2">Timezone</label>
                <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cafe-gold">
                    <option value="Asia/Jakarta" {{ $settings['timezone'] === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                    <option value="Asia/Bangkok" {{ $settings['timezone'] === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (ICT)</option>
                    <option value="Asia/Singapore" {{ $settings['timezone'] === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore (SGT)</option>
                    <option value="UTC" {{ $settings['timezone'] === 'UTC' ? 'selected' : '' }}>UTC</option>
                </select>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-cafe-gold to-cafe-peach rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-opacity-90 text-sm">Current Tax Rate</p>
                        <p class="text-3xl font-bold mt-1">{{ $settings['tax_rate'] }}%</p>
                    </div>
                    <svg class="w-12 h-12 text-white text-opacity-30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-cafe-sky to-cafe-latte rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-opacity-90 text-sm">Service Charge</p>
                        <p class="text-3xl font-bold mt-1">{{ $settings['service_charge_rate'] }}%</p>
                    </div>
                    <svg class="w-12 h-12 text-white text-opacity-30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-cafe-rust to-cafe-peach rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-opacity-90 text-sm">Currency</p>
                        <p class="text-3xl font-bold mt-1">{{ $settings['currency'] }}</p>
                    </div>
                    <svg class="w-12 h-12 text-white text-opacity-30" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-cafe-gold text-white rounded-lg hover:bg-opacity-90 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Settings
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
