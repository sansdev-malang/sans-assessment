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
                        <a href="{{ route('admin.guru', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
                            <i data-lucide="users" class="h-3.5 w-3.5"></i>
                            <span>Guru Pengamat</span>
                        </a>
                        <a href="{{ route('admin.siswa', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex items-center gap-1.5 rounded-md bg-muted border border-border px-2.5 py-1.5 font-medium text-foreground transition-colors">
                            <i data-lucide="notebook-pen" class="h-3.5 w-3.5 text-muted-foreground"></i>
                            <span>Calon Siswa</span>
                        </a>
                        <a href="{{ route('admin.indikator', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-muted-foreground hover:text-foreground hover:bg-card transition-colors">
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
                        <a href="{{ route('admin.siswa', ['jenjang' => 'sd']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'sd' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
                            SD
                        </a>
                        <a href="{{ route('admin.siswa', ['jenjang' => 'smp']) }}" class="px-2.5 py-1 rounded-md font-medium transition-all {{ ($jenjang ?? 'sd') === 'smp' ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:text-foreground' }}">
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

            <!-- Page Header with Jenjang Switcher & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-semibold tracking-tight text-foreground">Daftar Calon Siswa</h1>
                        <span class="rounded-md border border-border bg-card px-2 py-0.5 text-[11px] text-muted-foreground font-mono">
                            {{ $user['unit'] ?? '-' }}
                        </span>
                        @if(($jenjang ?? 'sd') === 'sd')
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-semibold text-emerald-400">
                                3 Sentra
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-indigo-500/30 bg-indigo-500/10 px-2 py-0.5 text-[10px] font-semibold text-indigo-400">
                                4 Kelompok
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Data calon siswa baru yang terdaftar pada unit {{ $user['unit'] ?? '-' }} tahun ajaran 2026/2027
                    </p>
                </div>

                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <!-- Template CSV -->
                    <a href="{{ route('admin.siswa.template', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground hover:bg-muted transition-colors" title="Download Template CSV Calon Siswa">
                        <i data-lucide="download" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span class="hidden md:inline">Template CSV</span>
                    </a>

                    <!-- Import Siswa -->
                    <button type="button" onclick="openImportSiswaModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer" title="Import Siswa via CSV">
                        <i data-lucide="upload" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span>Import Siswa</span>
                    </button>

                    <!-- Export CSV -->
                    <a href="{{ route('admin.siswa.export', ['jenjang' => $jenjang ?? 'sd']) }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-border bg-card px-2.5 text-xs font-medium text-foreground hover:bg-muted transition-colors" title="Export Seluruh Siswa ke CSV">
                        <i data-lucide="file-spreadsheet" class="h-3.5 w-3.5 text-muted-foreground"></i>
                        <span class="hidden md:inline">Export</span>
                    </a>

                    <!-- Tambah Siswa -->
                    <button type="button" onclick="openTambahSiswaModal()" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                        <i data-lucide="user-plus" class="h-3.5 w-3.5"></i>
                        <span>Tambah Siswa</span>
                    </button>
                </div>
            </div>

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Total Calon Siswa</span>
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
                    <p class="text-[11px] text-emerald-400">Tuntas seluruh kelompok/sentra</p>
                </div>
                <div class="rounded-xl border border-border bg-card p-4 space-y-1">
                    <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                        <span>Dalam Proses / Menunggu</span>
                        <i data-lucide="clock" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold font-mono text-foreground">
                        {{ collect($siswa)->where('status_observasi', '!=', 'Selesai')->count() }}
                    </div>
                    <p class="text-[11px] text-amber-400">Belum tuntas semua ruang</p>
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

            <!-- Table Container -->
            <div class="rounded-xl border border-border bg-card overflow-hidden">
                <div class="p-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-foreground">Data Calon Siswa &bull; {{ $user['unit'] ?? '-' }}</h2>
                        <p class="text-xs text-muted-foreground">Rekap pendaftaran, kontak wali, dan status hasil observasi siswa baru</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <!-- Status Filter Tabs -->
                        <div class="inline-flex items-center rounded-lg border border-border bg-muted/40 p-0.5 text-[11px]" id="status-filter-tabs">
                            <button type="button" onclick="filterByStatus('all', this)" class="status-tab px-2.5 py-1 rounded-md font-medium bg-card text-foreground shadow-xs transition-all">Semua</button>
                            <button type="button" onclick="filterByStatus('selesai', this)" class="status-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Selesai</button>
                            <button type="button" onclick="filterByStatus('proses', this)" class="status-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Dalam Proses</button>
                            <button type="button" onclick="filterByStatus('diterima', this)" class="status-tab px-2.5 py-1 rounded-md font-medium text-muted-foreground hover:text-foreground transition-all">Diterima</button>
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <input 
                                type="text" 
                                id="search-siswa" 
                                onkeyup="filterSiswaTable()" 
                                placeholder="Cari nama / no daftar..." 
                                class="h-8 w-44 sm:w-56 rounded-md border border-border bg-muted/40 pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" 
                            />
                            <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-muted-foreground"></i>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs" id="table-siswa">
                        <thead>
                            <tr class="border-b border-border text-[11px] font-medium text-muted-foreground bg-card">
                                <th class="h-9 px-4 w-32">No. Daftar</th>
                                <th class="h-9 px-4">Nama Calon Siswa</th>
                                <th class="h-9 px-4">Usia &amp; Kelas</th>
                                <th class="h-9 px-4">Wali &amp; Kontak</th>
                                <th class="h-9 px-4">Mulai / Posisi</th>
                                <th class="h-9 px-4 text-center">Observasi</th>
                                <th class="h-9 px-4 text-center">Rekomendasi</th>
                                <th class="h-9 px-4 text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60 text-foreground/90">
                            @foreach($siswa as $s)
                                <tr class="siswa-row hover:bg-muted/40 transition-colors"
                                    data-name="{{ strtolower($s['name']) }}"
                                    data-no="{{ strtolower($s['no']) }}"
                                    data-status="{{ strtolower($s['status_observasi']) }}"
                                    data-rekomendasi="{{ strtolower($s['rekomendasi']) }}">
                                    
                                    {{-- No Pendaftaran --}}
                                    <td class="p-3 pl-4 font-mono text-muted-foreground whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded border border-border bg-muted/40 text-[11px] font-semibold">
                                            {{ $s['no'] }}
                                        </span>
                                    </td>

                                    {{-- Nama Siswa & Asal Sekolah --}}
                                    <td class="p-3">
                                        <div class="font-semibold text-foreground text-xs">{{ $s['name'] }}</div>
                                        <div class="text-[11px] text-muted-foreground truncate max-w-xs">{{ $s['asal_sekolah'] ?? '-' }}</div>
                                    </td>

                                    {{-- Usia & Target Kelas --}}
                                    <td class="p-3 whitespace-nowrap">
                                        <div class="font-medium text-foreground">{{ $s['kelas_target'] ?? '-' }}</div>
                                        <div class="text-[11px] text-muted-foreground">{{ $s['usia'] ?? '-' }}</div>
                                    </td>

                                    {{-- Wali & Kontak --}}
                                    <td class="p-3 whitespace-nowrap">
                                        <div class="text-foreground">{{ $s['wali'] ?? '-' }}</div>
                                        <div class="text-[11px] text-muted-foreground font-mono">{{ $s['kontak'] ?? '-' }}</div>
                                    </td>

                                    {{-- Mulai / Posisi --}}
                                    <td class="p-3">
                                        <div class="text-[11px] text-foreground font-medium truncate max-w-[140px]">{{ $s['mulai_dari'] ?? '-' }}</div>
                                        <div class="text-[10px] text-muted-foreground truncate max-w-[140px]">{{ $s['posisi_sekarang'] ?? '-' }}</div>
                                    </td>

                                    {{-- Status Observasi --}}
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if($s['status_observasi'] === 'Selesai')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                <i data-lucide="check" class="h-3 w-3"></i>
                                                Selesai ({{ $s['selesai_count'] ?? 0 }}/{{ $s['target_count'] ?? 3 }})
                                            </span>
                                        @elseif($s['status_observasi'] === 'Sedang Diobservasi')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-indigo-500/30 bg-indigo-500/10 px-2 py-0.5 text-[11px] font-medium text-indigo-400">
                                                <i data-lucide="loader-2" class="h-3 w-3 animate-spin"></i>
                                                Proses ({{ $s['selesai_count'] ?? 0 }}/{{ $s['target_count'] ?? 3 }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-md border border-amber-500/30 bg-amber-500/10 px-2 py-0.5 text-[11px] font-medium text-amber-400">
                                                <i data-lucide="clock" class="h-3 w-3"></i>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Rekomendasi --}}
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if($s['rekomendasi'] === 'Diterima')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-emerald-500/30 bg-emerald-500/10 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                                <i data-lucide="badge-check" class="h-3 w-3"></i>
                                                Diterima
                                            </span>
                                        @elseif($s['rekomendasi'] === 'Pendampingan')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-orange-500/30 bg-orange-500/10 px-2 py-0.5 text-[11px] font-medium text-orange-400">
                                                <i data-lucide="alert-circle" class="h-3 w-3"></i>
                                                Pendampingan
                                            </span>
                                        @elseif($s['rekomendasi'] === 'Dipertimbangkan')
                                            <span class="inline-flex items-center gap-1 rounded-md border border-amber-500/30 bg-amber-500/10 px-2 py-0.5 text-[11px] font-medium text-amber-400">
                                                Dipertimbangkan
                                            </span>
                                        @else
                                            <span class="text-muted-foreground text-[11px]">Dalam Proses</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="p-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button
                                                type="button"
                                                onclick="openDetailSiswaModal({{ json_encode($s) }})"
                                                class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                title="Lihat Detail Siswa"
                                            >
                                                <i data-lucide="eye" class="h-3 w-3 text-muted-foreground"></i>
                                                <span class="hidden md:inline">Detail</span>
                                            </button>
                                            <button
                                                type="button"
                                                onclick="openEditSiswaModal({{ json_encode($s) }})"
                                                class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-card px-2 text-[11px] font-medium text-foreground hover:bg-muted transition-colors cursor-pointer"
                                                title="Edit Data Siswa"
                                            >
                                                <i data-lucide="pencil" class="h-3 w-3 text-muted-foreground"></i>
                                                <span class="hidden md:inline">Edit</span>
                                            </button>
                                            <button
                                                type="button"
                                                onclick="confirmDeleteSiswa({{ json_encode($s) }})"
                                                class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-rose-500/30 bg-rose-500/10 px-2 text-[11px] font-medium text-rose-400 hover:bg-rose-500/20 transition-colors cursor-pointer"
                                                title="Hapus Siswa"
                                            >
                                                <i data-lucide="trash-2" class="h-3 w-3"></i>
                                                <span class="hidden md:inline">Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="no-result-siswa" class="hidden p-8 text-center space-y-2">
                    <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-muted text-muted-foreground mb-1">
                        <i data-lucide="user-x" class="h-5 w-5"></i>
                    </div>
                    <p class="text-xs font-semibold text-foreground">Calon siswa tidak ditemukan</p>
                    <p class="text-[11px] text-muted-foreground">Coba ubah kata kunci pencarian atau ganti filter status observasi.</p>
                </div>
            </div>

        </main>

        <!-- ========================================================================= -->
        <!-- MODAL DETAIL CALON SISWA                                                  -->
        <!-- ========================================================================= -->
        <div id="modal-detail-siswa" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white text-xs">
                            <i data-lucide="user" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Profil Lengkap Calon Siswa</h3>
                            <p class="text-[11px] text-muted-foreground">Detail pendaftaran dan progres observasi terpadu</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDetailSiswaModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <div class="p-5 space-y-4 text-xs">
                    <div class="flex items-start justify-between pb-3 border-b border-border">
                        <div>
                            <h4 id="detail-siswa-nama" class="text-base font-bold text-foreground">-</h4>
                            <p class="text-[11px] text-muted-foreground font-mono mt-0.5">No. Pendaftaran: <span id="detail-siswa-no" class="font-semibold text-foreground">-</span></p>
                        </div>
                        <span id="detail-siswa-rekomendasi" class="px-2.5 py-1 rounded-full text-[11px] font-bold border border-emerald-500/30 bg-emerald-500/10 text-emerald-400">
                            -
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">Kelas Target</div>
                            <div id="detail-siswa-kelas" class="font-medium text-foreground">-</div>
                        </div>
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">Usia</div>
                            <div id="detail-siswa-usia" class="font-medium text-foreground">-</div>
                        </div>
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">Asal Sekolah</div>
                            <div id="detail-siswa-asal" class="font-medium text-foreground">-</div>
                        </div>
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">Wali / Orang Tua</div>
                            <div id="detail-siswa-wali" class="font-medium text-foreground">-</div>
                        </div>
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">No. Kontak / WA</div>
                            <div id="detail-siswa-kontak" class="font-mono text-foreground">-</div>
                        </div>
                        <div class="rounded-lg border border-border bg-muted/30 p-2.5 space-y-0.5">
                            <div class="text-[10px] text-muted-foreground font-semibold uppercase">Nilai Terpadu</div>
                            <div id="detail-siswa-nilai" class="font-mono font-bold text-emerald-400">-</div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-border bg-muted/30 p-3 space-y-1">
                        <div class="text-[10px] text-muted-foreground font-semibold uppercase">Catatan Perkembangan / Karakter</div>
                        <p id="detail-siswa-catatan" class="text-xs text-foreground/90 italic leading-relaxed">-</p>
                    </div>
                </div>

                <div class="p-3 bg-muted/40 border-t border-border flex items-center justify-end">
                    <button type="button" onclick="closeDetailSiswaModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL TAMBAH CALON SISWA                                                  -->
        <!-- ========================================================================= -->
        <div id="modal-tambah-siswa" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-primary text-primary-foreground text-xs">
                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Tambah Calon Siswa Baru</h3>
                            <p class="text-[11px] text-muted-foreground">Pendaftaran peserta baru SPMB {{ strtoupper($jenjang ?? 'sd') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeTambahSiswaModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="jenjang" value="{{ $jenjang ?? 'sd' }}">

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">No. Pendaftaran</label>
                            <input type="text" name="no_pendaftaran" required placeholder="Contoh: SPMB-2026-{{ strtoupper($jenjang ?? 'sd') }}07" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Kelas Dituju</label>
                            <input type="text" name="kelas_target" value="{{ ($jenjang ?? 'sd') === 'sd' ? 'Kelas 1' : 'Kelas 7' }}" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Lengkap Siswa</label>
                        <input type="text" name="nama" required placeholder="Nama lengkap ananda" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Usia</label>
                            <input type="text" name="usia" placeholder="Contoh: 6 Thn 2 Bln" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" placeholder="TK / SD sebelumnya" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Nama Wali / Orang Tua</label>
                            <input type="text" name="wali" placeholder="Bpk. / Ibu..." class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">No. Kontak / WhatsApp</label>
                            <input type="text" name="kontak" placeholder="0812-xxxx-xxxx" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Mulai Dari Sentra / Kelompok</label>
                        <select name="mulai_dari" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2.5 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                            @if(($jenjang ?? 'sd') === 'sd')
                                <option value="Sentra Ubudiyah">Sentra Ubudiyah</option>
                                <option value="Sentra Persiapan">Sentra Persiapan</option>
                                <option value="Sentra Eksplorasi Diri">Sentra Eksplorasi Diri</option>
                            @else
                                <option value="Kelompok Keagamaan">Kelompok Keagamaan</option>
                                <option value="Kelompok Akademik">Kelompok Akademik CBT</option>
                                <option value="Kelompok Potensi Bakat Minat">Kelompok Potensi Bakat Minat</option>
                                <option value="Kelompok Pribadi & Sosial">Kelompok Pribadi &amp; Sosial</option>
                            @endif
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Catatan Khusus (Opsional)</label>
                        <textarea name="catatan_umum" rows="2" placeholder="Catatan minat, kebiasaan, atau pendampingan..." class="w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"></textarea>
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeTambahSiswaModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-primary px-3 text-xs font-semibold text-primary-foreground shadow-xs hover:bg-primary/90 transition-colors cursor-pointer">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                            <span>Simpan Siswa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL EDIT CALON SISWA                                                    -->
        <!-- ========================================================================= -->
        <div id="modal-edit-siswa" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-indigo-600 text-white text-xs">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Edit Calon Siswa</h3>
                            <p class="text-[11px] text-muted-foreground">Perbarui informasi calon siswa</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditSiswaModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.siswa.update') }}" method="POST" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="id" id="edit-siswa-id">

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">No. Pendaftaran</label>
                            <input type="text" name="no_pendaftaran" id="edit-siswa-no" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Kelas Dituju</label>
                            <input type="text" name="kelas_target" id="edit-siswa-kelas" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Nama Lengkap Siswa</label>
                        <input type="text" name="nama" id="edit-siswa-nama" required class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Usia</label>
                            <input type="text" name="usia" id="edit-siswa-usia" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" id="edit-siswa-asal" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Nama Wali</label>
                            <input type="text" name="wali" id="edit-siswa-wali" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Kontak / WA</label>
                            <input type="text" name="kontak" id="edit-siswa-kontak" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs font-mono text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Mulai Dari</label>
                            <input type="text" name="mulai_dari" id="edit-siswa-mulai" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-3 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400" />
                        </div>
                        <div class="space-y-1">
                            <label class="font-medium text-foreground">Status Rekomendasi</label>
                            <select name="status_rekomendasi" id="edit-siswa-rekomendasi-select" class="flex h-8 w-full rounded-md border border-border bg-muted/40 px-2.5 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                                <option value="Dalam Proses">Dalam Proses</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Dipertimbangkan">Dipertimbangkan</option>
                                <option value="Pendampingan">Pendampingan</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="font-medium text-foreground">Catatan Khusus</label>
                        <textarea name="catatan_umum" id="edit-siswa-catatan-input" rows="2" class="w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"></textarea>
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeEditSiswaModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
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
        <!-- MODAL HAPUS CALON SISWA                                                   -->
        <!-- ========================================================================= -->
        <div id="modal-delete-siswa" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-sm rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center gap-2.5 text-rose-400">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <i data-lucide="alert-triangle" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Hapus Calon Siswa</h3>
                        <p class="text-[11px] text-muted-foreground">Konfirmasi penghapusan data calon siswa</p>
                    </div>
                </div>

                <form action="{{ route('admin.siswa.delete') }}" method="POST" class="p-4 space-y-3 text-xs">
                    @csrf
                    <input type="hidden" name="id" id="delete-siswa-id">

                    <p class="text-muted-foreground leading-relaxed">
                        Apakah Anda yakin ingin menghapus data calon siswa <strong class="text-foreground" id="delete-siswa-nama">-</strong> (<span class="font-mono text-muted-foreground" id="delete-siswa-no">-</span>)?
                    </p>
                    <p class="text-[11px] text-rose-400/90 bg-rose-500/10 border border-rose-500/20 rounded-md p-2">
                        Perhatian: Seluruh riwayat asesmen dan skor observasi siswa ini akan ikut terhapus secara permanen.
                    </p>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeDeleteSiswaModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-rose-600 hover:bg-rose-500 text-white px-3 text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                            <span>Ya, Hapus Siswa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL IMPORT CALON SISWA (CSV)                                            -->
        <!-- ========================================================================= -->
        <div id="modal-import-siswa" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-xl border border-border bg-card text-foreground shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="p-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-white text-xs">
                            <i data-lucide="upload" class="h-4 w-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-foreground">Import Calon Siswa (CSV)</h3>
                            <p class="text-[11px] text-muted-foreground">Upload file data calon siswa massal</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeImportSiswaModal()" class="h-7 w-7 rounded-md border border-border bg-muted text-muted-foreground hover:text-foreground flex items-center justify-center text-xs cursor-pointer font-bold">&times;</button>
                </div>

                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="jenjang" value="{{ $jenjang ?? 'sd' }}">

                    <div class="rounded-lg border border-border bg-muted/30 p-3 space-y-1.5 text-[11px]">
                        <div class="font-semibold text-foreground flex items-center justify-between">
                            <span>Petunjuk Format CSV:</span>
                            <a href="{{ route('admin.siswa.template', ['jenjang' => $jenjang ?? 'sd']) }}" class="text-primary hover:underline flex items-center gap-1 font-bold">
                                <i data-lucide="download" class="h-3 w-3"></i>
                                <span>Unduh Template CSV</span>
                            </a>
                        </div>
                        <p class="text-muted-foreground leading-relaxed">
                            Pastikan kolom header memuat: <code class="font-mono text-foreground font-semibold">no_pendaftaran, nama, jenjang, usia, kelas_target, asal_sekolah, wali, kontak, mulai_dari, status_rekomendasi, catatan_umum</code>.
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-medium text-foreground">Pilih File CSV:</label>
                        <input type="file" name="file_siswa" accept=".csv,text/csv,text/plain" required class="flex w-full rounded-md border border-border bg-muted/40 p-2 text-xs text-foreground file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-primary-foreground hover:file:bg-primary/90 cursor-pointer" />
                    </div>

                    <div class="pt-2 border-t border-border flex items-center justify-end gap-2">
                        <button type="button" onclick="closeImportSiswaModal()" class="inline-flex h-8 items-center justify-center rounded-md border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted transition-colors cursor-pointer">
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
            SAANS Assessment &bull; Sekolah Alam Anak Saleh &bull; Data Calon Siswa Baru SPMB 2026/2027
        </footer>

        <!-- Client Scripts -->
        <script>
            let currentStatusFilter = 'all';

            function filterByStatus(statusKey, btn) {
                currentStatusFilter = statusKey;

                document.querySelectorAll('.status-tab').forEach(b => {
                    b.classList.remove('bg-card', 'text-foreground', 'shadow-xs');
                    b.classList.add('text-muted-foreground');
                });
                btn.classList.add('bg-card', 'text-foreground', 'shadow-xs');
                btn.classList.remove('text-muted-foreground');

                filterSiswaTable();
            }

            function filterSiswaTable() {
                const searchVal = (document.getElementById('search-siswa')?.value || '').toLowerCase().trim();
                const rows = document.querySelectorAll('.siswa-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowName = row.getAttribute('data-name') || '';
                    const rowNo = row.getAttribute('data-no') || '';
                    const rowStatus = row.getAttribute('data-status') || '';
                    const rowRekom = row.getAttribute('data-rekomendasi') || '';

                    let matchesStatus = true;
                    if (currentStatusFilter === 'selesai') {
                        matchesStatus = (rowStatus === 'selesai');
                    } else if (currentStatusFilter === 'proses') {
                        matchesStatus = (rowStatus !== 'selesai');
                    } else if (currentStatusFilter === 'diterima') {
                        matchesStatus = (rowRekom === 'diterima');
                    }

                    const matchesSearch = !searchVal || rowName.includes(searchVal) || rowNo.includes(searchVal);

                    if (matchesStatus && matchesSearch) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });

                const noResult = document.getElementById('no-result-siswa');
                if (noResult) {
                    if (visibleCount === 0) {
                        noResult.classList.remove('hidden');
                    } else {
                        noResult.classList.add('hidden');
                    }
                }
            }

            // Detail Siswa
            function openDetailSiswaModal(siswa) {
                document.getElementById('detail-siswa-nama').textContent = siswa.name || '-';
                document.getElementById('detail-siswa-no').textContent = siswa.no || '-';
                document.getElementById('detail-siswa-kelas').textContent = siswa.kelas_target || '-';
                document.getElementById('detail-siswa-usia').textContent = siswa.usia || '-';
                document.getElementById('detail-siswa-asal').textContent = siswa.asal_sekolah || '-';
                document.getElementById('detail-siswa-wali').textContent = siswa.wali || '-';
                document.getElementById('detail-siswa-kontak').textContent = siswa.kontak || '-';
                document.getElementById('detail-siswa-nilai').textContent = siswa.nilai_akhir_terpadu ? (siswa.nilai_akhir_terpadu + '%') : 'Dalam Proses';
                document.getElementById('detail-siswa-catatan').textContent = siswa.catatan_umum || 'Belum ada catatan khusus.';
                
                const rekomEl = document.getElementById('detail-siswa-rekomendasi');
                if (rekomEl) {
                    rekomEl.textContent = siswa.rekomendasi || 'Dalam Proses';
                }

                document.getElementById('modal-detail-siswa')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeDetailSiswaModal() {
                document.getElementById('modal-detail-siswa')?.classList.add('hidden');
            }

            // Tambah Siswa
            function openTambahSiswaModal() {
                document.getElementById('modal-tambah-siswa')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeTambahSiswaModal() {
                document.getElementById('modal-tambah-siswa')?.classList.add('hidden');
            }

            // Edit Siswa
            function openEditSiswaModal(siswa) {
                document.getElementById('edit-siswa-id').value = siswa.id || '';
                document.getElementById('edit-siswa-no').value = siswa.no || '';
                document.getElementById('edit-siswa-nama').value = siswa.name || '';
                document.getElementById('edit-siswa-kelas').value = siswa.kelas_target || '';
                document.getElementById('edit-siswa-usia').value = siswa.usia || '';
                document.getElementById('edit-siswa-asal').value = siswa.asal_sekolah || '';
                document.getElementById('edit-siswa-wali').value = siswa.wali || '';
                document.getElementById('edit-siswa-kontak').value = siswa.kontak || '';
                document.getElementById('edit-siswa-mulai').value = siswa.mulai_dari || '';
                document.getElementById('edit-siswa-rekomendasi-select').value = siswa.rekomendasi || 'Dalam Proses';
                document.getElementById('edit-siswa-catatan-input').value = siswa.catatan_umum || '';

                document.getElementById('modal-edit-siswa')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeEditSiswaModal() {
                document.getElementById('modal-edit-siswa')?.classList.add('hidden');
            }

            // Hapus Siswa
            function confirmDeleteSiswa(siswa) {
                document.getElementById('delete-siswa-id').value = siswa.id || '';
                document.getElementById('delete-siswa-nama').textContent = siswa.name || '-';
                document.getElementById('delete-siswa-no').textContent = siswa.no || '-';

                document.getElementById('modal-delete-siswa')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeDeleteSiswaModal() {
                document.getElementById('modal-delete-siswa')?.classList.add('hidden');
            }

            // Import Siswa
            function openImportSiswaModal() {
                document.getElementById('modal-import-siswa')?.classList.remove('hidden');
                if (window.refreshLucideIcons) window.refreshLucideIcons();
            }

            function closeImportSiswaModal() {
                document.getElementById('modal-import-siswa')?.classList.add('hidden');
            }

            // Keyboard Escape Listener
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeDetailSiswaModal();
                    closeTambahSiswaModal();
                    closeEditSiswaModal();
                    closeDeleteSiswaModal();
                    closeImportSiswaModal();
                }
            });
        </script>

    </body>
</html>