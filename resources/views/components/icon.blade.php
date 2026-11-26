@props(['name', 'size' => 20])
<svg {{ $attributes->merge(['class' => 'ft-icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false">
    @if ($name === 'whatsapp')
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    @elseif ($name === 'instagram')
        <rect x="1.6" y="1.6" width="12.8" height="12.8" rx="3.6" fill="none" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="8" cy="8" r="2.9" fill="none" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="11.7" cy="4.3" r=".85"/>
    @elseif ($name === 'arrow-right')
        <path d="M3 8h9.4M9 4.6 12.4 8 9 11.4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'check')
        <path d="M3.2 8.5l3 3 6.6-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'bag')
        <path d="M3.9 5.4h8.2l.8 7.6a1.3 1.3 0 0 1-1.3 1.4H4.4a1.3 1.3 0 0 1-1.3-1.4z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
        <path d="M6 5.4V4.1a2 2 0 0 1 4 0v1.3" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
    @elseif ($name === 'user')
        <circle cx="8" cy="5.4" r="2.5" fill="none" stroke="currentColor" stroke-width="1.4"/>
        <path d="M3.3 13.4c0-2.3 2.1-3.9 4.7-3.9s4.7 1.6 4.7 3.9" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
    @elseif ($name === 'chevron-left')
        <path d="M10.5 2.8 5.3 8l5.2 5.2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'chevron-right')
        <path d="m5.5 2.8 5.2 5.2-5.2 5.2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
    @endif
</svg>
