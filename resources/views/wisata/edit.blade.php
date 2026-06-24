@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { width: 100%; height: 350px; border-radius: 8px; }
        .coord-input { background-color: #f8f9fa; }
    </style>
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Objek Wisata
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- PATCH karena kita hanya mengupdate sebagian field --}}
                    <form action="{{ route('wisata.update', $wisata->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="nama_wisata" class="form-label fw-semibold">
                                Nama Wisata <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('nama_wisata') is-invalid @enderror"
                                   id="nama_wisata" name="nama_wisata"
                                   value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required>
                            @error('nama_wisata')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">
                                Deskripsi <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info py-2 mb-2">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            <strong>Klik pada peta</strong> untuk memindahkan lokasi wisata.
                        </div>

                        <div id="map" class="mb-3"></div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label fw-semibold">
                                    Latitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="any"
                                       class="form-control coord-input @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude"
                                       value="{{ old('latitude', $wisata->latitude) }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label fw-semibold">
                                    Longitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="any"
                                       class="form-control coord-input @error('longitude') is-invalid @enderror"
                                       id="longitude" name="longitude"
                                       value="{{ old('longitude', $wisata->longitude) }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto</label>

                            {{-- Tampilkan foto yang ada saat ini --}}
                            @if ($wisata->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/images/' . $wisata->image) }}"
                                         width="150" class="img-thumbnail" alt="Foto saat ini">
                                    <p class="text-muted small mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/jpg,image/jpeg,image/png"
                                   onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
                            <div class="form-text">Format: JPG/PNG, maks. 2 MB. Kosongkan jika tidak ingin mengganti foto.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="preview" src="" alt="" class="img-thumbnail mt-2 d-none" width="200"
                                 onerror="this.classList.add('d-none')"
                                 onload="this.classList.remove('d-none')">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning text-dark">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Update
                            </button>
                            <a href="{{ route('wisata.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-1"></i>Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Koordinat data yang sedang diedit
        var existingLat = {{ old('latitude', $wisata->latitude) }};
        var existingLng = {{ old('longitude', $wisata->longitude) }};

        var map = L.map('map').setView([existingLat, existingLng], 14);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker awal di posisi wisata yang sedang diedit
        var marker = L.marker([existingLat, existingLng]).addTo(map);

        // Klik peta → geser marker & update input
        map.on('click', function (e) {
            var lat = e.latlng.lat.toFixed(8);
            var lng = e.latlng.lng.toFixed(8);

            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lng;

            marker.setLatLng(e.latlng);
        });
    </script>
@endsection
