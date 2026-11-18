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
        Schema::create('guru_pengamats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('jenjang')->default('smp'); // tk, sd, smp
            $table->string('kelompok_key'); // keagamaan, akademik, bakat_minat, pribadi_sosial
            $table->string('kelompok_short');
            $table->string('kelompok_nama');
            $table->string('pos_desc');
            $table->string('ruang');
            $table->string('unit')->default('SMP Alam Anak Saleh');
            $table->string('avatar')->default('GP');
            $table->string('color')->default('emerald');
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_pengamats');
    }
};
