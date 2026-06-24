<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class WisataController extends Controller
{
    // =========================================================
    // PUBLIC — dapat diakses siapa saja (Guest & Auth)
    // =========================================================

    /**
     * Tampilkan daftar semua wisata + peta Leaflet.
     * Route: GET /wisata  →  wisata.index
     */
    public function index(): View
    {
        // Eager-load relasi user agar tidak N+1 query
        $wisatas = Wisata::with('user')->latest()->get();

        return view('wisata.index', [
            'title'   => 'Peta Objek Wisata Semarang',
            'wisatas' => $wisatas,
        ]);
    }

    /**
     * Tampilkan detail satu objek wisata.
     * Route: GET /wisata/{wisata}  →  wisata.show
     */
    public function show(Wisata $wisata): View
    {
        return view('wisata.show', [
            'title'  => $wisata->nama_wisata,
            'wisata' => $wisata->load('user'),
        ]);
    }

    // =========================================================
    // AUTH — hanya bisa diakses setelah login
    // =========================================================

    /**
     * Tampilkan form tambah wisata baru.
     * Route: GET /wisata/create  →  wisata.create
     */
    public function create(): View
    {
        return view('wisata.create', [
            'title' => 'Tambah Objek Wisata',
        ]);
    }

    /**
     * Simpan data wisata baru ke database.
     * Route: POST /wisata  →  wisata.store
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nama_wisata' => 'required|string|max:255|unique:wisatas,nama_wisata',
                'deskripsi'   => 'required|string',
                // Validasi ketat koordinat GPS
                'latitude'    => 'required|numeric|between:-90,90',
                'longitude'   => 'required|numeric|between:-180,180',
                'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'nama_wisata.required' => 'Nama wisata wajib diisi.',
                'nama_wisata.unique'   => 'Nama wisata sudah terdaftar.',
                'deskripsi.required'   => 'Deskripsi wajib diisi.',
                'latitude.required'    => 'Koordinat latitude wajib diisi.',
                'latitude.between'     => 'Latitude harus antara -90 dan 90.',
                'longitude.required'   => 'Koordinat longitude wajib diisi.',
                'longitude.between'    => 'Longitude harus antara -180 dan 180.',
                'image.image'          => 'File harus berupa gambar.',
                'image.max'            => 'Ukuran gambar maksimal 2 MB.',
            ]
        );

        $validated['image']   = $this->handleImageUpload($request);
        // Catat siapa yang menambahkan data ini
        $validated['user_id'] = auth()->id();

        Wisata::create($validated);

        return redirect()->route('wisata.index')
                         ->with('success', 'Objek wisata berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit.
     * Route: GET /wisata/{wisata}/edit  →  wisata.edit
     */
    public function edit(Wisata $wisata): View
    {
        // Hanya pemilik atau admin yang boleh mengedit
        $this->authorizeAction($wisata);

        return view('wisata.edit', [
            'title'  => 'Edit Objek Wisata',
            'wisata' => $wisata,
        ]);
    }

    /**
     * Simpan perubahan data wisata.
     * Route: PATCH /wisata/{wisata}  →  wisata.update
     */
    public function update(Request $request, Wisata $wisata): RedirectResponse
    {
        $this->authorizeAction($wisata);

        $validated = $request->validate(
            [
                // ignore unique check untuk baris yang sedang diedit sendiri
                'nama_wisata' => 'required|string|max:255|unique:wisatas,nama_wisata,' . $wisata->id,
                'deskripsi'   => 'required|string',
                'latitude'    => 'required|numeric|between:-90,90',
                'longitude'   => 'required|numeric|between:-180,180',
                'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'nama_wisata.required' => 'Nama wisata wajib diisi.',
                'nama_wisata.unique'   => 'Nama wisata sudah digunakan.',
                'deskripsi.required'   => 'Deskripsi wajib diisi.',
                'latitude.between'     => 'Latitude harus antara -90 dan 90.',
                'longitude.between'    => 'Longitude harus antara -180 dan 180.',
            ]
        );

        // Upload gambar baru jika ada, jika tidak pakai yang lama
        if ($request->hasFile('image')) {
            $this->deleteOldImage($wisata->image);
            $validated['image'] = $this->handleImageUpload($request);
        } else {
            $validated['image'] = $wisata->image;
        }

        $wisata->update($validated);

        return redirect()->route('wisata.index')
                         ->with('success', 'Objek wisata berhasil diperbarui.');
    }

    /**
     * Hapus data wisata.
     * Route: DELETE /wisata/{wisata}  →  wisata.destroy
     */
    public function destroy(Wisata $wisata): RedirectResponse
    {
        $this->authorizeAction($wisata);

        $this->deleteOldImage($wisata->image);
        $wisata->delete();

        return redirect()->route('wisata.index')
                         ->with('success', 'Objek wisata berhasil dihapus.');
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    /**
     * Cek apakah user yang sedang login berhak mengubah/menghapus data ini.
     * Hanya pemilik data atau admin yang diizinkan.
     */
    private function authorizeAction(Wisata $wisata): void
    {
        $user = auth()->user();

        if ($wisata->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak berhak melakukan aksi ini.');
        }
    }

    /**
     * Upload gambar dan kembalikan nama file-nya.
     * Mengembalikan null jika tidak ada file yang diunggah.
     */
    private function handleImageUpload(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $imageDir = public_path('storage/images');

        if (! File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0777, true);
        }

        $image    = $request->file('image');
        $filename = time() . '_wisata.' . strtolower($image->getClientOriginalExtension());
        $image->move($imageDir, $filename);

        return $filename;
    }

    /**
     * Hapus file gambar lama dari storage jika ada.
     */
    private function deleteOldImage(?string $filename): void
    {
        if ($filename === null) {
            return;
        }

        $path = public_path('storage/images/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
