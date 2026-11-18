<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalonSiswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Semua asesmen kelompok siswa ini.
     */
    public function asesmenKelompoks(): HasMany
    {
        return $this->hasMany(AsesmenKelompok::class);
    }

    /**
     * Asesmen untuk kelompok spesifik.
     */
    public function asesmen(string $kelompokKey): ?AsesmenKelompok
    {
        return $this->asesmenKelompoks->firstWhere('kelompok_key', $kelompokKey);
    }

    /**
     * Hitung berapa kelompok yang sudah selesai dinilai.
     */
    public function getKelompokSelesaiCountAttribute(): int
    {
        return $this->asesmenKelompoks->where('status', 'Selesai')->count();
    }

    /**
     * Cek apakah seluruh 4 kelompok sudah tuntas dinilai.
     */
    public function getIsLengkapAttribute(): bool
    {
        return $this->kelompok_selesai_count >= 4;
    }
}
