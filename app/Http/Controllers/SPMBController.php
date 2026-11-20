<?php

namespace App\Http\Controllers;

use App\Models\AsesmenKelompok;
use App\Models\CalonSiswa;
use App\Models\GuruPengamat;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email atau NIP wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');

        // 1. Cari User berdasarkan email atau NIP Guru
        $user = null;
        $guru = null;

        // Cek apakah loginInput adalah NIP di tabel guru_pengamats
        $guru = GuruPengamat::where('nip', $loginInput)->first();
        if ($guru) {
            if ($guru->user_id) {
                $user = User::find($guru->user_id);
            }
            if (! $user && $guru->email) {
                $user = User::where('email', $guru->email)->first();
            }
        } else {
            // Cari berdasarkan email di tabel users
            $user = User::where('email', $loginInput)->first();
            if ($user) {
                $guru = GuruPengamat::where('user_id', $user->id)
                    ->orWhere('email', $user->email)
                    ->first();
            } else {
                // Cari di tabel guru_pengamats berdasarkan email
                $guru = GuruPengamat::where('email', $loginInput)->first();
                if ($guru && $guru->user_id) {
                    $user = User::find($guru->user_id);
                }
            }
        }

        // 2. Pastikan akun admin utama SPMB terpadu ada di database jika dicoba login
        if (! $user) {
            $defaultAdmins = [
                'admin@anaksaleh.sch.id' => [
                    'name' => 'Administrator SPMB',
                ],
                'admin.sd@anaksaleh.sch.id' => [
                    'name' => 'Administrator SPMB',
                ],
                'admin.smp@anaksaleh.sch.id' => [
                    'name' => 'Administrator SPMB',
                ],
            ];

            $lowerInput = strtolower($loginInput);
            if (isset($defaultAdmins[$lowerInput])) {
                $meta = $defaultAdmins[$lowerInput];
                $user = User::create([
                    'name' => $meta['name'],
                    'email' => $lowerInput,
                    'password' => Hash::make('admin123'),
                ]);
            }
        }
        // 3. Jika guru terdaftar tapi belum punya user account di tabel users, buatkan
        if ($guru && ! $user) {
            $user = User::firstOrCreate(
                ['email' => $guru->email],
                [
                    'name' => $guru->nama,
                    'password' => Hash::make('guru123'),
                ]
            );
            if (! $guru->user_id) {
                $guru->update(['user_id' => $user->id]);
            }
        }

        // 4. Verifikasi Kata Sandi
        if (! $user || ! Hash::check($password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email/NIP atau kata sandi yang Anda masukkan salah.');
        }

        // 5. Autentikasi berhasil: jika terhubung sebagai Guru Pengamat
        if ($guru) {
            session([
                'auth_user' => [
                    'id' => $guru->id,
                    'user_id' => $user->id,
                    'name' => $guru->nama,
                    'nip' => $guru->nip,
                    'email' => $guru->email,
                    'role' => 'guru',
                    'jenjang' => $guru->jenjang,
                    'unit' => $guru->unit,
                    'role_label' => 'Guru Pengamat '.$guru->kelompok_short,
                    'kelompok' => $guru->kelompok_nama,
                    'ruang' => $guru->ruang,
                    'pos_desc' => $guru->pos_desc,
                    'avatar' => $guru->avatar,
                    'color' => $guru->color,
                ],
            ]);

            return redirect()->route('guru.observasi', ['guru_nip' => $guru->nip])
                ->with('status', 'Selamat bertugas, '.$guru->nama.' ('.$guru->kelompok_short.')!');
        }

        // 6. Autentikasi berhasil: jika peran Admin Tunggal SPMB
        session([
            'auth_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
                'jenjang' => 'sd',
                'unit' => 'Sekolah Alam Anak Saleh',
                'role_label' => 'Administrator SPMB',
            ],
        ]);

        return redirect()->route('admin.dashboard')
            ->with('status', 'Selamat datang di Dashboard Observasi SPMB Sekolah Alam Anak Saleh!');
    }

    public function observasi(Request $request): View
    {
        $user = session('auth_user', []);
        $jenjangGuru = $user['jenjang'] ?? session('auth_user.jenjang', 'smp');

        // Deteksi SD dari NIP atau jenjang sesi
        $requestedNip = $request->query('guru_nip', $user['nip'] ?? '');
        $isSD = str_starts_with($requestedNip, 'GSD-') || $jenjangGuru === 'sd';

        if ($isSD) {
            return $this->observasiSD($request, $user, $requestedNip);
        }

        return $this->observasiSMP($request, $user, $requestedNip);
    }

    /**
     * Halaman Observasi SD — 3 Sentra, 6 Guru, Form per-indikator.
     */
    private function observasiSD(Request $request, array $user, string $requestedNip): View
    {
        // Bersihkan pendamping terpadu jika masih ada di DB
        GuruPengamat::where('kelompok_key', 'pendamping')->delete();

        // 1. Load semua guru SD dari DB
        $guruList = GuruPengamat::where('jenjang', 'sd')->orderBy('id')->get();

        $allGuruSD = $guruList->keyBy('nip')->map(function ($g) {
            return [
                'id' => $g->id,
                'nip' => $g->nip,
                'name' => $g->nama,
                'email' => $g->email,
                'kelompok_key' => $g->kelompok_key,
                'kelompok' => $g->kelompok_nama,
                'kelompok_short' => $g->kelompok_short,
                'pos_desc' => $g->pos_desc,
                'ruang' => $g->ruang,
                'unit' => $g->unit,
                'avatar' => $g->avatar,
                'color' => $g->color,
            ];
        })->toArray();

        if (empty($allGuruSD)) {
            $activeGuru = [
                'id' => null,
                'nip' => '-',
                'name' => 'Belum Ada Guru Pengamat',
                'email' => '-',
                'kelompok_key' => 'ubudiyah',
                'kelompok' => 'Sentra Ubudiyah',
                'kelompok_short' => 'Sentra Ubudiyah',
                'pos_desc' => '-',
                'ruang' => '-',
                'unit' => 'SD Alam Anak Saleh',
                'avatar' => 'GP',
                'color' => 'emerald',
            ];
            $allGuruSD = ['-' => $activeGuru];
        } else {
            if (! isset($allGuruSD[$requestedNip])) {
                $requestedNip = array_key_first($allGuruSD);
            }
            $activeGuru = $allGuruSD[$requestedNip];
        }

        // Sinkronisasi sesi
        $user['name'] = $activeGuru['name'];
        $user['nip'] = $activeGuru['nip'];
        $user['email'] = $activeGuru['email'];
        $user['role'] = 'guru';
        $user['jenjang'] = 'sd';
        $user['unit'] = 'SD Alam Anak Saleh';
        $user['role_label'] = 'Guru Pengamat '.$activeGuru['kelompok_short'];
        $user['kelompok'] = $activeGuru['kelompok'];
        $user['ruang'] = $activeGuru['ruang'];
        session(['auth_user' => $user]);

        // 2. Load calon siswa SD dari DB beserta relasi pengamatnya
        $calonSiswas = CalonSiswa::with(['asesmenKelompoks.guruPengamat'])
            ->where('jenjang', 'sd')
            ->orderBy('id')
            ->get();

        $siswaSD = $calonSiswas->map(function ($cs) {
            $asesmens = $cs->asesmenKelompoks->keyBy('kelompok_key');
            $ubudiyah = $asesmens->get('ubudiyah');
            $persiapan = $asesmens->get('persiapan');
            $eksplorasi = $asesmens->get('eksplorasi');

            $selesaiCount = $cs->asesmenKelompoks->where('status', 'Selesai')->count();

            return [
                'id' => $cs->id,
                'no' => $cs->no_pendaftaran,
                'name' => $cs->nama,
                'usia' => $cs->usia,
                'kelas_target' => $cs->kelas_target,
                'asal_sekolah' => $cs->asal_sekolah,
                'wali' => $cs->wali,
                'kontak' => $cs->kontak,
                'mulai_dari' => $cs->mulai_dari,
                'posisi_sekarang' => $cs->posisi_sekarang,
                'status_rekomendasi' => $cs->status_rekomendasi,
                'nilai_akhir_terpadu' => $cs->nilai_akhir_terpadu ? (float) $cs->nilai_akhir_terpadu : null,
                'catatan_umum' => $cs->catatan_umum,
                'selesai_count' => $selesaiCount,
                'is_lengkap' => ($selesaiCount >= 3),
                'ubudiyah' => [
                    'guru_id' => $ubudiyah?->guru_pengamat_id,
                    'guru_nip' => $ubudiyah?->guruPengamat?->nip,
                    'guru_nama' => $ubudiyah?->guruPengamat?->nama,
                    'nilai' => $ubudiyah ? (float) $ubudiyah->nilai : null,
                    'status' => $ubudiyah?->status ?? 'Belum Diuji',
                    'kategori' => $ubudiyah?->kategori,
                    'catatan' => $ubudiyah?->catatan_anecdotal,
                    'detail' => $ubudiyah?->detail_nilai ?? [],
                ],
                'persiapan' => [
                    'guru_id' => $persiapan?->guru_pengamat_id,
                    'guru_nip' => $persiapan?->guruPengamat?->nip,
                    'guru_nama' => $persiapan?->guruPengamat?->nama,
                    'nilai' => $persiapan ? (float) $persiapan->nilai : null,
                    'status' => $persiapan?->status ?? 'Belum Diuji',
                    'kategori' => $persiapan?->kategori,
                    'catatan' => $persiapan?->catatan_anecdotal,
                    'detail' => $persiapan?->detail_nilai ?? [],
                ],
                'eksplorasi' => [
                    'guru_id' => $eksplorasi?->guru_pengamat_id,
                    'guru_nip' => $eksplorasi?->guruPengamat?->nip,
                    'guru_nama' => $eksplorasi?->guruPengamat?->nama,
                    'nilai' => $eksplorasi ? (float) $eksplorasi->nilai : null,
                    'status' => $eksplorasi?->status ?? 'Belum Diuji',
                    'kategori' => $eksplorasi?->kategori,
                    'catatan' => $eksplorasi?->catatan_anecdotal,
                    'detail' => $eksplorasi?->detail_nilai ?? [],
                ],
            ];
        })->toArray();

        // Tentukan murid aktif
        $mySentraKey = $activeGuru['kelompok_key'] ?? 'ubudiyah';
        $requestedNo = $request->query('siswa_no');

        if (empty($siswaSD)) {
            $activeSiswa = [];
            $nextSiswa = [];
        } else {
            if ($requestedNo) {
                $activeSiswa = collect($siswaSD)->firstWhere('no', $requestedNo) ?? ($siswaSD[0] ?? []);
            } else {
                // Prioritas murid aktif default:
                // 1. Yang sudah diklaim guru ini
                // 2. Yang masih tersedia (belum diklaim siapapun di sentra ini)
                // 3. Fallback murid pertama
                $activeSiswa = collect($siswaSD)->first(function ($s) use ($mySentraKey, $activeGuru) {
                    $nip = $s[$mySentraKey]['guru_nip'] ?? null;

                    return $nip === $activeGuru['nip'];
                }) ?? collect($siswaSD)->first(function ($s) use ($mySentraKey) {
                    return empty($s[$mySentraKey]['guru_nip']);
                }) ?? ($siswaSD[0] ?? []);
            }

            // Navigasi ke murid berikutnya (yang tidak terkunci oleh co-pengamat jika ada)
            $unlockedSiswa = collect($siswaSD)->filter(function ($s) use ($mySentraKey, $activeGuru) {
                $nip = $s[$mySentraKey]['guru_nip'] ?? null;

                return empty($nip) || $nip === $activeGuru['nip'];
            })->values()->toArray();

            if (empty($unlockedSiswa)) {
                $unlockedSiswa = $siswaSD;
            }

            $currentIndex = 0;
            foreach ($unlockedSiswa as $idx => $s) {
                if ($s['no'] === ($activeSiswa['no'] ?? '')) {
                    $currentIndex = $idx;
                    break;
                }
            }
            $nextIndex = ($currentIndex + 1) % max(1, count($unlockedSiswa));
            $nextSiswa = $unlockedSiswa[$nextIndex] ?? $activeSiswa;
        }

        return view('guru.observasi-sd', compact('user', 'allGuruSD', 'activeGuru', 'siswaSD', 'activeSiswa', 'nextSiswa'));
    }

    /**
     * Halaman Observasi Siswa Baru untuk Guru Pengamat SMP (Terintegrasi Database).
     */
    private function observasiSMP(Request $request, array $user, string $requestedNip): View
    {
        // 1. Load semua guru SMP dari DB
        $guruList = GuruPengamat::where('jenjang', 'smp')->orderBy('id')->get();

        $allGuruSMP = $guruList->keyBy('nip')->map(function ($g) {
            return [
                'id' => $g->id,
                'nip' => $g->nip,
                'name' => $g->nama,
                'email' => $g->email,
                'kelompok_key' => $g->kelompok_key,
                'kelompok' => $g->kelompok_nama,
                'kelompok_short' => $g->kelompok_short,
                'pos_desc' => $g->pos_desc,
                'ruang' => $g->ruang,
                'unit' => $g->unit,
                'avatar' => $g->avatar,
                'color' => $g->color,
            ];
        })->toArray();

        if (empty($allGuruSMP)) {
            $activeGuru = [
                'id' => null,
                'nip' => '-',
                'name' => 'Belum Ada Guru Pengamat',
                'email' => '-',
                'kelompok_key' => 'keagamaan',
                'kelompok' => 'Kelompok Keagamaan',
                'kelompok_short' => 'Keagamaan',
                'pos_desc' => '-',
                'ruang' => '-',
                'unit' => 'SMP Alam Anak Saleh',
                'avatar' => 'GP',
                'color' => 'emerald',
            ];
            $allGuruSMP = ['-' => $activeGuru];
        } else {
            if (empty($requestedNip) || ! isset($allGuruSMP[$requestedNip])) {
                $requestedNip = array_key_first($allGuruSMP);
            }
            $activeGuru = $allGuruSMP[$requestedNip];
        }

        // Sinkronisasi sesi guru aktif
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

        // 2. Ambil seluruh Calon Siswa SMP beserta Asesmen Kelompoknya dari Database
        $calonSiswas = CalonSiswa::with('asesmenKelompoks')
            ->where('jenjang', 'smp')
            ->orderBy('id')
            ->get();

        $siswaSMP = $calonSiswas->map(function ($cs) {
            $asesmens = $cs->asesmenKelompoks->keyBy('kelompok_key');

            $keagamaan = $asesmens->get('keagamaan');
            $akademik = $asesmens->get('akademik');
            $bakatMinat = $asesmens->get('bakat_minat');
            $pribadiSosial = $asesmens->get('pribadi_sosial');

            $kDetail = $keagamaan?->detail_nilai ?? [];
            $aDetail = $akademik?->detail_nilai ?? [];
            $bDetail = $bakatMinat?->detail_nilai ?? [];
            $pDetail = $pribadiSosial?->detail_nilai ?? [];

            // Hitung status kelengkapan 4 kelompok
            $selesaiCount = $cs->asesmenKelompoks->where('status', 'Selesai')->count();

            return [
                'id' => $cs->id,
                'no' => $cs->no_pendaftaran,
                'name' => $cs->nama,
                'usia' => $cs->usia,
                'kelas_target' => $cs->kelas_target,
                'asal_sekolah' => $cs->asal_sekolah,
                'wali' => $cs->wali,
                'kontak' => $cs->kontak,
                'mulai_dari' => $cs->mulai_dari,
                'posisi_sekarang' => $cs->posisi_sekarang,
                'status_rekomendasi' => $cs->status_rekomendasi,
                'nilai_akhir_terpadu' => $cs->nilai_akhir_terpadu ? (float) $cs->nilai_akhir_terpadu : null,
                'catatan_umum' => $cs->catatan_umum,
                'selesai_count' => $selesaiCount,
                'is_lengkap' => ($selesaiCount >= 4),
                'keagamaan' => [
                    'pos1' => $kDetail['pos1'] ?? null,
                    'pos2' => $kDetail['pos2'] ?? null,
                    'pos3' => $kDetail['pos3'] ?? null,
                    'pos4' => $kDetail['pos4'] ?? null,
                    'total_skor' => $keagamaan?->total_skor,
                    'nilai' => $keagamaan ? (float) $keagamaan->nilai : null,
                    'status' => $keagamaan?->status ?? 'Belum Diuji',
                    'kategori' => $keagamaan?->kategori,
                    'catatan' => $keagamaan?->catatan,
                    'detail' => $kDetail,
                ],
                'akademik' => [
                    'cbt' => $aDetail['cbt'] ?? ($aDetail ?: null),
                    'pos5' => $akademik ? (float) $akademik->nilai : null,
                    'nilai' => $akademik ? (float) $akademik->nilai : null,
                    'status' => $akademik?->status ?? 'Belum Diuji',
                    'kategori' => $akademik?->kategori,
                    'catatan' => $akademik?->catatan,
                    'detail' => $aDetail,
                ],
                'bakat_minat' => [
                    'pos6_skor' => $bDetail['pos6_skor'] ?? null,
                    'pos6' => $bDetail['pos6'] ?? null,
                    'pos7_skor' => $bDetail['pos7_skor'] ?? null,
                    'pos7' => $bDetail['pos7'] ?? null,
                    'total_skor' => $bakatMinat?->total_skor,
                    'nilai' => $bakatMinat ? (float) $bakatMinat->nilai : null,
                    'status' => $bakatMinat?->status ?? 'Belum Diuji',
                    'kategori' => $bakatMinat?->kategori,
                    'catatan' => $bakatMinat?->catatan,
                    'detail' => $bDetail,
                ],
                'pribadi_sosial' => [
                    'angket_skor' => $pDetail['angket_skor'] ?? null,
                    'angket_nilai' => $pDetail['angket_nilai'] ?? null,
                    'observasi_skor' => $pDetail['observasi_skor'] ?? null,
                    'observasi_nilai' => $pDetail['observasi_nilai'] ?? null,
                    'total_skor' => $pribadiSosial?->total_skor,
                    'nilai' => $pribadiSosial ? (float) $pribadiSosial->nilai : null,
                    'status' => $pribadiSosial?->status ?? 'Belum Diuji',
                    'kategori_bk' => $pribadiSosial?->kategori ?? 'Sangat Siap',
                    'catatan' => $pribadiSosial?->catatan,
                    'catatan_wawancara' => $pDetail['catatan_wawancara'] ?? null,
                    'catatan_ortu' => $pDetail['catatan_ortu'] ?? null,
                    'detail' => $pDetail,
                ],
            ];
        })->toArray();

        // Murid aktif yang dipilih
        if (empty($siswaSMP)) {
            $activeSiswa = [];
            $nextSiswa = [];
        } else {
            $studentNo = $request->query('siswa_no', $siswaSMP[0]['no'] ?? '');
            $activeSiswa = collect($siswaSMP)->firstWhere('no', $studentNo) ?? ($siswaSMP[0] ?? []);

            // Murid berikutnya (untuk navigasi praktis mobile)
            $currentIndex = 0;
            foreach ($siswaSMP as $idx => $s) {
                if ($s['no'] === ($activeSiswa['no'] ?? '')) {
                    $currentIndex = $idx;
                    break;
                }
            }
            $nextIndex = ($currentIndex + 1) % max(1, count($siswaSMP));
            $nextSiswa = $siswaSMP[$nextIndex] ?? $activeSiswa;
        }

        return view('guru.observasi', compact('user', 'allGuruSMP', 'activeGuru', 'siswaSMP', 'activeSiswa', 'nextSiswa'));
    }

    /**
     * Simpan / Perbarui Nilai Observasi ke Database MySQL.
     */
    public function simpanObservasi(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_no' => 'required|string',
            'kelompok_key' => 'required|string',
            'scores' => 'nullable|array',
            'detail_nilai' => 'nullable|array',
            'catatan' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $siswaNo = $request->input('siswa_no');
        $kelompokKey = $request->input('kelompok_key');
        $scores = $request->input('scores', []);
        $detailNilai = $request->input('detail_nilai', []);
        $catatan = $request->input('catatan');
        $status = $request->input('status', 'Selesai');

        $siswa = CalonSiswa::where('no_pendaftaran', $siswaNo)->first();
        if (! $siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data calon siswa dengan nomor pendaftaran '.$siswaNo.' tidak ditemukan di sistem.',
            ], 404);
        }

        $userNip = session('auth_user.nip');
        $guru = null;
        if ($userNip) {
            $guru = GuruPengamat::where('nip', $userNip)->first();
        }
        if (! $guru) {
            $guru = GuruPengamat::where('kelompok_key', $kelompokKey)->first();
        }
        if (! $guru) {
            $shortLabels = [
                'ubudiyah' => 'Sentra Ubudiyah',
                'persiapan' => 'Sentra Persiapan',
                'eksplorasi' => 'Sentra Eksplorasi Diri',
            ];
            $guru = (object) [
                'id' => null,
                'kelompok_short' => $shortLabels[$kelompokKey] ?? ucfirst($kelompokKey),
            ];
        }

        $totalSkor = 0;
        $skorMaksimal = 100;
        $nilai = 0.0;
        $kategori = '';

        if ($kelompokKey === 'ubudiyah') {
            // Sentra Ubudiyah: 7 Indikator, skala 1-4
            $bobots = [
                'SD-UBUD-01' => 3.5,
                'SD-UBUD-02' => 3.5,
                'SD-UBUD-03' => 3.0,
                'SD-UBUD-04' => 2.5,
                'SD-UBUD-05' => 2.5,
                'SD-UBUD-06' => 5.0,
                'SD-UBUD-07' => 5.0,
            ];
            $skorDiperoleh = 0;
            $rawSum = 0;
            foreach ($bobots as $indId => $b) {
                $s = intval($scores[$indId] ?? 4);
                $rawSum += $s;
                $skorDiperoleh += ($s / 4) * $b;
            }
            $totalSkor = $rawSum;
            $skorMaksimal = 28;
            $nilai = round(($skorDiperoleh / 25.0) * 100, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik' : (($nilai >= 75) ? 'Baik' : (($nilai >= 65) ? 'Cukup' : 'Perlu Bimbingan'));
            $detailNilai = $scores;
        } elseif ($kelompokKey === 'persiapan') {
            // Sentra Persiapan: 14 Indikator, skala 1-4
            $bobots = [
                'SD-PERS-01' => 2.0, 'SD-PERS-02' => 2.0, 'SD-PERS-03' => 2.0, 'SD-PERS-04' => 2.0, 'SD-PERS-05' => 2.0,
                'SD-PERS-06' => 1.5, 'SD-PERS-07' => 1.5, 'SD-PERS-08' => 1.5, 'SD-PERS-09' => 1.5, 'SD-PERS-10' => 1.5,
                'SD-PERS-11' => 2.0, 'SD-PERS-12' => 2.0, 'SD-PERS-13' => 2.0, 'SD-PERS-14' => 2.0,
            ];
            $skorDiperoleh = 0;
            $rawSum = 0;
            foreach ($bobots as $indId => $b) {
                $s = intval($scores[$indId] ?? 4);
                $rawSum += $s;
                $skorDiperoleh += ($s / 4) * $b;
            }
            $totalSkor = $rawSum;
            $skorMaksimal = 56;
            $nilai = round(($skorDiperoleh / 25.5) * 100, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik' : (($nilai >= 75) ? 'Baik' : (($nilai >= 65) ? 'Cukup' : 'Perlu Bimbingan'));
            $detailNilai = $scores;
        } elseif ($kelompokKey === 'eksplorasi') {
            // Sentra Eksplorasi Diri: 18 Indikator (10 Motorik @2.2, 8 Sos-Em @2.25)
            $bobots = [
                'SD-EKS-01' => 2.2, 'SD-EKS-02' => 2.2, 'SD-EKS-03' => 2.2, 'SD-EKS-04' => 2.2, 'SD-EKS-05' => 2.2,
                'SD-EKS-06' => 2.2, 'SD-EKS-07' => 2.2, 'SD-EKS-08' => 2.2, 'SD-EKS-09' => 2.2, 'SD-EKS-10' => 2.2,
                'SD-EKS-11' => 2.25, 'SD-EKS-12' => 2.25, 'SD-EKS-13' => 2.25, 'SD-EKS-14' => 2.25,
                'SD-EKS-15' => 2.25, 'SD-EKS-16' => 2.25, 'SD-EKS-17' => 2.25, 'SD-EKS-18' => 2.25,
            ];
            $skorDiperoleh = 0;
            $rawSum = 0;
            foreach ($bobots as $indId => $b) {
                $s = intval($scores[$indId] ?? 4);
                $rawSum += $s;
                $skorDiperoleh += ($s / 4) * $b;
            }
            $totalSkor = $rawSum;
            $skorMaksimal = 72;
            $nilai = round(($skorDiperoleh / 40.0) * 100, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik' : (($nilai >= 75) ? 'Baik' : (($nilai >= 65) ? 'Cukup' : 'Perlu Bimbingan'));
            $detailNilai = $scores;
        } elseif ($kelompokKey === 'keagamaan') {
            // Pos 1 s/d Pos 4 (Total 27 aspek, skala 1-4, Skor Maksimal = 108)
            $p1 = 0;
            for ($i = 0; $i < 7; $i++) {
                $p1 += intval($scores['p1-'.$i] ?? 4);
            }
            $p2 = 0;
            for ($i = 0; $i < 7; $i++) {
                $p2 += intval($scores['p2-'.$i] ?? 4);
            }
            $p3 = 0;
            for ($i = 0; $i < 6; $i++) {
                $p3 += intval($scores['p3-'.$i] ?? 4);
            }
            $p4 = 0;
            for ($i = 0; $i < 7; $i++) {
                $p4 += intval($scores['p4-'.$i] ?? 4);
            }

            $totalSkor = ! empty($scores) ? ($p1 + $p2 + $p3 + $p4) : (int) ($request->input('total_skor', 100));
            $skorMaksimal = 108;
            $nilai = round(($totalSkor / $skorMaksimal) * 100, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik' : (($nilai >= 75) ? 'Baik' : (($nilai >= 65) ? 'Cukup' : 'Perlu Bimbingan'));

            $detailNilai = array_merge($detailNilai, [
                'pos1' => $p1,
                'pos2' => $p2,
                'pos3' => $p3,
                'pos4' => $p4,
                'raw_scores' => $scores,
            ]);
        } elseif ($kelompokKey === 'akademik') {
            // CBT Pos 5: B. Indonesia, Matematika, IPAS, B. Inggris (0-100)
            $indo = (float) ($scores['indo'] ?? ($detailNilai['cbt']['indo'] ?? 90));
            $mtk = (float) ($scores['mtk'] ?? ($detailNilai['cbt']['mtk'] ?? 90));
            $ipas = (float) ($scores['ipas'] ?? ($detailNilai['cbt']['ipas'] ?? 90));
            $inggris = (float) ($scores['inggris'] ?? ($detailNilai['cbt']['inggris'] ?? 90));

            $totalSkor = (int) round($indo + $mtk + $ipas + $inggris);
            $skorMaksimal = 400;
            $nilai = round($totalSkor / 4, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik (A)' : (($nilai >= 75) ? 'Baik (B)' : (($nilai >= 65) ? 'Cukup (C)' : 'Perlu Bimbingan (D)'));

            $detailNilai = [
                'cbt' => [
                    'indo' => $indo,
                    'mtk' => $mtk,
                    'ipas' => $ipas,
                    'inggris' => $inggris,
                ],
            ];
        } elseif ($kelompokKey === 'bakat_minat') {
            // Pos 6 (7 aspek, max 28) + Pos 7 (6 aspek, max 24) = Total Maksimal 52
            $p6 = 0;
            for ($i = 0; $i < 7; $i++) {
                $p6 += intval($scores['p6-'.$i] ?? 4);
            }
            $p7 = 0;
            for ($i = 0; $i < 6; $i++) {
                $p7 += intval($scores['p7-'.$i] ?? 4);
            }

            $totalSkor = ! empty($scores) ? ($p6 + $p7) : (int) ($request->input('total_skor', 48));
            $skorMaksimal = 52;
            $nilai = round(($totalSkor / $skorMaksimal) * 100, 1);
            $kategori = ($nilai >= 85) ? 'Sangat Baik' : (($nilai >= 75) ? 'Baik' : (($nilai >= 65) ? 'Cukup' : 'Perlu Bimbingan'));

            $detailNilai = array_merge($detailNilai, [
                'pos6_skor' => $p6,
                'pos6' => round(($p6 / 28) * 100, 1),
                'pos7_skor' => $p7,
                'pos7' => round(($p7 / 24) * 100, 1),
                'raw_scores' => $scores,
            ]);
        } elseif ($kelompokKey === 'pribadi_sosial') {
            // Sub-Tab A: Angket Refleksi (10 item, skala 1-4, max 40)
            // Sub-Tab C: Observasi Kelompok (6 aspek, skala 1-4, max 24)
            $angSkor = 0;
            for ($i = 0; $i < 10; $i++) {
                $angSkor += intval($scores['ang-'.$i] ?? 4);
            }
            $obsSkor = 0;
            for ($i = 0; $i < 6; $i++) {
                $obsSkor += intval($scores['obs-'.$i] ?? 4);
            }

            $totalSkor = $angSkor + $obsSkor;
            $skorMaksimal = 64;

            $angketNilai = round(($angSkor / 40) * 100, 1);
            $observasiNilai = round(($obsSkor / 24) * 100, 1);
            $nilai = round(($angketNilai + $observasiNilai) / 2, 1);

            // Pemetaan Layanan BK Otomatis
            if ($nilai >= 86) {
                $kategori = 'Sangat Siap';
            } elseif ($nilai >= 76) {
                $kategori = 'Siap';
            } elseif ($nilai >= 60) {
                $kategori = 'Berkembang';
            } else {
                $kategori = 'Perlu Pendampingan';
            }

            $detailNilai = array_merge($detailNilai, [
                'angket_skor' => $angSkor,
                'angket_nilai' => $angketNilai,
                'observasi_skor' => $obsSkor,
                'observasi_nilai' => $observasiNilai,
                'catatan_wawancara' => $request->input('catatan_wawancara', $detailNilai['catatan_wawancara'] ?? ''),
                'catatan_ortu' => $request->input('catatan_ortu', $detailNilai['catatan_ortu'] ?? ''),
                'raw_scores' => $scores,
            ]);
        }

        // Cek jika siswa sudah diklaim / diobservasi oleh rekan co-pengamat lain
        if ($siswa && $guru && isset($guru->id)) {
            $existingAsesmen = AsesmenKelompok::where('calon_siswa_id', $siswa->id)
                ->where('kelompok_key', $kelompokKey)
                ->first();
            if ($existingAsesmen && $existingAsesmen->guru_pengamat_id && $existingAsesmen->guru_pengamat_id != $guru->id) {
                $otherGuru = GuruPengamat::find($existingAsesmen->guru_pengamat_id);

                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, siswa ini sedang/sudah diobservasi oleh rekan Co-Pengamat: '.($otherGuru->nama ?? 'Guru Lain').'. Anda tidak dapat mengubah penilaian ini.',
                ], 422);
            }
        }

        // Simpan / Perbarui Asesmen ke Database
        AsesmenKelompok::updateOrCreate(
            [
                'calon_siswa_id' => $siswa->id,
                'kelompok_key' => $kelompokKey,
            ],
            [
                'guru_pengamat_id' => $guru->id,
                'total_skor' => $totalSkor,
                'skor_maksimal' => $skorMaksimal,
                'nilai' => $nilai,
                'status' => $status,
                'kategori' => $kategori,
                'catatan' => $catatan,
                'detail_nilai' => $detailNilai,
            ]
        );

        // Muat ulang data siswa & evaluasi kelengkapan kelompok / sentra
        $siswa->load('asesmenKelompoks');
        $selesaiCount = $siswa->asesmenKelompoks->where('status', 'Selesai')->count();
        $targetCount = ($siswa->jenjang === 'sd' || in_array($kelompokKey, ['ubudiyah', 'persiapan', 'eksplorasi'])) ? 3 : 4;
        $isLengkap = ($selesaiCount >= $targetCount);

        if ($isLengkap) {
            $avgScore = round($siswa->asesmenKelompoks->where('status', 'Selesai')->avg('nilai'), 2);
            $siswa->update([
                'nilai_akhir_terpadu' => $avgScore,
                'status_rekomendasi' => ($avgScore >= 75) ? 'Diterima' : 'Dipertimbangkan',
                'posisi_sekarang' => 'Semua Ruang Tuntas',
            ]);
        } else {
            $siswa->update([
                'posisi_sekarang' => 'Sedang di Ruang '.$guru->kelompok_short,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Alhamdulillah! Nilai {$guru->kelompok_short} untuk ananda {$siswa->nama} berhasil disimpan ke database.",
            'nilai' => $nilai,
            'kategori' => $kategori,
            'total_skor' => $totalSkor,
            'skor_maksimal' => $skorMaksimal,
            'status' => $status,
            'selesai_count' => $selesaiCount,
            'is_lengkap' => $isLengkap,
            'posisi_sekarang' => $siswa->posisi_sekarang,
            'siswa_no' => $siswaNo,
            'kelompok_key' => $kelompokKey,
        ]);
    }

    /**
     * Klaim Calon Siswa oleh Guru Pengamat Aktif (Mencegah observasi ganda oleh co-pengamat).
     */
    public function klaimSiswa(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_no' => 'required|string',
            'kelompok_key' => 'required|string',
        ]);

        $siswaNo = $request->input('siswa_no');
        $kelompokKey = $request->input('kelompok_key');
        $userNip = session('auth_user.nip');

        $guru = GuruPengamat::where('nip', $userNip)->first();
        $siswa = CalonSiswa::where('no_pendaftaran', $siswaNo)->first();
        if (! $siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data calon siswa dengan nomor pendaftaran '.$siswaNo.' tidak ditemukan di sistem.',
            ], 404);
        }

        if ($siswa && $guru) {
            $asesmen = AsesmenKelompok::firstOrNew([
                'calon_siswa_id' => $siswa->id,
                'kelompok_key' => $kelompokKey,
            ]);

            // Cek jika sudah diklaim oleh guru co-pengamat lain
            if ($asesmen->exists && $asesmen->guru_pengamat_id && $asesmen->guru_pengamat_id != $guru->id) {
                $otherGuru = GuruPengamat::find($asesmen->guru_pengamat_id);

                return response()->json([
                    'success' => false,
                    'message' => 'Siswa ini sedang/sudah diobservasi oleh rekan Co-Pengamat: '.($otherGuru->nama ?? 'Guru Lain').'.',
                ], 422);
            }

            $asesmen->guru_pengamat_id = $guru->id;
            if (! $asesmen->status || $asesmen->status === 'Belum Diuji') {
                $asesmen->status = 'Sedang Diobservasi';
            }
            $asesmen->save();

            $siswa->update([
                'posisi_sekarang' => 'Sedang di '.($guru->kelompok_short ?? $guru->ruang),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alhamdulillah, siswa berhasil Anda klaim untuk diobservasi.',
        ]);
    }

    /**
     * Lepas Siswa kembali ke antrean bersama di Sentra ini.
     */
    public function lepasSiswa(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_no' => 'required|string',
            'kelompok_key' => 'required|string',
        ]);

        $siswaNo = $request->input('siswa_no');
        $kelompokKey = $request->input('kelompok_key');
        $userNip = session('auth_user.nip');

        $guru = GuruPengamat::where('nip', $userNip)->first();
        $siswa = CalonSiswa::where('no_pendaftaran', $siswaNo)->first();
        if (! $siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data calon siswa dengan nomor pendaftaran '.$siswaNo.' tidak ditemukan di sistem.',
            ], 404);
        }

        if ($siswa && $guru) {
            $asesmen = AsesmenKelompok::where('calon_siswa_id', $siswa->id)
                ->where('kelompok_key', $kelompokKey)
                ->where('guru_pengamat_id', $guru->id)
                ->first();

            if ($asesmen) {
                // Hanya bisa lepas jika belum bernilai final 'Selesai'
                if ($asesmen->status !== 'Selesai') {
                    $asesmen->guru_pengamat_id = null;
                    $asesmen->status = 'Belum Diuji';
                    $asesmen->save();

                    $siswa->update([
                        'posisi_sekarang' => 'Menunggu Antrean Sentra',
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa sudah berstatus Selesai dinilai dan tidak dapat dilepas.',
                    ], 422);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil dilepas kembali ke antrean bersama.',
        ]);
    }

    /**
     * Dashboard Observasi & Rekapitulasi untuk Admin (TK / SD / SMP).
     */
    public function dashboard(): View
    {
        $requestedJenjang = request('jenjang');
        if ($requestedJenjang && in_array($requestedJenjang, ['sd', 'smp', 'tk'])) {
            $user = session('auth_user', []);
            $user['jenjang'] = $requestedJenjang;
            session(['auth_user' => $user]);
        }

        $user = session('auth_user', [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
            'unit' => 'Sekolah Alam Anak Saleh',
            'role_label' => 'Administrator SPMB',
        ]);

        $currentJenjang = $requestedJenjang ?? ($user['jenjang'] ?? 'sd');

        $jenjang = $currentJenjang;

        $targetCount = ($jenjang === 'smp') ? 4 : 3;

        $allSiswa = CalonSiswa::with('asesmenKelompoks')
            ->where('jenjang', $jenjang)
            ->get();

        $totalPendaftar = $allSiswa->count();
        $selesaiObservasi = $allSiswa->filter(fn ($cs) => $cs->asesmenKelompoks->where('status', 'Selesai')->count() >= $targetCount)->count();
        $antrean = $allSiswa->filter(fn ($cs) => $cs->asesmenKelompoks->where('status', 'Selesai')->count() > 0 && $cs->asesmenKelompoks->where('status', 'Selesai')->count() < $targetCount)->count();
        $rataKesiapan = $allSiswa->whereNotNull('nilai_akhir_terpadu')->avg('nilai_akhir_terpadu');
        $persen = $totalPendaftar > 0 ? round(($selesaiObservasi / $totalPendaftar) * 100, 1) : 0;

        $unitTitles = ['sd' => 'SD Alam Anak Saleh', 'smp' => 'SMP Alam Anak Saleh', 'tk' => 'KB & TK Anak Saleh'];
        $unitBadges = ['sd' => 'Jenjang Sekolah Dasar (Kelas 1)', 'smp' => 'Jenjang Sekolah Menengah Pertama (Kelas 7)', 'tk' => 'Jenjang Usia Dini (3 - 6 Tahun)'];

        $data = [
            'unit_title' => $unitTitles[$jenjang] ?? $unitTitles['sd'],
            'unit_badge' => $unitBadges[$jenjang] ?? $unitBadges['sd'],
            'total_pendaftar' => $totalPendaftar,
            'pendaftar_sub' => $totalPendaftar === 0 ? 'Belum ada data pendaftar' : 'Tahun ajaran 2026/2027',
            'selesai_observasi' => $selesaiObservasi,
            'persen_selesai' => $persen.'% selesai diobservasi',
            'antrean_hari_ini' => $antrean,
            'antrean_sub' => $antrean > 0 ? 'Sedang dalam proses observasi' : 'Tidak ada antrian aktif',
            'rata_kesiapan' => $rataKesiapan ? number_format($rataKesiapan, 1).'%' : '—',
            'analytics_1_title' => $jenjang === 'smp' ? 'Distribusi Kelengkapan Asesmen (SMP)' : 'Distribusi Kelengkapan Asesmen (SD)',
            'analytics_1_subtitle' => $selesaiObservasi.' Siswa Terpetakan',
            'analytics_1_items' => [
                ['label' => 'Selesai semua '.($jenjang === 'smp' ? '4 kelompok' : '3 sentra'), 'val' => $selesaiObservasi.' Siswa', 'bar' => $totalPendaftar > 0 ? round(($selesaiObservasi / $totalPendaftar) * 100).'%' : '0%'],
                ['label' => 'Sedang diobservasi (parsial)', 'val' => $antrean.' Siswa', 'bar' => $totalPendaftar > 0 ? round(($antrean / $totalPendaftar) * 100).'%' : '0%'],
                ['label' => 'Belum mulai observasi', 'val' => ($totalPendaftar - $selesaiObservasi - $antrean).' Siswa', 'bar' => $totalPendaftar > 0 ? round((($totalPendaftar - $selesaiObservasi - $antrean) / $totalPendaftar) * 100).'%' : '0%'],
            ],
            'analytics_2_title' => 'Status Rekomendasi Penerimaan',
            'analytics_2_subtitle' => 'Rekap Keputusan',
            'analytics_2_boxes' => [
                ['val' => (string) $allSiswa->where('status_rekomendasi', 'Diterima')->count(), 'title' => 'Diterima', 'sub' => 'Rekomendasi Final'],
                ['val' => (string) $allSiswa->where('status_rekomendasi', 'Dalam Proses')->count(), 'title' => 'Dalam Proses', 'sub' => 'Sedang Dinilai'],
                ['val' => (string) $allSiswa->whereIn('status_rekomendasi', ['Pendampingan', 'Dipertimbangkan'])->count(), 'title' => 'Lainnya', 'sub' => 'Pertimbangan Khusus'],
            ],
            'students' => $allSiswa->map(function ($cs) use ($targetCount) {
                $selesai = $cs->asesmenKelompoks->where('status', 'Selesai')->count();
                $nilai = $cs->nilai_akhir_terpadu ? number_format($cs->nilai_akhir_terpadu, 1).'%' : '—';
                $status = $selesai >= $targetCount ? 'Selesai Observasi' : ($selesai > 0 ? 'Dalam Proses' : 'Menunggu');
                $displayStatus = ($cs->status_rekomendasi && $cs->status_rekomendasi !== 'Belum Diuji')
                    ? $cs->status_rekomendasi
                    : $status;
                $badge = $displayStatus === 'Diterima' ? 'emerald' : ($displayStatus === 'Dalam Proses' ? 'blue' : 'amber');

                return [
                    'id' => $cs->no_pendaftaran,
                    'name' => $cs->nama,
                    'class' => $cs->kelas_target ?? '-',
                    'guru' => '-',
                    'score' => $nilai,
                    'char' => $cs->catatan_umum ?? '-',
                    'status' => $displayStatus,
                    'badge' => $badge,
                ];
            })->values()->toArray(),
        ];

        return view('admin.dashboard', compact('user', 'currentJenjang', 'data'));
    }

    /**
     * Tampilkan rapor hasil observasi seorang calon siswa.
     */
    public function rapor(string $siswa): View
    {
        $calonSiswa = CalonSiswa::with('asesmenKelompoks.guruPengamat')
            ->where('no_pendaftaran', $siswa)
            ->firstOrFail();

        $targetCount = $calonSiswa->jenjang === 'smp' ? 4 : 3;
        $asesmens = $calonSiswa->asesmenKelompoks->sortBy('created_at');
        $selesaiCount = $asesmens->where('status', 'Selesai')->count();
        $indikatorById = collect($this->getIndikatorData($calonSiswa->jenjang))->keyBy('id');
        $kelompokLabels = [
            'ubudiyah' => 'Sentra Ubudiyah',
            'persiapan' => 'Sentra Persiapan',
            'eksplorasi' => 'Sentra Eksplorasi Diri',
            'keagamaan' => 'Keagamaan',
            'akademik' => 'Akademik',
            'bakat_minat' => 'Bakat dan Minat',
            'pribadi_sosial' => 'Pribadi dan Sosial',
        ];

        $rapor = [
            'siswa' => $calonSiswa,
            'kelompok' => $asesmens->map(fn (AsesmenKelompok $asesmen) => [
                'label' => $kelompokLabels[$asesmen->kelompok_key] ?? ucfirst(str_replace('_', ' ', $asesmen->kelompok_key)),
                'asesmen' => $asesmen,
                'detail' => $asesmen->detail_nilai ?? [],
                'indikator' => collect($asesmen->detail_nilai ?? [])->map(function ($score, $key) use ($indikatorById) {
                    $indikator = $indikatorById->get($key);

                    return [
                        'key' => $key,
                        'name' => $indikator['nama'] ?? ucfirst(str_replace(['_', '-'], ' ', (string) $key)),
                        'aspect' => $indikator['aspek'] ?? null,
                        'score' => is_numeric($score) ? (float) $score : null,
                        'rubrik' => is_numeric($score) ? ($indikator['rubrik'][(int) $score] ?? null) : null,
                        'raw' => is_scalar($score) ? (string) $score : null,
                    ];
                })->filter(fn (array $item) => $item['raw'] !== null)->values(),
            ])->values(),
            'selesai_count' => $selesaiCount,
            'target_count' => $targetCount,
            'status' => $selesaiCount >= $targetCount ? 'Observasi lengkap' : 'Observasi belum lengkap',
        ];

        return view('admin.rapor', compact('rapor'));
    }

    /**
     * Daftar Guru Pengamat per unit (Terhubung Database).
     */
    public function guruList(): View
    {
        $requestedJenjang = request('jenjang');
        if ($requestedJenjang && in_array($requestedJenjang, ['sd', 'smp', 'tk'])) {
            $user = session('auth_user', []);
            $user['jenjang'] = $requestedJenjang;
            $user['role_label'] = 'Admin SPMB '.strtoupper($requestedJenjang);
            $user['unit'] = $requestedJenjang === 'sd' ? 'SD Alam Anak Saleh' : ($requestedJenjang === 'smp' ? 'SMP Alam Anak Saleh' : 'TK Anak Saleh');
            session(['auth_user' => $user]);
        }

        $user = session('auth_user', [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
            'unit' => 'Sekolah Alam Anak Saleh',
            'role_label' => 'Administrator SPMB',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';

        // Bersihkan pendamping terpadu jika masih ada
        GuruPengamat::where('kelompok_key', 'pendamping')->delete();

        // Ambil guru dari database
        $guruModels = GuruPengamat::where('jenjang', $jenjang)->orderBy('id')->get();
        $guru = $guruModels->map(function ($g) {
            $obsCount = AsesmenKelompok::where('guru_pengamat_id', $g->id)
                ->where('status', '!=', 'Belum Diuji')
                ->count();

            return [
                'id' => $g->id,
                'nip' => $g->nip,
                'name' => $g->nama,
                'email' => $g->email,
                'jenjang' => $g->jenjang,
                'sentra' => $g->kelompok_short ?? $g->kelompok_nama,
                'sentra_key' => $g->kelompok_key,
                'kelompok' => $g->kelompok_nama,
                'kelompok_short' => $g->kelompok_short,
                'ruang' => $g->ruang,
                'pos_desc' => $g->pos_desc,
                'jumlah_observasi' => $obsCount,
                'status' => $g->status ?? 'Aktif',
                'avatar' => $g->avatar ?? substr($g->nama, 0, 2),
                'color' => $g->color ?? 'emerald',
            ];
        })->toArray();

        return view('admin.guru', compact('user', 'guru', 'jenjang'));
    }

    /**
     * Tambah Guru Pengamat Baru.
     */
    public function guruStore(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:guru_pengamats,nip',
            'email' => 'required|email|max:255|unique:guru_pengamats,email',
            'jenjang' => 'required|in:sd,smp,tk',
            'kelompok_key' => 'required|string',
            'ruang' => 'nullable|string|max:255',
            'pos_desc' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $jenjang = $request->input('jenjang', 'sd');
        $kelompokKey = $request->input('kelompok_key');

        $meta = $this->resolveKelompokMeta($jenjang, $kelompokKey);

        $userAccount = User::firstOrCreate(
            ['email' => $request->input('email')],
            [
                'name' => $request->input('nama'),
                'password' => Hash::make('guru123'),
            ]
        );

        $initials = collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $request->input('nama'))))
            ->filter()
            ->take(2)
            ->map(fn ($w) => strtoupper($w[0]))
            ->implode('');

        GuruPengamat::create([
            'user_id' => $userAccount->id,
            'nip' => $request->input('nip'),
            'nama' => $request->input('nama'),
            'email' => $request->input('email'),
            'jenjang' => $jenjang,
            'kelompok_key' => $kelompokKey,
            'kelompok_short' => $meta['short'],
            'kelompok_nama' => $meta['nama'],
            'pos_desc' => $request->input('pos_desc') ?: $meta['pos_desc'],
            'ruang' => $request->input('ruang') ?: $meta['ruang'],
            'unit' => $jenjang === 'sd' ? 'SD Alam Anak Saleh' : ($jenjang === 'smp' ? 'SMP Alam Anak Saleh' : 'TK Anak Saleh'),
            'avatar' => $initials ?: 'GP',
            'color' => $meta['color'],
            'status' => $request->input('status', 'Aktif'),
        ]);

        return redirect()->route('admin.guru', ['jenjang' => $jenjang])
            ->with('status', "Alhamdulillah! Guru {$request->input('nama')} ({$request->input('nip')}) berhasil ditambahkan.");
    }

    /**
     * Update Guru Pengamat.
     */
    public function guruUpdate(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        $guru = GuruPengamat::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:guru_pengamats,nip,'.$id,
            'email' => 'required|email|max:255|unique:guru_pengamats,email,'.$id,
            'kelompok_key' => 'required|string',
            'ruang' => 'nullable|string|max:255',
            'pos_desc' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $jenjang = $guru->jenjang;
        $kelompokKey = $request->input('kelompok_key');
        $meta = $this->resolveKelompokMeta($jenjang, $kelompokKey);

        $guru->update([
            'nama' => $request->input('nama'),
            'nip' => $request->input('nip'),
            'email' => $request->input('email'),
            'kelompok_key' => $kelompokKey,
            'kelompok_short' => $meta['short'],
            'kelompok_nama' => $meta['nama'],
            'ruang' => $request->input('ruang') ?: $meta['ruang'],
            'pos_desc' => $request->input('pos_desc') ?: $meta['pos_desc'],
            'color' => $meta['color'],
            'status' => $request->input('status', 'Aktif'),
        ]);

        if ($guru->user) {
            $guru->user->update([
                'name' => $request->input('nama'),
                'email' => $request->input('email'),
            ]);
        }

        return redirect()->route('admin.guru', ['jenjang' => $jenjang])
            ->with('status', "Data guru {$guru->nama} ({$guru->nip}) berhasil diperbarui.");
    }

    /**
     * Hapus Guru Pengamat.
     */
    public function guruDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|exists:guru_pengamats,id',
        ]);

        $guru = GuruPengamat::findOrFail($request->input('id'));
        $jenjang = $guru->jenjang;
        $nama = $guru->nama;
        $nip = $guru->nip;

        $guru->delete();

        return redirect()->route('admin.guru', ['jenjang' => $jenjang])
            ->with('status', "Guru {$nama} ({$nip}) berhasil dihapus dari sistem.");
    }

    /**
     * Unduh Template CSV Import Guru.
     */
    public function guruTemplate(Request $request): Response
    {
        $jenjang = $request->query('jenjang', 'sd');
        $filename = "template-guru-{$jenjang}.csv";

        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['nip', 'nama', 'email', 'jenjang', 'kelompok_key', 'ruang', 'pos_desc', 'status']);

        if ($jenjang === 'sd') {
            fputcsv($output, ['GSD-2026-08', 'Ustadzah Siti Aminah, S.Pd.', 'aminah@anaksaleh.sch.id', 'sd', 'ubudiyah', 'Ruang Sentra Ubudiyah', 'Pos 1.1–1.3 Wudhu, Sholat, Doa & Hafalan', 'Aktif']);
            fputcsv($output, ['GSD-2026-09', 'Ustadz Ahmad Fauzi, S.Pd.', 'fauzi@anaksaleh.sch.id', 'sd', 'persiapan', 'Ruang Sentra Persiapan', 'Pos 2.1–2.4 Calistung & English', 'Aktif']);
            fputcsv($output, ['GSD-2026-10', 'Ustadzah Halimah, S.Psi.', 'halimah@anaksaleh.sch.id', 'sd', 'eksplorasi', 'Ruang Sentra Eksplorasi Diri', 'Pos 3.1–3.2 Motorik & Sos-Em', 'Aktif']);
        } else {
            fputcsv($output, ['GSMP-2026-05', 'Ustadz Salman Al-Farisi, M.Pd.', 'salman@anaksaleh.sch.id', 'smp', 'keagamaan', 'Ruang Kelompok Keagamaan', 'Pos 1–4 Sholat, Tilawati, Doa, Surat', 'Aktif']);
            fputcsv($output, ['GSMP-2026-06', 'Ustadzah Rahmawati, S.Si.', 'rahma@anaksaleh.sch.id', 'smp', 'akademik', 'Ruang Kelompok Akademik', 'Pos 5 CBT Matematika & Sains', 'Aktif']);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import Guru dari File CSV.
     */
    public function guruImport(Request $request): RedirectResponse
    {
        $request->validate([
            'file_guru' => 'required|file',
            'jenjang' => 'nullable|string|in:sd,smp,tk',
        ]);

        $defaultJenjang = $request->input('jenjang', 'sd');
        $rows = $this->parseCsvFile($request->file('file_guru'));

        if (empty($rows)) {
            return back()->with('error', 'File CSV kosong atau format header tidak sesuai.');
        }

        $imported = 0;
        foreach ($rows as $row) {
            $nip = $row['nip'] ?? null;
            $nama = $row['nama'] ?? ($row['nama_lengkap'] ?? null);
            $email = $row['email'] ?? null;
            $jenjang = strtolower($row['jenjang'] ?? $defaultJenjang);
            $kelompokKey = strtolower($row['kelompok_key'] ?? ($row['sentra_kelompok'] ?? 'ubudiyah'));

            if (! $nip || ! $nama || ! $email) {
                continue;
            }

            $meta = $this->resolveKelompokMeta($jenjang, $kelompokKey);

            $userAccount = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make('guru123'),
                ]
            );

            $initials = collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $nama)))
                ->filter()
                ->take(2)
                ->map(fn ($w) => strtoupper($w[0]))
                ->implode('');

            GuruPengamat::updateOrCreate(
                ['nip' => $nip],
                [
                    'user_id' => $userAccount->id,
                    'nama' => $nama,
                    'email' => $email,
                    'jenjang' => $jenjang,
                    'kelompok_key' => $kelompokKey,
                    'kelompok_short' => $meta['short'],
                    'kelompok_nama' => $meta['nama'],
                    'ruang' => $row['ruang'] ?? ($row['ruangan'] ?? $meta['ruang']),
                    'pos_desc' => $row['pos_desc'] ?? ($row['deskripsi_pos'] ?? $meta['pos_desc']),
                    'unit' => $jenjang === 'sd' ? 'SD Alam Anak Saleh' : ($jenjang === 'smp' ? 'SMP Alam Anak Saleh' : 'TK Anak Saleh'),
                    'avatar' => $initials ?: 'GP',
                    'color' => $meta['color'],
                    'status' => $row['status'] ?? 'Aktif',
                ]
            );

            $imported++;
        }

        return redirect()->route('admin.guru', ['jenjang' => $defaultJenjang])
            ->with('status', "Alhamdulillah! Berhasil mengimpor {$imported} data guru pengamat.");
    }

    /**
     * Daftar Calon Siswa per unit (Terhubung Database).
     */
    public function siswaList(): View
    {
        $requestedJenjang = request('jenjang');
        if ($requestedJenjang && in_array($requestedJenjang, ['sd', 'smp', 'tk'])) {
            $user = session('auth_user', []);
            $user['jenjang'] = $requestedJenjang;
            $user['role_label'] = 'Admin SPMB '.strtoupper($requestedJenjang);
            $user['unit'] = $requestedJenjang === 'sd' ? 'SD Alam Anak Saleh' : ($requestedJenjang === 'smp' ? 'SMP Alam Anak Saleh' : 'TK Anak Saleh');
            session(['auth_user' => $user]);
        }

        $user = session('auth_user', [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
            'unit' => 'Sekolah Alam Anak Saleh',
            'role_label' => 'Administrator SPMB',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';

        // Ambil data siswa dari DB beserta relasi asesmen
        $calonSiswas = CalonSiswa::with('asesmenKelompoks')
            ->where('jenjang', $jenjang)
            ->orderBy('id')
            ->get();

        $siswa = $calonSiswas->map(function ($cs) {
            $targetCount = ($cs->jenjang === 'sd') ? 3 : 4;
            $selesaiCount = $cs->asesmenKelompoks->where('status', 'Selesai')->count();

            if ($selesaiCount >= $targetCount) {
                $statusObservasi = 'Selesai';
            } elseif ($selesaiCount > 0) {
                $statusObservasi = 'Sedang Diobservasi';
            } else {
                $statusObservasi = 'Menunggu';
            }

            return [
                'id' => $cs->id,
                'no' => $cs->no_pendaftaran,
                'name' => $cs->nama,
                'usia' => $cs->usia,
                'jenjang' => $cs->jenjang,
                'kelas_target' => $cs->kelas_target,
                'asal_sekolah' => $cs->asal_sekolah,
                'wali' => $cs->wali,
                'kontak' => $cs->kontak,
                'mulai_dari' => $cs->mulai_dari,
                'posisi_sekarang' => $cs->posisi_sekarang,
                'status_observasi' => $statusObservasi,
                'rekomendasi' => $cs->status_rekomendasi ?: 'Dalam Proses',
                'nilai_akhir_terpadu' => $cs->nilai_akhir_terpadu ? (float) $cs->nilai_akhir_terpadu : null,
                'catatan_umum' => $cs->catatan_umum,
                'selesai_count' => $selesaiCount,
                'target_count' => $targetCount,
            ];
        })->toArray();

        return view('admin.siswa', compact('user', 'siswa', 'jenjang'));
    }

    /**
     * Tambah Calon Siswa Baru.
     */
    public function siswaStore(Request $request): RedirectResponse
    {
        $request->validate([
            'no_pendaftaran' => 'required|string|max:50|unique:calon_siswas,no_pendaftaran',
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|in:sd,smp,tk',
            'usia' => 'nullable|string|max:50',
            'kelas_target' => 'nullable|string|max:100',
            'asal_sekolah' => 'nullable|string|max:255',
            'wali' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'mulai_dari' => 'nullable|string|max:255',
            'status_rekomendasi' => 'nullable|string|max:50',
            'catatan_umum' => 'nullable|string',
        ]);

        $jenjang = $request->input('jenjang', 'sd');

        $siswa = CalonSiswa::create([
            'no_pendaftaran' => $request->input('no_pendaftaran'),
            'nama' => $request->input('nama'),
            'jenjang' => $jenjang,
            'usia' => $request->input('usia') ?: ($jenjang === 'sd' ? '6 Thn 0 Bln' : '12 Thn 0 Bln'),
            'kelas_target' => $request->input('kelas_target') ?: ($jenjang === 'sd' ? 'Kelas 1' : 'Kelas 7'),
            'asal_sekolah' => $request->input('asal_sekolah'),
            'wali' => $request->input('wali'),
            'kontak' => $request->input('kontak'),
            'mulai_dari' => $request->input('mulai_dari') ?: ($jenjang === 'sd' ? 'Sentra Ubudiyah' : 'Kelompok Keagamaan'),
            'posisi_sekarang' => 'Sedang Menunggu Giliran',
            'status_rekomendasi' => $request->input('status_rekomendasi', 'Dalam Proses'),
            'catatan_umum' => $request->input('catatan_umum'),
        ]);

        // Inisialisasi kelompok asesmen kosong
        $this->initStudentAssessments($siswa);

        return redirect()->route('admin.siswa', ['jenjang' => $jenjang])
            ->with('status', "Alhamdulillah! Calon siswa {$siswa->nama} ({$siswa->no_pendaftaran}) berhasil ditambahkan.");
    }

    /**
     * Update Calon Siswa.
     */
    public function siswaUpdate(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        $siswa = CalonSiswa::findOrFail($id);

        $request->validate([
            'no_pendaftaran' => 'required|string|max:50|unique:calon_siswas,no_pendaftaran,'.$id,
            'nama' => 'required|string|max:255',
            'usia' => 'nullable|string|max:50',
            'kelas_target' => 'nullable|string|max:100',
            'asal_sekolah' => 'nullable|string|max:255',
            'wali' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'mulai_dari' => 'nullable|string|max:255',
            'status_rekomendasi' => 'nullable|string|max:50',
            'catatan_umum' => 'nullable|string',
        ]);

        $siswa->update([
            'no_pendaftaran' => $request->input('no_pendaftaran'),
            'nama' => $request->input('nama'),
            'usia' => $request->input('usia'),
            'kelas_target' => $request->input('kelas_target'),
            'asal_sekolah' => $request->input('asal_sekolah'),
            'wali' => $request->input('wali'),
            'kontak' => $request->input('kontak'),
            'mulai_dari' => $request->input('mulai_dari'),
            'status_rekomendasi' => $request->input('status_rekomendasi', 'Dalam Proses'),
            'catatan_umum' => $request->input('catatan_umum'),
        ]);

        return redirect()->route('admin.siswa', ['jenjang' => $siswa->jenjang])
            ->with('status', "Data calon siswa {$siswa->nama} ({$siswa->no_pendaftaran}) berhasil diperbarui.");
    }

    /**
     * Hapus Calon Siswa.
     */
    public function siswaDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|exists:calon_siswas,id',
        ]);

        $siswa = CalonSiswa::findOrFail($request->input('id'));
        $jenjang = $siswa->jenjang;
        $nama = $siswa->nama;
        $no = $siswa->no_pendaftaran;

        $siswa->delete();

        return redirect()->route('admin.siswa', ['jenjang' => $jenjang])
            ->with('status', "Calon siswa {$nama} ({$no}) berhasil dihapus dari sistem.");
    }

    /**
     * Reset / Kosongkan seluruh nilai observasi calon siswa tanpa menghapus data profil siswa (Khusus Admin).
     */
    public function siswaResetNilai(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|exists:calon_siswas,id',
        ]);

        $siswa = CalonSiswa::findOrFail($request->input('id'));
        $jenjang = $siswa->jenjang;
        $nama = $siswa->nama;
        $no = $siswa->no_pendaftaran;

        // 1. Hapus seluruh riwayat asesmen penilaian kelompok / sentra siswa ini
        AsesmenKelompok::where('calon_siswa_id', $siswa->id)->delete();

        // 2. Kembalikan status dan nilai siswa ke kondisi awal (belum diobservasi)
        $siswa->update([
            'nilai_akhir_terpadu' => null,
            'status_rekomendasi' => 'Belum Diuji',
            'posisi_sekarang' => 'Belum Diuji',
        ]);

        return redirect()->route('admin.siswa', ['jenjang' => $jenjang])
            ->with('status', "Alhamdulillah! Seluruh nilai observasi untuk siswa {$nama} ({$no}) berhasil direset. Data profil siswa tetap aman dan tersimpan.");
    }

    /**
     * Unduh Template CSV Import Calon Siswa.
     */
    public function siswaTemplate(Request $request): Response
    {
        $jenjang = $request->query('jenjang', 'sd');
        $filename = "template-calon-siswa-{$jenjang}.csv";

        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['no_pendaftaran', 'nama', 'jenjang', 'usia', 'kelas_target', 'asal_sekolah', 'wali', 'kontak', 'mulai_dari', 'status_rekomendasi', 'catatan_umum']);

        if ($jenjang === 'sd') {
            fputcsv($output, ['SPMB-2026-SD07', 'Ahmad Daniyal', 'sd', '6 Thn 2 Bln', 'Kelas 1', 'TK Aisyiyah 1', 'Bpk. Daniyal', '0812-9999-0007', 'Sentra Ubudiyah', 'Dalam Proses', 'Cerdas, aktif, dan santun.']);
            fputcsv($output, ['SPMB-2026-SD08', 'Fathimah Zahira', 'sd', '6 Thn 4 Bln', 'Kelas 1 Unggulan', 'TK Pertiwi', 'Ibu Zahira', '0812-9999-0008', 'Sentra Persiapan', 'Dalam Proses', 'Hafalan surat lancar.']);
        } else {
            fputcsv($output, ['SPMB-2026-SMP06', 'Daffa Ibnu Malik', 'smp', '12 Thn 4 Bln', 'Kelas 7 Bilingual', 'SDIT Bina Anak Saleh', 'Bpk. Malik', '0821-9999-0006', 'Kelompok Keagamaan', 'Dalam Proses', 'Bakat kepemimpinan baik.']);
            fputcsv($output, ['SPMB-2026-SMP07', 'Nadia Syakira', 'smp', '12 Thn 2 Bln', 'Kelas 7 Tahfidz', 'SD Alam', 'Ibu Syakira', '0821-9999-0007', 'Kelompok Pribadi & Sosial', 'Dalam Proses', 'Santun dan mandiri.']);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import Calon Siswa dari File CSV.
     */
    public function siswaImport(Request $request): RedirectResponse
    {
        $request->validate([
            'file_siswa' => 'required|file',
            'jenjang' => 'nullable|string|in:sd,smp,tk',
        ]);

        $defaultJenjang = $request->input('jenjang', 'sd');
        $rows = $this->parseCsvFile($request->file('file_siswa'));

        if (empty($rows)) {
            return back()->with('error', 'File CSV kosong atau format header tidak sesuai.');
        }

        $imported = 0;
        foreach ($rows as $row) {
            $no = $row['no_pendaftaran'] ?? ($row['no_daftar'] ?? ($row['no'] ?? null));
            $nama = $row['nama'] ?? ($row['nama_calon_siswa'] ?? ($row['name'] ?? null));
            $jenjang = strtolower($row['jenjang'] ?? $defaultJenjang);

            if (! $no || ! $nama) {
                continue;
            }

            $siswa = CalonSiswa::updateOrCreate(
                ['no_pendaftaran' => $no],
                [
                    'nama' => $nama,
                    'jenjang' => $jenjang,
                    'usia' => $row['usia'] ?? ($jenjang === 'sd' ? '6 Thn 0 Bln' : '12 Thn 0 Bln'),
                    'kelas_target' => $row['kelas_target'] ?? ($row['kelas_dituju'] ?? ($jenjang === 'sd' ? 'Kelas 1' : 'Kelas 7')),
                    'asal_sekolah' => $row['asal_sekolah'] ?? null,
                    'wali' => $row['wali'] ?? ($row['nama_wali'] ?? null),
                    'kontak' => $row['kontak'] ?? ($row['no_kontak'] ?? null),
                    'mulai_dari' => $row['mulai_dari'] ?? ($jenjang === 'sd' ? 'Sentra Ubudiyah' : 'Kelompok Keagamaan'),
                    'posisi_sekarang' => $row['posisi_sekarang'] ?? 'Sedang Menunggu Giliran',
                    'status_rekomendasi' => $row['status_rekomendasi'] ?? 'Dalam Proses',
                    'catatan_umum' => $row['catatan_umum'] ?? ($row['catatan_khusus'] ?? null),
                ]
            );

            $this->initStudentAssessments($siswa);
            $imported++;
        }

        return redirect()->route('admin.siswa', ['jenjang' => $defaultJenjang])
            ->with('status', "Alhamdulillah! Berhasil mengimpor {$imported} data calon siswa baru.");
    }

    /**
     * Export Calon Siswa ke CSV.
     */
    public function siswaExport(Request $request): Response
    {
        $jenjang = $request->query('jenjang', 'sd');
        $filename = "data-calon-siswa-{$jenjang}-".date('Ymd_His').'.csv';

        $siswas = CalonSiswa::with('asesmenKelompoks')
            ->where('jenjang', $jenjang)
            ->orderBy('id')
            ->get();

        $output = fopen('php://temp', 'r+');
        fputcsv($output, ['no_pendaftaran', 'nama', 'jenjang', 'usia', 'kelas_target', 'asal_sekolah', 'wali', 'kontak', 'mulai_dari', 'posisi_sekarang', 'status_rekomendasi', 'nilai_akhir_terpadu', 'catatan_umum']);

        foreach ($siswas as $s) {
            fputcsv($output, [
                $s->no_pendaftaran,
                $s->nama,
                $s->jenjang,
                $s->usia,
                $s->kelas_target,
                $s->asal_sekolah,
                $s->wali,
                $s->kontak,
                $s->mulai_dari,
                $s->posisi_sekarang,
                $s->status_rekomendasi,
                $s->nilai_akhir_terpadu,
                $s->catatan_umum,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Helper: Resolve metadata kelompok/sentra.
     */
    private function resolveKelompokMeta(string $jenjang, string $key): array
    {
        $configs = [
            'sd' => [
                'ubudiyah' => [
                    'short' => 'Sentra Ubudiyah',
                    'nama' => 'Sentra Ubudiyah (Wudhu, Sholat, Doa & Hafalan)',
                    'color' => 'emerald',
                    'ruang' => 'Ruang Sentra Ubudiyah (Pos 1.1–1.3)',
                    'pos_desc' => 'Pos 1.1 Wudhu & Sholat • Pos 1.2 Doa Harian • Pos 1.3 Surat Pendek (7 Indikator)',
                ],
                'persiapan' => [
                    'short' => 'Sentra Persiapan',
                    'nama' => 'Sentra Persiapan (Calistung & Bahasa Inggris Dasar)',
                    'color' => 'indigo',
                    'ruang' => 'Ruang Sentra Persiapan (Pos 2.1–2.4)',
                    'pos_desc' => 'Pos 2.1 Membaca • Pos 2.2 Menulis/Dikte • Pos 2.3 Berhitung • Pos 2.4 English (14 Indikator)',
                ],
                'eksplorasi' => [
                    'short' => 'Sentra Eksplorasi Diri',
                    'nama' => 'Sentra Eksplorasi Diri (Motorik & Sosial Emosional)',
                    'color' => 'amber',
                    'ruang' => 'Ruang Sentra Eksplorasi Diri (Pos 3.1–3.2)',
                    'pos_desc' => 'Pos 3.1 Aspek Motorik (10 Aspek) • Pos 3.2 Wawancara Transisi (8 Pertanyaan)',
                ],
            ],
            'smp' => [
                'keagamaan' => [
                    'short' => 'Keagamaan',
                    'nama' => 'Kelompok Keagamaan',
                    'color' => 'emerald',
                    'ruang' => 'Ruang Kelompok Keagamaan (Pos 1–4)',
                    'pos_desc' => 'Pos 1 Sholat • Pos 2 Tilawati • Pos 3 Doa • Pos 4 Surat Pendek (27 Aspek)',
                ],
                'akademik' => [
                    'short' => 'Akademik CBT',
                    'nama' => 'Kelompok Akademik (Numerasi, Literasi, Science, and English)',
                    'color' => 'blue',
                    'ruang' => 'Ruang Kelompok Akademik (Pos 5 CBT)',
                    'pos_desc' => 'Pos 5 CBT: B. Indonesia, Matematika, IPAS, B. Inggris',
                ],
                'bakat_minat' => [
                    'short' => 'Potensi Bakat Minat',
                    'nama' => 'Kelompok Potensi Bakat dan Minat',
                    'color' => 'purple',
                    'ruang' => 'Ruang Kelompok Potensi Bakat & Minat (Pos 6–7)',
                    'pos_desc' => 'Pos 6 Motorik Halus & Kreativitas • Pos 7 Inovasi (13 Aspek)',
                ],
                'pribadi_sosial' => [
                    'short' => 'Pribadi & Sosial',
                    'nama' => 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)',
                    'color' => 'amber',
                    'ruang' => 'Ruang Kelompok Pribadi & Sosial',
                    'pos_desc' => 'Pos 8: Wawancara Psikososial Transisi SMP & Angket BK',
                ],
            ],
        ];

        return $configs[$jenjang][$key] ?? [
            'short' => ucfirst($key),
            'nama' => ucfirst($key),
            'color' => 'zinc',
            'ruang' => 'Ruang Observasi '.ucfirst($key),
            'pos_desc' => 'Observasi Asesmen '.ucfirst($key),
        ];
    }

    /**
     * Helper: Inisialisasi slot asesmen kelompok untuk siswa.
     */
    private function initStudentAssessments(CalonSiswa $siswa): void
    {
        $keys = ($siswa->jenjang === 'sd')
            ? ['ubudiyah', 'persiapan', 'eksplorasi']
            : ['keagamaan', 'akademik', 'bakat_minat', 'pribadi_sosial'];

        foreach ($keys as $k) {
            AsesmenKelompok::firstOrCreate(
                [
                    'calon_siswa_id' => $siswa->id,
                    'kelompok_key' => $k,
                ],
                [
                    'guru_pengamat_id' => null,
                    'total_skor' => null,
                    'skor_maksimal' => ($k === 'ubudiyah' ? 28 : ($k === 'persiapan' ? 56 : ($k === 'eksplorasi' ? 72 : 100))),
                    'nilai' => null,
                    'status' => 'Belum Diuji',
                    'kategori' => null,
                    'detail_nilai' => [],
                    'catatan_anecdotal' => null,
                ]
            );
        }
    }

    /**
     * Helper: Parse CSV File secara fleksibel (koma dan titik-koma).
     */
    private function parseCsvFile($file): array
    {
        $content = file_get_contents($file->getRealPath());
        if ($content === false) {
            return [];
        }

        // Hapus UTF-8 BOM
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) {
            return [];
        }

        $firstLine = $lines[0];
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $data = [];
        $header = null;
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $row = str_getcsv($line, $delimiter);
            if (! $header) {
                $header = array_map(function ($h) {
                    $clean = strtolower(trim($h));
                    $clean = preg_replace('/[^a-z0-9_]/', '_', $clean);

                    return trim($clean, '_');
                }, $row);

                continue;
            }

            if (count($row) === count($header)) {
                $data[] = array_combine($header, array_map('trim', $row));
            } elseif (count($row) > 1) {
                $padded = array_pad(array_map('trim', $row), count($header), '');
                $sliced = array_slice($padded, 0, count($header));
                $data[] = array_combine($header, $sliced);
            }
        }

        return $data;
    }

    /**
     * Manajemen Indikator Observasi & Skoring per unit.
     */
    public function indikatorList(): View
    {
        $requestedJenjang = request('jenjang');
        if ($requestedJenjang && in_array($requestedJenjang, ['sd', 'smp', 'tk'])) {
            $user = session('auth_user', []);
            $user['jenjang'] = $requestedJenjang;
            $user['role_label'] = 'Admin SPMB '.strtoupper($requestedJenjang);
            $user['unit'] = $requestedJenjang === 'sd' ? 'SD Alam Anak Saleh' : ($requestedJenjang === 'smp' ? 'SMP Alam Anak Saleh' : 'TK Anak Saleh');
            session(['auth_user' => $user]);
        }

        $user = session('auth_user', [
            'name' => 'Administrator SPMB',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'jenjang' => 'sd',
            'unit' => 'Sekolah Alam Anak Saleh',
            'role_label' => 'Administrator SPMB',
        ]);

        $jenjang = $user['jenjang'] ?? 'sd';
        $indikator = $this->getIndikatorData($jenjang);

        // Group by aspek
        $grouped = collect($indikator)->groupBy('aspek');

        return view('admin.indikator', compact('user', 'indikator', 'grouped', 'jenjang'));
    }

    /**
     * Definisi indikator yang dipakai pada halaman manajemen indikator dan rapor.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getIndikatorData(string $jenjang): array
    {
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
                // ── Sentra Ubudiyah (7 Indikator) ─────────────────────────
                // 1.1 Praktek Wudhu dan Sholat (3 Indikator)
                [
                    'id' => 'SD-UBUD-01',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.1 Praktek Wudhu dan Sholat',
                    'pos_id' => 'sd-ubud-1',
                    'pos_label' => '1.1',
                    'nama' => 'Niat dan Gerakan dalam Berwudhu',
                    'deskripsi' => 'Penguasaan niat dan urutan rukun serta sunnah gerakan berwudhu dengan tertib.',
                    'bobot' => 3.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menguasai urutan wudhu, perlu bimbingan penuh.',
                        2 => 'Menguasai sebagian urutan wudhu dengan sedikit panduan.',
                        3 => 'Melakukan urutan wudhu mandiri dengan tertib.',
                        4 => 'Praktik wudhu sangat baik, tertib, dan sesuai sunnah.',
                    ],
                ],
                [
                    'id' => 'SD-UBUD-02',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.1 Praktek Wudhu dan Sholat',
                    'pos_id' => 'sd-ubud-1',
                    'pos_label' => '1.1',
                    'nama' => 'Niat dan Gerakan Sholat',
                    'deskripsi' => 'Ketepatan gerakan takbiratul ihram, ruku\', i\'tidal, sujud, dan duduk di antara dua sujud.',
                    'bobot' => 3.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Gerakan sholat belum tertib, banyak gerakan di luar sholat.',
                        2 => 'Gerakan sholat cukup baik dengan beberapa arahan.',
                        3 => 'Gerakan sholat tertib dan tuma\'ninah cukup baik.',
                        4 => 'Gerakan sholat sangat tertib, tuma\'ninah terjaga sempurna.',
                    ],
                ],
                [
                    'id' => 'SD-UBUD-03',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.1 Praktek Wudhu dan Sholat',
                    'pos_id' => 'sd-ubud-1',
                    'pos_label' => '1.1',
                    'nama' => 'Bacaan Sholat',
                    'deskripsi' => 'Pelafalan bacaan sholat (doa iftitah, ruku\', sujud, dan tahiyyat) dengan tartil.',
                    'bobot' => 3.0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum menghafal bacaan sholat.',
                        2 => 'Menghafal sebagian bacaan dengan bantuan guru.',
                        3 => 'Menghafal sebagian besar bacaan dengan lancar.',
                        4 => 'Bacaan sholat lengkap, fasih, dan lancar.',
                    ],
                ],
                // 1.2 Hafalan Doa Sehari-hari (2 Indikator)
                [
                    'id' => 'SD-UBUD-04',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.2 Hafalan Doa Sehari-hari',
                    'pos_id' => 'sd-ubud-2',
                    'pos_label' => '1.2',
                    'nama' => 'Doa Sebelum Makan',
                    'deskripsi' => 'Kelancaran dan adab melafalkan doa sebelum makan/minum.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal doa sebelum makan.',
                        2 => 'Hafal dengan dipandu kalimat per kalimat.',
                        3 => 'Hafal mandiri, sedikit terbata-bata.',
                        4 => 'Hafal lancar, fasih, dan beradab saat berdoa.',
                    ],
                ],
                [
                    'id' => 'SD-UBUD-05',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.2 Hafalan Doa Sehari-hari',
                    'pos_id' => 'sd-ubud-2',
                    'pos_label' => '1.2',
                    'nama' => 'Doa Setelah Makan',
                    'deskripsi' => 'Kelancaran dan adab melafalkan doa setelah selesai makan/minum.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal doa setelah makan.',
                        2 => 'Hafal dengan dipandu guru.',
                        3 => 'Hafal mandiri dengan lancar.',
                        4 => 'Hafal sangat lancar, fasih, dan penuh rasa syukur.',
                    ],
                ],
                // 1.3 Hafalan Surat-Surat Pendek (2 Indikator)
                [
                    'id' => 'SD-UBUD-06',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.3 Hafalan Surat-Surat Pendek',
                    'pos_id' => 'sd-ubud-3',
                    'pos_label' => '1.3',
                    'nama' => 'Al-Fatihah',
                    'deskripsi' => 'Makharijul huruf, kelancaran, dan adab melafalkan Surat Al-Fatihah.',
                    'bobot' => 5.0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal lengkap Surat Al-Fatihah.',
                        2 => 'Hafal dengan bimbingan di beberapa ayat.',
                        3 => 'Hafal lengkap dan lancar.',
                        4 => 'Hafal sangat lancar, tajwid dan makhraj tepat.',
                    ],
                ],
                [
                    'id' => 'SD-UBUD-07',
                    'aspek' => 'Sentra Ubudiyah',
                    'pos' => '1.3 Hafalan Surat-Surat Pendek',
                    'pos_id' => 'sd-ubud-3',
                    'pos_label' => '1.3',
                    'nama' => 'Al-Ikhlas',
                    'deskripsi' => 'Makharijul huruf dan kelancaran melafalkan Surat Al-Ikhlas.',
                    'bobot' => 5.0,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum hafal Surat Al-Ikhlas.',
                        2 => 'Hafal 1–2 ayat awal.',
                        3 => 'Hafal lengkap mandiri.',
                        4 => 'Hafal sangat lancar, tartil, dan fasih.',
                    ],
                ],

                // ── Sentra Persiapan (14 Indikator) ────────────────────────
                // 2.1 Membaca (Pengenalan Huruf) (3 Indikator)
                [
                    'id' => 'SD-SIAP-01',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.1 Membaca (Pengenalan Huruf)',
                    'pos_id' => 'sd-siap-1',
                    'pos_label' => '2.1',
                    'nama' => 'Melafalkan huruf A-Z secara urut (klasikal)',
                    'deskripsi' => 'Kemampuan melafalkan abjad A–Z secara berurutan dengan artikulasi jelas.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu melafalkan abjad secara urut.',
                        2 => 'Mampu melafalkan A–M dengan baik.',
                        3 => 'Mampu melafalkan A–Z dengan sedikit jeda.',
                        4 => 'Melafalkan A–Z sangat lancar dan tepat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-02',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.1 Membaca (Pengenalan Huruf)',
                    'pos_id' => 'sd-siap-1',
                    'pos_label' => '2.1',
                    'nama' => 'Membaca Suku Kata (individu)',
                    'deskripsi' => 'Kemampuan merangkai konsonan-vokal (KV) menjadi suku kata sederhana.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal konsep suku kata.',
                        2 => 'Mampu membaca 2–3 suku kata dengan bantuan.',
                        3 => 'Mampu membaca suku kata mandiri.',
                        4 => 'Membaca suku kata sangat cepat dan akurat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-03',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.1 Membaca (Pengenalan Huruf)',
                    'pos_id' => 'sd-siap-1',
                    'pos_label' => '2.1',
                    'nama' => 'Membaca per Kata (individu)',
                    'deskripsi' => 'Kemampuan membaca kata bermakna (2–3 suku kata) tanpa mengeja panjang.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu membaca kata utuh.',
                        2 => 'Membaca kata dengan mengeja lambat.',
                        3 => 'Membaca kata sederhana dengan lancar.',
                        4 => 'Membaca kata majemuk dan lancar tanpa mengeja.',
                    ],
                ],
                // 2.2 Menulis / Dikte (2 Indikator)
                [
                    'id' => 'SD-SIAP-04',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.2 Menulis / Dikte',
                    'pos_id' => 'sd-siap-2',
                    'pos_label' => '2.2',
                    'nama' => 'Teknik Menulis',
                    'deskripsi' => 'Posisi duduk tegak, cara memegang pensil (tripod grasp), dan ketepatan arah goresan huruf.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Posisi duduk dan pegangan pensil belum ergonomis.',
                        2 => 'Mulai memegang pensil dengan benar dengan koreksi berkala.',
                        3 => 'Pegangan pensil stabil dan posisi duduk tertib.',
                        4 => 'Teknik menulis sangat ergonomis, konsisten, dan rapi.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-05',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.2 Menulis / Dikte',
                    'pos_id' => 'sd-siap-2',
                    'pos_label' => '2.2',
                    'nama' => 'Menulis Nama Benda',
                    'deskripsi' => 'Kemampuan menyalin atau menuliskan nama benda sederhana yang didiktekan.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mampu menulis huruf yang membentuk kata benda.',
                        2 => 'Menuliskan huruf awal atau sebagian huruf.',
                        3 => 'Menuliskan nama benda lengkap dengan sedikit koreksi.',
                        4 => 'Menuliskan kata benda tepat, jelas, dan proporsional.',
                    ],
                ],
                // 2.3 Berhitung (5 Indikator)
                [
                    'id' => 'SD-SIAP-06',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.3 Berhitung',
                    'pos_id' => 'sd-siap-3',
                    'pos_label' => '2.3',
                    'nama' => 'Membilang Urut Maju (1-10)',
                    'deskripsi' => 'Kemampuan berhitung urut maju angka 1 sampai 10 tanpa terlewat.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum bisa membilang urut 1–5.',
                        2 => 'Membilang 1–5 lancar, 6–10 perlu dibantu.',
                        3 => 'Membilang 1–10 urut mandiri.',
                        4 => 'Membilang 1–10 sangat lancar dan memahami urutannya.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-07',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.3 Berhitung',
                    'pos_id' => 'sd-siap-3',
                    'pos_label' => '2.3',
                    'nama' => 'Membilang Urut Mundur (10-1)',
                    'deskripsi' => 'Kemampuan membilang terbalik dari angka 10 menuju 1.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami konsep hitung mundur.',
                        2 => 'Mampu membilang mundur dari 5 ke 1.',
                        3 => 'Membilang mundur 10 ke 1 dengan sedikit jeda.',
                        4 => 'Membilang mundur 10 ke 1 lancar dan cepat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-08',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.3 Berhitung',
                    'pos_id' => 'sd-siap-3',
                    'pos_label' => '2.3',
                    'nama' => 'Menuliskan Lambang Bilangan Secara Acak (1-10)',
                    'deskripsi' => 'Mengenali dan menuliskan simbol angka 1–10 saat disebutkan secara acak.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal lambang bilangan.',
                        2 => 'Mengenal dan menuliskan 1–4 angka acak.',
                        3 => 'Menuliskan hampir seluruh angka (beberapa terbalik).',
                        4 => 'Menuliskan seluruh angka 1–10 acak dengan benar dan rapi.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-09',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.3 Berhitung',
                    'pos_id' => 'sd-siap-3',
                    'pos_label' => '2.3',
                    'nama' => 'Membilang Banyak Benda',
                    'deskripsi' => 'Menghitung jumlah benda konkret/gambar (korespondensi satu-satu) hingga 10.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menghitung benda acak, sering melompati objek.',
                        2 => 'Mampu menghitung hingga 5 objek konkret.',
                        3 => 'Mampu menghitung hingga 10 objek dengan teliti.',
                        4 => 'Korespondensi satu-satu sangat akurat dan cepat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-10',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.3 Berhitung',
                    'pos_id' => 'sd-siap-3',
                    'pos_label' => '2.3',
                    'nama' => 'Membandingkan Banyak dan Ukuran Benda',
                    'deskripsi' => 'Membedakan konsep lebih banyak/sedikit, lebih besar/kecil, dan lebih panjang/pendek.',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum membedakan konsep ukuran dan kuantitas.',
                        2 => 'Membedakan perbandingan kontras ekstrem.',
                        3 => 'Membandingkan ukuran dan jumlah dengan benar.',
                        4 => 'Mampu menjelaskan alasan perbandingan secara logis.',
                    ],
                ],
                // 2.4 English (4 Indikator)
                [
                    'id' => 'SD-SIAP-11',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.4 English',
                    'pos_id' => 'sd-siap-4',
                    'pos_label' => '2.4',
                    'nama' => 'Listening about Colour',
                    'deskripsi' => 'Kemampuan mengenali dan menunjuk warna saat instruksi berbahasa Inggris disebutkan (red, blue, green, yellow, etc.).',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami instruksi warna dalam bahasa Inggris.',
                        2 => 'Merespon 1–2 warna dasar dengan benar.',
                        3 => 'Merespon 3–4 warna dengan tepat.',
                        4 => 'Merespon seluruh warna dengan tepat dan cepat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-12',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.4 English',
                    'pos_id' => 'sd-siap-4',
                    'pos_label' => '2.4',
                    'nama' => 'Speaking (Introduction and Greeting)',
                    'deskripsi' => 'Kemampuan merespons sapaan sederhana (Hello, Good Morning, How are you?) dan menyebut nama (My name is...).',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum merespon sapaan bahasa Inggris.',
                        2 => 'Merespon dengan gestur atau satu kata pendek.',
                        3 => 'Merespon sapaan dan perkenalan sederhana.',
                        4 => 'Percaya diri dan fasih dalam perkenalan bahasa Inggris dasar.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-13',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.4 English',
                    'pos_id' => 'sd-siap-4',
                    'pos_label' => '2.4',
                    'nama' => 'Vocabulary About Fruits',
                    'deskripsi' => 'Mengenali kosa kata nama buah-buahan umum dalam bahasa Inggris (apple, banana, orange, etc.).',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal kosa kata buah dalam bahasa Inggris.',
                        2 => 'Mengenal 1–2 nama buah.',
                        3 => 'Mengenal 3–4 nama buah dengan baik.',
                        4 => 'Menyebutkan dan mengidentifikasi ragam kosa kata buah dengan tepat.',
                    ],
                ],
                [
                    'id' => 'SD-SIAP-14',
                    'aspek' => 'Sentra Persiapan',
                    'pos' => '2.4 English',
                    'pos_id' => 'sd-siap-4',
                    'pos_label' => '2.4',
                    'nama' => 'Vocabulary About Animals',
                    'deskripsi' => 'Mengenali kosa kata nama binatang dalam bahasa Inggris (cat, dog, bird, fish, lion, etc.).',
                    'bobot' => 2.5,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum mengenal kosa kata hewan dalam bahasa Inggris.',
                        2 => 'Mengenal 1–2 nama hewan umum.',
                        3 => 'Mengenal 3–4 nama hewan dengan tepat.',
                        4 => 'Menyebutkan ragam nama hewan dan suara/karakternya.',
                    ],
                ],

                // ── Sentra Eksplorasi Diri (18 Indikator) ───────────────────
                // 3.1 Aspek Motorik (10 Indikator)
                [
                    'id' => 'SD-EKS-01',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Koordinasi mata dan tangan (meronce)',
                    'deskripsi' => 'Kemampuan memasukkan benang ke dalam lubang manik dengan ketelitian visual-motorik.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Perlu bantuan penuh',
                        2 => 'Dengan panduan berkala',
                        3 => 'Mandiri cukup rapi',
                        4 => 'Mandiri, presisi, dan cepat',
                    ],
                ],
                [
                    'id' => 'SD-EKS-02',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Finger Painting',
                    'deskripsi' => 'Eksplorasi sensorik taktil menggunakan jari tangan pada media kertas dengan percaya diri.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menolak menyentuh cat/enggan',
                        2 => 'Mau menyentuh dengan dorongan',
                        3 => 'Aktif melukis dengan jari',
                        4 => 'Sangat antusias, kreatif, dan ekspresif',
                    ],
                ],
                [
                    'id' => 'SD-EKS-03',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Tracing dan Menggunting',
                    'deskripsi' => 'Mengikuti garis pola dan menggunting kertas mengikuti garis lurus dan lengkung.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum dapat mengendalikan gunting',
                        2 => 'Menggunting terputus-putus di luar pola',
                        3 => 'Mengikuti garis pola dengan cukup baik',
                        4 => 'Menggunting presisi mengikuti garis rapi',
                    ],
                ],
                [
                    'id' => 'SD-EKS-04',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Mencocok Pola dengan Jarum',
                    'deskripsi' => 'Ketelitian dan kehati-hatian menggunakan jarum cocok mengikuti batas pola gambar.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Kurang hati-hati / belum terarah',
                        2 => 'Mencocok dengan jarak renggang',
                        3 => 'Mencocok rapat mengikuti pola',
                        4 => 'Sangat rapi, rapat, dan mudah dilepas',
                    ],
                ],
                [
                    'id' => 'SD-EKS-05',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Keseimbangan (meniti papan titian)',
                    'deskripsi' => 'Keseimbangan tubuh dinamis saat berjalan di atas papan titian tanpa jatuh.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Takut/perlu dipegangi kedua tangan',
                        2 => 'Berjalan pelan dipegangi satu tangan',
                        3 => 'Berjalan mandiri sedikit goyah',
                        4 => 'Keseimbangan sempurna dan percaya diri',
                    ],
                ],
                [
                    'id' => 'SD-EKS-06',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Melompat, meloncat, dan merayap',
                    'deskripsi' => 'Kekuatan dan kelincahan motorik kasar melalui rintangan melompat, meloncat, dan merayap.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Ragu-ragu / menolak melewati rintangan',
                        2 => 'Melewati rintangan dengan panduan penuh',
                        3 => 'Melewati rintangan mandiri',
                        4 => 'Lincah, tangkas, dan menguasai seluruh rintangan',
                    ],
                ],
                [
                    'id' => 'SD-EKS-07',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Gerak kombinasi tangan dan kaki',
                    'deskripsi' => 'Koordinasi bilateral anggota gerak tubuh dalam irama teratur.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Gerakan belum terkoordinasi',
                        2 => 'Koordinasi mulai tampak saat dipandu',
                        3 => 'Gerakan terkoordinasi dengan baik',
                        4 => 'Koordinasi sangat harmonis dan stabil',
                    ],
                ],
                [
                    'id' => 'SD-EKS-08',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Meniup mainan balon',
                    'deskripsi' => 'Kekuatan otot oral-motorik dan kapasitas pernapasan saat meniup.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum dapat memusatkan hembusan nafas',
                        2 => 'Meniup namun belum mengembang',
                        3 => 'Mampu meniup balon mengembang sedang',
                        4 => 'Hembusan kuat dan kontrol nafas optimal',
                    ],
                ],
                [
                    'id' => 'SD-EKS-09',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Duduk tertib dan tegap selama belajar',
                    'deskripsi' => 'Ketahanan postural (postural stability) dan ketertiban selama proses aktivitas berlangsung.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Mudah gelisah, sering meninggalkan kursi',
                        2 => 'Duduk tertib dengan beberapa teguran',
                        3 => 'Duduk tertib dan fokus cukup lama',
                        4 => 'Postur tegap, sangat tertib, dan fokus konsisten',
                    ],
                ],
                [
                    'id' => 'SD-EKS-10',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.1 Aspek Motorik',
                    'pos_id' => 'sd-eks-1',
                    'pos_label' => '3.1',
                    'nama' => 'Eye Contact',
                    'deskripsi' => 'Kontak mata fokus dan wajar saat berinteraksi atau menerima instruksi dari guru pengamat.',
                    'bobot' => 2.2,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menghindari kontak mata',
                        2 => 'Kontak mata sekilas / perlu dipanggil',
                        3 => 'Kontak mata wajar dan responsif',
                        4 => 'Kontak mata hangat, fokus, dan komunikatif',
                    ],
                ],
                // 3.2 Sosial Emosional (8 Pertanyaan Wawancara Transisi)
                [
                    'id' => 'SD-EKS-11',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Kalau pagi hari, apa saja yang biasanya kamu lakukan sendiri setelah bangun tidur sampai siap berangkat sekolah?',
                    'deskripsi' => 'Observasi kemandirian rutinitas pagi (mandi, pakai baju, sarapan).',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Semua masih disiapkan dan dibantu orang tua',
                        2 => 'Mampu 1 aktivitas sendiri',
                        3 => 'Mampu sebagian besar aktivitas mandiri',
                        4 => 'Mandiri penuh dan memiliki inisiatif pagi',
                    ],
                ],
                [
                    'id' => 'SD-EKS-12',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Kalau tiba-tiba di sekolah perutmu sakit dan ingin pup, apa yang akan kamu lakukan?',
                    'deskripsi' => 'Kemampuan toilet training mandiri dan inisiatif melapor ke guru.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menangis / diam menahan',
                        2 => 'Perlu ditanyai guru terlebih dahulu',
                        3 => 'Berani melapor ke guru',
                        4 => 'Percaya diri melapor dan mandiri ke toilet',
                    ],
                ],
                [
                    'id' => 'SD-EKS-13',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Kenapa ya mainan yang sudah selesai dipakai harus dirapikan lagi ke tempatnya? Apa yang terjadi kalau dibiarkan berantakan?',
                    'deskripsi' => 'Pemahaman tanggung jawab, kepedulian lingkungan, dan nalar sebab-akibat.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memahami alasan merapikan',
                        2 => 'Menjawab singkat karena disuruh',
                        3 => 'Memahami agar rapi dan tidak hilang',
                        4 => 'Nalar sebab-akibat sangat matang & peduli',
                    ],
                ],
                [
                    'id' => 'SD-EKS-14',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Kalau pulang sekolah, sepatu sebaiknya ditaruh di mana? Kenapa tidak boleh dilempar sembarangan?',
                    'deskripsi' => 'Kerapian, adab meletakkan barang pribadi, dan disiplin.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Belum memiliki kebiasaan merapikan',
                        2 => 'Mengetahui tempatnya jika diingatkan',
                        3 => 'Menjawab di rak sepatu agar rapi',
                        4 => 'Paham adab kerapian dan disiplin mandiri',
                    ],
                ],
                [
                    'id' => 'SD-EKS-15',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Pernah tidak kamu ingin beli mainan/es krim tapi Mama bilang "tidak boleh"? Waktu itu kamu ngapain? Menangis atau bagaimana?',
                    'deskripsi' => 'Regulasi emosi, toleransi terhadap penolakan/frustrasi, dan kemampuan mengendalikan diri.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Tantrum / menangis lama',
                        2 => 'Merajuk cukup lama lalu menurut',
                        3 => 'Menerima penolakan dengan penjelasan',
                        4 => 'Regulasi emosi sangat dewasa & pengertian',
                    ],
                ],
                [
                    'id' => 'SD-EKS-16',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Kalau kamu sedang bermain dengan temanmu, lalu jatuh dan lututmu sakit, kamu bakal bagaimana?',
                    'deskripsi' => 'Resiliensi, ekspresi emosi yang wajar, dan cara mencari bantuan.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Panik berlebihan / menyalahkan orang lain',
                        2 => 'Menangis dan pasif menunggu',
                        3 => 'Menenangkan diri dan mencari bantuan guru',
                        4 => 'Resiliensi tinggi, tenang, dan solutif',
                    ],
                ],
                [
                    'id' => 'SD-EKS-17',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Nanti di kelas, kalau kamu tidak bisa membuka tutup botol minummu, kamu mau minta tolong ke siapa? Bagaimana cara bilangnya?',
                    'deskripsi' => 'Komunikasi asertif, kesantunan (tolong & terima kasih), dan inisiatif sosial.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Diam / frustrasi sendiri',
                        2 => 'Menyodorkan botol tanpa bicara',
                        3 => 'Meminta tolong dengan sopan',
                        4 => 'Sangat santun mengucapkan tolong dan terima kasih',
                    ],
                ],
                [
                    'id' => 'SD-EKS-18',
                    'aspek' => 'Sentra Eksplorasi Diri',
                    'pos' => '3.2 Sosial Emosional',
                    'pos_id' => 'sd-eks-2',
                    'pos_label' => '3.2',
                    'nama' => 'Apakah kamu pernah bertengkar dengan teman atau saudara? Bagaimana caramu berbaikan jika sedang bertengkar dengan teman atau saudara?',
                    'deskripsi' => 'Keterampilan resolusi konflik sosial, empati, dan sikap pemaaf.',
                    'bobot' => 2.25,
                    'status' => 'Aktif',
                    'rubrik' => [
                        1 => 'Menyimpan dendam / memukul',
                        2 => 'Berbaikan hanya bila dipaksa dewasa',
                        3 => 'Mau meminta maaf / memaafkan',
                        4 => 'Empatik, berinisiatif damai, dan pemaaf',
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

        return $allIndikator[$jenjang] ?? $allIndikator['sd'];
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
