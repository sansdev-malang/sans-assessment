<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Observasi Siswa Baru — SAANS Assessment Anak Saleh</title>
        
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
                        <span class="text-zinc-200 font-medium">Halaman Observasi Guru</span>
                    </div>
                </div>

                <!-- Right: User & Actions -->
                <div class="flex items-center gap-3">
                    <span class="hidden md:inline-flex items-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 py-0.5 text-[11px] font-medium text-zinc-300">
                        <i data-lucide="user-check" class="h-3 w-3 text-emerald-400"></i>
                        <span>{{ $user['role_label'] ?? 'Tim Pengamat & Psikolog' }}</span>
                    </span>

                    <div class="flex items-center gap-2 pl-2 border-l border-zinc-800">
                        <div class="h-7 w-7 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-200">
                            UF
                        </div>
                        <span class="hidden sm:inline text-xs font-medium text-zinc-200">{{ $user['name'] ?? 'Ustadzah Fatimah, S.Pd.' }}</span>
                    </div>

                    <a href="{{ route('admin.dashboard') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 text-xs font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                        <i data-lucide="layout-dashboard" class="h-3.5 w-3.5"></i>
                        <span>Dashboard Admin</span>
                    </a>

                    <x-theme-settings />

                    <a href="{{ route('logout') }}" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-transparent px-2.5 text-xs font-medium text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100 transition-colors">
                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                        <span>Keluar</span>
                    </a>
                </div>

            </div>
        </header>

        <!-- Main Content Shell -->
        <main class="max-w-7xl w-full mx-auto px-4 lg:px-6 py-6 flex-1 space-y-6">
            
            <!-- Page Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-800/80">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-zinc-100 flex items-center gap-2">
                        <i data-lucide="clipboard-list" class="h-5 w-5 text-emerald-400"></i>
                        <span>Lembar Kerja Observasi Kesiapan Siswa Baru</span>
                    </h1>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        Ruang Observasi Melati &bull; Sesi Gelombang 1 TA 2026/2027 &bull; Pendekatan Play-Based Tanpa Beban
                    </p>
                </div>

                <!-- Shadcn Segmented Jenjang Filter -->
                <div class="inline-flex h-9 items-center justify-center rounded-lg border border-zinc-800 bg-zinc-900/70 p-1 text-xs">
                    <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded-md bg-zinc-800 px-3 py-1 font-medium text-zinc-100 shadow-sm">
                        <i data-lucide="baby" class="h-3.5 w-3.5"></i>
                        <span>KB &amp; TK</span>
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded-md px-3 py-1 font-medium text-zinc-400 hover:text-zinc-200">
                        <i data-lucide="backpack" class="h-3.5 w-3.5"></i>
                        <span>SD Anak Saleh</span>
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-1.5 rounded-md px-3 py-1 font-medium text-zinc-400 hover:text-zinc-200">
                        <i data-lucide="school" class="h-3.5 w-3.5"></i>
                        <span>SMP Anak Saleh</span>
                    </button>
                </div>
            </div>

            <!-- Workspace 3-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Sidebar: Antrean Siswa (3 cols) -->
                <div class="lg:col-span-3 rounded-xl border border-zinc-800 bg-zinc-900/40 p-4 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-zinc-800">
                        <span class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5">
                            <i data-lucide="users" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>Daftar Siswa Hari Ini</span>
                        </span>
                        <span class="rounded bg-zinc-800 px-1.5 py-0.5 font-mono text-[10px] text-zinc-400">4 Siswa</span>
                    </div>

                    <div class="space-y-1.5">
                        
                        <!-- Siswa 1: Active -->
                        <div class="p-3 rounded-lg border border-zinc-700 bg-zinc-800/80 text-left cursor-pointer transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-zinc-100">Fatih Al-Ayyubi</div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Usia 6 th &bull; Calon SD Kelas 1</div>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-950/80 border border-emerald-800/80 px-1.5 py-0.5 text-[9px] font-medium text-emerald-400">
                                    <span class="h-1 w-1 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Aktif</span>
                                </span>
                            </div>
                            <div class="mt-2 text-[10px] text-zinc-400 font-mono flex items-center gap-1">
                                <i data-lucide="clock" class="h-3 w-3"></i>
                                <span>Pukul 08:30 WIB</span>
                            </div>
                        </div>

                        <!-- Siswa 2 -->
                        <div class="p-3 rounded-lg border border-zinc-800/60 bg-zinc-900/30 hover:bg-zinc-800/40 text-left cursor-pointer transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-300">Aisyah Humaira</div>
                                    <div class="text-[11px] text-zinc-500 mt-0.5">Usia 4 th 5 bln &bull; Calon TK A</div>
                                </div>
                                <span class="text-[10px] font-mono text-zinc-500">09:15</span>
                            </div>
                        </div>

                        <!-- Siswa 3 -->
                        <div class="p-3 rounded-lg border border-zinc-800/60 bg-zinc-900/30 hover:bg-zinc-800/40 text-left cursor-pointer transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-300">Bilal Ramadhan</div>
                                    <div class="text-[11px] text-zinc-500 mt-0.5">Usia 12 th &bull; Calon SMP</div>
                                </div>
                                <span class="text-[10px] font-mono text-zinc-500">10:00</span>
                            </div>
                        </div>

                        <!-- Siswa 4 -->
                        <div class="p-3 rounded-lg border border-zinc-800/60 bg-zinc-900/30 opacity-60 text-left">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-300">Maryam Qonita</div>
                                    <div class="text-[11px] text-zinc-500 mt-0.5">Calon TK A &bull; Selesai</div>
                                </div>
                                <span class="text-[10px] font-mono text-emerald-400 font-semibold">96%</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Center Main: Lembar Rubrik Observasi (6 cols) -->
                <div class="lg:col-span-6 rounded-xl border border-zinc-800 bg-zinc-900/40 p-6 space-y-6">
                    
                    <!-- Student Identity Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-800">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-semibold text-zinc-100">Muhammad Fatih Al-Ayyubi</h2>
                                <span class="font-mono text-[11px] text-zinc-400 border border-zinc-800 rounded px-1.5 py-0.5 bg-zinc-900">
                                    SPMB-2026-084
                                </span>
                            </div>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Jenjang Dituju: <strong class="text-zinc-200">SD Anak Saleh (Kelas 1)</strong> &bull; Asal: TK B Anak Saleh
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900 px-2.5 py-1 text-xs text-zinc-300">
                            <i data-lucide="timer" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>25 Menit</span>
                        </span>
                    </div>

                    <!-- Evaluation Rubrics with Lucide Icons -->
                    <div class="space-y-4">
                        
                        <!-- Item 1: Sosio-Emosional -->
                        <div class="space-y-2 rounded-lg border border-zinc-800/80 bg-zinc-900/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="heart-handshake" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        <span>1. Kematangan Sosio-Emosional &amp; Kemandirian</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Pemisahan dari orang tua, interaksi ramah, toleransi berbagi mainan.</div>
                                </div>
                                <span class="text-xs font-semibold text-emerald-400">Skor: 4</span>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 pt-1">
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">1: Kurang</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">2: Mulai</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">3: Sesuai</button>
                                <button type="button" class="h-8 rounded-md bg-zinc-100 text-[11px] font-semibold text-zinc-900 shadow-sm">4: Sangat Baik</button>
                            </div>
                        </div>

                        <!-- Item 2: Motorik -->
                        <div class="space-y-2 rounded-lg border border-zinc-800/80 bg-zinc-900/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="activity" class="h-3.5 w-3.5 text-indigo-400"></i>
                                        <span>2. Sensori Motorik &amp; Koordinasi Fisik</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Memegang krayon, menggunting kertas, melompat seimbang.</div>
                                </div>
                                <span class="text-xs font-semibold text-emerald-400">Skor: 4</span>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 pt-1">
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">1: Kurang</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">2: Mulai</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">3: Sesuai</button>
                                <button type="button" class="h-8 rounded-md bg-zinc-100 text-[11px] font-semibold text-zinc-900 shadow-sm">4: Sangat Baik</button>
                            </div>
                        </div>

                        <!-- Item 3: Kesiapan Belajar -->
                        <div class="space-y-2 rounded-lg border border-zinc-800/80 bg-zinc-900/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="brain" class="h-3.5 w-3.5 text-purple-400"></i>
                                        <span>3. Kesiapan Belajar &amp; Rentang Fokus</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Menyimak dongeng, daya tahan fokus &gt; 15 menit, rasa ingin tahu.</div>
                                </div>
                                <span class="text-xs font-semibold text-zinc-200">Skor: 3</span>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 pt-1">
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">1: Kurang</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">2: Mulai</button>
                                <button type="button" class="h-8 rounded-md bg-zinc-100 text-[11px] font-semibold text-zinc-900 shadow-sm">3: Sesuai</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">4: Sangat Baik</button>
                            </div>
                        </div>

                        <!-- Item 4: Adab & Sholat -->
                        <div class="space-y-2 rounded-lg border border-zinc-800/80 bg-zinc-900/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="smile" class="h-3.5 w-3.5 text-amber-400"></i>
                                        <span>4. Pembiasaan Adab &amp; Nilai Keislaman</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Mengucapkan salam, doa sebelum makan, adab tertib santun.</div>
                                </div>
                                <span class="text-xs font-semibold text-emerald-400">Skor: 4</span>
                            </div>
                            <div class="grid grid-cols-4 gap-1.5 pt-1">
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">1: Kurang</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">2: Mulai</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400 hover:bg-zinc-800">3: Sesuai</button>
                                <button type="button" class="h-8 rounded-md bg-zinc-100 text-[11px] font-semibold text-zinc-900 shadow-sm">4: Sangat Baik</button>
                            </div>
                        </div>

                        <!-- Item 5: Tilawati & Tahfidz -->
                        <div class="space-y-2 rounded-lg border border-zinc-800/80 bg-zinc-900/60 p-3.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-medium text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="book-open" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        <span>5. Placement Tilawati &amp; Hafalan Juz 30</span>
                                    </div>
                                    <div class="text-[11px] text-zinc-400 mt-0.5">Pengenalan huruf hijaiyah berharakat &amp; kelancaran surat pendek.</div>
                                </div>
                                <span class="text-xs font-semibold text-emerald-400">Tilawati Jilid 2</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-1">
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400">Jilid 1</button>
                                <button type="button" class="h-8 rounded-md bg-zinc-100 text-[11px] font-semibold text-zinc-900 shadow-sm">Jilid 2</button>
                                <button type="button" class="h-8 rounded-md border border-zinc-800 bg-zinc-950 text-[11px] font-medium text-zinc-400">Jilid 3+</button>
                            </div>
                        </div>

                        <!-- Catatan Anecdotal -->
                        <div class="space-y-1.5 pt-2">
                            <label class="text-xs font-medium text-zinc-300 flex items-center gap-1.5">
                                <i data-lucide="file-text" class="h-3.5 w-3.5 text-zinc-400"></i>
                                <span>Catatan Pengamat (Anecdotal Record)</span>
                            </label>
                            <textarea 
                                rows="3" 
                                class="w-full rounded-md border border-zinc-800 bg-zinc-950 p-3 text-xs text-zinc-200 placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors"
                                placeholder="Tuliskan catatan respon emosi atau minat unik ananda...">Ananda Fatih sangat antusias ketika menyusun balok bertema arsitektur. Memiliki konsentrasi visual-spasial tinggi, sopan menjawab pertanyaan guru, dan senang membantu merapikan mainan kembali ke rak.</textarea>
                        </div>

                    </div>

                    <!-- Footer Actions with Lucide Icons -->
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-800">
                        <button type="button" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-transparent px-4 text-xs font-medium text-zinc-400 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                            <i data-lucide="save" class="h-3.5 w-3.5"></i>
                            <span>Simpan Sebagai Draf</span>
                        </button>
                        
                        <button type="button" onclick="alert('Hasil observasi Ananda Fatih Al-Ayyubi berhasil disimpan ke basis data.');" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md bg-zinc-100 px-4 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                            <span>Simpan Lembar Observasi</span>
                        </button>
                    </div>

                </div>

                <!-- Right Sidebar: Ringkasan Hasil & Aksi (3 cols) -->
                <div class="lg:col-span-3 rounded-xl border border-zinc-800 bg-zinc-900/40 p-5 space-y-5">
                    
                    <div>
                        <span class="text-xs font-semibold text-zinc-200 flex items-center gap-1.5 pb-2 border-b border-zinc-800">
                            <i data-lucide="pie-chart" class="h-3.5 w-3.5 text-zinc-400"></i>
                            <span>Ringkasan Kesiapan Ananda</span>
                        </span>

                        <div class="text-center my-4 py-3 rounded-lg border border-zinc-800 bg-zinc-950">
                            <div class="text-3xl font-bold font-mono text-zinc-100">95%</div>
                            <div class="text-xs font-semibold text-emerald-400 mt-1">Sangat Siap Belajar</div>
                            <div class="text-[11px] text-zinc-500">Transisi TK ke SD Lancar</div>
                        </div>

                        <div class="space-y-2 text-xs divide-y divide-zinc-800/80">
                            <div class="flex justify-between pt-2">
                                <span class="text-zinc-400">Kemandirian:</span>
                                <span class="text-zinc-200 font-medium">Tinggi (Mandiri)</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-zinc-400">Gaya Belajar:</span>
                                <span class="text-zinc-200 font-medium">Visual-Kinestetik</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-zinc-400">Adab Sosial:</span>
                                <span class="text-zinc-200 font-medium">Sangat Santun</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-zinc-400">Kemitraan Ortu:</span>
                                <span class="text-zinc-200 font-medium">Selaras (Tinggi)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action List with Lucide Icons -->
                    <div class="space-y-2 pt-2 border-t border-zinc-800">
                        <button type="button" onclick="alert('Mengunduh Rapor Observasi PDF...');" class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-900/70 px-3 text-xs font-medium text-zinc-300 hover:bg-zinc-800 hover:text-zinc-100 transition-colors">
                            <i data-lucide="download" class="h-3.5 w-3.5"></i>
                            <span>Unduh Rapor Kesiapan (PDF)</span>
                        </button>
                        <button type="button" onclick="alert('Memanggil ananda berikutnya: Aisyah Humaira');" class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md border border-zinc-800 bg-zinc-800/80 px-3 text-xs font-semibold text-zinc-100 hover:bg-zinc-700 transition-colors">
                            <span>Panggil Siswa Berikutnya</span>
                            <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </button>
                    </div>

                </div>

            </div>

        </main>

        <!-- Minimal Footer -->
        <footer class="border-t border-zinc-800/80 py-4 px-6 text-center text-xs text-zinc-500">
            SAANS Assessment &bull; Perguruan Anak Saleh &bull; Portal Kerja Observasi Guru
        </footer>

    </body>
</html>
