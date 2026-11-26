@props(['name', 'size' => 20])
<svg {{ $attributes->merge(['class' => 'ft-icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false">
    @if ($name === 'whatsapp')
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    @elseif ($name === 'instagram')
        <rect x="1.6" y="1.6" width="12.8" height="12.8" rx="3.6" fill="none" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="8" cy="8" r="2.9" fill="none" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="11.7" cy="4.3" r=".85"/>
    @elseif ($name === 'chevron-left')
        <path d="M10.5 2.8 5.3 8l5.2 5.2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'chevron-right')
        <path d="m5.5 2.8 5.2 5.2-5.2 5.2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'arrow-right')
        <path d="M2.4 8h10.4M8.7 4.1 12.6 8l-3.9 3.9" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'check')
        <path d="m2.9 8.4 2.7 2.7 5.9-6.2" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
    @elseif ($name === 'eye')
        <path d="M8 3.4c3 0 5.4 2 6.4 4.6-1 2.6-3.4 4.6-6.4 4.6S2.6 10.6 1.6 8C2.6 5.4 5 3.4 8 3.4z" fill="none" stroke="currentColor" stroke-width="1.3"/>
        <circle cx="8" cy="8" r="1.9" fill="none" stroke="currentColor" stroke-width="1.3"/>
    @elseif ($name === 'eye-off')
        <path d="M2.6 2.6 13.4 13.4" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        <path d="M6.4 4.2A7 7 0 0 1 8 4c3 0 5.4 2 6.4 4.6-.5 1.2-1.2 2.3-2.2 3.1M4.6 5.5C3.3 6.4 2.3 7.5 1.6 8.6 2.6 11.2 5 13.2 8 13.2c1 0 1.9-.2 2.8-.6" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
    @endif
</svg>
