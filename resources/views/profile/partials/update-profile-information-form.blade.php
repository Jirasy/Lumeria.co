<section aria-labelledby="profile-information-title">
    <h2 id="profile-information-title" class="h4 fw-bold mb-1">{{ __('profile.title') }}</h2>
    <p class="text-muted pb-3 mb-4 border-bottom">{{ __('profile.subtitle') }}</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('patch')

        <div class="row g-4">
            {{-- Foto profil: di atas pada layar kecil, di kanan pada layar sedang ke atas --}}
            <div class="col-md-4 order-first order-md-last profile-photo-col text-center">
                <div id="avatar-preview" class="d-inline-block mb-3">
                    <x-user-avatar :user="$user" :size="100" />
                </div>

                <div>
                    <input id="avatar" name="avatar" type="file" class="visually-hidden profile-file"
                           accept="image/png,image/jpeg" data-max-bytes="{{ \App\Models\User::AVATAR_MAX_KB * 1024 }}"
                           data-too-big="{{ __('profile.image_too_big') }}"
                           aria-describedby="avatar-help{{ $errors->has('avatar') ? ' avatar-error' : '' }}"
                           @if ($errors->has('avatar')) aria-invalid="true" @endif>
                    <label for="avatar" class="btn btn-outline-dark">{{ __('profile.choose_image') }}</label>
                </div>

                <p id="avatar-help" class="text-muted small mt-3 mb-0">
                    {{ __('profile.image_max') }}<br>{{ __('profile.image_format') }}
                </p>

                <div id="avatar-error" class="text-danger small mt-2" @if (! $errors->has('avatar')) hidden @endif>
                    @foreach ($errors->get('avatar') as $message)
                        <div>{{ $message }}</div>
                    @endforeach
                </div>

                @if ($user->hasAvatar())
                    <div class="form-check d-inline-block text-start mt-3">
                        <input class="form-check-input" type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                        <label class="form-check-label" for="remove_avatar">{{ __('profile.remove_photo') }}</label>
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <x-auth.field name="name" type="text" :label="__('Name')" :value="old('name', $user->name)" autocomplete="name" :lg="false" :horizontal="true" />

                <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email', $user->email)" autocomplete="username" :lg="false" :horizontal="true" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="alert alert-warning mb-0" role="status">
                                {{ __('Your email address is unverified.') }}
                                <button type="submit" form="send-verification" class="btn btn-link p-0 align-baseline">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </div>
                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success mt-2 mb-0" role="status">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <x-auth.field name="phone" type="tel" :label="__('profile.phone')" :value="old('phone', $user->phone)" :hint="__('profile.phone_hint')"
                    autocomplete="tel" inputmode="tel" :required="false" :lg="false" :horizontal="true" />

                <fieldset class="row mb-3">
                    <legend class="col-sm-3 col-form-label text-sm-end fw-semibold pt-0 fs-6">{{ __('profile.gender') }}</legend>
                    <div class="col-sm-9">
                        <div class="d-flex flex-wrap gap-3">
                            @foreach (\App\Models\User::GENDERS as $gender)
                                <div class="form-check">
                                    <input class="form-check-input{{ $errors->has('gender') ? ' is-invalid' : '' }}" type="radio" name="gender"
                                           id="gender_{{ $gender }}" value="{{ $gender }}" @checked(old('gender', $user->gender) === $gender)>
                                    <label class="form-check-label" for="gender_{{ $gender }}">{{ __('profile.' . $gender) }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('gender')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                <x-auth.field name="birth_date" type="date" :label="__('profile.birth_date')"
                    :value="old('birth_date', $user->birth_date?->toDateString())" max="{{ now()->subDay()->toDateString() }}"
                    autocomplete="bday" :required="false" :lg="false" :horizontal="true" />

                <div class="row">
                    <div class="col-sm-9 offset-sm-3 d-flex flex-wrap align-items-center gap-3">
                        <button type="submit" class="btn btn-lumeria px-4">{{ __('Save') }}</button>

                        @if (session('status') === 'profile-updated')
                            <span class="text-success-emphasis fw-semibold" role="status">{{ __('Saved.') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts')
    <script>
        (function () {
            var input = document.getElementById('avatar');
            var holder = document.getElementById('avatar-preview');
            var error = document.getElementById('avatar-error');
            if (!input || !holder) { return; }

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file) { return; }

                // Batas 1 MB juga dicek di server; ini hanya agar pesan muncul lebih cepat.
                if (file.size > Number(input.dataset.maxBytes)) {
                    input.value = '';
                    error.textContent = input.dataset.tooBig;
                    error.hidden = false;
                    return;
                }

                error.hidden = true;
                error.textContent = '';

                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = '';
                img.width = 100;
                img.height = 100;
                img.className = 'user-avatar rounded-circle';
                img.style.width = '100px';
                img.style.height = '100px';
                holder.replaceChildren(img);
            });
        })();
    </script>
@endpush
