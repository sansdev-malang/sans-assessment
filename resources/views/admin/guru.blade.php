<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Guru Pengamat — {{ $user['unit'] ?? 'SAANS' }}</title>
        
        <!-- Theme Initialization Script (Prevents FOUT) -->
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
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-background text-foreground antialiased font-sans flex flex-col">

        <!-- Navbar -->
        <header class="border-b border-border bg-background/80 backdrop-blur sticky top-0 z-40 px-4 lg:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between">

                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white shadow-xs">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-xs font-semibold tracking-tight text-foreground">SAANS</span>
                    </a>

                    <nav class="hidden sm:flex items-center gap-1 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.guru') }}" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground transition-colors">
                            <i data-lucide="users" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>Guru Pengamat</span>
                        </a>
                        <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="notebook-pen" class="h-3.5 w-3.5"></i>
                            <span>Calon Siswa</span>
                        </a>
                        <a href="{{ route('admin.indikator') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                            <span>Indikator</span>
                        </a>
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 pl-2 border-l border-border">
                        <div class="h-7 w-7 rounded-full bg-muted border border-border flex items-center justify-center text-xs font-semibold text-foreground">
                            {{ substr($user['name'] ?? 'Admin', 0, 2) }}
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-foreground">{{ $user['name'] ?? 'Administrator' }}</span>
                    </div>
                    <!-- Jenjang Switcher -->
                    <div class="inline-flex items-center rounded-lg border border-border bg-card p-1 text-xs">
                        <a href="{{ route('admin.guru', ['jenjang' => 'sd']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'sd' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
                            SD Karakter
                        </a>
                        <a href="{{ route('admin.guru', ['jenjang' => 'smp']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'smp' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
                            SMP Karakter
                        </a>
                    </div>
                    
                    <x-theme-settings />

                    <a href="{{ route('logout') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-transparent px-2.5 text-xs font-medium text-muted-foreground hover:bg-card hover:text-foreground transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main -->
        <main class="max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 flex-1 space-y-6">

            <!-- Flash Status / Error Messages -->
            @if (session('status'))
                <div class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-3 text-xs text-emerald-300 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="h-4 w-4 shrink-0 text-emerald-400"></i>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xs cursor-pointer font-bold">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg border border-rose-500/30 bg-rose-500/10 p-3 text-xs text-rose-300 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="h-4 w-4 shrink-0 text-rose-400"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 text-xs cursor-pointer font-bold">&times;</button>
                </div>
            @endif

            <!-- Page Header with Switcher -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">Daftar Guru Pengamat</h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                        @if(($jenjang ?? 'sd') === 'sd')
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-semibold text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Model 3 Sentra
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-2 py-0.5 text-[10px] font-semibold text-indigo-400">
                                Model 4 Kelompok
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Tim guru dan observer yang bertugas melakukan observasi calon siswa baru unit {{ $user['unit'] ?? '-' }}
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <!-- Template CSV -->
                    <a href="{{ route('admin.guru.template', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground hover:bg-muted transition-colors" title="Download Template CSV Guru">
                        <i data-lucide="download" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span class="hidden md:inline">Template CSV</span>
                    </a>

                    <!-- Import Guru -->
                    <button type="button" onclick="openImportModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer" title="Import Guru via CSV">
                        <i data-lucide="upload" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span>Import Guru</span>
                    </button>

                    <!-- Tambah Guru -->
                    <button type="button" onclick="openTambahModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                        <i data-lucide="user-plus" class="h-3.5 w-3.5"></i>
                        <span>Tambah Guru</span>
                    </button>
                </div>
            </div>

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Guru Pengamat</span>
                        <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">{{ count($guru) }}</div>
                    <p class="text-[11px] text-muted-foreground">Tim pengamat {{ strtoupper($jenjang ?? 'sd') }}</p>
                </div>

                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Cakupan Asesmen</span>
                        <i data-lucide="layers" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        @if(($jenjang ?? 'sd') === 'sd')
                            3 Sentra
                        @else
                            4 Kelompok
                        @endif
                    </div>
                    <p class="text-[11px] text-muted-foreground">
                        @if(($jenjang ?? 'sd') === 'sd')
                            9 Pos Observasi Terpadu
                        @else
                            8 Pos Stasiun Rotasi
                        @endif
                    </p>
                </div>

                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Aktif Bertugas</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($guru)->where('status', 'Aktif')->count() }}
                    </div>
                    <p class="text-[11px] text-emerald-400">Siap observasi</p>
                </div>

                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Observasi</span>
                        <i data-lucide="clipboard-check" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($guru)->sum('jumlah_observasi') }}
                    </div>
                    <p class="text-[11px] text-muted-foreground">Sesi tercatat di sistem</p>
                </div>
            </div>

            <!-- Table Container -->
            <div class="rounded-xl border border-border bg-card overflow-hidden">
                <div class="p-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-foreground">
                            Tim Guru Pengamat &bull; {{ $user['unit'] ?? '-' }}
                        </h2>
                        <p class="text-xs text-muted-foreground">
                            @if(($jenjang ?? 'sd') === 'sd')
                                Distribusi observer per Sentra Ubudiyah, Sentra Persiapan, dan Sentra Eksplorasi Diri
                            @else
                                Distribusi observer per 4 Kelompok Ruang Observasi SMP
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <!-- Sentra Filter Tabs -->
                        <div class="inline-flex items-center rounded-lg border border-border bg-muted/40 p-0.5 text-[11px]" id="filter-tabs">
                            <button type="button" onclick="filterBySentra('all', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium bg-card text-foreground shadow-xs transition-all">Semua</button>
                            @if(($jenjang ?? 'sd') === 'sd')
                                <button type="button" onclick="filterBySentra('ubudiyah', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Ubudiyah</button>
                                <button type="button" onclick="filterBySentra('persiapan', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Persiapan</button>
                                <button type="button" onclick="filterBySentra('eksplorasi', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Eksplorasi</button>
                            @else
                                <button type="button" onclick="filterBySentra('keagamaan', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Keagamaan</button>
                                <button type="button" onclick="filterBySentra('akademik', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Akademik</button>
                                <button type="button" onclick="filterBySentra('bakat_minat', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Bakat</button>
                                <button type="button" onclick="filterBySentra('pribadi_sosial', this)" class="sentra-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Pribadi</button>
                            @endif
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <input 
                                type="text" 
                                id="search-guru" 
                                onkeyup="filterTable()" 
                                placeholder="Cari nama / NIP..." 
                                class="h-8 w-44 sm:w-56 rounded-md border border-border bg-muted/40 pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" 
                            />
                            <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-muted-foreground"></i>
                        </div>
                    </div>
                </div>

                @if(($jenjang ?? 'sd') === 'sd')
                    {{-- ============================================================ --}}
                    {{-- SD: Grouped-by-Sentra layout (2 co-pengamat per sentra)      --}}
                    {{-- ============================================================ --}}
                    @php
                        $guruBySentra = collect($guru)->groupBy('sentra_key');
                        $sentraConfig = [
                            'ubudiyah'   => ['label' => 'Sentra Ubudiyah',        'color' => 'emerald', 'icon' => 'book-open', 'pos' => 'Pos 1.1 – 1.3'],
                            'persiapan'  => ['label' => 'Sentra Persiapan',       'color' => 'indigo',  'icon' => 'pencil',    'pos' => 'Pos 2.1 – 2.4'],
                            'eksplorasi' => ['label' => 'Sentra Eksplorasi Diri', 'color' => 'amber',   'icon' => 'sparkles',  'pos' => 'Pos 3.1 – 3.2'],
                        ];
                    @endphp

                    <div class="divide-y divide-border" id="table-guru">
                        @foreach($guruBySentra as $sentraKey => $sentraGurus)
                            @php
                                $cfg = $sentraConfig[$sentraKey] ?? ['label' => $sentraKey, 'color' => 'zinc', 'icon' => 'users', 'pos' => ''];
                                $c   = $cfg['color'];
                                $groupColorClass = match($c) {
                                    'emerald' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30',
                                    'indigo'  => 'text-indigo-400 bg-indigo-500/10 border-indigo-500/30',
                                    'amber'   => 'text-amber-400 bg-amber-500/10 border-amber-500/30',
                                    'sky'     => 'text-sky-400 bg-sky-500/10 border-sky-500/30',
                                    default   => 'text-muted-foreground bg-muted border-border',
                                };
                                $headerBg = match($c) {
                                    'emerald' => 'bg-emerald-500/5',
                                    'indigo'  => 'bg-indigo-500/5',
                                    'amber'   => 'bg-amber-500/5',
                                    'sky'     => 'bg-sky-500/5',
                                    default   => 'bg-muted/30',
                                };
                            @endphp
                            <div class="sentra-group" data-sentra="{{ $sentraKey }}">

                                {{-- Sentra Group Header --}}
                                <div class="px-4 py-2.5 {{ $headerBg }} flex items-center justify-between border-b border-border/60">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="{{ $cfg['icon'] }}" class="h-3.5 w-3.5 {{ explode(' ', $groupColorClass)[0] }}"></i>
                                        <span class="text-xs font-semibold text-foreground">{{ $cfg['label'] }}</span>
                                        <span class="hidden sm:inline text-[10px] font-mono text-muted-foreground">{{ $cfg['pos'] }}</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold {{ $groupColorClass }} border px-2 py-0.5 rounded-full">
                                        <i data-lucide="users" class="h-3 w-3"></i>
                                        {{ $sentraGurus->count() }} Co-Pengamat
                                    </span>
                                </div>

                                {{-- Per-guru rows in this sentra --}}
                                <table class="w-full text-left text-xs">
                                    @if($loop->first)
                                        <thead>
                                            <tr class="text-[11px] font-medium text-muted-foreground">
                                                <th class="h-8 px-4 w-28">NIP / Kode</th>
                                                <th class="h-8 px-4">Profil Guru Pengamat</th>
                                                <th class="h-8 px-4">Ruangan &amp; Cakupan Pos</th>
                                                <th class="h-8 px-4 text-center w-28">Jml. Observasi</th>
                                                <th class="h-8 px-4 text-center w-24">Status</th>
                                                <th class="h-8 px-4 text-center w-32">Aksi</th>
                                            </tr>
                                        </thead>
                                    @endif
                                    <tbody class="divide-y divide-border/40">
                                        @foreach($sentraGurus as $g)
                                            @php
                                                $color = $g['color'] ?? 'emerald';
                                                $avatarStyle = match($color) {
                                                    'emerald' => 'bg-emerald-600 text-white',
                                                    'indigo', 'blue' => 'bg-indigo-600 text-white',
                                                    'amber'  => 'bg-amber-600 text-white',
                                                    'sky'    => 'bg-sky-600 text-white',
                                                    default  => 'bg-muted text-foreground',
                                                };
                                                $badgeStyle = match($color) {
                                                    'emerald' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400',
                                                    'indigo', 'blue' => 'border-indigo-500/30 bg-indigo-500/10 text-indigo-400',
                                                    'amber'  => 'border-amber-500/30 bg-amber-500/10 text-amber-400',
                                                    'sky'    => 'border-sky-500/30 bg-sky-500/10 text-sky-400',
                                                    default  => 'border-border bg-muted text-muted-foreground',
                                                };
                                            @endphp
                                            <tr class="guru-row hover:bg-muted/40 transition-colors"
                                                data-sentra="{{ $g['sentra_key'] ?? $sentraKey }}"
                                                data-name="{{ strtolower($g['name']) }}"
                                                data-nip="{{ strtolower($g['nip']) }}">

                                                {{-- NIP --}}
                                                <td class="p-3 pl-4 font-mono whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded border border-border bg-muted/40 text-[11px] text-muted-foreground">
                                                        {{ $g['nip'] }}
                                                    </span>
                                                </td>

                                                {{-- Profil --}}
                                                <td class="p-3 whitespace-nowrap">
                                                    <div class="flex items-center gap-2.5">
                                                        <div class="h-8 w-8 rounded-lg {{ $avatarStyle }} flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                            {{ $g['avatar'] ?? substr($g['name'], 0, 2) }}
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-foreground text-xs">{{ $g['name'] }}</div>
                                                            <div class="text-[11px] text-muted-foreground font-mono">{{ $g['email'] ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                {{-- Ruangan & Pos --}}
                                                <td class="p-3">
                                                    <div class="flex flex-col gap-1 max-w-md">
                                                        <div class="inline-flex items-center gap-1.5 text-[11px] font-medium text-foreground">
                                                            <i data-lucide="door-open" class="h-3 w-3 text-muted-foreground shrink-0"></i>
                                                            <span>{{ $g['ruang'] }}</span>
                                                        </div>
                                                        @if(!empty($g['pos_desc']))
                                                            <p class="text-[11px] text-muted-foreground line-clamp-2 leading-relaxed">
                                                                {{ $g['pos_desc'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </td>

                                                {{-- Jml. Observasi --}}
                                                <td class="p-3 text-center whitespace-nowrap">
                                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full font-mono font-semibold text-xs border border-border bg-card text-foreground">
                                                        {{ $g['jumlah_observasi'] }} Siswa
                                                    </span>
                                                </td>

                                                {{-- Status --}}
                                                <td class="p-3 text-center whitespace-nowrap">
                                                    @if($g['status'] === 'Aktif')
                                                        <span class="inline-flex items-center gap-1 rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-muted-foreground"></span>
                                                            {{ $g['status'] }}
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Aksi --}}
                                                <td class="p-3 text-center whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        <button
                                                            type="button"
                                                            onclick="openDetailModal({{ json_encode($g) }})"
                                                            class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                            title="Detail Guru"
                                                        >
                                                            <i data-lucide="eye" class="h-3 w-3 text-muted-foreground"></i>
                                                            <span class="hidden xl:inline">Detail</span>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onclick="openEditGuruModal({{ json_encode($g) }})"
                                                            class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                            title="Edit Guru"
                                                        >
                                                            <i data-lucide="pencil" class="h-3 w-3 text-muted-foreground"></i>
                                                            <span class="hidden xl:inline">Edit</span>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onclick="confirmDeleteGuru({{ json_encode($g) }})"
                                                            class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-rose-500/30 bg-rose-500/10 px-2 text-[11px] font-medium text-rose-400 hover:bg-rose-500/20 transition-colors cursor-pointer"
                                                            title="Hapus Guru"
                                                        >
                                                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                            <span class="hidden xl:inline">Hapus</span>
                                                        </button>
                                                        <a
                                                            href="{{ route('guru.observasi', ['guru_nip' => $g['nip']]) }}"
                                                            title="Akses halaman observasi guru ini"
                                                            class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-muted/60 px-2 text-[11px] font-medium text-foreground hover:bg-primary hover:text-primary-foreground hover:border-primary transition-colors"
                                                        >
                                                            <i data-lucide="external-link" class="h-3 w-3"></i>
                                                            <span class="hidden md:inline">Observasi</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>

                @else
                    {{-- ============================================================ --}}
                    {{-- SMP: Flat table (1 guru per kelompok, unchanged)             --}}
                    {{-- ============================================================ --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs" id="table-guru">
                            <thead>
                                <tr class="border-b border-border text-[11px] font-medium text-muted-foreground bg-card">
                                    <th class="h-9 px-4">NIP / Kode</th>
                                    <th class="h-9 px-4">Profil Guru Pengamat</th>
                                    <th class="h-9 px-4">Kelompok &amp; Ruangan Observasi</th>
                                    <th class="h-9 px-4 text-center">Jml. Observasi</th>
                                    <th class="h-9 px-4 text-center">Status</th>
                                    <th class="h-9 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60 text-foreground/90" id="tbody-guru">
                                @foreach($guru as $g)
                                    @php
                                        $sentraKey = $g['sentra_key'] ?? 'all';
                                        $color = $g['color'] ?? 'emerald';
                                        $badgeStyle = match($color) {
                                            'emerald' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400',
                                            'indigo', 'blue' => 'border-indigo-500/30 bg-indigo-500/10 text-indigo-400',
                                            'amber' => 'border-amber-500/30 bg-amber-500/10 text-amber-400',
                                            'purple' => 'border-purple-500/30 bg-purple-500/10 text-purple-400',
                                            'sky' => 'border-sky-500/30 bg-sky-500/10 text-sky-400',
                                            default => 'border-border bg-muted text-muted-foreground',
                                        };
                                        $avatarStyle = match($color) {
                                            'emerald' => 'bg-emerald-600 text-white',
                                            'indigo', 'blue' => 'bg-indigo-600 text-white',
                                            'amber' => 'bg-amber-600 text-white',
                                            'purple' => 'bg-purple-600 text-white',
                                            'sky' => 'bg-sky-600 text-white',
                                            default => 'bg-muted text-foreground',
                                        };
                                    @endphp
                                    <tr class="guru-row hover:bg-muted/50 transition-colors" data-sentra="{{ $sentraKey }}" data-name="{{ strtolower($g['name']) }}" data-nip="{{ strtolower($g['nip']) }}">
                                        <td class="p-4 font-mono text-muted-foreground whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded border border-border bg-muted/40 text-[11px]">{{ $g['nip'] }}</span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-lg {{ $avatarStyle }} flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                    {{ $g['avatar'] ?? substr($g['name'], 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-foreground text-xs">{{ $g['name'] }}</div>
                                                    <div class="text-[11px] text-muted-foreground">{{ $g['email'] ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col gap-1.5 max-w-md">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border text-[10px] font-semibold {{ $badgeStyle }}">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                        <span>{{ $g['sentra'] ?? $g['kelompok'] }}</span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-foreground">
                                                        <i data-lucide="door-open" class="h-3 w-3 text-muted-foreground shrink-0"></i>
                                                        <span>{{ $g['ruang'] }}</span>
                                                    </span>
                                                </div>
                                                @if(!empty($g['pos_desc']))
                                                    <p class="text-[11px] text-muted-foreground line-clamp-2">{{ $g['pos_desc'] }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full font-mono font-semibold text-xs border border-border bg-card text-foreground">
                                                {{ $g['jumlah_observasi'] }} Siswa
                                            </span>
                                        </td>
                                        <td class="p-4 text-center whitespace-nowrap">
                                            @if($g['status'] === 'Aktif')
                                                <span class="inline-flex items-center gap-1 rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-muted-foreground"></span>
                                                    {{ $g['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button
                                                    type="button"
                                                    onclick="openDetailModal({{ json_encode($g) }})"
                                                    class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                    title="Detail Guru"
                                                >
                                                    <i data-lucide="eye" class="h-3 w-3 text-muted-foreground"></i>
                                                    <span class="hidden xl:inline">Detail</span>
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="openEditGuruModal({{ json_encode($g) }})"
                                                    class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                    title="Edit Guru"
                                                >
                                                    <i data-lucide="pencil" class="h-3 w-3 text-muted-foreground"></i>
                                                    <span class="hidden xl:inline">Edit</span>
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="confirmDeleteGuru({{ json_encode($g) }})"
                                                    class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-rose-500/30 bg-rose-500/10 px-2 text-[11px] font-medium text-rose-400 hover:bg-rose-500/20 transition-colors cursor-pointer"
                                                    title="Hapus Guru"
                                                >
                                                    <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                    <span class="hidden xl:inline">Hapus</span>
                                                </button>
                                                <a
                                                    href="{{ route('guru.observasi', ['guru_nip' => $g['nip']]) }}"
                                                    title="Akses halaman observasi guru ini"
                                                    class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-muted/60 px-2 text-[11px] font-medium text-foreground hover:bg-primary hover:text-primary-foreground hover:border-primary transition-colors"
                                                >
                                                    <i data-lucide="external-link" class="h-3 w-3"></i>
                                                    <span class="hidden md:inline">Observasi</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Empty State if filter yields zero -->
                <div id="no-result-state" class="hidden p-8 text-center space-y-2">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-muted text-muted-foreground mb-1">
                        <i data-lucide="user-x" class="h-5 w-5"></i>
                    </div>
                    <p class="text-xs font-semibold text-foreground">Guru pengamat tidak ditemukan</p>
                    <p class="text-[11px] text-muted-foreground">Coba ubah kata kunci pencarian atau ganti filter sentra.</p>
                </div>
            </div>

        </main>

        <!-- ========================================================================= -->
        <!-- MODAL DETAIL GURU PENGAMAT                                                -->
        <!-- ========================================================================= -->
        <div id="modal-detail-guru" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white text-xs">
                            <i data-lucide="user-check" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Profil Lengkap Guru Pengamat</h3>
                            <p class="text-[11px] text-muted-foreground">Detail penugasan observasi calon siswa baru</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <div class="p-5 space-y-4">
                    <!-- Identity Header -->
                    <div class="flex items-center gap-3.5 pb-4 border-b border-border">
                        <div id="modal-avatar" class="h-12 w-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs">
                            GP
                        </div>
                        <div class="space-y-0.5">
                            <h4 id="modal-name" class="text-sm font-bold text-foreground">-</h4>
                            <p id="modal-email" class="text-xs text-muted-foreground font-mono">-</p>
                            <div class="flex items-center gap-2 pt-1">
                                <span id="modal-nip" class="font-mono text-[10px] px-1.5 py-0.5 rounded border border-border bg-muted text-foreground/90">-</span>
                                <span id="modal-status" class="text-[10px] font-semibold text-emerald-400">Aktif</span>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Info -->
                    <div class="space-y-3 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-1">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-wider font-semibold">Penugasan Sentra / Kelompok</div>
                                <div id="modal-sentra" class="font-medium text-foreground">-</div>
                            </div>
                            <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-1">
                                <div class="text-[10px] text-muted-foreground uppercase tracking-wider font-semibold">Ruangan Observasi</div>
                                <div id="modal-ruang" class="font-medium text-foreground">-</div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-border bg-muted/30 p-3 space-y-1">
                            <div class="text-[10px] text-muted-foreground uppercase tracking-wider font-semibold">Cakupan Pos &amp; Aspek Dinilai</div>
                            <p id="modal-pos-desc" class="text-xs text-foreground/90 leading-relaxed">-</p>
                        </div>

                        <div class="rounded-lg border border-border bg-card p-3 flex items-center justify-between">
                            <span class="text-xs text-muted-foreground">Jumlah Observasi Tercatat:</span>
                            <span id="modal-obs-count" class="font-mono font-bold text-sm text-foreground">0 Siswa</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-muted/40 border-t border-border flex items-center justify-between gap-2">
                    <button type="button" onclick="closeDetailModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                        Tutup
                    </button>
                    <a id="modal-observasi-link" href="#" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors">
                        <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
                        <span>Buka Halaman Observasi Guru Ini</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL TAMBAH GURU PENGAMAT                                                -->
        <!-- ========================================================================= -->
        <div id="modal-tambah-guru" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-primary text-primary-foreground text-xs">
                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Tambah Guru Pengamat</h3>
                            <p class="text-[11px] text-muted-foreground">Tugaskan guru baru sebagai observer SPMB {{ strtoupper($jenjang ?? 'sd') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeTambahModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.guru.store') }}" method="POST" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="jenjang" value="{{ $jenjang ?? 'sd' }}">

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Lengkap &amp; Gelar</label>
                        <input type="text" name="nama" required placeholder="Contoh: Ustadzah Fatimah, S.Pd." class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">NIP / Kode Guru</label>
                            <input type="text" name="nip" required placeholder="Contoh: GSD-2026-08" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Email Institusi</label>
                            <input type="email" name="email" required placeholder="guru@anaksaleh.sch.id" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Penugasan Sentra / Ruangan</label>
                        <select name="kelompok_key" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2.5 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                            @if(($jenjang ?? 'sd') === 'sd')
                                <option value="ubudiyah">Sentra Ubudiyah (Pos 1.1–1.3)</option>
                                <option value="persiapan">Sentra Persiapan (Pos 2.1–2.4)</option>
                                <option value="eksplorasi">Sentra Eksplorasi Diri (Pos 3.1–3.2)</option>
                            @else
                                <option value="keagamaan">Kelompok Keagamaan (Pos 1–4)</option>
                                <option value="akademik">Kelompok Akademik CBT (Pos 5)</option>
                                <option value="bakat_minat">Kelompok Potensi Bakat Minat (Pos 6–7)</option>
                                <option value="pribadi_sosial">Kelompok Pribadi &amp; Sosial (Pos 8)</option>
                            @endif
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Ruangan (Opsional)</label>
                        <input type="text" name="ruang" placeholder="Contoh: Ruang Sentra Ubudiyah" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Deskripsi Pos / Cakupan (Opsional)</label>
                        <input type="text" name="pos_desc" placeholder="Contoh: Pos 1.1 Wudhu & Sholat • Pos 1.2 Doa Harian" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeTambahModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                            <span>Simpan Guru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL EDIT GURU PENGAMAT                                                  -->
        <!-- ========================================================================= -->
        <div id="modal-edit-guru" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-indigo-600 text-white text-xs">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Edit Guru Pengamat</h3>
                            <p class="text-[11px] text-muted-foreground">Perbarui informasi penugasan guru observer</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditGuruModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.guru.update') }}" method="POST" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="id" id="edit-guru-id">

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Lengkap &amp; Gelar</label>
                        <input type="text" name="nama" id="edit-guru-nama" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">NIP / Kode Guru</label>
                            <input type="text" name="nip" id="edit-guru-nip" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Email Institusi</label>
                            <input type="email" name="email" id="edit-guru-email" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Penugasan Sentra/Ruang</label>
                            <select name="kelompok_key" id="edit-guru-kelompok" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2.5 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                                @if(($jenjang ?? 'sd') === 'sd')
                                    <option value="ubudiyah">Sentra Ubudiyah</option>
                                    <option value="persiapan">Sentra Persiapan</option>
                                    <option value="eksplorasi">Sentra Eksplorasi Diri</option>
                                @else
                                    <option value="keagamaan">Kelompok Keagamaan</option>
                                    <option value="akademik">Kelompok Akademik CBT</option>
                                    <option value="bakat_minat">Kelompok Potensi Bakat Minat</option>
                                    <option value="pribadi_sosial">Kelompok Pribadi &amp; Sosial</option>
                                @endif
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Status</label>
                            <select name="status" id="edit-guru-status" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2.5 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Ruangan</label>
                        <input type="text" name="ruang" id="edit-guru-ruang" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Deskripsi Pos / Cakupan</label>
                        <input type="text" name="pos_desc" id="edit-guru-pos-desc" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeEditGuruModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-indigo-600 hover:bg-indigo-500 text-white px-3 text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL HAPUS GURU PENGAMAT                                                 -->
        <!-- ========================================================================= -->
        <div id="modal-delete-guru" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-sm rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center gap-2.5 text-rose-400">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <i data-lucide="alert-triangle" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Hapus Guru Pengamat</h3>
                        <p class="text-[11px] text-muted-foreground">Konfirmasi penghapusan data pengamat</p>
                    </div>
                </div>

                <form action="{{ route('admin.guru.delete') }}" method="POST" class="p-4 space-y-3 text-xs">
                    @csrf
                    <input type="hidden" name="id" id="delete-guru-id">

                    <p class="text-muted-foreground leading-relaxed">
                        Apakah Anda yakin ingin menghapus data guru <strong class="text-foreground" id="delete-guru-nama">-</strong> (<span class="font-mono text-muted-foreground" id="delete-guru-nip">-</span>)?
                    </p>
                    <p class="text-[11px] text-amber-400/90 bg-amber-500/10 border border-amber-500/20 rounded-md p-2">
                        Perhatian: Akun guru akan dihapus dari sistem pengamat SPMB unit ini.
                    </p>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeDeleteGuruModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-rose-600 hover:bg-rose-500 text-white px-3 text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                            <span>Ya, Hapus Guru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL IMPORT GURU PENGAMAT (CSV)                                          -->
        <!-- ========================================================================= -->
        <div id="modal-import-guru" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white text-xs">
                            <i data-lucide="upload" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Import Guru Pengamat (CSV)</h3>
                            <p class="text-[11px] text-muted-foreground">Upload file data guru observer massal</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeImportModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="jenjang" value="{{ $jenjang ?? 'sd' }}">

                    <div class="rounded-lg border border-border bg-muted/30 p-3 space-y-1.5 text-[11px]">
                        <div class="font-semibold text-foreground flex items-center justify-between">
                            <span>Petunjuk Format CSV:</span>
                            <a href="{{ route('admin.guru.template', ['jenjang' => $jenjang ?? 'sd']) }}" class="text-primary hover:underline flex items-center gap-1 font-bold">
                                <i data-lucide="download" class="h-3 w-3"></i>
                                <span>Unduh Template CSV</span>
                            </a>
                        </div>
                        <p class="text-muted-foreground leading-relaxed">
                            Pastikan kolom header memuat: <code class="font-mono text-foreground font-semibold">nip, nama, email, jenjang, kelompok_key, ruang, pos_desc, status</code>.
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-foreground">Pilih File CSV:</label>
                        <input type="file" name="file_guru" accept=".csv,text/csv,text/plain" required class="flex w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 cursor-pointer" />
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeImportModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-emerald-600 hover:bg-emerald-500 text-white px-3 text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="upload" class="h-3.5 w-3.5"></i>
                            <span>Upload &amp; Import</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <footer class="border-t border-border py-4 px-6 text-center text-xs text-muted-foreground">
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Manajemen Tim Pengamat SPMB
        </footer>

        <!-- Client Scripts -->
        <script>
            let currentSentraFilter = 'all';

            function filterBySentra(sentraKey, btn) {
                currentSentraFilter = sentraKey;

                document.querySelectorAll('.sentra-tab').forEach(b => {
                    b.classList.remove('bg-card', 'text-foreground', 'shadow-xs');
                    b.classList.add('text-muted-foreground');
                });
                btn.classList.add('bg-card', 'text-foreground', 'shadow-xs');
                btn.classList.remove('text-muted-foreground');

                filterTable();
            }

            function filterTable() {
                const searchVal = (document.getElementById('search-guru')?.value || '').toLowerCase().trim();
                const rows = document.querySelectorAll('.guru-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowSentra = row.getAttribute('data-sentra') || '';
                    const rowName = row.getAttribute('data-name') || '';
                    const rowNip = row.getAttribute('data-nip') || '';

                    const matchesSentra = (currentSentraFilter === 'all') || (rowSentra === currentSentraFilter);
                    const matchesSearch = !searchVal || rowName.includes(searchVal) || rowNip.includes(searchVal);

                    if (matchesSentra && matchesSearch) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });

                document.querySelectorAll('.sentra-group').forEach(group => {
                    const hasVisible = group.querySelectorAll('.guru-row:not(.hidden)').length > 0;
                    group.classList.toggle('hidden', !hasVisible);
                });

                const noResult = document.getElementById('no-result-state');
                if (noResult) {
                    if (visibleCount === 0) {
                        noResult.classList.remove('hidden');
                    } else {
                        noResult.classList.add('hidden');
                    }
                }
            }

            // Detail Modal
            function openDetailModal(guru) {
                document.getElementById('modal-name').textContent = guru.name || '-';
                document.getElementById('modal-email').textContent = guru.email || '-';
                document.getElementById('modal-nip').textContent = guru.nip || '-';
                document.getElementById('modal-sentra').textContent = guru.sentra || guru.kelompok || '-';
                document.getElementById('modal-ruang').textContent = guru.ruang || '-';
                document.getElementById('modal-pos-desc').textContent = guru.pos_desc || 'Deskripsi pos observasi belum diatur.';
                document.getElementById('modal-obs-count').textContent = (guru.jumlah_observasi || 0) + ' Siswa';
                
                const avatarEl = document.getElementById('modal-avatar');
                if (avatarEl) {
                    avatarEl.textContent = guru.avatar || (guru.name ? guru.name.substr(0, 2) : 'GP');
                }

                const obsLink = document.getElementById('modal-observasi-link');
                if (obsLink && guru.nip) {
                    obsLink.href = '{{ route("guru.observasi") }}?guru_nip=' + encodeURIComponent(guru.nip);
                }

                document.getElementById('modal-detail-guru')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeDetailModal() {
                document.getElementById('modal-detail-guru')?.classList.add('hidden');
            }

            // Tambah Guru Modal
            function openTambahModal() {
                document.getElementById('modal-tambah-guru')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeTambahModal() {
                document.getElementById('modal-tambah-guru')?.classList.add('hidden');
            }

            // Edit Guru Modal
            function openEditGuruModal(guru) {
                document.getElementById('edit-guru-id').value = guru.id || '';
                document.getElementById('edit-guru-nama').value = guru.name || '';
                document.getElementById('edit-guru-nip').value = guru.nip || '';
                document.getElementById('edit-guru-email').value = guru.email || '';
                document.getElementById('edit-guru-kelompok').value = guru.sentra_key || '';
                document.getElementById('edit-guru-status').value = guru.status || 'Aktif';
                document.getElementById('edit-guru-ruang').value = guru.ruang || '';
                document.getElementById('edit-guru-pos-desc').value = guru.pos_desc || '';

                document.getElementById('modal-edit-guru')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeEditGuruModal() {
                document.getElementById('modal-edit-guru')?.classList.add('hidden');
            }

            // Hapus Guru Modal
            function confirmDeleteGuru(guru) {
                document.getElementById('delete-guru-id').value = guru.id || '';
                document.getElementById('delete-guru-nama').textContent = guru.name || '-';
                document.getElementById('delete-guru-nip').textContent = guru.nip || '-';

                document.getElementById('modal-delete-guru')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeDeleteGuruModal() {
                document.getElementById('modal-delete-guru')?.classList.add('hidden');
            }

            // Import Guru Modal
            function openImportModal() {
                document.getElementById('modal-import-guru')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeImportModal() {
                document.getElementById('modal-import-guru')?.classList.add('hidden');
            }

            // Keyboard Escape Listener
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeDetailModal();
                    closeTambahModal();
                    closeEditGuruModal();
                    closeDeleteGuruModal();
                    closeImportModal();
                }
            });
        </script>

    </body>
</html>