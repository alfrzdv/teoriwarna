<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = StoreSetting::first();

        if (!$settings) {
            $settings = StoreSetting::create([
                'store_name' => config('app.name', 'Teori Warna Store'),
                'address' => '',
                'email' => '',
                'phone' => ''
            ]);
        }

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'business_hours' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Payment Gateway Settings
            'midtrans_enabled' => 'nullable|boolean',
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|boolean',
            'bank_transfer_enabled' => 'nullable|boolean',
            'e_wallet_enabled' => 'nullable|boolean',
            'cod_enabled' => 'nullable|boolean',

            // Shipping Settings
            'shipping_regular_cost' => 'nullable|integer|min:0',
            'shipping_regular_name' => 'nullable|string|max:255',
            'shipping_regular_estimation' => 'nullable|string|max:255',
            'shipping_express_cost' => 'nullable|integer|min:0',
            'shipping_express_name' => 'nullable|string|max:255',
            'shipping_express_estimation' => 'nullable|string|max:255',
            'shipping_sameday_cost' => 'nullable|integer|min:0',
            'shipping_sameday_name' => 'nullable|string|max:255',
            'shipping_sameday_estimation' => 'nullable|string|max:255',
            'free_shipping_enabled' => 'nullable|boolean',
            'free_shipping_minimum' => 'nullable|integer|min:0',
        ]);

        $settings = StoreSetting::first();

        if (!$settings) {
            $settings = new StoreSetting();
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }

            $logoPath = $request->file('logo')->store('settings', 'public');
            $validated['logo'] = $logoPath;
        }

        // Convert checkbox values to boolean
        $validated['midtrans_enabled'] = $request->has('midtrans_enabled');
        $validated['midtrans_is_production'] = $request->has('midtrans_is_production');
        $validated['bank_transfer_enabled'] = $request->has('bank_transfer_enabled');
        $validated['e_wallet_enabled'] = $request->has('e_wallet_enabled');
        $validated['cod_enabled'] = $request->has('cod_enabled');
        $validated['free_shipping_enabled'] = $request->has('free_shipping_enabled');

        $settings->fill($validated);
        $settings->save();

        return back()->with('success', 'Pengaturan toko berhasil diupdate.');
    }
}
