<?php

namespace App\Http\Controllers;

use App\Models\PointImage;
use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PointsController extends Controller
{
    protected PointsModel $points;

    public function __construct()
    {
        $this->points = new PointsModel;
    }

    public function index()
    {
        return view('map', ['title' => 'Map']);
    }

    /** Halaman publik detail satu titik wisata */
    public function show(string $id)
    {
        $point = PointsModel::select(DB::raw(
            "id, name, description, image, created_at, user_id,
             ST_X(geom) as lng, ST_Y(geom) as lat"
        ))->with(['images', 'user'])->where('id', $id)->firstOrFail();

        return view('destinasi.show', [
            'title' => $point->name,
            'point' => $point,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'        => 'required|unique:points,name',
                'description' => 'required',
                'geom_point'  => 'required',
                'images'      => 'nullable|array',
                'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            ],
            [
                'name.required'        => 'Nama wajib diisi.',
                'name.unique'          => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
                'geom_point.required'  => 'Lokasi wajib dipilih di peta.',
            ]
        );

        $imageDir = public_path('storage/images');
        File::ensureDirectoryExists($imageDir);

        // Simpan foto pertama ke kolom `image` (backward compat) dan semua ke point_images
        $coverName = null;
        $point     = $this->points->create([
            'geom'        => $request->geom_point,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => null,
            'user_id'     => auth()->id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $filename = time() . '_' . $i . '_point.' . strtolower($file->getClientOriginalExtension());
                $file->move($imageDir, $filename);

                PointImage::create([
                    'point_id'   => $point->id,
                    'filename'   => $filename,
                    'sort_order' => $i,
                ]);

                if ($i === 0) {
                    $coverName = $filename;
                }
            }
            $point->update(['image' => $coverName]);
        }

        return redirect()->route('map')->with('success', 'Objek wisata berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $point = PointsModel::with('images')->findOrFail($id);
        return view('edit-point', [
            'title' => 'Edit Point',
            'id'    => $id,
            'point' => $point,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name'        => 'required|unique:points,name,' . $id,
                'description' => 'required',
                'geom_point'  => 'required',
                'images'      => 'nullable|array',
                'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'integer|exists:point_images,id',
            ],
            [
                'name.required'        => 'Nama wajib diisi.',
                'name.unique'          => 'Nama sudah digunakan.',
                'description.required' => 'Deskripsi wajib diisi.',
                'geom_point.required'  => 'Lokasi wajib dipilih di peta.',
            ]
        );

        $imageDir = public_path('storage/images');
        File::ensureDirectoryExists($imageDir);

        $pointModel = $this->points->findOrFail($id);

        // Hapus foto yang diminta
        if ($request->filled('delete_images')) {
            $toDelete = PointImage::whereIn('id', $request->delete_images)
                                  ->where('point_id', $id)->get();
            foreach ($toDelete as $img) {
                $path = $imageDir . '/' . $img->filename;
                File::exists($path) && File::delete($path);
                $img->delete();
            }
        }

        // Upload foto baru
        $currentMax = PointImage::where('point_id', $id)->max('sort_order') ?? -1;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $filename = time() . '_' . $i . '_point.' . strtolower($file->getClientOriginalExtension());
                $file->move($imageDir, $filename);
                PointImage::create([
                    'point_id'   => $id,
                    'filename'   => $filename,
                    'sort_order' => $currentMax + $i + 1,
                ]);
            }
        }

        // Update cover: foto pertama yang tersisa
        $firstImage = PointImage::where('point_id', $id)->orderBy('sort_order')->orderBy('id')->first();
        $coverName  = $firstImage?->filename ?? $pointModel->image;

        $pointModel->update([
            'geom'        => $request->geom_point,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $coverName,
        ]);

        return redirect()->route('map')->with('success', 'Objek wisata berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pointModel = $this->points->findOrFail($id);
        $imageDir   = public_path('storage/images');

        // Hapus semua foto dari point_images
        foreach ($pointModel->images as $img) {
            $path = $imageDir . '/' . $img->filename;
            File::exists($path) && File::delete($path);
        }

        // Hapus cover image jika ada dan tidak tercover point_images
        if ($pointModel->image && $pointModel->images->isEmpty()) {
            $path = $imageDir . '/' . $pointModel->image;
            File::exists($path) && File::delete($path);
        }

        $pointModel->delete(); // cascade hapus point_images juga

        return redirect()->route('map')->with('success', 'Objek wisata berhasil dihapus.');
    }
}
