<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointImage extends Model
{
    protected $fillable = ['point_id', 'filename', 'sort_order'];

    public function point(): BelongsTo
    {
        return $this->belongsTo(PointsModel::class, 'point_id');
    }
}
