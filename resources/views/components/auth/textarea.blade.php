{{--
    Area teks (Bootstrap) dengan label terhubung, hint, dan pesan error; pasangan x-auth.field.
    <x-auth.textarea name="description_id" :label="..." :value="old('description_id', $x)" :rows="4" />
--}}
@props(['name', 'label', 'value' => null, 'hint' => null, 'id' => null, 'rows' => 4, 'required' => true])
@php
    $id          = $id ?: $name;
    $hasError    = $errors->has($name);
    $anyErrors   = $errors->any();
    $focus       = $hasError && $anyErrors && array_key_first($errors->messages()) === $name;
    $describedBy = trim(($hint ? $id . '-hint ' : '') . ($hasError ? $id . '-error' : ''));
@endphp
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->class(['form-control', 'is-invalid' => $hasError]) }}
        @required($required)
        @if ($focus) autofocus @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
    >{{ $value }}</textarea>
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
