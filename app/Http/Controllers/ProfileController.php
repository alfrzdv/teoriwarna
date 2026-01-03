<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Helpers\ImageHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'addresses' => $request->user()->user_addresses,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        \Log::info('Profile update request data:', [
            'has_profile_picture' => $request->has('profile_picture'),
            'filled_profile_picture' => $request->filled('profile_picture'),
            'profile_picture_data' => $request->profile_picture ? substr($request->profile_picture, 0, 100) : null,
            'all_keys' => array_keys($request->all())
        ]);

        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle profile picture upload (base64 from cropper or regular file)
        if ($request->filled('profile_picture')) {
            \Log::info('Processing profile picture upload');

            // Delete old profile picture and thumbnail if exists
            if ($user->profile_picture) {
                ImageHelper::deleteWithThumbnail($user->profile_picture);
            }

            $profilePictureData = $request->input('profile_picture');

            // Check if it's base64 data from cropper
            if (preg_match('/^data:image\/(\w+);base64,/', $profilePictureData, $matches)) {
                \Log::info('Processing base64 image');

                // Extract base64 string
                $base64Image = substr($profilePictureData, strpos($profilePictureData, ',') + 1);
                $imageData = base64_decode($base64Image);

                // Generate filename
                $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $filename = 'profile_' . uniqid() . '.' . $extension;
                $path = 'profile-pictures/' . $filename;

                // Store the image
                Storage::disk('public')->put($path, $imageData);

                // Create thumbnail
                try {
                    $thumbnailPath = ImageHelper::createThumbnail($path, 150, 150);
                } catch (\Exception $e) {
                    \Log::warning('Thumbnail generation failed: ' . $e->getMessage());
                }

                $user->profile_picture = $path;
            } elseif ($request->hasFile('profile_picture')) {
                \Log::info('Processing file upload');

                // Handle regular file upload (fallback)
                $result = ImageHelper::uploadWithThumbnail(
                    $request->file('profile_picture'),
                    'profile-pictures',
                    150,
                    150
                );
                $user->profile_picture = $result['original'];
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile picture only.
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old profile picture and thumbnail if exists
        if ($user->profile_picture) {
            ImageHelper::deleteWithThumbnail($user->profile_picture);
        }

        // Handle file upload
        if ($request->hasFile('profile_picture')) {
            $result = ImageHelper::uploadWithThumbnail(
                $request->file('profile_picture'),
                'profile-pictures',
                150,
                150
            );
            $user->profile_picture = $result['original'];
            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-picture-updated');
        }

        return Redirect::route('profile.edit')->withErrors(['profile_picture' => 'Invalid image format']);
    }

    /**
     * Delete the user's profile picture.
     */
    public function deleteProfilePicture(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_picture) {
            ImageHelper::deleteWithThumbnail($user->profile_picture);
            $user->profile_picture = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-picture-deleted');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
