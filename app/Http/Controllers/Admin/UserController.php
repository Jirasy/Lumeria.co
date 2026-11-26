<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Kelola pengguna (CRUD). Seluruh rute ada di group middleware ['auth', 'admin'].
 *
 * Pengaman:
 *  - admin tidak bisa menghapus akunnya sendiri;
 *  - admin tidak bisa mengubah perannya sendiri (agar toko tidak pernah kehilangan admin terakhir).
 */
class UserController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $role   = $request->query('role');

        $users = User::query()
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('email', 'like', $like));
            })
            ->when(in_array($role, User::ROLES, true), fn ($q) => $q->where('role', $role))
            ->orderByRaw("role = ? desc", [User::ROLE_ADMIN])
            ->orderBy('name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.users.index', [
            'users'  => $users,
            'search' => $search,
            'role'   => in_array($role, User::ROLES, true) ? $role : '',
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['user' => new User(['role' => User::ROLE_CUSTOMER])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'role'     => ['required', Rule::in(User::ROLES)],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = new User();
        $user->fill(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
        // Peran tidak mass-assignable; diisi eksplisit di sini (rute ini hanya bisa dibuka admin).
        $user->forceFill(['role' => $data['role'], 'email_verified_at' => now()])->save();

        return redirect()->route('admin.users.index')->with('status', __('users.created', ['name' => $user->name]));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'role'     => ['required', Rule::in(User::ROLES)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($user->is($request->user()) && $data['role'] !== $user->role) {
            return back()->withInput()->withErrors(['role' => __('users.cannot_change_own_role')]);
        }

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = now();
        }

        $user->forceFill(['role' => $data['role']])->save();

        return redirect()->route('admin.users.index')->with('status', __('users.updated', ['name' => $user->name]));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return redirect()->route('admin.users.index')->with('error', __('users.cannot_delete_self'));
        }

        $name = $user->name;
        $user->deleteAvatarFile();
        $user->delete(); // pesanan lama tetap tersimpan (orders.user_id menjadi null)

        return redirect()->route('admin.users.index')->with('status', __('users.deleted', ['name' => $name]));
    }
}
