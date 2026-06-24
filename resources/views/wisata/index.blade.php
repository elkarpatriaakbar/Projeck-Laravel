@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { width: 100%; height: 450px; }
        .badge-admin { background-color: #dc3545; font-size: 0.7rem; }
    </style>
@endsection

@section('content')
<div class="container-fluid mt-3">

    {{-- ── Judul + Tombol Tambah (hanya tampil jika sudah login) ── --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fa-solid fa-map-location-dot me-2"></i>Peta Objek Wisata Semarang</h4>

        @auth
            {{-- Tombol ini HANYA muncul untuk user yang sudah login --}}
            <a href="{{ route('wisata.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus me-1"></i>Tambah Wisata
            </a>
        @endauth

        @guest
            {{-- Pesan informatif untuk pengunjung umum --}}
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-right-to-bracket me-1"></i>Login untuk menambahkan data
            </a>
        @endguest
    </div>

    {{-- ── Flash Messages ── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-xmark me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Peta Leaflet ── --}}
    <div id="map" class="rounded shadow-sm mb-4"></div>

    {{-- ── Tabel Data Wisata ── --}}
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <i class="fa-solid fa-table me-1"></i>Daftar Objek Wisata
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="wisataTable">
                <thead class="table-success">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Wisata</th>
                        <th>Deskripsi</th>
                        <th>Koordinat</th>
                        <th>Ditambahkan Oleh</th>
                        @auth
                            {{-- Kolom Aksi hanya muncul jika sudah login --}}
                            <th width="15%">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wisatas as $wisata)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $wisata->nama_wisata }}</strong>
                                @if($wisata->image)
                                    <br>
                                    <img src="{{ asset('storage/images/' . $wisata->image) }}"
                                         width="60" class="rounded mt-1" alt="{{ $wisata->nama_wisata }}">
                                @endif
                            </td>
                            <td>{{ Str::limit($wisata->deskripsi, 80) }}</td>
                            <td>
                                <small class="text-muted">
                                    Lat: {{ $wisata->latitude }}<br>
                                    Lng: {{ $wisata->longitude }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $wisata->user?->name ?? 'Unknown' }}</small>
                            </td>
                            @auth
                                <td>
                                    {{-- Tombol Edit hanya untuk pemilik atau admin --}}
                                    @if(auth()->id() === $wisata->user_id || auth()->user()->isAdmin())
                                        <a href="{{ route('wisata.edit', $wisata->id) }}"
                                           class="btn btn-warning btn-sm me-1">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        {{-- Tombol Hapus dengan konfirmasi --}}
                                        <form action="{{ route('wisata.destroy', $wisata->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus {{ $wisata->nama_wisata }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data wisata.
                                @auth <a href="{{ route('wisata.create') }}">Tambahkan sekarang</a>. @endauth
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // ── Kirim status autentikasi dari server ke JavaScript ──
        // Ini cara aman: keputusan siapa yang boleh lihat tombol
        // ditentukan oleh SERVER (PHP), bukan oleh client (JS).
        var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        var authUserId      = {{ auth()->id() ?? 'null' }};

        // Data wisata dari database, di-encode sebagai JSON
        var wisatasData = @json($wisatas);

        var map = L.map('map').setView([-7.008052836998501, 110.39784218601451], 12);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Render setiap wisata sebagai marker di peta
        wisatasData.forEach(function(wisata) {
            var lat = parseFloat(wisata.latitude);
            var lng = parseFloat(wisata.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            // ── Bangun konten popup ──
            var imageHtml = wisata.image
                ? "<img src='/storage/images/" + wisata.image + "' width='200' class='rounded mb-2'><br>"
                : '';

            // Tombol Edit & Hapus hanya dirender jika user sudah login
            // DAN merupakan pemilik data atau admin
            var actionHtml = '';
            if (isAuthenticated) {
                var editUrl   = "{{ url('/wisata') }}/" + wisata.id + "/edit";
                var deleteUrl = "{{ url('/wisata') }}/" + wisata.id;

                actionHtml = "<div class='d-flex gap-2 mt-2'>" +
                    "<a href='" + editUrl + "' class='btn btn-warning btn-sm'>" +
                        "<i class='fa-solid fa-pen-to-square'></i> Edit" +
                    "</a>" +
                    "<form method='POST' action='" + deleteUrl + "' " +
                          "onsubmit=\"return confirm('Yakin hapus " + wisata.nama_wisata + "?')\">" +
                        "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
                        "<input type='hidden' name='_method' value='DELETE'>" +
                        "<button type='submit' class='btn btn-danger btn-sm'>" +
                            "<i class='fa-solid fa-trash'></i> Hapus" +
                        "</button>" +
                    "</form>" +
                "</div>";
            }

            var popupContent =
                "<strong>" + wisata.nama_wisata + "</strong><br>" +
                imageHtml +
                "<small>" + wisata.deskripsi + "</small><br>" +
                "<small class='text-muted'>Oleh: " + (wisata.user ? wisata.user.name : 'Unknown') + "</small>" +
                actionHtml;

            L.marker([lat, lng])
             .bindPopup(popupContent, { maxWidth: 280 })
             .bindTooltip(wisata.nama_wisata)
             .addTo(map);
        });
    </script>
@endsection
