<?php

use App\Models\CalonSiswa;
use App\Models\GuruPengamat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Buat Calon Siswa SD
    $this->siswa = CalonSiswa::create([
        'no_pendaftaran' => 'SANS-2027-TEST01',
        'nama' => 'Ananda Uji Observasi SD',
        'jenjang' => 'sd',
        'usia' => '6 Thn 2 Bln',
        'kelas_target' => 'Kelas 1',
        'asal_sekolah' => 'TK Anak Saleh',
        'wali' => 'Bunda Uji',
        'kontak' => '081234567890',
        'mulai_dari' => 'Sentra Ubudiyah',
        'posisi_sekarang' => 'Sedang Menunggu Giliran',
        'status_rekomendasi' => 'Belum Diuji',
    ]);

    // Buat User Akun Guru 1
    $this->userGuru1 = User::create([
        'name' => 'Ustadzah Fatimah',
        'email' => 'fatimah@anaksaleh.sch.id',
        'password' => Hash::make('password123'),
    ]);

    // Guru Pengamat 1 (Sentra Ubudiyah)
    $this->guru1 = GuruPengamat::create([
        'user_id' => $this->userGuru1->id,
        'nip' => 'GP-SD-UBUD-01',
        'nama' => 'Ustadzah Fatimah, S.Pd.I',
        'email' => 'fatimah@anaksaleh.sch.id',
        'jenjang' => 'sd',
        'kelompok_key' => 'ubudiyah',
        'kelompok_short' => 'Sentra Ubudiyah',
        'kelompok_nama' => 'Sentra Ubudiyah (Wudhu, Sholat, Doa & Hafalan)',
        'ruang' => 'Ruang Sentra Ubudiyah',
        'pos_desc' => 'Pos 1.1 Wudhu & Sholat',
        'unit' => 'SD Alam Anak Saleh',
        'avatar' => 'FA',
        'color' => 'emerald',
        'status' => 'Aktif',
    ]);

    // Buat User Akun Guru 2 (Co-Pengamat di Sentra yang sama)
    $this->userGuru2 = User::create([
        'name' => 'Ustadz Ahmad',
        'email' => 'ahmad@anaksaleh.sch.id',
        'password' => Hash::make('password123'),
    ]);

    // Guru Pengamat 2 (Sentra Ubudiyah)
    $this->guru2 = GuruPengamat::create([
        'user_id' => $this->userGuru2->id,
        'nip' => 'GP-SD-UBUD-02',
        'nama' => 'Ustadz Ahmad, S.Pd',
        'email' => 'ahmad@anaksaleh.sch.id',
        'jenjang' => 'sd',
        'kelompok_key' => 'ubudiyah',
        'kelompok_short' => 'Sentra Ubudiyah',
        'kelompok_nama' => 'Sentra Ubudiyah (Wudhu, Sholat, Doa & Hafalan)',
        'ruang' => 'Ruang Sentra Ubudiyah',
        'pos_desc' => 'Pos 1.1 Wudhu & Sholat',
        'unit' => 'SD Alam Anak Saleh',
        'avatar' => 'AH',
        'color' => 'emerald',
        'status' => 'Aktif',
    ]);
});

test('guru sd dapat langsung mengakses halaman observasi siswa tanpa klaim', function () {
    $response = $this->withSession([
        'auth_user' => [
            'id' => $this->guru1->id,
            'user_id' => $this->userGuru1->id,
            'name' => $this->guru1->nama,
            'nip' => $this->guru1->nip,
            'role' => 'guru',
            'jenjang' => 'sd',
            'kelompok_key' => 'ubudiyah',
        ],
    ])->get(route('guru.observasi', [
        'guru_nip' => $this->guru1->nip,
        'siswa_no' => $this->siswa->no_pendaftaran,
    ]));

    $response->assertStatus(200);
    $response->assertSee('Ananda Uji Observasi SD');
    $response->assertSee('Form Penilaian');
    $response->assertDontSee('Klaim Siswa Ini');
    $response->assertDontSee('Terkunci Co-Pengamat');
});

test('guru sd dapat langsung menilai siswa tanpa harus mengklaim terlebih dahulu', function () {
    $response = $this->withSession([
        'auth_user' => [
            'id' => $this->guru1->id,
            'user_id' => $this->userGuru1->id,
            'name' => $this->guru1->nama,
            'nip' => $this->guru1->nip,
            'role' => 'guru',
            'jenjang' => 'sd',
            'kelompok_key' => 'ubudiyah',
        ],
    ])->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => $this->siswa->no_pendaftaran,
        'kelompok_key' => 'ubudiyah',
        'catatan' => 'Ananda sangat tertib dan mandiri dalam wudhu serta sholat.',
        'scores' => [
            'SD-UBUD-01' => 4,
            'SD-UBUD-02' => 4,
            'SD-UBUD-03' => 3,
            'SD-UBUD-04' => 4,
            'SD-UBUD-05' => 4,
            'SD-UBUD-06' => 4,
            'SD-UBUD-07' => 4,
        ],
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'status' => 'Selesai',
    ]);

    $asesmen = $this->siswa->asesmen('ubudiyah');
    expect($asesmen)->not->toBeNull();
    expect($asesmen->status)->toBe('Selesai');
    expect($asesmen->guru_pengamat_id)->toBe($this->guru1->id);
});

test('guru co-pengamat lain dapat menilai atau memperbarui nilai tanpa terblokir', function () {
    // Guru 1 menilai duluan
    $this->withSession([
        'auth_user' => [
            'id' => $this->guru1->id,
            'user_id' => $this->userGuru1->id,
            'name' => $this->guru1->nama,
            'nip' => $this->guru1->nip,
            'role' => 'guru',
            'jenjang' => 'sd',
            'kelompok_key' => 'ubudiyah',
        ],
    ])->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => $this->siswa->no_pendaftaran,
        'kelompok_key' => 'ubudiyah',
        'catatan' => 'Catatan awal guru 1',
        'scores' => [
            'SD-UBUD-01' => 3,
        ],
    ]);

    // Guru 2 co-pengamat memperbarui penilaian tanpa blokir klaim
    $response = $this->withSession([
        'auth_user' => [
            'id' => $this->guru2->id,
            'user_id' => $this->userGuru2->id,
            'name' => $this->guru2->nama,
            'nip' => $this->guru2->nip,
            'role' => 'guru',
            'jenjang' => 'sd',
            'kelompok_key' => 'ubudiyah',
        ],
    ])->postJson(route('guru.observasi.simpan'), [
        'siswa_no' => $this->siswa->no_pendaftaran,
        'kelompok_key' => 'ubudiyah',
        'catatan' => 'Diperbarui oleh Ustadz Ahmad',
        'scores' => [
            'SD-UBUD-01' => 4,
            'SD-UBUD-02' => 4,
        ],
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $asesmen = $this->siswa->fresh()->asesmen('ubudiyah');
    expect($asesmen->guru_pengamat_id)->toBe($this->guru2->id);
    expect($asesmen->catatan_anecdotal)->toBe('Diperbarui oleh Ustadz Ahmad');
});
