<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Pedoman Rubrik Penilaian — {{ $user['unit'] ?? 'SAANS' }}</title>
        
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
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white shadow-sm">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-xs font-semibold tracking-tight text-foreground">SAANS</span>
                    </a>
                    <nav class="hidden sm:flex items-center gap-1 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.guru') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            <span>Guru Pengamat</span>
                        </a>
                        <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="notebook-pen" class="h-3.5 w-3.5"></i>
                            <span>Calon Siswa</span>
                        </a>
                        <a href="{{ route('admin.indikator') }}" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground transition-colors">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>Indikator</span>
                        </a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <!-- <span class="inline-flex items-center gap-1.5 rounded-md border border-border bg-card px-2.5 py-0.5 text-[11px] font-medium text-foreground">
                        @if($jenjang === 'tk')
                            <i data-lucide="baby" class="h-3 w-3 text-emerald-400"></i>
                        @elseif($jenjang === 'smp')
                            <i data-lucide="school" class="h-3 w-3 text-purple-400"></i>
                        @else
                            <i data-lucide="backpack" class="h-3 w-3 text-indigo-400"></i>
                        @endif
                        <span>{{ $user['role_label'] ?? 'Admin SPMB' }}</span>
                    </span> -->
                    <div class="flex items-center gap-2 pl-2 border-l border-border">
                        <div class="h-7 w-7 rounded-full bg-muted border border-border flex items-center justify-center text-xs font-semibold text-foreground">
                            {{ substr($user['name'] ?? 'Admin', 0, 2) }}
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-foreground">{{ $user['name'] ?? 'Administrator' }}</span>
                    </div>
                    <!-- Jenjang Switcher -->
                    <div class="inline-flex items-center rounded-lg border border-border bg-card p-1 text-xs">
                        <a href="{{ route('admin.indikator', ['jenjang' => 'sd']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'sd' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
                            SD
                        </a>
                        <a href="{{ route('admin.indikator', ['jenjang' => 'smp']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'smp' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
                            SMP
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

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">Pedoman Rubrik &amp; Instrumen Penilaian SPMB</h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Kelola instrumen observasi, panduan rubrik penilaian skala 1–4, dan bobot tiap aspek asesmen SPMB
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openTambahIndikatorModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                        <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                        <span>Tambah Instrumen</span>
                    </button>
                </div>
            </div>

            @if(session('status'))
                <div data-three-alert="success" class="rounded-lg border border-emerald-500/30 bg-emerald-500/10 p-3 text-xs text-emerald-400 flex items-center gap-2">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div data-three-alert="error" class="rounded-lg border border-red-500/30 bg-red-500/10 p-3 text-xs text-red-400 flex items-center gap-2">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-lg border border-red-500/30 bg-red-500/10 p-3 text-xs text-red-400 space-y-1">
                    <div class="font-semibold flex items-center gap-1.5">
                        <i data-lucide="alert-triangle" class="h-4 w-4"></i>
                        <span>Terdapat kesalahan pengisian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Indikator</span>
                        <i data-lucide="list-checks" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">{{ count($indikator) }}</div>
                    <p class="text-[11px] text-muted-foreground">Instrumen observasi {{ strtoupper($jenjang ?? 'sd') }}</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Aspek / Sentra Dinilai</span>
                        <i data-lucide="layers" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">{{ $grouped->count() }}</div>
                    <p class="text-[11px] text-muted-foreground">Domain / sentra unik</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Aktif</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($indikator)->where('status', 'Aktif')->count() }}
                    </div>
                    <p class="text-[11px] text-emerald-400">Digunakan observasi</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Bobot</span>
                        <i data-lucide="percent" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ round(collect($indikator)->where('status', 'Aktif')->sum('bobot')) }}%
                    </div>
                    <p class="text-[11px] text-amber-400">Akumulasi bobot 100%</p>
                </div>
            </div>

            <!-- Indikator Groups (Sentra Asesmen SD / 4 Kelompok SMP) -->
            <div class="space-y-4">
                @php
                    $aspekMeta = [
                        // SD Sentras
                        'Sentra Ubudiyah'                                                        => ['icon' => 'book-open',       'color' => 'emerald', 'ring' => 'border-emerald-800/60 bg-emerald-950/20', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-900/60'],
                        'Sentra Persiapan'                                                       => ['icon' => 'laptop',          'color' => 'indigo',  'ring' => 'border-indigo-800/60 bg-indigo-950/20',   'text' => 'text-indigo-400',  'dot' => 'bg-indigo-900/60'],
                        'Sentra Eksplorasi Diri'                                                 => ['icon' => 'sparkles',        'color' => 'amber',   'ring' => 'border-amber-800/60 bg-amber-950/20',     'text' => 'text-amber-400',   'dot' => 'bg-amber-900/60'],
                        // SMP Kelompoks
                        'Kelompok Keagamaan'                                                     => ['icon' => 'book-open',       'color' => 'emerald', 'ring' => 'border-emerald-800/60 bg-emerald-950/20', 'text' => 'text-emerald-400', 'dot' => 'bg-emerald-900/60'],
                        'Kelompok Akademik (Numerasi, Literasi, Science, and English)'           => ['icon' => 'laptop',          'color' => 'indigo',  'ring' => 'border-indigo-800/60 bg-indigo-950/20',   'text' => 'text-indigo-400',  'dot' => 'bg-indigo-900/60'],
                        'Kelompok Potensi Bakat dan Minat'                                       => ['icon' => 'sparkles',        'color' => 'amber',   'ring' => 'border-amber-800/60 bg-amber-950/20',     'text' => 'text-amber-400',   'dot' => 'bg-amber-900/60'],
                        'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)'  => ['icon' => 'heart-handshake', 'color' => 'rose',    'ring' => 'border-rose-800/60 bg-rose-950/20',       'text' => 'text-rose-400',    'dot' => 'bg-rose-900/60'],
                    ];
                @endphp

                @foreach($grouped as $aspek => $items)
                    @php
                        $meta = $aspekMeta[$aspek] ?? ['icon' => 'folder-open', 'color' => 'zinc', 'ring' => 'border-border bg-muted', 'text' => 'text-muted-foreground', 'dot' => 'bg-zinc-700'];
                        $groupId = 'group-' . md5($aspek);
                    @endphp
                    <div class="rounded-xl border border-border bg-card overflow-hidden">

                        <!-- Aspek Header (Clickable Collapse Toggle) -->
                        <button
                            type="button"
                            onclick="toggleGroup('{{ $groupId }}')"
                            class="w-full flex items-center justify-between px-5 py-3 border-b border-border bg-card hover:bg-card transition-colors text-left"
                        >
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-6 w-6 items-center justify-center rounded-md border {{ $meta['ring'] }}">
                                    <i data-lucide="{{ $meta['icon'] }}" class="h-3.5 w-3.5 {{ $meta['text'] }}"></i>
                                </div>
                                <span class="text-sm font-semibold text-foreground">{{ $aspek }}</span>
                                <span class="rounded-full border border-border bg-muted px-2 py-0.5 text-[10px] font-medium text-muted-foreground">
                                    {{ $items->count() }} indikator
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-muted-foreground font-mono">Bobot: {{ $items->sum('bobot') }}%</span>
                                <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground transition-transform duration-200" id="{{ $groupId }}-chevron"></i>
                            </div>
                        </button>

                        <!-- Indikator Items (Collapsible) -->
                        <div id="{{ $groupId }}" class="divide-y divide-border/60">

                            @if($aspek === 'Sentra Ubudiyah')
                                <!-- Banner Pedoman Sentra Ubudiyah -->
                                <div class="p-5 bg-gradient-to-r from-emerald-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-emerald-800 bg-emerald-950/80 text-emerald-400">
                                            <i data-lucide="book-open" class="h-4 w-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                Pedoman Asesmen Sentra Ubudiyah
                                                <span class="rounded bg-emerald-900/60 border border-emerald-700/60 text-emerald-300 text-[10px] font-mono px-1.5 py-0.5">3 Sub-Aspek &bull; 7 Indikator &bull; Skala 1–4</span>
                                            </h3>
                                            <p class="text-xs text-muted-foreground mt-0.5">
                                                Observasi pembiasaan ibadah dasar anak: 1.1 Praktek Wudhu dan Sholat &bull; 1.2 Hafalan Doa Sehari-hari &bull; 1.3 Hafalan Surat-Surat Pendek.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 pt-1 text-xs">
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground flex items-center gap-1.5">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span> 1.1 Praktek Wudhu &amp; Sholat
                                            </span>
                                            <p class="text-[11px] text-muted-foreground">Urutan wudhu tertib, gerakan sholat tuma'ninah, dan bacaan sholat tartil.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground flex items-center gap-1.5">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span> 1.2 Doa Sehari-hari
                                            </span>
                                            <p class="text-[11px] text-muted-foreground">Doa sebelum makan dan doa setelah makan dengan adab dan pelafalan fasih.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground flex items-center gap-1.5">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span> 1.3 Surat Pendek
                                            </span>
                                            <p class="text-[11px] text-muted-foreground">Makharijul huruf dan kelancaran Surat Al-Fatihah dan Al-Ikhlas.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($aspek === 'Sentra Persiapan')
                                <!-- Banner Pedoman Sentra Persiapan -->
                                <div class="p-5 bg-gradient-to-r from-indigo-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-indigo-800 bg-indigo-950/80 text-indigo-400">
                                            <i data-lucide="laptop" class="h-4 w-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                Pedoman Asesmen Sentra Persiapan (Calistung &amp; Bahasa)
                                                <span class="rounded bg-indigo-900/60 border border-indigo-700/60 text-indigo-300 text-[10px] font-mono px-1.5 py-0.5">4 Sub-Aspek &bull; 14 Indikator &bull; Skala 1–4</span>
                                            </h3>
                                            <p class="text-xs text-muted-foreground mt-0.5">
                                                Kesiapan akademik awal anak masuk SD: 2.1 Membaca (3 Aspek) &bull; 2.2 Menulis / Dikte (2 Aspek) &bull; 2.3 Berhitung (5 Aspek) &bull; 2.4 English (4 Aspek).
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1 text-xs">
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground">2.1 Membaca</span>
                                            <p class="text-[11px] text-muted-foreground">Huruf A–Z urut, membaca suku kata KV, membaca per kata.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground">2.2 Menulis / Dikte</span>
                                            <p class="text-[11px] text-muted-foreground">Teknik tripod grasp pensil dan menulis nama benda.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground">2.3 Berhitung</span>
                                            <p class="text-[11px] text-muted-foreground">Membilang 1–10 maju/mundur, angka acak, kuantitas &amp; ukuran.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-foreground">2.4 English</span>
                                            <p class="text-[11px] text-muted-foreground">Listening colour, speaking greeting, kosakata buah &amp; hewan.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($aspek === 'Sentra Eksplorasi Diri')
                                <!-- Banner Pedoman Sentra Eksplorasi Diri -->
                                <div class="p-5 bg-gradient-to-r from-amber-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-amber-800 bg-amber-950/80 text-amber-400">
                                            <i data-lucide="sparkles" class="h-4 w-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                Pedoman Asesmen Sentra Eksplorasi Diri (Motorik &amp; Sosial Emosional)
                                                <span class="rounded bg-amber-900/60 border border-amber-700/60 text-amber-300 text-[10px] font-mono px-1.5 py-0.5">2 Sub-Aspek &bull; 18 Indikator &bull; Skala 1–4</span>
                                            </h3>
                                            <p class="text-xs text-muted-foreground mt-0.5">
                                                3.1 Aspek Motorik Halus &amp; Kasar (10 Indikator) &bull; 3.2 Wawancara Transisi Sosial Emosional (8 Pertanyaan).
                                            </p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 pt-1 text-xs">
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-amber-400 flex items-center gap-1.5">
                                                <i data-lucide="activity" class="h-3.5 w-3.5"></i> 3.1 Aspek Motorik (10 Aspek)
                                            </span>
                                            <p class="text-[11px] text-muted-foreground">Meronce, finger painting, menggunting, mencocok jarum, meniti titian, melompat/merayap, koordinasi gerak, meniup balon, duduk tertib, dan eye contact.</p>
                                        </div>
                                        <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                            <span class="font-semibold text-amber-400 flex items-center gap-1.5">
                                                <i data-lucide="heart-handshake" class="h-3.5 w-3.5"></i> 3.2 Sosial Emosional (8 Pertanyaan)
                                            </span>
                                            <p class="text-[11px] text-muted-foreground">Kemandirian rutinitas pagi, toilet training, merapikan mainan &amp; sepatu, regulasi emosi saat dilarang, resiliensi saat jatuh, meminta tolong, dan resolusi konflik.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($aspek === 'Kelompok Keagamaan')
                                <!-- Banner Rumus Perhitungan & Rekomendasi Otomatis Keagamaan -->
                                <div class="p-5 bg-gradient-to-r from-emerald-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-4">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-emerald-800 bg-emerald-950/80 text-emerald-400">
                                                <i data-lucide="calculator" class="h-4 w-4"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                    Rumus Penilaian &amp; Rekomendasi Otomatis Kelompok Keagamaan
                                                    <span class="rounded bg-emerald-900/60 border border-emerald-700/60 text-emerald-300 text-[10px] font-mono px-1.5 py-0.5">4 Pos &bull; Skala 1–4</span>
                                                </h3>
                                                <p class="text-xs text-muted-foreground mt-0.5">
                                                    Pos 1 (7 Aspek, maks 28), Pos 2 (7 Aspek, maks 28), Pos 3 (6 Aspek, maks 24), Pos 4 (7 Aspek, maks 28). Skala nilai 1–4.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grid 2 Kolom: Rumus & Skema Kategori/Rekomendasi -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                        <!-- Box 1: Rumus Perhitungan -->
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="sigma" class="h-3.5 w-3.5 text-emerald-400"></i>
                                                Rumus Perhitungan Nilai:
                                            </p>
                                            <div class="space-y-1.5 text-xs text-foreground/90">
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 font-mono">
                                                    <span class="text-muted-foreground">Nilai Pos 1, 2, 4 (maks 28)</span>
                                                    <span class="text-emerald-400 font-semibold">(Total Skor / 28) × 100</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 font-mono">
                                                    <span class="text-muted-foreground">Nilai Pos 3 (maks 24)</span>
                                                    <span class="text-emerald-400 font-semibold">(Total Skor / 24) × 100</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 font-mono">
                                                    <span class="text-muted-foreground">Nilai Akhir Keagamaan</span>
                                                    <span class="text-emerald-400 font-semibold">Rata-rata dari 4 Nilai Pos</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Box 2: Kategori & Rekomendasi Otomatis -->
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="tag" class="h-3.5 w-3.5 text-amber-400"></i>
                                                Kategori &amp; Rekomendasi Otomatis:
                                            </p>
                                            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                                                <div class="p-1.5 rounded border border-emerald-900/60 bg-emerald-950/30">
                                                    <span class="font-bold text-emerald-300">86 – 100 (Sangat Baik)</span>
                                                    <p class="text-muted-foreground text-[10px]">Kelas Pengayaan / Tilawah Mandiri</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-blue-900/60 bg-blue-950/30">
                                                    <span class="font-bold text-blue-300">76 – 85 (Baik)</span>
                                                    <p class="text-muted-foreground text-[10px]">Kelas Reguler / Pembiasaan</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-amber-900/60 bg-amber-950/30">
                                                    <span class="font-bold text-amber-300">60 – 75 (Berkembang)</span>
                                                    <p class="text-muted-foreground text-[10px]">Pendampingan / Tahsin Lanjutan</p>
                                                </div>
                                                <div class="p-1.5 rounded border border-red-900/60 bg-red-950/30">
                                                    <span class="font-bold text-red-300">&lt; 60 (Perlu Pendampingan)</span>
                                                    <p class="text-muted-foreground text-[10px]">Kelas Matrikulasi / Tahsin Dasar</p>
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
                                            <span class="text-[11px] text-muted-foreground">Uji coba simulasi perhitungan real-time</span>
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                            <div>
                                                <label class="text-[10px] text-muted-foreground block mb-1">Skor Pos 1 (Sholat /28)</label>
                                                <input type="number" id="sim-pos-1" min="0" max="28" value="26" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-border bg-muted px-2 text-xs text-foreground font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-muted-foreground block mb-1">Skor Pos 2 (Mengaji /28)</label>
                                                <input type="number" id="sim-pos-2" min="0" max="28" value="25" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-border bg-muted px-2 text-xs text-foreground font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-muted-foreground block mb-1">Skor Pos 3 (Doa /24)</label>
                                                <input type="number" id="sim-pos-3" min="0" max="24" value="22" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-border bg-muted px-2 text-xs text-foreground font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-muted-foreground block mb-1">Skor Pos 4 (Surat /28)</label>
                                                <input type="number" id="sim-pos-4" min="0" max="28" value="26" oninput="hitungSimulasiKeagamaan()" class="w-full h-8 rounded border border-border bg-muted px-2 text-xs text-foreground font-mono focus:outline-none focus:border-emerald-500" />
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 border-t border-emerald-900/40 text-xs">
                                            <div class="flex items-center gap-3">
                                                <span class="text-muted-foreground">Rata-rata Nilai: <strong id="sim-hasil-nilai" class="text-emerald-300 font-mono text-sm font-bold">92.6</strong></span>
                                                <span class="text-muted-foreground">Kategori: <span id="sim-hasil-kategori" class="font-semibold text-emerald-400 px-1.5 py-0.5 rounded bg-emerald-900/60 border border-emerald-700/60">Sangat Baik</span></span>
                                            </div>
                                            <div class="text-foreground/90 text-[11px]">
                                                Rekomendasi: <span id="sim-hasil-rekomendasi" class="font-medium text-emerald-300">Kelas Pengayaan / Tilawah Mandiri</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($aspek === 'Kelompok Akademik (Numerasi, Literasi, Science, and English)' || $aspek === 'Kelompok Akademik (Numerasi, Literasi, Science and English)')
                                <!-- Banner Pedoman Kelompok Akademik CBT -->
                                <div class="p-5 bg-gradient-to-r from-purple-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-5">
                                    
                                    <!-- Header Rumus & Penjelasan Modul -->
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-purple-800 bg-purple-950/80 text-purple-400">
                                                <i data-lucide="brain-circuit" class="h-4 w-4"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                    Pedoman Asesmen Kelompok Akademik — Pos 5 (CBT Numerasi, Literasi, Science &amp; English)
                                                    <span class="rounded bg-purple-900/60 border border-purple-700/60 text-purple-300 text-[10px] font-mono px-1.5 py-0.5">CBT 4 Mapel</span>
                                                </h3>
                                                <p class="text-xs text-muted-foreground mt-0.5">
                                                    Mencakup Pos 5 (Akademik Dasar CBT 0-100) untuk 4 Mata Pelajaran: Bahasa Indonesia, Matematika, IPAS, dan Bahasa Inggris.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grid 2 Kolom: Rumus & Skema Rekomendasi Akademik -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <!-- Box 1: Rumus Akademik & Keterampilan -->
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="sigma" class="h-3.5 w-3.5 text-purple-400"></i>
                                                Rumus Perhitungan Nilai:
                                            </p>
                                            <div class="space-y-1.5 text-xs text-foreground/90 font-mono">
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 text-[11px]">
                                                    <span class="text-muted-foreground font-sans">Nilai Akhir Pos 5 (CBT)</span>
                                                    <span class="text-purple-400 font-semibold">(B.Indo + MTK + IPAS + Inggris) / 4</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-purple-950/40 border border-purple-800/50 rounded px-2.5 py-1.5 text-[11px]">
                                                    <span class="text-purple-300 font-sans font-semibold">Nilai Akhir Kelompok Akademik</span>
                                                    <span class="text-purple-300 font-bold">= Nilai Pos 5 (rata-rata 4 mapel)</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Box 2: Kategori & Rekomendasi Hasil -->
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="award" class="h-3.5 w-3.5 text-amber-400"></i>
                                                Kategori Hasil Otomatis (Per Pos &amp; Nilai Akhir):
                                            </p>
                                            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                                                <div class="p-2 rounded border border-emerald-900/60 bg-emerald-950/30">
                                                    <span class="font-bold text-emerald-300">86 – 100 (Sangat Baik)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Program Pengayaan / Kelas Unggulan</p>
                                                </div>
                                                <div class="p-2 rounded border border-blue-900/60 bg-blue-950/30">
                                                    <span class="font-bold text-blue-300">76 – 85 (Baik)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Pembelajaran Reguler</p>
                                                </div>
                                                <div class="p-2 rounded border border-amber-900/60 bg-amber-950/30">
                                                    <span class="font-bold text-amber-300">60 – 75 (Berkembang)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Pendampingan Belajar</p>
                                                </div>
                                                <div class="p-2 rounded border border-red-900/60 bg-red-950/30">
                                                    <span class="font-bold text-red-300">&lt; 60 (Perlu Pendampingan)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Kelas Matrikulasi Akademik/Keterampilan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Daftar 4 Mata Pelajaran CBT (Pedoman) -->
                                    <div class="rounded-lg border border-purple-800/60 bg-purple-950/20 p-3.5 space-y-2">
                                        <p class="text-xs font-semibold text-purple-300 flex items-center gap-1.5">
                                            <i data-lucide="book-marked" class="h-3.5 w-3.5"></i>
                                            Komponen Mata Pelajaran CBT (Skala Nilai 0–100):
                                        </p>
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 text-xs">
                                            <div class="p-2.5 rounded bg-card border border-border space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-foreground">1. Bahasa Indonesia</span>
                                                    <span class="text-[10px] font-mono text-purple-400">0–100</span>
                                                </div>
                                                <p class="text-[11px] text-muted-foreground">Literasi teks, pemahaman wacana, dan tata bahasa dasar.</p>
                                            </div>
                                            <div class="p-2.5 rounded bg-card border border-border space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-foreground">2. Matematika</span>
                                                    <span class="text-[10px] font-mono text-purple-400">0–100</span>
                                                </div>
                                                <p class="text-[11px] text-muted-foreground">Numerasi, pemecahan masalah logika, dan operasi hitung.</p>
                                            </div>
                                            <div class="p-2.5 rounded bg-card border border-border space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-foreground">3. IPAS</span>
                                                    <span class="text-[10px] font-mono text-purple-400">0–100</span>
                                                </div>
                                                <p class="text-[11px] text-muted-foreground">Sains dasar, metode observasi alam, dan penalaran sosial.</p>
                                            </div>
                                            <div class="p-2.5 rounded bg-card border border-border space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-foreground">4. Bahasa Inggris</span>
                                                    <span class="text-[10px] font-mono text-purple-400">0–100</span>
                                                </div>
                                                <p class="text-[11px] text-muted-foreground">Reading comprehension, basic vocabulary, dan functional text.</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif

                            @if($aspek === 'Kelompok Potensi Bakat dan Minat' || $aspek === 'Kelompok Potensi Bakat Minat')
                                <!-- Banner Pedoman Kelompok Potensi Bakat Minat (Pos 6 & Pos 7) -->
                                <div class="p-5 bg-gradient-to-r from-blue-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-5">

                                    <!-- Header -->
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-blue-800 bg-blue-950/80 text-blue-400">
                                            <i data-lucide="sparkles" class="h-4 w-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                Pedoman Asesmen Potensi Bakat &amp; Minat
                                                <span class="rounded bg-blue-900/60 border border-blue-700/60 text-blue-300 text-[10px] font-mono px-1.5 py-0.5">Pos 6 &amp; Pos 7</span>
                                            </h3>
                                            <p class="text-xs text-muted-foreground mt-0.5">Pos 6: Motorik Halus &amp; Kreativitas (7 Indikator /28) · Pos 7: Kreativitas &amp; Inovasi (6 Aspek /24)</p>
                                        </div>
                                    </div>

                                    <!-- Rumus -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="sigma" class="h-3.5 w-3.5 text-blue-400"></i>
                                                Rumus Perhitungan Nilai:
                                            </p>
                                            <div class="space-y-1.5 text-xs font-mono">
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 text-[11px]">
                                                    <span class="text-muted-foreground font-sans">Nilai Pos 6 (Motorik Halus)</span>
                                                    <span class="text-blue-400 font-semibold">(Total Skor / 28) × 100</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-muted border border-border rounded px-2.5 py-1.5 text-[11px]">
                                                    <span class="text-muted-foreground font-sans">Nilai Pos 7 (Kreativitas)</span>
                                                    <span class="text-blue-400 font-semibold">(Total Skor / 24) × 100</span>
                                                </div>
                                                <div class="flex items-center justify-between bg-blue-950/40 border border-blue-800/50 rounded px-2.5 py-1.5 text-[11px]">
                                                    <span class="text-blue-300 font-sans font-semibold">Nilai Akhir Kelompok</span>
                                                    <span class="text-blue-300 font-bold">(Pos 6 + Pos 7) / 2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rounded-lg border border-border bg-card p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                <i data-lucide="award" class="h-3.5 w-3.5 text-amber-400"></i>
                                                Kategori Hasil Otomatis:
                                            </p>
                                            <div class="grid grid-cols-2 gap-1.5 text-[11px]">
                                                <div class="p-2 rounded border border-emerald-900/60 bg-emerald-950/30">
                                                    <span class="font-bold text-emerald-300">86 – 100 (Sangat Baik)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Program Pengayaan / Kelas Unggulan</p>
                                                </div>
                                                <div class="p-2 rounded border border-blue-900/60 bg-blue-950/30">
                                                    <span class="font-bold text-blue-300">76 – 85 (Baik)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Pembelajaran Reguler</p>
                                                </div>
                                                <div class="p-2 rounded border border-amber-900/60 bg-amber-950/30">
                                                    <span class="font-bold text-amber-300">60 – 75 (Berkembang)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Pendampingan Belajar</p>
                                                </div>
                                                <div class="p-2 rounded border border-red-900/60 bg-red-950/30">
                                                    <span class="font-bold text-red-300">&lt; 60 (Perlu Pendampingan)</span>
                                                    <p class="text-muted-foreground text-[10px] mt-0.5">Kelas Matrikulasi</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ringkasan Pos Bakat & Minat (Pedoman) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="rounded-lg border border-blue-900/60 bg-blue-950/20 p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-blue-300 flex items-center gap-1.5">
                                                <i data-lucide="scissors" class="h-3.5 w-3.5"></i>
                                                Pos 6: Motorik Halus &amp; Kreativitas (7 Indikator)
                                            </p>
                                            <p class="text-[11px] text-muted-foreground">
                                                Skala 1–4, Skor Maksimal 28. Menilai koordinasi mata-tangan, ketepatan bentuk, kontrol gerakan tangan, ketelitian mengikuti instruksi, pemahaman ruang &amp; bentuk, kemampuan menyusun objek, dan kerapian karya.
                                            </p>
                                        </div>
                                        <div class="rounded-lg border border-blue-900/60 bg-blue-950/20 p-3.5 space-y-2">
                                            <p class="text-xs font-semibold text-blue-300 flex items-center gap-1.5">
                                                <i data-lucide="lightbulb" class="h-3.5 w-3.5"></i>
                                                Pos 7: Kreativitas &amp; Inovasi (6 Aspek)
                                            </p>
                                            <p class="text-[11px] text-muted-foreground">
                                                Skala 1–4, Skor Maksimal 24. Menilai 6 aspek Torrance: Fluency (kelancaran gagasan), Flexibility (keberagaman sudut pandang), Originality (kebaruan gagasan), Elaboration (elaborasi detail), Problem solving, dan Communication.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($aspek === 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation & Kelompok)' || $aspek === 'Kelompok Perkembangan Pribadi & Sosial (Daily Conversation)' || $aspek === 'Kelompok Perkembangan Pribadi & Sosial (Asesmen Psikologi)')
                                <!-- Banner Pedoman Perkembangan Pribadi & Sosial -->
                                <div class="p-5 bg-gradient-to-r from-rose-950/40 via-zinc-900 to-zinc-900 border-b border-border space-y-5">

                                    <!-- Header -->
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-rose-800 bg-rose-950/80 text-rose-400">
                                            <i data-lucide="heart-handshake" class="h-4 w-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                Pedoman Asesmen Perkembangan Pribadi &amp; Sosial (Daily Conversation &amp; Kelompok)
                                                <span class="rounded bg-rose-900/60 border border-rose-700/60 text-rose-300 text-[10px] font-mono px-1.5 py-0.5">6 Aspek &bull; Skor Maks 24</span>
                                            </h3>
                                            <p class="text-xs text-muted-foreground mt-0.5">Digunakan sebagai acuan penilaian pada sesi wawancara individual (daily conversation) maupun observasi aktivitas diskusi kelompok, yang dilengkapi dengan angket refleksi diri calon murid.</p>
                                        </div>
                                    </div>

                                    <!-- Tab Navigation (Pedoman Instrumen) -->
                                    <div class="rounded-xl border border-border bg-muted/40 p-4 space-y-4">
                                        <div class="flex items-center justify-between border-b border-border pb-3">
                                            <div class="inline-flex flex-wrap items-center gap-1.5 p-1 rounded-lg bg-card border border-border text-xs">
                                                <button type="button" id="psi-tab-btn-rubrik" onclick="switchPsiTab('rubrik')"
                                                    class="psi-tab-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium transition-all bg-rose-600 text-white shadow-sm">
                                                    <i data-lucide="check-square" class="h-3.5 w-3.5"></i>
                                                    <span>Rubrik Penilaian (6 Aspek)</span>
                                                </button>
                                                <button type="button" id="psi-tab-btn-angket" onclick="switchPsiTab('angket')"
                                                    class="psi-tab-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">
                                                    <i data-lucide="clipboard-list" class="h-3.5 w-3.5"></i>
                                                    <span>Angket Refleksi Diri (10 Item)</span>
                                                </button>
                                                <button type="button" id="psi-tab-btn-wawancara" onclick="switchPsiTab('wawancara')"
                                                    class="psi-tab-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">
                                                    <i data-lucide="mic" class="h-3.5 w-3.5"></i>
                                                    <span>Panduan Wawancara (8 Topik)</span>
                                                </button>
                                            </div>
                                            <span class="text-xs text-muted-foreground font-medium">Acuan Instrumen &amp; Rubrik</span>
                                        </div>

                                        <!-- PSI TAB 1: Rubrik Penilaian Observasi & Wawancara (6 Aspek, Skala 1-4, Skor Maks = 24) -->
                                        <div id="psi-panel-rubrik" class="psi-panel space-y-4">
                                            <div class="p-3 rounded-lg border border-border bg-card space-y-1">
                                                <h4 class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                    <i data-lucide="check-square" class="h-3.5 w-3.5 text-rose-400"></i>
                                                    <span>Rubrik Observasi &amp; Percakapan Individual (6 Aspek &bull; Skor Maks = 24)</span>
                                                </h4>
                                                <p class="text-[11px] text-muted-foreground">Digunakan penguji untuk menilai perkembangan pribadi &amp; sosial melalui wawancara individual santai (daily conversation) dan observasi interaksi kelompok.</p>
                                            </div>

                                            <div class="space-y-2.5">
                                                @php
                                                    $rubrikPsiItems = [
                                                        1 => ['nama' => 'Kemandirian', 'bobot' => '2.7%', 'deskripsi' => 'Kemampuan melakukan tugas dan aktivitas secara mandiri tanpa banyak bergantung pada orang lain.', 'rubrik' => [4 => 'Melakukan tugas secara mandiri', 3 => 'Sedikit arahan', 2 => 'Beberapa kali memerlukan bantuan', 1 => 'Memerlukan pendampingan intensif']],
                                                        2 => ['nama' => 'Komunikasi', 'bobot' => '2.7%', 'deskripsi' => 'Kemampuan menyampaikan gagasan, menyimak lawan bicara, dan merespons dengan santun dan jelas.', 'rubrik' => [4 => 'Menyampaikan gagasan dengan jelas', 3 => 'Cukup jelas', 2 => 'Masih terbatas', 1 => 'Belum menunjukkan respons yang memadai']],
                                                        3 => ['nama' => 'Kerja sama', 'bobot' => '2.7%', 'deskripsi' => 'Kemampuan berkolaborasi secara aktif, saling menghargai anggota lain, dan menyelesaikan tugas bersama.', 'rubrik' => [4 => 'Aktif dan menghargai anggota lain', 3 => 'Bekerja sama dengan baik', 2 => 'Terlibat setelah diarahkan', 1 => 'Belum terlibat secara konsisten']],
                                                        4 => ['nama' => 'Regulasi emosi', 'bobot' => '2.7%', 'deskripsi' => 'Kemampuan mengenali dan mengelola respons emosi secara wajar saat menghadapi tekanan atau interaksi sosial.', 'rubrik' => [4 => 'Mampu mengenali dan mengelola respons', 3 => 'Umumnya mampu', 2 => 'Masih memerlukan arahan', 1 => 'Membutuhkan pendampingan lebih lanjut']],
                                                        5 => ['nama' => 'Tanggung jawab', 'bobot' => '2.6%', 'deskripsi' => 'Konsistensi menyelesaikan tugas dan tanggung jawab yang diberikan secara tuntas.', 'rubrik' => [4 => 'Konsisten menyelesaikan tugas', 3 => 'Sebagian besar selesai', 2 => 'Memerlukan pengingat', 1 => 'Belum menunjukkan penyelesaian tugas']],
                                                        6 => ['nama' => 'Empati', 'bobot' => '2.6%', 'deskripsi' => 'Menunjukkan kepedulian secara aktif dan peka terhadap perasaan serta kebutuhan orang lain di sekitarnya.', 'rubrik' => [4 => 'Menunjukkan kepedulian secara aktif', 3 => 'Menunjukkan kepedulian', 2 => 'Memerlukan pengingat', 1 => 'Belum tampak dalam situasi pengamatan']],
                                                    ];
                                                @endphp
                                                @foreach($rubrikPsiItems as $rIdx => $r)
                                                    <div class="p-3 rounded-lg border border-border bg-card space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <span class="flex h-5 w-5 items-center justify-center rounded bg-muted border border-border text-xs font-mono text-muted-foreground shrink-0">{{ $rIdx }}</span>
                                                                <div>
                                                                    <div class="flex items-center gap-1.5">
                                                                        <p class="text-xs font-semibold text-foreground">{{ $r['nama'] }}</p>
                                                                        <span class="rounded bg-rose-950 border border-rose-800 text-rose-300 text-[10px] font-mono px-1">Bobot: {{ $r['bobot'] }}</span>
                                                                    </div>
                                                                    <p class="text-[11px] text-muted-foreground">{{ $r['deskripsi'] }}</p>
                                                                </div>
                                                            </div>
                                                            <span class="text-[10px] font-mono text-rose-400 bg-rose-950/60 border border-rose-800/60 rounded px-1.5 py-0.5">Skala 1–4</span>
                                                        </div>
                                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-1.5 pt-1">
                                                            @foreach($r['rubrik'] as $rSkor => $rText)
                                                                <div class="p-2 rounded border border-border bg-muted/40 text-[10px] space-y-0.5">
                                                                    <span class="font-bold font-mono text-rose-400">Skor {{ $rSkor }}</span>
                                                                    <p class="text-foreground/90 leading-snug">{{ $rText }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Pemetaan Layanan BK -->
                                            <div class="rounded-lg border border-border bg-card p-3 space-y-2">
                                                <p class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                    <i data-lucide="compass" class="h-3.5 w-3.5 text-rose-400"></i>
                                                    Pemetaan Kesiapan Transisi &amp; Layanan Bimbingan Konseling (BK):
                                                </p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                                    <div class="p-2 rounded border border-emerald-900/60 bg-emerald-950/30">
                                                        <span class="font-bold text-emerald-300">86 – 100 &rarr; Sangat Baik</span>
                                                        <p class="text-muted-foreground text-[10px] mt-0.5">Sangat siap beradaptasi mandiri, memiliki potensi peer-leader dan inisiatif tinggi.</p>
                                                    </div>
                                                    <div class="p-2 rounded border border-blue-900/60 bg-blue-950/30">
                                                        <span class="font-bold text-blue-300">76 – 85 &rarr; Baik</span>
                                                        <p class="text-muted-foreground text-[10px] mt-0.5">Adaptasi baik, pembiasaan reguler bersama wali kelas dalam lingkungan SMP.</p>
                                                    </div>
                                                    <div class="p-2 rounded border border-amber-900/60 bg-amber-950/30">
                                                        <span class="font-bold text-amber-300">60 – 75 &rarr; Berkembang</span>
                                                        <p class="text-muted-foreground text-[10px] mt-0.5">Pemantauan berkala, penguatan motivasi dan sosial oleh guru BK.</p>
                                                    </div>
                                                    <div class="p-2 rounded border border-red-900/60 bg-red-950/30">
                                                        <span class="font-bold text-red-300">&lt; 60 &rarr; Perlu Pendampingan</span>
                                                        <p class="text-muted-foreground text-[10px] mt-0.5">Prioritas pendampingan intensif BK &amp; koordinasi dengan orang tua/wali.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PSI TAB 2: Pos 8-A Angket Refleksi Diri (10 pernyataan, skala 1-4) -->
                                        <div id="psi-panel-angket" class="psi-panel hidden space-y-4">
                                            <div class="p-3 rounded-lg border border-border bg-card space-y-1.5">
                                                <h4 class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                    <i data-lucide="clipboard-list" class="h-3.5 w-3.5 text-rose-400"></i>
                                                    <span>Sub-Instrumen A: Angket Refleksi Diri Calon Murid (10 Pernyataan &bull; Skor Maks = 40)</span>
                                                </h4>
                                                <div class="p-2.5 rounded bg-muted border border-border text-[11px] text-foreground/90">
                                                    <span class="font-semibold text-foreground block mb-1">Pedoman Skala Respon Angket:</span>
                                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 text-[10px] text-muted-foreground">
                                                        <div><strong class="text-emerald-400">4:</strong> Sangat Sesuai</div>
                                                        <div><strong class="text-blue-400">3:</strong> Sesuai</div>
                                                        <div><strong class="text-amber-400">2:</strong> Kadang-kadang</div>
                                                        <div><strong class="text-red-400">1:</strong> Belum Sesuai</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                @php
                                                    $angketItems = [
                                                        1  => 'Saya mengetahui hal yang menjadi kelebihan saya',
                                                        2  => 'Saya berusaha menyelesaikan tugas yang diberikan',
                                                        3  => 'Saya berani bertanya ketika belum memahami pelajaran',
                                                        4  => 'Saya dapat menerima masukan dari orang lain',
                                                        5  => 'Saya berusaha mengelola waktu belajar',
                                                        6  => 'Saya menghargai perbedaan pendapat',
                                                        7  => 'Saya bersedia membantu teman yang membutuhkan',
                                                        8  => 'Saya berusaha bertanggung jawab atas kesalahan saya',
                                                        9  => 'Saya bersedia mencoba kegiatan baru',
                                                        10 => 'Saya menggunakan perangkat digital dengan memperhatikan tujuan penggunaannya',
                                                    ];
                                                @endphp
                                                @foreach($angketItems as $aIdx => $aText)
                                                    <div class="p-3 rounded-lg border border-border bg-card flex items-center justify-between gap-2">
                                                        <div class="flex items-start gap-2.5">
                                                            <span class="flex h-5 w-5 items-center justify-center rounded bg-muted border border-border text-xs font-mono text-muted-foreground shrink-0 mt-0.5">{{ $aIdx }}</span>
                                                            <p class="text-xs text-foreground">{{ $aText }}</p>
                                                        </div>
                                                        <span class="text-[10px] font-mono text-muted-foreground bg-muted border border-border rounded px-1.5 py-0.5 shrink-0">Skor 1–4</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- PSI TAB 3: Pos 8-B Wawancara Individual -->
                                        <div id="psi-panel-wawancara" class="psi-panel hidden space-y-4">
                                            <div class="p-3 rounded-lg border border-border bg-card space-y-1.5">
                                                <h4 class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                                    <i data-lucide="mic" class="h-3.5 w-3.5 text-rose-400"></i>
                                                    <span>Sub-Instrumen B: Wawancara Individual &amp; Percakapan Orang Tua (Catatan Kualitatif)</span>
                                                </h4>
                                                <p class="text-[11px] text-muted-foreground">Panduan pertanyaan 8 topik untuk wawancara calon murid dan obrolan dengan orang tua/wali. Data ini bersifat kualitatif dan tidak mempengaruhi skor akumulasi.</p>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                @php
                                                    $wawancaraTopik = [
                                                        1 => ['judul' => 'Kesadaran Diri', 'pertanyaan' => ['Apa yang kamu sukai dari dirimu sendiri?', 'Apa yang ingin kamu tingkatkan?', 'Bagaimana kamu tahu kamu sudah berhasil?']],
                                                        2 => ['judul' => 'Regulasi Emosi', 'pertanyaan' => ['Apa yang kamu lakukan ketika merasa marah atau sedih?', 'Pernah merasa tidak ingin sekolah? Mengapa?']],
                                                        3 => ['judul' => 'Kepercayaan Diri', 'pertanyaan' => ['Apakah kamu berani bertanya kepada guru?', 'Bagaimana perasaanmu saat tampil di depan kelas?']],
                                                        4 => ['judul' => 'Kemandirian', 'pertanyaan' => ['Siapa yang biasanya mengingatkan kamu belajar?', 'Apakah kamu bisa mengatur waktu sendiri?']],
                                                        5 => ['judul' => 'Interaksi Sosial', 'pertanyaan' => ['Bagaimana cara kamu berkenalan dengan teman baru?', 'Pernah ada konflik dengan teman? Bagaimana kamu menyelesaikannya?']],
                                                        6 => ['judul' => 'Empati & Tanggung Jawab', 'pertanyaan' => ['Pernah membantu teman yang kesulitan? Ceritakan!', 'Apa yang kamu lakukan jika melakukan kesalahan?']],
                                                        7 => ['judul' => 'Motivasi & Adaptasi', 'pertanyaan' => ['Apa impian atau cita-citamu?', 'Bagaimana perasaanmu jika harus pindah ke lingkungan baru?']],
                                                        8 => ['judul' => 'Literasi Digital', 'pertanyaan' => ['Apa yang kamu lakukan dengan gadget atau HP?', 'Apakah kamu tahu apa itu jejak digital?']],
                                                    ];
                                                @endphp
                                                @foreach($wawancaraTopik as $wIdx => $w)
                                                    <div class="rounded-lg border border-border bg-card overflow-hidden">
                                                        <div class="px-3 py-2 bg-muted flex items-center gap-2">
                                                            <span class="flex h-5 w-5 items-center justify-center rounded bg-zinc-700 border border-zinc-600 text-xs font-mono text-foreground/90 shrink-0">{{ $wIdx }}</span>
                                                            <h5 class="text-xs font-semibold text-foreground">{{ $w['judul'] }}</h5>
                                                        </div>
                                                        <div class="p-3">
                                                            <ul class="space-y-1">
                                                                @foreach($w['pertanyaan'] as $q)
                                                                    <li class="text-[11px] text-muted-foreground flex items-start gap-1.5">
                                                                        <span class="text-rose-500 mt-0.5">›</span>{{ $q }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
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
                                <div class="px-5 py-2.5 bg-card border-b border-border flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <span class="text-muted-foreground font-medium flex items-center gap-1.5">
                                        <i data-lucide="layers" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        Daftar {{ $posGroups->count() }} Pos Observasi (Klik pada Pos untuk buka / tutup aspek)
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" onclick="expandAllPos()" class="inline-flex items-center gap-1 text-[11px] text-foreground/90 hover:text-emerald-400 bg-muted hover:bg-zinc-700/80 border border-border px-2.5 py-1 rounded-md transition-colors cursor-pointer">
                                            <i data-lucide="chevrons-up-down" class="h-3 w-3"></i>
                                            Buka Semua Pos
                                        </button>
                                        <button type="button" onclick="collapseAllPos()" class="inline-flex items-center gap-1 text-[11px] text-muted-foreground hover:text-foreground bg-muted hover:bg-zinc-700/80 border border-border px-2.5 py-1 rounded-md transition-colors cursor-pointer">
                                            <i data-lucide="chevrons-down-up" class="h-3 w-3"></i>
                                            Tutup Semua Pos
                                        </button>
                                    </div>
                                </div>

                                @foreach($posGroups as $posTitle => $posItems)
                                    @php
                                        $posKey = $groupId . '-pos-' . $loop->index;
                                        $isCbt = ($posItems->first()['tipe'] ?? '') === 'cbt_score';
                                        $posMaxScore = $isCbt ? 100 : ($posItems->count() * 4);
                                    @endphp
                                    <div class="bg-card border-b border-border last:border-b-0">
                                        <!-- Pos Sub-Header Collapsible Button -->
                                        <button 
                                            type="button" 
                                            onclick="toggleGroup('{{ $posKey }}')" 
                                            class="w-full px-5 py-3.5 bg-card hover:bg-muted border-b border-border flex flex-wrap items-center justify-between gap-3 text-left transition-colors cursor-pointer"
                                        >
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $posBadge = str_starts_with($posTitle, 'Pos') ? substr($posTitle, 4, 1) : explode(' ', $posTitle)[0];
                                                @endphp
                                                <span class="flex h-7 min-w-7 px-1.5 items-center justify-center rounded-lg bg-muted border border-border text-foreground text-xs font-bold font-mono shrink-0">
                                                    {{ $posBadge }}
                                                </span>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-foreground flex items-center gap-2">
                                                        {{ $posTitle }}
                                                        <span class="rounded bg-muted border border-border px-2 py-0.5 text-[10px] font-mono font-normal text-foreground/90">{{ $posItems->count() }} Aspek</span>
                                                    </h4>
                                                    <p class="text-[11px] text-muted-foreground mt-0.5">
                                                        @if($isCbt)
                                                            Skala Nilai 0–100 per Mapel &bull; Bobot: {{ round($posItems->sum('bobot'), 1) }}% &bull; Rumus Pos: Rata-rata 4 Mapel
                                                        @else
                                                            Skala Nilai 1–4 &bull; Skor Maksimal {{ $posMaxScore }} &bull; Bobot Pos: {{ round($posItems->sum('bobot'), 1) }}% &bull; Rumus: (Skor / {{ $posMaxScore }}) × 100
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2.5">
                                                <span class="rounded bg-muted border border-border px-2 py-0.5 text-[11px] font-mono text-foreground/90 hidden sm:inline">
                                                    @if($isCbt)
                                                        Rumus: Rata-rata 4 Mapel
                                                    @else
                                                        Rumus: (Skor / {{ $posMaxScore }}) × 100
                                                    @endif
                                                </span>
                                                <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground transition-transform duration-200" id="{{ $posKey }}-chevron"></i>
                                            </div>
                                        </button>

                                        <!-- Pos Aspects List (Collapsible) -->
                                        <div id="{{ $posKey }}" class="divide-y divide-border/50">
                                            @foreach($posItems as $ind)
                                                <div class="p-5 space-y-3 hover:bg-card transition-colors">
                                                    <!-- Aspect Row Header -->
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div class="flex items-start gap-3 flex-1 min-w-0">
                                                            <span class="shrink-0 font-mono text-[10px] text-muted-foreground mt-0.5 bg-muted border border-border rounded px-1.5 py-0.5">
                                                                {{ $ind['id'] }}
                                                            </span>
                                                            <div class="min-w-0">
                                                                <div class="flex items-center gap-2">
                                                                    <p class="text-sm font-semibold text-foreground leading-snug">{{ $ind['nama'] }}</p>
                                                                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950/60 border border-emerald-800/60 rounded px-1.5 py-0.2">Skor 1–4</span>
                                                                </div>
                                                                <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed">{{ $ind['deskripsi'] }}</p>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2 shrink-0">
                                                            <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-mono font-medium text-foreground">
                                                                <i data-lucide="percent" class="h-3 w-3 text-muted-foreground"></i>
                                                                {{ $ind['bobot'] }}%
                                                            </span>
                                                            @if(($ind['status'] ?? 'Aktif') === 'Aktif')
                                                                <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                                    <i data-lucide="circle-dot" class="h-3 w-3"></i>
                                                                    Aktif
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                                    <i data-lucide="pencil" class="h-3 w-3"></i>
                                                                    {{ $ind['status'] }}
                                                                </span>
                                                            @endif
                                                            <button type="button" onclick="openEditIndikatorModal({{ json_encode($ind) }})" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer" title="Edit Instrumen & Rubrik">
                                                                <i data-lucide="pencil" class="h-3 w-3 text-muted-foreground"></i>
                                                                <span class="hidden sm:inline">Edit</span>
                                                            </button>
                                                            <button type="button" onclick="confirmDeleteIndikator({{ json_encode($ind) }})" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-rose-500 hover:bg-rose-500/10 hover:border-rose-500/30 transition-colors cursor-pointer" title="Hapus Instrumen">
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
                                                                        Skor {{ $skor }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-[11px] text-foreground/90 leading-relaxed">{{ $deskripsi }}</p>
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
                                                <span class="shrink-0 font-mono text-[10px] text-muted-foreground mt-0.5 bg-muted border border-border rounded px-1.5 py-0.5">
                                                    {{ $ind['id'] }}
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-foreground leading-snug">{{ $ind['nama'] }}</p>
                                                    <p class="text-xs text-muted-foreground mt-0.5 leading-relaxed">{{ $ind['deskripsi'] }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-mono font-medium text-foreground">
                                                    <i data-lucide="percent" class="h-3 w-3 text-muted-foreground"></i>
                                                    {{ $ind['bobot'] }}
                                                </span>
                                                @if(($ind['status'] ?? 'Aktif') === 'Aktif')
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                        <i data-lucide="circle-dot" class="h-3 w-3"></i>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                        <i data-lucide="pencil" class="h-3 w-3"></i>
                                                        {{ $ind['status'] }}
                                                    </span>
                                                @endif
                                                <button type="button" onclick="openEditIndikatorModal({{ json_encode($ind) }})" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer" title="Edit Instrumen & Rubrik">
                                                    <i data-lucide="pencil" class="h-3 w-3 text-muted-foreground"></i>
                                                    <span class="hidden sm:inline">Edit</span>
                                                </button>
                                                <button type="button" onclick="confirmDeleteIndikator({{ json_encode($ind) }})" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-rose-500 hover:bg-rose-500/10 hover:border-rose-500/30 transition-colors cursor-pointer" title="Hapus Instrumen">
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
                                                    <p class="text-[11px] text-foreground/90 leading-relaxed">{{ $deskripsi }}</p>
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

        <!-- ========================================================================= -->
        <!-- MODAL TAMBAH INSTRUMEN & RUBRIK                                           -->
        <!-- ========================================================================= -->
        <div id="modal-tambah-indikator" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-2xl rounded-xl border border-border bg-card text-foreground shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[92vh] flex flex-col">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary border border-primary/20">
                            <i data-lucide="plus-circle" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-foreground">Tambah Instrumen &amp; Rubrik Baru</h3>
                            <p class="text-[11px] text-muted-foreground">Unit: {{ strtoupper($jenjang ?? 'sd') }} — Tambahkan aspek observasi dan rubrik skala 1–4</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeTambahIndikatorModal()" class="rounded-md p-1 text-muted-foreground hover:bg-muted hover:text-foreground">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>

                <form action="{{ route('admin.indikator.store') }}" method="POST" class="p-5 space-y-4 text-xs overflow-y-auto flex-1">
                    @csrf
                    <input type="hidden" name="jenjang" value="{{ $jenjang ?? 'sd' }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Aspek / Sentra <span class="text-rose-500">*</span></label>
                            <input list="aspek-list-tambah" name="aspek" id="tambah-indikator-aspek" required placeholder="Pilih atau ketik aspek..." class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                            <datalist id="aspek-list-tambah">
                                @foreach($grouped->keys() as $a)
                                    <option value="{{ $a }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Sub-Aspek / Pos</label>
                            <input type="text" name="pos_nama" id="tambah-indikator-pos" placeholder="Contoh: 1.1 Praktek Wudhu atau Pos 1" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Kode Instrumen</label>
                            <input type="text" name="kode" id="tambah-indikator-kode" placeholder="Otomatis jika kosong" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground font-mono focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Bobot Penilaian (%)</label>
                            <input type="number" step="0.1" name="bobot" id="tambah-indikator-bobot" value="2.5" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground font-mono focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Status</label>
                            <select name="status" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                                <option value="Aktif">Aktif</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Instrumen / Indikator <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" id="tambah-indikator-nama" required placeholder="Contoh: Niat dan Gerakan Berwudhu" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Deskripsi / Panduan Pengamatan</label>
                        <textarea name="deskripsi" id="tambah-indikator-deskripsi" rows="2" placeholder="Jelaskan fokus amatan guru saat mengobservasi instrumen ini..." class="flex w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"></textarea>
                    </div>

                    <!-- Rubrik Penilaian Skala 1-4 -->
                    <div class="pt-2 border-t border-border space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                <i data-lucide="scale" class="h-3.5 w-3.5 text-indigo-400"></i>
                                Rubrik Skoring Skala 1–4
                            </span>
                            <span class="text-[11px] text-muted-foreground">Kriteria capaian setiap skor</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div class="p-2.5 rounded-lg border border-red-900/40 bg-red-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-red-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-red-900/60 text-[10px]">1</span>
                                    Skor 1 (Perlu Bimbingan)
                                </label>
                                <textarea name="rubrik_1" rows="2" placeholder="Kriteria ketika anak belum mampu / butuh bimbingan penuh..." class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-red-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-amber-900/40 bg-amber-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-amber-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-900/60 text-[10px]">2</span>
                                    Skor 2 (Berkembang)
                                </label>
                                <textarea name="rubrik_2" rows="2" placeholder="Kriteria ketika anak berkembang dengan bantuan/arahan berkala..." class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-amber-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-indigo-900/40 bg-indigo-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-indigo-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-indigo-900/60 text-[10px]">3</span>
                                    Skor 3 (Baik / Mandiri)
                                </label>
                                <textarea name="rubrik_3" rows="2" placeholder="Kriteria ketika anak mampu mandiri dengan sedikit dorongan..." class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-indigo-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-emerald-900/40 bg-emerald-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-emerald-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-900/60 text-[10px]">4</span>
                                    Skor 4 (Sangat Baik / Mahir)
                                </label>
                                <textarea name="rubrik_4" rows="2" placeholder="Kriteria ketika anak mandiri penuh, akurat, dan konsisten..." class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-emerald-400"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeTambahIndikatorModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary text-primary-foreground px-3 text-xs font-semibold shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                            <span>Simpan Instrumen &amp; Rubrik</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL EDIT INSTRUMEN & RUBRIK                                             -->
        <!-- ========================================================================= -->
        <div id="modal-edit-indikator" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-2xl rounded-xl border border-border bg-card text-foreground shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 max-h-[92vh] flex flex-col">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-foreground">Edit Instrumen &amp; Rubrik Penilaian</h3>
                            <p class="text-[11px] text-muted-foreground">Sesuaikan redaksi instrumen, bobot, atau deskripsi capaian rubrik</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditIndikatorModal()" class="rounded-md p-1 text-muted-foreground hover:bg-muted hover:text-foreground">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>

                <form action="{{ route('admin.indikator.update') }}" method="POST" class="p-5 space-y-4 text-xs overflow-y-auto flex-1">
                    @csrf
                    <input type="hidden" name="id" id="edit-indikator-id">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Aspek / Sentra <span class="text-rose-500">*</span></label>
                            <input list="aspek-list-edit" name="aspek" id="edit-indikator-aspek" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                            <datalist id="aspek-list-edit">
                                @foreach($grouped->keys() as $a)
                                    <option value="{{ $a }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Sub-Aspek / Pos</label>
                            <input type="text" name="pos_nama" id="edit-indikator-pos" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Kode Instrumen</label>
                            <input type="text" name="kode" id="edit-indikator-kode" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground font-mono focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Bobot Penilaian (%)</label>
                            <input type="number" step="0.1" name="bobot" id="edit-indikator-bobot" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground font-mono focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Status</label>
                            <select name="status" id="edit-indikator-status" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                                <option value="Aktif">Aktif</option>
                                <option value="Draft">Draft</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Instrumen / Indikator <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" id="edit-indikator-nama" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Deskripsi / Panduan Pengamatan</label>
                        <textarea name="deskripsi" id="edit-indikator-deskripsi" rows="2" class="flex w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"></textarea>
                    </div>

                    <!-- Rubrik Penilaian Skala 1-4 -->
                    <div class="pt-2 border-t border-border space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                                <i data-lucide="scale" class="h-3.5 w-3.5 text-indigo-400"></i>
                                Rubrik Skoring Skala 1–4
                            </span>
                            <span class="text-[11px] text-muted-foreground">Kriteria capaian setiap skor</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div class="p-2.5 rounded-lg border border-red-900/40 bg-red-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-red-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-red-900/60 text-[10px]">1</span>
                                    Skor 1 (Perlu Bimbingan)
                                </label>
                                <textarea name="rubrik_1" id="edit-indikator-rubrik-1" rows="2" class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-red-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-amber-900/40 bg-amber-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-amber-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-amber-900/60 text-[10px]">2</span>
                                    Skor 2 (Berkembang)
                                </label>
                                <textarea name="rubrik_2" id="edit-indikator-rubrik-2" rows="2" class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-amber-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-indigo-900/40 bg-indigo-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-indigo-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-indigo-900/60 text-[10px]">3</span>
                                    Skor 3 (Baik / Mandiri)
                                </label>
                                <textarea name="rubrik_3" id="edit-indikator-rubrik-3" rows="2" class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-indigo-400"></textarea>
                            </div>
                            <div class="p-2.5 rounded-lg border border-emerald-900/40 bg-emerald-950/20 space-y-1">
                                <label class="text-[11px] font-bold text-emerald-300 flex items-center gap-1">
                                    <span class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-900/60 text-[10px]">4</span>
                                    Skor 4 (Sangat Baik / Mahir)
                                </label>
                                <textarea name="rubrik_4" id="edit-indikator-rubrik-4" rows="2" class="w-full rounded border border-border bg-card p-1.5 text-xs text-foreground placeholder:text-muted-foreground/60 focus:outline-none focus:ring-1 focus:ring-emerald-400"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeEditIndikatorModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
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
        <!-- MODAL HAPUS INSTRUMEN INDIKATOR                                           -->
        <!-- ========================================================================= -->
        <div id="modal-delete-indikator" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-sm rounded-xl border border-border bg-card text-foreground shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center gap-2.5 text-rose-400">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <i data-lucide="alert-triangle" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Hapus Instrumen</h3>
                        <p class="text-[11px] text-muted-foreground">Konfirmasi penghapusan indikator</p>
                    </div>
                </div>

                <form action="{{ route('admin.indikator.delete') }}" method="POST" class="p-4 space-y-3 text-xs">
                    @csrf
                    <input type="hidden" name="id" id="delete-indikator-id">

                    <p class="text-muted-foreground leading-relaxed">
                        Apakah Anda yakin ingin menghapus instrumen <strong class="text-foreground" id="delete-indikator-nama">-</strong> (<span class="font-mono text-muted-foreground" id="delete-indikator-kode">-</span>)?
                    </p>
                    <p class="text-[11px] text-amber-400/90 bg-amber-500/10 border border-amber-500/20 rounded-md p-2">
                        Perhatian: Instrumen dan rubrik terkait akan dihapus dari acuan observasi unit ini.
                    </p>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeDeleteIndikatorModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-rose-600 hover:bg-rose-500 text-white px-3 text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                            <span>Ya, Hapus Instrumen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <footer class="border-t border-border py-4 px-6 text-center text-xs text-muted-foreground">
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Manajemen Instrumen Observasi SPMB
        </footer>

        <script>
            // Helper: Klasifikasi Kategori & Rekomendasi
            function getKategoriRekomendasi(score) {
                const s = Math.round(score * 10) / 10;
                if (s >= 86) {
                    return {
                        kategori: 'Sangat Baik',
                        rekomendasi: 'Program Pengayaan / Kelas Unggulan',
                        badge: 'bg-emerald-950 text-emerald-400 border-emerald-800'
                    };
                } else if (s >= 76) {
                    return {
                        kategori: 'Baik',
                        rekomendasi: 'Pembelajaran Reguler',
                        badge: 'bg-blue-950 text-blue-400 border-blue-800'
                    };
                } else if (s >= 60) {
                    return {
                        kategori: 'Berkembang',
                        rekomendasi: 'Pendampingan Belajar',
                        badge: 'bg-amber-950 text-amber-400 border-amber-800'
                    };
                } else {
                    return {
                        kategori: 'Perlu Pendampingan',
                        rekomendasi: 'Kelas Matrikulasi Akademik/Keterampilan',
                        badge: 'bg-red-950 text-red-400 border-red-800'
                    };
                }
            }

            // ── Switch Tab Modul Perkembangan Pribadi & Sosial (Pedoman) ─────
            function switchPsiTab(tab) {
                ['rubrik', 'angket', 'wawancara'].forEach(t => {
                    const btn = document.getElementById('psi-tab-btn-' + t);
                    const panel = document.getElementById('psi-panel-' + t);
                    if (btn && panel) {
                        if (t === tab) {
                            btn.className = 'psi-tab-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium transition-all bg-rose-600 text-white shadow-sm';
                            panel.classList.remove('hidden');
                        } else {
                            btn.className = 'psi-tab-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all';
                            panel.classList.add('hidden');
                        }
                    }
                });
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            // ── Collapse / Expand ──────────────────────────────────────────
            function toggleGroup(id) {
                const body    = document.getElementById(id);
                const chevron = document.getElementById(id + '-chevron');
                if (!body) return;
                const isOpen  = !body.classList.contains('hidden');

                if (isOpen) {
                    body.classList.add('hidden');
                    if (chevron) chevron.style.transform = 'rotate(-90deg)';
                } else {
                    body.classList.remove('hidden');
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                }
            }

            // Start all groups and pos collapsed by default
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[id^="group-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });

                document.querySelectorAll('[id*="-pos-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            });

            function expandAllPos() {
                document.querySelectorAll('[id*="-pos-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.remove('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            function collapseAllPos() {
                document.querySelectorAll('[id*="-pos-"]').forEach(el => {
                    if (!el.id.endsWith('-chevron')) {
                        el.classList.add('hidden');
                        const chevron = document.getElementById(el.id + '-chevron');
                        if (chevron) chevron.style.transform = 'rotate(-90deg)';
                    }
                });
            }

            // ── Kalkulator Simulasi Kelompok Keagamaan (Skor Maks 28, 28, 24, 28) ───
            function hitungSimulasiKeagamaan() {
                const p1 = Math.min(28, Math.max(0, parseFloat(document.getElementById('sim-pos-1')?.value || 0)));
                const p2 = Math.min(28, Math.max(0, parseFloat(document.getElementById('sim-pos-2')?.value || 0)));
                const p3 = Math.min(24, Math.max(0, parseFloat(document.getElementById('sim-pos-3')?.value || 0)));
                const p4 = Math.min(28, Math.max(0, parseFloat(document.getElementById('sim-pos-4')?.value || 0)));

                const n1 = (p1 / 28) * 100;
                const n2 = (p2 / 28) * 100;
                const n3 = (p3 / 24) * 100;
                const n4 = (p4 / 28) * 100;

                const rata = (n1 + n2 + n3 + n4) / 4;
                const roundRata = Math.round(rata * 10) / 10;
                const info = getKategoriRekomendasi(roundRata);

                const valEl = document.getElementById('sim-hasil-nilai');
                const katEl = document.getElementById('sim-hasil-kategori');
                const rekEl = document.getElementById('sim-hasil-rekomendasi');

                if (valEl) valEl.textContent = roundRata;
                if (katEl) {
                    katEl.textContent = info.kategori;
                    katEl.className = 'font-semibold text-xs px-1.5 py-0.5 rounded border ' + info.badge;
                }
                if (rekEl) rekEl.textContent = info.rekomendasi;
            }

            // ── Modal Tambah Indikator ─────────────────────────────────────
            function openTambahIndikatorModal() {
                document.getElementById('modal-tambah-indikator')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }
            function closeTambahIndikatorModal() {
                document.getElementById('modal-tambah-indikator')?.classList.add('hidden');
            }

            // ── Modal Edit Indikator ───────────────────────────────────────
            function openEditIndikatorModal(ind) {
                document.getElementById('edit-indikator-id').value = ind.db_id || '';
                document.getElementById('edit-indikator-kode').value = ind.id || '';
                document.getElementById('edit-indikator-aspek').value = ind.aspek || '';
                document.getElementById('edit-indikator-pos').value = ind.pos || ind.aspek || '';
                document.getElementById('edit-indikator-nama').value = ind.nama || '';
                document.getElementById('edit-indikator-bobot').value = ind.bobot ?? 0;
                document.getElementById('edit-indikator-status').value = ind.status || 'Aktif';
                document.getElementById('edit-indikator-deskripsi').value = ind.deskripsi || '';

                const rubrik = ind.rubrik || {};
                document.getElementById('edit-indikator-rubrik-1').value = rubrik[1] || '';
                document.getElementById('edit-indikator-rubrik-2').value = rubrik[2] || '';
                document.getElementById('edit-indikator-rubrik-3').value = rubrik[3] || '';
                document.getElementById('edit-indikator-rubrik-4').value = rubrik[4] || '';

                document.getElementById('modal-edit-indikator')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }
            function closeEditIndikatorModal() {
                document.getElementById('modal-edit-indikator')?.classList.add('hidden');
            }

            // ── Modal Delete Indikator ─────────────────────────────────────
            function confirmDeleteIndikator(ind) {
                document.getElementById('delete-indikator-id').value = ind.db_id || '';
                document.getElementById('delete-indikator-nama').textContent = ind.nama || '-';
                document.getElementById('delete-indikator-kode').textContent = ind.id || '-';

                document.getElementById('modal-delete-indikator')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }
            function closeDeleteIndikatorModal() {
                document.getElementById('modal-delete-indikator')?.classList.add('hidden');
            }

            // Keyboard Escape Listener
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeTambahIndikatorModal();
                    closeEditIndikatorModal();
                    closeDeleteIndikatorModal();
                }
            });
        </script>

    </body>
</html>
