{{--
    Satu kolom form halaman autentikasi (Bootstrap): label terhubung (for/id), pesan bantuan, dan pesan error.
    Pemakaian:
        <x-auth.field name="email" type="email" :label="__('Email')" :value="old('email')" autocomplete="username" autofocus />

    - Kata sandi tidak pernah diisi ulang (type="password" selalu kosong).
    - Saat ada error validasi, hanya kolom error pertama yang mendapat fokus; tanpa error, kolom ber-autofocus yang dipakai.
--}}
@props([
    'name',
    'label',
    'type'     => 'text',
    'value'    => null,
    'hint'     => null,
    'id'       => null,
    'required' => true,
])
@php
    $id         = $id ?: $name;
    $hasError   = $errors->has($name);
    $firstError = $hasError && array_key_first($errors->messages()) === $name;
    $focus      = $errors->any() ? $firstError : $attributes->has('autofocus');
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password' && $value !== null) value="{{ $value }}" @endif
        {{ $attributes->except('autofocus')->class(['form-control', 'form-control-lg', 'is-invalid' => $hasError]) }}
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
            @foreach ($errors->get($name) as $message)
                <div>{{ $message }}</div>
            @endforeach
        </div>
    @endif
</div>
