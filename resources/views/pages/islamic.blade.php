@extends('layouts.lumeria')

@section('title', __('islamic.title'))

@push('styles')
    <style>
        .arabic {
            font-family: "Noto Naskh Arabic", "Traditional Arabic", "Amiri", "Times New Roman", serif;
            font-size: 1.6rem;
            line-height: 2.1;
        }
    </style>
@endpush

@section('content')
    @php
        // Satu salinan teks Arab untuk semua bahasa. Samakan dengan mushaf / kitab hadis sebelum dikumpulkan.
        $arabic = [
            'mutaffifin' => 'وَيْلٌ لِّلْمُطَفِّفِينَ ۝ الَّذِينَ إِذَا اكْتَالُوا عَلَى النَّاسِ يَسْتَوْفُونَ ۝ وَإِذَا كَالُوهُمْ أَوْ وَزَنُوهُمْ يُخْسِرُونَ',
            'anam'       => 'وَأَوْفُوا الْكَيْلَ وَالْمِيزَانَ بِالْقِسْطِ',
            'anfal'      => 'يَا أَيُّهَا الَّذِينَ آمَنُوا لَا تَخُونُوا اللَّهَ وَالرَّسُولَ وَتَخُونُوا أَمَانَاتِكُمْ وَأَنتُمْ تَعْلَمُونَ',
            'khiyar'     => 'الْبَيِّعَانِ بِالْخِيَارِ مَا لَمْ يَتَفَرَّقَا، فَإِنْ صَدَقَا وَبَيَّنَا بُورِكَ لَهُمَا فِي بَيْعِهِمَا، وَإِنْ كَتَمَا وَكَذَبَا مُحِقَتْ بَرَكَةُ بَيْعِهِمَا',
            'ghasysy'    => 'مَنْ غَشَّنَا فَلَيْسَ مِنَّا',
            'tirmidzi'   => 'التَّاجِرُ الصَّدُوقُ الْأَمِينُ مَعَ النَّبِيِّينَ وَالصِّدِّيقِينَ وَالشُّهَدَاءِ',
        ];
    @endphp

    <section class="py-5">
        <div class="container" style="max-width: 56rem">
            <p class="text-lumeria fw-semibold mb-1">{{ __('islamic.chapter') }}</p>
            <h1 class="fw-bold">{{ __('islamic.heading') }}</h1>
            <p class="lead">{{ __('islamic.intro') }}</p>

            <h2 class="h3 fw-bold mt-5 mb-2">{{ __('islamic.dalil_title') }}</h2>
            <p class="small text-muted mb-3">{{ __('islamic.dalil_note') }}</p>

            @foreach (__('islamic.dalil') as $key => $d)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <span class="badge text-bg-warning mb-2">{{ $d['theme'] }}</span>
                        <p class="arabic text-end mb-3" dir="rtl" lang="ar">{{ $arabic[$key] ?? '' }}</p>
                        <p class="mb-1">&ldquo;{{ $d['text'] }}&rdquo;</p>
                        <p class="small text-muted mb-0">{{ $d['ref'] }}</p>
                        @isset($d['note'])
                            <p class="small text-danger mt-2 mb-0">{{ $d['note'] }}</p>
                        @endisset
                    </div>
                </div>
            @endforeach

            <h2 class="h3 fw-bold mt-5 mb-3">{{ __('islamic.apply_title') }}</h2>
            <div class="row g-3">
                @foreach (__('islamic.apply') as $a)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h3 class="h5 text-lumeria">{{ $a['title'] }}</h3>
                                <p>{{ $a['text'] }}</p>
                                <p class="small text-muted mb-0">{{ __('islamic.basis') }}: {{ $a['basis'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card border-warning mt-5">
                <div class="card-body">
                    <h2 class="h4">{{ __('islamic.paylater_title') }}</h2>
                    <p>{{ __('islamic.paylater_text') }}</p>
                    <p class="small text-muted mb-0">{{ __('islamic.paylater_note') }}</p>
                </div>
            </div>

            <h2 class="h3 fw-bold mt-5 mb-3">{{ __('islamic.refs_title') }}</h2>
            <ul>
                @foreach (__('islamic.refs') as $ref)
                    <li>{{ $ref }}</li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection