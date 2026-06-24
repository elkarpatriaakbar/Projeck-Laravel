<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PointsController extends Controller
{

    public function __construct()
    {
        $this->points = new PointsModel;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map',
        ];

        return view('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate(
            [
                'name'        => 'required|unique:points,name',
                'description' => 'required',
                'geom_point'  => 'required',
                'image'       => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required'        => 'Name is required',
                'name.unique'          => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required'  => 'Location is required',
            ]
        );

        // ✅ FIX 1: Gunakan public_path() agar path selalu benar
        $imageDir = public_path('storage/images');
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0777, true); // true = buat parent folder
        }

        // Get image file
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $name_image = time() . '_point.' . strtolower($image->getClientOriginalExtension());
            $image->move($imageDir, $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom'        => $request->geom_point,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $name_image,
            // ✅ FIX 2: Pastikan kolom user_id ada di tabel, kalau tidak ada hapus baris ini
            // 'user_id'  => auth()->user()->id,
        ];

        // Create Data
        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Point Failed to add');
        }

        return redirect()->route('map')->with('success', 'Point has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Point',
            'id'    => $id,
        ];
        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate(
            [
                'name'        => 'required|unique:points,name,' . $id,
                'description' => 'required',
                'geom_point'  => 'required',
                'image'       => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required'        => 'Name is required',
                'name.unique'          => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_point.required'  => 'Location is required',
            ]
        );

        // ✅ FIX 1: Gunakan public_path() agar path selalu benar
        $imageDir = public_path('storage/images');
        if (!File::exists($imageDir)) {
            File::makeDirectory($imageDir, 0777, true);
        }

        // Get old image file name
        $old_image = $this->points->find($id)->image;

        // Get image file
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $name_image = time() . '_point.' . strtolower($image->getClientOriginalExtension());
            $image->move($imageDir, $name_image);

            // ✅ FIX 3: Hapus gambar lama kalau ada
            if ($old_image != null) {
                $oldImagePath = $imageDir . '/' . $old_image;
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
        } else {
            $name_image = $old_image;
        }

        $data = [
            'geom'        => $request->geom_point,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $name_image,
        ];

        // Update Data
        if (!$this->points->find($id)->update($data)) {
            return redirect()->route('map')->with('error', 'Point Failed to update');
        }

        return redirect()->route('map')->with('success', 'Point has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->points->find($id)->image;

        if (!$this->points->destroy($id)) {
            return redirect()->route('map')->with('error', 'Point Failed to delete');
        }

        // ✅ FIX 3: Hapus file gambar pakai public_path()
        if ($imagefile != null) {
            $imagePath = public_path('storage/images/' . $imagefile);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        return redirect()->route('map')->with('success', 'Point has been deleted');
    }
}
