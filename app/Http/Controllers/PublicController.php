<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;

class PublicController extends Controller
{
    public function index()
    {
        return view('home', [
            'title'  => 'Home',
            // Ambil data objek wisata dari tabel points
            'points' => PointsModel::select('id', 'name', 'description', 'image')->with('images')->latest()->get(),
        ]);
    }
}
