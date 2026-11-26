
@extends('layouts.lumeria')

@section('title', __('william.heading'))

@push('styles')
    <style>
        .william-page {
            max-width: 56rem;
        }

        .william-photo {
            width: 100%;
            max-width: 280px;
            height: 340px;
            object-fit: cover;
            object-position: center 25%;
        }

        .william-timeline {
            border-left: 3px solid #a35a1f;
            padding-left: 1.25rem;
            margin-left: .5rem;
        }

        .william-timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .william-timeline-item::before {
            content: "";
            position: absolute;
            left: -1.67rem;
            top: .35rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #a35a1f;
        }
    </style>
@endpush

@section('content')
    @php
        $content = 'william';
    @endphp

    <section class="py-5">
        <div class="container william-page">

            {{-- Header --}}
            <p class="text-lumeria fw-semibold mb-1">
                {{ __("{$content}.chapter") }}
            </p>

            <h1 class="fw-bold mb-2">
                {{ __("{$content}.heading") }}
            </h1>

            <p class="lead text-muted">
                {{ __("{$content}.subtitle") }}
            </p>

            <hr class="my-4">

            {{-- Biodata dan foto --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.profile_title") }}
            </h2>

            <div class="card shadow-sm mb-5">
                <div class="card-body p-3 p-md-4">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-5 text-center">
                            <img
                                src="https://commons.wikimedia.org/wiki/Special:FilePath/William_Tanuwijaya_1.jpg?width=600"
                                alt="William Tanuwijaya"
                                class="william-photo rounded shadow-sm"
                                loading="lazy"
                            >

                            <p class="small text-muted mt-2 mb-0">
                                {{ __("{$content}.photo_credit") }}
                                <br>
                                <a
                                    href="https://commons.wikimedia.org/wiki/File:William_Tanuwijaya_1.jpg"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Priscilla anais, Wikimedia Commons
                                </a>
                                ·
                                <a
                                    href="https://creativecommons.org/licenses/by-sa/4.0/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    CC BY-SA 4.0
                                </a>
                            </p>
                        </div>

                        <div class="col-md-7">
                            <p>{{ __("{$content}.intro") }}</p>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <tbody>
                                        @foreach (__("{$content}.profile") as $item)
                                            <tr>
                                                <th class="text-muted" style="width: 42%">
                                                    {{ $item['label'] }}
                                                </th>
                                                <td>{{ $item['value'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perjalanan hidup --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.journey_title") }}
            </h2>

            <div class="william-timeline mb-5">
                @foreach (__("{$content}.journey") as $item)
                    <div class="william-timeline-item">
                        <span class="badge text-bg-warning mb-2">
                            {{ $item['year'] }}
                        </span>
                        <h3 class="h5 fw-bold">{{ $item['title'] }}</h3>
                        <p class="mb-0">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Awal Tokopedia --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.tokopedia_title") }}
            </h2>

            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <p>{{ __("{$content}.tokopedia_text") }}</p>
                    <p class="mb-0">{{ __("{$content}.tokopedia_launch") }}</p>
                </div>
            </div>

            {{-- Tantangan dan pencapaian --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.challenge_title") }}
            </h2>

            <div class="row g-3 mb-5">
                @foreach (__("{$content}.challenges") as $item)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h3 class="h5 text-lumeria fw-bold">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="mb-0">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Kontribusi --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.contribution_title") }}
            </h2>

            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <ul class="mb-0">
                        @foreach (__("{$content}.contributions") as $item)
                            <li class="mb-2">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Nilai keteladanan --}}
            <h2 class="h3 fw-bold mb-2">
                {{ __("{$content}.values_title") }}
            </h2>

            <p class="text-muted">
                {{ __("{$content}.values_note") }}
            </p>

            <div class="row g-3 mb-5">
                @foreach (__("{$content}.values") as $index => $item)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <span class="badge text-bg-warning mb-2">
                                    {{ $index + 1 }}
                                </span>
                                <h3 class="h5 fw-bold">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="mb-0">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Refleksi --}}
            <div class="card border-warning mb-5">
                <div class="card-body p-4">
                    <h2 class="h4 fw-bold">
                        {{ __("{$content}.reflection_title") }}
                    </h2>
                    <p class="mb-0">
                        {{ __("{$content}.reflection_text") }}
                    </p>
                </div>
            </div>

            {{-- Referensi --}}
            <h2 class="h3 fw-bold mb-3">
                {{ __("{$content}.references_title") }}
            </h2>

            <div class="card shadow-sm">
                <div class="card-body">
                    <ol class="mb-0">
                        @foreach (__("{$content}.references") as $ref)
                            <li class="mb-2">
                                <a
                                    href="{{ $ref['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    {{ $ref['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

        </div>
    </section>
@endsection
