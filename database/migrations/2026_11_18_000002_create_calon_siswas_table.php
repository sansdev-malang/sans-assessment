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
        Schema::create('calon_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique()->index();
            $table->string('nama');
            $table->string('usia')->nullable();
            $table->string('jenjang')->default('smp')->index(); // tk, sd, smp
            $table->string('kelas_target')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('wali')->nullable();
            $table->string('kontak')->nullable();
            $table->string('mulai_dari')->nullable(); // kelompok pertama yang dimasuki siswa
            $table->string('posisi_sekarang')->default('Sedang Berlangsung');
            $table->string('status_rekomendasi')->default('Dalam Proses'); // Dalam Proses, Diterima, Pendampingan
            $table->decimal('nilai_akhir_terpadu', 5, 2)->nullable();
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_siswas');
    }
};
