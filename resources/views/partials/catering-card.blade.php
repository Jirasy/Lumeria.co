{{-- Kartu Event & Catering: visual kotak Lumeria, label, judul, deskripsi, dua tombol aksi. Teks: lang/*/foodtech.php --}}
@php $wa = \App\Support\StoreContact::whatsapp(__('foodtech.catering.wa_message')); @endphp
<article class="ft-event card border-0 shadow-sm overflow-hidden">
    <div class="ft-event-media" aria-hidden="true">
        <div class="ft-box">
            <div class="ft-box-lid">{{ __('common.brand') }}</div>
            <div class="ft-box-tray">
                @foreach (['milk', 'tea', 'coffee', 'tea', 'coffee', 'milk'] as $tone)
                    <span class="ft-ball ft-ball-{{ $tone }}"></span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ft-event-body">
        <span class="ft-pill">{{ __('foodtech.catering.label') }}</span>
        <h2 class="ft-event-title">{{ __('foodtech.catering.title') }}</h2>
        <p class="ft-event-text">{{ __('foodtech.catering.text') }}</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ $wa ?? route('order') }}" class="btn btn-lumeria btn-lg"
               @if ($wa) target="_blank" rel="noopener noreferrer" @endif>{{ __('foodtech.catering.cta_order') }}</a>
            <a href="{{ route('order') }}" class="btn btn-outline-dark btn-lg">{{ __('foodtech.catering.cta_more') }}</a>
        </div>
    </div>
</article>
