@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        body { overflow: hidden; }

        #map {
            width: 100%;
            height: calc(100vh - 56px);
            z-index: 1;
        }

        /* Floating panel kiri atas */
        #mapPanel {
            position: absolute;
            top: 72px;
            left: 12px;
            z-index: 500;
            width: 270px;
            background: rgba(15,23,42,.88);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 14px;
            padding: 1rem;
            color: white;
            box-shadow: 0 8px 32px rgba(0,0,0,.35);
        }
        #mapPanel .panel-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            margin-bottom: .6rem;
        }
        .layer-toggle {
            display: flex; align-items: center; justify-content: space-between;
            padding: .4rem .6rem;
            border-radius: 8px;
            font-size: .82rem;
            cursor: pointer;
            transition: background .15s;
        }
        .layer-toggle:hover { background: rgba(255,255,255,.07); }
        .layer-dot {
            width: 10px; height: 10px; border-radius: 50%;
            display: inline-block; flex-shrink: 0; margin-right: 8px;
        }
        .form-check-input:checked { background-color: #22c55e; border-color: #22c55e; }

        /* Tombol tambah titik (hanya auth) */
        #btnAddPoint {
            position: absolute;
            bottom: 32px;
            right: 16px;
            z-index: 500;
            background: #22c55e;
            color: #0f172a;
            border: none;
            border-radius: 50px;
            padding: .7rem 1.4rem;
            font-weight: 700;
            font-size: .875rem;
            box-shadow: 0 4px 20px rgba(34,197,94,.5);
            transition: transform .15s, box-shadow .15s;
            cursor: pointer;
        }
        #btnAddPoint:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(34,197,94,.6); }

        /* Legenda bawah kanan */
        .map-legend {
            background: rgba(15,23,42,.82) !important;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.1) !important;
            border-radius: 10px !important;
            color: rgba(255,255,255,.85) !important;
            padding: 10px 14px !important;
            font-size: .78rem !important;
            box-shadow: 0 4px 16px rgba(0,0,0,.3) !important;
        }
        .map-legend h4 { color: white; font-size: .8rem; font-weight: 600; margin-bottom: 6px; }
        .map-legend .legend-row { display: flex; align-items: center; gap: 7px; margin-bottom: 4px; }

        /* Popup styling */
        .leaflet-popup-content-wrapper {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0,0,0,.2) !important;
            padding: 0 !important;
            overflow: hidden;
        }
        .leaflet-popup-content { margin: 0 !important; width: 280px !important; }
        .popup-card { padding: 14px; }
        .popup-card .popup-title { font-weight: 700; font-size: .95rem; margin-bottom: 3px; }
        .popup-card .popup-desc {
            font-size: .8rem; color: #6c757d; margin-bottom: 6px;
            overflow-wrap: break-word; word-break: break-word;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        }
        .popup-card .popup-meta { font-size: .72rem; color: #9ca3af; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .popup-actions { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; }
        .popup-actions .btn { font-size: .75rem; padding: .28rem .75rem; border-radius: 20px; }

        /* Popup mini photo slider */
        .popup-slider { position: relative; height: 140px; overflow: hidden; background: #0f172a; }
        .popup-slider img { width: 100%; height: 140px; object-fit: cover; display: none; }
        .popup-slider img.ps-active { display: block; }
        .ps-ctrl {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,.55));
            display: flex; align-items: center; justify-content: space-between;
            padding: 4px 8px;
        }
        .ps-btn {
            background: rgba(255,255,255,.2); border: none; color: white;
            width: 24px; height: 24px; border-radius: 50%;
            font-size: .9rem; line-height: 1; cursor: pointer;
            transition: background .15s;
        }
        .ps-btn:hover { background: rgba(255,255,255,.4); }
        .ps-idx { color: rgba(255,255,255,.85); font-size: .72rem; }

        /* Modal modern */
        .modal-content { border: none; border-radius: 16px; overflow: hidden; }
        .modal-header { background: #0f172a; color: white; border-bottom: 1px solid rgba(255,255,255,.08); }
        .modal-header .btn-close { filter: invert(1); }
        .modal-header .modal-title { font-size: 1rem; font-weight: 600; }
    </style>
@endsection

@section('content')
    {{-- Peta --}}
    <div id="map"></div>

    {{-- Panel Layer (floating kiri atas) --}}
    <div id="mapPanel">
        <div class="panel-title"><i class="fa-solid fa-layer-group me-2"></i>Layer Peta</div>

        {{-- Layer statis --}}
        <div class="layer-toggle" onclick="toggleLayer('points')">
            <span><span class="layer-dot" style="background:#1d4ed8"></span>Objek Wisata</span>
            <input class="form-check-input" type="checkbox" id="chkPoints" checked onclick="event.stopPropagation();toggleLayer('points')">
        </div>
        <div class="layer-toggle" onclick="toggleLayer('semarang')">
            <span><span class="layer-dot" style="background:#f59e0b"></span>Batas Administrasi</span>
            <input class="form-check-input" type="checkbox" id="chkSemarang" checked onclick="event.stopPropagation();toggleLayer('semarang')">
        </div>
        <div class="layer-toggle" onclick="toggleLayer('jalan')">
            <span><span class="layer-dot" style="background:#ef4444;border-radius:2px;height:3px"></span>Jalan</span>
            <input class="form-check-input" type="checkbox" id="chkJalan" checked onclick="event.stopPropagation();toggleLayer('jalan')">
        </div>
        <div class="layer-toggle" onclick="toggleLayer('polylines')">
            <span><span class="layer-dot" style="background:#8b5cf6;border-radius:2px;height:3px"></span>Polylines DB</span>
            <input class="form-check-input" type="checkbox" id="chkPolylines" onclick="event.stopPropagation();toggleLayer('polylines')">
        </div>
        <div class="layer-toggle" onclick="toggleLayer('polygons')">
            <span><span class="layer-dot" style="background:#10b981;border-radius:2px"></span>Polygon DB</span>
            <input class="form-check-input" type="checkbox" id="chkPolygons" onclick="event.stopPropagation();toggleLayer('polygons')">
        </div>

        {{-- Separator + layer upload --}}
        <div id="uploadedLayers"></div>
    </div>

    {{-- Tombol tambah titik (hanya auth) --}}
    @auth
        <button id="btnAddPoint" title="Tambah Objek Wisata">
            <i class="fa-solid fa-plus me-2"></i>Tambah Titik
        </button>
    @endauth

    {{-- ============================================================
         Modal Create Point
    ============================================================ --}}
    @auth
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-location-dot me-2 text-success"></i>Tambah Objek Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Objek Wisata</label>
                            <input type="text" class="form-control" name="name" placeholder="Misal: Lawang Sewu" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi singkat objek wisata..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Geometri (WKT)</label>
                            <input type="text" class="form-control form-control-sm font-monospace bg-light"
                                   id="geom_point" name="geom_point" readonly
                                   placeholder="Klik pada peta untuk menentukan titik">
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small">Foto (bisa lebih dari satu)</label>
                            <input type="file" class="form-control form-control-sm" name="images[]"
                                   accept="image/*" multiple onchange="previewMultiImg(this,'preview-point-imgs')">
                            <div id="preview-point-imgs" class="d-flex flex-wrap gap-1 mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Create Polylines --}}
    <div class="modal fade" id="CreatePolylinesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-route me-2 text-warning"></i>Tambah Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('polylines.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Geometri (WKT)</label>
                            <input type="text" class="form-control form-control-sm font-monospace bg-light"
                                   id="geom_polyline" name="geom_polyline" readonly>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small">Foto (opsional)</label>
                            <input type="file" class="form-control form-control-sm" name="image" accept="image/*"
                                   onchange="previewImg(this,'preview-image-polyline')">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail mt-2 d-none" style="max-height:130px">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm rounded-pill px-4 text-dark fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Create Polygon --}}
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-draw-polygon me-2 text-danger"></i>Tambah Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('polygon.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Geometri (WKT)</label>
                            <input type="text" class="form-control form-control-sm font-monospace bg-light"
                                   id="geom_polygon" name="geom_polygon" readonly>
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold small">Foto (opsional)</label>
                            <input type="file" class="form-control form-control-sm" name="image" accept="image/*"
                                   onchange="previewImg(this,'preview-image-polygon')">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail mt-2 d-none" style="max-height:130px">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        // ─── Status autentikasi dari server (diputuskan di PHP) ───────────────────
        var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        // ─── Inisialisasi peta ────────────────────────────────────────────────────
        var map = L.map('map', { zoomControl: false })
                   .setView([-7.008052836998501, 110.39784218601451], 12);

        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Tile kartografi bersih (CartoDB Light)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 19
        }).addTo(map);

        // ─── Layer groups ─────────────────────────────────────────────────────────
        var pointLayer    = L.layerGroup().addTo(map);
        var polylineLayer = L.layerGroup();
        var polygonLayer  = L.layerGroup();
        var jalanLayer, semarangLayer;
        var uploadedLayerMap = {}; // id => L.geoJson

        // ─── Layer state ──────────────────────────────────────────────────────────
        var layerState = { points: true, semarang: true, jalan: true, polylines: false, polygons: false };

        function toggleLayer(name) {
            layerState[name] = !layerState[name];
            var chk = document.getElementById('chk' + name.charAt(0).toUpperCase() + name.slice(1));
            if (chk) chk.checked = layerState[name];

            switch(name) {
                case 'points':    layerState[name] ? pointLayer.addTo(map)    : map.removeLayer(pointLayer);    break;
                case 'polylines': layerState[name] ? polylineLayer.addTo(map) : map.removeLayer(polylineLayer); break;
                case 'polygons':  layerState[name] ? polygonLayer.addTo(map)  : map.removeLayer(polygonLayer);  break;
                case 'semarang':  semarangLayer && (layerState[name] ? semarangLayer.addTo(map) : map.removeLayer(semarangLayer)); break;
                case 'jalan':     jalanLayer    && (layerState[name] ? jalanLayer.addTo(map)    : map.removeLayer(jalanLayer));    break;
            }
        }

        // ─── Ikon & popup helper ──────────────────────────────────────────────────
        var ptIcon = L.divIcon({
            className: '',
            html: '<div style="width:14px;height:14px;background:#1d4ed8;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(29,78,216,.6)"></div>',
            iconSize: [14,14], iconAnchor: [7,7], popupAnchor: [0,-8]
        });

        // Slider state: pointId => currentIndex
        var psState = {};

        // Geser foto di popup
        window.pSlide = function(id, dir) {
            var imgs = document.querySelectorAll('#pslide-' + id + ' img');
            if (!imgs.length) return;
            psState[id] = ((psState[id] || 0) + dir + imgs.length) % imgs.length;
            imgs.forEach(function(img, i) { img.classList.toggle('ps-active', i === psState[id]); });
            var el = document.getElementById('pIdx-' + id);
            if (el) el.textContent = (psState[id] + 1) + '/' + imgs.length;
        };

        function buildImgSlider(id, images) {
            if (!images || images.length === 0) return '';
            if (images.length === 1) {
                return "<div class='popup-slider'>" +
                       "<img src='{{ asset('storage/images') }}/" + images[0] + "' class='ps-active'>" +
                       "</div>";
            }
            var imgTags = images.map(function(src, i) {
                return "<img src='{{ asset('storage/images') }}/" + src + "' class='" + (i === 0 ? 'ps-active' : '') + "'>";
            }).join('');
            return "<div class='popup-slider' id='pslide-" + id + "'>" +
                   imgTags +
                   "<div class='ps-ctrl'>" +
                   "<button class='ps-btn' onclick='pSlide(" + id + ",-1)'>&#8249;</button>" +
                   "<span class='ps-idx' id='pIdx-" + id + "'>1/" + images.length + "</span>" +
                   "<button class='ps-btn' onclick='pSlide(" + id + ",1)'>&#8250;</button>" +
                   "</div></div>";
        }

        function fmtDate(iso) {
            if (!iso) return '';
            try {
                return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            } catch(e) { return iso; }
        }

        function buildPopup(id, name, desc, images, detailUrl, meta, actions) {
            return buildImgSlider(id, images) +
                   "<div class='popup-card'>" +
                   "<div class='popup-title'>" + name + "</div>" +
                   "<div class='popup-desc'>" + (desc || '—') + "</div>" +
                   "<div class='popup-meta'>" + meta + "</div>" +
                   "<div class='popup-actions'>" +
                   "<a href='" + detailUrl + "' class='btn btn-sm btn-dark rounded-pill'>" +
                   "<i class='fa-solid fa-circle-info me-1'></i>Detail</a>" +
                   (actions || '') +
                   "</div></div>";
        }

        function editBtn(route) {
            return "<a href='" + route + "' class='btn btn-warning btn-sm rounded-pill'><i class='fa-solid fa-pen-to-square me-1'></i>Edit</a>";
        }

        function deleteBtn(route) {
            return "<form method='POST' action='" + route + "' style='display:inline'>" +
                   "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
                   "<input type='hidden' name='_method' value='DELETE'>" +
                   "<button type='submit' class='btn btn-danger btn-sm rounded-pill' onclick='return confirm(\"Hapus data ini?\")'>" +
                   "<i class='fa-solid fa-trash me-1'></i>Hapus</button></form>";
        }

        // ─── Load Points ─────────────────────────────────────────────────────────
        var pointMarkers = {}; // id => Leaflet marker layer (untuk zoom)

        function loadPoints() {
            $.getJSON("{{ route('api.points') }}", function(data) {
                pointLayer.clearLayers();
                pointMarkers = {};

                L.geoJson(data, {
                    pointToLayer: function(f, latlng) {
                        var m = L.marker(latlng, { icon: ptIcon });
                        pointMarkers[f.properties.id] = { latlng: latlng, layer: m };
                        return m;
                    },
                    onEachFeature: function(feature, layer) {
                        var p       = feature.properties;
                        var actions = '';
                        if (isAuthenticated) {
                            actions = editBtn("{{ route('points.edit', ':id') }}".replace(':id', p.id))
                                    + deleteBtn("{{ route('points.destroy', ':id') }}".replace(':id', p.id));
                        }
                        var meta       = (p.user_created ? '<i class="fa-solid fa-user fa-xs me-1"></i>' + p.user_created + ' · ' : '') + fmtDate(p.created_at);
                        var detailUrl  = "{{ route('destinasi.show', ':id') }}".replace(':id', p.id);
                        var imgs       = p.images && p.images.length ? p.images : (p.image ? [p.image] : []);

                        layer.bindPopup(buildPopup(p.id, p.name, p.description, imgs, detailUrl, meta, actions), { maxWidth: 300 });
                        layer.bindTooltip(p.name, { direction: 'top' });
                    }
                }).addTo(pointLayer);

                // Zoom ke titik dari URL param ?point=ID
                var urlId = parseInt(new URLSearchParams(window.location.search).get('point'));
                if (urlId && pointMarkers[urlId]) {
                    map.setView(pointMarkers[urlId].latlng, 17, { animate: true });
                    setTimeout(function() { pointMarkers[urlId].layer.openPopup(); }, 600);
                }
            });
        }

        // ─── Load Polylines ───────────────────────────────────────────────────────
        function loadPolylines() {
            $.getJSON("{{ route('api.polylines') }}", function(data) {
                polylineLayer.clearLayers();
                L.geoJson(data, {
                    style: { color: '#8b5cf6', weight: 3, opacity: .85 },
                    onEachFeature: function(feature, layer) {
                        var p = feature.properties;
                        var actions = '';
                        if (isAuthenticated) {
                            actions = editBtn("{{ route('polylines.edit', ':id') }}".replace(':id', p.id))
                                    + deleteBtn("{{ route('polylines.destroy', ':id') }}".replace(':id', p.id));
                        }
                        var meta = (p.length_km ? p.length_km.toFixed(2) + ' km · ' : '') + (p.user_created || '') + (p.created_at ? ' · ' + fmtDate(p.created_at) : '');
                        layer.bindPopup(buildPopup(p.name, p.description, p.image, meta, actions), { maxWidth: 290 });
                        layer.bindTooltip(p.name, { direction: 'top' });
                    }
                }).addTo(polylineLayer);
            });
        }

        // ─── Load Polygons ────────────────────────────────────────────────────────
        function loadPolygons() {
            $.getJSON("{{ route('api.polygon') }}", function(data) {
                polygonLayer.clearLayers();
                L.geoJson(data, {
                    style: { color: '#10b981', weight: 2, fillOpacity: .12 },
                    onEachFeature: function(feature, layer) {
                        var p = feature.properties;
                        var actions = '';
                        if (isAuthenticated) {
                            actions = editBtn("{{ route('polygon.edit', ':id') }}".replace(':id', p.id))
                                    + deleteBtn("{{ route('polygon.destroy', ':id') }}".replace(':id', p.id));
                        }
                        var meta = (p.luas_hektar ? p.luas_hektar.toFixed(2) + ' ha · ' : '') + (p.user_created || '') + (p.created_at ? ' · ' + fmtDate(p.created_at) : '');
                        layer.bindPopup(buildPopup(p.name, p.description, p.image, meta, actions), { maxWidth: 290 });
                        layer.bindTooltip(p.name, { direction: 'top' });
                    }
                }).addTo(polygonLayer);
            });
        }

        // ─── Load static GeoJSON (jalan + semarang) ───────────────────────────────
        fetch("{{ asset('geojson/jalan.geojson') }}")
            .then(r => r.json())
            .then(data => {
                jalanLayer = L.geoJson(data, { style: { color: '#ef4444', weight: 1.5, opacity: .8 } }).addTo(map);
            }).catch(() => {});

        fetch("{{ asset('geojson/semarang.geojson') }}")
            .then(r => r.json())
            .then(data => {
                semarangLayer = L.geoJson(data, {
                    style: { color: '#f59e0b', weight: 1.5, fillOpacity: .12 },
                    onEachFeature: function(feature, layer) {
                        if (feature.properties && feature.properties.NAMOBJ) {
                            layer.bindTooltip(feature.properties.NAMOBJ, { direction: 'top', sticky: true });
                            layer.bindPopup('Kelurahan: <strong>' + feature.properties.NAMOBJ + '</strong>');
                        }
                    }
                }).addTo(map);
            }).catch(() => {});

        // ─── Load uploaded GeoJSON dari API ───────────────────────────────────────
        $.getJSON("{{ route('api.geojson') }}", function(files) {
            var container = document.getElementById('uploadedLayers');
            if (!files.length) return;

            var sep = document.createElement('div');
            sep.style.cssText = 'border-top:1px solid rgba(255,255,255,.08);margin:.6rem 0 .5rem';
            container.appendChild(sep);

            var label = document.createElement('div');
            label.className = 'panel-title';
            label.innerHTML = '<i class="fa-solid fa-upload me-2"></i>Layer Upload';
            container.appendChild(label);

            files.forEach(function(f) {
                $.getJSON("{{ url('/api/geojson') }}/" + f.id, function(geoData) {
                    var gLayer = L.geoJson(geoData, {
                        style: { color: f.color, weight: 2, fillOpacity: f.type === 'polygon' ? .15 : 0 }
                    }).addTo(map);
                    uploadedLayerMap[f.id] = gLayer;

                    // Tambah toggle di panel
                    var row = document.createElement('div');
                    row.className = 'layer-toggle';
                    row.innerHTML =
                        "<span><span class='layer-dot' style='background:" + f.color + "'></span>" + f.name + "</span>" +
                        "<input class='form-check-input' type='checkbox' checked " +
                        "onclick='event.stopPropagation();toggleUploadedLayer(" + f.id + ",this)'>";
                    container.appendChild(row);
                    row.addEventListener('click', function() { toggleUploadedLayer(f.id, row.querySelector('input')); });
                });
            });
        });

        function toggleUploadedLayer(id, chk) {
            var layer = uploadedLayerMap[id];
            if (!layer) return;
            if (map.hasLayer(layer)) { map.removeLayer(layer); chk.checked = false; }
            else                     { layer.addTo(map);        chk.checked = true;  }
        }

        // ─── Legenda ──────────────────────────────────────────────────────────────
        var legend = L.control({ position: 'bottomleft' });
        legend.onAdd = function() {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML =
                '<h4><i class="fa-solid fa-circle-info me-1"></i>Legenda</h4>' +
                '<div class="legend-row"><span style="width:20px;height:3px;background:#ef4444;display:inline-block;border-radius:2px"></span>Jalan</div>' +
                '<div class="legend-row"><span style="width:14px;height:14px;border-radius:3px;background:#f59e0b;opacity:.7;display:inline-block"></span>Batas Administrasi</div>' +
                '<div class="legend-row"><span style="width:12px;height:12px;border-radius:50%;background:#1d4ed8;display:inline-block;box-shadow:0 1px 4px rgba(29,78,216,.5)"></span>Objek Wisata</div>';
            return div;
        };
        legend.addTo(map);

        // ─── Initial load ─────────────────────────────────────────────────────────
        loadPoints();
        // Polylines & polygon dimuat hanya saat checkbox dicentang (tidak autoload)

        // Checkbox init listeners untuk polylines & polygons
        document.getElementById('chkPolylines').addEventListener('change', function() {
            if (this.checked) { loadPolylines(); polylineLayer.addTo(map); layerState.polylines = true; }
            else { map.removeLayer(polylineLayer); layerState.polylines = false; }
        });
        document.getElementById('chkPolygons').addEventListener('change', function() {
            if (this.checked) { loadPolygons(); polygonLayer.addTo(map); layerState.polygons = true; }
            else { map.removeLayer(polygonLayer); layerState.polygons = false; }
        });

        // ─── Digitize (hanya auth) ────────────────────────────────────────────────
        if (isAuthenticated) {
            var drawnItems = new L.FeatureGroup().addTo(map);

            var drawControl = new L.Control.Draw({
                draw: {
                    marker: true,
                    polyline: { shapeOptions: { color: '#8b5cf6' } },
                    polygon:  { shapeOptions: { color: '#10b981', fillOpacity: .15 } },
                    rectangle: false, circle: false, circlemarker: false
                },
                edit: { featureGroup: drawnItems }
            });
            map.addControl(drawControl);

            map.on('draw:created', function(e) {
                var type  = e.layerType;
                var layer = e.layer;
                var wkt   = Terraformer.geojsonToWKT(layer.toGeoJSON().geometry);
                drawnItems.addLayer(layer);

                if (type === 'marker') {
                    document.getElementById('geom_point').value = wkt;
                    new bootstrap.Modal(document.getElementById('CreatePointModal')).show();
                } else if (type === 'polyline') {
                    document.getElementById('geom_polyline').value = wkt;
                    new bootstrap.Modal(document.getElementById('CreatePolylinesModal')).show();
                } else if (type === 'polygon') {
                    document.getElementById('geom_polygon').value = wkt;
                    new bootstrap.Modal(document.getElementById('CreatePolygonModal')).show();
                }
            });

            document.getElementById('btnAddPoint').addEventListener('click', function() {
                // Aktifkan tool marker draw
                new L.Draw.Marker(map, drawControl.options.draw.marker).enable();
            });
        }

        // ─── Utilitas ─────────────────────────────────────────────────────────────
        function previewImg(input, previewId) {
            var el = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                el.src = URL.createObjectURL(input.files[0]);
                el.classList.remove('d-none');
            }
        }

        function previewMultiImg(input, containerId) {
            var container = document.getElementById(containerId);
            container.innerHTML = '';
            Array.from(input.files).forEach(function(file) {
                var img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.cssText = 'height:70px;width:100px;object-fit:cover;border-radius:8px';
                container.appendChild(img);
            });
        }
    </script>
@endsection
