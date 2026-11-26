@extends('layouts.lumeria')

@section('title', 'Hasil Kuis PAIBP')

@push('styles')
<style>
    .result-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .result-card {
        border: 1px solid #e8e5df;
        border-radius: 18px;
        background: #fff;
        padding: 2rem;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .04);
    }

    .score-circle {
        width: 130px;
        height: 130px;
        border: 8px solid #dcebe1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1.5rem auto;
        color: #245b3d;
        font-size: 2rem;
        font-weight: 700;
    }

    .answer-card {
        border: 1px solid #e8e5df;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }

    @media print {
        nav, footer, .no-print {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .result-card {
            box-shadow: none;
            border: none;
        }

        .answer-card {
            break-inside: avoid;
        }
    }

    @media (max-width: 576px) {
        .result-card {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container result-wrapper">

        <div class="result-card">

            <div class="text-center">
                <span class="badge rounded-pill text-bg-light border">
                    Hasil Pendidikan Agama Islam dan Budi Pekerti
                </span>

                <h1 class="h3 fw-bold mt-3">Kuis Berhasil Diselesaikan!</h1>

                <p class="text-muted">
                    Berikut adalah hasil pengerjaan kuis PAIBP kamu.
                </p>

                <div class="score-circle">
                    {{ $attempt->score }}
                </div>

                <p class="fw-semibold mb-1">Nilai Akhir</p>

                <p class="text-muted">
                    {{ $attempt->correct_answers }}
                    dari
                    {{ $attempt->total_questions }}
                    jawaban benar
                </p>
            </div>

            <hr class="my-4">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 bg-light">
                        <div class="small text-muted">Tanggal pengerjaan</div>
                        <strong>
                            {{ $attempt->completed_at->format('d-m-Y H:i') }}
                        </strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded-3 bg-light">
                        <div class="small text-muted">Jumlah soal</div>
                        <strong>{{ $attempt->total_questions }} soal</strong>
                    </div>
                </div>
            </div>

            <h2 class="h5 fw-bold mb-3">Pembahasan Jawaban</h2>

            @foreach ($details as $index => $detail)
                <div class="answer-card">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <h3 class="h6 fw-bold mb-0">
                            Pertanyaan {{ $index + 1 }}
                        </h3>

                        @if ($detail['is_correct'])
                            <span class="badge text-bg-success">Benar</span>
                        @else
                            <span class="badge text-bg-danger">Salah</span>
                        @endif
                    </div>

                    <p class="fw-semibold">
                        {{ $detail['question'] }}
                    </p>

                    <p class="mb-2">
                        Jawaban kamu:
                        <strong>
                            {{ $detail['selected'] }}.
                            {{ $detail['options'][$detail['selected']] ?? '' }}
                        </strong>
                    </p>

                    <p class="mb-2">
                        Jawaban benar:
                        <strong>
                            {{ $detail['correct_answer'] }}.
                            {{ $detail['options'][$detail['correct_answer']] ?? '' }}
                        </strong>
                    </p>

                    <div class="mt-3">
                        <strong>Pembahasan:</strong>
                        <p class="mb-2">{{ $detail['explanation'] }}</p>

                        <p class="small text-muted mb-0">
                            Referensi: {{ $detail['reference'] }}
                        </p>
                    </div>
                </div>
            @endforeach

            <div class="d-flex flex-wrap justify-content-between gap-2 mt-4 no-print">
                <a href="{{ route('quiz.index') }}" class="btn btn-outline-secondary">
                    Ulangi Kuis
                </a>

                <button type="button" onclick="window.print()" class="btn btn-lumeria">
                    Cetak / Simpan PDF
                </button>
            </div>

        </div>
    </div>
</section>
@endsection
