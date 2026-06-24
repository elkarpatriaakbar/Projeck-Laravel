<?php

namespace App\Http\Controllers;

use App\Models\GeojsonFile;
use App\Models\PointsModel;
use App\Models\PolygonModel;
use App\Models\PolylinesModel;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title'          => 'Dashboard',
            'totalPoints'    => PointsModel::count(),
            'totalPolylines' => PolylinesModel::count(),
            'totalPolygon'   => PolygonModel::count(),
            'totalGeojson'   => GeojsonFile::count(),
            'latestPoints'   => PointsModel::latest()->take(5)->get(),
            'geojsonFiles'   => GeojsonFile::with('user')->latest()->get(),
        ]);
    }
}
