@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <style>
        #pointstable { color: #333; }
        #pointstable thead th { background-color: #fff9c4; color: #000; }
        #pointstable tbody tr:hover td { background-color: #fbc02d; }
        #pointstable tbody tr td { background-color: #fff9c4; }
    </style>
@endsection

@section('content')
<div class="container mt-4 mb-4">

    {{-- ── Flash Messages ── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Titik Wisata</h4>

            {{-- Tombol Tambah hanya muncul jika sudah login --}}
            @auth
                <a href="{{ route('wisata.create') }}" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-plus"></i> Tambah Wisata
                </a>
            @endauth

            @guest
                {{-- Informasi bagi pengunjung umum --}}
                <span class="text-muted small">
                    <i class="fa-solid fa-circle-info"></i>
                    <a href="{{ route('login') }}">Login</a> untuk mengelola data
                </span>
            @endguest
        </div>

        {{-- Tabel ini bisa dilihat oleh SEMUA pengunjung (guest maupun login) --}}
        <div class="table-responsive">
            <table class="table table-striped" id="pointstable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Dibuat</th>
                        <th>Diperbarui</th>
                        @auth
                            {{-- Kolom aksi HANYA tampil jika sudah login --}}
                            <th>Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse ($points as $point)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $point->name }}</td>
                            <td>{{ Str::limit($point->description, 80) }}</td>
                            <td>
                                @if ($point->image)
                                    <img src="{{ asset('storage/images/' . $point->image) }}"
                                         alt="{{ $point->name }}" width="100" class="rounded">
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $point->created_at?->format('d/m/Y') }}</td>
                            <td>{{ $point->updated_at?->format('d/m/Y') }}</td>

                            @auth
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('points.edit', $point->id) }}"
                                       class="btn btn-warning btn-sm me-1">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    {{-- Tombol Hapus dengan konfirmasi --}}
                                    <form action="{{ route('points.destroy', $point->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data titik wisata.
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        new DataTable('#pointstable');
    </script>
@endsection
