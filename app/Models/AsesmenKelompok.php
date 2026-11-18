<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsesmenKelompok extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'detail_nilai' => 'array',
            'nilai' => 'decimal:2',
            'total_skor' => 'integer',
            'skor_maksimal' => 'integer',
        ];
    }

    /**
     * Calon siswa yang dinilai.
     */
    public function calonSiswa(): BelongsTo
    {
        return $this->belongsTo(CalonSiswa::class);
    }

    /**
     * Guru pengamat yang menilai.
     */
    public function guruPengamat(): BelongsTo
    {
        return $this->belongsTo(GuruPengamat::class);
    }
}
