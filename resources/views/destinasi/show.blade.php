@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body { background: #f0f2f5; }

        /* ── Hero ─────────────────────────────────────────────── */
        .detail-hero {
            position: relative;
            height: 480px;
            background: #0f172a;
            overflow: hidden;
        }
        .detail-hero .carousel,
        .detail-hero .carousel-inner,
        .detail-hero .carousel-item { height: 100%; }
        .detail-hero .carousel-item img {
            width: 100%; height: 480px;
            object-fit: cover;
            filter: brightness(.72);
            cursor: zoom-in;
        }
        .detail-hero .hero-placeholder {
            height: 480px;
            background: linear-gradient(135deg, #0d5c36, #28a665);
            display: flex; align-items: center; justify-content: center;
        }
        .detail-hero .hero-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,.75));
            padding: 3rem 2rem 1.75rem;
            z-index: 10;
            pointer-events: none;
        }
        .detail-hero .hero-overlay h1 {
            color: white; font-size: 2.2rem; font-weight: 800;
            text-shadow: 0 2px 14px rgba(0,0,0,.5); margin-bottom: .3rem;
        }
        .detail-hero .hero-overlay .meta { color: rgba(255,255,255,.72); font-size: .85rem; }
        .type-badge {
            background: rgba(34,197,94,.18); color: #4ade80;
            border: 1px solid rgba(34,197,94,.3);
            border-radius: 20px; padding: .25rem .8rem;
            font-size: .72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
        }

        /* ── Thumbnail strip ──────────────────────────────────── */
        .thumb-strip {
            display: flex; gap: 8px; padding: .75rem 0;
            overflow-x: auto; scrollbar-width: thin;
        }
        .thumb-strip img {
            height: 68px; width: 100px; object-fit: cover;
            border-radius: 8px; cursor: pointer; flex-shrink: 0;
            opacity: .6; border: 2.5px solid transparent;
            transition: opacity .2s, border-color .2s, transform .2s;
        }
        .thumb-strip img:hover { opacity: .9; }
        .thumb-strip img.active { opacity: 1; border-color: #22c55e; transform: scale(1.05); }

        /* ── Konten ───────────────────────────────────────────── */
        .content-card { border: none; border-radius: 16px; }

        /* Deskripsi: word-break agar teks panjang tanpa spasi tidak jebol */
        .desc-text {
            white-space: pre-wrap;
            overflow-wrap: break-word;
            word-break: break-word;
            line-height: 1.8;
            color: #475569;
        }

        /* ── Peta sticky ──────────────────────────────────────── */
        .map-sticky-col {
            position: sticky;
            top: 72px; /* tinggi navbar */
            align-self: flex-start;
        }
        #minimap {
            height: calc(100vh - 200px);
            min-height: 340px;
            max-height: 600px;
            border-radius: 0 0 16px 16px;
        }

        /* ── Galeri ───────────────────────────────────────────── */
        .gallery-img {
            height: 170px; width: 100%; object-fit: cover;
            border-radius: 12px; cursor: zoom-in;
            transition: transform .25s, box-shadow .25s;
        }
        .gallery-img:hover { transform: scale(1.04); box-shadow: 0 8px 24px rgba(0,0,0,.15); }

        /* ── Lightbox ─────────────────────────────────────────── */
        .lb-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.93);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .25s;
        }
        .lb-overlay.active { opacity: 1; pointer-events: all; }
        .lb-overlay img {
            max-width: 92vw; max-height: 88vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,.6);
            transition: opacity .2s;
        }
        .lb-btn {
            position: absolute;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,.2);
            color: white; cursor: pointer;
            border-radius: 50%;
            transition: background .2s;
            display: flex; align-items: center; justify-content: center;
        }
        .lb-btn:hover { background: rgba(255,255,255,.28); }
        .lb-close {
            top: 18px; right: 18px;
            width: 42px; height: 42px; font-size: 1.3rem;
        }
        .lb-prev, .lb-next {
            top: 50%; transform: translateY(-50%);
            width: 50px; height: 50px; font-size: 1.6rem;
        }
        .lb-prev { left: 20px; }
        .lb-next { right: 20px; }
        .lb-counter {
            position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
            color: rgba(255,255,255,.65); font-size: .82rem; letter-spacing: 1px;
        }

        /* ── CTA ──────────────────────────────────────────────── */
        .btn-open-map {
            background: #22c55e; color: #0f172a;
            font-weight: 700; border: none; border-radius: 50px;
            padding: .7rem 1.75rem; font-size: .9rem;
            box-shadow: 0 4px 16px rgba(34,197,94,.4);
            transition: transform .15s, box-shadow .15s;
        }
        .btn-open-map:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(34,197,94,.55);
            color: #0f172a;
        }
    </style>
@endsection

@section('content')
@php
    $allImages = $point->allImageFilenames();
    $hasImages = count($allImages) > 0;
@endphp

{{-- ── Lightbox overlay ──────────────────────────────────────────── --}}
<div id="lightbox" class="lb-overlay" onclick="lbClose()">
    <button class="lb-btn lb-close" onclick="lbClose()">&#10005;</button>
    @if (count($allImages) > 1)
        <button class="lb-btn lb-prev" onclick="lbNav(-1,event)">&#8249;</button>
        <button class="lb-btn lb-next" onclick="lbNav(1,event)">&#8250;</button>
    @endif
    <img id="lbImg" src="" alt="" onclick="event.stopPropagation()">
    <div class="lb-counter" id="lbCounter"></div>
</div>

{{-- ── Hero carousel ─────────────────────────────────────────────── --}}
<div class="detail-hero">
    @if ($hasImages)
        <div id="heroCarousel" class="carousel slide" data-bs-ride="false">
            <div class="carousel-inner">
                @foreach ($allImages as $idx => $fname)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/images/' . $fname) }}"
                             alt="{{ $point->name }}"
                             onclick="lbOpen({{ $idx }})">
                    </div>
                @endforeach
            </div>
            @if (count($allImages) > 1)
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @else
        <div class="hero-placeholder">
            <i class="fa-solid fa-mountain-sun fa-5x" style="color:rgba(255,255,255,.2)"></i>
        </div>
    @endif

    <div class="hero-overlay">
        <div class="container">
            <span class="type-badge mb-2 d-inline-block">Objek Wisata</span>
            <h1>{{ $point->name }}</h1>
            <div class="meta">
                <i class="fa-solid fa-user fa-xs me-1"></i>{{ $point->user?->name ?? '—' }}
                &nbsp;·&nbsp;
                <i class="fa-solid fa-clock fa-xs me-1"></i>{{ $point->created_at?->diffForHumans() ?? '—' }}
            </div>
        </div>
    </div>
</div>

{{-- ── Thumbnail strip ────────────────────────────────────────────── --}}
@if (count($allImages) > 1)
    <div class="bg-white border-bottom">
        <div class="container">
            <div class="thumb-strip">
                @foreach ($allImages as $idx => $fname)
                    <img src="{{ asset('storage/images/' . $fname) }}"
                         alt="Foto {{ $idx + 1 }}"
                         class="{{ $idx === 0 ? 'active' : '' }}"
                         onclick="goSlide({{ $idx }}, this)">
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- ── Konten utama ────────────────────────────────────────────────── --}}
<div class="container py-4" style="max-width:1200px">
    <div class="row g-4 align-items-start">

        {{-- Kiri: deskripsi + galeri (lebih lebar) --}}
        <div class="col-lg-8">

            {{-- Card deskripsi --}}
            <div class="card content-card shadow-sm p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Tentang Tempat Ini</h4>
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ route('points.edit', $point->id) }}"
                               class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </a>
                        @endauth
                        <a href="{{ route('home') }}"
                           class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <p class="desc-text">{{ $point->description ?: 'Belum ada deskripsi.' }}</p>

                <div class="mt-4 pt-2 border-top d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('map') }}?point={{ $point->id }}" class="btn btn-open-map">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Buka di Peta
                    </a>
                    @if ($hasImages)
                        <button class="btn btn-outline-secondary rounded-pill px-3"
                                onclick="lbOpen(0)">
                            <i class="fa-solid fa-images me-2"></i>Lihat Foto
                        </button>
                    @endif
                </div>
            </div>

            {{-- Galeri semua foto --}}
            @if (count($allImages) > 1)
                <div class="card content-card shadow-sm p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-images text-primary me-2"></i>
                        Galeri Foto
                        <span class="text-muted fw-normal small ms-1">({{ count($allImages) }} foto)</span>
                    </h5>
                    <div class="row g-3">
                        @foreach ($allImages as $idx => $fname)
                            <div class="col-6 col-md-4">
                                <img src="{{ asset('storage/images/' . $fname) }}"
                                     class="gallery-img shadow-sm"
                                     onclick="lbOpen({{ $idx }})"
                                     alt="Foto {{ $idx + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Kanan: peta sticky ──────────────────────────────────── --}}
        <div class="col-lg-4 map-sticky-col">
            <div class="card content-card shadow-sm overflow-hidden">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-2
                            d-flex justify-content-between align-items-center">
                    <span class="fw-semibold small">
                        <i class="fa-solid fa-map-pin text-danger me-2"></i>Lokasi di Peta
                    </span>
                    <a href="{{ route('map') }}?point={{ $point->id }}"
                       class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-expand me-1"></i>Peta Penuh
                    </a>
                </div>
                <div id="minimap"></div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // ── Peta mini ──────────────────────────────────────────────────
        var lat = {{ $point->lat ?? -7.008 }};
        var lng = {{ $point->lng ?? 110.398 }};

        var minimap = L.map('minimap', {
            zoomControl: false, dragging: true, scrollWheelZoom: false
        }).setView([lat, lng], 15);

        L.control.zoom({ position: 'bottomright' }).addTo(minimap);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CARTO'
        }).addTo(minimap);

        var pin = L.divIcon({
            className: '',
            html: '<div style="width:18px;height:18px;background:#ef4444;border-radius:50%;border:3px solid white;box-shadow:0 2px 10px rgba(239,68,68,.6)"></div>',
            iconSize: [18,18], iconAnchor: [9,9]
        });
        L.marker([lat, lng], { icon: pin })
         .addTo(minimap)
         .bindTooltip('{{ addslashes($point->name) }}', { permanent: true, direction: 'top', offset: [0, -12] });

        // ── Lightbox ───────────────────────────────────────────────────
        var lbImages = @json(array_values($allImages));
        var lbIdx    = 0;

        function lbOpen(idx) {
            lbIdx = idx;
            document.getElementById('lbImg').src = '{{ asset('storage/images') }}/' + lbImages[lbIdx];
            document.getElementById('lbCounter').textContent =
                lbImages.length > 1 ? (lbIdx + 1) + ' / ' + lbImages.length : '';
            document.getElementById('lightbox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function lbClose() {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = '';
        }

        function lbNav(dir, e) {
            e && e.stopPropagation();
            lbIdx = (lbIdx + dir + lbImages.length) % lbImages.length;
            document.getElementById('lbImg').src = '{{ asset('storage/images') }}/' + lbImages[lbIdx];
            document.getElementById('lbCounter').textContent =
                lbImages.length > 1 ? (lbIdx + 1) + ' / ' + lbImages.length : '';
        }

        document.addEventListener('keydown', function(e) {
            var lb = document.getElementById('lightbox');
            if (!lb.classList.contains('active')) return;
            if (e.key === 'Escape')      lbClose();
            if (e.key === 'ArrowLeft')   lbNav(-1, null);
            if (e.key === 'ArrowRight')  lbNav(1,  null);
        });

        // ── Hero carousel + thumbnail sync ─────────────────────────────
        function goSlide(idx, thumbEl) {
            var carousel = document.getElementById('heroCarousel');
            if (carousel) bootstrap.Carousel.getOrCreateInstance(carousel).to(idx);
            document.querySelectorAll('.thumb-strip img').forEach(function(el, i) {
                el.classList.toggle('active', i === idx);
            });
        }

        var heroEl = document.getElementById('heroCarousel');
        if (heroEl) {
            heroEl.addEventListener('slid.bs.carousel', function(e) {
                document.querySelectorAll('.thumb-strip img').forEach(function(el, i) {
                    el.classList.toggle('active', i === e.to);
                });
            });
        }
    </script>
@endsection
