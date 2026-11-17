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

        // Default: Guru / Pengamat
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
     * Halaman Observasi Siswa Baru untuk Guru.
     */
    public function observasi(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadzah Fatimah Azzahra, S.Pd.',
            'email' => 'guru@anaksaleh.sch.id',
            'role' => 'guru',
            'jenjang' => 'all',
            'unit' => 'Perguruan Anak Saleh',
            'role_label' => 'Tim Pengamat & Psikolog Anak',
        ]);

        return view('guru.observasi', compact('user'));
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
                ['nip' => 'GSMP-2026-01', 'name' => 'Ustadz Hasan Al-Banna, M.Pd.', 'kelompok' => 'Kelompok Keagamaan (Tahfidz, Tilawah, Ibadah, Adab)', 'ruang' => 'Ruang Kelompok Keagamaan', 'jumlah_observasi' => 22, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-02', 'name' => 'Ustadz Ridwan Al-Hafidz, M.Si.', 'kelompok' => 'Kelompok Akademik (Numerasi, Literasi, Science, English)', 'ruang' => 'Ruang Kelompok Akademik', 'jumlah_observasi' => 16, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-03', 'name' => 'Ustadzah Fatimah Az-Zahra, S.Pd.', 'kelompok' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation)', 'ruang' => 'Ruang Kelompok Pribadi & Sosial', 'jumlah_observasi' => 6, 'status' => 'Aktif'],
                ['nip' => 'GSMP-2026-04', 'name' => 'Ustadz Bilal Ramadhan, S.Pd.', 'kelompok' => 'Kelompok Potensi Bakat Minat (Presentasi, Digital, Seni)', 'ruang' => 'Ruang Kelompok Potensi Bakat Minat', 'jumlah_observasi' => 12, 'status' => 'Aktif'],
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.7,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.7,
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
                    'bobot' => 2.1,
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
                    'bobot' => 2.1,
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
                    'bobot' => 2.1,
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
                    'bobot' => 2.1,
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
                    'bobot' => 2.1,
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
                    'bobot' => 2.0,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.8,
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
                    'bobot' => 1.7,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Membutuhkan pendampingan',
                        2 => 'Sering dibantu',
                        3 => 'Sedikit bantuan',
                        4 => 'Mandiri',
                    ],
                ],

                // ── Kelompok Akademik ───────────────────────────────────────
                [
                    'id' => 'SMP-AKD-01',
                    'aspek' => 'Kelompok Akademik',
                    'nama' => 'Numerasi — Penalaran matematika kontekstual',
                    'deskripsi' => 'Kemampuan menyelesaikan soal matematika setara kelas 6 SD termasuk pecahan, rasio, dan aljabar dasar.',
                    'bobot' => 10,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak dapat mengerjakan soal setara kelas 4 SD.',
                        2 => 'Mengerjakan soal kelas 5 SD dengan banyak kesalahan.',
                        3 => 'Mengerjakan soal kelas 6 SD dengan benar >60%, sistematis.',
                        4 => 'Mengerjakan soal kelas 6 SD dengan benar >90%, efisien.',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-02',
                    'aspek' => 'Kelompok Akademik',
                    'nama' => 'Literasi — Pemahaman bacaan & menulis argumen',
                    'deskripsi' => 'Kemampuan memahami teks 300–400 kata, mengidentifikasi ide pokok, dan menuliskan pendapat dengan alasan.',
                    'bobot' => 10,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak dapat mengidentifikasi ide pokok teks.',
                        2 => 'Mengidentifikasi ide pokok dengan panduan pertanyaan.',
                        3 => 'Merangkum dan memberikan pendapat dengan alasan cukup.',
                        4 => 'Analisis kritis, argumen terstruktur dengan bukti dari teks.',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-03',
                    'aspek' => 'Kelompok Akademik',
                    'nama' => 'Science — Literasi sains & penalaran ilmiah',
                    'deskripsi' => 'Kemampuan membaca fenomena alam, menghubungkan konsep IPA sederhana, dan menarik kesimpulan ilmiah.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa menghubungkan fenomena dengan konsep IPA.',
                        2 => 'Mengenali konsep IPA namun penalaran masih dangkal.',
                        3 => 'Menghubungkan konsep IPA dengan fenomena, menarik kesimpulan.',
                        4 => 'Menganalisis secara kritis, mengajukan hipotesis sederhana.',
                    ],
                ],
                [
                    'id' => 'SMP-AKD-04',
                    'aspek' => 'Kelompok Akademik',
                    'nama' => 'English — Daily conversation & reading comprehension',
                    'deskripsi' => 'Kemampuan berkomunikasi dalam bahasa Inggris sehari-hari dan memahami teks pendek berbahasa Inggris.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa merespons dalam bahasa Inggris.',
                        2 => 'Merespons dengan kata atau frasa pendek, banyak jeda.',
                        3 => 'Berkomunikasi dengan kalimat lengkap, ada kesalahan minor.',
                        4 => 'Percakapan lancar, kosakata kaya, memahami teks pendek.',
                    ],
                ],

                // ── Kelompok Potensi Bakat Minat ───────────────────────────────────
                [
                    'id' => 'SMP-SKL-01',
                    'aspek' => 'Kelompok Potensi Bakat Minat',
                    'nama' => 'Keterampilan presentasi & public speaking',
                    'deskripsi' => 'Kemampuan menyampaikan gagasan secara lisan di depan kelompok dengan percaya diri dan terstruktur.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak berani berbicara di depan kelompok, diam.',
                        2 => 'Berbicara singkat, suara pelan, kurang terstruktur.',
                        3 => 'Menyampaikan gagasan dengan cukup jelas dan terstruktur.',
                        4 => 'Presentasi percaya diri, struktur jelas, menarik perhatian.',
                    ],
                ],
                [
                    'id' => 'SMP-SKL-02',
                    'aspek' => 'Kelompok Potensi Bakat Minat',
                    'nama' => 'Keterampilan digital & literasi teknologi',
                    'deskripsi' => 'Kemampuan menggunakan perangkat digital untuk belajar, mengetik, dan mencari informasi secara bertanggung jawab.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum terbiasa menggunakan perangkat digital.',
                        2 => 'Dapat menggunakan perangkat dasar dengan bimbingan.',
                        3 => 'Mandiri menggunakan perangkat untuk tugas belajar.',
                        4 => 'Memanfaatkan teknologi secara kreatif dan bertanggung jawab.',
                    ],
                ],
                [
                    'id' => 'SMP-SKL-03',
                    'aspek' => 'Kelompok Potensi Bakat Minat',
                    'nama' => 'Keterampilan seni, olahraga, atau prakarya',
                    'deskripsi' => 'Minat dan kemampuan dalam salah satu bidang seni (kaligrafi, nasyid), olahraga, atau prakarya islami.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menunjukkan minat atau kemampuan khusus.',
                        2 => 'Menunjukkan minat, keterampilan masih berkembang.',
                        3 => 'Memiliki keterampilan yang baik di salah satu bidang.',
                        4 => 'Keterampilan menonjol, aktif mengembangkan dan berbagi.',
                    ],
                ],

                // ── Kelompok Perkembangan Pribadi & Sosial ──────────────────
                [
                    'id' => 'SMP-PSI-01',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial',
                    'nama' => 'Daily conversation — Komunikasi sosial efektif',
                    'deskripsi' => 'Kemampuan memulai, menjaga, dan mengakhiri percakapan sehari-hari dengan teman sebaya secara natural dan santun.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Sangat pendiam, menghindari interaksi dengan teman baru.',
                        2 => 'Menjawab bila ditanya, belum menginisiasi percakapan.',
                        3 => 'Aktif berkomunikasi, percakapan mengalir dengan cukup baik.',
                        4 => 'Komunikasi hangat dan natural, mudah berteman dengan siapa saja.',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-02',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial',
                    'nama' => 'Kepemimpinan & kerja tim',
                    'deskripsi' => 'Kemampuan berkolaborasi dalam tim, menghargai pendapat orang lain, dan mengambil peran aktif dalam kelompok.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tidak mau bekerja dalam tim, cenderung individualistis.',
                        2 => 'Mau bekerja dalam tim namun pasif.',
                        3 => 'Aktif berkontribusi, menghargai pendapat anggota lain.',
                        4 => 'Memimpin tim secara efektif, mendorong partisipasi semua.',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-03',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial',
                    'nama' => 'Regulasi emosi & manajemen tekanan',
                    'deskripsi' => 'Kemampuan mengelola emosi saat menghadapi tantangan, kegagalan, atau tekanan dalam situasi observasi.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Mudah frustrasi atau menangis saat menemui kesulitan.',
                        2 => 'Kurang stabil, perlu dorongan verbal dari guru.',
                        3 => 'Cukup tenang menghadapi tekanan, pemulihan cepat.',
                        4 => 'Sangat tenang, mampu menghibur teman yang kesulitan.',
                    ],
                ],
                [
                    'id' => 'SMP-PSI-04',
                    'aspek' => 'Kelompok Perkembangan Pribadi & Sosial',
                    'nama' => 'Kemandirian & tanggung jawab pribadi',
                    'deskripsi' => 'Tingkat kemandirian dalam mengatur diri, menyelesaikan tugas tanpa diingatkan, dan bertanggung jawab atas pilihan.',
                    'bobot' => 5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Sangat bergantung pada orang lain untuk menyelesaikan tugas.',
                        2 => 'Menyelesaikan tugas dengan banyak pengingat.',
                        3 => 'Mandiri dengan pengawasan minimal, tanggung jawab baik.',
                        4 => 'Sangat mandiri, proaktif, dan dapat dipercaya sepenuhnya.',
                    ],
                ],
            ],
        ];

        $indikator = $allIndikator[$jenjang] ?? $allIndikator['sd'];

        // Group by aspek
        $grouped = collect($indikator)->groupBy('aspek');

        return view('admin.indikator', compact('user', 'indikator', 'grouped', 'jenjang'));
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
