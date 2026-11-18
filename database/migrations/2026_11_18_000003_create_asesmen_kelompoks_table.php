<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asesmen_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_siswa_id')->constrained('calon_siswas')->cascadeOnDelete();
            $table->foreignId('guru_pengamat_id')->nullable()->constrained('guru_pengamats')->nullOnDelete();
            $table->string('kelompok_key')->index(); // keagamaan, akademik, bakat_minat, pribadi_sosial
            $table->integer('total_skor')->nullable();
            $table->integer('skor_maksimal')->default(100);
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('status')->default('Belum Diuji'); // Belum Diuji, Sedang Diobservasi, Selesai
            $table->json('detail_nilai')->nullable(); // detail jawaban aspek 1-4, CBT 4 mapel, angket, dll.
            $table->text('catatan_anecdotal')->nullable();
            $table->string('kategori')->nullable(); // Sangat Baik, Sangat Siap, A, dll.
            $table->timestamps();

            $table->unique(['calon_siswa_id', 'kelompok_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmen_kelompoks');
    }
};
