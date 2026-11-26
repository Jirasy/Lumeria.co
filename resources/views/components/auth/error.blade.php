{{--
    Pesan error satu kolom form, dipakai x-auth.field.
    Diletakkan tepat setelah input (di dalam .input-group bila ada tombol),
    karena Bootstrap hanya menampilkan .invalid-feedback yang bersaudara dengan input ber-.is-invalid.
--}}
@props(['name', 'id', 'bag' => 'default'])
@php($bagErrors = $errors->getBag($bag))
@if ($bagErrors->has($name))
    <div id="{{ $id }}-error" class="invalid-feedback">
        @foreach ($bagErrors->get($name) as $message)
            <div>{{ $message }}</div>
        @endforeach
    </div>
@endif
