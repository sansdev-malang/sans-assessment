<?php

use App\Models\CalonSiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

test('import calon siswa sd from user csv works properly and normalizes phone numbers', function () {
    $csvPath = 'C:/Users/SERVER PC/.gemini/antigravity/brain/36abc4c1-ce02-448a-97b5-55d0538093ff/.user_uploaded/media_1795478076672_c0fc0169.csv';
    $csvContent = file_get_contents($csvPath);

    $file = UploadedFile::fake()->createWithContent('template-calon-siswa-sd.csv', $csvContent);

    // Simulasi admin berada di tab smp saat mengunggah file sd
    $response = $this->withSession([
        'auth_user' => [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'smp',
        ],
    ])->post(route('admin.siswa.import'), [
        'jenjang' => 'auto',
        'file_siswa' => $file,
    ]);

    // Harus otomatis dialihkan ke jenjang sd dan session disinkronkan ke sd
    $response->assertRedirect(route('admin.siswa', ['jenjang' => 'sd']));
    $response->assertSessionHas('status');
    expect(session('auth_user.jenjang'))->toBe('sd');

    // Seluruh 16 siswa berhasil diimpor
    expect(CalonSiswa::where('jenjang', 'sd')->count())->toBe(16);

    // Cek siswa dengan tanda koma di dalam nama wali yang diapit tanda petik
    $sabda = CalonSiswa::where('no_pendaftaran', 'SANS-2027-0232')->first();
    expect($sabda)->not->toBeNull();
    expect($sabda->nama)->toBe('SABDA SAGARA DISSERTASIA');
    expect($sabda->wali)->toBe('USWATUN HASANAH, S.S');
    expect($sabda->kontak)->toBe('081333184680');

    // Cek siswa pertama normalisasi nomor kontak berawalan 8 -> 08...
    $arshaka = CalonSiswa::where('no_pendaftaran', 'SANS-2027-0079')->first();
    expect($arshaka)->not->toBeNull();
    expect($arshaka->nama)->toBe('MUHAMMAD ARSHAKA KEENANDRA NAJIH');
    expect($arshaka->kontak)->toBe('081336323434');
    expect($arshaka->asesmenKelompoks()->count())->toBe(3); // ubudiyah, persiapan, eksplorasi
});

test('import calon siswa dengan delimiter titik koma dan bom utf-8', function () {
    $csv = "\xEF\xBB\xBFno_pendaftaran;nama;jenjang;usia;kelas_target;asal_sekolah;wali;kontak\n";
    $csv .= "SANS-2027-9991;Siswa Uji Titik Koma;sd;6 Thn;Kelas 1;TK Kartika;Wali Siswa;08123456789\n";

    $file = UploadedFile::fake()->createWithContent('test-semicolon.csv', $csv);

    $response = $this->withSession([
        'auth_user' => [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
        ],
    ])->post(route('admin.siswa.import'), [
        'jenjang' => 'auto',
        'file_siswa' => $file,
    ]);

    $response->assertRedirect(route('admin.siswa', ['jenjang' => 'sd']));
    $response->assertSessionHas('status');

    $siswa = CalonSiswa::where('no_pendaftaran', 'SANS-2027-9991')->first();
    expect($siswa)->not->toBeNull();
    expect($siswa->nama)->toBe('Siswa Uji Titik Koma');
});

test('import calon siswa mengembalikan pesan error jika file csv tidak valid', function () {
    $file = UploadedFile::fake()->createWithContent('empty.csv', '');

    $response = $this->withSession([
        'auth_user' => [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
        ],
    ])->post(route('admin.siswa.import'), [
        'jenjang' => 'sd',
        'file_siswa' => $file,
    ]);

    $response->assertSessionHas('error');
});
