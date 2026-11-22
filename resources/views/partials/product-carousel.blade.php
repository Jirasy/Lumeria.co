{{--
    Carousel produk yang bisa digeser (scroll-snap + tombol + indikator halaman).
    Parameter: $products (koleksi Product), $id (opsional, unik per halaman).
    Sentuh/trackpad/keyboard (panah kiri-kanan saat track fokus) semuanya jalan; JS hanya untuk tombol dan titik.
--}}
@php $cid = $id ?? 'product-carousel'; @endphp
<section class="ft-carousel" data-ft-carousel aria-labelledby="{{ $cid }}-title">
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
        <div>
            <h2 id="{{ $cid }}-title" class="h1 fw-bold mb-1">{{ __('foodtech.carousel.title') }}</h2>
            <p class="text-muted mb-0">{{ __('foodtech.carousel.sub') }}</p>
        </div>
        <a href="{{ route('products') }}" class="ft-more fw-semibold">{{ __('foodtech.carousel.all') }}</a>
    </div>

    <div class="ft-track" data-ft-track tabindex="0" role="region" aria-roledescription="carousel"
         aria-label="{{ __('foodtech.carousel.region') }}">
        @foreach ($products as $item)
            <a href="{{ route('products.show', $item) }}" class="ft-slide">
                <span class="ft-slide-art">
                    @if ($item->badge_label)
                        <span class="ft-badge ft-badge-{{ $item->badge_tone }}">{{ $item->badge_label }}</span>
                    @endif
                    @if ($item->image)
                        <img src="{{ asset($item->image) }}" alt="" loading="lazy">
                    @else
                        <x-product-art :slug="$item->slug" :bare="true" :size="150" />
                    @endif
                </span>
                <span class="ft-slide-name">{{ $item->name }}</span>
                <span class="ft-slide-price">{{ $item->formatted_price }}</span>
            </a>
        @endforeach
    </div>

    <div class="ft-controls">
        <div class="ft-dots" data-ft-dots data-label="{{ __('foodtech.carousel.dot', ['n' => ':n']) }}"></div>
        <div class="ft-arrows">
            <button type="button" class="ft-arrow" data-ft-prev aria-label="{{ __('foodtech.carousel.prev') }}">
                <x-icon name="chevron-left" :size="18" />
            </button>
            <button type="button" class="ft-arrow" data-ft-next aria-label="{{ __('foodtech.carousel.next') }}">
                <x-icon name="chevron-right" :size="18" />
            </button>
        </div>
    </div>
</section>

@once
    @push('scripts')
        <script>
            (function () {
                var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

                document.querySelectorAll('[data-ft-carousel]').forEach(function (root) {
                    var track = root.querySelector('[data-ft-track]');
                    var prev  = root.querySelector('[data-ft-prev]');
                    var next  = root.querySelector('[data-ft-next]');
                    var dots  = root.querySelector('[data-ft-dots]');
                    if (!track) { return; }

                    var template = dots ? dots.getAttribute('data-label') : '';
                    var count = 0;

                    function behavior() { return reduce.matches ? 'auto' : 'smooth'; }
                    function pageCount() { return Math.max(1, Math.ceil((track.scrollWidth - 2) / track.clientWidth)); }
                    function current() {
                        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) { return count - 1; }
                        return Math.min(count - 1, Math.round(track.scrollLeft / track.clientWidth));
                    }

                    function build() {
                        count = pageCount();
                        root.classList.toggle('ft-static', count <= 1);
                        if (!dots) { return; }
                        dots.innerHTML = '';
                        for (var i = 0; i < count; i++) {
                            (function (n) {
                                var b = document.createElement('button');
                                b.type = 'button';
                                b.className = 'ft-dot';
                                b.setAttribute('aria-label', template.replace(':n', n + 1));
                                b.addEventListener('click', function () {
                                    track.scrollTo({ left: n * track.clientWidth, behavior: behavior() });
                                });
                                dots.appendChild(b);
                            })(i);
                        }
                        sync();
                    }

                    function sync() {
                        var now = current();
                        if (dots) {
                            Array.prototype.forEach.call(dots.children, function (b, i) {
                                if (i === now) { b.setAttribute('aria-current', 'true'); } else { b.removeAttribute('aria-current'); }
                            });
                        }
                        if (prev) { prev.disabled = track.scrollLeft <= 2; }
                        if (next) { next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2; }
                    }

                    function step(dir) {
                        track.scrollBy({ left: dir * track.clientWidth * 0.9, behavior: behavior() });
                    }

                    if (prev) { prev.addEventListener('click', function () { step(-1); }); }
                    if (next) { next.addEventListener('click', function () { step(1); }); }
                    track.addEventListener('scroll', function () { window.requestAnimationFrame(sync); }, { passive: true });
                    track.addEventListener('keydown', function (e) {
                        if (e.key === 'ArrowRight') { e.preventDefault(); step(1); }
                        if (e.key === 'ArrowLeft')  { e.preventDefault(); step(-1); }
                    });
                    window.addEventListener('resize', build);
                    build();
                });
            })();
        </script>
    @endpush
@endonce
