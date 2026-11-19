<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Calon Siswa — {{ $user['unit'] ?? 'SAANS' }}</title>
        
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
                        <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground transition-colors">
                            <i data-lucide="notebook-pen" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>Calon Siswa</span>
                        </a>
                        <a href="{{ route('admin.indikator') }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="list-checks" class="h-3.5 w-3.5"></i>
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">Daftar Calon Siswa</h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Data seluruh calon siswa yang mendaftar ke unit {{ $user['unit'] ?? '-' }} tahun ajaran 2026/2027
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="alert('Export data calon siswa ke CSV');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors">
                        <i data-lucide="file-spreadsheet" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span>Export CSV</span>
                    </button>
                    <button type="button" onclick="alert('Tambah calon siswa baru');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                        <i data-lucide="user-round-plus" class="h-3.5 w-3.5"></i>
                        <span>Tambah Siswa</span>
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Pendaftar</span>
                        <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">{{ count($siswa) }}</div>
                    <p class="text-[11px] text-muted-foreground">{{ $user['unit'] ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Selesai Observasi</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($siswa)->where('status_observasi', 'Selesai')->count() }}
                    </div>
                    <p class="text-[11px] text-emerald-400">Sudah diobservasi</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Menunggu</span>
                        <i data-lucide="clock" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($siswa)->where('status_observasi', 'Menunggu')->count() }}
                    </div>
                    <p class="text-[11px] text-amber-400">Belum diobservasi</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Direkomendasikan</span>
                        <i data-lucide="award" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($siswa)->where('rekomendasi', 'Diterima')->count() }}
                    </div>
                    <p class="text-[11px] text-indigo-400">Status: Diterima</p>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 text-xs">
                <button type="button" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground shadow-sm transition-colors">
                    Semua
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card border border-transparent transition-colors">
                    <i data-lucide="check-circle-2" class="h-3.5 w-3.5 text-emerald-400"></i>
                    Selesai Observasi
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card border border-transparent transition-colors">
                    <i data-lucide="clock" class="h-3.5 w-3.5 text-amber-400"></i>
                    Menunggu
                </button>
                <button type="button" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card border border-transparent transition-colors">
                    <i data-lucide="alert-circle" class="h-3.5 w-3.5 text-orange-400"></i>
                    Pendampingan
                </button>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-border bg-card overflow-hidden">
                <div class="p-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-foreground">Data Calon Siswa — {{ $user['unit'] ?? '-' }}</h2>
                        <p class="text-xs text-muted-foreground">Rekap pendaftaran dan status observasi seluruh calon siswa baru</p>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Cari nama siswa..." class="h-8 rounded-md border border-border bg-muted/40 pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-muted-foreground"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-border text-[11px] font-medium text-muted-foreground bg-card">
                                <th class="h-9 px-4">No. Daftar</th>
                                <th class="h-9 px-4">Nama Calon Siswa</th>
                                <th class="h-9 px-4">Usia</th>
                                <th class="h-9 px-4">Kelas Dituju</th>
                                <th class="h-9 px-4">Wali / Orang Tua</th>
                                <th class="h-9 px-4">Kontak</th>
                                <th class="h-9 px-4 text-center">Status Observasi</th>
                                <th class="h-9 px-4 text-center">Rekomendasi</th>
                                <th class="h-9 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60 text-foreground/90">
                            @foreach($siswa as $s)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="p-4 font-mono text-muted-foreground">{{ $s['no'] }}</td>
                                    <td class="p-4 font-medium text-foreground">{{ $s['name'] }}</td>
                                    <td class="p-4 text-muted-foreground">{{ $s['usia'] }}</td>
                                    <td class="p-4">{{ $s['kelas_target'] }}</td>
                                    <td class="p-4 text-muted-foreground">{{ $s['wali'] }}</td>
                                    <td class="p-4 font-mono text-muted-foreground">{{ $s['kontak'] }}</td>
                                    <td class="p-4 text-center">
                                        @if($s['status_observasi'] === 'Selesai')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-800 dark:text-emerald-300">
                                                <i data-lucide="check" class="h-3 w-3 text-emerald-600 dark:text-emerald-400"></i>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md border border-amber-200 dark:border-amber-800/80 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 text-[11px] font-medium text-amber-800 dark:text-amber-300">
                                                <i data-lucide="clock" class="h-3 w-3 text-amber-600 dark:text-amber-400"></i>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($s['rekomendasi'] === 'Diterima')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-800 dark:text-emerald-300">
                                                <i data-lucide="badge-check" class="h-3 w-3 text-emerald-600 dark:text-emerald-400"></i>
                                                Diterima
                                            </span>
                                        @elseif($s['rekomendasi'] === 'Pendampingan')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-orange-200 dark:border-orange-800/80 bg-orange-50 dark:bg-orange-950/60 px-2 py-0.5 text-[11px] font-medium text-orange-800 dark:text-orange-300">
                                                <i data-lucide="alert-circle" class="h-3 w-3 text-orange-600 dark:text-orange-400"></i>
                                                Pendampingan
                                            </span>
                                        @else
                                            <span class="text-muted-foreground">—</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <button type="button" onclick="alert('Detail Calon Siswa: {{ $s['name'] }}');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2.5 text-[11px] font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors">
                                            <i data-lucide="eye" class="h-3 w-3"></i>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

        <footer class="border-t border-border py-4 px-6 text-center text-xs text-muted-foreground">
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Data Calon Siswa Baru SPMB 2026/2027
        </footer>

    </body>
</html>
