<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsModel extends Model
{
    protected $table = 'points';

    protected $guarded = ['id'];

    public function images(): HasMany
    {
        return $this->hasMany(PointImage::class, 'point_id')->orderBy('sort_order')->orderBy('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Semua foto sebuah titik: prioritas point_images, fallback ke kolom image.
     * Kembalikan array filename string.
     */
    public function allImageFilenames(): array
    {
        $imgs = $this->images->pluck('filename')->toArray();
        if (empty($imgs) && $this->image) {
            $imgs = [$this->image];
        }
        return $imgs;
    }

    public function geojson_points()
    {
        $points = $this
            ->select(DB::raw('points.id, ST_AsGeoJSON(points.geom) as geom, points.name,
                points.description, points.image, points.created_at, points.updated_at,
                points.user_id, users.name as user_created'))
            ->leftJoin('users', 'points.user_id', '=', 'users.id')
            ->get();

        // Ambil semua foto sekaligus (hindari N+1)
        $allImages = DB::table('point_images')
            ->orderBy('sort_order')->orderBy('id')
            ->get()->groupBy('point_id');

        $geojson = [
            'type'     => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $imgs = $allImages->has($p->id)
                ? $allImages[$p->id]->pluck('filename')->toArray()
                : [];
            if (empty($imgs) && $p->image) {
                $imgs = [$p->image];
            }

            $geojson['features'][] = [
                'type'     => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id'           => $p->id,
                    'name'         => $p->name,
                    'description'  => $p->description,
                    'created_at'   => $p->created_at,
                    'updated_at'   => $p->updated_at,
                    'image'        => $p->image,
                    'images'       => $imgs,
                    'user_id'      => $p->user_id,
                    'user_created' => $p->user_created,
                ],
            ];
        }
        return $geojson;
    }

    public function geojson_point($id)
    {
        $points = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) as geom, name, description, image,
                created_at, updated_at, user_id'))
            ->where('id', $id)
            ->get();

        $geojson = [
            'type'     => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $geojson['features'][] = [
                'type'     => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'description' => $p->description,
                    'created_at'  => $p->created_at,
                    'updated_at'  => $p->updated_at,
                    'image'       => $p->image,
                    'user_id'     => $p->user_id,
                ],
            ];
        }
        return $geojson;
    }
}
