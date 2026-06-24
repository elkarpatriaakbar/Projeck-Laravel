@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body { background: #f0f2f5; }

        .dash-hero {
            background: linear-gradient(135deg, #0d5c36 0%, #1a7a4a 50%, #28a665 100%);
            border-radius: 16px;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
            color: white;
        }
        .dash-hero::before, .dash-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .dash-hero::before { width:280px;height:280px;right:-60px;top:-80px; }
        .dash-hero::after  { width:180px;height:180px;right:120px;bottom:-80px; }

        .stat-card { border:none; border-radius:14px; transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-4px); box-shadow:0 12px 28px rgba(0,0,0,.1) !important; }
        .stat-icon { width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem; }

        #dashmap { width:100%;height:400px;border-radius:12px; }

        .file-badge { font-size:.72rem;padding:.25rem .6rem;border-radius:20px; }
        .upload-zone {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 1.5rem;
            transition: border-color .2s, background .2s;
        }
        .upload-zone:hover { border-color: #198754; background: #f8fff9; }
        .card-flat { border:none;border-radius:14px; }
        .table-sm td, .table-sm th { padding:.55rem .75rem; }
    </style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4" style="max-width:1400px">

    {{-- Flash --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-3">
            <i class="fa-solid fa-circle-xmark me-2"></i>
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Hero --}}
    <div class="dash-hero shadow mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div style="position:relative;z-index:1">
                <p class="mb-1 small opacity-75">Selamat datang kembali 👋</p>
                <h3 class="fw-bold mb-1">{{ auth()->user()->name }}</h3>
                <p class="mb-0 opacity-75 small">Panel manajemen objek wisata Kota Semarang</p>
            </div>
            <a href="{{ route('points.create') ?? route('map') }}" onclick="window.location='{{ route('map') }}';return false;"
               style="position:relative;z-index:1"
               class="btn btn-light fw-semibold text-success px-4 py-2 rounded-pill shadow-sm">
                <i class="fa-solid fa-plus me-2"></i>Tambah Objek Wisata
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10">
                        <i class="fa-solid fa-location-dot text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-3 lh-1">{{ $totalPoints }}</div>
                        <div class="text-muted small">Objek Wisata</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10">
                        <i class="fa-solid fa-route text-warning"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-3 lh-1">{{ $totalPolylines }}</div>
                        <div class="text-muted small">Polylines</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10">
                        <i class="fa-solid fa-draw-polygon text-danger"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-3 lh-1">{{ $totalPolygon }}</div>
                        <div class="text-muted small">Polygon</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card stat-card shadow-sm p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10">
                        <i class="fa-solid fa-file-code text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-3 lh-1">{{ $totalGeojson }}</div>
                        <div class="text-muted small">Layer GeoJSON</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Peta + Upload GeoJSON --}}
    <div class="row g-4 mb-4">

        {{-- Peta Mini --}}
        <div class="col-xl-7">
            <div class="card card-flat shadow-sm">
                <div class="card-header bg-white border-0 pt-3 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="fa-solid fa-map text-primary me-2"></i>Peta Objek Wisata</h6>
                        <small class="text-muted">Klik marker untuk detail objek wisata</small>
                    </div>
                    <a href="{{ route('map') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="fa-solid fa-expand me-1"></i>Buka Penuh
                    </a>
                </div>
                <div class="card-body p-3 pt-0">
                    <div id="dashmap"></div>
                </div>
            </div>
        </div>

        {{-- Upload GeoJSON --}}
        <div class="col-xl-5">
            <div class="card card-flat shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3 px-4 pb-2">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-upload text-success me-2"></i>Upload Layer GeoJSON</h6>
                    <small class="text-muted">Boundary, polyline, atau polygon dari file .geojson / .json</small>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('geojson.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-zone mb-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Nama Layer</label>
                                    <input type="text" name="name" class="form-control form-control-sm"
                                           placeholder="Contoh: Batas Kecamatan Semarang" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Tipe</label>
                                    <select name="type" class="form-select form-select-sm" required>
                                        <option value="boundary">Batas Wilayah</option>
                                        <option value="polyline">Polyline (Jalan/Jalur)</option>
                                        <option value="polygon">Polygon (Area)</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Warna</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="color" name="color" class="form-control form-control-color form-control-sm"
                                               value="#3388ff" style="width:46px;height:31px">
                                        <span class="text-muted small">Warna di peta</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">File GeoJSON</label>
                                    <input type="file" name="file" class="form-control form-control-sm"
                                           accept=".geojson,.json" required>
                                    <div class="form-text">Format: .geojson atau .json — maks. 20 MB</div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fa-solid fa-upload me-1"></i>Upload Layer
                        </button>
                    </form>
                </div>

                {{-- Daftar file yang sudah diupload --}}
                @if ($geojsonFiles->isNotEmpty())
                    <div class="border-top px-4 pb-3">
                        <p class="small fw-semibold text-muted mt-3 mb-2">Layer Terupload ({{ $geojsonFiles->count() }})</p>
                        <div class="d-flex flex-column gap-2">
                            @foreach ($geojsonFiles as $gf)
                                <div class="d-flex justify-content-between align-items-center
                                            bg-light rounded-3 px-3 py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="width:12px;height:12px;border-radius:3px;background:{{ $gf->color }};display:inline-block;flex-shrink:0"></span>
                                        <div>
                                            <div class="fw-semibold small">{{ $gf->name }}</div>
                                            <span class="badge file-badge
                                                {{ $gf->type === 'boundary' ? 'bg-warning text-dark' : ($gf->type === 'polyline' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                                {{ ucfirst($gf->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <form action="{{ route('geojson.destroy', $gf->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus layer {{ $gf->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm py-1 px-2 rounded-3">
                                            <i class="fa-solid fa-trash fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Points Terbaru --}}
    <div class="card card-flat shadow-sm">
        <div class="card-header bg-white border-0 pt-3 px-4 pb-2 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Objek Wisata Terbaru</h6>
            <a href="{{ route('map') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Lihat Peta</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Foto</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestPoints as $p)
                        <tr>
                            <td class="ps-4">
                                @if($p->image)
                                    <img src="{{ asset('storage/images/'.$p->image) }}"
                                         width="44" height="44"
                                         style="object-fit:cover;border-radius:8px" alt="">
                                @else
                                    <div style="width:44px;height:44px;border-radius:8px;background:#e9ecef;display:flex;align-items:center;justify-content:center">
                                        <i class="fa-solid fa-image text-muted fa-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold small">{{ $p->name }}</td>
                            <td class="text-muted small">{{ Str::limit($p->description, 60) }}</td>
                            <td class="text-muted small">{{ $p->created_at?->diffForHumans() }}</td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('points.edit', $p->id) }}"
                                   class="btn btn-warning btn-sm rounded-3 px-2 py-1">
                                    <i class="fa-solid fa-pen-to-square fa-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fa-solid fa-inbox fa-2x d-block mb-2 opacity-25"></i>
                                Belum ada data. Buka peta untuk menambahkan objek wisata.
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var dashmap = L.map('dashmap', { zoomControl: false })
                       .setView([-7.008052836998501, 110.39784218601451], 12);

        L.control.zoom({ position: 'bottomright' }).addTo(dashmap);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO'
        }).addTo(dashmap);

        var ptIcon = L.divIcon({
            className: '',
            html: '<div style="width:12px;height:12px;background:#1d4ed8;border-radius:50%;border:2.5px solid white;box-shadow:0 2px 5px rgba(0,0,0,.3)"></div>',
            iconSize: [12,12], iconAnchor: [6,6]
        });

        // Load points dari API
        $.getJSON("{{ route('api.points') }}", function(data) {
            L.geoJson(data, {
                pointToLayer: function(f, latlng) { return L.marker(latlng, {icon: ptIcon}); },
                onEachFeature: function(feature, layer) {
                    var p = feature.properties;
                    var img = p.image ? "<img src='/storage/images/"+p.image+"' style='width:100%;height:100px;object-fit:cover;border-radius:8px;margin-bottom:6px'>" : '';
                    layer.bindTooltip(p.name, {direction:'top'});
                    layer.bindPopup("<div style='min-width:180px'>"+img+"<strong>"+p.name+"</strong><br><small style='color:#6c757d'>"+p.description+"</small></div>", {maxWidth:220});
                }
            }).addTo(dashmap);
        });

        // Load layer GeoJSON yang diupload
        $.getJSON("{{ route('api.geojson') }}", function(files) {
            files.forEach(function(f) {
                $.getJSON("{{ url('/api/geojson') }}/"+f.id, function(geoData) {
                    L.geoJson(geoData, {
                        style: { color: f.color, weight: 2, fillOpacity: f.type === 'polygon' ? 0.15 : 0 }
                    }).addTo(dashmap);
                });
            });
        });
    </script>
@endsection
