@props(['slug' => '', 'size' => 96, 'bare' => false])
@php
    $tints = [
        'original-milk'           => '#fff1d6',
        'bola-teh'                => '#eef6d9',
        'bola-kopi'               => '#f3e4d3',
        'bolu-lumer-cokelat-keju' => '#fde9d9',
        'kue-balok-lumer'         => '#fdeccd',
        'es-krim-lumeria-creamy'  => '#fde4ec',
    ];
    $ballColors = [
        'original-milk' => ['#fffaf0', '#f0d9a8'],
        'bola-teh'      => ['#f4fadf', '#9bbd68'],
        'bola-kopi'     => ['#e9cfae', '#7b4a28'],
    ];
    $kind   = match ($slug) {
        'bolu-lumer-cokelat-keju' => 'cake',
        'kue-balok-lumer'         => 'balok',
        'es-krim-lumeria-creamy'  => 'icecream',
        default                   => 'ball',
    };
    $tint   = $tints[$slug] ?? '#fff1d6';
    $colors = $ballColors[$slug] ?? ['#fffaf0', '#f0d9a8'];
    $id     = 'art-' . preg_replace('/[^a-z0-9-]/', '', $slug);
@endphp
@unless ($bare)
<div {{ $attributes->merge(['class' => 'product-art']) }} style="--art-bg: {{ $tint }}">
@endunless
<svg @if ($bare) {{ $attributes }} @endif width="{{ $size }}" height="{{ $size }}" viewBox="0 0 120 120" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
    <ellipse cx="60" cy="108" rx="34" ry="6" fill="#3b2a20" opacity=".12"/>

    @if ($kind === 'ball')
        <defs>
            <radialGradient id="{{ $id }}" cx="35%" cy="30%" r="75%">
                <stop offset="0" stop-color="{{ $colors[0] }}"/>
                <stop offset="1" stop-color="{{ $colors[1] }}"/>
            </radialGradient>
        </defs>
        <circle cx="60" cy="60" r="42" fill="url(#{{ $id }})"/>
        <ellipse cx="46" cy="44" rx="12" ry="7" fill="#fff" opacity=".55" transform="rotate(-25 46 44)"/>

    @elseif ($kind === 'cake')
        <defs>
            <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#f7cf85"/>
                <stop offset="1" stop-color="#e2a24c"/>
            </linearGradient>
        </defs>
        <rect x="20" y="50" width="80" height="50" rx="10" fill="url(#{{ $id }})"/>
        <rect x="20" y="46" width="80" height="18" rx="9" fill="#5a3321"/>
        <rect x="32" y="58" width="12" height="20" rx="6" fill="#5a3321"/>
        <rect x="56" y="58" width="12" height="28" rx="6" fill="#5a3321"/>
        <rect x="80" y="58" width="12" height="16" rx="6" fill="#5a3321"/>
        <rect x="30" y="50" width="26" height="3" rx="1.5" fill="#fff" opacity=".25"/>
        <rect x="62" y="32" width="22" height="14" rx="3" fill="#ffd45e"/>
        <circle cx="70" cy="39" r="2" fill="#f0b429"/>
        <circle cx="78" cy="42" r="1.6" fill="#f0b429"/>

    @elseif ($kind === 'balok')
        <defs>
            <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#e5a85a"/>
                <stop offset="1" stop-color="#b9702d"/>
            </linearGradient>
        </defs>
        <rect x="18" y="46" width="84" height="52" rx="10" fill="url(#{{ $id }})"/>
        @foreach ([34, 48, 62, 76, 90] as $x)
            <rect x="{{ $x }}" y="46" width="3" height="52" fill="#8c5320" opacity=".35"/>
        @endforeach
        <path d="M18 56c0-6 4-10 10-10h64c6 0 10 4 10 10z" fill="#f7d98b"/>
        <rect x="44" y="52" width="8" height="20" rx="4" fill="#f7d98b"/>
        <rect x="70" y="52" width="8" height="28" rx="4" fill="#f7d98b"/>

    @else
        <defs>
            <linearGradient id="{{ $id }}-a" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#fff3f7"/>
                <stop offset="1" stop-color="#f5a9c3"/>
            </linearGradient>
            <linearGradient id="{{ $id }}-b" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#fff8ea"/>
                <stop offset="1" stop-color="#f2d29c"/>
            </linearGradient>
            <linearGradient id="{{ $id }}-c" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#e8b36a"/>
                <stop offset="1" stop-color="#c98332"/>
            </linearGradient>
        </defs>
        <polygon points="40,72 80,72 60,110" fill="url(#{{ $id }}-c)"/>
        <path d="M46 76l16 28M56 74l12 24M72 76l-16 28M64 74l-10 24" stroke="#a8691f" stroke-width="2" opacity=".45" fill="none"/>
        <ellipse cx="60" cy="68" rx="26" ry="14" fill="url(#{{ $id }}-b)"/>
        <circle cx="60" cy="48" r="22" fill="url(#{{ $id }}-a)"/>
        <circle cx="60" cy="24" r="5" fill="#d7263d"/>
        <ellipse cx="50" cy="42" rx="7" ry="4" fill="#fff" opacity=".55" transform="rotate(-25 50 42)"/>
    @endif
</svg>
@unless ($bare)
</div>
@endunless