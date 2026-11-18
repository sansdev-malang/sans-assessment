<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuruPengamat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Akun User login terhubung.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Asesmen yang ditangani oleh guru pengamat ini.
     */
    public function asesmenKelompoks(): HasMany
    {
        return $this->hasMany(AsesmenKelompok::class);
    }
}
