<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARCADĪPA — Peta Wisata Semarang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background: #fff;
            overflow-x: hidden;
        }

        /* ── Navbar ──────────────────────────────────────────────── */
        #homeNav {
            position: fixed;
            top: 0; left: 0; width: 100%;
            z-index: 1000;
            padding: .75rem 0;
            transition: background .35s, backdrop-filter .35s, box-shadow .35s;
        }
        #homeNav.scrolled {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 24px rgba(0,0,0,.08);
        }
        #homeNav .brand {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: .5px;
            color: #fff;
            text-decoration: none;
            transition: color .25s;
        }
        #homeNav.scrolled .brand { color: #0d5c36; }
        #homeNav .nav-link {
            color: rgba(255,255,255,.85);
            font-weight: 500;
            font-size: .9rem;
            padding: .4rem .85rem;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        #homeNav.scrolled .nav-link { color: #334155; }
        #homeNav .nav-link:hover { color: #22c55e; background: rgba(34,197,94,.08); }
        #homeNav.scrolled .nav-link:hover { color: #0d5c36; }
        #homeNav .btn-nav-login {
            background: rgba(255,255,255,.15);
            color: white;
            border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 50px;
            padding: .4rem 1.25rem;
            font-size: .85rem;
            font-weight: 600;
            transition: background .2s, color .2s, border .2s;
        }
        #homeNav .btn-nav-login:hover { background: white; color: #0d5c36; border-color: white; }
        #homeNav.scrolled .btn-nav-login {
            background: #0d5c36; color: white; border-color: #0d5c36;
        }
        #homeNav.scrolled .btn-nav-login:hover { background: #0a4a2c; }
        #homeNav .btn-nav-reg {
            background: #22c55e; color: #0f172a; border: none;
            border-radius: 50px; padding: .4rem 1.25rem;
            font-size: .85rem; font-weight: 700;
            transition: background .2s, transform .15s;
        }
        #homeNav .btn-nav-reg:hover { background: #16a34a; transform: translateY(-1px); }
        #homeNav .navbar-toggler { border: none; }
        #homeNav .toggler-icon { width: 22px; display: flex; flex-direction: column; gap: 5px; }
        #homeNav .toggler-icon span {
            display: block; height: 2px; border-radius: 2px; background: white;
            transition: background .25s;
        }
        #homeNav.scrolled .toggler-icon span { background: #1e293b; }
        #homeNav .nav-user {
            display: flex; align-items: center; gap: 8px;
            color: rgba(255,255,255,.9); font-size: .85rem; font-weight: 500;
        }
        #homeNav.scrolled .nav-user { color: #1e293b; }
        .nav-avatar-sm {
            width: 30px; height: 30px; border-radius: 50%;
            background: #22c55e; color: #0f172a;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 800; flex-shrink: 0;
        }

        /* ── Hero ────────────────────────────────────────────────── */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('{{ asset('images/lawangsewu.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(13,92,54,.65) 100%);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 1.5rem;
            max-width: 820px;
        }
        .hero-eyebrow {
            display: inline-block;
            background: rgba(34,197,94,.2);
            border: 1px solid rgba(34,197,94,.4);
            color: #86efac;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: .35rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
        }
        .hero-title {
            font-size: clamp(3.5rem, 10vw, 6rem);
            font-weight: 900;
            color: #fff;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .hero-title .accent { color: #4ade80; }
        .hero-tagline {
            font-size: clamp(.95rem, 2.5vw, 1.15rem);
            color: rgba(255,255,255,.75);
            font-style: italic;
            font-weight: 400;
            margin-bottom: .75rem;
        }
        .hero-author {
            font-size: .8rem;
            color: rgba(255,255,255,.45);
            margin-bottom: 2.5rem;
        }
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #22c55e;
            color: #0f172a;
            font-weight: 700;
            font-size: .95rem;
            padding: .85rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 6px 28px rgba(34,197,94,.45);
            transition: transform .2s, box-shadow .2s;
        }
        .hero-cta:hover { transform: translateY(-3px); box-shadow: 0 10px 36px rgba(34,197,94,.55); color: #0f172a; }
        .hero-scroll {
            position: absolute;
            bottom: 2.5rem; left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,.5);
            font-size: .72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .scroll-mouse {
            width: 22px; height: 36px;
            border: 2px solid rgba(255,255,255,.35);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            padding-top: 5px;
        }
        .scroll-dot {
            width: 4px; height: 8px;
            background: rgba(255,255,255,.6);
            border-radius: 4px;
            animation: scrollAnim 2s infinite;
        }
        @keyframes scrollAnim {
            0%   { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(14px); opacity: 0; }
        }

        /* ── About Section ───────────────────────────────────────── */
        .about-section { background: #f8fafc; padding: 6rem 0; }
        .section-label {
            display: inline-block;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #22c55e;
            margin-bottom: .75rem;
        }
        .section-title {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .about-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            box-shadow: 0 2px 20px rgba(0,0,0,.06);
            transition: transform .25s, box-shadow .25s;
        }
        .about-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(0,0,0,.1); }
        .about-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 1.25rem;
        }

        /* ── Destinasi Section ───────────────────────────────────── */
        .destinasi-section { padding: 6rem 0; background: #fff; }
        .news-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            transition: transform .3s ease, box-shadow .3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .news-card:hover { transform: translateY(-8px); box-shadow: 0 16px 40px rgba(0,0,0,.13); }
        .news-card .cover {
            position: relative;
            height: 230px;
            overflow: hidden;
            flex-shrink: 0;
        }
        /* Semua gambar slide di-stack absolute */
        .cs-img {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0; z-index: 1;
            transition: opacity .45s ease;
        }
        .cs-img.cs-active { opacity: 1; z-index: 2; }

        .news-card .cover .cover-gradient {
            position: absolute; inset: 0; z-index: 3;
            background: linear-gradient(180deg, transparent 50%, rgba(0,0,0,.45) 100%);
            pointer-events: none;
        }
        .news-card .cover .placeholder-cover {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #0d5c36 0%, #28a665 100%);
            display: flex; align-items: center; justify-content: center;
        }

        /* Tombol prev / next */
        .cs-btn {
            position: absolute; top: 50%; transform: translateY(-50%);
            z-index: 10;
            background: rgba(0,0,0,.42);
            backdrop-filter: blur(4px);
            color: #fff; border: none;
            width: 34px; height: 34px; border-radius: 50%;
            font-size: 1.25rem; line-height: 1; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity .2s, background .2s;
        }
        .news-card:hover .cs-btn { opacity: 1; }
        .cs-btn:hover { background: rgba(0,0,0,.72); }
        .cs-prev { left: 10px; }
        .cs-next { right: 10px; }

        /* Dot indicators */
        .cs-dots {
            position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
            z-index: 10; display: flex; gap: 6px; align-items: center;
        }
        .cs-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,.5);
            cursor: pointer;
            transition: background .2s, transform .2s;
        }
        .cs-dot.active { background: #fff; transform: scale(1.4); }
        .news-card .card-body {
            padding: 1.4rem 1.4rem .8rem;
            flex: 1;
        }
        .news-card .card-title {
            font-weight: 800;
            font-size: 1.05rem;
            color: #0f172a;
            margin-bottom: .5rem;
            line-height: 1.35;
        }
        .news-card .card-text {
            font-size: .855rem;
            color: #64748b;
            line-height: 1.65;
            margin-bottom: 0;
            overflow-wrap: break-word;
            word-break: break-word;
            /* 3-line clamp */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .news-card .card-footer-actions {
            padding: .8rem 1.4rem 1.4rem;
            display: flex;
            gap: 8px;
        }
        .btn-detail {
            flex: 1;
            background: #0f172a;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .6rem .75rem;
            font-size: .82rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background .2s;
        }
        .btn-detail:hover { background: #1e293b; color: #fff; }
        .btn-peta {
            flex: 1;
            background: transparent;
            color: #16a34a;
            border: 1.5px solid #22c55e;
            border-radius: 10px;
            padding: .6rem .75rem;
            font-size: .82rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background .2s, color .2s;
        }
        .btn-peta:hover { background: #22c55e; color: #fff; border-color: #22c55e; }

        /* empty state */
        .empty-state {
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        /* ── Contact Section ─────────────────────────────────────── */
        .contact-section { background: #f8fafc; padding: 6rem 0; }
        .contact-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 2px 16px rgba(0,0,0,.06);
            transition: transform .25s, box-shadow .25s;
        }
        .contact-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.1); }
        .contact-icon {
            width: 60px; height: 60px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1.25rem;
        }

        /* ── Footer ──────────────────────────────────────────────── */
        footer {
            background: #0f172a;
            color: rgba(255,255,255,.55);
            padding: 3rem 0 2rem;
        }
        footer .footer-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: #22c55e;
            letter-spacing: .3px;
        }
        footer .footer-tagline { font-size: .82rem; color: rgba(255,255,255,.4); margin-top: .3rem; }
        footer .footer-divider { border-color: rgba(255,255,255,.08); margin: 2rem 0 1.5rem; }
        footer .footer-bottom { font-size: .8rem; }
        footer .footer-links a {
            color: rgba(255,255,255,.45);
            text-decoration: none;
            font-size: .82rem;
            transition: color .2s;
        }
        footer .footer-links a:hover { color: #22c55e; }
    </style>
</head>
<body>

<!-- ── Navbar ─────────────────────────────────────────────────────── -->
<nav id="homeNav" class="navbar navbar-expand-lg">
    <div class="container">
        <a class="brand" href="{{ route('home') }}">
            <i class="fa-solid fa-earth-asia me-2 text-success"></i>ARCADĪPA
        </a>
        <button class="navbar-toggler p-1" type="button"
                data-bs-toggle="collapse" data-bs-target="#homeNavContent"
                aria-label="Toggle navigation">
            <div class="toggler-icon"><span></span><span></span><span></span></div>
        </button>

        <div class="collapse navbar-collapse" id="homeNavContent">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1 mt-3 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('map') }}">
                        <i class="fa-solid fa-map fa-xs me-1 opacity-75"></i>Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('table') }}">
                        <i class="fa-solid fa-table fa-xs me-1 opacity-75"></i>Tabel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#destinasi">
                        <i class="fa-solid fa-compass fa-xs me-1 opacity-75"></i>Destinasi
                    </a>
                </li>

                @auth
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link nav-user" href="{{ route('dashboard') }}">
                            <span class="nav-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            {{ auth()->user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-nav-login">
                                <i class="fa-solid fa-right-from-bracket me-1"></i>Keluar
                            </button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-nav-login">
                            <i class="fa-solid fa-right-to-bracket me-1"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item ms-lg-1">
                        <a href="{{ route('register') }}" class="btn btn-nav-reg">
                            Daftar
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- ── Hero ───────────────────────────────────────────────────────── -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-eyebrow">Peta Wisata Kota Semarang</span>
        <h1 class="hero-title">ARC<span class="accent">A</span>DĪPA</h1>
        <p class="hero-tagline">"Pelita Spasial: Menyinari Keindahan Sejarah dan Ruang Semarang"</p>
        <p class="hero-author">Elkar Patria Akbar — 25/569213/SV/27612</p>
        <a href="#destinasi" class="hero-cta">
            <i class="fa-solid fa-compass"></i>Jelajahi Wisata
            <i class="fa-solid fa-arrow-down fa-xs opacity-75"></i>
        </a>
    </div>
    <div class="hero-scroll">
        <div class="scroll-mouse"><div class="scroll-dot"></div></div>
        Scroll
    </div>
</section>

<!-- ── About / Tujuan ─────────────────────────────────────────────── -->
<section class="about-section" id="tentang">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">Tentang Website</span>
            <h2 class="section-title">Tujuan &amp; Latar Belakang</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="0">
                <div class="about-card">
                    <div class="about-icon" style="background:#dcfce7">
                        <i class="fa-solid fa-bullseye text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Tujuan Dibuatnya Website</h5>
                    <p class="text-secondary lh-lg mb-0">
                        Website ini bertujuan untuk mempromosikan keindahan dan keberagaman destinasi wisata
                        di Semarang kepada masyarakat luas, sekaligus memudahkan pengunjung untuk mengetahui
                        lokasi dan informasi terkait tempat-tempat menarik di Semarang.
                    </p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="about-card">
                    <div class="about-icon" style="background:#dbeafe">
                        <i class="fa-solid fa-layer-group text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Latar Belakang Pembuatan</h5>
                    <p class="text-secondary lh-lg mb-0">
                        Website ini dibuat untuk memetakan dan memberikan informasi tentang berbagai lokasi
                        wisata di wilayah Semarang, Jawa Tengah — termasuk gambar, deskripsi, dan keterangan
                        spasial, sehingga pengguna dapat dengan mudah menavigasi dan menemukan destinasi wisata.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Destinasi ──────────────────────────────────────────────────── -->
<section class="destinasi-section" id="destinasi">
    <div class="container">
        <div class="row align-items-end mb-5" data-aos="fade-up">
            <div class="col">
                <span class="section-label">Temukan Tempat Baru</span>
                <h2 class="section-title mb-0">Destinasi Wisata Semarang</h2>
            </div>
            <div class="col-auto d-none d-md-block">
                <a href="{{ route('map') }}" class="btn btn-outline-success rounded-pill px-4">
                    <i class="fa-solid fa-map me-2"></i>Lihat Semua di Peta
                </a>
            </div>
        </div>

        @if ($points->isEmpty())
            <div class="empty-state" data-aos="fade-up">
                <i class="fa-solid fa-map-location-dot fa-3x mb-3 d-block opacity-25"></i>
                <p class="fw-semibold mb-1">Belum ada objek wisata</p>
                <p class="small mb-0">Data destinasi akan muncul di sini setelah ditambahkan oleh admin.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach ($points as $point)
                    @php $imgs = $point->allImageFilenames(); @endphp
                    <div class="col-xl-4 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 80 }}">
                        <div class="news-card w-100">

                            {{-- Cover image slider --}}
                            <div class="cover" data-cid="{{ $point->id }}">
                                @if (count($imgs) > 0)
                                    @foreach ($imgs as $i => $fname)
                                        <img src="{{ asset('storage/images/' . $fname) }}"
                                             alt="{{ $point->name }}"
                                             class="cs-img {{ $i === 0 ? 'cs-active' : '' }}"
                                             loading="lazy">
                                    @endforeach
                                    <div class="cover-gradient"></div>
                                    @if (count($imgs) > 1)
                                        <button class="cs-btn cs-prev"
                                                onclick="csSlide({{ $point->id }},-1,event)">&#8249;</button>
                                        <button class="cs-btn cs-next"
                                                onclick="csSlide({{ $point->id }},1,event)">&#8250;</button>
                                        <div class="cs-dots">
                                            @foreach ($imgs as $i => $fname)
                                                <span class="cs-dot {{ $i === 0 ? 'active' : '' }}"
                                                      onclick="csGoto({{ $point->id }},{{ $i }},event)"></span>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="placeholder-cover">
                                        <i class="fa-solid fa-mountain-sun fa-3x" style="color:rgba(255,255,255,.3)"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Body --}}
                            <div class="card-body">
                                <h5 class="card-title">{{ $point->name }}</h5>
                                <p class="card-text">{{ $point->description }}</p>
                            </div>

                            {{-- Actions --}}
                            <div class="card-footer-actions">
                                <a href="{{ route('destinasi.show', $point->id) }}" class="btn-detail">
                                    <i class="fa-solid fa-circle-info me-1"></i>Detail
                                </a>
                                <a href="{{ route('map') }}?point={{ $point->id }}" class="btn-peta">
                                    <i class="fa-solid fa-map-location-dot me-1"></i>Buka di Peta
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5 d-md-none" data-aos="fade-up">
                <a href="{{ route('map') }}" class="btn btn-outline-success rounded-pill px-4">
                    <i class="fa-solid fa-map me-2"></i>Lihat Semua di Peta
                </a>
            </div>
        @endif
    </div>
</section>

<!-- ── Contact ────────────────────────────────────────────────────── -->
<section class="contact-section" id="kontak">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-label">Hubungi Kami</span>
            <h2 class="section-title">Info Kontak</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-card">
                    <div class="contact-icon" style="background:#dcfce7">
                        <i class="fab fa-whatsapp text-success"></i>
                    </div>
                    <h6 class="fw-bold mb-1">WhatsApp</h6>
                    <p class="text-secondary small mb-3">Chat langsung dengan kami</p>
                    <a href="https://wa.me/6281277554529"
                       class="btn btn-outline-success btn-sm rounded-pill px-3 w-100">+62 812-7755-4529</a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="80">
                <div class="contact-card">
                    <div class="contact-icon" style="background:#fce7f3">
                        <i class="fab fa-instagram text-danger"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Instagram</h6>
                    <p class="text-secondary small mb-3">Ikuti kami di Instagram</p>
                    <a href="https://www.instagram.com/elkar21"
                       class="btn btn-outline-danger btn-sm rounded-pill px-3 w-100">@elkar21</a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="160">
                <div class="contact-card">
                    <div class="contact-icon" style="background:#dbeafe">
                        <i class="fab fa-linkedin text-primary"></i>
                    </div>
                    <h6 class="fw-bold mb-1">LinkedIn</h6>
                    <p class="text-secondary small mb-3">Terhubung secara profesional</p>
                    <a href="https://www.linkedin.com/in/elkar-patria-akbar/"
                       class="btn btn-outline-primary btn-sm rounded-pill px-3 w-100">elkar-patria-akbar</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Footer ─────────────────────────────────────────────────────── -->
<footer>
    <div class="container">
        <div class="row align-items-start g-4">
            <div class="col-md-5">
                <div class="footer-brand">
                    <i class="fa-solid fa-earth-asia me-2"></i>ARCADĪPA
                </div>
                <p class="footer-tagline mt-1">Pelita Spasial Kota Semarang</p>
                <p class="mt-3 small" style="color:rgba(255,255,255,.35);max-width:340px">
                    Sistem informasi geografis objek wisata Kota Semarang berbasis web — dibangun dengan Laravel &amp; PostGIS.
                </p>
            </div>
            <div class="col-md-3 offset-md-1">
                <p class="fw-semibold small text-white mb-3">Navigasi</p>
                <div class="footer-links d-flex flex-column gap-2">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house fa-xs me-2"></i>Beranda</a>
                    <a href="{{ route('map') }}"><i class="fa-solid fa-map fa-xs me-2"></i>Peta Interaktif</a>
                    <a href="{{ route('table') }}"><i class="fa-solid fa-table fa-xs me-2"></i>Tabel Data</a>
                    @auth
                        <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge fa-xs me-2"></i>Dashboard</a>
                    @endauth
                </div>
            </div>
            <div class="col-md-3">
                <p class="fw-semibold small text-white mb-3">Akun</p>
                <div class="footer-links d-flex flex-column gap-2">
                    @guest
                        <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket fa-xs me-2"></i>Masuk</a>
                        <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus fa-xs me-2"></i>Daftar</a>
                    @endguest
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="footer-links p-0 border-0 bg-transparent text-start"
                                    style="color:rgba(255,255,255,.45);font-size:.82rem;cursor:pointer;transition:color .2s"
                                    onmouseover="this.style.color='#22c55e'" onmouseout="this.style.color='rgba(255,255,255,.45)'">
                                <i class="fa-solid fa-right-from-bracket fa-xs me-2"></i>Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        <hr class="footer-divider">
        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} ARCADĪPA — Elkar Patria Akbar</span>
            <span>Dibangun dengan Laravel &amp; PostGIS</span>
        </div>
    </div>
</footer>

<script>
    // ── Navbar scroll effect ─────────────────────────────────────────
    var nav = document.getElementById('homeNav');
    window.addEventListener('scroll', function () {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });

    // ── AOS init ─────────────────────────────────────────────────────
    AOS.init({ once: true, duration: 700, offset: 60 });

    // ── Card image slider ─────────────────────────────────────────────
    var csState = {};

    function csSlide(id, dir, e) {
        e && e.stopPropagation();
        var cover = document.querySelector('[data-cid="' + id + '"]');
        if (!cover) return;
        var imgs = cover.querySelectorAll('.cs-img');
        var dots = cover.querySelectorAll('.cs-dot');
        var n    = imgs.length;
        var cur  = ((csState[id] !== undefined ? csState[id] : 0) + dir + n) % n;
        csState[id] = cur;
        imgs.forEach(function(img, i) { img.classList.toggle('cs-active', i === cur); });
        dots.forEach(function(dot, i) { dot.classList.toggle('active',    i === cur); });
    }

    function csGoto(id, idx, e) {
        e && e.stopPropagation();
        var cover = document.querySelector('[data-cid="' + id + '"]');
        if (!cover) return;
        var imgs = cover.querySelectorAll('.cs-img');
        var dots = cover.querySelectorAll('.cs-dot');
        csState[id] = idx;
        imgs.forEach(function(img, i) { img.classList.toggle('cs-active', i === idx); });
        dots.forEach(function(dot, i) { dot.classList.toggle('active',    i === idx); });
    }
</script>
</body>
</html>
