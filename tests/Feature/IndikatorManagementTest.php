<?php

use App\Models\IndikatorPenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('halaman indikator otomatis menginisialisasi master indikator jika masih kosong', function () {
    expect(IndikatorPenilaian::count())->toBe(0);

    $response = $this->get(route('admin.indikator', ['jenjang' => 'sd']));

    $response->assertStatus(200);
    expect(IndikatorPenilaian::where('jenjang', 'sd')->count())->toBeGreaterThan(0);
    expect(IndikatorPenilaian::where('kode', 'SD-UBUD-01')->exists())->toBeTrue();
});

test('admin dapat menambah instrumen indikator baru beserta rubriknya', function () {
    $response = $this->post(route('admin.indikator.store'), [
        'jenjang' => 'sd',
        'aspek' => 'Sentra Ubudiyah',
        'pos_nama' => '1.1 Praktek Wudhu dan Sholat',
        'kode' => 'SD-UBUD-99',
        'nama' => 'Adab Masuk dan Keluar Masjid',
        'deskripsi' => 'Pengamatan kesopanan mendahulukan kaki kanan saat masuk masjid.',
        'bobot' => 3.0,
        'status' => 'Aktif',
        'rubrik_1' => 'Belum paham adab masuk masjid.',
        'rubrik_2' => 'Masuk masjid dengan bimbingan dan diingatkan.',
        'rubrik_3' => 'Mendahulukan kaki kanan secara mandiri.',
        'rubrik_4' => 'Mendahulukan kaki kanan, membaca doa, dan sangat tertib.',
    ]);

    $response->assertRedirect(route('admin.indikator', ['jenjang' => 'sd']));
    $response->assertSessionHas('status');

    $indikator = IndikatorPenilaian::where('kode', 'SD-UBUD-99')->first();
    expect($indikator)->not->toBeNull();
    expect($indikator->nama)->toBe('Adab Masuk dan Keluar Masjid');
    expect($indikator->rubrik)->toBeArray();
    expect($indikator->rubrik[1])->toBe('Belum paham adab masuk masjid.');
    expect($indikator->rubrik[4])->toBe('Mendahulukan kaki kanan, membaca doa, dan sangat tertib.');
});

test('admin dapat mengedit instrumen indikator dan mengubah rubrik penilaian', function () {
    // Inisialisasi dulu data default
    $this->get(route('admin.indikator', ['jenjang' => 'sd']));

    $target = IndikatorPenilaian::where('kode', 'SD-UBUD-01')->firstOrFail();

    $response = $this->post(route('admin.indikator.update'), [
        'id' => $target->id,
        'kode' => 'SD-UBUD-01',
        'aspek' => 'Sentra Ubudiyah',
        'pos_nama' => '1.1 Praktek Wudhu dan Sholat',
        'nama' => 'Niat dan Gerakan Wudhu Sesuai Sunnah (Revisi)',
        'deskripsi' => 'Deskripsi panduan observasi yang telah disesuaikan oleh admin.',
        'bobot' => 4.0,
        'status' => 'Aktif',
        'rubrik_1' => 'Rubrik revisi skor 1: butuh bimbingan penuh.',
        'rubrik_2' => 'Rubrik revisi skor 2: dengan arahan guru.',
        'rubrik_3' => 'Rubrik revisi skor 3: mandiri cukup baik.',
        'rubrik_4' => 'Rubrik revisi skor 4: sempurna dan tertib sunnah.',
    ]);

    $response->assertRedirect(route('admin.indikator', ['jenjang' => 'sd']));
    $response->assertSessionHas('status');

    $target->refresh();
    expect($target->nama)->toBe('Niat dan Gerakan Wudhu Sesuai Sunnah (Revisi)');
    expect((float) $target->bobot)->toBe(4.0);
    expect($target->rubrik[1])->toBe('Rubrik revisi skor 1: butuh bimbingan penuh.');
    expect($target->rubrik[4])->toBe('Rubrik revisi skor 4: sempurna dan tertib sunnah.');
});

test('admin dapat menghapus instrumen indikator', function () {
    $ind = IndikatorPenilaian::create([
        'kode' => 'SD-TEST-DELETE',
        'jenjang' => 'sd',
        'kelompok_key' => 'ubudiyah',
        'aspek' => 'Sentra Ubudiyah',
        'pos_nama' => 'Pos Tes',
        'nama' => 'Instrumen Yang Akan Dihapus',
        'status' => 'Aktif',
    ]);

    $response = $this->post(route('admin.indikator.delete'), [
        'id' => $ind->id,
    ]);

    $response->assertRedirect(route('admin.indikator', ['jenjang' => 'sd']));
    $response->assertSessionHas('status');

    expect(IndikatorPenilaian::where('kode', 'SD-TEST-DELETE')->exists())->toBeFalse();
});
