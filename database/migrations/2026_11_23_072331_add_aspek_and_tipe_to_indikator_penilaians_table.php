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
        Schema::table('indikator_penilaians', function (Blueprint $table) {
            $table->string('aspek')->nullable()->after('kelompok_key');
            $table->string('tipe')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_penilaians', function (Blueprint $table) {
            $table->dropColumn(['aspek', 'tipe']);
        });
    }
};
