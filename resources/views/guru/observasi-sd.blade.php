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
                <div data-three-alert="success" class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-3 text-xs text-emerald-800 dark:text-emerald-300 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
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
                        </div>
                        <p class="text-[11px] text-muted-foreground mt-0.5">
                            Pengamat Aktif: <strong class="text-foreground">{{ $activeGuru['name'] }}</strong> ({{ $activeGuru['nip'] }}) &bull; {{ $activeGuru['pos_desc'] }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto text-[11px] flex-wrap">

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

            <!-- 2. DAFTAR CALON SISWA — Tombol Pilih Siswa + Tab Sesi + Modal -->
            @php
                $sentraKeyForList = $activeGuru['kelompok_key'] ?? 'ubudiyah';
                $totalSiswa = count($siswaSD);
                $sudahDinilaiCount = collect($siswaSD)->filter(fn($s) => !empty($s[$sentraKeyForList]['nilai']))->count();

                $siswaByNo = collect($siswaSD)->keyBy('no');
                $rawSesi = array_filter(array_map('trim', explode(',', (string) request()->query('sesi', ''))));
                $sesiNos = collect($rawSesi)->filter(fn($no) => $siswaByNo->has($no))->unique()->values()->toArray();
                if (!empty($activeSiswa['no']) && !in_array($activeSiswa['no'], $sesiNos, true)) {
                    if (empty($sesiNos)) {
                        $sesiNos = [$activeSiswa['no']];
                    } else {
                        array_unshift($sesiNos, $activeSiswa['no']);
                    }
                }
            @endphp

            <div class="rounded-xl border border-border bg-card p-2.5 sm:p-3 space-y-2.5">
                <div class="flex items-center justify-between gap-3">
                    <!-- Info Siswa Aktif -->
                    <div class="flex items-center gap-2.5 min-w-0">
                        <i data-lucide="users" class="h-4 w-4 text-muted-foreground shrink-0"></i>
                        <div class="min-w-0">
                            @if(!empty($activeSiswa))
                                <div id="top-bar-siswa-name" class="text-xs font-semibold text-foreground truncate">{{ $activeSiswa['name'] }}</div>
                                <div id="top-bar-siswa-no" class="text-[10px] font-mono text-muted-foreground">{{ $activeSiswa['no'] }} &bull; Sedang dinilai</div>
                            @else
                                <div id="top-bar-siswa-name" class="text-xs font-semibold text-foreground">Pilih Siswa</div>
                                <div id="top-bar-siswa-no" class="text-[10px] text-muted-foreground">Bisa pilih 1 atau beberapa siswa sekaligus</div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol + Progress -->
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex flex-col items-end text-[10px] text-muted-foreground">
                            <span class="font-mono text-emerald-400 font-semibold">{{ $sudahDinilaiCount }}/{{ $totalSiswa }}</span>
                            <span>dinilai</span>
                        </div>
                        <button
                            type="button"
                            onclick="openSiswaModal()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-border bg-muted hover:bg-muted/80 text-foreground text-xs font-medium transition-colors cursor-pointer"
                        >
                            <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                            <span>Pilih Siswa</span>
                            <span class="hidden sm:inline text-muted-foreground">({{ $totalSiswa }})</span>
                        </button>
                    </div>
                </div>

                <!-- Tab Bar Sesi Multi-Siswa (tampil saat memilih > 1 siswa) -->
                <div id="sesi-tabs-wrapper" class="{{ count($sesiNos) > 1 ? '' : 'hidden' }} pt-2 border-t border-border/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div id="sesi-tabs-list" class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                        {{-- Rendered/hydrated by JS --}}
                    </div>
                    <button
                        type="button"
                        id="btn-save-all-sesi"
                        onclick="saveAllSesi()"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-semibold shadow-xs transition-colors cursor-pointer shrink-0"
                    >
                        <i data-lucide="save-all" class="h-3.5 w-3.5"></i>
                        <span>Simpan Semua</span>
                    </button>
                </div>

                <!-- Progress bar mini -->
                @if($totalSiswa > 0)
                <div class="h-1 bg-muted rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ round($sudahDinilaiCount / $totalSiswa * 100) }}%"></div>
                </div>
                @endif
            </div>

            <!-- Modal Pilih Siswa -->
            <div
                id="siswa-modal"
                class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 hidden"
                role="dialog"
                aria-modal="true"
                aria-labelledby="siswa-modal-title"
            >
                <!-- Backdrop -->
                <div
                    id="siswa-modal-backdrop"
                    class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                    onclick="closeSiswaModal()"
                ></div>

                <!-- Panel -->
                <div
                    id="siswa-modal-panel"
                    class="relative w-full sm:max-w-lg bg-card border border-border rounded-t-2xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[85vh] sm:max-h-[75vh] overflow-hidden"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-3 border-b border-border shrink-0">
                        <div class="flex items-center gap-2">
                            <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                            <span id="siswa-modal-title" class="text-sm font-semibold text-foreground">Pilih Siswa</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-muted border border-border text-muted-foreground">{{ $totalSiswa }}</span>
                        </div>
                        <button
                            type="button"
                            onclick="closeSiswaModal()"
                            class="inline-flex items-center justify-center h-7 w-7 rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer"
                            aria-label="Tutup"
                        >
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <!-- Search + Hint -->
                    <div class="px-4 py-2.5 border-b border-border shrink-0 space-y-1.5">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-muted-foreground pointer-events-none"></i>
                            <input
                                type="text"
                                id="siswa-search"
                                placeholder="Cari nama atau nomor pendaftaran..."
                                oninput="filterSiswaList(this.value)"
                                class="w-full pl-8 pr-3 py-2 text-xs rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors"
                                autocomplete="off"
                            >
                        </div>
                        <p class="text-[10px] text-muted-foreground">
                            Klik nama untuk menilai 1 siswa, atau centang kotak di kiri untuk menilai beberapa siswa bersamaan.
                        </p>
                    </div>

                    <!-- Daftar Siswa Scrollable -->
                    <div class="overflow-y-auto flex-1" id="siswa-modal-list">
                        @php
                            $sortedSiswaSD = collect($siswaSD)->sortBy(function ($item) use ($sentraKeyForList) {
                                return !empty($item[$sentraKeyForList]['nilai']) ? 1 : 0;
                            })->values();
                        @endphp
                        @forelse($sortedSiswaSD as $s)
                            @php
                                $isActive = ($s['no'] === ($activeSiswa['no'] ?? ''));
                                $isInSesi = in_array($s['no'], $sesiNos, true);
                                $myScoreData = $s[$sentraKeyForList] ?? [];
                                $sudahDinilai = !empty($myScoreData['nilai']);
                                $nilaiRuang = $sudahDinilai ? number_format($myScoreData['nilai'], 1).'%' : null;
                            @endphp
                            <div
                                class="siswa-item flex items-center gap-2.5 px-4 py-2.5 border-b border-border/60 hover:bg-muted/50 transition-colors {{ $isActive ? 'bg-primary/5' : '' }}"
                                data-name="{{ strtolower($s['name']) }}"
                                data-no="{{ strtolower($s['no']) }}"
                            >
                                <label class="flex items-center justify-center p-1 cursor-pointer shrink-0" title="Tambahkan ke sesi kelompok">
                                    <input
                                        type="checkbox"
                                        class="siswa-checkbox h-4 w-4 rounded border-border accent-emerald-600 cursor-pointer"
                                        value="{{ $s['no'] }}"
                                        {{ $isInSesi && count($sesiNos) > 1 ? 'checked' : '' }}
                                        onchange="onSiswaCheckboxChange()"
                                    >
                                </label>

                                <a
                                    href="{{ route('guru.observasi', ['guru_nip' => $activeGuru['nip'], 'siswa_no' => $s['no']]) }}"
                                    class="flex-1 flex items-center justify-between gap-3 min-w-0 py-0.5"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <!-- Avatar initials -->
                                        <div class="h-8 w-8 rounded-lg flex items-center justify-center text-[10px] font-bold shrink-0 {{ $isActive ? 'bg-primary text-primary-foreground' : ($sudahDinilai ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-muted text-muted-foreground border border-border') }}">
                                            {{ substr($s['name'], 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold text-foreground truncate leading-tight">{{ $s['name'] }}</div>
                                            <div class="text-[10px] font-mono text-muted-foreground">{{ $s['no'] }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($isActive)
                                            <span class="text-[9px] font-mono px-1.5 py-0.5 rounded bg-primary/10 text-primary border border-primary/30 font-bold">Aktif</span>
                                        @endif
                                        @if($sudahDinilai)
                                            <span class="siswa-modal-badge text-[9px] font-mono px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold">{{ $nilaiRuang }}</span>
                                        @else
                                            <span class="siswa-modal-badge text-[9px] px-1.5 py-0.5 rounded bg-muted text-muted-foreground border border-border">Belum</span>
                                        @endif
                                        <i data-lucide="chevron-right" class="h-3.5 w-3.5 text-muted-foreground"></i>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-muted-foreground">
                                Belum ada calon siswa terdaftar.
                            </div>
                        @endforelse

                        <!-- Empty state saat search tidak ketemu -->
                        <div id="siswa-empty-state" class="hidden py-10 text-center text-xs text-muted-foreground">
                            <i data-lucide="search-x" class="h-6 w-6 mx-auto mb-2 opacity-40"></i>
                            <p>Siswa tidak ditemukan.</p>
                        </div>
                    </div>

                    <!-- Footer: Legend + Multi-select action -->
                    <div class="px-4 py-2.5 border-t border-border shrink-0 flex items-center justify-between gap-2 bg-muted/20">
                        <div id="siswa-legend" class="flex items-center gap-3 text-[10px] text-muted-foreground">
                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Sudah ({{ $sudahDinilaiCount }})</span>
                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-zinc-500"></span> Belum ({{ $totalSiswa - $sudahDinilaiCount }})</span>
                        </div>
                        <div id="siswa-multi-actions" class="hidden flex items-center gap-2 ml-auto">
                            <button type="button" onclick="resetSiswaSelection()" class="text-[11px] text-muted-foreground hover:text-foreground px-2 py-1 cursor-pointer">
                                Reset
                            </button>
                            <button
                                type="button"
                                id="btn-start-multi-sesi"
                                onclick="startMultiSesi()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                            >
                                <i data-lucide="play" class="h-3 w-3"></i>
                                <span id="btn-start-multi-label">Mulai Nilai</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($activeSiswa))
            <!-- 3. PROFIL MURID & STATUS KELENGKAPAN 3 SENTRA -->
            @php
                $curSentraKey = $activeGuru['kelompok_key'] ?? 'ubudiyah';
                $curSentraData = $activeSiswa[$curSentraKey] ?? [];
                $curScore = !empty($curSentraData['nilai']) ? number_format($curSentraData['nilai'], 1).'%' : null;
                $currentPengamatNama = $curSentraData['guru_nama'] ?? null;
                $sudahDinilai = !empty($curSentraData['nilai']);
            @endphp

            <div class="rounded-xl border border-border bg-card p-3 sm:p-5 space-y-4 shadow-sm">
                
                <!-- Student Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-border">
                    <div class="flex items-start gap-3">
                        <div id="active-siswa-avatar" class="h-12 w-12 rounded-xl bg-muted border border-border flex items-center justify-center text-sm font-bold text-foreground shrink-0 shadow-inner">
                            {{ substr($activeSiswa['name'] ?? 'S', 0, 2) }}
                        </div>
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 id="active-siswa-name" class="text-sm sm:text-base font-bold text-foreground">{{ $activeSiswa['name'] }}</h2>
                                <span id="active-siswa-no" class="font-mono text-[10px] px-2 py-0.5 rounded border border-border bg-muted text-muted-foreground font-semibold">
                                    {{ $activeSiswa['no'] }}
                                </span>
                            </div>
                            <p id="active-siswa-meta" class="text-xs text-muted-foreground">
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

                

            </div>

            <!-- 4. FORM PENILAIAN INDIKATOR SENTRA INI -->
            @php
                $sentrasMeta = [
                    'ubudiyah'   => ['label' => 'Sentra Ubudiyah',        'color' => 'emerald', 'icon' => 'book-open'],
                    'persiapan'  => ['label' => 'Sentra Persiapan',       'color' => 'indigo',  'icon' => 'pencil'],
                    'eksplorasi' => ['label' => 'Sentra Eksplorasi Diri', 'color' => 'amber',   'icon' => 'sparkles'],
                ];
                $meta = $sentrasMeta[$curSentraKey] ?? $sentrasMeta['ubudiyah'];

                $indikatorMap = !empty($indikatorMap) ? $indikatorMap : [
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

                <div class="rounded-xl border border-border bg-card shadow-sm overflow-hidden">
                    
                    <!-- Section Header -->
                    <div class="p-3.5 sm:p-4 border-b border-border bg-muted/30 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-{{ $meta['color'] }}-500/10 text-{{ $meta['color'] }}-400 border border-{{ $meta['color'] }}-500/20">
                                <i data-lucide="{{ $meta['icon'] }}" class="h-4 w-4"></i>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-foreground">Form Penilaian: {{ $meta['label'] }}</h3>
                                <p id="form-status-subtitle" class="text-[10px] text-muted-foreground">
                                    @if($sudahDinilai)
                                        <span class="text-emerald-400 font-semibold">Sudah Dinilai &bull; Nilai: {{ $curScore }}</span>
                                    @else
                                        <span>Siap Dinilai &bull; Silakan pilih skor indikator</span>
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
                    <fieldset class="p-3 sm:p-5 space-y-6">
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
                                                        onclick="setScore('{{ $ind['id'] }}', {{ $val }}, this)" 
                                                        data-id="{{ $ind['id'] }}" 
                                                        data-score="{{ $val }}"
                                                        class="score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center {{ $isSelected ? $activeClass : 'border-border bg-card text-foreground hover:bg-muted active:scale-95' }}"
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
                                placeholder="Tuliskan catatan khusus terkait karakter, kemandirian, hambatan, atau keistimewaan ananda selama observasi..." 
                                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary resize-y"
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
                            <button
                                type="button"
                                onclick="switchToNextSesiSiswa()"
                                class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                title="Berpindah ke siswa lain yang sudah dipilih"
                            >
                                <i data-lucide="arrow-right-left" class="h-3.5 w-3.5 text-muted-foreground"></i>
                                <span>Siswa Lain</span>
                            </button>

                            <button 
                                type="submit" 
                                id="btn-save-desktop" 
                                class="inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white px-4 text-xs font-bold shadow transition-colors cursor-pointer"
                            >
                                <i data-lucide="check" class="h-4 w-4"></i>
                                <span>Simpan Nilai</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
            @else
                @if(empty($siswaSD))
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
                @else
                    <div class="rounded-xl border border-dashed border-border bg-card p-8 sm:p-10 text-center space-y-4">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <i data-lucide="user-search" class="h-6 w-6"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm sm:text-base font-semibold text-foreground">Pilih Calon Siswa Terlebih Dahulu</h3>
                            <p class="text-xs text-muted-foreground max-w-md mx-auto">
                                Form penilaian akan muncul setelah Anda memilih calon siswa yang akan diobservasi di ruang <strong class="text-foreground">{{ $activeGuru['kelompok_short'] ?? 'ini' }}</strong>.
                            </p>
                        </div>
                        <div>
                            <button
                                type="button"
                                onclick="openSiswaModal()"
                                class="inline-flex h-9 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-xs font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 transition-colors cursor-pointer"
                            >
                                <i data-lucide="search" class="h-3.5 w-3.5"></i>
                                <span>Pilih Siswa ({{ count($siswaSD) }})</span>
                            </button>
                        </div>
                    </div>
                @endif
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
                    <button
                        type="button"
                        onclick="switchToNextSesiSiswa()"
                        class="inline-flex h-9 items-center justify-center px-2.5 rounded-lg border border-border bg-card text-foreground/90 text-[11px] font-medium cursor-pointer"
                        title="Berpindah ke siswa lain yang sudah dipilih"
                    >
                        <i data-lucide="arrow-right-left" class="h-3.5 w-3.5 mr-1 text-muted-foreground"></i>
                        <span>Siswa Lain</span>
                    </button>

                        <button 
                            type="button" 
                            onclick="document.getElementById('form-observasi-sd')?.requestSubmit()" 
                            id="btn-save-mobile" 
                            class="inline-flex h-9 items-center justify-center gap-1.5 px-3.5 rounded-lg bg-emerald-600 text-white text-xs font-bold shadow active:scale-95 transition-transform cursor-pointer"
                        >
                            <i data-lucide="check" class="h-4 w-4"></i>
                            <span>Simpan Nilai</span>
                        </button>
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
                            <p id="sd-rekap-siswa-info" class="text-[11px] text-muted-foreground">{{ $activeSiswa['name'] ?? '-' }} ({{ $activeSiswa['no'] ?? '-' }})</p>
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
        @php
            $jsSentraKey = $curSentraKey ?? ($activeGuru['kelompok_key'] ?? 'ubudiyah');
            $siswaDataMapPhp = collect($siswaSD ?? [])->mapWithKeys(function ($s) use ($jsSentraKey) {
                return [
                    $s['no'] => [
                        'no' => $s['no'],
                        'name' => $s['name'],
                        'kelas_target' => $s['kelas_target'] ?? 'Kelas 1',
                        'asal_sekolah' => $s['asal_sekolah'] ?? 'SD Alam Anak Saleh',
                        'wali' => $s['wali'] ?? '-',
                        'nilai_akhir_terpadu' => $s['nilai_akhir_terpadu'] ?? null,
                        'sentra' => [
                            'nilai' => $s[$jsSentraKey]['nilai'] ?? null,
                            'catatan' => $s[$jsSentraKey]['catatan'] ?? '',
                            'detail' => $s[$jsSentraKey]['detail'] ?? [],
                        ],
                        'ubudiyah' => ['nilai' => $s['ubudiyah']['nilai'] ?? null],
                        'persiapan' => ['nilai' => $s['persiapan']['nilai'] ?? null],
                        'eksplorasi' => ['nilai' => $s['eksplorasi']['nilai'] ?? null],
                    ],
                ];
            })->toArray();
        @endphp
        <script>
            const rubrikData      = @json($indikatorMap ?? []);
            const myKey           = @json($jsSentraKey);
            const myColor         = @json($meta['color'] ?? 'emerald');
            const guruNip         = @json($activeGuru['nip'] ?? '');
            const observasiUrl    = @json(route('guru.observasi'));
            const siswaDataMap    = @json($siswaDataMapPhp);
            let   sesiNos         = @json(array_values($sesiNos ?? []));
            let   currentSiswaNo  = @json($activeSiswa['no'] ?? '');

            const activeColorClass = {
                'indigo': 'bg-indigo-600 text-white border-indigo-600 shadow-sm ring-2 ring-indigo-500/30',
                'amber':  'bg-amber-600 text-white border-amber-600 shadow-sm ring-2 ring-amber-500/30',
                'emerald':'bg-emerald-600 text-white border-emerald-600 shadow-sm ring-2 ring-emerald-500/30',
            }[myColor] || 'bg-emerald-600 text-white border-emerald-600 shadow-sm ring-2 ring-emerald-500/30';

            const defaultBtnClass = 'score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center border-border bg-card text-foreground hover:bg-muted active:scale-95';
            const draftStorageKey = `saans_sd_drafts_${guruNip}_${myKey}`;

            let scores = {};
            let sessionDrafts = {};

            try {
                const stored = localStorage.getItem(draftStorageKey);
                if (stored) sessionDrafts = JSON.parse(stored) || {};
            } catch (e) { sessionDrafts = {}; }

            function persistDrafts() {
                try { localStorage.setItem(draftStorageKey, JSON.stringify(sessionDrafts)); } catch (e) {}
            }

            function getEffectiveState(no) {
                const draft = sessionDrafts[no];
                const saved = siswaDataMap[no]?.sentra || {};
                const baseDetail = (saved.detail && !Array.isArray(saved.detail)) ? { ...saved.detail } : {};
                return {
                    scores: draft?.scores ? { ...draft.scores } : baseDetail,
                    catatan: draft?.catatan !== undefined ? draft.catatan : (saved.catatan || ''),
                };
            }

            // Pre-populate active student's scores
            if (currentSiswaNo) {
                const initState = getEffectiveState(currentSiswaNo);
                scores = initState.scores;
                const catEl = document.getElementById('input-catatan');
                if (catEl && sessionDrafts[currentSiswaNo]?.catatan !== undefined) {
                    catEl.value = initState.catatan;
                }
            }

            function syncCurrentToDraft() {
                if (!currentSiswaNo) return;
                const catEl = document.getElementById('input-catatan');
                sessionDrafts[currentSiswaNo] = {
                    scores: { ...scores },
                    catatan: catEl ? catEl.value : '',
                };
                persistDrafts();
            }

            document.getElementById('input-catatan')?.addEventListener('input', () => {
                syncCurrentToDraft();
            });

            function setScore(indId, val, btn) {
                scores[indId] = val;

                // Update Button Visuals
                const group = btn.closest('[data-indikator]');
                group.querySelectorAll('.score-btn').forEach(b => {
                    b.className = defaultBtnClass;
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

                syncCurrentToDraft();
                updateLiveScore();
                renderSesiTabs();
            }

            function updateLiveScore() {
                const inds = rubrikData[myKey] || [];
                const hLive = document.getElementById('header-score-live');
                const bDesktop = document.getElementById('bottom-score-desktop');
                const bMobile = document.getElementById('bottom-score-badge');

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
                    if (hLive) hLive.textContent = persen;
                    if (bDesktop) bDesktop.textContent = persen;
                    if (bMobile) bMobile.textContent = persen;
                } else {
                    const savedNilai = siswaDataMap[currentSiswaNo]?.sentra?.nilai;
                    const fallback = savedNilai ? Number(savedNilai).toFixed(1) + '%' : '—';
                    if (hLive) hLive.textContent = fallback;
                    if (bDesktop) bDesktop.textContent = fallback;
                    if (bMobile) bMobile.textContent = fallback;
                }
            }

            // ── Tab Bar Sesi Multi-Siswa ──────────────────────────────────
            function renderSesiTabs() {
                const wrapper = document.getElementById('sesi-tabs-wrapper');
                const listEl  = document.getElementById('sesi-tabs-list');
                if (!wrapper || !listEl) return;

                if (sesiNos.length <= 1) {
                    wrapper.classList.add('hidden');
                    return;
                }

                wrapper.classList.remove('hidden');
                const inds = rubrikData[myKey] || [];
                const totalInd = inds.length;

                listEl.innerHTML = '';
                sesiNos.forEach(no => {
                    const s = siswaDataMap[no];
                    if (!s) return;

                    const isActive = (no === currentSiswaNo);
                    const state = isActive ? { scores } : getEffectiveState(no);
                    const filledCount = inds.filter(i => state.scores[i.id]).length;
                    const savedNilai = s.sentra?.nilai;

                    let badgeText = `${filledCount}/${totalInd}`;
                    let badgeCls  = 'bg-muted text-muted-foreground';
                    if (savedNilai && !sessionDrafts[no]) {
                        badgeText = `✓ ${Number(savedNilai).toFixed(0)}%`;
                        badgeCls  = 'bg-emerald-500/15 text-emerald-400';
                    } else if (filledCount === totalInd && totalInd > 0) {
                        badgeText = `${filledCount}/${totalInd} Siap`;
                        badgeCls  = 'bg-amber-500/15 text-amber-400';
                    }

                    const shortName = s.name.split(' ').slice(0, 2).join(' ');
                    const tab = document.createElement('div');
                    tab.className = `inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-xs shrink-0 transition-all cursor-pointer ${
                        isActive
                            ? 'border-primary bg-primary/10 text-foreground font-semibold shadow-xs'
                            : 'border-border bg-muted/40 text-muted-foreground hover:text-foreground hover:bg-muted'
                    }`;
                    tab.onclick = (e) => {
                        if (e.target.closest('.btn-remove-tab')) return;
                        switchSiswaTab(no);
                    };

                    tab.innerHTML = `
                        <span class="truncate max-w-[110px]">${shortName}</span>
                        <span class="text-[9px] font-mono px-1.5 py-0.5 rounded ${badgeCls}">${badgeText}</span>
                        <button type="button" onclick="removeFromSesi('${no}')" class="btn-remove-tab ml-0.5 text-muted-foreground hover:text-rose-400 leading-none px-0.5 cursor-pointer" title="Keluarkan dari sesi">&times;</button>
                    `;
                    listEl.appendChild(tab);
                });
            }

            function switchSiswaTab(targetNo) {
                if (!targetNo || targetNo === currentSiswaNo || !siswaDataMap[targetNo]) return;

                syncCurrentToDraft();
                currentSiswaNo = targetNo;

                // Update URL without reload
                const url = new URL(window.location.href);
                url.searchParams.set('siswa_no', targetNo);
                if (sesiNos.length > 1) {
                    url.searchParams.set('sesi', sesiNos.join(','));
                }
                window.history.replaceState({}, '', url.toString());

                const s = siswaDataMap[targetNo];
                const state = getEffectiveState(targetNo);
                scores = state.scores;

                // Update header & student card
                const topName = document.getElementById('top-bar-siswa-name');
                const topNo   = document.getElementById('top-bar-siswa-no');
                if (topName) topName.textContent = s.name;
                if (topNo)   topNo.textContent   = `${s.no} • Sedang dinilai`;

                const avatarEl = document.getElementById('active-siswa-avatar');
                const nameEl   = document.getElementById('active-siswa-name');
                const noEl     = document.getElementById('active-siswa-no');
                const metaEl   = document.getElementById('active-siswa-meta');
                if (avatarEl) avatarEl.textContent = s.name.substring(0, 2);
                if (nameEl)   nameEl.textContent   = s.name;
                if (noEl)     noEl.textContent     = s.no;
                if (metaEl)   metaEl.innerHTML     = `Target: <strong class="text-foreground">${s.kelas_target}</strong> &bull; Asal: ${s.asal_sekolah} &bull; Wali: ${s.wali}`;

                // Update form hidden siswa_no & subtitle
                const hiddenNo = document.querySelector('#form-observasi-sd input[name="siswa_no"]');
                if (hiddenNo) hiddenNo.value = s.no;

                const subtitleEl = document.getElementById('form-status-subtitle');
                if (subtitleEl) {
                    if (s.sentra?.nilai) {
                        subtitleEl.innerHTML = `<span class="text-emerald-400 font-semibold">Sudah Dinilai &bull; Nilai: ${Number(s.sentra.nilai).toFixed(1)}%</span>`;
                    } else {
                        subtitleEl.innerHTML = `<span>Siap Dinilai &bull; Silakan pilih skor indikator</span>`;
                    }
                }

                // Update all indicator buttons & hints
                const inds = rubrikData[myKey] || [];
                document.querySelectorAll('[data-indikator]').forEach(group => {
                    const indId = group.dataset.indikator;
                    const val   = scores[indId] ? Number(scores[indId]) : null;
                    group.querySelectorAll('.score-btn').forEach(b => {
                        const bVal = Number(b.dataset.score);
                        b.className = (val && bVal === val)
                            ? `score-btn h-10 rounded-lg border text-xs font-bold transition-all cursor-pointer flex items-center justify-center ${activeColorClass}`
                            : defaultBtnClass;
                    });
                    const badgeEl = document.getElementById('badge-score-' + indId);
                    if (badgeEl) badgeEl.textContent = val || '-';

                    const hintEl = document.getElementById('hint-' + indId);
                    if (hintEl) {
                        const ind = inds.find(i => i.id === indId);
                        hintEl.textContent = val ? (ind?.rubrik?.[val] ?? '') : 'Pilih skor 1–4 untuk melihat rubrik deskripsi capaian ananda.';
                    }
                });

                // Update catatan
                const catEl = document.getElementById('input-catatan');
                if (catEl) catEl.value = state.catatan || '';

                // Reset save button text
                const btnDesktop = document.getElementById('btn-save-desktop');
                const btnMobile  = document.getElementById('btn-save-mobile');
                if (btnDesktop) btnDesktop.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i><span>Simpan Nilai</span>';
                if (btnMobile)  btnMobile.innerHTML  = '<i data-lucide="check" class="h-4 w-4"></i><span>Simpan Nilai</span>';

                // Update Rekap Modal
                const rekapInfo = document.getElementById('sd-rekap-siswa-info');
                if (rekapInfo) rekapInfo.textContent = `${s.name} (${s.no})`;
                ['ubudiyah', 'persiapan', 'eksplorasi'].forEach(k => {
                    const el = document.getElementById('sd-rekap-score-' + k);
                    if (el) el.textContent = s[k]?.nilai ? Number(s[k].nilai).toFixed(1) + '%' : 'Belum Lengkap';
                });
                const rekapAkhir = document.getElementById('sd-rekap-score-akhir');
                if (rekapAkhir) rekapAkhir.textContent = s.nilai_akhir_terpadu ? Number(s.nilai_akhir_terpadu).toFixed(1) + '%' : 'Dalam Proses';

                updateLiveScore();
                renderSesiTabs();
                if (window.refreshLucideIcons) window.refreshLucideIcons();
                if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
            }

            function removeFromSesi(targetNo) {
                sesiNos = sesiNos.filter(n => n !== targetNo);
                const url = new URL(window.location.href);
                if (sesiNos.length > 1) {
                    url.searchParams.set('sesi', sesiNos.join(','));
                } else {
                    url.searchParams.delete('sesi');
                }
                window.history.replaceState({}, '', url.toString());

                if (targetNo === currentSiswaNo && sesiNos.length > 0) {
                    switchSiswaTab(sesiNos[0]);
                } else {
                    renderSesiTabs();
                }
            }

            function switchToNextSesiSiswa() {
                if (sesiNos.length > 1) {
                    const idx = sesiNos.indexOf(currentSiswaNo);
                    const nextIdx = (idx + 1) % sesiNos.length;
                    switchSiswaTab(sesiNos[nextIdx]);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
                openSiswaModal();
            }

            // ── Multi-Select Modal Helpers ────────────────────────────────
            function onSiswaCheckboxChange() {
                const checked = document.querySelectorAll('#siswa-modal-list .siswa-checkbox:checked');
                const count = checked.length;
                const legend = document.getElementById('siswa-legend');
                const actions = document.getElementById('siswa-multi-actions');
                const label = document.getElementById('btn-start-multi-label');

                if (count > 0) {
                    legend?.classList.add('hidden');
                    actions?.classList.remove('hidden');
                    if (label) label.textContent = `Mulai Nilai (${count} Siswa)`;
                } else {
                    legend?.classList.remove('hidden');
                    actions?.classList.add('hidden');
                }
            }

            function resetSiswaSelection() {
                document.querySelectorAll('#siswa-modal-list .siswa-checkbox:checked').forEach(cb => {
                    cb.checked = false;
                });
                onSiswaCheckboxChange();
            }

            function startMultiSesi() {
                const checked = Array.from(document.querySelectorAll('#siswa-modal-list .siswa-checkbox:checked')).map(cb => cb.value);
                if (checked.length === 0) return;

                const url = new URL(observasiUrl, window.location.origin);
                url.searchParams.set('guru_nip', guruNip);
                url.searchParams.set('siswa_no', checked[0]);
                if (checked.length > 1) {
                    url.searchParams.set('sesi', checked.join(','));
                }
                window.location.href = url.toString();
            }

            function showToast(message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-16 left-1/2 -translate-x-1/2 z-50 flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-950/90 backdrop-blur-md px-4 py-2.5 text-xs font-semibold text-emerald-300 shadow-2xl';
                toast.innerHTML = `<svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>${message}</span>`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3500);
            }

            function applySavedResultToUi(siswaNo, kelompokKey, nilai, sentraScores, catatan) {
                if (siswaDataMap[siswaNo]) {
                    siswaDataMap[siswaNo].sentra = {
                        nilai: nilai,
                        catatan: catatan,
                        detail: { ...sentraScores },
                    };
                    if (siswaDataMap[siswaNo][kelompokKey]) {
                        siswaDataMap[siswaNo][kelompokKey].nilai = nilai;
                    }
                }
                delete sessionDrafts[siswaNo];
                persistDrafts();

                const modalItem = document.querySelector(`#siswa-modal-list .siswa-item[data-no="${siswaNo.toLowerCase()}"]`);
                if (modalItem) {
                    const badgeEl = modalItem.querySelector('.siswa-modal-badge');
                    if (badgeEl) {
                        badgeEl.className = 'siswa-modal-badge text-[9px] font-mono px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold';
                        badgeEl.textContent = nilai + '%';
                    }
                    const avatar = modalItem.querySelector('.h-8.w-8');
                    if (avatar) {
                        avatar.classList.remove('bg-muted', 'text-muted-foreground', 'border-border');
                        avatar.classList.add('bg-emerald-500/15', 'text-emerald-400', 'border-emerald-500/30');
                    }

                    // Pindahkan siswa yang sudah dinilai ke bagian bawah daftar
                    const listContainer = document.getElementById('siswa-modal-list');
                    const emptyEl = document.getElementById('siswa-empty-state');
                    if (listContainer && emptyEl) {
                        listContainer.insertBefore(modalItem, emptyEl);
                    }
                }
            }

            async function saveOneSiswa(siswaNo, kelompokKey, sentraScores, catatan) {
                const response = await fetch('{{ route("guru.observasi.simpan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        siswa_no: siswaNo,
                        kelompok_key: kelompokKey,
                        scores: sentraScores,
                        detail_nilai: sentraScores,
                        catatan: catatan,
                        status: 'Selesai',
                    }),
                });
                const data = await response.json();
                if (!data.success) {
                    throw new Error(data.message || 'Gagal menyimpan.');
                }
                applySavedResultToUi(siswaNo, kelompokKey, data.nilai, sentraScores, catatan);
                return data;
            }

            async function submitObservasiSD(e) {
                e.preventDefault();

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
                    const data = await saveOneSiswa(siswaNo, kelompokKey, scores, catatan);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    const namaSiswa = siswaDataMap[siswaNo]?.name || siswaNo;
                    showToast(`Nilai ${namaSiswa} berhasil disimpan!`);

                    if (btnDesktop) { btnDesktop.innerHTML = '<svg class="h-4 w-4 inline mr-1 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>✓ Tersimpan</span>'; btnDesktop.disabled = false; }
                    if (btnMobile) { btnMobile.innerHTML = '<svg class="h-4 w-4 inline mr-1 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>✓ Tersimpan</span>'; btnMobile.disabled = false; }

                    const headerScore = document.getElementById('header-score-live');
                    if (headerScore) headerScore.textContent = data.nilai + '%';

                    const rekapScore = document.getElementById('sd-rekap-score-' + kelompokKey);
                    if (rekapScore) rekapScore.textContent = data.nilai + '%';

                    renderSesiTabs();
                    if (window.refreshLucideIcons) window.refreshLucideIcons();
                    if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
                } catch (err) {
                    alert('Gagal menyimpan: ' + err.message);
                    if (btnDesktop) { btnDesktop.disabled = false; btnDesktop.innerHTML = '<svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>Simpan Nilai</span>'; }
                    if (btnMobile) { btnMobile.disabled = false; btnMobile.innerHTML = '<svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>Simpan Nilai</span>'; }
                }

                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            async function saveAllSesi() {
                syncCurrentToDraft();
                const inds = rubrikData[myKey] || [];
                const readyList = [];
                const incompleteNames = [];

                sesiNos.forEach(no => {
                    const state = getEffectiveState(no);
                    const filledCount = inds.filter(i => state.scores[i.id]).length;
                    if (filledCount === inds.length && inds.length > 0) {
                        readyList.push({ no, state });
                    } else if (filledCount > 0) {
                        incompleteNames.push(`${siswaDataMap[no]?.name || no} (${filledCount}/${inds.length})`);
                    }
                });

                if (readyList.length === 0) {
                    alert(incompleteNames.length > 0
                        ? `Belum ada siswa yang penilaiannya lengkap:\n- ${incompleteNames.join('\n- ')}`
                        : 'Silakan lengkapi penilaian minimal 1 siswa terlebih dahulu.');
                    return;
                }

                const btnAll = document.getElementById('btn-save-all-sesi');
                if (btnAll) { btnAll.disabled = true; btnAll.textContent = 'Menyimpan...'; }

                try {
                    let savedCount = 0;
                    for (const item of readyList) {
                        const data = await saveOneSiswa(item.no, myKey, item.state.scores, item.state.catatan);
                        if (item.no === currentSiswaNo) {
                            const headerScore = document.getElementById('header-score-live');
                            if (headerScore) headerScore.textContent = data.nilai + '%';
                        }
                        savedCount++;
                    }
                    renderSesiTabs();
                    const note = incompleteNames.length > 0 ? ` (${incompleteNames.length} siswa belum lengkap)` : '';
                    showToast(`Alhamdulillah! ${savedCount} siswa berhasil disimpan${note}.`);
                } catch (err) {
                    alert('Sebagian gagal disimpan: ' + err.message);
                } finally {
                    if (btnAll) {
                        btnAll.disabled = false;
                        btnAll.innerHTML = '<i data-lucide="save-all" class="h-3.5 w-3.5"></i><span>Simpan Semua</span>';
                        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
                    }
                }
            }

            function toggleRekapModal() {
                const modal = document.getElementById('modal-rekap-backdrop');
                if (modal) {
                    modal.classList.toggle('hidden');
                    if (window.refreshLucideIcons) window.refreshLucideIcons();
                }
            }

            // ── Modal Pilih Siswa ──────────────────────────────────────────
            function openSiswaModal() {
                const modal = document.getElementById('siswa-modal');
                const panel = document.getElementById('siswa-modal-panel');
                if (!modal) return;

                // Reset search & sync checkbox state
                const searchEl = document.getElementById('siswa-search');
                if (searchEl) { searchEl.value = ''; filterSiswaList(''); }
                onSiswaCheckboxChange();

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Animate in
                requestAnimationFrame(() => {
                    if (panel) {
                        panel.style.transition = 'transform 0.25s cubic-bezier(0.32,0.72,0,1), opacity 0.2s';
                        panel.style.transform   = 'translateY(0)';
                        panel.style.opacity     = '1';
                    }
                });

                if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
                setTimeout(() => searchEl?.focus(), 100);
            }

            function closeSiswaModal() {
                const modal = document.getElementById('siswa-modal');
                const panel = document.getElementById('siswa-modal-panel');
                if (!modal) return;

                if (panel) {
                    panel.style.transition = 'transform 0.2s cubic-bezier(0.32,0.72,0,1), opacity 0.15s';
                    panel.style.transform  = 'translateY(40px)';
                    panel.style.opacity    = '0';
                }

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    if (panel) { panel.style.transform = ''; panel.style.opacity = ''; }
                }, 200);
            }

            function filterSiswaList(query) {
                const q = query.toLowerCase().trim();
                const items = document.querySelectorAll('#siswa-modal-list .siswa-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const name = item.dataset.name || '';
                    const no   = item.dataset.no   || '';
                    const match = !q || name.includes(q) || no.includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                const emptyState = document.getElementById('siswa-empty-state');
                if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
            }

            // Initialize modal panel starting position for animation
            (function () {
                const panel = document.getElementById('siswa-modal-panel');
                if (panel) { panel.style.transform = 'translateY(40px)'; panel.style.opacity = '0'; }
            })();

            // Keyboard Escape — tutup modal siswa atau rekap
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const siswaModal = document.getElementById('siswa-modal');
                    if (siswaModal && !siswaModal.classList.contains('hidden')) {
                        closeSiswaModal();
                        return;
                    }
                    document.getElementById('modal-rekap-backdrop')?.classList.add('hidden');
                }
            });

            // Initial run
            updateLiveScore();
            renderSesiTabs();
        </script>

    </body>
</html>
