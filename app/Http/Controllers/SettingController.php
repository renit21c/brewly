<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = [
            'app_name' => 'Brewly',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'tax_rate' => 10, // percentage
            'service_charge_rate' => 5, // percentage
            'business_phone' => '+62-812-3456-7890',
            'business_email' => 'info@brewly.com',
            'business_address' => 'Jl. Coffee Street No. 123, Jakarta',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        // Settings would typically be stored in a settings table or config
        // For now, this is a placeholder for future implementation
        
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
