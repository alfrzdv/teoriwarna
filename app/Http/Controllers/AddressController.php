<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // View all addresses
    public function index()
    {
        $addresses = Auth::user()->user_addresses;

        return view('addresses.index', compact('addresses'));
    }

    // Create form
    public function create()
    {
        return view('addresses.create');
    }

    // Store new address
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_primary' => 'boolean'
        ]);

        // If this is primary, unset other primary addresses
        if ($request->is_primary) {
            Auth::user()->user_addresses()->update(['is_primary' => false]);
        }

        Auth::user()->user_addresses()->create($request->all());

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    // Edit form
    public function edit(UserAddress $address)
    {
        // Ensure address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('addresses.edit', compact('address'));
    }

    // Update address
    public function update(Request $request, UserAddress $address)
    {
        // Ensure address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'label' => 'required|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_primary' => 'boolean'
        ]);

        // If this is primary, unset other primary addresses
        if ($request->is_primary) {
            Auth::user()->user_addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_primary' => false]);
        }

        $address->update($request->all());

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil diupdate.');
    }

    // Set primary address
    public function setPrimary(UserAddress $address)
    {
        // Ensure address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->user_addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }

    // Delete address
    public function destroy(UserAddress $address)
    {
        // Ensure address belongs to current user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}
