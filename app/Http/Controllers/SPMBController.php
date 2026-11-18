<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SPMBController extends Controller
{
    /**
     * Show the Login Page (Portals Guru & Admin TK, SD, SMP).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle Login authentication and role-based redirect.
     */
    public function login(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'guru');
        $email = strtolower($request->input('email', ''));

        // Deteksi unit admin berdasarkan email (tab Admin tunggal)
        if ($role === 'admin' || str_contains($email, 'admin')) {
            // Admin TK
            if (str_contains($email, 'admin.tk')) {
                session([
                    'auth_user' => [
                        'name' => 'Ustadzah Siti Aminah, S.Pd.I.',
                        'email' => $email ?: 'admin.tk@anaksaleh.sch.id',
                        'role' => 'admin_tk',
                        'jenjang' => 'tk',
                        'unit' => 'KB & TK Anak Saleh',
                        'role_label' => 'Admin SPMB KB & TK',
                    ],
                ]);

                return redirect()->route('admin.dashboard')
                    ->with('status', 'Selamat datang di Dashboard Observasi SPMB KB & TK Anak Saleh!');
            }

            // Admin SMP
            if (str_contains($email, 'admin.smp')) {
                session([
                    'auth_user' => [
                        'name' => 'Ustadz Ridwan Malik, M.Si.',
                        'email' => $email ?: 'admin.smp@anaksaleh.sch.id',
                        'role' => 'admin_smp',
                        'jenjang' => 'smp',
                        'unit' => 'SMP Alam Anak Saleh',
                        'role_label' => 'Admin SPMB SMP',
                    ],
                ]);

                return redirect()->route('admin.dashboard')
                    ->with('status', 'Selamat datang di Dashboard Observasi SPMB SMP Anak Saleh!');
            }

            // Admin SD (default admin)
            session([
                'auth_user' => [
                    'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
                    'email' => $email ?: 'admin.sd@anaksaleh.sch.id',
                    'role' => 'admin_sd',
                    'jenjang' => 'sd',
                    'unit' => 'SD Karakter Anak Saleh',
                    'role_label' => 'Admin SPMB SD',
                ],
            ]);

            return redirect()->route('admin.dashboard')
                ->with('status', 'Selamat datang di Dashboard Observasi SPMB SD Anak Saleh!');
        }

        // Guru Pengamat SMP (Hadi Susanto, Imarotul, Mutaahirotul, Davies)
        if (str_contains($email, 'hadi') || str_contains($email, 'keagamaan') || $request->input('nip') === 'GSMP-2026-01') {
            session([
                'auth_user' => [
                    'name' => 'Hadi Susanto, S.Hum.',
                    'nip' => 'GSMP-2026-01',
                    'email' => 'hadi@anaksaleh.sch.id',
                    'role' => 'guru',
                    'jenjang' => 'smp',
                    'unit' => 'SMP Alam Anak Saleh',
                    'role_label' => 'Guru Pengamat Kelompok Keagamaan',
                    'kelompok' => 'Kelompok Keagamaan',
                    'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)',
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => 'GSMP-2026-01'])
                ->with('status', 'Selamat bertugas, Ustadz Hadi Susanto (Kelompok Keagamaan)!');
        }

        if (str_contains($email, 'imarotul') || str_contains($email, 'akademik') || $request->input('nip') === 'GSMP-2026-02') {
            session([
                'auth_user' => [
                    'name' => 'Imarotul Muhibbah, S.Si., M.Pd. Gr.',
                    'nip' => 'GSMP-2026-02',
                    'email' => 'imarotul@anaksaleh.sch.id',
                    'role' => 'guru',
                    'jenjang' => 'smp',
                    'unit' => 'SMP Alam Anak Saleh',
                    'role_label' => 'Guru Pengamat Kelompok Akademik',
                    'kelompok' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'ruang' => 'Ruang Kelompok Akademik (Pos 5 CBT)',
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => 'GSMP-2026-02'])
                ->with('status', 'Selamat bertugas, Ustadzah Imarotul Muhibbah (Kelompok Akademik CBT)!');
        }

        if (str_contains($email, 'mutaahirotul') || str_contains($email, 'bakat') || $request->input('nip') === 'GSMP-2026-03') {
            session([
                'auth_user' => [
                    'name' => 'Mutaahirotul Hidayati,S.Pd., S.Pd.',
                    'nip' => 'GSMP-2026-03',
                    'email' => 'mutaahirotul@anaksaleh.sch.id',
                    'role' => 'guru',
                    'jenjang' => 'smp',
                    'unit' => 'SMP Alam Anak Saleh',
                    'role_label' => 'Guru Pengamat Kelompok Potensi Bakat & Minat',
                    'kelompok' => 'Kelompok Potensi Bakat dan Minat',
                    'ruang' => 'Ruang Kelompok Potensi Bakat & Minat (Pos 6–7)',
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => 'GSMP-2026-03'])
                ->with('status', 'Selamat bertugas, Ustadzah Mutaahirotul Hidayati (Kelompok Potensi Bakat & Minat)!');
        }

        if (str_contains($email, 'davies') || str_contains($email, 'pribadi') || str_contains($email, 'sosial') || $request->input('nip') === 'GSMP-2026-04') {
            session([
                'auth_user' => [
                    'name' => 'Davies Yudisno, S.S.Gr., S.Pd.',
                    'nip' => 'GSMP-2026-04',
                    'email' => 'davies@anaksaleh.sch.id',
                    'role' => 'guru',
                    'jenjang' => 'smp',
                    'unit' => 'SMP Alam Anak Saleh',
                    'role_label' => 'Guru Pengamat Perkembangan Pribadi & Sosial',
                    'kelompok' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'ruang' => 'Ruang Kelompok Pribadi & Sosial',
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => 'GSMP-2026-04'])
                ->with('status', 'Selamat bertugas, Ustadz Davies Yudisno (Kelompok Pribadi & Sosial / BK)!');
        }

        if (str_contains($email, 'guru.smp')) {
            session([
                'auth_user' => [
                    'name' => 'Hadi Susanto, S.Hum.',
                    'nip' => 'GSMP-2026-01',
                    'email' => 'hadi@anaksaleh.sch.id',
                    'role' => 'guru',
                    'jenjang' => 'smp',
                    'unit' => 'SMP Alam Anak Saleh',
                    'role_label' => 'Guru Pengamat Kelompok Keagamaan',
                    'kelompok' => 'Kelompok Keagamaan',
                    'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)',
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => 'GSMP-2026-01'])
                ->with('status', 'Selamat datang di Halaman Observasi Guru Pengamat SMP!');
        }

        // Default: Guru / Pengamat SD/TK
        session([
            'auth_user' => [
                'name' => 'Ustadzah Fatimah Azzahra, S.Pd.',
                'email' => $email ?: 'guru@anaksaleh.sch.id',
                'role' => 'guru',
                'jenjang' => 'all',
                'unit' => 'Perguruan Anak Saleh',
                'role_label' => 'Tim Pengamat & Psikolog Anak',
            ],
        ]);

        return redirect()->route('guru.observasi')
            ->with('status', 'Selamat bertugas di Halaman Observasi Siswa Baru!');
    }

    /**
     * Halaman Observasi Siswa Baru untuk Guru (Termasuk 4 Guru Pengamat SMP).
     */
    public function observasi(Request $request): View
    {
        $allGuruSMP = [
            'GSMP-2026-01' => [
                'nip' => 'GSMP-2026-01',
                'name' => 'Hadi Susanto, S.Hum.',
                'kelompok_key' => 'keagamaan',
                'kelompok' => 'Kelompok Keagamaan',
                'kelompok_short' => 'Keagamaan',
                'pos_desc' => 'Pos 1–4 (Sholat, Mengaji, Doa-doa Harian, Surat Pendek)',
                'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)',
                'unit' => 'SMP Alam Anak Saleh',
                'avatar' => 'HS',
                'email' => 'hadi@anaksaleh.sch.id',
                'color' => 'emerald',
                'icon' => 'book-open',
            ],
            'GSMP-2026-02' => [
                'nip' => 'GSMP-2026-02',
                'name' => 'Imarotul Muhibbah, S.Si., M.Pd. Gr.',
                'kelompok_key' => 'akademik',
                'kelompok' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                'kelompok_short' => 'Akademik CBT',
                'pos_desc' => 'Pos 5 CBT (B. Indonesia, Matematika, IPAS, B. Inggris)',
                'ruang' => 'Ruang Kelompok Akademik (Pos 5 CBT)',
                'unit' => 'SMP Alam Anak Saleh',
                'avatar' => 'IM',
                'email' => 'imarotul@anaksaleh.sch.id',
                'color' => 'blue',
                'icon' => 'calculator',
            ],
            'GSMP-2026-03' => [
                'nip' => 'GSMP-2026-03',
                'name' => 'Mutaahirotul Hidayati,S.Pd., S.Pd.',
                'kelompok_key' => 'bakat_minat',
                'kelompok' => 'Kelompok Potensi Bakat dan Minat',
                'kelompok_short' => 'Potensi Bakat Minat',
                'pos_desc' => 'Pos 6 & 7 (Motorik Halus & Kreativitas Inovasi)',
                'ruang' => 'Ruang Kelompok Potensi Bakat & Minat (Pos 6–7)',
                'unit' => 'SMP Alam Anak Saleh',
                'avatar' => 'MH',
                'email' => 'mutaahirotul@anaksaleh.sch.id',
                'color' => 'purple',
                'icon' => 'palette',
            ],
            'GSMP-2026-04' => [
                'nip' => 'GSMP-2026-04',
                'name' => 'Davies Yudisno, S.S.Gr., S.Pd.',
                'kelompok_key' => 'pribadi_sosial',
                'kelompok' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                'kelompok_short' => 'Pribadi & Sosial',
                'pos_desc' => 'Pos 8 (Kesiapan Psikososial Transisi SMP & Layanan BK)',
                'ruang' => 'Ruang Kelompok Pribadi & Sosial',
                'unit' => 'SMP Alam Anak Saleh',
                'avatar' => 'DY',
                'email' => 'davies@anaksaleh.sch.id',
                'color' => 'amber',
                'icon' => 'heart-handshake',
            ],
        ];

        // Tentukan guru aktif
        $requestedNip = $request->query('guru_nip', session('auth_user.nip', 'GSMP-2026-01'));
        if (! isset($allGuruSMP[$requestedNip])) {
            $requestedNip = 'GSMP-2026-01';
        }
        $activeGuru = $allGuruSMP[$requestedNip];

        // Sinkronisasi sesi guru aktif
        $user = session('auth_user', []);
        $user['name'] = $activeGuru['name'];
        $user['nip'] = $activeGuru['nip'];
        $user['email'] = $activeGuru['email'];
        $user['role'] = 'guru';
        $user['jenjang'] = 'smp';
        $user['unit'] = 'SMP Alam Anak Saleh';
        $user['role_label'] = 'Guru Pengamat '.$activeGuru['kelompok_short'];
        $user['kelompok'] = $activeGuru['kelompok'];
        $user['ruang'] = $activeGuru['ruang'];
        session(['auth_user' => $user]);

        // Daftar Calon Murid SMP (Rotasi Terbuka / Tanpa Antrean Kaku)
        // Siswa bebas masuk kelompok mana pun yang tersedia, urutan fleksibel, wajib terpenuhi 4/4 kelompok
        $siswaSMP = [
            [
                'no' => 'SPMB-2026-SMP01',
                'name' => 'Bilal Ramadhan Putra',
                'usia' => '12 Thn 3 Bln',
                'kelas_target' => 'Kelas 7 Bilingual',
                'asal_sekolah' => 'SD Karakter Anak Saleh',
                'wali' => 'Bpk. Andi Ramadhan',
                'kontak' => '0821-2222-0001',
                'mulai_dari' => 'Kelompok Keagamaan',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'keagamaan' => ['pos1' => 26, 'pos2' => 25, 'pos3' => 23, 'pos4' => 26, 'total_skor' => 100, 'nilai' => 92.6, 'status' => 'Selesai'],
                'akademik' => ['cbt' => ['indo' => 92, 'mtk' => 95, 'ipas' => 90, 'inggris' => 95], 'pos5' => 93.0, 'nilai' => 93.0, 'status' => 'Selesai'],
                'bakat_minat' => ['pos6_skor' => 26, 'pos6' => 92.9, 'pos7_skor' => 22, 'pos7' => 91.7, 'nilai' => 92.3, 'status' => 'Selesai'],
                'pribadi_sosial' => ['angket_skor' => 37, 'angket_nilai' => 92.5, 'observasi_skor' => 22, 'observasi_nilai' => 91.7, 'nilai' => 92.1, 'status' => 'Selesai', 'kategori_bk' => 'Sangat Siap'],
            ],
            [
                'no' => 'SPMB-2026-SMP02',
                'name' => 'Salma Hanifah',
                'usia' => '12 Thn 1 Bln',
                'kelas_target' => 'Kelas 7 Tahfidz',
                'asal_sekolah' => 'SD Karakter Anak Saleh',
                'wali' => 'Ibu Lina Hanifah',
                'kontak' => '0821-2222-0002',
                'mulai_dari' => 'Kelompok Pribadi & Sosial',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'keagamaan' => ['pos1' => 27, 'pos2' => 28, 'pos3' => 24, 'pos4' => 27, 'total_skor' => 106, 'nilai' => 98.1, 'status' => 'Selesai'],
                'akademik' => ['cbt' => ['indo' => 94, 'mtk' => 88, 'ipas' => 90, 'inggris' => 92], 'pos5' => 91.0, 'nilai' => 91.0, 'status' => 'Selesai'],
                'bakat_minat' => ['pos6_skor' => 25, 'pos6' => 89.3, 'pos7_skor' => 23, 'pos7' => 95.8, 'nilai' => 92.6, 'status' => 'Selesai'],
                'pribadi_sosial' => ['angket_skor' => 38, 'angket_nilai' => 95.0, 'observasi_skor' => 23, 'observasi_nilai' => 95.8, 'nilai' => 95.4, 'status' => 'Selesai', 'kategori_bk' => 'Sangat Siap'],
            ],
            [
                'no' => 'SPMB-2026-SMP03',
                'name' => 'Umar Al-Faruq',
                'usia' => '12 Thn 6 Bln',
                'kelas_target' => 'Kelas 7 Bilingual',
                'asal_sekolah' => 'SD Plus Insan Mandiri',
                'wali' => 'Bpk. Zainal Al-Faruq',
                'kontak' => '0821-2222-0003',
                'mulai_dari' => 'Kelompok Akademik CBT',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
                'keagamaan' => ['pos1' => 23, 'pos2' => 22, 'pos3' => 20, 'pos4' => 23, 'total_skor' => 88, 'nilai' => 81.5, 'status' => 'Selesai'],
                'akademik' => ['cbt' => ['indo' => 85, 'mtk' => 90, 'ipas' => 86, 'inggris' => 83], 'pos5' => 86.0, 'nilai' => 86.0, 'status' => 'Selesai'],
                'bakat_minat' => ['pos6_skor' => 23, 'pos6' => 82.1, 'pos7_skor' => 20, 'pos7' => 83.3, 'nilai' => 82.7, 'status' => 'Selesai'],
                'pribadi_sosial' => ['angket_skor' => 31, 'angket_nilai' => 77.5, 'observasi_skor' => 19, 'observasi_nilai' => 79.2, 'nilai' => 78.4, 'status' => 'Selesai', 'kategori_bk' => 'Siap'],
            ],
            [
                'no' => 'SPMB-2026-SMP04',
                'name' => 'Jasmine Alya Zahra',
                'usia' => '12 Thn 0 Bln',
                'kelas_target' => 'Kelas 7 Reguler',
                'asal_sekolah' => 'SD Negeri Percobaan 1',
                'wali' => 'Ibu Zahra Alya',
                'kontak' => '0821-2222-0004',
                'mulai_dari' => 'Kelompok Potensi Bakat & Minat',
                'posisi_sekarang' => 'Sedang di Ruang Akademik',
                'keagamaan' => ['pos1' => null, 'pos2' => null, 'pos3' => null, 'pos4' => null, 'total_skor' => null, 'nilai' => null, 'status' => 'Belum Diuji'],
                'akademik' => ['cbt' => ['indo' => 75, 'mtk' => 70, 'ipas' => 72, 'inggris' => 75], 'pos5' => 73.0, 'nilai' => 73.0, 'status' => 'Sedang Diobservasi'],
                'bakat_minat' => ['pos6_skor' => 20, 'pos6' => 71.4, 'pos7_skor' => 18, 'pos7' => 75.0, 'nilai' => 73.2, 'status' => 'Selesai'],
                'pribadi_sosial' => ['angket_skor' => 28, 'angket_nilai' => 70.0, 'observasi_skor' => 17, 'observasi_nilai' => 70.8, 'nilai' => 70.4, 'status' => 'Selesai', 'kategori_bk' => 'Cukup Siap'],
            ],
            [
                'no' => 'SPMB-2026-SMP05',
                'name' => 'Hafizh Mubarok',
                'usia' => '12 Thn 9 Bln',
                'kelas_target' => 'Kelas 7 Tahfidz',
                'asal_sekolah' => 'SDIT Al-Hikmah',
                'wali' => 'Bpk. Mubarok Hafizh',
                'kontak' => '0821-2222-0005',
                'mulai_dari' => 'Kelompok Pribadi & Sosial',
                'posisi_sekarang' => 'Sedang di Ruang Keagamaan',
                'keagamaan' => ['pos1' => 22, 'pos2' => 21, 'pos3' => 18, 'pos4' => 21, 'total_skor' => 82, 'nilai' => 75.9, 'status' => 'Sedang Diobservasi'],
                'akademik' => ['cbt' => null, 'pos5' => null, 'nilai' => null, 'status' => 'Belum Diuji'],
                'bakat_minat' => ['pos6_skor' => null, 'pos6' => null, 'pos7_skor' => null, 'pos7' => null, 'nilai' => null, 'status' => 'Belum Diuji'],
                'pribadi_sosial' => ['angket_skor' => 25, 'angket_nilai' => 62.5, 'observasi_skor' => 15, 'observasi_nilai' => 62.5, 'nilai' => 62.5, 'status' => 'Selesai', 'kategori_bk' => 'Butuh Pendampingan Khusus'],
            ],
        ];

        // Hitung kelengkapan 4 kelompok untuk setiap siswa
        $kelompokKeys = ['keagamaan', 'akademik', 'bakat_minat', 'pribadi_sosial'];
        foreach ($siswaSMP as &$s) {
            $selesai = 0;
            foreach ($kelompokKeys as $kk) {
                if (($s[$kk]['status'] ?? '') === 'Selesai') {
                    $selesai++;
                }
            }
            $s['selesai_count'] = $selesai;
            $s['is_lengkap'] = ($selesai === 4);
        }
        unset($s);

        // Murid aktif yang dipilih
        $studentNo = $request->query('siswa_no', 'SPMB-2026-SMP01');
        $activeSiswa = collect($siswaSMP)->firstWhere('no', $studentNo) ?? $siswaSMP[0];

        return view('guru.observasi', compact('user', 'allGuruSMP', 'activeGuru', 'siswaSMP', 'activeSiswa'));

        return view('guru.observasi', compact('user', 'allGuruSMP', 'activeGuru', 'siswaSMP', 'activeSiswa'));
    }

    /**
     * Dashboard Observasi & Rekapitulasi untuk Admin (TK / SD / SMP).
     */
    public function dashboard(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
            'email' => 'admin.sd@anaksaleh.sch.id',
            'role' => 'admin_sd',
            'jenjang' => 'sd',
            'unit' => 'SD Karakter Anak Saleh',
            'role_label' => 'Admin SPMB SD',
        ]);

        $currentJenjang = $user['jenjang'] ?? 'sd';

        // Unit-specific dashboard data
        $dashboards = [
            'tk' => [
                'unit_title' => 'KB & TK Anak Saleh',
                'unit_badge' => 'Jenjang Usia Dini (3 - 6 Tahun)',
                'total_pendaftar' => 48,
                'pendaftar_sub' => 'Playgroup: 18 • TK A: 20 • TK B: 10',
                'selesai_observasi' => 42,
                'persen_selesai' => '87.5% selesai diobservasi',
                'antrean_hari_ini' => 4,
                'antrean_sub' => 'Ruang Observasi Melati & Kenanga',
                'rata_kesiapan' => '95.8%',
                'analytics_1_title' => 'Pemetaan Kesiapan Sensori & Motorik (TK)',
                'analytics_1_subtitle' => '42 Ananda Terobservasi',
                'analytics_1_items' => [
                    ['label' => 'Kemandirian & Kemampuan Sosialisasi', 'val' => '94% (40 Siswa)', 'bar' => '94%'],
                    ['label' => 'Sensori Motorik Halus (Memegang Krayon & Meronce)', 'val' => '92% (39 Siswa)', 'bar' => '92%'],
                    ['label' => 'Kesiapan Toilet Training Mandiri', 'val' => '89% (37 Siswa)', 'bar' => '89%'],
                ],
                'analytics_2_title' => 'Pengenalan Tilawati / Iqro Cilik',
                'analytics_2_subtitle' => 'Halaqah Pemula',
                'analytics_2_boxes' => [
                    ['val' => '45%', 'title' => 'Iqro / Pengenalan', 'sub' => 'Huruf Hijaiyah'],
                    ['val' => '55%', 'title' => 'Tilawati Jilid 1', 'sub' => 'Kelancaran Harakat'],
                    ['val' => '24%', 'title' => 'Hafalan 3+ Surat', 'sub' => 'Doa Harian Santun'],
                ],
                'students' => [
                    ['id' => 'SPMB-2026-TK01', 'name' => 'Maryam Qonita Zahra', 'class' => 'TK A', 'guru' => 'Ustadzah Nurul, S.Psi.', 'score' => '96%', 'char' => 'Ceria & Ramah Berbagi', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-TK02', 'name' => 'Kenzo Raditya Pratama', 'class' => 'Playgroup', 'guru' => 'Ustadzah Nurul, S.Psi.', 'score' => '82%', 'char' => 'Aktif Kinestetik (Adaptasi Toilet)', 'status' => 'Pendampingan Khusus', 'badge' => 'amber'],
                    ['id' => 'SPMB-2026-TK03', 'name' => 'Khadijah Al-Kubro', 'class' => 'TK A', 'guru' => 'Ustadzah Fatimah, S.Pd.', 'score' => '94%', 'char' => 'Mandiri & Teliti Meronce', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-TK04', 'name' => 'Zaid Ibrahim', 'class' => 'Playgroup', 'guru' => 'Ustadzah Nurul, S.Psi.', 'score' => '88%', 'char' => 'Ekspresif & Suka Berdongeng', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                ],
            ],
            'sd' => [
                'unit_title' => 'SD Karakter Anak Saleh',
                'unit_badge' => 'Jenjang Sekolah Dasar (Kelas 1)',
                'total_pendaftar' => 96,
                'pendaftar_sub' => 'Kelas 1 Reguler & Kelas Karakter Unggulan',
                'selesai_observasi' => 76,
                'persen_selesai' => '79.2% selesai diobservasi',
                'antrean_hari_ini' => 8,
                'antrean_sub' => 'Ruang Observasi Utama A & B',
                'rata_kesiapan' => '94.6%',
                'analytics_1_title' => 'Distribusi Gaya Belajar Siswa Baru (VAK)',
                'analytics_1_subtitle' => '76 Siswa Terpetakan',
                'analytics_1_items' => [
                    ['label' => 'Visual-Spasial (Belajar melalui warna & ilustrasi)', 'val' => '45% (34 Siswa)', 'bar' => '45%'],
                    ['label' => 'Kinestetik (Belajar melalui praktik & motorik)', 'val' => '32% (24 Siswa)', 'bar' => '32%'],
                    ['label' => 'Auditori (Belajar melalui dongeng & menyimak)', 'val' => '23% (18 Siswa)', 'bar' => '23%'],
                ],
                'analytics_2_title' => 'Penempatan Halaqah Al-Qur\'an (Tilawati)',
                'analytics_2_subtitle' => 'Baseline Awal Siswa Baru',
                'analytics_2_boxes' => [
                    ['val' => '20%', 'title' => 'Tilawati Jilid 1', 'sub' => 'Huruf Hijaiyah'],
                    ['val' => '56%', 'title' => 'Tilawati Jilid 2', 'sub' => 'Kelancaran Mad'],
                    ['val' => '24%', 'title' => 'Tilawati Jilid 3+', 'sub' => 'Tahfidz Juz 30'],
                ],
                'students' => [
                    ['id' => 'SPMB-2026-SD01', 'name' => 'Muhammad Fatih Al-Ayyubi', 'class' => 'SD Kelas 1', 'guru' => 'Ustadzah Fatimah, S.Pd.', 'score' => '95%', 'char' => 'Mandiri & Spasial Kreatif', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SD02', 'name' => 'Yusuf Al-Ghifari', 'class' => 'SD Kelas 1', 'guru' => 'Ustadzah Fatimah, S.Pd.', 'score' => '93%', 'char' => 'Logika Angka Cepat & Santun', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SD03', 'name' => 'Naura Salsabila', 'class' => 'SD Kelas 1', 'guru' => 'Ustadz Hasan, M.Pd.', 'score' => '96%', 'char' => 'Fokus Atensi Tinggi & Tahfidz', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SD04', 'name' => 'Rayhan Arkananta', 'class' => 'SD Kelas 1', 'guru' => 'Ustadzah Fatimah, S.Pd.', 'score' => '85%', 'char' => 'Kinestetik Aktif (Perlu Stimulasi Fokus)', 'status' => 'Pendampingan Khusus', 'badge' => 'amber'],
                ],
            ],
            'smp' => [
                'unit_title' => 'SMP Alam Anak Saleh',
                'unit_badge' => 'Jenjang Sekolah Menengah Pertama (Kelas 7)',
                'total_pendaftar' => 64,
                'pendaftar_sub' => 'Kelas 7 Putra & Putri Bilingual Tahfidz',
                'selesai_observasi' => 44,
                'persen_selesai' => '68.8% selesai diobservasi',
                'antrean_hari_ini' => 6,
                'antrean_sub' => 'Laboratorium Observasi & Interview',
                'rata_kesiapan' => '92.4%',
                'analytics_1_title' => 'Diagnostik Literasi & Logika Akademik (HOTS)',
                'analytics_1_subtitle' => '44 Siswa Teruji',
                'analytics_1_items' => [
                    ['label' => 'Penalaran Matematika & Logika Algoritmik', 'val' => '92% (40 Siswa)', 'bar' => '92%'],
                    ['label' => 'Literasi Sains Kontekstual & Lingkungan', 'val' => '88% (39 Siswa)', 'bar' => '88%'],
                    ['label' => 'English Academic & Problem Solving', 'val' => '85% (37 Siswa)', 'bar' => '85%'],
                ],
                'analytics_2_title' => 'Capaian Tahfidz Al-Qur\'an Awal',
                'analytics_2_subtitle' => 'Penempatan Halaqah SMP',
                'analytics_2_boxes' => [
                    ['val' => '52%', 'title' => 'Juz 30 (Lancar)', 'sub' => 'Mutqin & Tartil'],
                    ['val' => '32%', 'title' => 'Juz 29 - 30', 'sub' => '2 Juz Al-Qur\'an'],
                    ['val' => '16%', 'title' => '3+ Juz', 'sub' => 'Target Kelas Tahfidz'],
                ],
                'students' => [
                    ['id' => 'SPMB-2026-SMP01', 'name' => 'Bilal Ramadhan Putra', 'class' => 'SMP Kelas 7', 'guru' => 'Ustadz Hasan, M.Pd.', 'score' => '91%', 'char' => 'Leadership & Tahfidz 2 Juz', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SMP02', 'name' => 'Salma Hanifah', 'class' => 'SMP Kelas 7', 'guru' => 'Ustadz Ridwan, M.Si.', 'score' => '95%', 'char' => 'Tahfidz 3 Juz & Literasi Unggul', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SMP03', 'name' => 'Umar Al-Faruq', 'class' => 'SMP Kelas 7', 'guru' => 'Ustadz Hasan, M.Pd.', 'score' => '92%', 'char' => 'Kritis & Minat Robotik Tinggi', 'status' => 'Siap Belajar', 'badge' => 'emerald'],
                    ['id' => 'SPMB-2026-SMP04', 'name' => 'Jasmine Alya Zahra', 'class' => 'SMP Kelas 7', 'guru' => 'Ustadzah Fatimah, S.Pd.', 'score' => '86%', 'char' => 'Pendiam (Perlu Adaptasi Sosial)', 'status' => 'Pendampingan Khusus', 'badge' => 'amber'],
                ],
            ],
        ];

        $data = $dashboards[$currentJenjang] ?? $dashboards['sd'];

        return view('admin.dashboard', compact('user', 'currentJenjang', 'data'));
    }

    /**
     * Daftar Guru Pengamat per unit.
     */
    public function guruList(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
            'email' => 'admin.sd@anaksaleh.sch.id',
            'role' => 'admin_sd',
            'jenjang' => 'sd',
            'unit' => 'SD Karakter Anak Saleh',
            'role_label' => 'Admin SPMB SD',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';

        $allGuru = [
            'tk' => [
                ['nip' => 'GTK-2026-01', 'name' => 'Ustadzah Nurul Hidayah, S.Psi.', 'kelompok' => 'Kelompok Sensori & Motorik', 'ruang' => 'Ruang Melati (Pos 1)', 'jumlah_observasi' => 18, 'status' => 'Aktif'],
                ['nip' => 'GTK-2026-02', 'name' => 'Ustadzah Fatimah Az-Zahra, S.Pd.', 'kelompok' => 'Kelompok Kemandirian & Toilet Training', 'ruang' => 'Ruang Kenanga (Pos 2)', 'jumlah_observasi' => 15, 'status' => 'Aktif'],
                ['nip' => 'GTK-2026-03', 'name' => 'Ustadzah Aisyah Mardiyah, S.Pd.I.', 'kelompok' => 'Kelompok Sosial, Emosi & Kognitif', 'ruang' => 'Ruang Cempaka (Pos 3)', 'jumlah_observasi' => 9, 'status' => 'Aktif'],
            ],
            'sd' => [
                ['nip' => 'GSD-2026-01', 'name' => 'Ustadzah Fatimah Az-Zahra, S.Pd.', 'kelompok' => 'Kelompok Kesiapan Literasi & Numerasi', 'ruang' => 'Ruang Kelompok A (Pos 1)', 'jumlah_observasi' => 28, 'status' => 'Aktif'],
                ['nip' => 'GSD-2026-02', 'name' => 'Ustadz Hasan Al-Banna, M.Pd.', 'kelompok' => 'Kelompok Karakter, Adab & Tahfidz', 'ruang' => 'Ruang Kelompok B (Pos 2)', 'jumlah_observasi' => 24, 'status' => 'Aktif'],
                ['nip' => 'GSD-2026-03', 'name' => 'Ustadzah Maryam Sholihah, S.Psi.', 'kelompok' => 'Kelompok Kemandirian & Motorik', 'ruang' => 'Ruang Kelompok C (Pos 3)', 'jumlah_observasi' => 20, 'status' => 'Aktif'],
                ['nip' => 'GSD-2026-04', 'name' => 'Ustadz Bilal Ramadhan, S.Pd.', 'kelompok' => 'Kelompok Halaqah Tilawati Al-Qur\'an', 'ruang' => 'Ruang Tilawati (Pos 4)', 'jumlah_observasi' => 4, 'status' => 'Cuti'],
            ],
            'smp' => [
                ['nip' => 'GSMP-2026-01', 'name' => 'Hadi Susanto, S.Hum.', 'kelompok' => 'Kelompok Keagamaan', 'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)', 'jumlah_observasi' => 22, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-02', 'name' => 'Imarotul Muhibbah, S.Si., M.Pd. Gr.', 'kelompok' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)', 'ruang' => 'Ruang Kelompok Akademik (Pos 5 CBT)', 'jumlah_observasi' => 16, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-03', 'name' => 'Mutaahirotul Hidayati,S.Pd., S.Pd.', 'kelompok' => 'Kelompok Potensi Bakat dan Minat', 'ruang' => 'Ruang Kelompok Potensi Bakat & Minat (Pos 6–7)', 'jumlah_observasi' => 12, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-04', 'name' => 'Davies Yudisno, S.S.Gr., S.Pd.', 'kelompok' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)', 'ruang' => 'Ruang Kelompok Pribadi & Sosial', 'jumlah_observasi' => 6, 'status' => 'Aktif'],
            ],
        ];

        $guru = $allGuru[$jenjang] ?? $allGuru['sd'];

        return view('admin.guru', compact('user', 'guru', 'jenjang'));
    }

    /**
     * Daftar Calon Siswa per unit.
     */
    public function siswaList(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
            'email' => 'admin.sd@anaksaleh.sch.id',
            'role' => 'admin_sd',
            'jenjang' => 'sd',
            'unit' => 'SD Karakter Anak Saleh',
            'role_label' => 'Admin SPMB SD',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';

        $allSiswa = [
            'tk' => [
                ['no' => 'SPMB-2026-TK01', 'name' => 'Maryam Qonita Zahra', 'usia' => '5 Thn 2 Bln', 'kelas_target' => 'TK A', 'wali' => 'Bpk. Ahmad Zahra', 'kontak' => '0812-3456-7890', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-TK02', 'name' => 'Kenzo Raditya Pratama', 'usia' => '3 Thn 8 Bln', 'kelas_target' => 'Playgroup', 'wali' => 'Ibu Sinta Pratama', 'kontak' => '0813-9876-5432', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Pendampingan'],
                ['no' => 'SPMB-2026-TK03', 'name' => 'Khadijah Al-Kubro', 'usia' => '5 Thn 0 Bln', 'kelas_target' => 'TK A', 'wali' => 'Bpk. Mukhlis Kubro', 'kontak' => '0815-1111-2222', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-TK04', 'name' => 'Zaid Ibrahim', 'usia' => '3 Thn 11 Bln', 'kelas_target' => 'Playgroup', 'wali' => 'Ibu Rahma Ibrahim', 'kontak' => '0817-3333-4444', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-TK05', 'name' => 'Hana Salsabila Putri', 'usia' => '6 Thn 1 Bln', 'kelas_target' => 'TK B', 'wali' => 'Bpk. Dian Putri', 'kontak' => '0819-5555-6666', 'status_observasi' => 'Menunggu', 'rekomendasi' => '-'],
            ],
            'sd' => [
                ['no' => 'SPMB-2026-SD01', 'name' => 'Muhammad Fatih Al-Ayyubi', 'usia' => '6 Thn 5 Bln', 'kelas_target' => 'Kelas 1', 'wali' => 'Bpk. Ridho Al-Ayyubi', 'kontak' => '0812-1111-0001', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SD02', 'name' => 'Yusuf Al-Ghifari', 'usia' => '6 Thn 3 Bln', 'kelas_target' => 'Kelas 1', 'wali' => 'Ibu Halimah Ghifari', 'kontak' => '0812-1111-0002', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SD03', 'name' => 'Naura Salsabila', 'usia' => '6 Thn 7 Bln', 'kelas_target' => 'Kelas 1 Unggulan', 'wali' => 'Bpk. Faisal Salsabila', 'kontak' => '0812-1111-0003', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SD04', 'name' => 'Rayhan Arkananta', 'usia' => '6 Thn 1 Bln', 'kelas_target' => 'Kelas 1', 'wali' => 'Ibu Dewi Arkananta', 'kontak' => '0812-1111-0004', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Pendampingan'],
                ['no' => 'SPMB-2026-SD05', 'name' => 'Aisyah Fitria Noor', 'usia' => '6 Thn 0 Bln', 'kelas_target' => 'Kelas 1', 'wali' => 'Bpk. Noor Fitria', 'kontak' => '0812-1111-0005', 'status_observasi' => 'Menunggu', 'rekomendasi' => '-'],
                ['no' => 'SPMB-2026-SD06', 'name' => 'Ibnu Hafidz Rachman', 'usia' => '6 Thn 8 Bln', 'kelas_target' => 'Kelas 1 Unggulan', 'wali' => 'Ibu Rachman Hafidz', 'kontak' => '0812-1111-0006', 'status_observasi' => 'Menunggu', 'rekomendasi' => '-'],
            ],
            'smp' => [
                ['no' => 'SPMB-2026-SMP01', 'name' => 'Bilal Ramadhan Putra', 'usia' => '12 Thn 3 Bln', 'kelas_target' => 'Kelas 7 Bilingual', 'wali' => 'Bpk. Andi Ramadhan', 'kontak' => '0821-2222-0001', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SMP02', 'name' => 'Salma Hanifah', 'usia' => '12 Thn 1 Bln', 'kelas_target' => 'Kelas 7 Tahfidz', 'wali' => 'Ibu Lina Hanifah', 'kontak' => '0821-2222-0002', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SMP03', 'name' => 'Umar Al-Faruq', 'usia' => '12 Thn 6 Bln', 'kelas_target' => 'Kelas 7 Bilingual', 'wali' => 'Bpk. Zainal Al-Faruq', 'kontak' => '0821-2222-0003', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Diterima'],
                ['no' => 'SPMB-2026-SMP04', 'name' => 'Jasmine Alya Zahra', 'usia' => '12 Thn 0 Bln', 'kelas_target' => 'Kelas 7', 'wali' => 'Ibu Zahra Alya', 'kontak' => '0821-2222-0004', 'status_observasi' => 'Selesai', 'rekomendasi' => 'Pendampingan'],
                ['no' => 'SPMB-2026-SMP05', 'name' => 'Hafizh Mubarok', 'usia' => '12 Thn 9 Bln', 'kelas_target' => 'Kelas 7 Tahfidz', 'wali' => 'Bpk. Mubarok Hafizh', 'kontak' => '0821-2222-0005', 'status_observasi' => 'Menunggu', 'rekomendasi' => '-'],
            ],
        ];

        $siswa = $allSiswa[$jenjang] ?? $allSiswa['sd'];

        return view('admin.siswa', compact('user', 'siswa', 'jenjang'));
    }

    /**
     * Manajemen Indikator Observasi & Skoring per unit.
     */
    public function indikatorList(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
            'email' => 'admin.sd@anaksaleh.sch.id',
            'role' => 'admin_sd',
            'jenjang' => 'sd',
            'unit' => 'SD Karakter Anak Saleh',
            'role_label' => 'Admin SPMB SD',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';

        $allIndikator = [
            'tk' => [
                [
                    'id' => 'TK-IND-01',
                    'aspek' => 'Kemandirian',
                    'nama' => 'Dapat memakai sepatu sendiri',
                    'deskripsi' => 'Kemampuan anak memakai dan melepas alas kaki secara mandiri tanpa bantuan orang tua.',
                    'bobot' => 20,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu sama sekali, perlu bantuan penuh.',
                        2 => 'Mampu dengan panduan verbal dari guru.',
                        3 => 'Mampu mandiri dengan sedikit dorongan.',
                        4 => 'Mampu mandiri penuh dan konsisten.',
                    ],
                ],
                [
                    'id' => 'TK-IND-02',
                    'aspek' => 'Kemandirian',
                    'nama' => 'Dapat menggunakan toilet mandiri',
                    'deskripsi' => 'Kemampuan anak menggunakan kamar mandi tanpa bantuan dan membersihkan diri sendiri.',
                    'bobot' => 20,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa, masih menggunakan popok/pampers.',
                        2 => 'Bisa dengan pengawasan intensif orang dewasa.',
                        3 => 'Bisa mandiri namun kadang perlu diingatkan.',
                        4 => 'Mandiri penuh, termasuk mencuci tangan sesudahnya.',
                    ],
                ],
                [
                    'id' => 'TK-IND-03',
                    'aspek' => 'Sensori Motorik',
                    'nama' => 'Memegang krayon dengan benar',
                    'deskripsi' => 'Kemampuan motorik halus anak dalam menggenggam alat tulis menggunakan posisi tripod.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menggenggam dengan seluruh telapak tangan (palmar grasp).',
                        2 => 'Menggunakan 4 jari, belum presisi.',
                        3 => 'Mendekati tripod grasp, sudah stabil.',
                        4 => 'Tripod grasp sempurna, tekanan stabil & terkontrol.',
                    ],
                ],
                [
                    'id' => 'TK-IND-04',
                    'aspek' => 'Sensori Motorik',
                    'nama' => 'Meronce manik-manik kecil',
                    'deskripsi' => 'Koordinasi tangan dan mata dalam memasukkan manik ke dalam tali dengan ukuran kecil.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu, koordinasi tangan-mata belum berkembang.',
                        2 => 'Mampu dengan panduan tangan dari guru.',
                        3 => 'Mampu mandiri namun lambat.',
                        4 => 'Mampu cepat dan akurat, koordinasi sangat baik.',
                    ],
                ],
                [
                    'id' => 'TK-IND-05',
                    'aspek' => 'Sosial & Emosi',
                    'nama' => 'Mau berbagi mainan dengan teman',
                    'deskripsi' => 'Kemampuan anak berbagi dan bergiliran dalam penggunaan mainan saat sesi bermain bersama.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menolak berbagi, mudah tantrum saat diminta.',
                        2 => 'Mau berbagi hanya setelah bujukan panjang.',
                        3 => 'Mau berbagi dengan diingatkan sekali.',
                        4 => 'Proaktif mengajak teman berbagi tanpa diingatkan.',
                    ],
                ],
                [
                    'id' => 'TK-IND-06',
                    'aspek' => 'Kognitif Awal',
                    'nama' => 'Mengenal warna dasar (6 warna)',
                    'deskripsi' => 'Kemampuan anak menyebutkan dan menunjuk minimal 6 warna dasar dengan benar.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal warna, tidak dapat menyebutkan.',
                        2 => 'Mengenal 1–3 warna dasar.',
                        3 => 'Mengenal 4–5 warna dasar.',
                        4 => 'Mengenal 6+ warna, dapat menyebutkan & mencocokkan.',
                    ],
                ],
            ],
            'sd' => [
                [
                    'id' => 'SD-IND-01',
                    'aspek' => 'Kesiapan Literasi',
                    'nama' => 'Mengenal dan menyebutkan huruf abjad',
                    'deskripsi' => 'Kemampuan anak mengenali dan menyebutkan nama huruf A–Z baik kapital maupun kecil.',
                    'bobot' => 20,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal huruf sama sekali.',
                        2 => 'Mengenal 10–15 huruf secara acak.',
                        3 => 'Mengenal hampir semua huruf (20–24 huruf).',
                        4 => 'Mengenal dan menyebutkan seluruh 26 huruf dengan lancar.',
                    ],
                ],
                [
                    'id' => 'SD-IND-02',
                    'aspek' => 'Kesiapan Literasi',
                    'nama' => 'Membaca suku kata sederhana',
                    'deskripsi' => 'Kemampuan membaca suku kata KV (konsonan-vokal) dan kata 2 suku kata sederhana.',
                    'bobot' => 20,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa membaca suku kata.',
                        2 => 'Dapat mengeja suku kata satu per satu dengan lambat.',
                        3 => 'Dapat membaca kata 2 suku kata dengan lancar.',
                        4 => 'Dapat membaca kata majemuk dan kalimat pendek dengan lancar.',
                    ],
                ],
                [
                    'id' => 'SD-IND-03',
                    'aspek' => 'Kesiapan Numerasi',
                    'nama' => 'Berhitung 1–20 dan mengenal konsep bilangan',
                    'deskripsi' => 'Kemampuan berhitung urut dan memahami konsep kuantitas bilangan 1–20.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa berhitung urut 1–10.',
                        2 => 'Berhitung 1–10 urut namun belum paham konsep.',
                        3 => 'Berhitung 1–20 dan paham konsep "lebih banyak/sedikit".',
                        4 => 'Berhitung 1–20 lancar, dapat penjumlahan sederhana.',
                    ],
                ],
                [
                    'id' => 'SD-IND-04',
                    'aspek' => 'Karakter & Adab',
                    'nama' => 'Menjawab salam dan berbicara santun',
                    'deskripsi' => 'Kebiasaan menjawab salam, mengucapkan tolong/terima kasih, dan berbicara dengan sopan.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak menjawab salam, bicara kurang sopan.',
                        2 => 'Menjawab salam bila diingatkan.',
                        3 => 'Menjawab salam secara spontan, bicara cukup santun.',
                        4 => 'Proaktif memberi salam, bicara santun & penuh adab.',
                    ],
                ],
                [
                    'id' => 'SD-IND-05',
                    'aspek' => 'Tahfidz Al-Qur\'an',
                    'nama' => 'Hafalan surat-surat pendek Juz 30',
                    'deskripsi' => 'Kemampuan menghafal dan melafalkan surat-surat pendek dari Juz Amma dengan tartil.',
                    'bobot' => 15,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal surat pendek apapun.',
                        2 => 'Hafal 1–3 surat pendek (Al-Fatihah, Al-Ikhlas, An-Nas).',
                        3 => 'Hafal 4–7 surat pendek dengan tartil.',
                        4 => 'Hafal 8+ surat pendek, tajwid baik dan lancar.',
                    ],
                ],
                [
                    'id' => 'SD-IND-06',
                    'aspek' => 'Kemandirian & Motorik',
                    'nama' => 'Menulis nama sendiri dengan benar',
                    'deskripsi' => 'Kemampuan motorik halus untuk menulis nama lengkap dengan huruf yang terbaca.',
                    'bobot' => 15,
                    'status' => 'Draft',
                    'rubrik' => [
                        1 => 'Belum bisa menulis, hanya mencoret-coret.',
                        2 => 'Menulis sebagian huruf nama dengan bimbingan.',
                        3 => 'Menulis nama sendiri, beberapa huruf terbalik.',
                        4 => 'Menulis nama sendiri dengan jelas dan rapi.',
                    ],
                ],
            ],
            'smp' => [
                // ── Kelompok Keagamaan (4 Pos, 27 Aspek Penilaian, Skala 1–4) ───
                // Pos 1: Asesmen Ibadah Sholat (7 Aspek, Skor Maksimal = 28)
                [
                    'id' => 'SMP-AGM-P1-01',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Wudhu - Praktik',
                    'deskripsi' => 'Observasi tata cara urutan dan pelaksanaan gerakan wudhu secara langsung.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu melakukan urutan dengan benar',
                        2 => 'Beberapa tahapan belum tepat',
                        3 => 'Ada sedikit kekeliruan',
                        4 => 'Urutan dan pelaksanaan tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-02',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Wudhu - Ketentuan',
                    'deskripsi' => 'Penguasaan ketentuan, rukun, dan syarat sahnya berwudhu.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum sesuai dengan ketentuan dalam sahnya berwudhu',
                        2 => 'Ada ketentuan dan sahnya berwudhu beberapa kurang',
                        3 => 'Ketentuan dan sahnya berwudhu sedikit kurang lengkap',
                        4 => 'Ketentuan dan sahnya berwudhu lengkap',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-03',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Gerakan sholat',
                    'deskripsi' => 'Kesesuaian dan ketertiban gerakan sholat wajib dari takbiratul ihram hingga salam.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu mengikuti urutan',
                        2 => 'Beberapa gerakan perlu arahan',
                        3 => 'Ada sedikit kekeliruan',
                        4 => 'Urutan dan gerakan tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-04',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Bacaan sholat',
                    'deskripsi' => 'Kelancaran dan kefasihan lafal bacaan sholat (iftitah, ruku, sujud, tasyahud).',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu melafalkan sebagian besar bacaan',
                        2 => 'Belum lancar',
                        3 => 'Ada sedikit kesalahan',
                        4 => 'Lancar dan tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-05',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Pemahaman',
                    'deskripsi' => 'Pemahaman mengenai makna, syarat, dan rukun ibadah sholat.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami konsep dasar',
                        2 => 'Memahami sebagian',
                        3 => 'Sebagian besar tepat',
                        4 => 'Mampu menjelaskan dengan tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-06',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Ketertiban',
                    'deskripsi' => 'Ketertiban, kekhusyukan, dan kemandirian dalam mempraktikkan ibadah sholat.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan pendampingan intensif',
                        2 => 'Memerlukan beberapa arahan',
                        3 => 'Sedikit memerlukan arahan',
                        4 => 'Praktik tertib dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P1-07',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 1: Asesmen Ibadah Sholat',
                    'pos_id' => 'pos-1',
                    'pos_label' => 'Pos 1',
                    'nama' => 'Sholat Wajib',
                    'deskripsi' => 'Kemampuan menjelaskan syarat sah, waktu, dan rukun sholat wajib.',
                    'bobot' => 1.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak menjelaskan syarat sah sholat dengan lengkap',
                        2 => 'Penjelasan syarat sah sholat beberapa tidak lengkap',
                        3 => 'Penjelasan syarat sah sholat sedikit kurang lengkap',
                        4 => 'Penjelasan syarat sah sholat lengkap',
                    ],
                ],

                // Pos 2: Asesmen Ibadah Mengaji (7 Aspek, Skor Maksimal = 28)
                [
                    'id' => 'SMP-AGM-P2-01',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Huruf',
                    'deskripsi' => 'Pengenalan dan pengucapan huruf-huruf hijaiyah secara tepat.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenali sebagian besar',
                        2 => 'Beberapa kesalahan',
                        3 => 'Sedikit kesalahan',
                        4 => 'Seluruhnya tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-02',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Harakat',
                    'deskripsi' => 'Ketepatan membaca harakat (fathah, kasrah, dhammah, sukun, tanwin, tasydid).',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenali sebagian besar',
                        2 => 'Beberapa kesalahan',
                        3 => 'Sedikit kesalahan',
                        4 => 'Seluruhnya tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-03',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Huruf Bersambung',
                    'deskripsi' => 'Kemampuan mengidentifikasi dan melafalkan perubahan bentuk huruf hijaiyah saat bersambung.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan bimbingan dasar',
                        2 => 'Beberapa huruf belum tepat',
                        3 => 'Sedikit kekeliruan',
                        4 => 'Tepat dan konsisten',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-04',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Makharijul huruf',
                    'deskripsi' => 'Ketepatan tempat keluarnya bunyi huruf dari tenggorokan, lidah, atau bibir.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan bimbingan dasar',
                        2 => 'Beberapa huruf belum tepat',
                        3 => 'Sedikit kekeliruan',
                        4 => 'Tepat dan konsisten',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-05',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Tajwid',
                    'deskripsi' => 'Penerapan hukum tajwid (nun sukun/tanwin, mim sukun, idgham, ikhfa, dll).',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami penerapan',
                        2 => 'Beberapa hukum belum dikuasai',
                        3 => 'Ada sedikit kekeliruan',
                        4 => 'Penerapan tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-06',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Mad',
                    'deskripsi' => 'Ketepatan penerapan panjang pendek bacaan (1 alif, 2 alif, 3 alif).',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami panjang pendek',
                        2 => 'Sering memerlukan koreksi',
                        3 => 'Sedikit kesalahan',
                        4 => 'Panjang pendek tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P2-07',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 2: Asesmen Ibadah Mengaji',
                    'pos_id' => 'pos-2',
                    'pos_label' => 'Pos 2',
                    'nama' => 'Kelancaran',
                    'deskripsi' => 'Kelancaran alur tilawah ayat tanpa terbata-bata atau sering berhenti berulang kali.',
                    'bobot' => 1.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum dapat membaca secara berurutan',
                        2 => 'Sering berhenti',
                        3 => 'Sedikit terbata-bata',
                        4 => 'Lancar dan mandiri',
                    ],
                ],

                // Pos 3: Asesmen Pelafalan Doa-doa Harian (6 Aspek, Skor Maksimal = 24)
                [
                    'id' => 'SMP-AGM-P3-01',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Hafalan',
                    'deskripsi' => 'Penguasaan materi doa-doa harian yang diujikan.',
                    'bobot' => 1.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menguasai sedikit',
                        2 => 'Menguasai sebagian',
                        3 => 'Menguasai sebagian besar',
                        4 => 'Menguasai seluruh doa yang diuji',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P3-02',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Pelafalan',
                    'deskripsi' => 'Kejelasan lafaz dan artikulasi pengucapan kalimat doa berbahasa Arab.',
                    'bobot' => 1.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu melafalkan dengan tepat',
                        2 => 'Beberapa kekeliruan',
                        3 => 'Sedikit kekeliruan',
                        4 => 'Tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P3-03',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Kelengkapan',
                    'deskripsi' => 'Keutuhan kalimat doa dari awal hingga akhir tanpa ada penggalan yang hilang.',
                    'bobot' => 1.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Sebagian besar belum dikuasai',
                        2 => 'Beberapa bagian terlewat',
                        3 => 'Ada bagian kecil terlewat',
                        4 => 'Lengkap',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P3-04',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Kelancaran',
                    'deskripsi' => 'Kelancaran penyampaian doa tanpa jeda panjang atau bantuan pengingat.',
                    'bobot' => 1.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan bantuan intensif',
                        2 => 'Sering memerlukan petunjuk',
                        3 => 'Sedikit jeda',
                        4 => 'Lancar tanpa bantuan',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P3-05',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Pemahaman',
                    'deskripsi' => 'Pemahaman terhadap arti, makna, dan konteks penggunaan doa dalam keseharian.',
                    'bobot' => 1.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami makna dasar',
                        2 => 'Memahami sebagian',
                        3 => 'Memahami sebagian besar',
                        4 => 'Memahami makna dan konteks',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P3-06',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 3: Asesmen Pelafalan Doa-doa Harian',
                    'pos_id' => 'pos-3',
                    'pos_label' => 'Pos 3',
                    'nama' => 'Pengetahuan',
                    'deskripsi' => 'Pengetahuan mengenai ketepatan waktu pengamalan dan adab berdoa.',
                    'bobot' => 1.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak mengetahui dengan tepat waktu dan doanya',
                        2 => 'Tidak banyak mengetahui dengan tepat waktu dan doanya',
                        3 => 'Mengetahui sedikit yang kurang tepat untuk waktu dan doanya',
                        4 => 'Mengetahui dengan tepat waktu dan doanya',
                    ],
                ],

                // Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur'an (7 Aspek, Skor Maksimal = 28)
                [
                    'id' => 'SMP-AGM-P4-01',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Kelancaran',
                    'deskripsi' => 'Kelancaran hafalan surat-surat Al-Qur\'an Juz 30 tanpa terhenti berulang kali.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu menyelesaikan',
                        2 => 'Beberapa kali terhenti',
                        3 => 'Sedikit jeda',
                        4 => 'Lancar tanpa jeda berarti',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-02',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Ketepatan ayat',
                    'deskripsi' => 'Ketepatan urutan ayat, kalimat, dan harakat surat yang dilafalkan secara mutqin.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menguasai urutan',
                        2 => 'Beberapa ayat tertukar',
                        3 => 'Ada sedikit kekeliruan',
                        4 => 'Urutan dan lafaz tepat',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-03',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Hafalan',
                    'deskripsi' => 'Kekuatan hafalan keseluruhan ayat pada surat yang diujikan.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal ayat dan urutannya',
                        2 => 'Hafal sedikit ayat dan butuh stimulus',
                        3 => 'Hafal beberapa ayat dengan baik',
                        4 => 'Hafal semua ayat dengan baik',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-04',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Pelafalan',
                    'deskripsi' => 'Kejelasan artikulasi dan fashahah lafadz ayat Al-Qur\'an.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Pelafalan tidak tepat dan kurang jelas',
                        2 => 'Pelafalan banyak yang kurang tepat dan jelas',
                        3 => 'Pelafalan sedikit kurang tepat dan jelas',
                        4 => 'Pelafalan sangat tepat dan jelas',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-05',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Tajwid',
                    'deskripsi' => 'Penerapan kaidah tajwid secara konsisten pada saat melafalkan hafalan surat.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan penguatan dasar',
                        2 => 'Beberapa kesalahan',
                        3 => 'Sedikit kesalahan',
                        4 => 'Tepat dan konsisten',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-06',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Sambung ayat',
                    'deskripsi' => 'Kemampuan merespons dan melanjutkan potongan ayat yang dibacakan oleh pengamat.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu melanjutkan',
                        2 => 'Memerlukan beberapa petunjuk',
                        3 => 'Sedikit petunjuk',
                        4 => 'Tepat tanpa bantuan',
                    ],
                ],
                [
                    'id' => 'SMP-AGM-P4-07',
                    'aspek' => 'Kelompok Keagamaan',
                    'pos' => 'Pos 4: Asesmen Pelafalan Surat-surat dalam Al-Qur\'an',
                    'pos_id' => 'pos-4',
                    'pos_label' => 'Pos 4',
                    'nama' => 'Kemandirian',
                    'deskripsi' => 'Kemandirian dan kepercayaan diri saat menyetorkan hafalan di hadapan pengamat.',
                    'bobot' => 1.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan',
                        2 => 'Sering dibantu',
                        3 => 'Sedikit bantuan',
                        4 => 'Mandiri',
                    ],
                ],

                // ── Kelompok Akademik (Numerasi, Literasi, Science, and English) ─────────────
                // Pos 5: Asesmen Akademik Dasar - CBT (4 Mata Pelajaran, Skala 0–100)
                [
                    'id' => 'SMP-AKD-P5-01',
                    'aspek' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'pos' => 'Pos 5: Asesmen Akademik Dasar - CBT',
                    'pos_id' => 'pos-5',
                    'pos_label' => 'Pos 5',
                    'tipe' => 'cbt_score',
                    'nama' => 'Literasi (Bahasa Indonesia)',
                    'deskripsi' => 'Membaca pemahaman, menemukan ide pokok, menyimpulkan teks, memahami informasi tersurat dan tersirat, menyusun kalimat dan paragraf.',
                    'bobot' => 7.0,
                    'skor_maks' => 100,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Skor CBT 0 – 59 (Perlu Pendampingan Khusus)',
                        2 => 'Skor CBT 60 – 75 (Kategori Berkembang / Cukup)',
                        3 => 'Skor CBT 76 – 85 (Kategori Baik / Tuntas)',
                        4 => 'Skor CBT 86 – 100 (Kategori Sangat Baik / Mahir)',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P5-02',
                    'aspek' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'pos' => 'Pos 5: Asesmen Akademik Dasar - CBT',
                    'pos_id' => 'pos-5',
                    'pos_label' => 'Pos 5',
                    'tipe' => 'cbt_score',
                    'nama' => 'Numerasi (Matematika)',
                    'deskripsi' => 'Operasi bilangan bulat, pecahan, desimal, persen, perbandingan, pengukuran, geometri, dan pengolahan data.',
                    'bobot' => 7.0,
                    'skor_maks' => 100,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Skor CBT 0 – 59 (Perlu Pendampingan Khusus)',
                        2 => 'Skor CBT 60 – 75 (Kategori Berkembang / Cukup)',
                        3 => 'Skor CBT 76 – 85 (Kategori Baik / Tuntas)',
                        4 => 'Skor CBT 86 – 100 (Kategori Sangat Baik / Mahir)',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P5-03',
                    'aspek' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'pos' => 'Pos 5: Asesmen Akademik Dasar - CBT',
                    'pos_id' => 'pos-5',
                    'pos_label' => 'Pos 5',
                    'tipe' => 'cbt_score',
                    'nama' => 'Science (IPAS)',
                    'deskripsi' => 'Makhluk hidup, organ tubuh, ekosistem, energi, materi, lingkungan, bumi dan antariksa, serta fenomena sosial.',
                    'bobot' => 7.0,
                    'skor_maks' => 100,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Skor CBT 0 – 59 (Perlu Pendampingan Khusus)',
                        2 => 'Skor CBT 60 – 75 (Kategori Berkembang / Cukup)',
                        3 => 'Skor CBT 76 – 85 (Kategori Baik / Tuntas)',
                        4 => 'Skor CBT 86 – 100 (Kategori Sangat Baik / Mahir)',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P5-04',
                    'aspek' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'pos' => 'Pos 5: Asesmen Akademik Dasar - CBT',
                    'pos_id' => 'pos-5',
                    'pos_label' => 'Pos 5',
                    'tipe' => 'cbt_score',
                    'nama' => 'English (Bahasa Inggris)',
                    'deskripsi' => 'Vocabulary, reading comprehension, basic grammar, and sentence construction.',
                    'bobot' => 7.0,
                    'skor_maks' => 100,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Skor CBT 0 – 59 (Perlu Pendampingan Khusus)',
                        2 => 'Skor CBT 60 – 75 (Kategori Berkembang / Cukup)',
                        3 => 'Skor CBT 76 – 85 (Kategori Baik / Tuntas)',
                        4 => 'Skor CBT 86 – 100 (Kategori Sangat Baik / Mahir)',
                    ],
                ],

                // ── Kelompok Potensi Bakat dan Minat (Pos 6 & Pos 7) ─────────────────────
                // Pos 6: Asesmen Motorik Halus dan Kreativitas (7 Indikator, Skor Maksimal = 28)
                [
                    'id' => 'SMP-AKD-P6-01',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Koordinasi mata dan tangan',
                    'deskripsi' => 'Sinkronisasi visual dan motorik dalam memanipulasi alat, memotong, atau membentuk benda.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-02',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Ketepatan membuat bentuk',
                    'deskripsi' => 'Akurasi dan proporsi dalam membuat wujud atau pola visual sesuai instruksi tugas.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-03',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Kontrol gerakan tangan',
                    'deskripsi' => 'Kestabilan, keluwesan jari-jemari, dan regulasi tekanan saat bekerja dengan detail halus.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-04',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Ketelitian mengikuti instruksi',
                    'deskripsi' => 'Kepatuhan pada tahapan prosedur teknis dan spesifikasi tugas prakarya/kriya.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-05',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Kemampuan memahami ruang dan bentuk',
                    'deskripsi' => 'Spasial visual, kesadaran dimensi (2D/3D), dan penempatan elemen karya secara seimbang.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-06',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Kemampuan menyusun objek',
                    'deskripsi' => 'Keterampilan merangkai komponen, menata bagian per bagian hingga menjadi kesatuan utuh.',
                    'bobot' => 1.1,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P6-07',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 6: Asesmen Motorik Halus & Kreativitas',
                    'pos_id' => 'pos-6',
                    'pos_label' => 'Pos 6',
                    'nama' => 'Kerapian dan fungsi hasil karya',
                    'deskripsi' => 'Finishing touch, kebersihan, kekokohan struktur, dan kesesuaian fungsi dari produk karya.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                        2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                        3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                        4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                    ],
                ],

                // Pos 7: Asesmen Kreativitas dan Inovasi (6 Aspek, Skor Maksimal = 24)
                [
                    'id' => 'SMP-AKD-P7-01',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Fluency',
                    'deskripsi' => 'Kelancaran menghasilkan sejumlah alternatif ide dan gagasan yang relevan dengan topik.',
                    'bobot' => 1.3,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menghasilkan ide yang relevan',
                        2 => 'Menghasilkan sedikit ide',
                        3 => 'Menghasilkan beberapa ide',
                        4 => 'Menghasilkan banyak ide relevan',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P7-02',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Flexibility',
                    'deskripsi' => 'Keluwesan sudut pandang, keberagaman pendekatan, dan cara berpikir yang tidak kaku.',
                    'bobot' => 1.3,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menunjukkan variasi',
                        2 => 'Ide masih terbatas pada satu pendekatan',
                        3 => 'Ide memiliki beberapa variasi',
                        4 => 'Ide berasal dari beragam pendekatan',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P7-03',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Originality',
                    'deskripsi' => 'Keaslian gagasan, kebaruan (novelty), dan ide yang tidak klise atau berbeda dari kebiasaan umum.',
                    'bobot' => 1.3,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu mengembangkan ide',
                        2 => 'Ide masih umum',
                        3 => 'Ada pengembangan dari ide umum',
                        4 => 'Ide menunjukkan unsur kebaruan yang jelas',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P7-04',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Elaboration',
                    'deskripsi' => 'Kemampuan merinci rancangan, memperkaya detail teknis, dan memperjelas implementasi ide.',
                    'bobot' => 1.3,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengembangkan rancangan',
                        2 => 'Rancangan masih sederhana',
                        3 => 'Rancangan cukup terperinci',
                        4 => 'Rancangan sangat terperinci',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P7-05',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Problem solving',
                    'deskripsi' => 'Ketepatan solusi yang diajukan terhadap masalah yang dihadapi secara logis dan aplikatif.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menemukan solusi yang sesuai',
                        2 => 'Solusi memerlukan pengembangan',
                        3 => 'Solusi relevan dan cukup logis',
                        4 => 'Solusi relevan, logis, dan dapat diterapkan',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-P7-06',
                    'aspek' => 'Kelompok Potensi Bakat dan Minat',
                    'pos' => 'Pos 7: Asesmen Kreativitas & Inovasi',
                    'pos_id' => 'pos-7',
                    'pos_label' => 'Pos 7',
                    'nama' => 'Communication',
                    'deskripsi' => 'Kemampuan mempresentasikan dan mengomunikasikan gagasan kreatif dengan runut dan persuasif.',
                    'bobot' => 1.4,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan bantuan untuk menjelaskan',
                        2 => 'Penjelasan masih terbatas',
                        3 => 'Penjelasan cukup jelas',
                        4 => 'Menjelaskan ide dengan runtut dan alasan kuat',
                    ],
                ],

                // ── Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok) ──
                // Rubrik Observasi & Wawancara (6 Aspek, Skor Maksimal = 24, Bobot 16%)
                [
                    'id' => 'SMP-PSI-01',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Kemandirian',
                    'deskripsi' => 'Melakukan tugas secara mandiri dan mengelola diri tanpa bergantung berlebihan.',
                    'bobot' => 2.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Memerlukan pendampingan intensif',
                        2 => 'Beberapa kali memerlukan bantuan',
                        3 => 'Sedikit arahan',
                        4 => 'Melakukan tugas secara mandiri',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-02',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Komunikasi',
                    'deskripsi' => 'Menyampaikan gagasan, berpendapat, dan merespons lawan bicara secara efektif.',
                    'bobot' => 2.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menunjukkan respons yang memadai',
                        2 => 'Masih terbatas',
                        3 => 'Cukup jelas',
                        4 => 'Menyampaikan gagasan dengan jelas',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-03',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Kerja sama',
                    'deskripsi' => 'Aktif berpartisipasi dan menghargai anggota lain dalam aktivitas kelompok.',
                    'bobot' => 2.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum terlibat secara konsisten',
                        2 => 'Terlibat setelah diarahkan',
                        3 => 'Bekerja sama dengan baik',
                        4 => 'Aktif dan menghargai anggota lain',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-04',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Regulasi emosi',
                    'deskripsi' => 'Mampu mengenali dan mengelola respons emosional dalam berbagai situasi pengamatan.',
                    'bobot' => 2.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan lebih lanjut',
                        2 => 'Masih memerlukan arahan',
                        3 => 'Umumnya mampu',
                        4 => 'Mampu mengenali dan mengelola respons',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-05',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Tanggung jawab',
                    'deskripsi' => 'Konsistensi menyelesaikan tugas yang diberikan hingga tuntas.',
                    'bobot' => 2.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menunjukkan penyelesaian tugas',
                        2 => 'Memerlukan pengingat',
                        3 => 'Sebagian besar selesai',
                        4 => 'Konsisten menyelesaikan tugas',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-06',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'nama' => 'Empati',
                    'deskripsi' => 'Menunjukkan kepedulian secara aktif terhadap sesama dan lingkungan sekitar.',
                    'bobot' => 2.6,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum tampak dalam situasi pengamatan',
                        2 => 'Memerlukan pengingat',
                        3 => 'Menunjukkan kepedulian',
                        4 => 'Menunjukkan kepedulian secara aktif',
                    ],
                ],
                // ── Sub-instrumen Angket Refleksi Diri Calon Murid (10 Pernyataan, Diagnostik) ───
                [
                    'id' => 'SMP-PSI-A01',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya mengetahui hal yang menjadi kelebihan saya',
                    'deskripsi' => 'Kesadaran diri terhadap kekuatan dan potensi yang dimiliki.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A02',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya berusaha menyelesaikan tugas yang diberikan',
                    'deskripsi' => 'Komitmen dan ketekunan dalam menuntaskan tanggung jawab akademik.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A03',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya berani bertanya ketika belum memahami pelajaran',
                    'deskripsi' => 'Keberanian mengungkapkan ketidakpahaman demi kemajuan belajar.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A04',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya dapat menerima masukan dari orang lain',
                    'deskripsi' => 'Keterbukaan menerima kritik dan saran secara positif.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A05',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya berusaha mengelola waktu belajar',
                    'deskripsi' => 'Kemampuan merencanakan dan menggunakan waktu belajar secara efektif.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A06',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya menghargai perbedaan pendapat',
                    'deskripsi' => 'Sikap toleran terhadap sudut pandang dan pendapat yang berbeda.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A07',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya bersedia membantu teman yang membutuhkan',
                    'deskripsi' => 'Kepedulian sosial dan kesiapan memberikan bantuan kepada teman.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A08',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya berusaha bertanggung jawab atas kesalahan saya',
                    'deskripsi' => 'Kesediaan mengakui dan memperbaiki kesalahan yang telah dilakukan.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A09',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya bersedia mencoba kegiatan baru',
                    'deskripsi' => 'Keterbukaan dan antusiasme dalam menghadapi pengalaman baru.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-A10',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'sub_instrumen' => 'angket',
                    'nama' => 'Saya menggunakan perangkat digital dengan memperhatikan tujuan penggunaannya',
                    'deskripsi' => 'Kesadaran dan kedisiplinan dalam pemanfaatan teknologi digital secara bijak.',
                    'bobot' => 0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum Sesuai',
                        2 => 'Kadang-kadang',
                        3 => 'Sesuai',
                        4 => 'Sangat Sesuai',
                    ],
                ],
            ],
        ];

        $indikator = $allIndikator[$jenjang] ?? $allIndikator['sd'];

        // Group by aspek
        $grouped = collect($indikator)->groupBy('aspek');

        // Database Peserta & Rekapitulasi Terpadu SMP (4 Kelompok)
        $siswaData = [
            'smp' => [
                [
                    'no' => 'SPMB-2026-SMP01',
                    'name' => 'Bilal Ramadhan Putra',
                    'kelas_target' => 'Kelas 7 Bilingual',
                    'keagamaan' => [
                        'pos1' => 26,
                        'pos2' => 25,
                        'pos3' => 23,
                        'pos4' => 26,
                        'nilai' => 92.7,
                    ],
                    'akademik' => [
                        'cbt' => ['indo' => 92, 'mtk' => 95, 'ipas' => 90, 'inggris' => 95],
                        'pos5' => 93.0,
                        'nilai' => 93.0,
                    ],
                    'bakat_minat' => [
                        'pos6_skor' => 26,
                        'pos6' => 92.9,
                        'pos7_skor' => 22,
                        'pos7' => 91.7,
                        'nilai' => 92.3,
                    ],
                    'pribadi_sosial' => [
                        'skor' => 15,
                        'nilai' => 93.8,
                    ],
                    'psikologi' => [
                        'angket_skor' => 37,
                        'angket_nilai' => 92.5,
                        'observasi_skor' => 22,
                        'observasi_nilai' => 91.7,
                        'nilai' => 92.1,
                    ],
                    'nilai_terpadu' => 93.0,
                    'kategori' => 'Sangat Baik',
                    'rekomendasi' => 'Program Pengayaan / Kelas Unggulan',
                ],
                [
                    'no' => 'SPMB-2026-SMP02',
                    'name' => 'Salma Hanifah',
                    'kelas_target' => 'Kelas 7 Tahfidz',
                    'keagamaan' => [
                        'pos1' => 27,
                        'pos2' => 28,
                        'pos3' => 24,
                        'pos4' => 27,
                        'nilai' => 97.3,
                    ],
                    'akademik' => [
                        'cbt' => ['indo' => 94, 'mtk' => 88, 'ipas' => 90, 'inggris' => 92],
                        'pos5' => 91.0,
                        'nilai' => 91.0,
                    ],
                    'bakat_minat' => [
                        'pos6_skor' => 25,
                        'pos6' => 89.3,
                        'pos7_skor' => 23,
                        'pos7' => 95.8,
                        'nilai' => 92.6,
                    ],
                    'pribadi_sosial' => [
                        'skor' => 16,
                        'nilai' => 100.0,
                    ],
                    'psikologi' => [
                        'angket_skor' => 38,
                        'angket_nilai' => 95.0,
                        'observasi_skor' => 23,
                        'observasi_nilai' => 95.8,
                        'nilai' => 95.4,
                    ],
                    'nilai_terpadu' => 95.2,
                    'kategori' => 'Sangat Baik',
                    'rekomendasi' => 'Program Pengayaan / Kelas Unggulan',
                ],
                [
                    'no' => 'SPMB-2026-SMP03',
                    'name' => 'Umar Al-Faruq',
                    'kelas_target' => 'Kelas 7 Bilingual',
                    'keagamaan' => [
                        'pos1' => 23,
                        'pos2' => 22,
                        'pos3' => 20,
                        'pos4' => 23,
                        'nilai' => 81.1,
                    ],
                    'akademik' => [
                        'cbt' => ['indo' => 85, 'mtk' => 90, 'ipas' => 86, 'inggris' => 83],
                        'pos5' => 86.0,
                        'nilai' => 86.0,
                    ],
                    'bakat_minat' => [
                        'pos6_skor' => 23,
                        'pos6' => 82.1,
                        'pos7_skor' => 20,
                        'pos7' => 83.3,
                        'nilai' => 82.7,
                    ],
                    'pribadi_sosial' => [
                        'skor' => 13,
                        'nilai' => 81.3,
                    ],
                    'psikologi' => [
                        'angket_skor' => 31,
                        'angket_nilai' => 77.5,
                        'observasi_skor' => 19,
                        'observasi_nilai' => 79.2,
                        'nilai' => 78.4,
                    ],
                    'nilai_terpadu' => 82.8,
                    'kategori' => 'Baik',
                    'rekomendasi' => 'Pembelajaran Reguler',
                ],
                [
                    'no' => 'SPMB-2026-SMP04',
                    'name' => 'Jasmine Alya Zahra',
                    'kelas_target' => 'Kelas 7',
                    'keagamaan' => [
                        'pos1' => 20,
                        'pos2' => 19,
                        'pos3' => 17,
                        'pos4' => 19,
                        'nilai' => 69.3,
                    ],
                    'akademik' => [
                        'cbt' => ['indo' => 76, 'mtk' => 68, 'ipas' => 72, 'inggris' => 72],
                        'pos5' => 72.0,
                        'nilai' => 72.0,
                    ],
                    'bakat_minat' => [
                        'pos6_skor' => 20,
                        'pos6' => 71.4,
                        'pos7_skor' => 17,
                        'pos7' => 70.8,
                        'nilai' => 71.1,
                    ],
                    'pribadi_sosial' => [
                        'skor' => 11,
                        'nilai' => 68.8,
                    ],
                    'psikologi' => [
                        'angket_skor' => 27,
                        'angket_nilai' => 67.5,
                        'observasi_skor' => 16,
                        'observasi_nilai' => 66.7,
                        'nilai' => 67.1,
                    ],
                    'nilai_terpadu' => 70.3,
                    'kategori' => 'Berkembang',
                    'rekomendasi' => 'Pendampingan Belajar',
                ],
                [
                    'no' => 'SPMB-2026-SMP05',
                    'name' => 'Hafizh Mubarok',
                    'kelas_target' => 'Kelas 7 Tahfidz',
                    'keagamaan' => [
                        'pos1' => 22,
                        'pos2' => 21,
                        'pos3' => 18,
                        'pos4' => 21,
                        'nilai' => 76.2,
                    ],
                    'akademik' => [
                        'cbt' => ['indo' => 60, 'mtk' => 52, 'ipas' => 55, 'inggris' => 57],
                        'pos5' => 56.0,
                        'nilai' => 56.0,
                    ],
                    'bakat_minat' => [
                        'pos6_skor' => 16,
                        'pos6' => 57.1,
                        'pos7_skor' => 14,
                        'pos7' => 58.3,
                        'nilai' => 57.7,
                    ],
                    'pribadi_sosial' => [
                        'skor' => 10,
                        'nilai' => 62.5,
                    ],
                    'psikologi' => [
                        'angket_skor' => 25,
                        'angket_nilai' => 62.5,
                        'observasi_skor' => 15,
                        'observasi_nilai' => 62.5,
                        'nilai' => 62.5,
                    ],
                    'nilai_terpadu' => 63.1,
                    'kategori' => 'Berkembang',
                    'rekomendasi' => 'Pendampingan Belajar',
                ],
            ],
        ];

        $siswa = $siswaData[$jenjang] ?? $siswaData['smp'];

        return view('admin.indikator', compact('user', 'indikator', 'grouped', 'jenjang', 'siswa'));
    }

    /**
     * Logout and redirect to login page.
     */
    public function logout(): RedirectResponse
    {
        session()->forget('auth_user');

        return redirect()->route('login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}
