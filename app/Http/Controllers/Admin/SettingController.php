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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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

        $settings->fill($validated);
        $settings->save();

        return back()->with('success', 'Pengaturan toko berhasil diupdate.');
    }
}
