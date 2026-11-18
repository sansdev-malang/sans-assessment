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
        Schema::create('indikator_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->index();
            $table->string('jenjang')->default('smp')->index(); // tk, sd, smp
            $table->string('kelompok_key')->index(); // keagamaan, akademik, bakat_minat, pribadi_sosial
            $table->string('pos_nama');
            $table->string('pos_id')->nullable();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 2)->default(0);
            $table->string('status')->default('Aktif');
            $table->json('rubrik')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_penilaians');
    }
};
