<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>Observasi Guru Pengamat SMP — SAANS Assessment</title>
        
        <!-- Theme Initialization Script (Prevents FOUT) -->
        <script>
            (function () {
                const theme = localStorage.getItem('saans-theme') || 'amber';
                const mode = localStorage.getItem('saans-mode') || 'dark';
                const fontScale = localStorage.getItem('saans-font-scale') || '106.25%';
                const radius = localStorage.getItem('saans-radius') || '0.875rem';
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.fontSize = fontScale;
                document.documentElement.style.setProperty('--radius', radius);
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

        <style>
            /* Custom Hide Scrollbar for Horizontal Touch Strips */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
    </head>
    <body class="min-h-full bg-zinc-950 text-zinc-100 antialiased font-sans flex flex-col justify-between pb-24 sm:pb-8">

        <!-- Top Navigation Bar (Mobile Optimized) -->
        <header class="border-b border-zinc-800/80 bg-zinc-950/90 backdrop-blur sticky top-0 z-40 px-3 sm:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between gap-2">
                
                <!-- Left: Brand Logo & Title -->
                <div class="flex items-center gap-2.5 min-w-0">
                    <a href="{{ route('login') }}" class="flex items-center gap-2 shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-500/40 bg-emerald-950/50 text-emerald-400 font-bold text-xs shadow-sm">
                            <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold tracking-tight text-zinc-100">SAANS</span>
                            <span class="text-[10px] text-zinc-400 -mt-0.5 hidden xs:inline font-mono">SPMB SMP</span>
                        </div>
                    </a>

                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-zinc-500 pl-2 border-l border-zinc-800">
                        <span class="text-zinc-300 font-medium truncate">Ruang: {{ $activeGuru['ruang'] }}</span>
                    </div>
                </div>

                <!-- Right: Active Observer Profile & Quick Action -->
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <!-- Current Teacher Pill -->
                    <div class="flex items-center gap-1.5 rounded-full border border-zinc-800 bg-zinc-900/80 px-2.5 py-1 text-xs">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-medium text-zinc-200 text-[11px] max-w-[110px] sm:max-w-none truncate">{{ $activeGuru['name'] }}</span>
                    </div>

                    <a href="{{ route('admin.dashboard') }}" title="Dashboard Admin" class="inline-flex h-8 w-8 sm:w-auto sm:px-2.5 items-center justify-center gap-1.5 rounded-lg border border-zinc-800 bg-zinc-900 text-xs font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                        <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                        <span class="hidden md:inline">Admin</span>
                    </a>

                    <x-theme-settings />

                    <a href="{{ route('logout') }}" title="Keluar" class="inline-flex h-8 w-8 sm:w-auto sm:px-2.5 items-center justify-center gap-1.5 rounded-lg border border-zinc-800 bg-transparent text-xs font-medium text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100 transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span class="hidden md:inline">Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Content Shell -->
        <main class="max-w-7xl w-full mx-auto px-3 sm:px-6 py-4 sm:py-6 flex-1 space-y-4 sm:space-y-5">
            
            <!-- Notification Flash if any -->
            @if (session('status'))
                <div class="rounded-lg border border-emerald-900/60 bg-emerald-950/40 p-3 text-xs text-emerald-400 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="h-4 w-4 shrink-0 text-emerald-400"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 text-xs">&times;</button>
                </div>
            @endif

            <!-- ========================================================================= -->
            <!-- 0. PEDOMAN SISTEM: ROTASI TERBUKA (TANPA ANTREAN KAKU)                    -->
            <!-- ========================================================================= -->
            <div class="rounded-xl border border-blue-900/50 bg-blue-950/20 p-3 sm:p-3.5 flex items-start gap-2.5">
                <i data-lucide="compass" class="h-4 w-4 text-blue-400 shrink-0 mt-0.5"></i>
                <div class="text-xs leading-relaxed text-zinc-300">
                    <strong class="text-blue-300">Sistem Asesmen Rotasi Terbuka (Tanpa Antrean Kaku):</strong>
                    Calon siswa langsung menempati kelompok mana pun yang masih tersedia (boleh memulai dari <em>Pribadi &amp; Sosial</em>, <em>Akademik CBT</em>, <em>Bakat Minat</em>, maupun <em>Keagamaan</em>). Urutan fleksibel tidak harus berurutan, namun <strong>seluruh 4 kelompok asesmen wajib terpenuhi lengkap</strong> sebelum kelulusan.
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 1. GURU PENGAMAT SWITCHER STRIP (Horizontal Swipe on Smartphones)          -->
            <!-- ========================================================================= -->
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-2.5 sm:p-3 space-y-2">
                <div class="flex items-center justify-between text-xs px-1">
                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                        <i data-lucide="door-open" class="h-3.5 w-3.5 text-emerald-400"></i>
                        <span>Pilih Ruang / Guru Pengamat SMP (4 Kelompok):</span>
                    </span>
                    <span class="text-[10px] text-zinc-500 font-mono hidden sm:inline">Pilih ruang untuk menilai</span>
                </div>

                <!-- Touch-friendly Horizontal Scrolling Pill Row -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1 pt-0.5">
                    @foreach ($allGuruSMP as $nip => $guru)
                        @php
                            $isActive = ($activeGuru['nip'] === $nip);
                        @endphp
                        <a 
                            href="{{ route('guru.observasi', ['guru_nip' => $nip, 'siswa_no' => $activeSiswa['no']]) }}" 
                            class="flex-shrink-0 flex items-center gap-2 px-3 py-2 rounded-lg border text-left transition-all text-xs
                                {{ $isActive 
                                    ? 'border-emerald-500 bg-emerald-950/30 text-zinc-100 shadow-sm ring-1 ring-emerald-500/50' 
                                    : 'border-zinc-800 bg-zinc-950/60 text-zinc-400 hover:text-zinc-200 hover:border-zinc-700' }}"
                        >
                            <span class="h-6 w-6 rounded-md flex items-center justify-center font-bold text-[11px] shrink-0
                                {{ $isActive ? 'bg-emerald-500 text-zinc-950' : 'bg-zinc-800 text-zinc-300' }}">
                                {{ $guru['avatar'] }}
                            </span>
                            <div class="truncate max-w-[150px] sm:max-w-none">
                                <div class="font-semibold truncate text-[11px] sm:text-xs {{ $isActive ? 'text-zinc-100' : 'text-zinc-300' }}">
                                    {{ $guru['kelompok_short'] }}
                                </div>
                                <div class="text-[10px] text-zinc-500 truncate">
                                    {{ $guru['name'] }}
                                </div>
                            </div>
                            @if($isActive)
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. DAFTAR CALON SISWA & STATUS ROTASI (Tanpa Antre)                       -->
            <!-- ========================================================================= -->
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/60 p-2.5 sm:p-3 space-y-2">
                <div class="flex items-center justify-between text-xs px-1">
                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                        <i data-lucide="users" class="h-3.5 w-3.5 text-blue-400"></i>
                        <span>Daftar Calon Murid (Pilih Siswa yang Sedang Berada di Ruang Ini):</span>
                    </span>
                    <span class="text-[10px] font-mono text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded-full">
                        {{ count($siswaSMP) }} Siswa
                    </span>
                </div>

                <!-- Horizontal Candidate Carousel -->
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1 pt-0.5">
                    @foreach ($siswaSMP as $s)
                        @php
                            $isSelected = ($activeSiswa['no'] === $s['no']);
                            $kKey = $activeGuru['kelompok_key'];
                            $kData = $s[$kKey] ?? [];
                            $statusRuangIni = $kData['status'] ?? 'Belum Diuji';
                            $scoreVal = $kData['nilai'] ?? '-';
                            $isCompleteAll = ($s['is_lengkap'] ?? false);
                            $selesaiCount = ($s['selesai_count'] ?? 0);
                        @endphp
                        <a 
                            href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $s['no']]) }}" 
                            class="flex-shrink-0 flex flex-col p-2.5 rounded-xl border text-left transition-all min-w-[190px] sm:min-w-[210px]
                                {{ $isSelected 
                                    ? 'border-blue-500 bg-blue-950/25 ring-1 ring-blue-500/40 shadow-sm' 
                                    : 'border-zinc-800/80 bg-zinc-950/60 hover:bg-zinc-800/50' }}"
                        >
                            <div class="flex items-start justify-between gap-1">
                                <div class="truncate">
                                    <div class="font-bold text-xs truncate {{ $isSelected ? 'text-zinc-100' : 'text-zinc-300' }}">
                                        {{ $s['name'] }}
                                    </div>
                                    <div class="text-xs text-zinc-400 font-mono mt-0.5 truncate">
                                        {{ $s['no'] }}
                                    </div>
                                </div>
                                @if($isCompleteAll)
                                    <span class="shrink-0 px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-950 border border-emerald-800 text-emerald-400">
                                        4/4 Lengkap
                                    </span>
                                @else
                                    <span class="shrink-0 px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-950 border border-amber-800 text-amber-400">
                                        {{ $selesaiCount }}/4 Kelompok
                                    </span>
                                @endif
                            </div>

                            <!-- Status di Ruang Aktif Ini -->
                            <div class="mt-2 pt-1.5 border-t border-zinc-800/60 flex items-center justify-between text-[10px]">
                                @if($statusRuangIni === 'Selesai')
                                    <span class="text-emerald-400 font-semibold flex items-center gap-1">
                                        <i data-lucide="check" class="h-3 w-3"></i>
                                        <span>Sudah Dinilai: {{ $scoreVal }}%</span>
                                    </span>
                                @elseif($statusRuangIni === 'Sedang Diobservasi')
                                    <span class="text-amber-400 font-semibold flex items-center gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        <span>Di Ruang Ini</span>
                                    </span>
                                @else
                                    <span class="text-zinc-400 flex items-center gap-1">
                                        <span>○ Belum Masuk</span>
                                    </span>
                                @endif
                            </div>

                            <div class="text-[9px] text-zinc-500 truncate mt-1">
                                Mulai: <span class="text-zinc-400">{{ $s['mulai_dari'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 3. ACTIVE STUDENT QUICK CARD & 4-STATION ROTATION MATRIX                   -->
            <!-- ========================================================================= -->
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/80 p-3.5 sm:p-4 shadow-sm space-y-3">
                
                <!-- Candidate Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-zinc-800/80">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-zinc-800 border border-zinc-700 flex items-center justify-center text-sm font-bold text-zinc-100 shrink-0">
                            {{ substr($activeSiswa['name'], 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-sm sm:text-base font-bold text-zinc-100">{{ $activeSiswa['name'] }}</h2>
                                <span class="font-mono text-[10px] px-1.5 py-0.5 rounded border border-zinc-700 bg-zinc-950 text-zinc-300">
                                    {{ $activeSiswa['no'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-zinc-400 mt-0.5">
                                Target: <strong class="text-zinc-200">{{ $activeSiswa['kelas_target'] }}</strong> &bull; Asal: {{ $activeSiswa['asal_sekolah'] }} &bull; Wali: {{ $activeSiswa['wali'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Right / Top Mobile: Live Score of Active Room & Modal Trigger -->
                    <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-zinc-800/80">
                        <div class="text-left sm:text-right">
                            <div class="text-[10px] text-zinc-400 font-mono">Nilai {{ $activeGuru['kelompok_short'] }}:</div>
                            @php
                                $kKey = $activeGuru['kelompok_key'];
                                $valNow = $activeSiswa[$kKey]['nilai'] ?? 92.5;
                            @endphp
                            <div class="text-base sm:text-lg font-bold font-mono text-emerald-400 flex items-center gap-1.5 sm:justify-end">
                                <span id="header-live-score">{{ $valNow ? $valNow.'%' : 'Belum Dinilai' }}</span>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            onclick="toggleRekapModal()" 
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-zinc-700 bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-medium transition-colors"
                        >
                            <i data-lucide="pie-chart" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>Rekap Nilai</span>
                        </button>
                    </div>
                </div>

                <!-- 4-STATION ROTATION MATRIX (Status Kelengkapan Seluruh Kelompok) -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                            <i data-lucide="check-square" class="h-3.5 w-3.5 text-emerald-400"></i>
                            <span>Status Kelengkapan 4 Kelompok (Wajib Terpenuhi Semua):</span>
                        </span>
                        <span class="text-[11px] font-mono {{ ($activeSiswa['is_lengkap'] ?? false) ? 'text-emerald-400 font-bold' : 'text-amber-400' }}">
                            {{ $activeSiswa['selesai_count'] ?? 0 }}/4 Kelompok Terpenuhi
                        </span>
                    </div>

                    <!-- 4 Mini Stations Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 text-xs">
                        
                        <!-- 1. Keagamaan -->
                        <div class="p-2.5 rounded-lg border {{ ($activeGuru['kelompok_key'] === 'keagamaan') ? 'border-emerald-500 bg-emerald-950/20' : 'border-zinc-800 bg-zinc-950/60' }}">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-medium text-zinc-300">1. Keagamaan</span>
                                @if(($activeSiswa['keagamaan']['status'] ?? '') === 'Selesai')
                                    <span class="text-emerald-400 font-bold font-mono">{{ $activeSiswa['keagamaan']['nilai'] }}%</span>
                                @elseif(($activeSiswa['keagamaan']['status'] ?? '') === 'Sedang Diobservasi')
                                    <span class="text-amber-400 text-[10px] animate-pulse">Sedang Uji</span>
                                @else
                                    <span class="text-zinc-500 text-[10px]">Belum</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-zinc-400 mt-1 flex items-center gap-1">
                                @if(($activeSiswa['keagamaan']['status'] ?? '') === 'Selesai')
                                    <span class="text-emerald-400">✓ Selesai</span>
                                @else
                                    <span class="text-zinc-500">Pos 1–4</span>
                                @endif
                            </div>
                        </div>

                        <!-- 2. Akademik CBT -->
                        <div class="p-2.5 rounded-lg border {{ ($activeGuru['kelompok_key'] === 'akademik') ? 'border-blue-500 bg-blue-950/20' : 'border-zinc-800 bg-zinc-950/60' }}">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-medium text-zinc-300">2. Akademik CBT</span>
                                @if(($activeSiswa['akademik']['status'] ?? '') === 'Selesai')
                                    <span class="text-blue-400 font-bold font-mono">{{ $activeSiswa['akademik']['nilai'] }}%</span>
                                @elseif(($activeSiswa['akademik']['status'] ?? '') === 'Sedang Diobservasi')
                                    <span class="text-amber-400 text-[10px] animate-pulse">Sedang Uji</span>
                                @else
                                    <span class="text-zinc-500 text-[10px]">Belum</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-zinc-400 mt-1 flex items-center gap-1">
                                @if(($activeSiswa['akademik']['status'] ?? '') === 'Selesai')
                                    <span class="text-blue-400">✓ Selesai</span>
                                @else
                                    <span class="text-zinc-500">Pos 5 CBT</span>
                                @endif
                            </div>
                        </div>

                        <!-- 3. Bakat Minat -->
                        <div class="p-2.5 rounded-lg border {{ ($activeGuru['kelompok_key'] === 'bakat_minat') ? 'border-purple-500 bg-purple-950/20' : 'border-zinc-800 bg-zinc-950/60' }}">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-medium text-zinc-300">3. Bakat Minat</span>
                                @if(($activeSiswa['bakat_minat']['status'] ?? '') === 'Selesai')
                                    <span class="text-purple-400 font-bold font-mono">{{ $activeSiswa['bakat_minat']['nilai'] }}%</span>
                                @elseif(($activeSiswa['bakat_minat']['status'] ?? '') === 'Sedang Diobservasi')
                                    <span class="text-amber-400 text-[10px] animate-pulse">Sedang Uji</span>
                                @else
                                    <span class="text-zinc-500 text-[10px]">Belum</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-zinc-400 mt-1 flex items-center gap-1">
                                @if(($activeSiswa['bakat_minat']['status'] ?? '') === 'Selesai')
                                    <span class="text-purple-400">✓ Selesai</span>
                                @else
                                    <span class="text-zinc-500">Pos 6–7</span>
                                @endif
                            </div>
                        </div>

                        <!-- 4. Pribadi Sosial -->
                        <div class="p-2.5 rounded-lg border {{ ($activeGuru['kelompok_key'] === 'pribadi_sosial') ? 'border-amber-500 bg-amber-950/20' : 'border-zinc-800 bg-zinc-950/60' }}">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-medium text-zinc-300">4. Pribadi &amp; Sosial</span>
                                @if(($activeSiswa['pribadi_sosial']['status'] ?? '') === 'Selesai')
                                    <span class="text-amber-400 font-bold font-mono">{{ $activeSiswa['pribadi_sosial']['nilai'] }}%</span>
                                @elseif(($activeSiswa['pribadi_sosial']['status'] ?? '') === 'Sedang Diobservasi')
                                    <span class="text-amber-400 text-[10px] animate-pulse">Sedang Uji</span>
                                @else
                                    <span class="text-zinc-500 text-[10px]">Belum</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-zinc-400 mt-1 flex items-center gap-1">
                                @if(($activeSiswa['pribadi_sosial']['status'] ?? '') === 'Selesai')
                                    <span class="text-amber-400">✓ Selesai</span>
                                @else
                                    <span class="text-zinc-500">Pos 8 / BK</span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Progress bar -->
                    <div class="w-full bg-zinc-800 h-1.5 rounded-full overflow-hidden mt-1">
                        <div class="h-full bg-emerald-500 transition-all duration-300" style="width: {{ (($activeSiswa['selesai_count'] ?? 0) / 4) * 100 }}%"></div>
                    </div>

                    <div class="flex items-center justify-between text-[10px] text-zinc-400">
                        <span>Mulai pertama dari: <strong class="text-zinc-200">{{ $activeSiswa['mulai_dari'] }}</strong></span>
                        @if($activeSiswa['is_lengkap'] ?? false)
                            <span class="text-emerald-400 font-semibold">✓ Seluruh Observasi Terpenuhi</span>
                        @else
                            <span class="text-amber-400">Tersisa {{ 4 - ($activeSiswa['selesai_count'] ?? 0) }} kelompok lagi</span>
                        @endif
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- 4. MAIN ASSESSMENT WORKSPACE                                              -->
            <!-- ========================================================================= -->
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/50 p-3.5 sm:p-6 space-y-5">
                
                <!-- ===================================================================== -->
                <!-- KELOMPOK 1: KEAGAMAAN (Ust. Hadi Susanto - Pos 1 s/d 4)               -->
                <!-- ===================================================================== -->
                @if($activeGuru['kelompok_key'] === 'keagamaan')
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-zinc-800">
                            <div>
                                <h3 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                                    <i data-lucide="book-open" class="h-4 w-4 text-emerald-400"></i>
                                    <span>Lembar Penilaian Kelompok Keagamaan</span>
                                </h3>
                                <p class="text-xs text-zinc-400">Pilih pos di bawah, lalu ketuk skor 1, 2, 3, atau 4 pada tiap aspek.</p>
                            </div>
                            <span class="text-xs font-mono px-2.5 py-1 rounded-md bg-emerald-950 border border-emerald-700 text-emerald-300 font-semibold self-start sm:self-auto">
                                Skor Total: <span id="keagamaan-total-badge">{{ $activeSiswa['keagamaan']['total_skor'] ?? 100 }}</span> / 108
                            </span>
                        </div>

                        <!-- Swipeable Sub-Tabs Pos 1 s/d 4 -->
                        <div class="flex rounded-lg border border-zinc-800 bg-zinc-950 p-1 text-xs font-medium gap-1 overflow-x-auto no-scrollbar">
                            <button type="button" onclick="switchPosTab('pos1')" id="tab-btn-pos1" class="pos-tab-btn flex-1 min-w-[110px] py-2 px-2.5 rounded-md bg-zinc-800 text-zinc-100 font-bold shadow text-center">
                                Pos 1: Sholat (7)
                            </button>
                            <button type="button" onclick="switchPosTab('pos2')" id="tab-btn-pos2" class="pos-tab-btn flex-1 min-w-[110px] py-2 px-2.5 rounded-md text-zinc-400 hover:text-zinc-200 text-center">
                                Pos 2: Mengaji (7)
                            </button>
                            <button type="button" onclick="switchPosTab('pos3')" id="tab-btn-pos3" class="pos-tab-btn flex-1 min-w-[110px] py-2 px-2.5 rounded-md text-zinc-400 hover:text-zinc-200 text-center">
                                Pos 3: Doa (6)
                            </button>
                            <button type="button" onclick="switchPosTab('pos4')" id="tab-btn-pos4" class="pos-tab-btn flex-1 min-w-[110px] py-2 px-2.5 rounded-md text-zinc-400 hover:text-zinc-200 text-center">
                                Pos 4: Surat (7)
                            </button>
                        </div>

                        <!-- POS 1 PANEL: SHOLAT -->
                        <div id="pos-panel-pos1" class="pos-panel space-y-3">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 1: Asesmen Ibadah Sholat</strong> &bull; 7 Aspek
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">Maks = 28</span>
                            </div>

                            @php
                                $pos1Aspek = [
                                    ['name' => '1. Wudhu - Praktik', 'desc' => 'Urutan dan pelaksanaan gerakan wudhu secara langsung.', 'default' => 4, 'rubrik' => [1 => 'Belum mampu melakukan urutan dengan benar', 2 => 'Beberapa tahapan belum tepat', 3 => 'Ada sedikit kekeliruan', 4 => 'Urutan dan pelaksanaan tepat']],
                                    ['name' => '2. Wudhu - Ketentuan', 'desc' => 'Ketentuan dan rukun sahnya berwudhu.', 'default' => 4, 'rubrik' => [1 => 'Belum sesuai dengan ketentuan dalam sahnya berwudhu', 2 => 'Ada ketentuan dan sahnya berwudhu beberapa kurang', 3 => 'Ketentuan dan sahnya berwudhu sedikit kurang lengkap', 4 => 'Ketentuan dan sahnya berwudhu lengkap']],
                                    ['name' => '3. Gerakan sholat', 'desc' => 'Urutan dan kesesuaian gerakan sholat wajib.', 'default' => 4, 'rubrik' => [1 => 'Belum mampu mengikuti urutan', 2 => 'Beberapa gerakan perlu arahan', 3 => 'Ada sedikit kekeliruan', 4 => 'Urutan dan gerakan tepat']],
                                    ['name' => '4. Bacaan sholat', 'desc' => 'Kelancaran bacaan sholat (iftitah, ruku, sujud, tasyahud).', 'default' => 3, 'rubrik' => [1 => 'Belum mampu melafalkan sebagian besar bacaan', 2 => 'Belum lancar', 3 => 'Ada sedikit kesalahan', 4 => 'Lancar dan tepat']],
                                    ['name' => '5. Pemahaman', 'desc' => 'Pemahaman mengenai makna dan rukun sholat.', 'default' => 4, 'rubrik' => [1 => 'Belum memahami konsep dasar', 2 => 'Memahami sebagian', 3 => 'Sebagian besar tepat', 4 => 'Mampu menjelaskan dengan tepat']],
                                    ['name' => '6. Ketertiban', 'desc' => 'Kemandirian dan ketertiban selama praktik sholat.', 'default' => 4, 'rubrik' => [1 => 'Memerlukan pendampingan intensif', 2 => 'Memerlukan beberapa arahan', 3 => 'Sedikit memerlukan arahan', 4 => 'Praktik tertib dan mandiri']],
                                    ['name' => '7. Sholat Wajib', 'desc' => 'Penjelasan syarat sah dan rukun sholat wajib.', 'default' => 3, 'rubrik' => [1 => 'Tidak menjelaskan syarat sah sholat dengan lengkap', 2 => 'Penjelasan syarat sah sholat beberapa tidak lengkap', 3 => 'Penjelasan syarat sah sholat sedikit kurang lengkap', 4 => 'Penjelasan syarat sah sholat lengkap']],
                                ];
                            @endphp

                            @foreach($pos1Aspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/60 border border-emerald-800/80 shrink-0" id="label-val-p1-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <!-- Touch-Friendly 4-Pill Segmented Score Buttons -->
                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p1-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-p1-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-emerald-500 text-zinc-950 border-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <!-- Full Rubric Text Box for Active Score -->
                                    <div id="desc-box-p1-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-emerald-400" id="desc-title-p1-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-p1-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- POS 2 PANEL: MENGAJI -->
                        <div id="pos-panel-pos2" class="pos-panel space-y-3 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 2: Asesmen Ibadah Mengaji</strong> &bull; 7 Aspek
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">Maks = 28</span>
                            </div>

                            @php
                                $pos2Aspek = [
                                    ['name' => '1. Huruf', 'desc' => 'Pengenalan dan pelafalan huruf hijaiyah.', 'default' => 4, 'rubrik' => [1 => 'Belum mengenali sebagian besar', 2 => 'Beberapa kesalahan', 3 => 'Sedikit kesalahan', 4 => 'Seluruhnya tepat']],
                                    ['name' => '2. Harakat', 'desc' => 'Ketepatan pembacaan tanda baca harakat.', 'default' => 4, 'rubrik' => [1 => 'Belum mengenali sebagian besar', 2 => 'Beberapa kesalahan', 3 => 'Sedikit kesalahan', 4 => 'Seluruhnya tepat']],
                                    ['name' => '3. Huruf Bersambung', 'desc' => 'Ketepatan membaca huruf hijaiyah bersambung.', 'default' => 3, 'rubrik' => [1 => 'Memerlukan bimbingan dasar', 2 => 'Beberapa huruf belum tepat', 3 => 'Sedikit kekeliruan', 4 => 'Tepat dan konsisten']],
                                    ['name' => '4. Makharijul huruf', 'desc' => 'Kesesuaian artikulasi tempat keluarnya huruf.', 'default' => 4, 'rubrik' => [1 => 'Memerlukan bimbingan dasar', 2 => 'Beberapa huruf belum tepat', 3 => 'Sedikit kekeliruan', 4 => 'Tepat dan konsisten']],
                                    ['name' => '5. Tajwid', 'desc' => 'Penerapan hukum tajwid (nun mati, mim mati, dsb).', 'default' => 3, 'rubrik' => [1 => 'Belum memahami penerapan', 2 => 'Beberapa hukum belum dikuasai', 3 => 'Ada sedikit kekeliruan', 4 => 'Penerapan tepat']],
                                    ['name' => '6. Mad', 'desc' => 'Panjang pendek pembacaan mad.', 'default' => 3, 'rubrik' => [1 => 'Belum memahami panjang pendek', 2 => 'Sering memerlukan koreksi', 3 => 'Sedikit kesalahan', 4 => 'Panjang pendek tepat']],
                                    ['name' => '7. Kelancaran', 'desc' => 'Kelancaran membaca ayat Al-Qur\'an.', 'default' => 4, 'rubrik' => [1 => 'Belum dapat membaca secara berurutan', 2 => 'Sering berhenti', 3 => 'Sedikit terbata-bata', 4 => 'Lancar dan mandiri']],
                                ];
                            @endphp

                            @foreach($pos2Aspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/60 border border-emerald-800/80 shrink-0" id="label-val-p2-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p2-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-p2-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-emerald-500 text-zinc-950 border-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-p2-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-emerald-400" id="desc-title-p2-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-p2-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- POS 3 PANEL: DOA HARIAN -->
                        <div id="pos-panel-pos3" class="pos-panel space-y-3 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 3: Asesmen Pelafalan Doa-doa Harian</strong> &bull; 6 Aspek
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">Maks = 24</span>
                            </div>

                            @php
                                $pos3Aspek = [
                                    ['name' => '1. Hafalan', 'desc' => 'Penguasaan hafalan doa harian yang diujikan.', 'default' => 4, 'rubrik' => [1 => 'Menguasai sedikit', 2 => 'Menguasai sebagian', 3 => 'Menguasai sebagian besar', 4 => 'Menguasai seluruh doa yang diuji']],
                                    ['name' => '2. Pelafalan', 'desc' => 'Ketepatan pelafalan kalimat dan makhraj doa.', 'default' => 4, 'rubrik' => [1 => 'Belum mampu melafalkan dengan tepat', 2 => 'Beberapa kekeliruan', 3 => 'Sedikit kekeliruan', 4 => 'Tepat']],
                                    ['name' => '3. Kelengkapan', 'desc' => 'Kelengkapan bait dan lafal doa dari awal hingga akhir.', 'default' => 4, 'rubrik' => [1 => 'Sebagian besar belum dikuasai', 2 => 'Beberapa bagian terlewat', 3 => 'Ada bagian kecil terlewat', 4 => 'Lengkap']],
                                    ['name' => '4. Kelancaran', 'desc' => 'Kelancaran tanpa terhenti atau ragu.', 'default' => 4, 'rubrik' => [1 => 'Memerlukan bantuan intensif', 2 => 'Sering memerlukan petunjuk', 3 => 'Sedikit jeda', 4 => 'Lancar tanpa bantuan']],
                                    ['name' => '5. Pemahaman', 'desc' => 'Pemahaman makna dan arti dasar doa.', 'default' => 3, 'rubrik' => [1 => 'Belum memahami makna dasar', 2 => 'Memahami sebagian', 3 => 'Memahami sebagian besar', 4 => 'Memahami makna dan konteks']],
                                    ['name' => '6. Pengetahuan', 'desc' => 'Pengetahuan waktu dan situasi pengamalan doa.', 'default' => 4, 'rubrik' => [1 => 'Belum mengetahui waktu dan doanya', 2 => 'Tidak banyak mengetahui dengan tepat waktu dan doanya', 3 => 'Mengetahui sedikit yang kurang tepat untuk waktu dan doanya', 4 => 'Mengetahui dengan tepat waktu dan doanya']],
                                ];
                            @endphp

                            @foreach($pos3Aspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/60 border border-emerald-800/80 shrink-0" id="label-val-p3-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p3-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-p3-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-emerald-500 text-zinc-950 border-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-p3-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-emerald-400" id="desc-title-p3-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-p3-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- POS 4 PANEL: SURAT PENDEK -->
                        <div id="pos-panel-pos4" class="pos-panel space-y-3 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 4: Asesmen Pelafalan Surat-surat Pendek</strong> &bull; 7 Aspek
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">Maks = 28</span>
                            </div>

                            @php
                                $pos4Aspek = [
                                    ['name' => '1. Hafalan', 'desc' => 'Penguasaan hafalan surat Juz 30 yang diujikan.', 'default' => 4, 'rubrik' => [1 => 'Hafalan sangat terbatas', 2 => 'Menghafal sebagian', 3 => 'Menghafal sebagian besar', 4 => 'Menghafal seluruh surat yang diujikan']],
                                    ['name' => '2. Kelancaran', 'desc' => 'Kelancaran membaca mengalir tanpa terputus.', 'default' => 4, 'rubrik' => [1 => 'Terbata-bata', 2 => 'Sering terhenti', 3 => 'Sedikit jeda atau pengulangan minor', 4 => 'Membaca mengalir lancar tanpa terputus']],
                                    ['name' => '3. Tajwid', 'desc' => 'Ketepatan penerapan kaidah hukum tajwid.', 'default' => 4, 'rubrik' => [1 => 'Belum menerapkan kaidah', 2 => 'Sering terjadi kesalahan', 3 => 'Menerapkan sebagian besar', 4 => 'Menerapkan seluruh hukum tajwid dengan benar']],
                                    ['name' => '4. Makharijul Huruf', 'desc' => 'Pengucapan makhraj huruf secara tepat dan fasih.', 'default' => 3, 'rubrik' => [1 => 'Belum tepat dalam melafalkan', 2 => 'Sering tertukar', 3 => 'Sebagian besar tepat', 4 => 'Pengucapan makhraj tepat dan konsisten']],
                                    ['name' => '5. Harakat', 'desc' => 'Ketepatan harakat fathah, kasrah, dhammah, sukun.', 'default' => 4, 'rubrik' => [1 => 'Belum tepat membedakan harakat', 2 => 'Beberapa kekeliruan fatal', 3 => 'Ada sedikit kekeliruan harakat', 4 => 'Pembacaan harakat sangat tepat']],
                                    ['name' => '6. Adab Membaca', 'desc' => 'Adab duduk santun, ta\'awudz, basmalah, khusyuk.', 'default' => 4, 'rubrik' => [1 => 'Belum menunjukkan adab membaca Al-Qur\'an', 2 => 'Kurang tertib', 3 => 'Menunjukkan adab baik dengan sedikit pengingat', 4 => 'Posisi duduk tertib, tenang, ta\'awudz & basmalah tertib']],
                                    ['name' => '7. Pengetahuan', 'desc' => 'Pengetahuan makna, asbabun nuzul, & jumlah ayat.', 'default' => 3, 'rubrik' => [1 => 'Belum mengetahui arti dan jumlah ayat', 2 => 'Kurang banyak mengetahui arti dan jumlah ayat', 3 => 'Mengetahui sedikit yang kurang tepat arti dan jumlah ayat', 4 => 'Mengetahui dengan tepat arti dan jumlah ayat surat']],
                                ];
                            @endphp

                            @foreach($pos4Aspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/60 border border-emerald-800/80 shrink-0" id="label-val-p4-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p4-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-p4-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-emerald-500 text-zinc-950 border-emerald-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-p4-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-emerald-400" id="desc-title-p4-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-p4-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- ===================================================================== -->
                <!-- KELOMPOK 2: AKADEMIK CBT (Ustdz. Imarotul Muhibbah - Pos 5 CBT)       -->
                <!-- ===================================================================== -->
                @if($activeGuru['kelompok_key'] === 'akademik')
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-zinc-800">
                            <div>
                                <h3 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                                    <i data-lucide="calculator" class="h-4 w-4 text-blue-400"></i>
                                    <span>Pos 5: Asesmen Akademik Dasar - CBT</span>
                                </h3>
                                <p class="text-xs text-zinc-400">Input nilai 4 mata pelajaran (0–100) atau import dari Google Form CBT.</p>
                            </div>
                            <button type="button" onclick="openCbtImportModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm self-start sm:self-auto transition-colors">
                                <i data-lucide="upload" class="h-3.5 w-3.5"></i>
                                <span>Import CSV CBT</span>
                            </button>
                        </div>

                        @php
                            $cbt = $activeSiswa['akademik']['cbt'] ?? ['indo' => 90, 'mtk' => 90, 'ipas' => 90, 'inggris' => 90];
                        @endphp

                        <!-- 4 Subject Inputs (Touch-optimized with +/- buttons) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            
                            <!-- B. Indo -->
                            <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="book" class="h-3.5 w-3.5 text-blue-400"></i>
                                        <span>Bahasa Indonesia</span>
                                    </span>
                                    <span class="font-mono text-sm font-bold text-blue-400" id="val-cbt-indo">{{ $cbt['indo'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="adjustCbt('indo', -5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">-5</button>
                                    <input 
                                        type="range" 
                                        min="0" 
                                        max="100" 
                                        value="{{ $cbt['indo'] ?? 0 }}" 
                                        class="w-full h-2 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                        id="range-cbt-indo"
                                        oninput="updateCbtScore('indo', this.value)"
                                    >
                                    <button type="button" onclick="adjustCbt('indo', 5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">+5</button>
                                </div>
                            </div>

                            <!-- Matematika -->
                            <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="binary" class="h-3.5 w-3.5 text-blue-400"></i>
                                        <span>Matematika (Numerasi)</span>
                                    </span>
                                    <span class="font-mono text-sm font-bold text-blue-400" id="val-cbt-mtk">{{ $cbt['mtk'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="adjustCbt('mtk', -5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">-5</button>
                                    <input 
                                        type="range" 
                                        min="0" 
                                        max="100" 
                                        value="{{ $cbt['mtk'] ?? 0 }}" 
                                        class="w-full h-2 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                        id="range-cbt-mtk"
                                        oninput="updateCbtScore('mtk', this.value)"
                                    >
                                    <button type="button" onclick="adjustCbt('mtk', 5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">+5</button>
                                </div>
                            </div>

                            <!-- IPAS -->
                            <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="flask-conical" class="h-3.5 w-3.5 text-blue-400"></i>
                                        <span>IPAS (Sains Terpadu)</span>
                                    </span>
                                    <span class="font-mono text-sm font-bold text-blue-400" id="val-cbt-ipas">{{ $cbt['ipas'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="adjustCbt('ipas', -5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">-5</button>
                                    <input 
                                        type="range" 
                                        min="0" 
                                        max="100" 
                                        value="{{ $cbt['ipas'] ?? 0 }}" 
                                        class="w-full h-2 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                        id="range-cbt-ipas"
                                        oninput="updateCbtScore('ipas', this.value)"
                                    >
                                    <button type="button" onclick="adjustCbt('ipas', 5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">+5</button>
                                </div>
                            </div>

                            <!-- B. Inggris -->
                            <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3.5 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="languages" class="h-3.5 w-3.5 text-blue-400"></i>
                                        <span>Bahasa Inggris</span>
                                    </span>
                                    <span class="font-mono text-sm font-bold text-blue-400" id="val-cbt-inggris">{{ $cbt['inggris'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="adjustCbt('inggris', -5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">-5</button>
                                    <input 
                                        type="range" 
                                        min="0" 
                                        max="100" 
                                        value="{{ $cbt['inggris'] ?? 0 }}" 
                                        class="w-full h-2 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                        id="range-cbt-inggris"
                                        oninput="updateCbtScore('inggris', this.value)"
                                    >
                                    <button type="button" onclick="adjustCbt('inggris', 5)" class="h-9 w-9 rounded-lg bg-zinc-800 border border-zinc-700 text-zinc-300 font-bold hover:bg-zinc-700 shrink-0 text-sm">+5</button>
                                </div>
                            </div>

                        </div>

                        <!-- CBT Calculation Card -->
                        <div class="rounded-xl border border-zinc-800 bg-zinc-950/80 p-3.5 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-xs text-zinc-300 flex items-center gap-2">
                                <i data-lucide="info" class="h-4 w-4 text-blue-400 shrink-0"></i>
                                <span>Rumus: <strong>Rata-rata 4 Mapel</strong> = (Indo + MTK + IPAS + Inggris) / 4</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs text-zinc-400 font-medium">Predikat:</span>
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-950 border border-blue-700 text-blue-300" id="cbt-predikat-label">
                                    Sangat Baik (A)
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ===================================================================== -->
                <!-- KELOMPOK 3: POTENSI BAKAT & MINAT (Ustdz. Mutaahirotul - Pos 6 & 7)   -->
                <!-- ===================================================================== -->
                @if($activeGuru['kelompok_key'] === 'bakat_minat')
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-zinc-800">
                            <div>
                                <h3 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                                    <i data-lucide="palette" class="h-4 w-4 text-purple-400"></i>
                                    <span>Kelompok Potensi Bakat dan Minat (Pos 6 &amp; 7)</span>
                                </h3>
                                <p class="text-xs text-zinc-400">Pilih pos di bawah ini untuk menilai Motorik Halus (Pos 6) atau Kreativitas (Pos 7).</p>
                            </div>
                            <span class="text-xs font-mono px-2.5 py-1 rounded-md bg-purple-950 border border-purple-700 text-purple-300 font-semibold self-start sm:self-auto">
                                Nilai: {{ $activeSiswa['bakat_minat']['nilai'] ?? 92.3 }}%
                            </span>
                        </div>

                        <!-- Swipeable Sub-Tabs Pos 6 vs Pos 7 -->
                        <div class="flex rounded-lg border border-zinc-800 bg-zinc-950 p-1 text-xs font-medium gap-1">
                            <button type="button" onclick="switchBakatTab('pos6')" id="tab-btn-pos6" class="bakat-tab-btn flex-1 py-2 px-3 rounded-md bg-zinc-800 text-zinc-100 font-bold shadow text-center truncate">
                                Pos 6: Motorik Halus (7)
                            </button>
                            <button type="button" onclick="switchBakatTab('pos7')" id="tab-btn-pos7" class="bakat-tab-btn flex-1 py-2 px-3 rounded-md text-zinc-400 hover:text-zinc-200 text-center truncate">
                                Pos 7: Kreativitas (6)
                            </button>
                        </div>

                        <!-- POS 6 PANEL: MOTORIK HALUS -->
                        <div id="bakat-panel-pos6" class="bakat-panel space-y-3">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 6: Asesmen Motorik Halus &amp; Kreativitas</strong> &bull; Skor Maks = 28
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">Rubrik Holistik</span>
                            </div>

                            @php
                                $pos6Indikator = [
                                    ['name' => '1. Koordinasi mata dan tangan', 'desc' => 'Ketepatan koordinasi visual-motorik saat mengerjakan karya.'],
                                    ['name' => '2. Ketepatan membuat bentuk', 'desc' => 'Akurasi pembuatan pola dan kontur bentuk.'],
                                    ['name' => '3. Kontrol gerakan tangan', 'desc' => 'Kestabilan dan kontrol kekuatan otot tangan saat memotong/membentuk.'],
                                    ['name' => '4. Ketelitian mengikuti instruksi', 'desc' => 'Ketertiban mematuhi panduan dan urutan tahap pembuatan.'],
                                    ['name' => '5. Kemampuan memahami ruang dan bentuk', 'desc' => 'Pemahaman spasial proporsi objek terhadap bidang.'],
                                    ['name' => '6. Kemampuan menyusun objek', 'desc' => 'Runtutan dan kerapian susunan objek sesuai konsep karya.'],
                                    ['name' => '7. Kerapian dan fungsi hasil karya', 'desc' => 'Kerapian akhir, kebersihan, dan kekokohan fungsi hasil karya.'],
                                ];
                                $holisticRubrik = [
                                    1 => 'Membutuhkan pendampingan dalam menyelesaikan sebagian besar tahapan',
                                    2 => 'Mampu menyelesaikan sebagian tugas dengan beberapa arahan',
                                    3 => 'Mampu menyelesaikan tugas dengan sedikit kekeliruan',
                                    4 => 'Mampu menyelesaikan tugas dengan tepat, terkontrol, dan mandiri',
                                ];
                            @endphp

                            @foreach($pos6Indikator as $idx => $ind)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $ind['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $ind['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-purple-400 px-2 py-0.5 rounded bg-purple-950/60 border border-purple-800/80 shrink-0" id="label-val-p6-{{ $idx }}">
                                            Skor: 4
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p6-{{ $idx }}', {{ $s }}, '{{ addslashes($holisticRubrik[$s]) }}')"
                                                id="btn-p6-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === 4 ? 'bg-purple-500 text-zinc-950 border-purple-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-p6-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-purple-400" id="desc-title-p6-{{ $idx }}">Kriteria Skor 4:</strong>
                                        <span id="desc-text-p6-{{ $idx }}" class="ml-1">{{ $holisticRubrik[4] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- POS 7 PANEL: KREATIVITAS & INOVASI -->
                        <div id="bakat-panel-pos7" class="bakat-panel space-y-3 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Pos 7: Asesmen Kreativitas dan Inovasi</strong> &bull; Skor Maks = 24
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">6 Aspek</span>
                            </div>

                            @php
                                $pos7Aspek = [
                                    ['name' => '1. Fluency (Kelancaran Gagasan)', 'desc' => 'Kemampuan mencetuskan banyak ide relevan.', 'default' => 4, 'rubrik' => [1 => 'Belum menghasilkan ide yang relevan', 2 => 'Menghasilkan sedikit ide', 3 => 'Menghasilkan beberapa ide', 4 => 'Menghasilkan banyak ide relevan']],
                                    ['name' => '2. Flexibility (Kelenturan Berpikir)', 'desc' => 'Ide berasal dari beragam sudut pandang dan pendekatan.', 'default' => 4, 'rubrik' => [1 => 'Belum menunjukkan variasi', 2 => 'Ide masih terbatas pada satu pendekatan', 3 => 'Ide memiliki beberapa variasi', 4 => 'Ide berasal dari beragam pendekatan']],
                                    ['name' => '3. Originality (Keaslian Gagasan)', 'desc' => 'Unsur kebaruan dan autentisitas ide.', 'default' => 3, 'rubrik' => [1 => 'Belum mampu mengembangkan ide', 2 => 'Ide masih umum', 3 => 'Ada pengembangan dari ide umum', 4 => 'Ide menunjukkan unsur kebaruan yang jelas']],
                                    ['name' => '4. Elaboration (Keterperincian)', 'desc' => 'Kedalaman rancangan dan detail gagasan.', 'default' => 4, 'rubrik' => [1 => 'Belum mengembangkan rancangan', 2 => 'Rancangan masih sederhana', 3 => 'Rancangan cukup terperinci', 4 => 'Rancangan sangat terperinci']],
                                    ['name' => '5. Problem solving (Solusi Masalah)', 'desc' => 'Solusi relevan, logis, dan dapat diaplikasikan.', 'default' => 4, 'rubrik' => [1 => 'Belum menemukan solusi yang sesuai', 2 => 'Solusi memerlukan pengembangan', 3 => 'Solusi relevan dan cukup logis', 4 => 'Solusi relevan, logis, dan dapat diterapkan']],
                                    ['name' => '6. Communication (Penyampaian Ide)', 'desc' => 'Penjelasan ide runtut dengan argumentasi kuat.', 'default' => 3, 'rubrik' => [1 => 'Memerlukan bantuan untuk menjelaskan', 2 => 'Penjelasan masih terbatas', 3 => 'Penjelasan cukup jelas', 4 => 'Menjelaskan ide dengan runtut dan alasan kuat']],
                                ];
                            @endphp

                            @foreach($pos7Aspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-purple-400 px-2 py-0.5 rounded bg-purple-950/60 border border-purple-800/80 shrink-0" id="label-val-p7-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('p7-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-p7-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-purple-500 text-zinc-950 border-purple-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-p7-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-purple-400" id="desc-title-p7-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-p7-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- ===================================================================== -->
                <!-- KELOMPOK 4: PRIBADI & SOSIAL / BK (Ust. Davies Yudisno - Pos 8)       -->
                <!-- ===================================================================== -->
                @if($activeGuru['kelompok_key'] === 'pribadi_sosial')
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-zinc-800">
                            <div>
                                <h3 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                                    <i data-lucide="heart-handshake" class="h-4 w-4 text-amber-400"></i>
                                    <span>Pos 8: Asesmen Psikologi &amp; Perkembangan Remaja</span>
                                </h3>
                                <p class="text-xs text-zinc-400">Pemetaan kesiapan transisi SD ke SMP dan kebutuhan layanan pendampingan BK.</p>
                            </div>
                            <span class="text-xs font-mono px-2.5 py-1 rounded-md bg-amber-950 border border-amber-700 text-amber-300 font-semibold self-start sm:self-auto">
                                Rekomendasi BK: {{ $activeSiswa['pribadi_sosial']['kategori_bk'] ?? 'Sangat Siap' }}
                            </span>
                        </div>

                        <!-- Non-Eliminatif Reminder Alert -->
                        <div class="rounded-lg border border-amber-900/60 bg-amber-950/20 p-3 text-xs text-amber-300/90 flex items-start gap-2">
                            <i data-lucide="info" class="h-4 w-4 shrink-0 text-amber-400 mt-0.5"></i>
                            <span class="text-[11px] leading-relaxed">
                                <strong>Catatan Sistem:</strong> Modul ini BUKAN untuk kelulusan/labeling, melainkan dasar pemetaan transisi dan pendampingan Bimbingan Konseling (BK).
                            </span>
                        </div>

                        <!-- Swipeable Sub-Tabs: A, B, C -->
                        <div class="flex rounded-lg border border-zinc-800 bg-zinc-950 p-1 text-xs font-medium gap-1 overflow-x-auto no-scrollbar">
                            <button type="button" onclick="switchPsiTab('angket')" id="tab-btn-angket" class="psi-tab-btn flex-1 min-w-[120px] py-2 px-2.5 rounded-md bg-zinc-800 text-zinc-100 font-bold shadow text-center truncate">
                                A: Angket Refleksi (10)
                            </button>
                            <button type="button" onclick="switchPsiTab('wawancara')" id="tab-btn-wawancara" class="psi-tab-btn flex-1 min-w-[120px] py-2 px-2.5 rounded-md text-zinc-400 hover:text-zinc-200 text-center truncate">
                                B: Wawancara 8 Topik
                            </button>
                            <button type="button" onclick="switchPsiTab('observasi')" id="tab-btn-observasi" class="psi-tab-btn flex-1 min-w-[120px] py-2 px-2.5 rounded-md text-zinc-400 hover:text-zinc-200 text-center truncate">
                                C: Observasi Kelompok (6)
                            </button>
                        </div>

                        <!-- SUB-TAB A: ANGKET REFLEKSI (10 PERNYATAAN) -->
                        <div id="psi-panel-angket" class="psi-panel space-y-3">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Sub-Tab A: Angket Refleksi Diri Calon Murid</strong> &bull; Skor Maks = 40
                                </div>
                                <span class="text-[11px] text-zinc-400 font-mono">Skala 1–4</span>
                            </div>

                            @php
                                $angketItems = [
                                    '1. Saya mengetahui hal yang menjadi kelebihan saya',
                                    '2. Saya berusaha menyelesaikan tugas yang diberikan',
                                    '3. Saya berani bertanya ketika belum memahami pelajaran',
                                    '4. Saya dapat menerima masukan dari orang lain',
                                    '5. Saya berusaha mengelola waktu belajar',
                                    '6. Saya menghargai perbedaan pendapat',
                                    '7. Saya bersedia membantu teman yang membutuhkan',
                                    '8. Saya berusaha bertanggung jawab atas kesalahan saya',
                                    '9. Saya bersedia mencoba kegiatan baru',
                                    '10. Saya menggunakan perangkat digital dengan memperhatikan tujuan penggunaannya',
                                ];
                                $angketLabels = [
                                    1 => 'Belum Sesuai',
                                    2 => 'Kadang-kadang',
                                    3 => 'Sesuai',
                                    4 => 'Sangat Sesuai',
                                ];
                            @endphp

                            @foreach($angketItems as $idx => $statement)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="text-xs sm:text-sm font-semibold text-zinc-100 leading-snug">{{ $statement }}</h4>
                                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-amber-950/60 border border-amber-800/80 shrink-0" id="label-val-ang-{{ $idx }}">
                                            Skor: 4
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('ang-{{ $idx }}', {{ $s }}, '{{ $angketLabels[$s] }}')"
                                                id="btn-ang-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === 4 ? 'bg-amber-500 text-zinc-950 border-amber-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-80 hidden xs:inline">{{ $angketLabels[$s] }}</span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-ang-{{ $idx }}" class="p-2 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] text-zinc-300">
                                        <strong class="text-amber-400" id="desc-title-ang-{{ $idx }}">Jawaban:</strong>
                                        <span id="desc-text-ang-{{ $idx }}" class="ml-1">{{ $angketLabels[4] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- SUB-TAB B: WAWANCARA 8 TOPIK & ORANG TUA -->
                        <div id="psi-panel-wawancara" class="psi-panel space-y-4 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300">
                                <strong class="text-zinc-100">Sub-Tab B: Catatan Kualitatif Wawancara 8 Topik &amp; Orang Tua</strong>
                                <p class="text-[11px] text-zinc-400 mt-0.5">Kombinasi observasi respon anak dan percakapan wali murid.</p>
                            </div>

                            <!-- 8 Topik Guide Chips -->
                            <div class="rounded-xl border border-zinc-800 bg-zinc-950/60 p-3 space-y-2">
                                <span class="text-xs font-semibold text-zinc-200">Panduan 8 Topik Wawancara:</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 text-[10px]">
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">1. Kesadaran Diri</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">2. Regulasi Emosi</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">3. Percaya Diri</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">4. Kemandirian</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">5. Interaksi Sosial</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">6. Empati &amp; Tanggung Jawab</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">7. Motivasi &amp; Adaptasi</div>
                                    <div class="p-1.5 rounded bg-zinc-900 border border-zinc-800 text-zinc-300">8. Literasi Digital</div>
                                </div>
                            </div>

                            <!-- Wawancara Siswa -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                                    <i data-lucide="user" class="h-3.5 w-3.5 text-amber-400"></i>
                                    <span>Catatan Ringkasan Jawaban Calon Murid</span>
                                </label>
                                <textarea 
                                    rows="3" 
                                    class="w-full rounded-xl border border-zinc-800 bg-zinc-950 p-3 text-xs text-zinc-200 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-amber-400 transition-colors"
                                    placeholder="Tuliskan catatan respon emosi, motivasi, dan kesiapan ananda..."
                                >Ananda memiliki kemampuan refleksi diri yang baik, antusias menceritakan proyek minat sains, mampu meregulasi emosi saat menghadapi tugas baru, dan terbiasa berkomunikasi terbuka.</textarea>
                            </div>

                            <!-- Percakapan Orang Tua -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                                    <i data-lucide="users" class="h-3.5 w-3.5 text-amber-400"></i>
                                    <span>Catatan Hasil Percakapan dengan Orang Tua / Wali</span>
                                </label>
                                <textarea 
                                    rows="3" 
                                    class="w-full rounded-xl border border-zinc-800 bg-zinc-950 p-3 text-xs text-zinc-200 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-amber-400 transition-colors"
                                    placeholder="Tuliskan catatan pendampingan orang tua di rumah, screen time, harapan..."
                                >Orang tua sangat mendukung pembelajaran berbasis karakter dan kurikulum alam. Di rumah telah disepakati batas screen time 1.5 jam/hari dan orang tua siap bersinergi dengan tim BK sekolah.</textarea>
                            </div>
                        </div>

                        <!-- SUB-TAB C: OBSERVASI KELOMPOK (6 ASPEK) -->
                        <div id="psi-panel-observasi" class="psi-panel space-y-3 hidden">
                            <div class="rounded-lg border border-zinc-800 bg-zinc-950/70 p-3 text-xs text-zinc-300 flex justify-between items-center">
                                <div>
                                    <strong class="text-zinc-100">Sub-Tab C: Rubrik Observasi Psikososial Diskusi Kelompok</strong> &bull; Skor Maks = 24
                                </div>
                                <span class="font-mono text-zinc-400 text-[11px]">6 Aspek</span>
                            </div>

                            @php
                                $obsAspek = [
                                    ['name' => '1. Kemandirian', 'desc' => 'Menyelesaikan tugas secara mandiri tanpa ketergantungan berlebih.', 'default' => 4, 'rubrik' => [1 => 'Memerlukan pendampingan intensif', 2 => 'Beberapa kali memerlukan bantuan', 3 => 'Sedikit arahan', 4 => 'Melakukan tugas secara mandiri']],
                                    ['name' => '2. Komunikasi', 'desc' => 'Menyampaikan gagasan, pendapat, dan pertanyaan dengan jelas.', 'default' => 4, 'rubrik' => [1 => 'Belum menunjukkan respons yang memadai', 2 => 'Masih terbatas', 3 => 'Cukup jelas', 4 => 'Menyampaikan gagasan dengan jelas']],
                                    ['name' => '3. Kerja sama', 'desc' => 'Aktif berkontribusi dan menghargai peran teman dalam kelompok.', 'default' => 3, 'rubrik' => [1 => 'Belum terlibat secara konsisten', 2 => 'Terlibat setelah diarahkan', 3 => 'Bekerja sama dengan baik', 4 => 'Aktif dan menghargai anggota lain']],
                                    ['name' => '4. Regulasi emosi', 'desc' => 'Mengenali dan mengelola ekspresi emosi serta respon saat berbeda pendapat.', 'default' => 4, 'rubrik' => [1 => 'Membutuhkan pendampingan lebih lanjut', 2 => 'Masih memerlukan arahan', 3 => 'Umumnya mampu', 4 => 'Mampu mengenali dan mengelola respons']],
                                    ['name' => '5. Tanggung jawab', 'desc' => 'Konsistensi menyelesaikan bagian tugas yang disepakati.', 'default' => 4, 'rubrik' => [1 => 'Belum menunjukkan penyelesaian tugas', 2 => 'Memerlukan pengingat', 3 => 'Sebagian besar selesai', 4 => 'Konsisten menyelesaikan tugas']],
                                    ['name' => '6. Empati', 'desc' => 'Menunjukkan kepedulian terhadap kebutuhan atau kesulitan teman.', 'default' => 3, 'rubrik' => [1 => 'Belum tampak dalam situasi pengamatan', 2 => 'Memerlukan pengingat', 3 => 'Menunjukkan kepedulian', 4 => 'Menunjukkan kepedulian secara aktif']],
                                ];
                            @endphp

                            @foreach($obsAspek as $idx => $asp)
                                <div class="rounded-xl border border-zinc-800 bg-zinc-900/70 p-3 sm:p-4 space-y-2.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-semibold text-zinc-100">{{ $asp['name'] }}</h4>
                                            <p class="text-[11px] text-zinc-400 mt-0.5">{{ $asp['desc'] }}</p>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-amber-400 px-2 py-0.5 rounded bg-amber-950/60 border border-amber-800/80 shrink-0" id="label-val-obs-{{ $idx }}">
                                            Skor: {{ $asp['default'] }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                                        @for($s = 1; $s <= 4; $s++)
                                            <button 
                                                type="button" 
                                                onclick="selectScoreMobile('obs-{{ $idx }}', {{ $s }}, '{{ addslashes($asp['rubrik'][$s]) }}')"
                                                id="btn-obs-{{ $idx }}-{{ $s }}"
                                                class="h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm
                                                    {{ $s === $asp['default'] ? 'bg-amber-500 text-zinc-950 border-amber-400' : 'bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200' }}"
                                            >
                                                <span>{{ $s }}</span>
                                                <span class="text-[10px] opacity-75 hidden xs:inline">
                                                    @if($s === 1) Belum @elseif($s === 2) Cukup @elseif($s === 3) Baik @else Mahir @endif
                                                </span>
                                            </button>
                                        @endfor
                                    </div>

                                    <div id="desc-box-obs-{{ $idx }}" class="p-2.5 rounded-lg border border-zinc-800/80 bg-zinc-950/80 text-[11px] leading-relaxed text-zinc-300">
                                        <strong class="text-amber-400" id="desc-title-obs-{{ $idx }}">Kriteria Skor {{ $asp['default'] }}:</strong>
                                        <span id="desc-text-obs-{{ $idx }}" class="ml-1">{{ $asp['rubrik'][$asp['default']] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Anecdotal Note Section -->
                <div class="space-y-1.5 pt-3 border-t border-zinc-800">
                    <label class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                        <i data-lucide="file-text" class="h-3.5 w-3.5 text-zinc-400"></i>
                        <span>Catatan Kualitatif Pengamat (Anecdotal Record)</span>
                    </label>
                    <textarea 
                        rows="2" 
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950 p-3 text-xs text-zinc-200 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors"
                        placeholder="Tuliskan catatan khusus atau rekomendasi pengamat..."
                    >{{ $activeGuru['kelompok_short'] }}: Ananda sangat kooperatif, percaya diri, dan memiliki kesiapan adab yang baik dalam mengikuti seluruh tahapan observasi.</textarea>
                </div>

                <!-- Desktop Bottom Action Buttons (Hidden on mobile sticky bar) -->
                <div class="hidden sm:flex items-center justify-between pt-4 border-t border-zinc-800">
                    <button type="button" onclick="alert('Draf penilaian ananda {{ $activeSiswa['name'] }} tersimpan di sesi lokal.');" class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg border border-zinc-800 bg-transparent px-4 text-xs font-medium text-zinc-400 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        <span>Simpan Sebagai Draf</span>
                    </button>
                    
                    <div class="flex items-center gap-2">
                        @php
                            $currentIndex = collect($siswaSMP)->search(fn($item) => $item['no'] === $activeSiswa['no']);
                            $nextSiswa = $siswaSMP[($currentIndex + 1) % count($siswaSMP)];
                        @endphp
                        <a 
                            href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $nextSiswa['no']]) }}" 
                            class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-800 px-4 text-xs font-medium text-zinc-200 hover:bg-zinc-700 transition-colors"
                        >
                            <i data-lucide="arrow-right-left" class="h-4 w-4"></i>
                            <span>Ganti Siswa Lain</span>
                        </a>

                        <button type="button" onclick="saveActiveAssessment()" class="inline-flex h-10 items-center justify-center gap-1.5 rounded-lg bg-emerald-500 px-5 text-xs font-bold text-zinc-950 shadow hover:bg-emerald-400 transition-colors">
                            <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                            <span>Simpan Nilai {{ $activeGuru['kelompok_short'] }}</span>
                        </button>
                    </div>
                </div>

            </div>

        </main>

        <!-- ========================================================================= -->
        <!-- 5. STICKY BOTTOM ACTION BAR (OPTIMAL UNTUK SMARTPHONE SCREEN)             -->
        <!-- ========================================================================= -->
        <div class="fixed bottom-0 inset-x-0 z-40 sm:hidden border-t border-zinc-800 bg-zinc-950/95 backdrop-blur px-3 py-2.5 shadow-2xl">
            <div class="flex items-center justify-between gap-2">
                <!-- Left: Live Score pill -->
                <div class="flex flex-col">
                    <span class="text-[9px] text-zinc-400 font-mono">Skor Ruang Ini:</span>
                    <span class="text-sm font-bold font-mono text-emerald-400" id="bottom-score-badge">
                        {{ $valNow ? $valNow.'%' : '-' }}
                    </span>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-1.5">
                    <a 
                        href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $nextSiswa['no']]) }}" 
                        class="inline-flex h-9 items-center justify-center px-2.5 rounded-lg border border-zinc-800 bg-zinc-900 text-zinc-300 text-[11px] font-medium"
                        title="Pilih Siswa Lain"
                    >
                        <i data-lucide="arrow-right-left" class="h-3.5 w-3.5 mr-1 text-zinc-400"></i>
                        <span>Siswa Lain</span>
                    </a>

                    <button 
                        type="button" 
                        onclick="saveActiveAssessment()" 
                        class="inline-flex h-9 items-center justify-center gap-1.5 px-3.5 rounded-lg bg-emerald-500 text-zinc-950 text-xs font-bold shadow active:scale-95 transition-transform"
                    >
                        <i data-lucide="check" class="h-4 w-4"></i>
                        <span>Simpan Nilai</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 6. MODAL REKAPITULASI 4 KELOMPOK (SLIDE-OVER / POPUP)                     -->
        <!-- ========================================================================= -->
        <div id="modal-rekap-backdrop" class="fixed inset-0 z-50 bg-zinc-950/80 backdrop-blur-sm hidden flex items-center justify-center p-3 sm:p-4">
            <div class="w-full max-w-lg rounded-2xl border border-zinc-800 bg-zinc-900 p-5 space-y-4 shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                    <div>
                        <h4 class="text-sm font-bold text-zinc-100 flex items-center gap-2">
                            <i data-lucide="pie-chart" class="h-4 w-4 text-emerald-400"></i>
                            <span>Rekap Kelengkapan 4 Kelompok Asesmen</span>
                        </h4>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ $activeSiswa['name'] }} &bull; {{ $activeSiswa['no'] }}</p>
                    </div>
                    <button type="button" onclick="toggleRekapModal()" class="h-8 w-8 rounded-lg bg-zinc-800 text-zinc-400 hover:text-zinc-200 flex items-center justify-center">&times;</button>
                </div>

                <div class="p-2.5 rounded-lg bg-zinc-950/60 border border-zinc-800 text-xs text-zinc-400 flex items-center justify-between">
                    <span>Mulai Asesmen Pertama Dari:</span>
                    <strong class="text-zinc-200">{{ $activeSiswa['mulai_dari'] }}</strong>
                </div>

                <!-- 4 Kelompok Score Cards -->
                <div class="space-y-2.5 text-xs">
                    <!-- Kelompok Keagamaan -->
                    <div class="p-3 rounded-xl border border-zinc-800 bg-zinc-950/70 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full bg-emerald-400 shrink-0"></span>
                            <div>
                                <div class="font-semibold text-zinc-200">1. Kelompok Keagamaan (Pos 1–4)</div>
                                <div class="text-[10px] text-zinc-500">Sholat, Mengaji, Doa, Surat</div>
                            </div>
                        </div>
                        @if(($activeSiswa['keagamaan']['status'] ?? '') === 'Selesai')
                            <span class="font-mono font-bold text-sm text-emerald-400">{{ $activeSiswa['keagamaan']['nilai'] }}%</span>
                        @elseif(($activeSiswa['keagamaan']['status'] ?? '') === 'Sedang Diobservasi')
                            <span class="text-[11px] text-amber-400 font-semibold animate-pulse">Sedang Berlangsung</span>
                        @else
                            <span class="text-[11px] text-zinc-500">Belum Diuji</span>
                        @endif
                    </div>

                    <!-- Kelompok Akademik -->
                    <div class="p-3 rounded-xl border border-zinc-800 bg-zinc-950/70 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full bg-blue-400 shrink-0"></span>
                            <div>
                                <div class="font-semibold text-zinc-200">2. Kelompok Akademik CBT (Pos 5)</div>
                                <div class="text-[10px] text-zinc-500">B. Indo, MTK, IPAS, B. Inggris</div>
                            </div>
                        </div>
                        @if(($activeSiswa['akademik']['status'] ?? '') === 'Selesai')
                            <span class="font-mono font-bold text-sm text-blue-400">{{ $activeSiswa['akademik']['nilai'] }}%</span>
                        @elseif(($activeSiswa['akademik']['status'] ?? '') === 'Sedang Diobservasi')
                            <span class="text-[11px] text-amber-400 font-semibold animate-pulse">Sedang Berlangsung</span>
                        @else
                            <span class="text-[11px] text-zinc-500">Belum Diuji</span>
                        @endif
                    </div>

                    <!-- Kelompok Bakat Minat -->
                    <div class="p-3 rounded-xl border border-zinc-800 bg-zinc-950/70 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full bg-purple-400 shrink-0"></span>
                            <div>
                                <div class="font-semibold text-zinc-200">3. Kelompok Potensi Bakat &amp; Minat (Pos 6–7)</div>
                                <div class="text-[10px] text-zinc-500">Motorik Halus &amp; Kreativitas Inovasi</div>
                            </div>
                        </div>
                        @if(($activeSiswa['bakat_minat']['status'] ?? '') === 'Selesai')
                            <span class="font-mono font-bold text-sm text-purple-400">{{ $activeSiswa['bakat_minat']['nilai'] }}%</span>
                        @elseif(($activeSiswa['bakat_minat']['status'] ?? '') === 'Sedang Diobservasi')
                            <span class="text-[11px] text-amber-400 font-semibold animate-pulse">Sedang Berlangsung</span>
                        @else
                            <span class="text-[11px] text-zinc-500">Belum Diuji</span>
                        @endif
                    </div>

                    <!-- Kelompok Pribadi Sosial -->
                    <div class="p-3 rounded-xl border border-zinc-800 bg-zinc-950/70 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full bg-amber-400 shrink-0"></span>
                            <div>
                                <div class="font-semibold text-zinc-200">4. Kelompok Perkembangan Pribadi &amp; Sosial</div>
                                <div class="text-[10px] text-zinc-500">Angket, Wawancara, Observasi BK</div>
                            </div>
                        </div>
                        @if(($activeSiswa['pribadi_sosial']['status'] ?? '') === 'Selesai')
                            <span class="font-mono font-bold text-sm text-amber-400">{{ $activeSiswa['pribadi_sosial']['nilai'] }}%</span>
                        @elseif(($activeSiswa['pribadi_sosial']['status'] ?? '') === 'Sedang Diobservasi')
                            <span class="text-[11px] text-amber-400 font-semibold animate-pulse">Sedang Berlangsung</span>
                        @else
                            <span class="text-[11px] text-zinc-500">Belum Diuji</span>
                        @endif
                    </div>
                </div>

                <div class="pt-2 flex justify-between items-center border-t border-zinc-800">
                    <button type="button" onclick="alert('Mengunduh Rapor Lengkap PDF...');" class="inline-flex items-center gap-1.5 text-xs text-zinc-300 hover:text-zinc-100">
                        <i data-lucide="download" class="h-3.5 w-3.5"></i>
                        <span>Unduh PDF</span>
                    </button>
                    <button type="button" onclick="toggleRekapModal()" class="px-4 py-1.5 rounded-lg bg-zinc-800 text-xs font-medium text-zinc-200 hover:bg-zinc-700">Tutup</button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 7. CBT IMPORT MODAL                                                       -->
        <!-- ========================================================================= -->
        <div id="cbt-modal-backdrop" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4">
            <div class="w-full max-w-md rounded-2xl border border-zinc-800 bg-zinc-900 p-5 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between pb-2 border-b border-zinc-800">
                    <div class="flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="h-4 w-4 text-blue-400"></i>
                        <h4 class="text-sm font-semibold text-zinc-100">Import Hasil CBT Google Form</h4>
                    </div>
                    <button type="button" onclick="closeCbtImportModal()" class="text-zinc-400 hover:text-zinc-200 text-base">&times;</button>
                </div>
                
                <p class="text-xs text-zinc-400 leading-relaxed">
                    Unggah file CSV ekspor Google Form CBT. Sistem otomatis memetakan nilai berdasarkan <strong>Nomor Peserta</strong>.
                </p>

                <div class="rounded-xl border-2 border-dashed border-zinc-700 bg-zinc-950/60 p-5 text-center space-y-2 cursor-pointer hover:border-blue-500 transition-colors">
                    <i data-lucide="upload-cloud" class="h-7 w-7 text-zinc-500 mx-auto"></i>
                    <div class="text-xs font-medium text-zinc-300">Pilih file .CSV / .XLSX</div>
                    <div class="text-[10px] text-zinc-500">Maksimal 5 MB</div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeCbtImportModal()" class="px-3 py-1.5 rounded-lg border border-zinc-800 text-xs text-zinc-400 hover:bg-zinc-800">Batal</button>
                    <button type="button" onclick="simulateImportSuccess()" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold">Terapkan Nilai</button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- JAVASCRIPT: TOUCH-FRIENDLY SCORE & TAB INTERACTIONS                       -->
        <!-- ========================================================================= -->
        <script>
            // Mobile-first Score Selection with Dynamic Rubric Text Update
            function selectScoreMobile(prefix, score, fullRubricText) {
                // Update score label
                const label = document.getElementById('label-val-' + prefix);
                if (label) label.textContent = 'Skor: ' + score;

                // Update full rubric description box below buttons
                const descBox = document.getElementById('desc-box-' + prefix);
                const descTitle = document.getElementById('desc-title-' + prefix);
                const descText = document.getElementById('desc-text-' + prefix);

                if (descTitle) descTitle.textContent = 'Kriteria Skor ' + score + ':';
                if (descText) descText.textContent = fullRubricText;

                // Highlight active button with high contrast
                for (let s = 1; s <= 4; s++) {
                    const btn = document.getElementById('btn-' + prefix + '-' + s);
                    if (btn) {
                        if (s === score) {
                            btn.className = 'h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm bg-emerald-500 text-zinc-950 border-emerald-400';
                        } else {
                            btn.className = 'h-10 rounded-lg text-xs font-bold border transition-all flex items-center justify-center gap-1 shadow-sm bg-zinc-950 border-zinc-800 text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200';
                        }
                    }
                }
            }

            // Sub-Tab Switcher: Pos 1 - 4 Keagamaan
            function switchPosTab(posId) {
                document.querySelectorAll('.pos-panel').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('.pos-tab-btn').forEach(b => {
                    b.classList.remove('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    b.classList.add('text-zinc-400');
                });

                const panel = document.getElementById('pos-panel-' + posId);
                const btn = document.getElementById('tab-btn-' + posId);
                if (panel) panel.classList.remove('hidden');
                if (btn) {
                    btn.classList.add('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    btn.classList.remove('text-zinc-400');
                }
            }

            // Sub-Tab Switcher: Bakat Minat Pos 6 vs Pos 7
            function switchBakatTab(bakatId) {
                document.querySelectorAll('.bakat-panel').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('.bakat-tab-btn').forEach(b => {
                    b.classList.remove('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    b.classList.add('text-zinc-400');
                });

                const panel = document.getElementById('bakat-panel-' + bakatId);
                const btn = document.getElementById('tab-btn-' + bakatId);
                if (panel) panel.classList.remove('hidden');
                if (btn) {
                    btn.classList.add('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    btn.classList.remove('text-zinc-400');
                }
            }

            // Sub-Tab Switcher: Psikososial (Angket, Wawancara, Observasi)
            function switchPsiTab(psiId) {
                document.querySelectorAll('.psi-panel').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('.psi-tab-btn').forEach(b => {
                    b.classList.remove('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    b.classList.add('text-zinc-400');
                });

                const panel = document.getElementById('psi-panel-' + psiId);
                const btn = document.getElementById('tab-btn-' + psiId);
                if (panel) panel.classList.remove('hidden');
                if (btn) {
                    btn.classList.add('bg-zinc-800', 'text-zinc-100', 'font-bold', 'shadow');
                    btn.classList.remove('text-zinc-400');
                }
            }

            // CBT Score Adjuster (+/- buttons)
            function adjustCbt(subject, delta) {
                const range = document.getElementById('range-cbt-' + subject);
                if (range) {
                    let cur = parseInt(range.value) || 0;
                    cur = Math.min(100, Math.max(0, cur + delta));
                    range.value = cur;
                    updateCbtScore(subject, cur);
                }
            }

            // CBT Live Calculation
            function updateCbtScore(subject, val) {
                const label = document.getElementById('val-cbt-' + subject);
                if (label) label.textContent = val;

                const indo = parseFloat(document.getElementById('range-cbt-indo')?.value || 0);
                const mtk = parseFloat(document.getElementById('range-cbt-mtk')?.value || 0);
                const ipas = parseFloat(document.getElementById('range-cbt-ipas')?.value || 0);
                const inggris = parseFloat(document.getElementById('range-cbt-inggris')?.value || 0);

                const avg = ((indo + mtk + ipas + inggris) / 4).toFixed(1);
                
                const headerScore = document.getElementById('header-live-score');
                if (headerScore) headerScore.textContent = avg + '%';

                const bottomScore = document.getElementById('bottom-score-badge');
                if (bottomScore) bottomScore.textContent = avg + '%';

                const predikat = document.getElementById('cbt-predikat-label');
                if (predikat) {
                    if (avg >= 85) predikat.textContent = 'Sangat Baik (A)';
                    else if (avg >= 75) predikat.textContent = 'Baik (B)';
                    else if (avg >= 65) predikat.textContent = 'Cukup (C)';
                    else predikat.textContent = 'Perlu Bimbingan (D)';
                }
            }

            // Modal Rekapitulasi 4 Kelompok Toggle
            function toggleRekapModal() {
                const modal = document.getElementById('modal-rekap-backdrop');
                if (modal) modal.classList.toggle('hidden');
            }

            // Modal CBT Import
            function openCbtImportModal() {
                document.getElementById('cbt-modal-backdrop')?.classList.remove('hidden');
            }
            function closeCbtImportModal() {
                document.getElementById('cbt-modal-backdrop')?.classList.add('hidden');
            }
            function simulateImportSuccess() {
                closeCbtImportModal();
                alert('Berhasil mengimpor 4 nilai CBT untuk {{ $activeSiswa["name"] }}!');
            }

            // Save active assessment
            function saveActiveAssessment() {
                alert('Alhamdulillah! Nilai asesmen {{ $activeGuru["kelompok_short"] }} untuk ananda {{ $activeSiswa["name"] }} berhasil disimpan secara permanen.');
            }
        </script>

    </body>
</html>
