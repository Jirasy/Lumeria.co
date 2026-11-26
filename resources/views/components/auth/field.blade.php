{{--
    Satu kolom form (Bootstrap): label terhubung (for/id), pesan bantuan, dan pesan error.
    Dipakai di halaman autentikasi dan profil.
    Pemakaian:
        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" autofocus />

    Props:
        bag   nama error bag Laravel (bawaan "default"; profil memakai "updatePassword" dan "userDeletion")
        lg    true (bawaan) untuk input besar; false untuk input ukuran biasa
        horizontal  true untuk tata letak label di kiri, input di kanan (halaman profil)
        id    bila beda dari name (mis. dua form di satu halaman)
        reveal  true untuk kolom kata sandi dengan tombol "tampilkan kata sandi"

    - Kata sandi tidak pernah diisi ulang (type="password" selalu kosong).
    - Saat ada error validasi di bag mana pun, hanya kolom error pertama dari bag ini yang mendapat fokus;
      tanpa error, kolom ber-autofocus yang dipakai.
--}}
@props([
    'name',
    'label',
    'type'     => 'text',
    'value'    => null,
    'hint'     => null,
    'id'       => null,
    'bag'      => 'default',
    'lg'       => true,
    'horizontal' => false,
    'required' => true,
    'reveal'   => false,
])
@php
    $id          = $id ?: $name;
    $reveal      = $reveal && $type === 'password';
    $bagErrors   = $errors->getBag($bag);
    $hasError    = $bagErrors->has($name);
    $anyErrors   = collect($errors->getBags())->contains(fn ($b) => $b->any());
    $firstError  = $hasError && array_key_first($bagErrors->messages()) === $name;
    $focus       = $anyErrors ? $firstError : $attributes->has('autofocus');
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
@if ($horizontal)
<div class="row mb-3">
    <label for="{{ $id }}" class="col-sm-3 col-form-label text-sm-end fw-semibold">{{ $label }}</label>
    <div class="col-sm-9">
@else
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
@endif
    @if ($reveal)
    <div class="input-group auth-input-group{{ $hasError ? ' has-validation' : '' }}">
    @endif
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password' && $value !== null) value="{{ $value }}" @endif
        {{ $attributes->except('autofocus')->class(['form-control', 'form-control-lg' => $lg, 'is-invalid' => $hasError]) }}
        @required($required)
        @if ($focus) autofocus @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
    >
    @if ($reveal)
        <button type="button" class="btn auth-pw-toggle" data-password-toggle data-target="{{ $id }}"
                aria-controls="{{ $id }}" aria-pressed="false"
                aria-label="{{ __('auth.password_show') }}" title="{{ __('auth.password_show') }}"
                data-show-label="{{ __('auth.password_show') }}" data-hide-label="{{ __('auth.password_hide') }}">
            <span class="auth-pw-icon" data-icon-show><x-icon name="eye" :size="18" /></span>
            <span class="auth-pw-icon" data-icon-hide><x-icon name="eye-off" :size="18" /></span>
        </button>
    @endif
    <x-auth.error :name="$name" :id="$id" :bag="$bag" />
    @if ($reveal)
    </div>
    @endif
    @if ($hint)
        <div id="{{ $id }}-hint" class="form-text">{{ $hint }}</div>
    @endif
@if ($horizontal)
    </div>
@endif
</div>
