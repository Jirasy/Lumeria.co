@extends('layouts.lumeria')

@section('title', 'Riwayat Kuis PAIBP')

@section('content')
<section class="py-4 py-md-5">
    <div class="container">
        <h1 class="fw-bold">Riwayat Kuis PAIBP</h1>

        <p class="lead text-muted">
            Lihat hasil pengerjaan kuis PAIBP yang pernah kamu selesaikan.
        </p>

        @if ($attempts->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted">
                        Belum ada riwayat pengerjaan kuis.
                    </p>

                    <a href="{{ route('quiz.index') }}" class="btn btn-lumeria">
                        Mulai Kuis
                    </a>
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle stack-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Tanggal Pengerjaan</th>
                                <th scope="col" class="text-end">Nilai</th>
                                <th scope="col" class="text-end">Jawaban Benar</th>
                                <th scope="col">Hasil</th>
                                <th scope="col">
                                    <span class="visually-hidden">Detail</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($attempts as $attempt)
                                <tr>
                                    <td data-label="No." class="fw-semibold">
                                        #{{ $attempt->id }}
                                    </td>

                                    <td data-label="Tanggal Pengerjaan">
                                        {{ $attempt->completed_at->translatedFormat('j M Y, H:i') }}
                                    </td>

                                    <td data-label="Nilai" class="text-sm-end fw-semibold">
                                        {{ $attempt->score }}
                                    </td>

                                    <td data-label="Jawaban Benar" class="text-sm-end">
                                        {{ $attempt->correct_answers }}
                                        / {{ $attempt->total_questions }}
                                    </td>

                                    <td data-label="Hasil">
                                        @if ($attempt->score >= 75)
                                            <span class="badge text-bg-success">Baik</span>
                                        @elseif ($attempt->score >= 50)
                                            <span class="badge text-bg-warning">Cukup</span>
                                        @else
                                            <span class="badge text-bg-danger">Perlu Belajar</span>
                                        @endif
                                    </td>

                                    <td data-label="Detail" class="text-sm-end">
                                        <a
                                            href="{{ route('quiz.show', $attempt) }}"
                                            class="btn btn-sm btn-outline-dark"
                                        >
                                            Lihat Hasil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $attempts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>
@endsection
