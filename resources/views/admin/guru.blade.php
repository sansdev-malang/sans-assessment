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
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">Daftar Guru Pengamat</h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Tim pengamat yang bertugas melakukan observasi calon siswa baru unit {{ $user['unit'] ?? '-' }}
                    </p>
                </div>

                <button type="button" onclick="alert('Tambah Guru Pengamat');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                    <i data-lucide="user-plus" class="h-3.5 w-3.5"></i>
                    <span>Tambah Guru</span>
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Guru Pengamat</span>
                        <i data-lucide="users" class="h-4 w-4 text-muted-foreground"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">{{ count($guru) }}</div>
                    <p class="text-[11px] text-muted-foreground">Bertugas di {{ $user['unit'] ?? '-' }}</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Aktif Bertugas</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($guru)->where('status', 'Aktif')->count() }}
                    </div>
                    <p class="text-[11px] text-emerald-400">Siap melakukan observasi</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Observasi</span>
                        <i data-lucide="clipboard-check" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($guru)->sum('jumlah_observasi') }}
                    </div>
                    <p class="text-[11px] text-muted-foreground">Sesi observasi selesai</p>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-border bg-card overflow-hidden">
                <div class="p-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-foreground">Tim Pengamat — {{ $user['unit'] ?? '-' }}</h2>
                        <p class="text-xs text-muted-foreground">Data guru dan psikolog yang ditugaskan sebagai observer SPMB</p>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Cari nama guru..." class="h-8 rounded-md border border-border bg-muted/40 pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-muted-foreground"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-border text-[11px] font-medium text-muted-foreground bg-card">
                                <th class="h-9 px-4">NIP / Kode</th>
                                <th class="h-9 px-4">Nama Guru Pengamat</th>
                                <th class="h-9 px-4">Ruangan Kelompok Observasi</th>
                                <th class="h-9 px-4 text-center">Jml. Observasi</th>
                                <th class="h-9 px-4 text-center">Status</th>
                                <th class="h-9 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60 text-foreground/90">
                            @foreach($guru as $g)
                                <tr class="hover:bg-muted transition-colors">
                                    <td class="p-4 font-mono text-muted-foreground">{{ $g['nip'] }}</td>
                                    <td class="p-4 font-medium text-foreground">{{ $g['name'] }}</td>
                                    <td class="p-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center gap-1.5 font-medium text-foreground">
                                                <i data-lucide="door-open" class="h-3.5 w-3.5 text-emerald-500 shrink-0"></i>
                                                <span>{{ $g['ruang'] }}</span>
                                            </span>
                                            <span class="text-[11px] text-muted-foreground pl-5">
                                                {{ $g['kelompok'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center font-mono font-semibold text-foreground">{{ $g['jumlah_observasi'] }}</td>
                                    <td class="p-4 text-center">
                                        @if($g['status'] === 'Aktif')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-800 dark:text-emerald-300">
                                                <i data-lucide="circle-dot" class="h-2.5 w-2.5 text-emerald-600 dark:text-emerald-400"></i>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md border border-border bg-muted px-2 py-0.5 text-[11px] font-medium text-muted-foreground">
                                                <i data-lucide="circle" class="h-2.5 w-2.5"></i>
                                                {{ $g['status'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <button type="button" onclick="alert('Detail Guru: {{ $g['name'] }}\n{{ $g['ruang'] }}\n{{ $g['kelompok'] }}');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2.5 text-[11px] font-medium text-foreground/90 hover:bg-muted hover:text-foreground transition-colors cursor-pointer">
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
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Manajemen Tim Pengamat SPMB
        </footer>

    </body>
</html>
