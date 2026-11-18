<?php

namespace Database\Seeders;

use App\Models\AsesmenKelompok;
use App\Models\CalonSiswa;
use App\Models\GuruPengamat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SPMBDbSeeder extends Seeder
{
    /**
     * Seed the database with Guru, Siswa, and Asesmen data.
     */
    public function run(): void
    {
        // 1. GURU PENGAMAT SMP (4 Kelompok)
        $gurus = [
            [
                'nip' => 'GSMP-2026-01',
                'name' => 'Hadi Susanto, S.Hum.',
                'email' => 'hadi@anaksaleh.sch.id',
                'kelompok_key' => 'keagamaan',
                'kelompok_short' => 'Keagamaan',
                'kelompok_nama' => 'Kelompok Keagamaan',
                'pos_desc' => 'Pos 1–4 (Sholat, Mengaji, Doa-doa Harian, Surat Pendek)',
                'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)',
                'avatar' => 'HS',
                'color' => 'emerald',
            ],
            [
                'nip' => 'GSMP-2026-02',
                'name' => 'Imarotul Muhibbah, S.Si., M.Pd. Gr.',
                'email' => 'imarotul@anaksaleh.sch.id',
                'kelompok_key' => 'akademik',
                'kelompok_short' => 'Akademik CBT',
                'kelompok_nama' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                'pos_desc' => 'Pos 5 CBT (B. Indonesia, Matematika, IPAS, B. Inggris)',
                'ruang' => 'Ruang Kelompok Akademik (Pos 5 CBT)',
                'avatar' => 'IM',
                'color' => 'blue',
            ],
            [
                'nip' => 'GSMP-2026-03',
                'name' => 'Mutaahirotul Hidayati,S.Pd., S.Pd.',
                'email' => 'mutaahirotul@anaksaleh.sch.id',
                'kelompok_key' => 'bakat_minat',
                'kelompok_short' => 'Potensi Bakat Minat',
                'kelompok_nama' => 'Kelompok Potensi Bakat dan Minat',
                'pos_desc' => 'Pos 6 & 7 (Motorik Halus & Kreativitas Inovasi)',
                'ruang' => 'Ruang Kelompok Potensi Bakat & Minat (Pos 6–7)',
                'avatar' => 'MH',
                'color' => 'purple',
            ],
            [
                'nip' => 'GSMP-2026-04',
                'name' => 'Davies Yudisno, S.S.Gr., S.Pd.',
                'email' => 'davies@anaksaleh.sch.id',
                'kelompok_key' => 'pribadi_sosial',
                'kelompok_short' => 'Pribadi & Sosial',
                'kelompok_nama' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                'pos_desc' => 'Pos 8 (Kesiapan Psikososial Transisi SMP & Layanan BK)',
                'ruang' => 'Ruang Kelompok Pribadi & Sosial',
                'avatar' => 'DY',
                'color' => 'amber',
            ],
        ];

        $guruModels = [];
        foreach ($gurus as $gData) {
            $user = User::updateOrCreate(
                ['email' => $gData['email']],
                [
                    'name' => $gData['name'],
                    'password' => Hash::make('guru123'),
                ]
            );

            $guru = GuruPengamat::updateOrCreate(
                ['nip' => $gData['nip']],
                [
                    'user_id' => $user->id,
                    'nama' => $gData['name'],
                    'email' => $gData['email'],
                    'jenjang' => 'smp',
                    'kelompok_key' => $gData['kelompok_key'],
                    'kelompok_short' => $gData['kelompok_short'],
                    'kelompok_nama' => $gData['kelompok_nama'],
                    'pos_desc' => $gData['pos_desc'],
                    'ruang' => $gData['ruang'],
                    'unit' => 'SMP Alam Anak Saleh',
                    'avatar' => $gData['avatar'],
                    'color' => $gData['color'],
                    'status' => 'Aktif',
                ]
            );

            $guruModels[$gData['kelompok_key']] = $guru;
        }

        // Admin SMP
        User::updateOrCreate(
            ['email' => 'admin.smp@anaksaleh.sch.id'],
            [
                'name' => 'Ustadz Ridwan Malik, M.Si.',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. CALON SISWA SMP (5 Siswa)
        $siswas = [
            [
                'no_pendaftaran' => 'SPMB-2026-SMP01',
                'nama' => 'Bilal Ramadhan Putra',
                'usia' => '12 Thn 3 Bln',
                'jenjang' => 'smp',
                'kelas_target' => 'Kelas 7 Bilingual',
                'asal_sekolah' => 'SD Karakter Anak Saleh',
                'wali' => 'Bpk. Andi Ramadhan',
                'kontak' => '0821-2222-0001',
                'mulai_dari' => 'Kelompok Keagamaan',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'status_rekomendasi' => 'Diterima',
                'nilai_akhir_terpadu' => 93.00,
                'catatan_umum' => 'Ananda menunjukkan kesiapan belajar yang sangat komprehensif di seluruh aspek.',
                'asesmens' => [
                    'keagamaan' => ['total_skor' => 100, 'skor_maksimal' => 108, 'nilai' => 92.6, 'status' => 'Selesai', 'kategori' => 'Sangat Baik', 'detail' => ['pos1' => 26, 'pos2' => 25, 'pos3' => 23, 'pos4' => 26]],
                    'akademik' => ['total_skor' => 372, 'skor_maksimal' => 400, 'nilai' => 93.0, 'status' => 'Selesai', 'kategori' => 'Sangat Baik (A)', 'detail' => ['cbt' => ['indo' => 92, 'mtk' => 95, 'ipas' => 90, 'inggris' => 95]]],
                    'bakat_minat' => ['total_skor' => 48, 'skor_maksimal' => 52, 'nilai' => 92.3, 'status' => 'Selesai', 'kategori' => 'Sangat Baik', 'detail' => ['pos6_skor' => 26, 'pos7_skor' => 22]],
                    'pribadi_sosial' => ['total_skor' => 59, 'skor_maksimal' => 64, 'nilai' => 92.1, 'status' => 'Selesai', 'kategori' => 'Sangat Siap', 'detail' => ['angket_skor' => 37, 'observasi_skor' => 22]],
                ],
            ],
            [
                'no_pendaftaran' => 'SPMB-2026-SMP02',
                'nama' => 'Salma Hanifah',
                'usia' => '12 Thn 1 Bln',
                'jenjang' => 'smp',
                'kelas_target' => 'Kelas 7 Tahfidz',
                'asal_sekolah' => 'SD Karakter Anak Saleh',
                'wali' => 'Ibu Lina Hanifah',
                'kontak' => '0821-2222-0002',
                'mulai_dari' => 'Kelompok Pribadi & Sosial',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'status_rekomendasi' => 'Diterima',
                'nilai_akhir_terpadu' => 95.20,
                'catatan_umum' => 'Karakter santun, hafalan dan tajwid sangat istimewa, mandiri penuh.',
                'asesmens' => [
                    'keagamaan' => ['total_skor' => 106, 'skor_maksimal' => 108, 'nilai' => 98.1, 'status' => 'Selesai', 'kategori' => 'Sangat Baik', 'detail' => ['pos1' => 27, 'pos2' => 28, 'pos3' => 24, 'pos4' => 27]],
                    'akademik' => ['total_skor' => 364, 'skor_maksimal' => 400, 'nilai' => 91.0, 'status' => 'Selesai', 'kategori' => 'Sangat Baik (A)', 'detail' => ['cbt' => ['indo' => 94, 'mtk' => 88, 'ipas' => 90, 'inggris' => 92]]],
                    'bakat_minat' => ['total_skor' => 48, 'skor_maksimal' => 52, 'nilai' => 92.6, 'status' => 'Selesai', 'kategori' => 'Sangat Baik', 'detail' => ['pos6_skor' => 25, 'pos7_skor' => 23]],
                    'pribadi_sosial' => ['total_skor' => 61, 'skor_maksimal' => 64, 'nilai' => 95.4, 'status' => 'Selesai', 'kategori' => 'Sangat Siap', 'detail' => ['angket_skor' => 38, 'observasi_skor' => 23]],
                ],
            ],
            [
                'no_pendaftaran' => 'SPMB-2026-SMP03',
                'nama' => 'Umar Al-Faruq',
                'usia' => '12 Thn 6 Bln',
                'jenjang' => 'smp',
                'kelas_target' => 'Kelas 7 Bilingual',
                'asal_sekolah' => 'SD Plus Insan Mandiri',
                'wali' => 'Bpk. Zainal Al-Faruq',
                'kontak' => '0821-2222-0003',
                'mulai_dari' => 'Kelompok Akademik CBT',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'status_rekomendasi' => 'Diterima',
                'nilai_akhir_terpadu' => 82.80,
                'catatan_umum' => 'Daya nalar logika kuat, kemampuan matematika dan sains menonjol.',
                'asesmens' => [
                    'keagamaan' => ['total_skor' => 88, 'skor_maksimal' => 108, 'nilai' => 81.5, 'status' => 'Selesai', 'kategori' => 'Baik', 'detail' => ['pos1' => 23, 'pos2' => 22, 'pos3' => 20, 'pos4' => 23]],
                    'akademik' => ['total_skor' => 344, 'skor_maksimal' => 400, 'nilai' => 86.0, 'status' => 'Selesai', 'kategori' => 'Baik (B)', 'detail' => ['cbt' => ['indo' => 85, 'mtk' => 90, 'ipas' => 86, 'inggris' => 83]]],
                    'bakat_minat' => ['total_skor' => 43, 'skor_maksimal' => 52, 'nilai' => 82.7, 'status' => 'Selesai', 'kategori' => 'Baik', 'detail' => ['pos6_skor' => 23, 'pos7_skor' => 20]],
                    'pribadi_sosial' => ['total_skor' => 50, 'skor_maksimal' => 64, 'nilai' => 78.4, 'status' => 'Selesai', 'kategori' => 'Siap', 'detail' => ['angket_skor' => 31, 'observasi_skor' => 19]],
                ],
            ],
            [
                'no_pendaftaran' => 'SPMB-2026-SMP04',
                'nama' => 'Jasmine Alya Zahra',
                'usia' => '12 Thn 0 Bln',
                'jenjang' => 'smp',
                'kelas_target' => 'Kelas 7 Reguler',
                'asal_sekolah' => 'SD Negeri Percobaan 1',
                'wali' => 'Ibu Zahra Alya',
                'kontak' => '0821-2222-0004',
                'mulai_dari' => 'Kelompok Potensi Bakat & Minat',
                'posisi_sekarang' => 'Sedang di Ruang Akademik',
                'status_rekomendasi' => 'Dalam Proses',
                'nilai_akhir_terpadu' => null,
                'catatan_umum' => 'Aktif berkreasi, komunikasi santun.',
                'asesmens' => [
                    'keagamaan' => ['total_skor' => null, 'skor_maksimal' => 108, 'nilai' => null, 'status' => 'Belum Diuji', 'kategori' => null, 'detail' => null],
                    'akademik' => ['total_skor' => 292, 'skor_maksimal' => 400, 'nilai' => 73.0, 'status' => 'Sedang Diobservasi', 'kategori' => 'Cukup (C)', 'detail' => ['cbt' => ['indo' => 75, 'mtk' => 70, 'ipas' => 72, 'inggris' => 75]]],
                    'bakat_minat' => ['total_skor' => 38, 'skor_maksimal' => 52, 'nilai' => 73.2, 'status' => 'Selesai', 'kategori' => 'Baik', 'detail' => ['pos6_skor' => 20, 'pos7_skor' => 18]],
                    'pribadi_sosial' => ['total_skor' => 45, 'skor_maksimal' => 64, 'nilai' => 70.4, 'status' => 'Selesai', 'kategori' => 'Cukup Siap', 'detail' => ['angket_skor' => 28, 'observasi_skor' => 17]],
                ],
            ],
            [
                'no_pendaftaran' => 'SPMB-2026-SMP05',
                'nama' => 'Hafizh Mubarok',
                'usia' => '12 Thn 9 Bln',
                'jenjang' => 'smp',
                'kelas_target' => 'Kelas 7 Tahfidz',
                'asal_sekolah' => 'SDIT Al-Hikmah',
                'wali' => 'Bpk. Mubarok Hafizh',
                'kontak' => '0821-2222-0005',
                'mulai_dari' => 'Kelompok Pribadi & Sosial',
                'posisi_sekarang' => 'Sedang di Ruang Keagamaan',
                'status_rekomendasi' => 'Dalam Proses',
                'nilai_akhir_terpadu' => null,
                'catatan_umum' => 'Perlu dorongan adaptasi belajar dan pendampingan percaya diri.',
                'asesmens' => [
                    'keagamaan' => ['total_skor' => 82, 'skor_maksimal' => 108, 'nilai' => 75.9, 'status' => 'Sedang Diobservasi', 'kategori' => 'Baik', 'detail' => ['pos1' => 22, 'pos2' => 21, 'pos3' => 18, 'pos4' => 21]],
                    'akademik' => ['total_skor' => null, 'skor_maksimal' => 400, 'nilai' => null, 'status' => 'Belum Diuji', 'kategori' => null, 'detail' => null],
                    'bakat_minat' => ['total_skor' => null, 'skor_maksimal' => 52, 'nilai' => null, 'status' => 'Belum Diuji', 'kategori' => null, 'detail' => null],
                    'pribadi_sosial' => ['total_skor' => 40, 'skor_maksimal' => 64, 'nilai' => 62.5, 'status' => 'Selesai', 'kategori' => 'Butuh Pendampingan Khusus', 'detail' => ['angket_skor' => 25, 'observasi_skor' => 15]],
                ],
            ],
        ];

        foreach ($siswas as $sData) {
            $asesmens = $sData['asesmens'];
            unset($sData['asesmens']);

            $calonSiswa = CalonSiswa::updateOrCreate(
                ['no_pendaftaran' => $sData['no_pendaftaran']],
                $sData
            );

            foreach ($asesmens as $kelKey => $aVal) {
                AsesmenKelompok::updateOrCreate(
                    [
                        'calon_siswa_id' => $calonSiswa->id,
                        'kelompok_key' => $kelKey,
                    ],
                    [
                        'guru_pengamat_id' => $guruModels[$kelKey]->id ?? null,
                        'total_skor' => $aVal['total_skor'],
                        'skor_maksimal' => $aVal['skor_maksimal'],
                        'nilai' => $aVal['nilai'],
                        'status' => $aVal['status'],
                        'kategori' => $aVal['kategori'],
                        'detail_nilai' => $aVal['detail'],
                        'catatan_anecdotal' => 'Observasi telah dicatat dan terverifikasi di sistem.',
                    ]
                );
            }
        }
    }
}
