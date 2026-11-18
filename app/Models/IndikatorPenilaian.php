<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorPenilaian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'rubrik' => 'array',
            'bobot' => 'decimal:2',
            'urutan' => 'integer',
        ];
    }
}
