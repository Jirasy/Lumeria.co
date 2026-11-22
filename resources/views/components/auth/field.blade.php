{{--
    Satu kolom form (Bootstrap): label terhubung (for/id), pesan bantuan, dan pesan error.
    Dipakai di halaman autentikasi dan profil.
    Pemakaian:
        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" autofocus />

    Props:
        bag   nama error bag Laravel (bawaan "default"; profil memakai "updatePassword" dan "userDeletion")
        lg    true (bawaan) untuk input besar; false untuk input ukuran biasa
        id    bila beda dari name (mis. dua form di satu halaman)

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
    'required' => true,
])
@php
    $id          = $id ?: $name;
    $bagErrors   = $errors->getBag($bag);
    $hasError    = $bagErrors->has($name);
    $anyErrors   = collect($errors->getBags())->contains(fn ($b) => $b->any());
    $firstError  = $hasError && array_key_first($bagErrors->messages()) === $name;
    $focus       = $anyErrors ? $firstError : $attributes->has('autofocus');
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
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
    @if ($hint)
        <div id="{{ $id }}-hint" class="form-text">{{ $hint }}</div>
    @endif
    @if ($hasError)
        <div id="{{ $id }}-error" class="invalid-feedback">
            @foreach ($bagErrors->get($name) as $message)
                <div>{{ $message }}</div>
            @endforeach
        </div>
    @endif
</div>
