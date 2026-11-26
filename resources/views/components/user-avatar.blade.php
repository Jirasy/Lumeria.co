{{--
    Foto profil bulat; bila belum ada foto, tampil inisial nama. Dekoratif (alt kosong): nama sudah ada
    di teks atau aria-label tautan di sekitarnya.
    <x-user-avatar :user="$user" :size="36" />
--}}
@props(['user', 'size' => 36])
@php($url = $user->avatarUrl())
@if ($url)
    <img src="{{ $url }}" alt="" width="{{ $size }}" height="{{ $size }}"
         {{ $attributes->merge(['class' => 'user-avatar rounded-circle']) }} style="width: {{ $size }}px; height: {{ $size }}px;">
@else
    <span {{ $attributes->merge(['class' => 'user-avatar user-avatar-initials rounded-circle']) }}
          style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ round($size * 0.38) }}px;" aria-hidden="true">{{ $user->initials() }}</span>
@endif
