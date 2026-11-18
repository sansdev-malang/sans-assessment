<?php

use App\Models\CalonSiswa;
use App\Models\GuruPengamat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('halaman observasi smp memuat data guru dan siswa dari database tanpa switcher simulasi', function () {
    $guru = GuruPengamat::where('nip', 'GSMP-2026-01')->first();
    expect($guru)->not->toBeNull();

    $siswa = CalonSiswa::where('no_pendaftaran', 'SPMB-2026-SMP01')->first();
    expect($siswa)->not->toBeNull();

    $response = $this->get(route('guru.observasi', ['guru_nip' => 'GSMP-2026-01', 'siswa_no' => 'SPMB-2026-SMP01']));

    $response->assertStatus(200);
    // Memastikan identitas ruang dan nama guru pengamat dari database tampil
    $response->assertSee($guru->nama);
    $response->assertSee('Kelompok Keagamaan');
    $response->assertSee($siswa->nama);
    $response->assertSee('MySQL Terhubung');

    // Memastikan simulasi switcher strip sudah dihilangkan
    $response->assertDontSee('SIMULASI PENGUJIAN');
    $response->assertDontSee('Pilih salah satu dari 4 Guru Pengamat SMP untuk melihat perspektif penilaian');
    $response->assertDontSee('Pilih Ruang / Guru Pengamat SMP (4 Kelompok):');
});

test('simpan observasi keagamaan berhasil tersimpan ke database', function () {
    $siswa = CalonSiswa::where('no_pendaftaran', 'SPMB-2026-SMP04')->first();
    expect($siswa)->not->toBeNull();

    $scores = [];
    // 27 aspek skala 1-4
    for ($i = 0; $i < 7; $i++) {
        $scores['p1-'.$i] = 4;
    }
    for ($i = 0; $i < 7; $i++) {
        $scores['p2-'.$i] = 4;
    }
    for ($i = 0; $i < 6; $i++) {
        $scores['p3-'.$i] = 3;
    }
    for ($i = 0; $i < 7; $i++) {
        $scores['p4-'.$i] = 4;
    }

    $response = $this->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => 'SPMB-2026-SMP04',
        'kelompok_key' => 'keagamaan',
        'scores' => $scores,
        'catatan' => 'Ananda menguasai bacaan dan gerakan sholat dengan baik.',
        'status' => 'Selesai',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'status' => 'Selesai',
    ]);

    // Verifikasi data masuk ke tabel asesmen_kelompoks
    $this->assertDatabaseHas('asesmen_kelompoks', [
        'calon_siswa_id' => $siswa->id,
        'kelompok_key' => 'keagamaan',
        'status' => 'Selesai',
    ]);
});

test('kelengkapan 4 kelompok otomatis memperbarui status siswa menjadi semua ruang tuntas', function () {
    $siswa = CalonSiswa::where('no_pendaftaran', 'SPMB-2026-SMP05')->first();
    expect($siswa)->not->toBeNull();

    // Selesaikan asesmen keagamaan (yang sebelumnya berstatus 'Sedang Diobservasi')
    $this->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => 'SPMB-2026-SMP05',
        'kelompok_key' => 'keagamaan',
        'total_skor' => 90,
        'status' => 'Selesai',
    ])->assertStatus(200);

    // Selesaikan asesmen akademik (sebelumnya 'Belum Diuji')
    $this->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => 'SPMB-2026-SMP05',
        'kelompok_key' => 'akademik',
        'scores' => ['indo' => 85, 'mtk' => 80, 'ipas' => 82, 'inggris' => 85],
        'status' => 'Selesai',
    ])->assertStatus(200);

    // Selesaikan asesmen bakat minat sehingga seluruh 4/4 kelompok tuntas
    $response = $this->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => 'SPMB-2026-SMP05',
        'kelompok_key' => 'bakat_minat',
        'scores' => [
            'p6-0' => 4, 'p6-1' => 4, 'p6-2' => 4, 'p6-3' => 4, 'p6-4' => 4, 'p6-5' => 4, 'p6-6' => 4,
            'p7-0' => 4, 'p7-1' => 4, 'p7-2' => 4, 'p7-3' => 4, 'p7-4' => 4, 'p7-5' => 4,
        ],
        'status' => 'Selesai',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'is_lengkap' => true,
        'selesai_count' => 4,
        'posisi_sekarang' => 'Semua Ruang Tuntas',
    ]);

    $siswa->refresh();
    expect($siswa->is_lengkap)->toBeTrue();
    expect($siswa->posisi_sekarang)->toBe('Semua Ruang Tuntas');
});
