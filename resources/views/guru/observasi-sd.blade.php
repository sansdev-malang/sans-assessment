<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Observasi Guru Pengamat SD — SAANS Assessment</title>

        <!-- Theme Initialization Script -->
        <script>
            (function () {
                const mode = localStorage.getItem('saans-mode') || 'dark';
                if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts

        <!-- Styles & Scripts via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-background text-foreground antialiased font-sans flex flex-col justify-between pb-36 sm:pb-16">

        <!-- Top Navigation Bar -->
        <header class="border-b border-border bg-background/90 backdrop-blur sticky top-0 z-40 px-3 sm:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between gap-2">

                <!-- Left: Brand Logo & Title -->
                <div class="flex items-center gap-2.5 min-w-0">
                    <a href="{{ route('login') }}" class="flex items-center gap-2 shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-sm">
                            <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold tracking-tight text-foreground">SAANS</span>
                            <span class="text-[10px] text-muted-foreground -mt-0.5 hidden xs:inline font-mono">SPMB SD</span>
                        </div>
                    </a>

                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-muted-foreground pl-2 border-l border-border">
                        <span class="text-foreground/90 font-medium truncate">{{ $activeGuru['ruang'] ?? 'Sentra Observasi SD' }}</span>
                    </div>
                </div>

                <!-- Right: Active Observer Profile & Switcher -->
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    @php
                        $myColor = $activeGuru['color'] ?? 'emerald';
                        $sentraGurus = collect($allGuruSD ?? [])->where('kelompok_key', $activeGuru['kelompok_key'] ?? '');
                    @endphp

                    <div class="flex items-center gap-1.5 rounded-full border border-border bg-card px-2.5 py-1 text-xs">
                        <span class="h-2 w-2 rounded-full bg-{{ $myColor }}-400 animate-pulse"></span>
                        <span class="font-medium text-foreground text-[11px] max-w-[120px] sm:max-w-none truncate">{{ $activeGuru['name'] }}</span>
                        <span class="text-[10px] text-muted-foreground font-mono hidden md:inline">({{ $activeGuru['nip'] }})</span>
                    </div>

                    <x-theme-settings />

                    <a href="{{ route('logout') }}" title="Keluar" class="inline-flex h-8 w-8 sm:w-auto sm:px-2.5 items-center justify-center gap-1.5 rounded-lg border border-border bg-transparent text-xs font-medium text-muted-foreground hover:bg-card hover:text-foreground transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span class="hidden md:inline">Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Content Shell -->
        <main class="max-w-7xl w-full mx-auto px-3 sm:px-6 py-4 sm:py-6 flex-1 space-y-4 sm:space-y-5">

            <!-- Flash Notification -->
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-3 text-xs text-emerald-800 dark:text-emerald-300 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400"></i>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200 text-xs cursor-pointer font-bold">&times;</button>
                </div>
            @endif

            <!-- 1. IDENTITAS RUANG ASESMEN AKTIF & GURU PENGAMAT -->
            @php
                $avatarBg = match($myColor) {
                    'indigo', 'blue' => 'bg-indigo-600',
                    'amber'          => 'bg-amber-600',
                    default          => 'bg-emerald-600',
                };
                $sentraGurus = collect($allGuruSD ?? [])->where('kelompok_key', $activeGuru['kelompok_key'] ?? '');
            @endphp
            <div class="rounded-xl border border-border bg-card p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="h-10 w-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 {{ $avatarBg }} text-white shadow">
                        {{ $activeGuru['avatar'] ?? 'GP' }}
                    </span>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold text-foreground sm:text-sm">{{ $activeGuru['kelompok'] }}</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-semibold flex items-center gap-1">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Ruang Aktif</span>
                            </span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full border border-border bg-muted/60 text-muted-foreground font-medium">
                                Sistem 2 Co-Pengamat (Klaim &amp; Kunci Otomatis)
                            </span>
                        </div>
                        <p class="text-[11px] text-muted-foreground mt-0.5">
                            Pengamat Aktif: <strong class="text-foreground">{{ $activeGuru['name'] }}</strong> ({{ $activeGuru['nip'] }}) &bull; {{ $activeGuru['pos_desc'] }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto text-[11px] flex-wrap">
                    <span class="px-2.5 py-1 rounded-md bg-muted text-foreground/90 font-mono text-[10px] flex items-center gap-1.5 border border-border">
                        <i data-lucide="shield-check" class="h-3 w-3 text-emerald-400"></i>
                        <span>Locking Aktif</span>
                    </span>

                    <!-- {{-- Quick Switch Co-Pengamat di Sentra Ini (Memudahkan pengujian & rotasi guru) --}}
                    @if(isset($sentraGurus) && $sentraGurus->count() > 1)
                        <div class="flex items-center gap-1 bg-muted/60 p-1 rounded-lg border border-border text-[10px]">
                            <span class="text-muted-foreground px-1 hidden xs:inline">Simulasi Co-Pengamat:</span>
                            @foreach($sentraGurus as $sg)
                                @php $isCurrentG = ($sg['nip'] === $activeGuru['nip']); @endphp
                                <a 
                                    href="{{ route('guru.observasi', ['guru_nip' => $sg['nip'], 'siswa_no' => $activeSiswa['no'] ?? null]) }}" 
                                    class="px-2 py-0.5 rounded font-medium transition-colors {{ $isCurrentG ? 'bg-primary text-primary-foreground font-bold shadow-xs' : 'text-muted-foreground hover:text-foreground hover:bg-card' }}"
                                    title="{{ $sg['name'] }} ({{ $sg['nip'] }})"
                                >
                                    {{ explode(' ', $sg['name'])[0] }} ({{ $sg['nip'] }})
                                </a>
                            @endforeach
                        </div>
                    @endif -->
                </div>
            </div>

            <!-- 2. DAFTAR CALON SISWA (Dengan Status Klaim & Kunci) -->
            <div class="rounded-xl border border-border bg-card p-2.5 sm:p-3 space-y-2">
                <div class="flex items-center justify-between text-xs px-1">
                    <span class="font-semibold text-foreground flex items-center gap-1.5">
                        <i data-lucide="users" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span>Daftar Calon Murid di Ruang Ini:</span>
                    </span>
                    <div class="flex items-center gap-3 text-[10px] text-muted-foreground">
                        <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Siswa Anda</span>
                        <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Terkunci Co-Pengamat</span>
                        <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-zinc-400"></span> Belum Diklaim</span>
                    </div>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-1.5 pt-0.5 scrollbar-thin scrollbar-thumb-muted-foreground/20 snap-x" id="siswa-scroll-list">
                    @forelse($siswaSD as $s)
                        @php
                            $isActive = ($s['no'] === ($activeSiswa['no'] ?? ''));
                            $isDone   = $s['is_lengkap'] ?? false;
                            $sentraKey = $activeGuru['kelompok_key'] ?? 'ubudiyah';
                            $myScoreData = $s[$sentraKey] ?? [];

                            $pengamatNip = $myScoreData['guru_nip'] ?? null;
                            $pengamatNama = $myScoreData['guru_nama'] ?? null;
                            
                            $sIsMine = (!empty($pengamatNip) && $pengamatNip === $activeGuru['nip']);
                            $sIsLocked = (!empty($pengamatNip) && $pengamatNip !== $activeGuru['nip']);
                            $sIsAvailable = empty($pengamatNip);

                            $sudahDinilai = !empty($myScoreData['nilai']);
                            $nilaiRuang = $sudahDinilai ? number_format($myScoreData['nilai'], 1).'%' : null;
                        @endphp

                        @if($sIsLocked)
                            {{-- Siswa Terkunci oleh Co-Pengamat --}}
                            <div 
                                class="shrink-0 w-60 sm:w-64 p-3 rounded-xl border border-border/60 bg-muted/30 opacity-60 cursor-not-allowed select-none relative snap-start"
                                title="Siswa ini sedang diobservasi oleh rekan: {{ $pengamatNama }}"
                            >
                                <div class="flex items-start justify-between gap-1 mb-1.5">
                                    <div>
                                        <div class="font-semibold text-xs text-foreground truncate max-w-[130px] flex items-center gap-1">
                                            <i data-lucide="lock" class="h-3 w-3 text-amber-400 shrink-0"></i>
                                            <span>{{ $s['name'] }}</span>
                                        </div>
                                        <div class="font-mono text-[10px] text-muted-foreground">{{ $s['no'] }}</div>
                                    </div>
                                    <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium">
                                        Terkunci
                                    </span>
                                </div>

                                <div class="border-t border-border/50 pt-1.5 mt-1 text-[11px] space-y-0.5">
                                    <div class="text-[10px] text-amber-400/90 font-medium truncate flex items-center gap-1">
                                        <i data-lucide="user-x" class="h-3 w-3 shrink-0"></i>
                                        <span>Diuji: {{ $pengamatNama ? explode(',', $pengamatNama)[0] : 'Co-Pengamat' }}</span>
                                    </div>
                                    <div class="text-[9px] text-muted-foreground">
                                        Tidak dapat dipilih guru lain
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Siswa Tersedia atau Milik Saya --}}
                            <a 
                                href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $s['no']]) }}" 
                                class="shrink-0 w-60 sm:w-64 p-3 rounded-xl border text-left transition-all snap-start block {{ $isActive ? 'border-primary ring-2 ring-primary/40 bg-card shadow-md' : ($sIsMine ? 'border-emerald-500/50 bg-emerald-500/5 hover:bg-emerald-500/10' : 'border-dashed border-border bg-card/60 hover:bg-muted/40 hover:border-zinc-400') }}"
                            >
                                <div class="flex items-start justify-between gap-1 mb-1.5">
                                    <div>
                                        <div class="font-semibold text-xs text-foreground truncate max-w-[140px] sm:max-w-[150px]">{{ $s['name'] }}</div>
                                        <div class="font-mono text-[10px] text-muted-foreground">{{ $s['no'] }}</div>
                                    </div>
                                    @if($sIsMine)
                                        <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">
                                            Siswa Anda
                                        </span>
                                    @else
                                        <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-muted text-muted-foreground border border-border font-medium">
                                            + Tersedia
                                        </span>
                                    @endif
                                </div>

                                <div id="sd-carousel-status-{{ $s['no'] }}" class="border-t border-border/70 pt-1.5 mt-1 text-[11px] space-y-0.5">
                                    <div class="flex items-center gap-1 font-mono font-medium {{ $sudahDinilai ? 'text-emerald-400 font-semibold' : 'text-muted-foreground' }}">
                                        @if($sudahDinilai)
                                            <i data-lucide="check" class="h-3 w-3 text-emerald-400"></i>
                                            <span>Sudah Dinilai: {{ $nilaiRuang }}</span>
                                        @elseif($sIsMine)
                                            <i data-lucide="pencil" class="h-3 w-3 text-emerald-400"></i>
                                            <span class="text-emerald-400">Sedang Anda Observasi</span>
                                        @else
                                            <i data-lucide="plus-circle" class="h-3 w-3 text-muted-foreground"></i>
                                            <span>Klik untuk ambil siswa ini</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-muted-foreground truncate">
                                        Mulai: {{ $s['mulai_dari'] ?? 'Sentra Ubudiyah' }}
                                    </div>
                                </div>
                            </a>
                        @endif
                    @empty
                        <div class="w-full py-4 px-3 text-center text-xs text-muted-foreground bg-muted/20 rounded-lg border border-dashed border-border">
                            Belum ada calon siswa terdaftar. Silakan tambahkan atau import siswa di menu Admin Siswa.
                        </div>
                    @endforelse
                </div>
            </div>

            @if(!empty($activeSiswa))
            <!-- 3. PROFIL MURID & STATUS KELENGKAPAN 3 SENTRA -->
            @php
                $curSentraKey = $activeGuru['kelompok_key'] ?? 'ubudiyah';
                $curSentraData = $activeSiswa[$curSentraKey] ?? [];
                $curScore = !empty($curSentraData['nilai']) ? number_format($curSentraData['nilai'], 1).'%' : null;

                $currentPengamatNip = $curSentraData['guru_nip'] ?? null;
                $currentPengamatNama = $curSentraData['guru_nama'] ?? null;

                $isActiveLockedByOther = (!empty($currentPengamatNip) && $currentPengamatNip !== $activeGuru['nip']);
                $isActiveMine = (!empty($currentPengamatNip) && $currentPengamatNip === $activeGuru['nip']);
                $isActiveAvailable = empty($currentPengamatNip);
            @endphp

            <!-- Banner Notifikasi Kunci Siswa jika diobservasi guru lain -->
            @if($isActiveLockedByOther)
                <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-3 sm:p-4 flex items-start gap-3 text-xs text-amber-200">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/20 text-amber-400 shrink-0 mt-0.5">
                        <i data-lucide="lock" class="h-4 w-4"></i>
                    </div>
                    <div class="flex-1 space-y-1">
                        <h4 class="font-bold text-amber-300">Calon Siswa Terkunci (Sedang Diobservasi Co-Pengamat)</h4>
                        <p class="text-[11px] text-amber-200/80 leading-relaxed">
                            Ananda <strong>{{ $activeSiswa['name'] }}</strong> sedang / sudah diobservasi oleh rekan guru: 
                            <strong class="text-white">{{ $currentPengamatNama }}</strong>.
                            Untuk mencegah penilaian ganda, Anda hanya dapat melihat data nilai secara <em>read-only</em>.
                        </p>
                    </div>
                </div>
            @elseif($isActiveAvailable)
                <div class="rounded-xl border border-border bg-card p-3 sm:p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-muted text-muted-foreground shrink-0">
                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-foreground">Siswa ini belum memiliki pengamat di Sentra ini</span>
                            <p class="text-[11px] text-muted-foreground">Klaim siswa ini untuk mulai menguji dan mengunci siswa agar tidak diobservasi rekan lain.</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        onclick="klaimSiswaAktif('{{ $activeSiswa['no'] }}')" 
                        class="inline-flex h-8 items-center justify-center gap-1.5 rounded-lg bg-primary text-primary-foreground px-3.5 text-xs font-semibold shadow-xs hover:bg-primary/90 transition-colors cursor-pointer self-start sm:self-center shrink-0"
                    >
                        <i data-lucide="user-check" class="h-3.5 w-3.5"></i>
                        <span>Klaim Siswa Ini</span>
                    </button>
                </div>
            @else
                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-3.5 py-2.5 flex items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 text-emerald-300">
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                        <span>Siswa ini sedang dalam penugasan observasi Anda (Terkunci untuk rekan lain).</span>
                    </div>
                    @if(($curSentraData['status'] ?? '') !== 'Selesai')
                        <button 
                            type="button" 
                            onclick="lepasSiswaAktif('{{ $activeSiswa['no'] }}')" 
                            class="text-[11px] font-medium text-emerald-400 hover:text-emerald-200 underline cursor-pointer"
                        >
                            Lepas ke Antrean
                        </button>
                    @endif
                </div>
            @endif

            <div class="rounded-xl border border-border bg-card p-3 sm:p-5 space-y-4 shadow-sm">
                
                <!-- Student Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border">
                    <div class="flex items-start gap-3">
                        <div class="h-12 w-12 rounded-xl bg-muted border border-border flex items-center justify-center text-sm font-bold text-foreground shrink-0 shadow-inner">
                            {{ substr($activeSiswa['name'] ?? 'S', 0, 2) }}
                        </div>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-sm sm:text-base font-bold text-foreground">{{ $activeSiswa['name'] }}</h2>
                                <span class="font-mono text-[10px] px-2 py-0.5 rounded border border-border bg-muted text-muted-foreground font-semibold">
                                    {{ $activeSiswa['no'] }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                Target: <strong class="text-foreground">{{ $activeSiswa['kelas_target'] ?? 'Kelas 1' }}</strong>
                                &bull; Asal: {{ $activeSiswa['asal_sekolah'] ?? 'SD Alam Anak Saleh' }}
                                &bull; Wali: {{ $activeSiswa['wali'] ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Live Assessment Score in Current Sentra + Modal Trigger -->
                    <div class="flex items-center gap-3 self-end sm:self-center">
                        <div class="text-right">
                            <div class="text-[10px] text-muted-foreground font-mono">Nilai {{ $activeGuru['kelompok_short'] ?? 'Sentra Ini' }}:</div>
                            <div class="text-lg sm:text-xl font-bold font-mono text-emerald-400" id="header-score-live">
                                {{ $curScore ?: '—' }}
                            </div>
                        </div>

                        <button 
                            type="button" 
                            onclick="toggleRekapModal()" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border bg-muted hover:bg-zinc-700 text-foreground text-xs font-medium transition-colors cursor-pointer"
                        >
                            <i data-lucide="pie-chart" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>Rekap Nilai</span>
                        </button>
                    </div>
                </div>

                <!-- 3-SENTRA ROTATION MATRIX (Status Kelengkapan 3 Sentra) -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-foreground flex items-center gap-1.5">
                            <i data-lucide="check-square" class="h-3.5 w-3.5 text-emerald-400"></i>
                            <span>Status Kelengkapan 3 Sentra (Wajib Terpenuhi Semua):</span>
                        </span>
                        <span id="sd-matrix-progress-text" class="text-[11px] font-mono {{ ($activeSiswa['is_lengkap'] ?? false) ? 'text-emerald-400 font-bold' : 'text-amber-400' }}">
                            {{ $activeSiswa['selesai_count'] ?? 0 }}/3 Sentra Terpenuhi
                        </span>
                    </div>

                    <!-- 3 Sentra Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                        
                        <!-- 1. Sentra Ubudiyah -->
                        @php
                            $ubud = $activeSiswa['ubudiyah'] ?? [];
                            $isUbudActive = ($curSentraKey === 'ubudiyah');
                            $ubudDone = ($ubud['status'] ?? '') === 'Selesai';
                        @endphp
                        <div class="p-2.5 rounded-lg border {{ $isUbudActive ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 ring-1 ring-emerald-500/30' : 'border-border bg-card' }}">
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-semibold text-foreground">1. Sentra Ubudiyah</span>
                                <span id="sd-mini-score-ubudiyah" class="font-mono font-bold text-emerald-400">
                                    {{ !empty($ubud['nilai']) ? number_format($ubud['nilai'], 1).'%' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-muted-foreground">
                                <span>Pos 1.1–1.3</span>
                                <span id="sd-mini-status-ubudiyah" class="{{ $ubudDone ? 'text-emerald-400 font-medium' : 'text-muted-foreground' }}">
                                    {{ $ubudDone ? '✓ Selesai' : ($ubud['status'] ?? 'Belum Diuji') }}
                                </span>
                            </div>
                            @if(!empty($ubud['guru_nama']))
                                <div class="text-[9px] text-muted-foreground pt-1 truncate border-t border-border/40 mt-1">
                                    Pengamat: {{ explode(',', $ubud['guru_nama'])[0] }}
                                </div>
                            @endif
                        </div>

                        <!-- 2. Sentra Persiapan -->
                        @php
                            $pers = $activeSiswa['persiapan'] ?? [];
                            $isPersActive = ($curSentraKey === 'persiapan');
                            $persDone = ($pers['status'] ?? '') === 'Selesai';
                        @endphp
                        <div class="p-2.5 rounded-lg border {{ $isPersActive ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-950/30 ring-1 ring-indigo-500/30' : 'border-border bg-card' }}">
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-semibold text-foreground">2. Sentra Persiapan</span>
                                <span id="sd-mini-score-persiapan" class="font-mono font-bold text-indigo-400">
                                    {{ !empty($pers['nilai']) ? number_format($pers['nilai'], 1).'%' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-muted-foreground">
                                <span>Pos 2.1–2.4</span>
                                <span id="sd-mini-status-persiapan" class="{{ $persDone ? 'text-indigo-400 font-medium' : 'text-muted-foreground' }}">
                                    {{ $persDone ? '✓ Selesai' : ($pers['status'] ?? 'Belum Diuji') }}
                                </span>
                            </div>
                            @if(!empty($pers['guru_nama']))
                                <div class="text-[9px] text-muted-foreground pt-1 truncate border-t border-border/40 mt-1">
                                    Pengamat: {{ explode(',', $pers['guru_nama'])[0] }}
                                </div>
                            @endif
                        </div>

                        <!-- 3. Sentra Eksplorasi Diri -->
                        @php
                            $eks = $activeSiswa['eksplorasi'] ?? [];
                            $isEksActive = ($curSentraKey === 'eksplorasi');
                            $eksDone = ($eks['status'] ?? '') === 'Selesai';
                        @endphp
                        <div class="p-2.5 rounded-lg border {{ $isEksActive ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/30 ring-1 ring-amber-500/30' : 'border-border bg-card' }}">
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-semibold text-foreground">3. Sentra Eksplorasi Diri</span>
                                <span id="sd-mini-score-eksplorasi" class="font-mono font-bold text-amber-400">
                                    {{ !empty($eks['nilai']) ? number_format($eks['nilai'], 1).'%' : '—' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-muted-foreground">
                                <span>Pos 3.1–3.2</span>
                                <span id="sd-mini-status-eksplorasi" class="{{ $eksDone ? 'text-amber-400 font-medium' : 'text-muted-foreground' }}">
                                    {{ $eksDone ? '✓ Selesai' : ($eks['status'] ?? 'Belum Diuji') }}
                                </span>
                            </div>
                            @if(!empty($eks['guru_nama']))
                                <div class="text-[9px] text-muted-foreground pt-1 truncate border-t border-border/40 mt-1">
                                    Pengamat: {{ explode(',', $eks['guru_nama'])[0] }}
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Progress bar -->
                    @php
                        $doneCount = $activeSiswa['selesai_count'] ?? 0;
                        $progressPercent = min(100, round(($doneCount / 3) * 100));
                    @endphp
                    <div class="w-full bg-muted rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-muted-foreground pt-0.5">
                        <span>Mulai pertama dari: <strong class="text-foreground">{{ $activeSiswa['mulai_dari'] ?? 'Sentra Ubudiyah' }}</strong></span>
                        @if(($activeSiswa['is_lengkap'] ?? false))
                            <span class="text-emerald-400 font-semibold">✓ Seluruh Observasi Terpenuhi</span>
                        @else
                            <span class="text-amber-400">Siswa berotasi mandiri antar 3 sentra</span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- 4. FORM PENILAIAN INDIKATOR SENTRA INI -->
            @php
                $sentrasMeta = [
                    'ubudiyah'   => ['label' => 'Sentra Ubudiyah',        'color' => 'emerald', 'icon' => 'book-open'],
                    'persiapan'  => ['label' => 'Sentra Persiapan',       'color' => 'indigo',  'icon' => 'pencil'],
                    'eksplorasi' => ['label' => 'Sentra Eksplorasi Diri', 'color' => 'amber',   'icon' => 'sparkles'],
                ];
                $meta = $sentrasMeta[$curSentraKey] ?? $sentrasMeta['ubudiyah'];

                $indikatorMap = [
                    'ubudiyah' => [
                        ['id'=>'SD-UBUD-01','pos_id'=>'sd-ubud-1','pos'=>'1.1 Praktek Wudhu dan Sholat','nama'=>'Niat dan Gerakan dalam Berwudhu','bobot'=>3.5,'rubrik'=>[1=>'Belum menguasai urutan wudhu, perlu bimbingan penuh.',2=>'Menguasai sebagian urutan wudhu dengan sedikit panduan.',3=>'Melakukan urutan wudhu mandiri dengan tertib.',4=>'Praktik wudhu sangat baik, tertib, dan sesuai sunnah.']],
                        ['id'=>'SD-UBUD-02','pos_id'=>'sd-ubud-1','pos'=>'1.1 Praktek Wudhu dan Sholat','nama'=>'Niat dan Gerakan Sholat','bobot'=>3.5,'rubrik'=>[1=>'Gerakan sholat belum tertib, banyak gerakan di luar sholat.',2=>'Gerakan sholat cukup baik dengan beberapa arahan.',3=>'Gerakan sholat tertib dan tuma\'ninah cukup baik.',4=>'Gerakan sholat sangat tertib, tuma\'ninah terjaga sempurna.']],
                        ['id'=>'SD-UBUD-03','pos_id'=>'sd-ubud-1','pos'=>'1.1 Praktek Wudhu dan Sholat','nama'=>'Bacaan Sholat','bobot'=>3.0,'rubrik'=>[1=>'Belum menghafal bacaan sholat.',2=>'Menghafal sebagian bacaan dengan bantuan guru.',3=>'Menghafal sebagian besar bacaan dengan lancar.',4=>'Bacaan sholat lengkap, fasih, dan lancar.']],
                        ['id'=>'SD-UBUD-04','pos_id'=>'sd-ubud-2','pos'=>'1.2 Hafalan Doa Sehari-hari','nama'=>'Doa Sebelum Makan','bobot'=>2.5,'rubrik'=>[1=>'Belum hafal doa sebelum makan.',2=>'Hafal dengan dipandu kalimat per kalimat.',3=>'Hafal mandiri, sedikit terbata-bata.',4=>'Hafal lancar, fasih, dan beradab saat berdoa.']],
                        ['id'=>'SD-UBUD-05','pos_id'=>'sd-ubud-2','pos'=>'1.2 Hafalan Doa Sehari-hari','nama'=>'Doa Setelah Makan','bobot'=>2.5,'rubrik'=>[1=>'Belum hafal doa setelah makan.',2=>'Hafal dengan dipandu guru.',3=>'Hafal mandiri dengan lancar.',4=>'Hafal sangat lancar, fasih, dan penuh rasa syukur.']],
                        ['id'=>'SD-UBUD-06','pos_id'=>'sd-ubud-3','pos'=>'1.3 Hafalan Surat-Surat Pendek','nama'=>'Al-Fatihah','bobot'=>5.0,'rubrik'=>[1=>'Belum hafal lengkap Surat Al-Fatihah.',2=>'Hafal dengan bimbingan di beberapa ayat.',3=>'Hafal lengkap dan lancar.',4=>'Hafal sangat lancar, tajwid dan makhraj tepat.']],
                        ['id'=>'SD-UBUD-07','pos_id'=>'sd-ubud-3','pos'=>'1.3 Hafalan Surat-Surat Pendek','nama'=>'Al-Ikhlas','bobot'=>5.0,'rubrik'=>[1=>'Belum hafal Surat Al-Ikhlas.',2=>'Hafal 1–2 ayat awal.',3=>'Hafal lengkap mandiri.',4=>'Hafal sangat lancar, tartil, dan fasih.']],
                    ],
                    'persiapan' => [
                        ['id'=>'SD-PERS-01','pos_id'=>'sd-pers-1','pos'=>'2.1 Membaca (Pengenalan Huruf)','nama'=>'Melafalkan huruf A-Z secara urut (klasikal)','bobot'=>2.0,'rubrik'=>[1=>'Belum mengenal huruf A-Z.',2=>'Mengenal kurang dari 15 huruf.',3=>'Mengenal sebagian besar huruf dengan tertib.',4=>'Melafalkan A-Z secara urut, jelas, dan percaya diri.']],
                        ['id'=>'SD-PERS-02','pos_id'=>'sd-pers-1','pos'=>'2.1 Membaca (Pengenalan Huruf)','nama'=>'Membaca Suku Kata (individu)','bobot'=>2.0,'rubrik'=>[1=>'Belum dapat membaca suku kata.',2=>'Membaca suku kata dengan bimbingan.',3=>'Membaca suku kata sederhana mandiri.',4=>'Membaca suku kata lancar dan tepat.']],
                        ['id'=>'SD-PERS-03','pos_id'=>'sd-pers-1','pos'=>'2.1 Membaca (Pengenalan Huruf)','nama'=>'Membaca per Kata (individu)','bobot'=>2.0,'rubrik'=>[1=>'Belum dapat membaca kata.',2=>'Membaca kata satu per satu dengan bantuan.',3=>'Membaca beberapa kata mandiri.',4=>'Membaca kata lancar dan fasih.']],
                        ['id'=>'SD-PERS-04','pos_id'=>'sd-pers-2','pos'=>'2.2 Menulis / Dikte','nama'=>'Teknik Menulis','bobot'=>2.0,'rubrik'=>[1=>'Belum dapat memegang pensil dengan benar.',2=>'Memegang pensil namun tekanan tidak stabil.',3=>'Teknik menulis cukup baik dan terbaca.',4=>'Teknik menulis sangat baik, huruf rapi dan proporsional.']],
                        ['id'=>'SD-PERS-05','pos_id'=>'sd-pers-2','pos'=>'2.2 Menulis / Dikte','nama'=>'Menulis Nama Benda','bobot'=>2.0,'rubrik'=>[1=>'Belum dapat menulis nama benda.',2=>'Menulis dengan banyak kesalahan huruf.',3=>'Menulis nama benda dengan cukup baik.',4=>'Menulis nama benda dengan rapi dan benar.']],
                        ['id'=>'SD-PERS-06','pos_id'=>'sd-pers-3','pos'=>'2.3 Berhitung','nama'=>'Membilang Urut Maju (1-10)','bobot'=>1.5,'rubrik'=>[1=>'Belum dapat membilang urut 1-10.',2=>'Membilang dengan kesalahan urutan.',3=>'Membilang 1-10 dengan tertib.',4=>'Membilang 1-10 cepat, lantang, dan tepat.']],
                        ['id'=>'SD-PERS-07','pos_id'=>'sd-pers-3','pos'=>'2.3 Berhitung','nama'=>'Membilang Urut Mundur (10-1)','bobot'=>1.5,'rubrik'=>[1=>'Belum dapat membilang mundur.',2=>'Membilang mundur dengan banyak bantuan.',3=>'Membilang mundur dengan beberapa kesalahan.',4=>'Membilang mundur 10-1 dengan lancar.']],
                        ['id'=>'SD-PERS-08','pos_id'=>'sd-pers-3','pos'=>'2.3 Berhitung','nama'=>'Menuliskan Lambang Bilangan Secara Acak (1-10)','bobot'=>1.5,'rubrik'=>[1=>'Belum dapat menulis angka.',2=>'Menulis angka dengan beberapa terbalik.',3=>'Menulis sebagian besar angka dengan benar.',4=>'Menulis semua angka 1-10 dengan benar dan rapi.']],
                        ['id'=>'SD-PERS-09','pos_id'=>'sd-pers-3','pos'=>'2.3 Berhitung','nama'=>'Membilang Banyak Benda','bobot'=>1.5,'rubrik'=>[1=>'Belum dapat menghitung benda.',2=>'Menghitung dengan hasil tidak akurat.',3=>'Menghitung benda sederhana dengan benar.',4=>'Menghitung benda dengan akurat dan cepat.']],
                        ['id'=>'SD-PERS-10','pos_id'=>'sd-pers-3','pos'=>'2.3 Berhitung','nama'=>'Membandingkan Banyak dan Ukuran Benda','bobot'=>1.5,'rubrik'=>[1=>'Belum memahami konsep lebih banyak/sedikit.',2=>'Membandingkan dengan panduan konkret.',3=>'Membandingkan benda dengan benar.',4=>'Membandingkan dengan pemahaman konsep yang baik.']],
                        ['id'=>'SD-PERS-11','pos_id'=>'sd-pers-4','pos'=>'2.4 English','nama'=>'Listening about Colour','bobot'=>2.0,'rubrik'=>[1=>'Tidak merespons instruksi bahasa Inggris.',2=>'Merespons dengan bantuan.',3=>'Memahami dan merespons warna dengan benar.',4=>'Sangat responsif dan tepat mengenal warna.']],
                        ['id'=>'SD-PERS-12','pos_id'=>'sd-pers-4','pos'=>'2.4 English','nama'=>'Speaking (Introduction and Greeting)','bobot'=>2.0,'rubrik'=>[1=>'Tidak dapat memperkenalkan diri dalam bahasa Inggris.',2=>'Menyebut nama saja.',3=>'Memperkenalkan diri dengan kalimat sederhana.',4=>'Memperkenalkan diri dengan lancar dan percaya diri.']],
                        ['id'=>'SD-PERS-13','pos_id'=>'sd-pers-4','pos'=>'2.4 English','nama'=>'Vocabulary About Fruits','bobot'=>2.0,'rubrik'=>[1=>'Tidak mengenal kosakata buah.',2=>'Mengenal 1-3 kata buah.',3=>'Mengenal sebagian besar kosakata buah.',4=>'Mengenal semua kosakata buah dengan pengucapan tepat.']],
                        ['id'=>'SD-PERS-14','pos_id'=>'sd-pers-4','pos'=>'2.4 English','nama'=>'Vocabulary About Animals','bobot'=>2.0,'rubrik'=>[1=>'Tidak mengenal kosakata hewan.',2=>'Mengenal 1-3 kata hewan.',3=>'Mengenal sebagian kosakata hewan.',4=>'Mengenal semua kosakata hewan dengan pengucapan tepat.']],
                    ],
                    'eksplorasi' => [
                        ['id'=>'SD-EKS-01','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Koordinasi mata dan tangan (meronce)','bobot'=>2.2,'rubrik'=>[1=>'Perlu bantuan penuh',2=>'Dengan panduan berkala',3=>'Mandiri cukup rapi',4=>'Mandiri, presisi, dan cepat']],
                        ['id'=>'SD-EKS-02','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Finger Painting','bobot'=>2.2,'rubrik'=>[1=>'Menolak menyentuh cat/enggan',2=>'Mau menyentuh dengan dorongan',3=>'Aktif melukis dengan jari',4=>'Sangat antusias, kreatif, dan ekspresif']],
                        ['id'=>'SD-EKS-03','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Tracing dan Menggunting','bobot'=>2.2,'rubrik'=>[1=>'Belum dapat mengendalikan gunting',2=>'Menggunting terputus-putus di luar pola',3=>'Mengikuti garis pola dengan cukup baik',4=>'Menggunting presisi mengikuti garis rapi']],
                        ['id'=>'SD-EKS-04','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Mencocok Pola dengan Jarum','bobot'=>2.2,'rubrik'=>[1=>'Kurang hati-hati / belum terarah',2=>'Mencocok dengan jarak renggang',3=>'Mencocok rapat mengikuti pola',4=>'Sangat rapi, rapat, dan mudah dilepas']],
                        ['id'=>'SD-EKS-05','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Keseimbangan (meniti papan titian)','bobot'=>2.2,'rubrik'=>[1=>'Takut/perlu dipegangi kedua tangan',2=>'Berjalan pelan dipegangi satu tangan',3=>'Berjalan mandiri sedikit goyah',4=>'Keseimbangan sempurna dan percaya diri']],
                        ['id'=>'SD-EKS-06','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Melompat, meloncat, dan merayap','bobot'=>2.2,'rubrik'=>[1=>'Ragu-ragu / menolak melewati rintangan',2=>'Melewati rintangan dengan panduan penuh',3=>'Melewati rintangan mandiri',4=>'Lincah, tangkas, dan menguasai seluruh rintangan']],
                        ['id'=>'SD-EKS-07','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Gerak kombinasi tangan dan kaki','bobot'=>2.2,'rubrik'=>[1=>'Gerakan belum terkoordinasi',2=>'Koordinasi mulai tampak saat dipandu',3=>'Gerakan terkoordinasi dengan baik',4=>'Koordinasi sangat harmonis dan stabil']],
                        ['id'=>'SD-EKS-08','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Meniup mainan balon','bobot'=>2.2,'rubrik'=>[1=>'Belum dapat memusatkan hembusan nafas',2=>'Meniup namun belum mengembang',3=>'Mampu meniup balon mengembang sedang',4=>'Hembusan kuat dan kontrol nafas optimal']],
                        ['id'=>'SD-EKS-09','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Duduk tertib dan tegap selama belajar','bobot'=>2.2,'rubrik'=>[1=>'Mudah gelisah, sering meninggalkan kursi',2=>'Duduk tertib dengan beberapa teguran',3=>'Duduk tertib dan fokus cukup lama',4=>'Postur tegap, sangat tertib, dan fokus konsisten']],
                        ['id'=>'SD-EKS-10','pos_id'=>'sd-eks-1','pos'=>'3.1 Aspek Motorik','nama'=>'Eye Contact','bobot'=>2.2,'rubrik'=>[1=>'Menghindari kontak mata',2=>'Kontak mata sekilas / perlu dipanggil',3=>'Kontak mata wajar dan responsif',4=>'Kontak mata hangat, fokus, dan komunikatif']],
                        ['id'=>'SD-EKS-11','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Kalau pagi hari, apa saja yang biasanya kamu lakukan sendiri setelah bangun tidur sampai siap berangkat sekolah?','bobot'=>2.25,'rubrik'=>[1=>'Semua masih disiapkan orang tua',2=>'Mampu 1 aktivitas sendiri',3=>'Mampu sebagian besar mandiri',4=>'Mandiri penuh dan berinisiatif']],
                        ['id'=>'SD-EKS-12','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Kalau tiba-tiba di sekolah perutmu sakit dan ingin pup, apa yang akan kamu lakukan?','bobot'=>2.25,'rubrik'=>[1=>'Menangis / diam menahan',2=>'Perlu ditanyai guru terlebih dahulu',3=>'Berani melapor ke guru',4=>'Percaya diri melapor dan mandiri ke toilet']],
                        ['id'=>'SD-EKS-13','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Kenapa ya mainan yang sudah selesai dipakai harus dirapikan lagi ke tempatnya? Apa yang terjadi kalau dibiarkan berantakan?','bobot'=>2.25,'rubrik'=>[1=>'Belum memahami alasan merapikan',2=>'Menjawab singkat karena disuruh',3=>'Memahami agar rapi dan tidak hilang',4=>'Nalar sebab-akibat sangat matang & peduli']],
                        ['id'=>'SD-EKS-14','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Kalau pulang sekolah, sepatu sebaiknya ditaruh di mana? Kenapa tidak boleh dilempar sembarangan?','bobot'=>2.25,'rubrik'=>[1=>'Belum memiliki kebiasaan merapikan',2=>'Mengetahui tempatnya jika diingatkan',3=>'Menjawab di rak sepatu agar rapi',4=>'Paham adab kerapian dan disiplin mandiri']],
                        ['id'=>'SD-EKS-15','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Pernah tidak kamu ingin beli mainan/es krim tapi Mama bilang "tidak boleh"? Waktu itu kamu ngapain? Menangis atau bagaimana?','bobot'=>2.25,'rubrik'=>[1=>'Tantrum / menangis lama',2=>'Merajuk cukup lama lalu menurut',3=>'Menerima penolakan dengan penjelasan',4=>'Regulasi emosi sangat dewasa & pengertian']],
                        ['id'=>'SD-EKS-16','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Kalau kamu sedang bermain dengan temanmu, lalu jatuh dan lututmu sakit, kamu bakal bagaimana?','bobot'=>2.25,'rubrik'=>[1=>'Panik berlebihan / menyalahkan orang lain',2=>'Menangis dan pasif menunggu',3=>'Menenangkan diri dan mencari bantuan guru',4=>'Resiliensi tinggi, tenang, dan solutif']],
                        ['id'=>'SD-EKS-17','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Nanti di kelas, kalau kamu tidak bisa membuka tutup botol minummu, kamu mau minta tolong ke siapa? Bagaimana cara bilangnya?','bobot'=>2.25,'rubrik'=>[1=>'Diam / frustrasi sendiri',2=>'Menyodorkan barang tanpa bicara',3=>'Meminta tolong dengan sopan',4=>'Sangat santun mengucapkan tolong dan terima kasih']],
                        ['id'=>'SD-EKS-18','pos_id'=>'sd-eks-2','pos'=>'3.2 Sosial Emosional','nama'=>'Apakah kamu pernah bertengkar dengan teman atau saudara? Bagaimana caramu berbaikan jika sedang bertengkar dengan teman atau saudara?','bobot'=>2.25,'rubrik'=>[1=>'Menyimpan dendam / memukul',2=>'Berbaikan hanya bila dipaksa dewasa',3=>'Mau meminta maaf / memaafkan',4=>'Empatik, berinisiatif damai, dan pemaaf']],
                    ],
                ];

                $myIndikator = $indikatorMap[$curSentraKey] ?? [];
                $myIndikatorByPos = collect($myIndikator)->groupBy('pos_id');
                $savedDetail = $curSentraData['detail'] ?? [];
            @endphp

            <form id="form-observasi-sd" onsubmit="submitObservasiSD(event)" class="space-y-4">
                <input type="hidden" name="siswa_no" value="{{ $activeSiswa['no'] }}">
                <input type="hidden" name="kelompok_key" value="{{ $curSentraKey }}">

                <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden {{ $isActiveLockedByOther ? 'opacity-70' : '' }}">
                    
                    <!-- Section Header -->
                    <div class="p-3.5 sm:p-4 border-b border-border bg-muted/30 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-{{ $meta['color'] }}-500/10 text-{{ $meta['color'] }}-400 border border-{{ $meta['color'] }}-500/20">
                                <i data-lucide="{{ $meta['icon'] }}" class="h-4 w-4"></i>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-foreground">Form Penilaian: {{ $meta['label'] }}</h3>
                                <p class="text-[10px] text-muted-foreground">
                                    @if($isActiveLockedByOther)
                                        <span class="text-amber-400 font-semibold">Terkunci &bull; Pengamat: {{ $currentPengamatNama }}</span>
                                    @elseif($isActiveMine)
                                        <span class="text-emerald-400 font-semibold">Aktif &bull; Siswa ini Anda observasi</span>
                                    @else
                                        <span>Tersedia &bull; Belum diklaim</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full border border-border bg-card text-muted-foreground">
                                Skala Nilai: 1 – 4
                            </span>
                        </div>
                    </div>

                    <!-- Indikator Container -->
                    <fieldset {{ $isActiveLockedByOther ? 'disabled' : '' }} class="p-3 sm:p-5 space-y-6">
                        @foreach($myIndikatorByPos as $posId => $posItems)
                            @php $posLabel = $posItems->first()['pos'] ?? $posId; @endphp
                            <div class="space-y-3">
                                
                                <!-- Pos Sub-Header -->
                                <div class="flex items-center gap-2 pb-1.5 border-b border-border/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-{{ $meta['color'] }}-400"></span>
                                    <h4 class="text-xs font-bold text-foreground">{{ $posLabel }}</h4>
                                    <span class="text-[10px] font-mono text-muted-foreground">({{ $posItems->count() }} Indikator)</span>
                                </div>

                                <!-- Indikator List -->
                                <div class="space-y-3">
                                    @foreach($posItems as $idx => $ind)
                                        @php
                                            $savedScore = $savedDetail[$ind['id']] ?? null;
                                        @endphp
                                        <div class="rounded-xl border border-border/80 bg-muted/20 p-3 sm:p-3.5 space-y-2.5 transition-colors hover:bg-muted/40" data-indikator="{{ $ind['id'] }}">
                                            
                                            <!-- Title & Badge -->
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-start gap-2">
                                                    <span class="font-mono text-[11px] font-semibold text-muted-foreground mt-0.5 shrink-0">{{ $loop->iteration }}.</span>
                                                    <p class="text-xs font-medium text-foreground leading-relaxed">{{ $ind['nama'] }}</p>
                                                </div>
                                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-muted border border-border text-muted-foreground shrink-0">
                                                    Skor: <strong class="text-foreground" id="badge-score-{{ $ind['id'] }}">{{ $savedScore ?: '-' }}</strong>
                                                </span>
                                            </div>

                                            <!-- 4 Score Buttons -->
                                            <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                                @foreach([1, 2, 3, 4] as $val)
                                                    @php
                                                        $isSelected = ($savedScore == $val);
                                                        $activeClass = match($meta['color']) {
                                                            'indigo' => 'bg-indigo-600 text-white border-indigo-600 shadow-sm ring-2 ring-indigo-500/30',
                                                            'amber'  => 'bg-amber-600 text-white border-amber-600 shadow-sm ring-2 ring-amber-500/30',
                                                            default  => 'bg-emerald-600 text-white border-emerald-600 shadow-sm ring-2 ring-emerald-500/30',
                                                        };
                                                    @endphp
                                                    <button 
                                                        type="button" 
                                                        {{ $isActiveLockedByOther ? 'disabled' : '' }}
                                                        onclick="setScore('{{ $ind['id'] }}', {{ $val }}, this)" 
                                                        data-id="{{ $ind['id'] }}" 
                                                        data-score="{{ $val }}"
                                                        class="score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center {{ $isSelected ? $activeClass : 'border-border bg-card text-foreground hover:bg-muted active:scale-95' }} {{ $isActiveLockedByOther ? 'opacity-60 cursor-not-allowed' : '' }}"
                                                    >
                                                        {{ $val }}
                                                    </button>
                                                @endforeach
                                            </div>

                                            <!-- Live Rubrik Hint -->
                                            <div class="text-[11px] text-muted-foreground bg-card/60 border border-border/60 rounded-md px-2.5 py-1.5 min-h-[30px] flex items-center">
                                                <span class="rubrik-hint italic leading-relaxed" id="hint-{{ $ind['id'] }}">
                                                    {{ $savedScore ? ($ind['rubrik'][$savedScore] ?? '') : 'Pilih skor 1–4 untuk melihat rubrik deskripsi capaian ananda.' }}
                                                </span>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach

                        <!-- Catatan Anekdotal & Alasan Penilaian -->
                        <div class="rounded-xl border border-border bg-muted/20 p-3 sm:p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="input-catatan" class="text-xs font-bold text-foreground flex items-center gap-1.5">
                                    <i data-lucide="message-square" class="h-3.5 w-3.5 text-muted-foreground"></i>
                                    <span>Catatan Anekdotal &amp; Observasi Perilaku:</span>
                                </label>
                                <span class="text-[10px] text-muted-foreground">Opsional</span>
                            </div>
                            <textarea 
                                id="input-catatan" 
                                name="catatan" 
                                rows="3" 
                                {{ $isActiveLockedByOther ? 'disabled' : '' }}
                                placeholder="Tuliskan catatan khusus terkait karakter, kemandirian, hambatan, atau keistimewaan ananda selama observasi..." 
                                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary resize-y {{ $isActiveLockedByOther ? 'opacity-60 cursor-not-allowed' : '' }}"
                            >{{ $curSentraData['catatan'] ?? '' }}</textarea>
                        </div>
                    </fieldset>

                    <!-- Bottom Bar (Desktop) -->
                    <div class="hidden sm:flex border-t border-border bg-muted/30 p-3 sm:p-4 items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div>
                                <span class="text-[10px] text-muted-foreground font-mono">Skor Ruang Ini:</span>
                                <div class="text-lg font-bold font-mono text-emerald-400" id="bottom-score-desktop">
                                    {{ $curScore ?: '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(!empty($nextSiswa) && ($nextSiswa['no'] ?? '') !== ($activeSiswa['no'] ?? ''))
                                <a 
                                    href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $nextSiswa['no']]) }}" 
                                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors"
                                >
                                    <i data-lucide="arrow-right-left" class="h-3.5 w-3.5 text-muted-foreground"></i>
                                    <span>Siswa Lain</span>
                                </a>
                            @endif

                            @if($isActiveLockedByOther)
                                <button 
                                    type="button" 
                                    disabled
                                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-muted text-muted-foreground px-4 text-xs font-medium border border-border cursor-not-allowed opacity-70"
                                >
                                    <i data-lucide="lock" class="h-4 w-4"></i>
                                    <span>Terkunci (Co-Pengamat)</span>
                                </button>
                            @else
                                <button 
                                    type="submit" 
                                    id="btn-save-desktop" 
                                    class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white px-4 text-xs font-bold shadow transition-colors cursor-pointer"
                                >
                                    <i data-lucide="check" class="h-4 w-4"></i>
                                    <span>Simpan Nilai</span>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
            @else
                <div class="rounded-xl border border-border bg-card p-8 text-center space-y-3">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-foreground">Belum Ada Calon Siswa</h3>
                        <p class="text-xs text-muted-foreground max-w-sm mx-auto">
                            Saat ini belum ada calon siswa terdaftar untuk jenjang SD. Silakan tambahkan atau import data siswa melalui menu Admin Siswa.
                        </p>
                    </div>
                    @if(($user['role'] ?? '') === 'admin')
                        <a href="{{ route('admin.siswa', ['jenjang' => 'sd']) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors">
                            <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                            <span>Buka Admin Siswa SD</span>
                        </a>
                    @endif
                </div>
            @endif

        </main>

        @if(!empty($activeSiswa))
        <!-- STICKY BOTTOM ACTION BAR (OPTIMAL UNTUK SMARTPHONE SCREEN) -->
        <div id="mobile-bottom-bar" class="fixed bottom-0 inset-x-0 z-40 sm:hidden border-t border-border bg-card/95 backdrop-blur-md px-3 py-2.5 shadow-2xl transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-between gap-2">
                <!-- Left: Live Score pill -->
                <div class="flex flex-col">
                    <span class="text-[9px] text-muted-foreground font-mono">Skor Ruang Ini:</span>
                    <span class="text-sm font-bold font-mono text-emerald-400" id="bottom-score-badge">
                        {{ $curScore ?: '—' }}
                    </span>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-1.5">
                    @if(!empty($nextSiswa) && ($nextSiswa['no'] ?? '') !== ($activeSiswa['no'] ?? ''))
                        <a 
                            href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $nextSiswa['no']]) }}" 
                            class="inline-flex h-9 items-center justify-center px-2.5 rounded-lg border border-border bg-card text-foreground/90 text-[11px] font-medium"
                            title="Pilih Siswa Lain"
                        >
                            <i data-lucide="arrow-right-left" class="h-3.5 w-3.5 mr-1 text-muted-foreground"></i>
                            <span>Siswa Lain</span>
                        </a>
                    @endif

                    @if($isActiveLockedByOther ?? false)
                        <button 
                            type="button" 
                            disabled 
                            class="inline-flex h-9 items-center justify-center gap-1.5 px-3 rounded-lg bg-muted text-muted-foreground text-xs font-medium border border-border opacity-70 cursor-not-allowed"
                        >
                            <i data-lucide="lock" class="h-3.5 w-3.5"></i>
                            <span>Terkunci</span>
                        </button>
                    @else
                        <button 
                            type="button" 
                            onclick="document.getElementById('form-observasi-sd')?.requestSubmit()" 
                            id="btn-save-mobile"
                            class="inline-flex h-9 items-center justify-center gap-1.5 px-3.5 rounded-lg bg-emerald-600 text-white text-xs font-bold shadow active:scale-95 transition-transform cursor-pointer"
                        >
                            <i data-lucide="check" class="h-4 w-4"></i>
                            <span>Simpan Nilai</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- MODAL REKAP NILAI 3 SENTRA SD -->
        <div id="modal-rekap-backdrop" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden flex items-center justify-center p-3 sm:p-4">
            <div class="w-full max-w-lg rounded-xl border border-border bg-card text-foreground shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white text-xs font-bold">
                            <i data-lucide="pie-chart" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-foreground">Rekap Nilai Terpadu Siswa Baru SD</h3>
                            <p class="text-[11px] text-muted-foreground">{{ $activeSiswa['name'] ?? '-' }} ({{ $activeSiswa['no'] ?? '-' }})</p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleRekapModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <div class="p-4 space-y-3.5 max-h-[75vh] overflow-y-auto text-xs">
                    
                    <!-- 1. Sentra Ubudiyah -->
                    @php $u = $activeSiswa['ubudiyah'] ?? []; @endphp
                    <div class="rounded-lg border border-border bg-muted/20 p-3 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-foreground">1. Sentra Ubudiyah (Pos 1.1–1.3)</span>
                            <span id="sd-rekap-score-ubudiyah" class="font-mono font-bold text-emerald-400">
                                {{ !empty($u['nilai']) ? number_format($u['nilai'], 1).'%' : 'Belum Lengkap' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-muted-foreground">Praktek wudhu, sholat, hafalan doa harian, dan surat pendek.</p>
                        @if(!empty($u['guru_nama']))
                            <div class="text-[10px] text-muted-foreground font-mono">Pengamat: {{ $u['guru_nama'] }}</div>
                        @endif
                        @if(!empty($u['catatan']))
                            <p class="text-[11px] italic text-foreground/80 mt-1">&ldquo;{{ $u['catatan'] }}&rdquo;</p>
                        @endif
                    </div>

                    <!-- 2. Sentra Persiapan -->
                    @php $p = $activeSiswa['persiapan'] ?? []; @endphp
                    <div class="rounded-lg border border-border bg-muted/20 p-3 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-foreground">2. Sentra Persiapan (Pos 2.1–2.4)</span>
                            <span id="sd-rekap-score-persiapan" class="font-mono font-bold text-indigo-400">
                                {{ !empty($p['nilai']) ? number_format($p['nilai'], 1).'%' : 'Belum Lengkap' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-muted-foreground">Membaca huruf/kata, menulis dikte, berhitung 1-10, dan English basic.</p>
                        @if(!empty($p['guru_nama']))
                            <div class="text-[10px] text-muted-foreground font-mono">Pengamat: {{ $p['guru_nama'] }}</div>
                        @endif
                        @if(!empty($p['catatan']))
                            <p class="text-[11px] italic text-foreground/80 mt-1">&ldquo;{{ $p['catatan'] }}&rdquo;</p>
                        @endif
                    </div>

                    <!-- 3. Sentra Eksplorasi Diri -->
                    @php $e = $activeSiswa['eksplorasi'] ?? []; @endphp
                    <div class="rounded-lg border border-border bg-muted/20 p-3 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-foreground">3. Sentra Eksplorasi Diri (Pos 3.1–3.2)</span>
                            <span id="sd-rekap-score-eksplorasi" class="font-mono font-bold text-amber-400">
                                {{ !empty($e['nilai']) ? number_format($e['nilai'], 1).'%' : 'Belum Lengkap' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-muted-foreground">Kesiapan motorik halus/kasar, sensorik, dan wawancara transisi sosial-emosional.</p>
                        @if(!empty($e['guru_nama']))
                            <div class="text-[10px] text-muted-foreground font-mono">Pengamat: {{ $e['guru_nama'] }}</div>
                        @endif
                        @if(!empty($e['catatan']))
                            <p class="text-[11px] italic text-foreground/80 mt-1">&ldquo;{{ $e['catatan'] }}&rdquo;</p>
                        @endif
                    </div>

                    <!-- Nilai Akhir Terpadu -->
                    <div class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-3 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-xs text-foreground">Nilai Akhir Terpadu 3 Sentra:</span>
                            <div class="text-[10px] text-muted-foreground">Kesiapan Belajar Kelas 1 SD Alam</div>
                        </div>
                        <span id="sd-rekap-score-akhir" class="text-xl font-bold font-mono text-emerald-400">
                            {{ !empty($activeSiswa['nilai_akhir_terpadu']) ? number_format($activeSiswa['nilai_akhir_terpadu'], 1).'%' : 'Dalam Proses' }}
                        </span>
                    </div>

                </div>

                <div class="p-3 bg-muted/40 border-t border-border flex items-center justify-end">
                    <button type="button" onclick="toggleRekapModal()" class="px-3.5 py-1.5 rounded-md border border-border bg-card text-xs font-medium hover:bg-muted transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="border-t border-border py-4 px-6 text-center text-xs text-muted-foreground">
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Observasi Calon Siswa Baru SD Alam
        </footer>

        <!-- Javascript Logic -->
        <script>
            const rubrikData = @json($indikatorMap ?? []);
            const myKey      = @json($curSentraKey ?? 'ubudiyah');
            const myColor    = @json($meta['color'] ?? 'emerald');
            const isLocked   = @json($isActiveLockedByOther ?? false);
            
            const activeColorClass = {
                'indigo': 'bg-indigo-600 text-white border-indigo-600 shadow-sm ring-2 ring-indigo-500/30',
                'amber':  'bg-amber-600 text-white border-amber-600 shadow-sm ring-2 ring-amber-500/30',
                'emerald':'bg-emerald-600 text-white border-emerald-600 shadow-sm ring-2 ring-emerald-500/30',
            }[myColor] || 'bg-emerald-600 text-white border-emerald-600 shadow-sm ring-2 ring-emerald-500/30';

            const scores = {};

            // Pre-populate saved scores
            @if(!empty($curSentraData['detail']))
                @foreach($curSentraData['detail'] as $indId => $savedVal)
                    scores['{{ $indId }}'] = {{ (int)$savedVal }};
                @endforeach
            @endif

            function setScore(indId, val, btn) {
                if (isLocked) return;

                scores[indId] = val;

                // Update Button Visuals
                const group = btn.closest('[data-indikator]');
                group.querySelectorAll('.score-btn').forEach(b => {
                    b.className = 'score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center border-border bg-card text-foreground hover:bg-muted active:scale-95';
                });
                btn.className = `score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center ${activeColorClass}`;

                // Update Badge Score
                const badgeEl = document.getElementById('badge-score-' + indId);
                if (badgeEl) badgeEl.textContent = val;

                // Update Rubrik Hint
                const hintEl = document.getElementById('hint-' + indId);
                if (hintEl) {
                    const inds = rubrikData[myKey] || [];
                    const ind  = inds.find(i => i.id === indId);
                    hintEl.textContent = ind?.rubrik?.[val] ?? '';
                }

                updateLiveScore();
            }

            function updateLiveScore() {
                const inds = rubrikData[myKey] || [];
                if (inds.length === 0) return;

                let totalBobot = 0;
                let totalSkor = 0;
                let terisi = 0;

                inds.forEach(i => {
                    totalBobot += i.bobot;
                    if (scores[i.id]) {
                        totalSkor += (scores[i.id] / 4) * i.bobot;
                        terisi++;
                    }
                });

                if (terisi > 0 && totalBobot > 0) {
                    const persen = (totalSkor / totalBobot * 100).toFixed(1) + '%';
                    const hLive = document.getElementById('header-score-live');
                    const bDesktop = document.getElementById('bottom-score-desktop');
                    const bMobile = document.getElementById('bottom-score-badge');
                    if (hLive) hLive.textContent = persen;
                    if (bDesktop) bDesktop.textContent = persen;
                    if (bMobile) bMobile.textContent = persen;
                }
            }

            // Klaim Siswa Aktif
            async function klaimSiswaAktif(no = null) {
                const siswaNo = no || document.querySelector('input[name="siswa_no"]')?.value || @json($activeSiswa['no'] ?? '');
                if (!siswaNo) return;

                try {
                    const res = await fetch('{{ route("guru.observasi.klaim") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            siswa_no: siswaNo,
                            kelompok_key: myKey,
                        }),
                    });

                    const data = await res.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal mengklaim siswa.');
                    }
                } catch (e) {
                    alert('Gagal mengklaim: ' + e.message);
                }
            }

            // Lepas Siswa Aktif
            async function lepasSiswaAktif(no = null) {
                if (!confirm('Apakah Anda yakin ingin melepas siswa ini kembali ke antrean bersama?')) {
                    return;
                }

                const siswaNo = no || document.querySelector('input[name="siswa_no"]')?.value || @json($activeSiswa['no'] ?? '');
                if (!siswaNo) return;

                try {
                    const res = await fetch('{{ route("guru.observasi.lepas") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            siswa_no: siswaNo,
                            kelompok_key: myKey,
                        }),
                    });

                    const data = await res.json();
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal melepas siswa.');
                    }
                } catch (e) {
                    alert('Gagal melepas: ' + e.message);
                }
            }

            async function submitObservasiSD(e) {
                e.preventDefault();
                if (isLocked) {
                    alert('Siswa ini terkunci oleh rekan guru co-pengamat.');
                    return;
                }

                const form = e.target;
                const siswaNo = form.querySelector('[name="siswa_no"]')?.value;
                const kelompokKey = form.querySelector('[name="kelompok_key"]')?.value;
                const catatan = form.querySelector('[name="catatan"]')?.value || '';

                // Validasi kelengkapan seluruh indikator
                const inds = rubrikData[myKey] || [];
                const missing = inds.filter(i => !scores[i.id]);
                if (missing.length > 0) {
                    alert(`Perhatian: Masih ada ${missing.length} indikator yang belum dinilai. Mohon lengkapi skor 1–4 untuk seluruh indikator.`);
                    const firstMissing = document.querySelector(`[data-indikator="${missing[0].id}"]`);
                    firstMissing?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                const btnDesktop = document.getElementById('btn-save-desktop');
                const btnMobile = document.getElementById('btn-save-mobile');
                if (btnDesktop) { btnDesktop.disabled = true; btnDesktop.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i><span>Menyimpan...</span>'; }
                if (btnMobile) { btnMobile.disabled = true; btnMobile.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i><span>Menyimpan...</span>'; }

                try {
                    const response = await fetch('{{ route("guru.observasi.simpan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            siswa_no: siswaNo,
                            kelompok_key: kelompokKey,
                            scores: scores,
                            detail_nilai: scores,
                            catatan: catatan,
                            status: 'Selesai',
                        }),
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Kembalikan scroll ke posisi paling atas secara halus
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        // Tampilkan Toast Sukses
                        const toast = document.createElement('div');
                        toast.className = 'fixed top-16 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-950/90 backdrop-blur-md px-4 py-2.5 text-xs font-semibold text-emerald-300 shadow-2xl';
                        toast.innerHTML = `<svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Nilai berhasil disimpan!`;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 3500);

                        if (btnDesktop) { btnDesktop.innerHTML = '<svg class="h-4 w-4 inline mr-1 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>✓ Tersimpan</span>'; btnDesktop.disabled = false; }
                        if (btnMobile) { btnMobile.innerHTML = '<svg class="h-4 w-4 inline mr-1 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>✓ Tersimpan</span>'; btnMobile.disabled = false; }

                        // 1. Update Carousel Card Status
                        const carouselStatus = document.getElementById('sd-carousel-status-' + siswaNo);
                        if (carouselStatus) {
                            carouselStatus.innerHTML = `
                                <div class="flex items-center gap-1 font-mono font-medium text-emerald-400 font-semibold">
                                    <svg class="h-3 w-3 text-emerald-400 shrink-0 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Sudah Dinilai: ${data.nilai}%</span>
                                </div>
                                <div class="text-[10px] text-muted-foreground truncate">
                                    Status: Selesai
                                </div>
                            `;
                        }

                        // 2. Update Live Score in Header
                        const headerScore = document.getElementById('header-score-live');
                        if (headerScore) headerScore.textContent = data.nilai + '%';

                        // 3. Update 3-Sentra Rotation Matrix
                        const progressText = document.getElementById('sd-matrix-progress-text');
                        if (progressText) {
                            progressText.textContent = `${data.selesai_count}/3 Sentra Terpenuhi`;
                            if (data.is_lengkap) {
                                progressText.className = 'text-[11px] font-mono text-emerald-400 font-bold';
                            }
                        }

                        // 4. Update Current Sentra Score & Status
                        const miniScore = document.getElementById('sd-mini-score-' + kelompokKey);
                        if (miniScore) miniScore.textContent = data.nilai + '%';
                        const miniStatus = document.getElementById('sd-mini-status-' + kelompokKey);
                        if (miniStatus) {
                            miniStatus.textContent = '✓ Selesai';
                            miniStatus.className = 'text-emerald-400 font-medium';
                        }

                        // 5. Update Rekap Modal
                        const rekapScore = document.getElementById('sd-rekap-score-' + kelompokKey);
                        if (rekapScore) rekapScore.textContent = data.nilai + '%';

                        if (window.refreshLucideIcons) window.refreshLucideIcons();
                        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
                    } else {
                        throw new Error(data.message || 'Gagal menyimpan.');
                    }
                } catch (err) {
                    alert('Gagal menyimpan: ' + err.message);
                    if (btnDesktop) { btnDesktop.disabled = false; btnDesktop.innerHTML = '<svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>Simpan Nilai</span>'; }
                    if (btnMobile) { btnMobile.disabled = false; btnMobile.innerHTML = '<svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>Simpan Nilai</span>'; }
                }

                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function toggleRekapModal() {
                const modal = document.getElementById('modal-rekap-backdrop');
                if (modal) {
                    modal.classList.toggle('hidden');
                    if (window.refreshLucideIcons) window.refreshLucideIcons();
                }
            }

            // Keyboard Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.getElementById('modal-rekap-backdrop')?.classList.add('hidden');
                }
            });

            // Initial run
            updateLiveScore();
        </script>

    </body>
</html>
