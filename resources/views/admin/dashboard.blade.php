<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard {{ $data['unit_title'] ?? 'SPMB' }} — SAANS Anak Saleh</title>
        
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
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts

        <!-- Styles & Scripts via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-background text-foreground antialiased font-sans flex flex-col justify-between">

        <!-- Top Navigation Bar (Shadcn Navbar Pattern) -->
        <header class="border-b border-border bg-background/80 backdrop-blur sticky top-0 z-40 px-4 lg:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between">
                
                <!-- Left: Logo & Nav Links -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white shadow-sm">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-xs font-semibold tracking-tight text-foreground">SAANS</span>
                    </a>

                    <nav class="hidden sm:flex items-center gap-1 text-xs">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground transition-colors">
                            <i data-lucide="layout-dashboard" class="h-3.5 w-3.5 text-muted-foreground"></i>
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
                        <a href="{{ route('admin.indikator') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
                            <span>Indikator</span>
                        </a>
                    </nav>
                </div>

                <!-- Right: User & Actions -->
                <div class="flex items-center gap-3">
                    <!-- <span class="inline-flex items-center gap-1.5 rounded-md border border-border bg-card px-2.5 py-0.5 text-[11px] font-medium text-foreground">
                        @if($currentJenjang === 'tk')
                            <i data-lucide="baby" class="h-3 w-3 text-emerald-400"></i>
                        @elseif($currentJenjang === 'smp')
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

                    <!-- <a href="{{ route('guru.observasi') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors">
                        <i data-lucide="clipboard-pen-line" class="h-3.5 w-3.5 text-emerald-400"></i>
                        <span>Observasi Guru</span>
                    </a> -->

                    <x-theme-settings />

                    <a href="{{ route('logout') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-transparent px-2.5 text-xs font-medium text-muted-foreground hover:bg-card hover:text-foreground transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Content Shell -->
        <main class="max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 flex-1 space-y-6">
            
            <!-- Page Header Bar with Unit Switcher -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">
                            Dashboard Observasi {{ $data['unit_title'] }}
                        </h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $data['unit_badge'] }}
                        </span>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Pemantauan hasil kesiapan belajar, gaya belajar, dan kuota penerimaan siswa baru unit {{ $data['unit_title'] }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="alert('Mengekspor data observasi {{ $data['unit_title'] }} ke CSV...');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors">
                        <i data-lucide="file-spreadsheet" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span>Export CSV</span>
                    </button>
                    <button type="button" onclick="alert('Menerbitkan SK Pengumuman Penerimaan {{ $data['unit_title'] }}...');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                        <i data-lucide="award" class="h-3.5 w-3.5 text-zinc-900"></i>
                        <span>Terbitkan SK</span>
                    </button>
                </div>
            </div>

            <!-- 4 Metric Cards (Shadcn Card System with Lucide Icons) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="rounded-xl border border-border bg-card p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Pendaftar {{ $data['unit_title'] }}</span>
                        <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-foreground font-mono">{{ $data['total_pendaftar'] }}</div>
                    <p class="text-[11px] text-muted-foreground">{{ $data['pendaftar_sub'] }}</p>
                </div>

                <div class="rounded-xl border border-border bg-card p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Selesai Diobservasi</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-foreground font-mono">{{ $data['selesai_observasi'] }}</div>
                    <p class="text-[11px] text-emerald-400">{{ $data['persen_selesai'] }}</p>
                </div>

                <div class="rounded-xl border border-border bg-card p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Antrean Hari Ini</span>
                        <i data-lucide="clock" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-foreground font-mono">{{ $data['antrean_hari_ini'] }}</div>
                    <p class="text-[11px] text-muted-foreground">{{ $data['antrean_sub'] }}</p>
                </div>

                <div class="rounded-xl border border-border bg-card p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Rata-Rata Kesiapan</span>
                        <i data-lucide="trending-up" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-foreground font-mono">{{ $data['rata_kesiapan'] }}</div>
                    <p class="text-[11px] text-muted-foreground">Kategori: Sangat Baik</p>
                </div>

            </div>

            <!-- Middle Analytics Row: 2 Cards with Lucide Icons -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="rounded-xl border border-border bg-card p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-border">
                        <span class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                            <i data-lucide="bar-chart-2" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>{{ $data['analytics_1_title'] }}</span>
                        </span>
                        <span class="text-[11px] text-muted-foreground font-mono">{{ $data['analytics_1_subtitle'] }}</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        @foreach($data['analytics_1_items'] as $item)
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-foreground/90">
                                    <span>{{ $item['label'] }}</span>
                                    <span class="font-mono text-foreground font-medium">{{ $item['val'] }}</span>
                                </div>
                                <div class="w-full bg-muted rounded-full h-2 overflow-hidden">
                                    <div class="bg-zinc-100 h-2 rounded-full" style="width: {{ $item['bar'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-xl border border-border bg-card p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-border">
                        <span class="text-xs font-semibold text-foreground flex items-center gap-1.5">
                            <i data-lucide="book-marked" class="h-3.5 w-3.5 text-emerald-400"></i>
                            <span>{{ $data['analytics_2_title'] }}</span>
                        </span>
                        <span class="text-[11px] text-muted-foreground font-mono">{{ $data['analytics_2_subtitle'] }}</span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center pt-2">
                        @foreach($data['analytics_2_boxes'] as $box)
                            <div class="rounded-lg border border-border bg-muted/40 p-3 space-y-1">
                                <div class="text-xl font-bold font-mono text-foreground">{{ $box['val'] }}</div>
                                <div class="text-xs font-medium text-foreground/90">{{ $box['title'] }}</div>
                                <div class="text-[10px] text-muted-foreground">{{ $box['sub'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Table Card (Shadcn Data Table) -->
            <div class="rounded-xl border border-border bg-card overflow-hidden space-y-0">
                
                <!-- Table Controls Header -->
                <div class="p-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-foreground">Data Hasil Observasi — {{ $data['unit_title'] }}</h2>
                        <p class="text-xs text-muted-foreground">Daftar calon siswa yang telah diobservasi oleh tim pengamat unit {{ $data['unit_title'] }}</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input 
                                type="text" 
                                placeholder="Cari nama ananda..." 
                                class="h-8 rounded-md border border-border bg-muted/40 pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"
                            />
                            <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-muted-foreground"></i>
                        </div>
                    </div>
                </div>

                <!-- Table Content with Lucide Icons -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-border text-[11px] font-medium text-muted-foreground bg-card">
                                <th class="h-9 px-4">No. Pendaftaran</th>
                                <th class="h-9 px-4">Nama Calon Siswa</th>
                                <th class="h-9 px-4">Jenjang</th>
                                <th class="h-9 px-4">Guru Pengamat</th>
                                <th class="h-9 px-4">Skor Kesiapan</th>
                                <th class="h-9 px-4">Karakter Unggulan</th>
                                <th class="h-9 px-4">Status Rekomendasi</th>
                                <th class="h-9 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60 text-foreground/90">
                            @forelse($data['students'] as $student)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="p-4 font-mono text-muted-foreground">{{ $student['id'] }}</td>
                                    <td class="p-4 font-medium text-foreground">{{ $student['name'] }}</td>
                                    <td class="p-4">{{ $student['class'] }}</td>
                                    <td class="p-4 text-muted-foreground">{{ $student['guru'] }}</td>
                                    <td class="p-4 font-mono font-semibold text-foreground">{{ $student['score'] }}</td>
                                    <td class="p-4 text-foreground/90">{{ $student['char'] }}</td>
                                    <td class="p-4">
                                        @if($student['badge'] === 'emerald')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-800 dark:text-emerald-300">
                                                <i data-lucide="check" class="h-3 w-3 text-emerald-600 dark:text-emerald-400"></i>
                                                <span>{{ $student['status'] }}</span>
                                            </span>
                                        @elseif($student['badge'] === 'blue')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-blue-200 dark:border-blue-800/80 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 text-[11px] font-medium text-blue-800 dark:text-blue-300">
                                                <i data-lucide="clock" class="h-3 w-3 text-blue-600 dark:text-blue-400"></i>
                                                <span>{{ $student['status'] }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md border border-amber-200 dark:border-amber-800/80 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 text-[11px] font-medium text-amber-800 dark:text-amber-300">
                                                <i data-lucide="alert-circle" class="h-3 w-3 text-amber-600 dark:text-amber-400"></i>
                                                <span>{{ $student['status'] }}</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <button type="button" onclick="alert('Membuka Laporan Rapor Ananda {{ $student['name'] }}');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2.5 text-[11px] font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors">
                                            <i data-lucide="file-text" class="h-3 w-3"></i>
                                            <span>Rapor</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-muted-foreground">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i data-lucide="inbox" class="h-8 w-8 text-muted-foreground/40"></i>
                                            <p class="text-sm font-medium">Belum ada data calon siswa untuk jenjang ini</p>
                                            <p class="text-xs text-muted-foreground/70">Silakan tambahkan atau import calon siswa melalui menu Calon Siswa</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </main>

        <!-- Minimal Footer -->
        <footer class="border-t border-border py-4 px-6 text-center text-xs text-muted-foreground">
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Dashboard Rekapitulasi Observasi SPMB
        </footer>

    </body>
</html>
