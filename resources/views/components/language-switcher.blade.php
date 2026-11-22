{{--
    Pengalih bahasa ID / EN.
    Bawaan: gaya Tailwind (layout dashboard dan profil).
    Dengan atribut "bootstrap": gaya Bootstrap yang sama dengan navbar publik (halaman login, daftar, dan sejenisnya).
--}}
@props(['bootstrap' => false])
@php($locale = app()->getLocale())
@if ($bootstrap)
    <div {{ $attributes->merge(['class' => 'btn-group btn-group-sm lang-switch']) }} role="group" aria-label="{{ __('common.language') }}">
        @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $label)
            <a href="{{ route('lang.switch', $code) }}"
               @if ($locale === $code) aria-current="true" @endif
               class="btn btn-outline-dark{{ $locale === $code ? ' active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
@else
    <div {{ $attributes->merge(['class' => 'inline-flex text-xs font-semibold shadow-sm']) }} role="group" aria-label="{{ __('common.language') }}">
        @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $label)
            <a href="{{ route('lang.switch', $code) }}"
               @if ($locale === $code) aria-current="true" @endif
               class="px-3 py-1 border border-gray-300 dark:border-gray-600 {{ $loop->first ? 'rounded-s-md' : '-ms-px rounded-e-md' }} {{ $locale === $code ? 'bg-gray-800 text-white dark:bg-gray-200 dark:text-gray-800' : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">{{ $label }}</a>
        @endforeach
    </div>
@endif
