<!DOCTYPE html>
<html lang="id" class="dark h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard Observasi SPMB — SAANS Anak Saleh</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts

        <!-- Styles & Scripts via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full bg-zinc-950 text-zinc-100 antialiased font-sans flex flex-col justify-between">

        <!-- Top Navigation Bar (Shadcn Navbar Pattern) -->
        <header class="border-b border-zinc-800/80 bg-zinc-950/80 backdrop-blur sticky top-0 z-40 px-4 lg:px-6">
            <div class="max-w-7xl mx-auto flex h-14 items-center justify-between">
                
                <!-- Left: Logo & Breadcrumbs -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-md border border-zinc-700 bg-zinc-800 text-zinc-100">
                            <i data-lucide="graduation-cap" class="h-3.5 w-3.5"></i>
                        </div>
                        <span class="text-xs font-semibold tracking-tight text-zinc-100">SAANS</span>
                    </a>

                    <div class="hidden sm:flex items-center gap-2 text-xs text-zinc-500">
                        <span>/</span>
                        <span>SPMB 2026</span>
                        <span>/</span>
                        <span class="text-zinc-200 font-medium">Dashboard Admin SPMB</span>
                    </div>
                </div>

                <!-- Right: User & Actions -->
                <div class="flex items-center gap-3">
                    <span class="hidden md:inline-flex items-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 py-0.5 text-[11px] font-medium text-zinc-300">
                        <i data-lucide="shield" class="h-3 w-3 text-indigo-400"></i>
                        <span>{{ $user['role_label'] ?? 'Kepala Panitia SPMB' }}</span>
                    </span>

                    <div class="flex items-center gap-2 pl-2 border-l border-zinc-800">
                        <div class="h-7 w-7 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-200">
                            AF
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-zinc-200">{{ $user['name'] ?? 'Ustadz H. Ahmad Fauzi, M.Pd.' }}</span>
                    </div>

                    <a href="{{ route('guru.observasi') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-xs font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                        <i data-lucide="clipboard-pen-line" class="h-3.5 w-3.5 text-emerald-400"></i>
                        <span>Supervisi Guru</span>
                    </a>

                    <a href="{{ route('logout') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-transparent px-2.5 text-xs font-medium text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100 transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Content Shell -->
        <main class="max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 flex-1 space-y-6">
            
            <!-- Page Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-800/80">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-zinc-100 flex items-center gap-2">
                        <i data-lucide="layout-dashboard" class="h-5 w-5 text-indigo-400"></i>
                        <span>Dashboard Rekapitulasi Observasi SPMB</span>
                    </h1>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        Pemantauan hasil kesiapan belajar, gaya belajar, dan kuota calon siswa baru TK, SD, dan SMP Anak Saleh
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="alert('Mengekspor data observasi ke format CSV...');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900/60 px-3 text-xs font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                        <i data-lucide="file-spreadsheet" class="h-3.5 w-3.5 text-zinc-400"></i>
                        <span>Export CSV</span>
                    </button>
                    <button type="button" onclick="alert('Menerbitkan SK Pengumuman Penerimaan...');" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-3 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                        <i data-lucide="award" class="h-3.5 w-3.5 text-zinc-900"></i>
                        <span>Terbitkan SK Penerimaan</span>
                    </button>
                </div>
            </div>

            <!-- 4 Metric Cards (Shadcn Card System with Lucide Icons) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Total Pendaftar SPMB</span>
                        <i data-lucide="users" class="h-4 w-4 text-zinc-500"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-zinc-100 font-mono">208</div>
                    <p class="text-[11px] text-zinc-500">TK: 48 &bull; SD: 96 &bull; SMP: 64</p>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Selesai Diobservasi</span>
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-zinc-100 font-mono">162</div>
                    <p class="text-[11px] text-emerald-400">77.8% telah terekapitulasi</p>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Antrean Hari Ini</span>
                        <i data-lucide="clock" class="h-4 w-4 text-amber-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-zinc-100 font-mono">18</div>
                    <p class="text-[11px] text-zinc-500">4 ruang kelas trial aktif</p>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium text-zinc-400">
                        <span>Rata-Rata Kesiapan</span>
                        <i data-lucide="trending-up" class="h-4 w-4 text-indigo-400"></i>
                    </div>
                    <div class="text-2xl font-bold tracking-tight text-zinc-100 font-mono">94.2%</div>
                    <p class="text-[11px] text-zinc-400">Kategori: Sangat Baik</p>
                </div>

            </div>

            <!-- Middle Analytics Row: 2 Cards with Lucide Icons -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                        <span class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                            <i data-lucide="bar-chart-2" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>Distribusi Gaya Belajar Siswa Baru (VAK)</span>
                        </span>
                        <span class="text-[11px] text-zinc-500 font-mono">162 Siswa</span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-zinc-300">
                                <span>Visual-Spasial (Belajar melalui gambar &amp; warna)</span>
                                <span class="font-mono text-zinc-100 font-medium">42% (68)</span>
                            </div>
                            <div class="w-full bg-zinc-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-zinc-100 h-2 rounded-full w-[42%]"></div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between text-zinc-300">
                                <span>Kinestetik (Belajar melalui aktivitas fisik &amp; motorik)</span>
                                <span class="font-mono text-zinc-100 font-medium">33% (53)</span>
                            </div>
                            <div class="w-full bg-zinc-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-zinc-400 h-2 rounded-full w-[33%]"></div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between text-zinc-300">
                                <span>Auditori (Belajar melalui dongeng &amp; nada)</span>
                                <span class="font-mono text-zinc-100 font-medium">25% (41)</span>
                            </div>
                            <div class="w-full bg-zinc-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-zinc-600 h-2 rounded-full w-[25%]"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                        <span class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                            <i data-lucide="book-marked" class="h-3.5 w-3.5 text-emerald-400"></i>
                            <span>Penempatan Halaqah Al-Qur'an (Tilawati)</span>
                        </span>
                        <span class="text-[11px] text-zinc-500 font-mono">Baseline Awal</span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center pt-2">
                        <div class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 space-y-1">
                            <div class="text-xl font-bold font-mono text-zinc-100">22%</div>
                            <div class="text-xs font-medium text-zinc-300">Jilid 1</div>
                            <div class="text-[10px] text-zinc-500">Huruf Hijaiyah</div>
                        </div>

                        <div class="rounded-lg border border-zinc-700 bg-zinc-800/60 p-3 space-y-1">
                            <div class="text-xl font-bold font-mono text-emerald-400">54%</div>
                            <div class="text-xs font-medium text-zinc-100">Jilid 2</div>
                            <div class="text-[10px] text-zinc-400">Kelancaran Mad</div>
                        </div>

                        <div class="rounded-lg border border-zinc-800 bg-zinc-950 p-3 space-y-1">
                            <div class="text-xl font-bold font-mono text-zinc-100">24%</div>
                            <div class="text-xs font-medium text-zinc-300">Jilid 3+</div>
                            <div class="text-[10px] text-zinc-500">Tahfidz Juz 30</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Table Card (Shadcn Data Table) -->
            <div class="rounded-xl border border-zinc-800 bg-zinc-900/40 overflow-hidden space-y-0">
                
                <!-- Table Controls Header -->
                <div class="p-4 border-b border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-zinc-100">Data Hasil Observasi Calon Siswa Baru</h2>
                        <p class="text-xs text-zinc-500">Rekapitulasi calon siswa yang telah diobservasi oleh tim guru pengamat</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input 
                                type="text" 
                                placeholder="Cari nama ananda..." 
                                class="h-8 rounded-md border border-zinc-800 bg-zinc-950 pl-8 pr-3 text-xs text-zinc-200 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400"
                            />
                            <i data-lucide="search" class="h-3.5 w-3.5 absolute left-2.5 top-2.5 text-zinc-500"></i>
                        </div>
                        <select class="h-8 rounded-md border border-zinc-800 bg-zinc-950 px-2.5 text-xs text-zinc-300 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400">
                            <option value="all">Semua Jenjang</option>
                            <option value="tk">KB &amp; TK</option>
                            <option value="sd">SD</option>
                            <option value="smp">SMP</option>
                        </select>
                    </div>
                </div>

                <!-- Table Content with Lucide Icons -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-800 text-[11px] font-medium text-zinc-400 bg-zinc-900/60">
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
                        <tbody class="divide-y divide-zinc-800/60 text-zinc-300">
                            
                            <tr class="hover:bg-zinc-800/40 transition-colors">
                                <td class="p-4 font-mono text-zinc-400">SPMB-2026-084</td>
                                <td class="p-4 font-medium text-zinc-100">Muhammad Fatih Al-Ayyubi</td>
                                <td class="p-4">SD Kelas 1</td>
                                <td class="p-4 text-zinc-400">Ustadzah Fatimah, S.Pd.</td>
                                <td class="p-4 font-mono font-semibold text-zinc-100">95%</td>
                                <td class="p-4 text-zinc-300">Mandiri &amp; Spasial Kreatif</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                        <i data-lucide="check" class="h-3 w-3"></i>
                                        <span>Siap Belajar</span>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="alert('Membuka Laporan Rapor Ananda Fatih Al-Ayyubi');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                                        <i data-lucide="file-text" class="h-3 w-3"></i>
                                        <span>Rapor</span>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-zinc-800/40 transition-colors">
                                <td class="p-4 font-mono text-zinc-400">SPMB-2026-085</td>
                                <td class="p-4 font-medium text-zinc-100">Maryam Qonita Zahra</td>
                                <td class="p-4">TK A</td>
                                <td class="p-4 text-zinc-400">Ustadzah Nurul, S.Psi.</td>
                                <td class="p-4 font-mono font-semibold text-zinc-100">96%</td>
                                <td class="p-4 text-zinc-300">Ceria &amp; Mudah Bergaul</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                        <i data-lucide="check" class="h-3 w-3"></i>
                                        <span>Siap Belajar</span>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="alert('Membuka Laporan Rapor Ananda Maryam Qonita');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                                        <i data-lucide="file-text" class="h-3 w-3"></i>
                                        <span>Rapor</span>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-zinc-800/40 transition-colors">
                                <td class="p-4 font-mono text-zinc-400">SPMB-2026-086</td>
                                <td class="p-4 font-medium text-zinc-100">Bilal Ramadhan Putra</td>
                                <td class="p-4">SMP Kelas 7</td>
                                <td class="p-4 text-zinc-400">Ustadz Hasan, M.Pd.</td>
                                <td class="p-4 font-mono font-semibold text-zinc-100">91%</td>
                                <td class="p-4 text-zinc-300">Leadership &amp; Tahfidz 2 Juz</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 rounded-md border border-emerald-800/80 bg-emerald-950/60 px-2 py-0.5 text-[11px] font-medium text-emerald-400">
                                        <i data-lucide="check" class="h-3 w-3"></i>
                                        <span>Siap Belajar</span>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="alert('Membuka Laporan Rapor Ananda Bilal Ramadhan');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                                        <i data-lucide="file-text" class="h-3 w-3"></i>
                                        <span>Rapor</span>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-zinc-800/40 transition-colors">
                                <td class="p-4 font-mono text-zinc-400">SPMB-2026-087</td>
                                <td class="p-4 font-medium text-zinc-100">Kenzo Raditya Pratama</td>
                                <td class="p-4">Playgroup</td>
                                <td class="p-4 text-zinc-400">Ustadzah Nurul, S.Psi.</td>
                                <td class="p-4 font-mono font-semibold text-zinc-100">82%</td>
                                <td class="p-4 text-zinc-300">Aktif Kinestetik (Adaptasi Toilet)</td>
                                <td class="p-4">
                                    <span class="inline-flex items-center gap-1 rounded-md border border-amber-800/80 bg-amber-950/60 px-2 py-0.5 text-[11px] font-medium text-amber-400">
                                        <i data-lucide="alert-circle" class="h-3 w-3"></i>
                                        <span>Pendampingan Khusus</span>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="alert('Membuka Catatan Pendampingan Khusus Ananda Kenzo');" class="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-[11px] font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                                        <i data-lucide="file-text" class="h-3 w-3"></i>
                                        <span>Rapor</span>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>

        </main>

        <!-- Minimal Footer -->
        <footer class="border-t border-zinc-800/80 py-4 px-6 text-center text-xs text-zinc-500">
            SAANS Assessment &bull; Perguruan Anak Saleh &bull; Dashboard Rekapitulasi Observasi SPMB
        </footer>

    </body>
</html>
