@extends('layouts.lumeria')

@section('title', __('team.title'))

@section('content')
    <section class="py-5">
        <div class="container">
            <h1 class="fw-bold">{{ __('team.title') }}</h1>
            <p class="lead text-muted">{{ __('team.subtitle') }}</p>

            <div class="row g-4 mt-1">
                @foreach ($members as $m)
                    @php
                        $initials = collect(explode(' ', $m['name']))
                            ->take(2)
                            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                            ->implode('');
                    @endphp
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-circle bg-lumeria d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                         style="width: 4rem; height: 4rem" aria-hidden="true">{{ $initials }}</div>
                                    <div>
                                        <h2 class="h5 mb-0">{{ $m['name'] }}</h2>
                                        <span class="text-lumeria">{{ __('team.roles.' . $m['key']) }}</span>
                                    </div>
                                </div>

                                <h3 class="h6">{{ __('team.focus_title') }}</h3>
                                <ul class="ps-3">
                                    @foreach ((array) __('team.focus.' . $m['key']) as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>

                                <dl class="row mb-0 small">
                                    @foreach (['class', 'hobby', 'motto'] as $field)
                                        @if (! empty($m[$field]))
                                            <dt class="col-4">{{ __('team.fields.' . $field) }}</dt>
                                            <dd class="col-8">{{ $m[$field] }}</dd>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection