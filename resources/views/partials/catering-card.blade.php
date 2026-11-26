{{-- Kartu Event & Catering: foto meja acara, label, judul, deskripsi, dua tombol aksi. Teks: lang/*/foodtech.php --}}
@php $wa = \App\Support\StoreContact::whatsapp(__('foodtech.catering.wa_message')); @endphp
<article class="ft-event">
    <div class="ft-event-media">
        <img src="{{ asset('images/catering-lumeria.jpg') }}"
             alt="{{ __('foodtech.catering.label') }} - {{ __('foodtech.catering.title') }}"
             loading="lazy" width="1392" height="752">
    </div>

    <div class="ft-event-body">
        <span class="ft-pill">{{ __('foodtech.catering.label') }}</span>
        <h2 class="ft-event-title">{{ __('foodtech.catering.title') }}</h2>
        <p class="ft-event-text">{{ __('foodtech.catering.text') }}</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ $wa ?? route('order') }}" class="btn btn-lumeria"
               @if ($wa) target="_blank" rel="noopener noreferrer" @endif>{{ __('foodtech.catering.cta_order') }}</a>
            <a href="{{ route('order') }}" class="btn btn-outline-dark">{{ __('foodtech.catering.cta_more') }}</a>
        </div>
    </div>
</article>
