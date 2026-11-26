{{--
    Pilihan (Bootstrap) dengan label terhubung, hint, dan pesan error; pasangan x-auth.field.
    <x-auth.select name="category" :label="..." :options="['a' => 'Label A']" :selected="old('category', $x)" />
    Bila :placeholder diisi, muncul opsi kosong di awal.
--}}
@props(['name', 'label', 'options', 'selected' => null, 'placeholder' => null, 'hint' => null, 'id' => null, 'required' => true])
@php
    $id          = $id ?: $name;
    $hasError    = $errors->has($name);
    $focus       = $hasError && array_key_first($errors->messages()) === $name;
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->class(['form-select', 'is-invalid' => $hasError]) }}
        @required($required)
        @if ($focus) autofocus @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected((string) $selected === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>
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
