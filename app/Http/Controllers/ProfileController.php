<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->except(['avatar', 'remove_avatar']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            $newPath = $request->file('avatar')->store('avatars', User::AVATAR_DISK);
            $user->deleteAvatarFile();
            $user->avatar_path = $newPath;
        } elseif ($request->boolean('remove_avatar')) {
            $user->deleteAvatarFile();
            $user->avatar_path = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Sajikan foto profil. Hanya pemilik foto dan admin yang boleh melihatnya.
     */
    public function avatar(Request $request, User $user): StreamedResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->isAdmin(), 403);
        abort_unless($user->hasAvatar(), 404);

        return Storage::disk(User::AVATAR_DISK)->response($user->avatar_path, null, [
            'Cache-Control'          => 'private, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
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

        // Toko tidak boleh kehilangan admin terakhir.
        if ($user->isAdmin() && ! User::where('role', User::ROLE_ADMIN)->whereKeyNot($user->getKey())->exists()) {
            return back()->withErrors(['password' => __('users.last_admin_cannot_delete')], 'userDeletion');
        }

        Auth::logout();

        $user->deleteAvatarFile();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
