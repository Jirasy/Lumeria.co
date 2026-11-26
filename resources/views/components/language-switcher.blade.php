{{--
    Pengalih bahasa ID / EN (Bootstrap), sama dengan yang di navbar publik.
    <x-language-switcher />
--}}
@php($locale = app()->getLocale())
<div {{ $attributes->merge(['class' => 'btn-group btn-group-sm lang-switch']) }} role="group" aria-label="{{ __('common.language') }}">
    @foreach (['id' => 'ID', 'en' => 'EN'] as $code => $label)
        <a href="{{ route('lang.switch', $code) }}"
           @if ($locale === $code) aria-current="true" @endif
           class="btn btn-outline-dark{{ $locale === $code ? ' active' : '' }}">{{ $label }}</a>
    @endforeach
</div>
