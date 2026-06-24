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
                <div class="card-header bg-success text-white">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Objek Wisata Baru
                </div>
                <div class="card-body">

                    {{-- Validasi error dari server --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('wisata.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nama Wisata --}}
                        <div class="mb-3">
                            <label for="nama_wisata" class="form-label fw-semibold">
                                Nama Wisata <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror"
                                   id="nama_wisata" name="nama_wisata"
                                   value="{{ old('nama_wisata') }}" placeholder="Contoh: Pantai Marina Semarang"
                                   required>
                            @error('nama_wisata')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">
                                Deskripsi <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi" name="deskripsi" rows="4"
                                      placeholder="Deskripsikan objek wisata ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Petunjuk Peta --}}
                        <div class="alert alert-info py-2 mb-2">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            <strong>Klik pada peta</strong> untuk menentukan lokasi wisata secara otomatis.
                        </div>

                        {{-- Peta Leaflet untuk klik lokasi --}}
                        <div id="map" class="mb-3"></div>

                        {{-- Latitude & Longitude (diisi otomatis saat klik peta) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label fw-semibold">
                                    Latitude <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="any"
                                       class="form-control coord-input @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude"
                                       value="{{ old('latitude') }}"
                                       placeholder="-7.0080..." required>
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
                                       value="{{ old('longitude') }}"
                                       placeholder="110.3978..." required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Upload Foto --}}
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">Foto (opsional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/jpg,image/jpeg,image/png"
                                   onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
                            <div class="form-text">Format: JPG/PNG, maks. 2 MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <img id="preview" src="" alt="" class="img-thumbnail mt-2 d-none" width="200"
                                 onerror="this.classList.add('d-none')"
                                 onload="this.classList.remove('d-none')">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
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
        var map = L.map('map').setView([-7.008052836998501, 110.39784218601451], 12);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = null;

        // Jika ada nilai lama (setelah validasi gagal), tampilkan marker
        var oldLat = parseFloat('{{ old('latitude') }}');
        var oldLng = parseFloat('{{ old('longitude') }}');

        if (!isNaN(oldLat) && !isNaN(oldLng)) {
            marker = L.marker([oldLat, oldLng]).addTo(map);
            map.setView([oldLat, oldLng], 14);
        }

        // Klik peta → pindahkan/buat marker, isi input koordinat
        map.on('click', function (e) {
            var lat = e.latlng.lat.toFixed(8);
            var lng = e.latlng.lng.toFixed(8);

            document.getElementById('latitude').value  = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    </script>
@endsection
