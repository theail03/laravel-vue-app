<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'matrix_id',
        'row',
        'column',
        'path',
    ];

    // Define the relationship with the Matrix model
    public function matrix()
    {
        return $this->belongsTo(Matrix::class);
    }
}
