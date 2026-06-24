<?php

namespace App\Http\Controllers;

use App\Models\GeojsonFile;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class GeojsonController extends Controller
{
    /** API: daftar semua file GeoJSON (publik, untuk peta) */
    public function index()
    {
        return response()->json(GeojsonFile::select('id', 'name', 'type', 'color')->get());
    }

    /** API: kembalikan isi file GeoJSON berdasarkan ID */
    public function serve(GeojsonFile $geojsonFile)
    {
        $path = storage_path('app/public/geojson/' . $geojsonFile->filename);

        if (! file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json(json_decode(file_get_contents($path)));
    }

    /** Upload file GeoJSON baru (hanya admin) */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name'  => 'required|string|max:255',
                'type'  => 'required|in:boundary,polyline,polygon',
                'color' => 'required|string|max:20',
                'file'  => 'required|file|max:20480',  // maks 20 MB
            ],
            [
                'name.required'  => 'Nama layer wajib diisi.',
                'type.required'  => 'Tipe layer wajib dipilih.',
                'color.required' => 'Warna layer wajib dipilih.',
                'file.required'  => 'File GeoJSON wajib diunggah.',
                'file.max'       => 'Ukuran file maksimal 20 MB.',
            ]
        );

        $file    = $request->file('file');
        $content = $file->get();

        // Validasi isi file adalah GeoJSON yang valid
        $json = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE || ! isset($json->type)) {
            return back()->withErrors(['file' => 'File bukan GeoJSON yang valid.']);
        }

        $filename = time() . '_' . preg_replace('/[^a-z0-9]/i', '_', $request->name) . '.geojson';
        Storage::disk('public')->put('geojson/' . $filename, $content);

        GeojsonFile::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'color'    => $request->color,
            'filename' => $filename,
            'user_id'  => auth()->id(),
        ]);

        return back()->with('success', 'File GeoJSON "' . $request->name . '" berhasil diupload.');
    }

    /** Hapus file GeoJSON */
    public function destroy(GeojsonFile $geojsonFile): RedirectResponse
    {
        Storage::disk('public')->delete('geojson/' . $geojsonFile->filename);
        $geojsonFile->delete();

        return back()->with('success', 'Layer "' . $geojsonFile->name . '" berhasil dihapus.');
    }
}
