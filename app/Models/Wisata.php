<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wisata extends Model
{
    protected $table = 'wisatas';

    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'latitude',
        'longitude',
        'image',
        'user_id',
    ];

    // Cast otomatis agar latitude/longitude selalu bertipe float, bukan string
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /**
     * Relasi: setiap wisata dimiliki oleh satu user (admin yang menambahkan).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
