<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Indikator Observasi — {{ $user['unit'] ?? 'SAANS' }}</title>
        
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
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-zinc-950 text-zinc-100 antialiased font-sans flex flex-col">

        <!-- Navbar -->
        <header class="border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur sticky top-0 z-40 px-4 lg:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md border border-zinc-700 bg-zinc-800 text-zinc-100">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-xs font-semibold tracking-tight text-zinc-100">SAANS</span>
                    </a>
                    <nav class="hidden sm:flex items-center gap-1 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900 transition-colors">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.guru') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900 transition-colors">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            <span>Guru Pengamat</span>
                        </a>
                        <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-900 transition-colors">
                            <i data-lucide="notebook-pen" class="h-3.5 w-3.5"></i>
                            <span>Calon Siswa</span>
                        </a>
                        <a href="{{ route('admin.indikator') }}" class="inline-flex items-center gap-1.5 rounded-md bg-zinc-800 border border-zinc-700/80 px-2.5 py-1.5 font-medium text-zinc-100 transition-colors">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>Indikator</span>
                        </a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <!-- <span class="inline-flex items-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 py-0.5 text-[11px] font-medium text-zinc-200">
                        @if($jenjang === 'tk')
                            <i data-lucide="baby" class="h-3 w-3 text-emerald-400"></i>
                        @elseif($jenjang === 'smp')
                            <i data-lucide="school" class="h-3 w-3 text-purple-400"></i>
                        @else
                            <i data-lucide="backpack" class="h-3 w-3 text-indigo-400"></i>
                        @endif
                        <span>{{ $user['role_label'] ?? 'Admin SPMB' }}</span>
                    </span> -->
                    <div class="flex items-center gap-2 pl-2 border-l border-zinc-800">
                        <div class="h-7 w-7 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-200">
                            {{ substr($user['name'] ?? 'Admin', 0, 2) }}
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-zinc-200">{{ $user['name'] ?? 'Administrator' }}</span>
                    </div>
                    <x-theme-settings />
                    <a href="{{ route('logout') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-transparent px-2.5 text-xs font-medium text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100 transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 flex-1 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-zinc-800/80">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight text-zinc-100">Indikator Observasi & Skoring</h1>
                        <span class="rounded-md border border-zinc-800 bg-zinc-900/80 px-2 py-0.5 text-[11px] text-zinc-400 font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        Kelola instrumen penilaian, rubrik skoring (1–4), dan bobot tiap aspek observasi
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                        <span>Tambah Indikator</span>
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Total Indikator</span>
                        <i data-lucide="list-checks" class="h-4 w-4 text-zinc-500"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-zinc-100">{{ count($indikator) }}</div>
                    <p class="text-[11px] text-zinc-500">Instrumen observasi</p>
                </div>
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Aspek Dinilai</span>
                        <i data-lucide="layers" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-zinc-100">{{ $grouped->count() }}</div>
                    <p class="text-[11px] text-zinc-500">Domain / aspek unik</p>
                </div>
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Aktif</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-zinc-100">
                        {{ collect($indikator)->where('status', 'Aktif')->count() }}
                    </div>
                    <p class="text-[11px] text-emerald-400">Digunakan observasi</p>
                </div>
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Total Bobot</span>
                        <i data-lucide="percent" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-zinc-100">
                        {{ collect($indikator)->where('status', 'Aktif')->sum('bobot') }}%
                    </div>
                    <p class="text-[11px] text-amber-400">Harus total 100%</p>
                </div>
            </div>

            <!-- Indikator Groups -->
            <div class="space-y-4">
                @php
                    $aspekMeta = [
                        'Kelompok Keagamaan'                      => ['icon' => 'book-open',       'color' => 'emerald', 'ring' => 'border-emerald-800/60 bg-emerald-950/20', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-900/60'],
                        'Kelompok Akademik'                       => ['icon' => 'flask-conical',    'color' => 'indigo',  'ring' => 'border-indigo-800/60 bg-indigo-950/20',   'text' => 'text-indigo-400',  'dot' => 'bg-indigo-900/60'],
                        'Kelompok Potensi Bakat Minat'                   => ['icon' => 'wrench',           'color' => 'amber',   'ring' => 'border-amber-800/60 bg-amber-950/20',     'text' => 'text-amber-400',   'dot' => 'bg-amber-900/60'],
                        'Kelompok Perkembangan Pribadi & Sosial'  => ['icon' => 'heart-handshake',  'color' => 'purple',  'ring' => 'border-purple-800/60 bg-purple-950/20',   'text' => 'text-purple-400',  'dot' => 'bg-purple-900/60'],
                    ];
                @endphp

                @foreach($grouped as $aspek => $items)
                    @php
                        $meta = $aspekMeta[$aspek] ?? ['icon' => 'folder-open', 'color' => 'zinc', 'ring' => 'border-zinc-700 bg-zinc-800/40', 'text' => 'text-zinc-400', 'dot' => 'bg-zinc-700'];
                        $groupId = 'group-' . md5($aspek);
                    @endphp
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 overflow-hidden">

                        <!-- Aspek Header (Clickable Collapse Toggle) -->
                        <button
                            type="button"
                            onclick="toggleGroup('{{ $groupId }}')"
                            class="w-full flex items-center justify-between px-5 py-3 border-b border-zinc-800 bg-zinc-900/60 hover:bg-zinc-900/90 transition-colors text-left"
                        >
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-6 w-6 items-center justify-center rounded-md border {{ $meta['ring'] }}">
                                    <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5 {{ $meta['text'] }}"></i>
                                </div>
                                <span class="text-sm font-semibold text-zinc-100">{{ $aspek }}</span>
                                <span class="rounded-full border border-zinc-700 bg-zinc-800 px-2 py-0.5 text-[10px] font-medium text-zinc-400">
                                    {{ $items->count() }} indikator
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-zinc-500 font-mono">Bobot: {{ $items->sum('bobot') }}%</span>
                                <i data-lucide="chevron-down" class="h-4 w-4 text-zinc-500 transition-transform duration-200" id="{{ $groupId }}-chevron"></i>
                            </div>
                        </button>

                        <!-- Indikator Items (Collapsible) -->
                        <div id="{{ $groupId }}" class="divide-y divide-zinc-800/60">

                            @if($aspek === 'Kelompok Keagamaan')
                                <!-- Banner Rumus Perhitungan & Rekomendasi Otomatis Keagamaan -->
                                <div class="p-5 bg-gradient-to-r from-emerald-950/40 via-zinc-900 to-zinc-900 border-b border-zinc-800 space-y-4">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-emerald-800 bg-emerald-950/80 text-emerald-400">
                                                <i data-lucide="calculator" class="h-4 w-4"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-zinc-100 flex items-center gap-2">
                                                    Rumus Penilaian &amp; Rekomendasi Otomatis Kelompok Keagamaan
                                                    <span class="rounded bg-emerald-900/60 border border-emerald-700/60 text-emerald-300 text-[10px] font-mono px-1.5 py-0.5">4 Pos &bull; Skala 1–4</span>
                                                </h3>
                                                <p class="text-xs text-zinc-400 mt-0.5">
                                                    Didalam kelompok keagamaan terdapat 4 Pos. Setiap Pos memiliki 5 aspek dengan skala nilai 1–4 (Skor maksimal 20 per Pos).
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grid 2 Kolom: Rumus & Skema Kategori/Rekomendasi -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                        <!-- Box 1: Rumus Perhitungan -->
                                        <div class="rounded-lg border border-zinc-700/80 bg-zinc-900/90 p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                                                <i data-lucide="sigma" class="h-3.5 w-3.5 text-emerald-400"></i>
                                                Rumus Perhitungan Nilai:
                                            </p>
                                            <div class="space-y-1.5 text-xs text-zinc-300">
                                                <div class="flex items-center justify-between bg-zinc-800/80 border border-zinc-700/50 rounded px-2.5 py-1.5 font-mono">
                                                    <span class="text-zinc-400">Nilai Akhir per Pos</span>
                                                    <span class="text-emerald-400 font-semibold">(Total Skor / 20) × 100</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-zinc-800/80 border border-zinc-700/50 rounded px-2.5 py-1.5 font-mono">
                                                    <span class="text-zinc-400">Nilai Akhir Keagamaan</span>
                                                    <span class="text-emerald-400 font-semibold">Rata-rata dari 4 Nilai Pos</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Box 2: Kategori & Rekomendasi Otomatis -->
                                        <div class="rounded-lg border border-zinc-700/80 bg-zinc-900/90 p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                                                <i data-lucide="tag" class="h-3.5 w-3.5 text-amber-400"></i>
                                                Kategori &amp; Rekomendasi Otomatis:
                                            </p>
                                            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                                                <div class="p-1.5 rounded border border-emerald-900/60 bg-emerald-950/30">
                                                    <span class="font-bold text-emerald-300">86 – 100 (Sangat Baik)</span>
                                                    <p class="text-zinc-400 text-[10px]">Kelas Pengayaan / Tilawah Mandiri</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-blue-900/60 bg-blue-950/30">
                                                    <span class="font-bold text-blue-300">76 – 85 (Baik)</span>
                                                    <p class="text-zinc-400 text-[10px]">Kelas Reguler / Pembiasaan</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-amber-900/60 bg-amber-950/30">
                                                    <span class="font-bold text-amber-300">60 – 75 (Berkembang)</span>
                                                    <p class="text-zinc-400 text-[10px]">Pendampingan / Tahsin Lanjutan</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-red-900/60 bg-red-950/30">
                                                    <span class="font-bold text-red-300">&lt; 60 (Perlu Pendampingan)</span>
                                                    <p class="text-zinc-400 text-[10px]">Kelas Matrikulasi / Tahsin Dasar</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interactive Quick Simulator -->
                                    <div class="rounded-lg border border-emerald-800/60 bg-emerald-950/20 p-3 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-emerald-300 flex items-center gap-1.5">
                                                <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                                                Simulasi Kalkulator Otomatis Nilai Pos &amp; Rekomendasi
                                            </span>
                                            <span class="text-[11px] text-zinc-400">Uji coba simulasi perhitungan real-time</span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                            <div>
                                                <label class="text-[10px] text-zinc-400 block mb-1">Skor Pos 1 (Sholat /20)</label>
                                                <input type="number" id="sim-pos-1" min="0" max="20" value="18" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-zinc-700 bg-zinc-800 px-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-zinc-400 block mb-1">Skor Pos 2 (Mengaji /20)</label>
                                                <input type="number" id="sim-pos-2" min="0" max="20" value="16" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-zinc-700 bg-zinc-800 px-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-zinc-400 block mb-1">Skor Pos 3 (Doa /20)</label>
                                                <input type="number" id="sim-pos-3" min="0" max="20" value="17" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-zinc-700 bg-zinc-800 px-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-zinc-400 block mb-1">Skor Pos 4 (Surat /20)</label>
                                                <input type="number" id="sim-pos-4" min="0" max="20" value="19" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-zinc-700 bg-zinc-800 px-2 text-xs text-zinc-100 font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 border-t border-emerald-900/40 text-xs">
                                            <div class="flex items-center gap-3">
                                                <span class="text-zinc-400">Rata-rata Nilai: <strong id="sim-hasil-nilai" class="text-emerald-300 font-mono text-sm font-bold">87.5</strong></span>
                                                <span class="text-zinc-400">Kategori: <span id="sim-hasil-kategori" class="font-semibold text-emerald-400 px-1.5 py-0.5 rounded bg-emerald-900/60 border border-emerald-700/60">Sangat Baik</span></span>
                                            </div>
                                            <div class="text-zinc-300 text-[11px]">
                                                Rekomendasi: <span id="sim-hasil-rekomendasi" class="font-medium text-emerald-300">Kelas Pengayaan / Tilawah Mandiri</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($items->first() && isset($items->first()['pos']))
                                @php
                                    $posGroups = $items->groupBy('pos');
                                @endphp

                                <!-- Pos Navigation & Quick Actions -->
                                <div class="px-5 py-2.5 bg-zinc-900/60 border-b border-zinc-800/80 flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <span class="text-zinc-400 font-medium flex items-center gap-1.5">
                                        <i data-lucide="layers" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        Daftar 4 Pos Observasi (Klik pada Pos untuk buka / tutup aspek)
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" onclick="expandAllPos()" class="inline-flex items-center gap-1 text-[11px] text-zinc-300 hover:text-emerald-400 bg-zinc-800 hover:bg-zinc-700/80 border border-zinc-700 px-2.5 py-1 rounded-md transition-colors cursor-pointer">
                                            <i data-lucide="chevrons-up-down" class="h-3 w-3"></i>
                                            Buka Semua Pos
                                        </button>
                                        <button type="button" onclick="collapseAllPos()" class="inline-flex items-center gap-1 text-[11px] text-zinc-400 hover:text-zinc-200 bg-zinc-800 hover:bg-zinc-700/80 border border-zinc-700 px-2.5 py-1 rounded-md transition-colors cursor-pointer">
                                            <i data-lucide="chevrons-down-up" class="h-3 w-3"></i>
                                            Tutup Semua Pos
                                        </button>
                                    </div>
                                </div>

                                @foreach($posGroups as $posTitle => $posItems)
                                    @php
                                        $posKey = 'pos-group-' . $loop->index;
                                    @endphp
                                    <div class="bg-zinc-900/40 border-b border-zinc-800 last:border-b-0">
                                        <!-- Pos Sub-Header Collapsible Button -->
                                        <button 
                                            type="button" 
                                            onclick="toggleGroup('{{ $posKey }}')" 
                                            class="w-full px-5 py-3.5 bg-zinc-900/80 hover:bg-zinc-800/60 border-b border-zinc-800/80 flex flex-wrap items-center justify-between gap-3 text-left transition-colors cursor-pointer"
                                        >
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-950 border border-emerald-800 text-emerald-400 text-xs font-bold font-mono shrink-0">
                                                    {{ substr($posTitle, 4, 1) }}
                                                </span>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-zinc-100 flex items-center gap-2">
                                                        {{ $posTitle }}
                                                        <span class="rounded bg-zinc-800 border border-zinc-700 px-2 py-0.5 text-[10px] font-mono font-normal text-zinc-300">5 Aspek</span>
                                                    </h4>
                                                    <p class="text-[11px] text-zinc-400 mt-0.5">Skala Nilai 1–4 &bull; Skor Maksimal 20 &bull; Bobot Pos: {{ $posItems->sum('bobot') }}% &bull; Rumus: (Skor / 20) × 100</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2.5">
                                                <span class="rounded bg-zinc-800 border border-zinc-700 px-2 py-0.5 text-[11px] font-mono text-zinc-300 hidden sm:inline">
                                                    Rumus: (Skor / 20) × 100
                                                </span>
                                                <i data-lucide="chevron-down" class="h-4 w-4 text-zinc-400 transition-transform duration-200" id="{{ $posKey }}-chevron"></i>
                                            </div>
                                        </button>

                                        <!-- Pos Aspects List (Collapsible) -->
                                        <div id="{{ $posKey }}" class="divide-y divide-zinc-800/50">
                                            @foreach($posItems as $ind)
                                                <div class="p-5 space-y-3 hover:bg-zinc-900/20 transition-colors">
                                                    <!-- Aspect Row Header -->
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                                            <span class="shrink-0 font-mono text-[10px] text-zinc-400 mt-0.5 bg-zinc-800 border border-zinc-700 rounded px-1.5 py-0.5">
                                                                {{ $ind['id'] }}
                                                            </span>
                                                            <div class="min-w-0">
                                                                <div class="flex items-center gap-2">
                                                                    <p class="text-sm font-semibold text-zinc-100 leading-snug">{{ $ind['nama'] }}</p>
                                                                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950/60 border border-emerald-800/60 rounded px-1.5 py-0.2">Skor 1–4</span>
                                                                </div>
                                                                <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">{{ $ind['deskripsi'] }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2 shrink-0">
                                                            <span class="inline-flex items-center gap-1 rounded-md border border-zinc-700 bg-zinc-800 px-2 py-0.5 text-[11px] font-mono font-medium text-zinc-200">
                                                                <i data-lucide="percent" class="h-3 w-3 text-zinc-400"></i>
                                                                {{ $ind['bobot'] }}%
                                                            </span>
                                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                                <i data-lucide="circle-dot" class="h-3 w-3"></i>
                                                                Aktif
                                                            </span>
                                                            <button onclick="openModal('{{ $ind['id'] }}')" class="inline-flex h-7 items-center gap-1 rounded-md border border-zinc-700 bg-zinc-800 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-700 hover:text-zinc-100 transition-colors">
                                                                <i data-lucide="pencil" class="h-3 w-3"></i>
                                                                Edit
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Rubrik Skoring Grid -->
                                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 pt-1">
                                                        @foreach($ind['rubrik'] as $skor => $deskripsi)
                                                            <div class="rounded-lg border p-3 space-y-1.5
                                                                @if($skor === 1) border-red-900/40 bg-red-950/20
                                                                @elseif($skor === 2) border-amber-900/40 bg-amber-950/20
                                                                @elseif($skor === 3) border-indigo-900/40 bg-indigo-950/20
                                                                @else border-emerald-900/40 bg-emerald-950/20
                                                                @endif
                                                            ">
                                                                <div class="flex items-center gap-1.5">
                                                                    <span class="flex h-5 w-5 items-center justify-center rounded-full text-[11px] font-bold font-mono
                                                                        @if($skor === 1) bg-red-900/60 text-red-300
                                                                        @elseif($skor === 2) bg-amber-900/60 text-amber-300
                                                                        @elseif($skor === 3) bg-indigo-900/60 text-indigo-300
                                                                        @else bg-emerald-900/60 text-emerald-300
                                                                        @endif
                                                                    ">{{ $skor }}</span>
                                                                    <span class="text-[10px] font-medium
                                                                        @if($skor === 1) text-red-400
                                                                        @elseif($skor === 2) text-amber-400
                                                                        @elseif($skor === 3) text-indigo-400
                                                                        @else text-emerald-400
                                                                        @endif
                                                                    ">
                                                                        Skor {{ $skor }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-[11px] text-zinc-300 leading-relaxed">{{ $deskripsi }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                <!-- Regular indicator rendering for groups without pos -->
                                @foreach($items as $ind)
                                    <div class="p-5 space-y-3">
                                        <!-- Row Header -->
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                                <span class="shrink-0 font-mono text-[10px] text-zinc-500 mt-0.5 bg-zinc-800 border border-zinc-700 rounded px-1.5 py-0.5">
                                                    {{ $ind['id'] }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-zinc-100 leading-snug">{{ $ind['nama'] }}</p>
                                                    <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">{{ $ind['deskripsi'] }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="inline-flex items-center gap-1 rounded-md border border-zinc-700 bg-zinc-800 px-2 py-0.5 text-[11px] font-mono font-medium text-zinc-200">
                                                    <i data-lucide="percent" class="h-3 w-3 text-zinc-400"></i>
                                                    {{ $ind['bobot'] }}
                                                </span>
                                                @if($ind['status'] === 'Aktif')
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                        <i data-lucide="circle-dot" class="h-3 w-3"></i>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-zinc-700 bg-zinc-800/60 px-2 py-0.5 text-[11px] font-medium text-zinc-500">
                                                        <i data-lucide="pencil" class="h-3 w-3"></i>
                                                        Draft
                                                    </span>
                                                @endif
                                                <button onclick="openModal('{{ $ind['id'] }}')" class="inline-flex h-7 items-center gap-1 rounded-md border border-zinc-700 bg-zinc-800 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-700 hover:text-zinc-100 transition-colors">
                                                    <i data-lucide="pencil" class="h-3 w-3"></i>
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete('{{ $ind['id'] }}', '{{ addslashes($ind['nama']) }}')" class="inline-flex h-7 items-center gap-1 rounded-md border border-red-900/60 bg-red-950/30 px-2.5 text-[11px] font-medium text-red-400 hover:bg-red-950/60 hover:text-red-300 transition-colors">
                                                    <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Rubrik Skoring Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 pt-1">
                                            @foreach($ind['rubrik'] as $skor => $deskripsi)
                                                <div class="rounded-lg border p-3 space-y-1.5
                                                    @if($skor === 1) border-red-900/40 bg-red-950/20
                                                    @elseif($skor === 2) border-amber-900/40 bg-amber-950/20
                                                    @elseif($skor === 3) border-indigo-900/40 bg-indigo-950/20
                                                    @else border-emerald-900/40 bg-emerald-950/20
                                                    @endif
                                                ">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="flex h-5 w-5 items-center justify-center rounded-full text-[11px] font-bold font-mono
                                                            @if($skor === 1) bg-red-900/60 text-red-300
                                                            @elseif($skor === 2) bg-amber-900/60 text-amber-300
                                                            @elseif($skor === 3) bg-indigo-900/60 text-indigo-300
                                                            @else bg-emerald-900/60 text-emerald-300
                                                            @endif
                                                        ">{{ $skor }}</span>
                                                        <span class="text-[10px] font-medium
                                                            @if($skor === 1) text-red-400
                                                            @elseif($skor === 2) text-amber-400
                                                            @elseif($skor === 3) text-indigo-400
                                                            @else text-emerald-400
                                                            @endif
                                                        ">
                                                            @if($skor === 1) Perlu Bimbingan
                                                            @elseif($skor === 2) Berkembang
                                                            @elseif($skor === 3) Baik
                                                            @else Sangat Baik
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <p class="text-[11px] text-zinc-300 leading-relaxed">{{ $deskripsi }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </main>

        <footer class="border-t border-zinc-800/80 py-4 px-6 text-center text-xs text-zinc-500">
            SAANS Assessment &bull; Perguruan Anak Saleh &bull; Manajemen Instrumen Observasi SPMB
        </footer>

        <!-- Modal Tambah / Edit Indikator -->
        <div id="modal-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
            <div class="w-full max-w-2xl rounded-xl border border-zinc-800 bg-zinc-950 shadow-2xl" role="dialog" aria-modal="true">

                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-zinc-800 px-5 py-4">
                    <div>
                        <h2 id="modal-title" class="text-sm font-semibold text-zinc-100">Tambah Indikator Observasi</h2>
                        <p class="text-xs text-zinc-400 mt-0.5">Isi detail indikator dan rubrik skoring 1–4</p>
                    </div>
                    <button onclick="closeModal()" class="flex h-7 w-7 items-center justify-center rounded-md border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-zinc-100 transition-colors">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">

                    <!-- Row 1: ID + Aspek -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-zinc-300">Kode Indikator</label>
                            <input id="f-id" type="text" placeholder="misal: SD-IND-07" class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-zinc-300">Aspek / Domain</label>
                            <input id="f-aspek" type="text" placeholder="misal: Kesiapan Literasi" class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors" />
                        </div>
                    </div>

                    <!-- Nama Indikator -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-zinc-300">Nama / Pernyataan Indikator</label>
                        <input id="f-nama" type="text" placeholder="misal: Mengenal dan menyebutkan huruf abjad" class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors" />
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-zinc-300">Deskripsi Instruksi Pengamat</label>
                        <textarea id="f-deskripsi" rows="2" placeholder="Jelaskan bagaimana guru mengamati dan menilai indikator ini..." class="flex w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors resize-none"></textarea>
                    </div>

                    <!-- Row: Bobot + Status -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-zinc-300">Bobot (%)</label>
                            <input id="f-bobot" type="number" min="1" max="100" placeholder="misal: 20" class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-medium text-zinc-300">Status</label>
                            <select id="f-status" class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 text-xs text-zinc-100 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors">
                                <option value="Aktif">Aktif</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rubrik Skoring -->
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-zinc-300 flex items-center gap-1.5">
                            <i data-lucide="sliders-horizontal" class="h-3.5 w-3.5 text-zinc-500"></i>
                            Rubrik Deskriptor Skoring
                        </label>
                        <div class="space-y-2">
                            @foreach([1 => ['Perlu Bimbingan', 'border-red-900/50 bg-red-950/20', 'text-red-400', 'border-red-800/50'], 2 => ['Berkembang', 'border-amber-900/50 bg-amber-950/20', 'text-amber-400', 'border-amber-800/50'], 3 => ['Baik', 'border-indigo-900/50 bg-indigo-950/20', 'text-indigo-400', 'border-indigo-800/50'], 4 => ['Sangat Baik', 'border-emerald-900/50 bg-emerald-950/20', 'text-emerald-400', 'border-emerald-800/50']] as $skor => $cfg)
                                <div class="flex gap-2.5 rounded-lg border {{ $cfg[0] }} p-3">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold font-mono {{ $cfg[1] }} {{ $cfg[3] }} border {{ $cfg[2] }}">{{ $skor }}</span>
                                    <div class="flex-1 space-y-1">
                                        <span class="text-[10px] font-medium {{ $cfg[2] }}">Skor {{ $skor }} — {{ $cfg[0] }}</span>
                                        <textarea id="f-rubrik-{{ $skor }}" rows="2" placeholder="Deskripsikan perilaku siswa yang menunjukkan skor {{ $skor }}..." class="flex w-full rounded-md border border-zinc-800 bg-zinc-900/50 px-3 py-1.5 text-xs text-zinc-100 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors resize-none"></textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-between border-t border-zinc-800 px-5 py-4">
                    <button onclick="closeModal()" class="inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-3 text-xs font-medium text-zinc-400 hover:text-zinc-100 transition-colors">
                        Batal
                    </button>
                    <div class="flex items-center gap-2">
                        <button onclick="saveDraft()" class="inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-700 bg-zinc-800 px-3 text-xs font-medium text-zinc-300 hover:bg-zinc-700 transition-colors">
                            <i data-lucide="file" class="h-3.5 w-3.5"></i>
                            Simpan Draft
                        </button>
                        <button onclick="saveIndikator()" class="inline-flex h-8 items-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                            <i data-lucide="save" class="h-3.5 w-3.5"></i>
                            Simpan & Aktifkan
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <script>
            const overlay = document.getElementById('modal-overlay');

            // ── Collapse / Expand ──────────────────────────────────────────
            function toggleGroup(id) {
                const body    = document.getElementById(id);
                const chevron = document.getElementById(id + '-chevron');
                const isOpen  = !body.classList.contains('hidden');

                if (isOpen) {
                    body.classList.add('hidden');
                    chevron.style.transform = 'rotate(-90deg)';
                } else {
                    body.classList.remove('hidden');
                    chevron.style.transform = 'rotate(0deg)';
                }
            }

            // Start all groups and pos collapsed
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[id^="group-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });

                document.querySelectorAll('[id^="pos-group-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            });

            function expandAllPos() {
                document.querySelectorAll('[id^="pos-group-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.remove('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            function collapseAllPos() {
                document.querySelectorAll('[id^="pos-group-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            }

            function openModal(id = null) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                document.getElementById('modal-title').textContent = id
                    ? 'Edit Indikator: ' + id
                    : 'Tambah Indikator Observasi';
                if (id) document.getElementById('f-id').value = id;
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeModal() {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                // Reset fields
                ['f-id','f-aspek','f-nama','f-deskripsi','f-bobot','f-rubrik-1','f-rubrik-2','f-rubrik-3','f-rubrik-4']
                    .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
                document.getElementById('f-status').value = 'Aktif';
            }

            function saveIndikator() {
                const nama = document.getElementById('f-nama').value.trim();
                if (!nama) { alert('Nama indikator wajib diisi.'); return; }
                alert('Indikator "' + nama + '" berhasil disimpan dan diaktifkan.\n\n(Integrasi database akan menyimpan data ini secara permanen.)');
                closeModal();
            }

            function saveDraft() {
                const nama = document.getElementById('f-nama').value.trim();
                if (!nama) { alert('Nama indikator wajib diisi.'); return; }
                alert('Indikator "' + nama + '" disimpan sebagai Draft.');
                closeModal();
            }

            function confirmDelete(id, nama) {
                if (confirm('Hapus indikator "' + nama + '" (' + id + ')?\n\nTindakan ini tidak dapat dibatalkan.')) {
                    alert('Indikator ' + id + ' telah dihapus.\n\n(Integrasi database akan menghapus secara permanen.)');
                }
            }

            // Close on overlay click
            overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

            // ── Kalkulator Simulasi Kelompok Keagamaan ───────────────────────
            function hitungSimulasiKeagamaan() {
                const p1 = Math.min(20, Math.max(0, parseFloat(document.getElementById('sim-pos-1')?.value || 0)));
                const p2 = Math.min(20, Math.max(0, parseFloat(document.getElementById('sim-pos-2')?.value || 0)));
                const p3 = Math.min(20, Math.max(0, parseFloat(document.getElementById('sim-pos-3')?.value || 0)));
                const p4 = Math.min(20, Math.max(0, parseFloat(document.getElementById('sim-pos-4')?.value || 0)));

                const n1 = (p1 / 20) * 100;
                const n2 = (p2 / 20) * 100;
                const n3 = (p3 / 20) * 100;
                const n4 = (p4 / 20) * 100;

                const rata = (n1 + n2 + n3 + n4) / 4;
                const roundRata = Math.round(rata * 10) / 10;

                let kategori = '';
                let rekomendasi = '';
                let badgeClass = '';

                if (roundRata >= 86) {
                    kategori = 'Sangat Baik';
                    rekomendasi = 'Kelas Pengayaan / Tilawah Mandiri';
                    badgeClass = 'font-semibold text-emerald-400 px-1.5 py-0.5 rounded bg-emerald-950 border border-emerald-800';
                } else if (roundRata >= 76) {
                    kategori = 'Baik';
                    rekomendasi = 'Kelas Reguler / Pembiasaan';
                    badgeClass = 'font-semibold text-blue-400 px-1.5 py-0.5 rounded bg-blue-950 border border-blue-800';
                } else if (roundRata >= 60) {
                    kategori = 'Berkembang';
                    rekomendasi = 'Pendampingan / Tahsin Lanjutan';
                    badgeClass = 'font-semibold text-amber-400 px-1.5 py-0.5 rounded bg-amber-950 border border-amber-800';
                } else {
                    kategori = 'Perlu Pendampingan';
                    rekomendasi = 'Kelas Matrikulasi / Tahsin Dasar';
                    badgeClass = 'font-semibold text-red-400 px-1.5 py-0.5 rounded bg-red-950 border border-red-800';
                }

                const valEl = document.getElementById('sim-hasil-nilai');
                const katEl = document.getElementById('sim-hasil-kategori');
                const rekEl = document.getElementById('sim-hasil-rekomendasi');

                if (valEl) valEl.textContent = roundRata;
                if (katEl) {
                    katEl.textContent = kategori;
                    katEl.className = badgeClass;
                }
                if (rekEl) rekEl.textContent = rekomendasi;
            }
        </script>

    </body>
</html>
