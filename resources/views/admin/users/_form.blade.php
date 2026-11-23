{{-- Form tambah/ubah pengguna. Variabel: $user, $action, $method ('post' | 'put') --}}
@php($isSelf = $user->exists && $user->is(auth()->user()))
<form method="post" action="{{ $action }}" novalidate>
    @csrf
    @if ($method !== 'post') @method($method) @endif

    <x-auth.field name="name" type="text" :label="__('Name')" :value="old('name', $user->name)" autocomplete="off" :lg="false" />

    <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email', $user->email)" autocomplete="off" :lg="false" />

    <div class="mb-3">
        <label for="role" class="form-label fw-semibold">{{ __('users.role') }}</label>
        <select id="role" name="role" class="form-select{{ $errors->has('role') ? ' is-invalid' : '' }}"
                @disabled($isSelf) aria-describedby="role-help{{ $errors->has('role') ? ' role-error' : '' }}">
            @foreach (\App\Models\User::ROLES as $r)
                <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ __('users.role_' . $r) }}</option>
            @endforeach
        </select>
        @if ($isSelf)
            {{-- Select yang dinonaktifkan tidak terkirim; kirim nilai peran saat ini lewat input tersembunyi. --}}
            <input type="hidden" name="role" value="{{ $user->role }}">
            <div id="role-help" class="form-text">{{ __('users.role_self_help') }}</div>
        @else
            <span id="role-help" class="visually-hidden">{{ __('users.role') }}</span>
        @endif
        @error('role')
            <div id="role-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <x-auth.field name="password" type="password" :label="__('Password')" :required="! $user->exists"
        :hint="$user->exists ? __('users.password_edit_hint') : __('auth.password_hint')" autocomplete="new-password" :lg="false" />

    <x-auth.field name="password_confirmation" type="password" :label="__('Confirm Password')" :required="! $user->exists"
        autocomplete="new-password" :lg="false" />

    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-lumeria px-4">{{ __('users.save') }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark">{{ __('Cancel') }}</a>
    </div>
</form>
