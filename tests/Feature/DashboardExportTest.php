<?php

use App\Models\AsesmenKelompok;
use App\Models\CalonSiswa;
use App\Models\GuruPengamat;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('admin dapat mengekspor rekap hasil observasi sd ke csv', function () {
    $guru = GuruPengamat::create([
        'nip' => 'GSD-2026-01',
        'nama' => 'Ustadzah Fatimah, S.Pd',
        'email' => 'fatimah@anaksaleh.sch.id',
        'jenjang' => 'sd',
        'kelompok_key' => 'ubudiyah',
        'kelompok_short' => 'Ubudiyah',
        'kelompok_nama' => 'Sentra Ubudiyah',
        'pos_desc' => 'Pos 1.1–1.3',
        'ruang' => 'Ruang Ubudiyah',
    ]);

    $siswa = CalonSiswa::create([
        'no_pendaftaran' => 'SPMB-2026-SD01',
        'nama' => 'Ahmad Zaki',
        'jenjang' => 'sd',
        'kelas_target' => 'Kelas 1 SD',
        'asal_sekolah' => 'TK Anak Saleh',
        'status_rekomendasi' => 'Diterima',
        'nilai_akhir_terpadu' => 92.5,
        'catatan_umum' => 'Mandiri dan santun',
    ]);

    AsesmenKelompok::create([
        'calon_siswa_id' => $siswa->id,
        'guru_pengamat_id' => $guru->id,
        'kelompok_key' => 'ubudiyah',
        'nilai' => 90,
        'status' => 'Selesai',
        'kategori' => 'Sangat Siap',
    ]);

    $response = $this->get(route('admin.dashboard.export', ['jenjang' => 'sd']));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    expect($response->headers->get('content-disposition'))->toContain('rekap-hasil-observasi-sd-');

    $content = $response->getContent();
    // Validasi UTF-8 BOM
    expect(str_starts_with($content, "\xEF\xBB\xBF"))->toBeTrue();
    // Validasi data siswa & sentra
    expect($content)->toContain('SPMB-2026-SD01');
    expect($content)->toContain('Ahmad Zaki');
    expect($content)->toContain('Sentra Ubudiyah (Skor)');
    expect($content)->toContain('Sentra Persiapan (Skor)');
    expect($content)->toContain('Sentra Eksplorasi Diri (Skor)');
    expect($content)->toContain('Ustadzah Fatimah, S.Pd');
});

test('admin dapat mengekspor rekap hasil observasi smp ke csv', function () {
    $siswa = CalonSiswa::create([
        'no_pendaftaran' => 'SPMB-2026-SMP01',
        'nama' => 'Nurul Hidayah',
        'jenjang' => 'smp',
        'kelas_target' => 'Kelas 7 SMP',
        'asal_sekolah' => 'SD Anak Saleh',
        'status_rekomendasi' => 'Diterima',
        'nilai_akhir_terpadu' => 88.0,
    ]);

    $response = $this->get(route('admin.dashboard.export', ['jenjang' => 'smp']));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    expect($response->headers->get('content-disposition'))->toContain('rekap-hasil-observasi-smp-');

    $content = $response->getContent();
    expect(str_starts_with($content, "\xEF\xBB\xBF"))->toBeTrue();
    expect($content)->toContain('SPMB-2026-SMP01');
    expect($content)->toContain('Nurul Hidayah');
    expect($content)->toContain('Kelompok Keagamaan (Skor)');
    expect($content)->toContain('Kelompok Akademik (Skor)');
    expect($content)->toContain('Kelompok Bakat & Minat (Skor)');
    expect($content)->toContain('Kelompok Pribadi & Sosial (Skor)');
});
