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
                        $name = (string) ($m['name'] ?? '');
                        $key  = (string) ($m['key'] ?? '');

                        $initials = collect(preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY))
                            ->take(2)
                            ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                            ->implode('');

                        $hasBio     = $key !== '' && \Illuminate\Support\Facades\Lang::has('team.bio.' . $key);
                        $hasContact = ! empty($m['whatsapp_url']) || ! empty($m['instagram_url']) || ! empty($m['portfolio_url']);
                    @endphp
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if (! empty($m['photo_url']))
                                        <img src="{{ $m['photo_url'] }}"
                                             alt="{{ __('team.photo_alt', ['name' => $name]) }}"
                                             width="80" height="80"
                                             class="team-photo rounded-circle flex-shrink-0"
                                             loading="lazy" decoding="async">
                                    @else
                                        <div class="team-photo team-photo-fallback rounded-circle bg-lumeria d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                             aria-hidden="true">{{ $initials }}</div>
                                    @endif

                                    <div class="team-name">
                                        <h2 class="h5 mb-0 text-break">{{ $name }}</h2>
                                        @if ($key !== '')
                                            <span class="text-lumeria">{{ __('team.roles.' . $key) }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($hasBio)
                                    <p class="text-muted">{{ __('team.bio.' . $key) }}</p>
                                @endif

                                @if ($key !== '')
                                    <h3 class="h6">{{ __('team.focus_title') }}</h3>
                                    <ul class="ps-3">
                                        @foreach ((array) __('team.focus.' . $key) as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <dl class="row mb-0 small">
                                    @foreach (['class', 'hobby', 'motto'] as $field)
                                        @if (! empty($m[$field]))
                                            <dt class="col-4">{{ __('team.fields.' . $field) }}</dt>
                                            <dd class="col-8">{{ $m[$field] }}</dd>
                                        @endif
                                    @endforeach
                                </dl>

                                @if ($hasContact)
                                    <div class="team-contact mt-auto pt-3">
                                        <h3 class="h6">{{ __('team.contact_title') }}</h3>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if (! empty($m['whatsapp_url']))
                                                <a href="{{ $m['whatsapp_url'] }}"
                                                   class="btn btn-sm btn-outline-lumeria"
                                                   target="_blank" rel="noopener noreferrer"
                                                   aria-label="{{ __('team.contact.whatsapp_aria', ['name' => $name]) }}">{{ __('team.contact.whatsapp') }}</a>
                                            @endif

                                            @if (! empty($m['instagram_url']) && ! empty($m['instagram_handle']))
                                                <a href="{{ $m['instagram_url'] }}"
                                                   class="btn btn-sm btn-outline-lumeria"
                                                   target="_blank" rel="noopener noreferrer"
                                                   aria-label="{{ __('team.contact.instagram_aria', ['name' => $name, 'handle' => '@' . ltrim($m['instagram_handle'], '@')]) }}">{{ '@' . ltrim($m['instagram_handle'], '@') }}</a>
                                            @endif

                                            @if (! empty($m['portfolio_url']))
                                                <a href="{{ $m['portfolio_url'] }}"
                                                   class="btn btn-sm btn-outline-lumeria"
                                                   target="_blank" rel="noopener noreferrer"
                                                   aria-label="{{ __('team.contact.portfolio_aria', ['name' => $name]) }}">{{ __('team.contact.portfolio') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection