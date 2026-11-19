<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SAANS Assessment SPMB Anak Saleh — Asesmen Kesiapan, Karakter &amp; Potensi Siswa TK • SD • SMP</title>
        
        <meta name="description" content="Platform Asesmen SPMB Terintegrasi Sekolah Anak Saleh (TK, SD, SMP). Pemetaan kesiapan belajar ramah anak, kecerdasan majemuk (Multiple Intelligences), adab & tahfidz, serta kemitraan orang tua.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts

        <!-- Styles & Scripts via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#030712] text-zinc-100 font-sans antialiased selection:bg-indigo-500/30 selection:text-indigo-200 overflow-x-hidden min-h-screen relative">

        <!-- Ambient Background Light Cones & Grid Matrix (21st.dev signature background) -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <!-- Subtle dot & line grid -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:44px_44px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
            
            <!-- Ambient blurred color glow blobs -->
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[720px] h-[480px] bg-indigo-600/15 rounded-full blur-[140px] animate-pulse-glow"></div>
            <div class="absolute top-[20%] -left-48 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[160px]"></div>
            <div class="absolute top-[45%] -right-48 w-[540px] h-[540px] bg-purple-600/10 rounded-full blur-[160px]"></div>
            <div class="absolute top-[75%] left-1/3 w-[600px] h-[450px] bg-amber-500/10 rounded-full blur-[150px]"></div>
        </div>

        <!-- Top Announcement Bar -->
        <div class="relative z-50 border-b border-white/5 bg-gradient-to-r from-emerald-950/40 via-indigo-950/40 to-purple-950/40 backdrop-blur-md px-4 py-2 text-center text-xs text-zinc-300">
            <span class="inline-flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="font-medium text-emerald-400">SPMB Anak Saleh TA 2026/2027:</span>
                Pendaftaran &amp; Observasi Kesiapan Siswa Baru TK, SD, dan SMP Telah Dibuka.
                <a href="#simulasi" class="underline underline-offset-2 text-white hover:text-emerald-300 transition-colors ml-1 font-semibold">Coba Simulasi Asesmen &rarr;</a>
            </span>
        </div>

        <!-- Floating Modern Pill Navbar (21st.dev style) -->
        <header id="navbar-header" class="fixed top-12 left-0 right-0 z-40 px-4 transition-all duration-300">
            <div class="max-w-6xl mx-auto flex items-center justify-between px-5 py-3 rounded-2xl border border-white/10 bg-[#090d16]/80 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)]">
                <!-- Brand Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 via-indigo-500 to-purple-500 p-[1px] shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                        <div class="w-full h-full bg-[#030712] rounded-[11px] flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400 group-hover:text-cyan-300 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-bold tracking-tight text-white font-mono">SAANS</span>
                            <span class="text-xs px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold border border-emerald-500/30">ANAK SALEH</span>
                        </div>
                        <span class="text-[10px] text-zinc-400 tracking-wider uppercase font-medium">Asesmen Kesiapan TK • SD • SMP</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-zinc-300">
                    <a href="#filosofi" class="hover:text-white transition-colors">Filosofi Asesmen</a>
                    <a href="#jenjang" class="hover:text-white transition-colors">Jenjang TK/SD/SMP</a>
                    <a href="#core-3d" class="hover:text-white transition-colors">3D Holo-Growth</a>
                    <a href="#simulasi" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <span>Coba Asesmen</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    </a>
                    <a href="#faq" class="hover:text-white transition-colors">FAQ Orang Tua</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <a href="#simulasi" class="hidden sm:inline-flex text-xs font-semibold px-4 py-2 rounded-xl text-zinc-300 hover:text-white hover:bg-white/5 border border-white/10 transition-colors">
                        Simulasi Observasi
                    </a>
                    <a href="#daftar" class="inline-flex items-center gap-1.5 text-xs font-semibold px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-600 to-indigo-600 text-white shadow-[0_0_20px_rgba(16,185,129,0.35)] hover:shadow-[0_0_28px_rgba(16,185,129,0.5)] hover:scale-[1.02] active:scale-[0.98] transition-all border border-emerald-400/30">
                        <span>Daftar SPMB</span>
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="relative z-10 pt-32 pb-24">

            <!-- HERO SECTION with 3D Holo-Growth Potential Core -->
            <section class="max-w-6xl mx-auto px-4 lg:px-6 pt-10 pb-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Left Column: Copywriting & CTAs -->
                    <div class="lg:col-span-7 flex flex-col gap-6 text-left">
                        
                        <!-- Top Tag / Pill -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-medium w-fit backdrop-blur-md">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span>SPMB Ramah Anak &amp; Holistik • Sekolah Alam Anak Saleh</span>
                            <span class="text-white/20">|</span>
                            <span class="text-zinc-400 font-mono text-[11px]">TK • SD • SMP</span>
                        </div>

                        <!-- Main Headline -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-white">
                            Kenali Potensi Ananda <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-indigo-300">
                                Sejak Hari Pertama Melangkah.
                            </span>
                        </h1>

                        <!-- Description -->
                        <p class="text-base sm:text-lg text-zinc-300 leading-relaxed max-w-xl">
                            SAANS Assessment SPMB hadir bukan untuk menguji atau membebani calon siswa dengan tes kaku, melainkan 
                            <span class="text-white font-medium">memetakan kesiapan tumbuh kembang, kecerdasan majemuk (Multiple Intelligences), karakter adab</span>, 
                            serta menyelaraskan sinergi kemitraan antara ayah-bunda dan Sekolah Anak Saleh.
                        </p>

                        <!-- CTA Action Buttons -->
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="#simulasi" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-600 to-indigo-600 text-white font-semibold text-sm shadow-[0_0_28px_rgba(16,185,129,0.4)] hover:shadow-[0_0_36px_rgba(16,185,129,0.6)] hover:scale-[1.02] active:scale-[0.98] transition-all border border-emerald-300/40">
                                <svg class="w-4 h-4 text-emerald-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                </svg>
                                <span>Coba Simulasi Asesmen Ananda</span>
                            </a>

                            <a href="#jenjang" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white/[0.04] text-zinc-200 font-medium text-sm hover:bg-white/[0.08] hover:text-white border border-white/10 transition-all">
                                <span>Panduan Jenjang TK/SD/SMP</span>
                                <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 9l-7 7-7-7"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Micro Metrics row -->
                        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-white/10 max-w-lg">
                            <div class="flex flex-col">
                                <span class="text-2xl sm:text-3xl font-bold font-mono text-emerald-400">100%</span>
                                <span class="text-xs text-zinc-400">Ramah &amp; Tanpa Stres</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-2xl sm:text-3xl font-bold font-mono text-indigo-400">8 Pilar</span>
                                <span class="text-xs text-zinc-400">Multiple Intelligences</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-2xl sm:text-3xl font-bold font-mono text-purple-400">Adab &amp; Al-Qur'an</span>
                                <span class="text-xs text-zinc-400">Pemetaan Karakter Islami</span>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Interactive Three.js 3D Viewport -->
                    <div id="core-3d" class="lg:col-span-5 relative flex flex-col items-center">
                        <div class="w-full relative rounded-3xl border border-white/10 bg-gradient-to-b from-slate-900/40 to-slate-950/80 backdrop-blur-xl p-3 shadow-2xl overflow-hidden group">
                            
                            <!-- Header Bar inside 3D Canvas Box -->
                            <div class="flex items-center justify-between px-3 py-2 border-b border-white/10 text-xs text-zinc-400 font-mono">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                    <span class="ml-2 text-zinc-300 font-semibold">SAANS 3D Holo-Growth Core</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] text-emerald-400">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Interactive 3D</span>
                                </div>
                            </div>

                            <!-- Three.js Canvas Container -->
                            <div id="three-hero-container" class="w-full h-[420px] sm:h-[460px] relative cursor-grab active:cursor-grabbing">
                                <div class="absolute inset-0 flex items-center justify-center text-zinc-500 text-xs">
                                    <span class="animate-pulse">Memuat 3D Core Potensi Anak...</span>
                                </div>
                            </div>

                            <!-- 3D Interactive Jenjang Selector -->
                            <div class="p-2 border-t border-white/10 bg-[#090d16]/70 rounded-2xl flex flex-col gap-2">
                                <div class="flex items-center justify-between px-2 text-[11px] text-zinc-400">
                                    <span>Pilih Fokus Pemetaan Jenjang:</span>
                                    <span class="text-emerald-400 font-mono">Sentuh / Geser 3D</span>
                                </div>
                                <div class="grid grid-cols-3 gap-1.5">
                                    <button type="button" data-three-mode="tk" class="text-[11px] py-1.5 px-2 rounded-lg font-medium border border-emerald-500/60 bg-emerald-600/30 text-white transition-all text-center truncate">
                                        🧸 Jenjang TK
                                    </button>
                                    <button type="button" data-three-mode="sd" class="text-[11px] py-1.5 px-2 rounded-lg font-medium border border-white/10 bg-white/[0.04] text-zinc-400 hover:text-white transition-all text-center truncate">
                                        🎒 Jenjang SD
                                    </button>
                                    <button type="button" data-three-mode="smp" class="text-[11px] py-1.5 px-2 rounded-lg font-medium border border-white/10 bg-white/[0.04] text-zinc-400 hover:text-white transition-all text-center truncate">
                                        🎓 Jenjang SMP
                                    </button>
                                </div>
                            </div>

                            <!-- Ambient corner glow inside card -->
                            <div class="absolute -bottom-10 -right-10 w-36 h-36 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- TIGA JENJANG PENDIDIKAN ANAK SALEH (Overview Badges) -->
            <section id="jenjang" class="max-w-6xl mx-auto px-4 py-8">
                <div class="text-center text-xs font-semibold uppercase tracking-wider text-zinc-400 mb-6">
                    Penerimaan Siswa Baru Terpadu Sekolah Alam Anak Saleh
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div class="p-5 rounded-2xl bg-gradient-to-b from-white/[0.04] to-transparent border border-emerald-500/30 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-2xl shrink-0">
                            🧸
                        </div>
                        <div>
                            <div class="text-xs font-mono font-bold text-emerald-400">KB &amp; TK ANAK SALEH</div>
                            <h3 class="text-sm font-bold text-white">Observasi Tumbuh Kembang</h3>
                            <p class="text-xs text-zinc-400 mt-0.5">Motorik, sensori, sosialisasi, toilet training &amp; kemandirian bermain.</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-gradient-to-b from-white/[0.04] to-transparent border border-indigo-500/30 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-2xl shrink-0">
                            🎒
                        </div>
                        <div>
                            <div class="text-xs font-mono font-bold text-indigo-400">SD ANAK SALEH</div>
                            <h3 class="text-sm font-bold text-white">Multiple Intelligences &amp; Adab</h3>
                            <p class="text-xs text-zinc-400 mt-0.5">Gaya belajar VAK, pra-literasi &amp; numerasi bermakna, pembiasaan sholat.</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-gradient-to-b from-white/[0.04] to-transparent border border-purple-500/30 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-2xl shrink-0">
                            🎓
                        </div>
                        <div>
                            <div class="text-xs font-mono font-bold text-purple-400">SMP ANAK SALEH</div>
                            <h3 class="text-sm font-bold text-white">Akademik Diagnostik &amp; Leadership</h3>
                            <p class="text-xs text-zinc-400 mt-0.5">Penalaran kritis, tahfidz Al-Qur'an mandiri, &amp; kematangan emosional remaja.</p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 21st.dev BENTO GRID: 6 PILAR ASESMEN ANAK SALEH -->
            <section id="filosofi" class="max-w-6xl mx-auto px-4 lg:px-6 py-20">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                        Metode Asesmen Modern &amp; Manusiawi
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
                        Mengapa Asesmen SAANS di Anak Saleh Begitu Berbeda?
                    </h2>
                    <p class="text-zinc-300 mt-3 text-sm sm:text-base leading-relaxed">
                        Kami tidak mengklasifikasi siswa dengan label lulus atau tidak lulus, melainkan merancang peta jalan pendampingan belajar unik bagi setiap ananda.
                    </p>
                </div>

                <!-- Bento Grid Container -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    
                    <!-- Bento Item 1: Large Span (Observasi Tumbuh Kembang & Psikolog Anak) -->
                    <div class="md:col-span-2 lg:col-span-2 glass-panel glass-panel-hover rounded-3xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-start justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-mono px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300">
                                Sesi Observasi Play-Based
                            </span>
                        </div>

                        <!-- Mock Child Observation Metric Visualizer -->
                        <div class="my-6 p-4 rounded-2xl bg-[#090d16] border border-white/10 relative overflow-hidden">
                            <div class="flex items-center justify-between text-xs text-zinc-400 mb-3 pb-2 border-b border-white/5 font-mono">
                                <span class="flex items-center gap-1.5 text-emerald-400">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Rubrik Observasi Kelas Trial</span>
                                </span>
                                <span>Psikolog &amp; Guru Pengamat</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white/[0.02] p-3 rounded-xl border border-white/5 flex flex-col gap-1">
                                    <span class="text-[10px] text-zinc-400 uppercase">Kemandirian &amp; Separasi</span>
                                    <span class="text-sm font-semibold text-emerald-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Mandiri (Percaya Diri)
                                    </span>
                                </div>
                                <div class="bg-white/[0.02] p-3 rounded-xl border border-white/5 flex flex-col gap-1">
                                    <span class="text-[10px] text-zinc-400 uppercase">Sensori &amp; Motorik Halus</span>
                                    <span class="text-sm font-semibold text-emerald-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Sesuai Usia Perkembangan
                                    </span>
                                </div>
                                <div class="bg-white/[0.02] p-3 rounded-xl border border-white/5 flex flex-col gap-1">
                                    <span class="text-[10px] text-zinc-400 uppercase">Kepatuhan Instruksi &amp; Adab</span>
                                    <span class="text-sm font-semibold text-emerald-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Santun &amp; Responsif
                                    </span>
                                </div>
                                <div class="bg-white/[0.02] p-3 rounded-xl border border-white/5 flex flex-col gap-1">
                                    <span class="text-[10px] text-zinc-400 uppercase">Interaksi dengan Teman</span>
                                    <span class="text-sm font-semibold text-emerald-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Suka Berbagi &amp; Empati
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">School Readiness &amp; Pemetaan Psikologis Anak</h3>
                            <p class="text-zinc-300 text-sm leading-relaxed">
                                Dilakukan melalui aktivitas bermain peran, mewarnai, puzzle, dan interaksi kelompok yang menyenangkan didampingi psikolog pendidikan anak untuk mengamati kesiapan sekolah tanpa rasa takut.
                            </p>
                        </div>
                    </div>

                    <!-- Bento Item 2: Multiple Intelligences Gardner -->
                    <div class="md:col-span-1 lg:col-span-2 glass-panel glass-panel-hover rounded-3xl p-6 sm:p-8 flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-start justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-mono px-2.5 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-300">
                                8 Kecerdasan Majemuk
                            </span>
                        </div>

                        <!-- Graphical Multiple Intelligences Radar Bar -->
                        <div class="my-6 p-4 rounded-2xl bg-[#090d16] border border-white/10">
                            <div class="flex items-center justify-between text-xs text-zinc-400 mb-2 font-mono">
                                <span>Radar Profil Kecerdasan Ananda</span>
                                <span class="text-indigo-400">Personalized Learning</span>
                            </div>
                            <div class="space-y-2 pt-2">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-zinc-300">Spasial-Visual &amp; Kreativitas</span>
                                    <span class="font-mono text-emerald-400">92%</span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full w-[92%] shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-zinc-300">Logika Matematis &amp; Pola</span>
                                    <span class="font-mono text-indigo-400">86%</span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full w-[86%] shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-zinc-300">Interpersonal &amp; Adab Sosial</span>
                                    <span class="font-mono text-purple-400">94%</span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full w-[94%] shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Multiple Intelligences &amp; Gaya Belajar VAK</h3>
                            <p class="text-zinc-300 text-sm leading-relaxed">
                                Setiap anak lahir dengan keistimewaan. SAANS memetakan apakah ananda lebih dominan tipe Visual, Auditori, atau Kinestetik agar guru dapat menyajikan metode ajar yang pas sejak hari pertama masuk kelas.
                            </p>
                        </div>
                    </div>

                    <!-- Bento Item 3: Karakter Islami & Tahfidz Diagnostic -->
                    <div class="md:col-span-1 lg:col-span-1 glass-panel glass-panel-hover rounded-3xl p-6 flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 mb-4 text-lg">
                            📖
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1.5">Diagnostik Tahfidz</h4>
                            <p class="text-zinc-300 text-xs leading-relaxed">
                                Menakar hafalan juz 30, makhraj, dan kelancaran membaca Al-Qur'an/Iqro secara santun untuk penempatan halaqah yang pas.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-[11px] font-mono text-amber-400">
                            <span>Metode Tilawati &amp; Adab</span>
                            <span>Tanpa Beban Target</span>
                        </div>
                    </div>

                    <!-- Bento Item 4: Wawancara Kemitraan Orang Tua -->
                    <div class="md:col-span-1 lg:col-span-1 glass-panel glass-panel-hover rounded-3xl p-6 flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-purple-400 mb-4 text-lg">
                            👨‍👩‍👧‍👦
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1.5">Kemitraan Orang Tua</h4>
                            <p class="text-zinc-300 text-xs leading-relaxed">
                                Sesi dialog mendalam menyelaraskan pola asuh keluarga di rumah dengan kurikulum karakter Sekolah Anak Saleh.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-[11px] font-mono text-purple-400">
                            <span>Parenting Alignment</span>
                            <span>Sinergi Rumah &amp; Sekolah</span>
                        </div>
                    </div>

                    <!-- Bento Item 5: Zero Stress & Gamified Assessment -->
                    <div class="md:col-span-1 lg:col-span-1 glass-panel glass-panel-hover rounded-3xl p-6 flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 mb-4 text-lg">
                            🎨
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1.5">No-Stress Testing</h4>
                            <p class="text-zinc-300 text-xs leading-relaxed">
                                Tidak ada tes calistung membebani untuk jenjang TK. Asesmen dikemas berupa petualangan game interaktif dan aktivitas kinestetik.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-[11px] font-mono text-cyan-400">
                            <span>Child-Centric</span>
                            <span>Play &amp; Discover</span>
                        </div>
                    </div>

                    <!-- Bento Item 6: Rapor Profil Potensi & Rekomendasi Guru -->
                    <div class="md:col-span-1 lg:col-span-1 glass-panel glass-panel-hover rounded-3xl p-6 flex flex-col justify-between">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400 mb-4 text-lg">
                            📋
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1.5">Rapor Profil Holistik</h4>
                            <p class="text-zinc-300 text-xs leading-relaxed">
                                Ayah dan bunda menerima dokumen laporan komprehensif tumbuh kembang lengkap dengan saran stimulasi belajar di rumah.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-[11px] font-mono text-rose-400">
                            <span>PDF Digital Report</span>
                            <span>Panduan Pengasuhan</span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- INTERACTIVE CHILD-FRIENDLY SPMB SIMULATION WORKSTATION -->
            <section id="simulasi" class="max-w-6xl mx-auto px-4 lg:px-6 py-20 relative">
                
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                        Simulasi Asesmen Kesiapan Belajar
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
                        Coba Contoh Rubrik Observasi &amp; Asesmen Anak Saleh
                    </h2>
                    <p class="text-zinc-300 mt-2 text-sm sm:text-base">
                        Simulasi interaktif bagaimana sistem SAANS memotret respons anak, logika adab, serta keselarasan pola asuh orang tua.
                    </p>
                </div>

                <!-- Simulation Workstation UI Card -->
                <div class="rounded-3xl border border-white/15 bg-gradient-to-b from-[#090d16] to-[#04060d] shadow-[0_20px_60px_rgba(0,0,0,0.8)] overflow-hidden">
                    
                    <!-- Simulated Exam Top Toolbar -->
                    <div class="px-5 py-3.5 bg-zinc-950/80 border-b border-white/10 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></div>
                            <div>
                                <h3 class="text-sm font-bold text-white">SAANS SPMB — Portal Observasi Siswa Baru Sekolah Anak Saleh</h3>
                                <div class="text-[11px] text-zinc-400">Calon Siswa: <span class="text-white font-mono">Ananda Fatih &amp; Orang Tua (SPMB-2026-AS)</span></div>
                            </div>
                        </div>

                        <!-- Live Timer & Progress -->
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 rounded-xl">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <span class="text-xs text-zinc-400">Durasi Sesi:</span>
                                <span id="cbt-timer" class="font-mono text-sm font-bold text-emerald-300">25:00</span>
                            </div>

                            <div class="hidden sm:flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl">
                                <span id="cbt-progress-count" class="font-mono text-xs font-semibold text-emerald-400">0/5 Terobservasi</span>
                            </div>

                            <button type="button" id="cbt-finish-btn" class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs transition-colors shadow-lg shadow-emerald-600/30">
                                Selesai Observasi
                            </button>
                        </div>
                    </div>

                    <!-- Workstation Body: Question & Right Proctoring Sidebar -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-white/10">
                        
                        <!-- Question Area (Left 8 cols) -->
                        <div class="lg:col-span-8 p-6 sm:p-8 flex flex-col justify-between min-h-[460px]">
                            
                            <div>
                                <!-- Question Header Badge -->
                                <div class="flex items-center justify-between pb-4 mb-6 border-b border-white/10">
                                    <div class="flex items-center gap-2">
                                        <span id="cbt-question-number" class="text-xs font-bold text-emerald-400 font-mono uppercase bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                                            Observasi No. 1 dari 5
                                        </span>
                                        <span id="cbt-subject" class="text-xs text-zinc-300 font-medium">
                                            Observasi Kesiapan Sosio-Emosional (Jenjang TK / SD)
                                        </span>
                                    </div>
                                    <span id="cbt-difficulty" class="text-[11px] font-mono text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-md border border-indigo-500/20">
                                        Asesmen: Kemandirian &amp; Adab Bergaul
                                    </span>
                                </div>

                                <!-- Question Text -->
                                <p id="cbt-text" class="text-base sm:text-lg text-white font-medium leading-relaxed mb-8">
                                    Memuat butir observasi kesiapan...
                                </p>

                                <!-- Options Container -->
                                <div id="cbt-options-container" class="space-y-3">
                                    <!-- Populated dynamically via JS -->
                                </div>
                            </div>

                            <!-- Question Navigation Buttons -->
                            <div class="flex items-center justify-between pt-8 mt-8 border-t border-white/10">
                                <button type="button" id="cbt-prev-btn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-white/10 text-xs font-medium text-zinc-300 hover:bg-white/5 hover:text-white transition-colors">
                                    &larr; Indikator Sebelumnya
                                </button>
                                
                                <span class="text-xs text-zinc-500 hidden sm:inline">Pilih opsi respon terbaik ananda</span>

                                <button type="button" id="cbt-next-btn" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-xs font-semibold text-white transition-all shadow-md shadow-emerald-600/30">
                                    Lanjut Indikator Berikutnya &rarr;
                                </button>
                            </div>

                        </div>

                        <!-- Right Panel: Profil Pengamat & Navigasi -->
                        <div class="lg:col-span-4 p-6 bg-zinc-950/40 flex flex-col justify-between gap-6">
                            
                            <!-- Pengamat / Tim Psikologi Card -->
                            <div class="rounded-2xl border border-white/10 bg-[#060810] p-4 relative overflow-hidden">
                                <div class="flex items-center justify-between text-xs font-mono text-zinc-400 mb-2">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>Status Observasi Sesi</span>
                                    </span>
                                    <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-500/30 font-bold">LIVE OBSERVATION</span>
                                </div>

                                <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-lg">
                                        👩‍🏫
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-white">Ibu Ratna, S.Psi., M.Pd.</div>
                                        <div class="text-[11px] text-zinc-400">Tim Psikolog &amp; Konselor Anak Saleh</div>
                                    </div>
                                </div>

                                <div class="mt-3 space-y-2 text-[11px] text-zinc-400">
                                    <div class="flex justify-between">
                                        <span>Suasana Observasi:</span>
                                        <span class="text-emerald-400 font-medium">Nyaman &amp; Ceria (Playful)</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tingkat Adaptasi Anak:</span>
                                        <span class="text-emerald-400 font-medium">Mudah Berbaur</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Indikator Palette (1 to 5) -->
                            <div class="rounded-2xl border border-white/10 bg-[#060810] p-4">
                                <div class="flex items-center justify-between text-xs font-semibold text-zinc-300 mb-3">
                                    <span>Pilar Indikator Asesmen</span>
                                    <span class="text-[11px] text-zinc-500 font-mono">5 Aspek Kunci</span>
                                </div>

                                <div class="grid grid-cols-5 gap-2">
                                    <button type="button" data-cbt-q-idx="0" class="h-10 rounded-xl font-mono text-xs font-bold border border-indigo-500 bg-indigo-600 text-white transition-all">
                                        1
                                    </button>
                                    <button type="button" data-cbt-q-idx="1" class="h-10 rounded-xl font-mono text-xs font-bold border border-white/10 bg-white/5 text-zinc-400 hover:border-white/20 transition-all">
                                        2
                                    </button>
                                    <button type="button" data-cbt-q-idx="2" class="h-10 rounded-xl font-mono text-xs font-bold border border-white/10 bg-white/5 text-zinc-400 hover:border-white/20 transition-all">
                                        3
                                    </button>
                                    <button type="button" data-cbt-q-idx="3" class="h-10 rounded-xl font-mono text-xs font-bold border border-white/10 bg-white/5 text-zinc-400 hover:border-white/20 transition-all">
                                        4
                                    </button>
                                    <button type="button" data-cbt-q-idx="4" class="h-10 rounded-xl font-mono text-xs font-bold border border-white/10 bg-white/5 text-zinc-400 hover:border-white/20 transition-all">
                                        5
                                    </button>
                                </div>

                                <div class="mt-4 pt-3 border-t border-white/5 grid grid-cols-2 gap-2 text-[10px] text-zinc-400 font-mono">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded bg-indigo-600"></span>
                                        <span>Aktif Diamati</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded bg-emerald-500/40"></span>
                                        <span>Terisi Catatan</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Modal Result of Simulated Exam -->
                <div id="cbt-result-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4">
                    <div class="max-w-md w-full rounded-3xl border border-white/20 bg-[#090d16] p-6 sm:p-8 text-center shadow-2xl relative">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center mx-auto mb-4 text-3xl">
                            🌟
                        </div>
                        <h3 class="text-2xl font-bold text-white">Observasi Selesai!</h3>
                        <p class="text-zinc-400 text-xs mt-1">Laporan Diagnostik Kesiapan Belajar &amp; Karakter Ananda</p>

                        <div class="my-6 p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex flex-col gap-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-zinc-400">Indeks Kesiapan Sekolah:</span>
                                <span id="modal-tps-score" class="font-mono text-2xl font-extrabold text-emerald-400">95%</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-zinc-400">Kesesuaian Indikator:</span>
                                <span id="modal-correct-count" class="font-mono text-emerald-400 font-semibold">5 dari 5 Terpenuhi Sangat Baik</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-zinc-400">Karakter &amp; Adab:</span>
                                <span class="font-mono text-indigo-400 font-semibold">Mandiri, Sopan, &amp; Suka Berbagi</span>
                            </div>
                            <div class="flex justify-between items-center text-xs pt-2 border-t border-white/5">
                                <span class="text-zinc-400">Rekomendasi Kelas:</span>
                                <span class="font-mono text-purple-400 font-bold">Siap Menjadi Siswa Anak Saleh!</span>
                            </div>
                        </div>

                        <button type="button" id="cbt-modal-close" class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-indigo-600 text-white font-semibold text-sm hover:opacity-95 transition-all shadow-lg shadow-emerald-600/30">
                            Tutup Hasil Asesmen
                        </button>
                    </div>
                </div>

            </section>

            <!-- TABS DETAIL JENJANG TK, SD, SMP ANAK SALEH -->
            <section class="max-w-6xl mx-auto px-4 lg:px-6 py-20">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-mono font-bold tracking-wider uppercase text-indigo-400 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20">
                        Detail Asesmen Berdasarkan Usia
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
                        Tahapan Asesmen di Jenjang TK, SD, dan SMP
                    </h2>
                    <p class="text-zinc-300 mt-2 text-sm sm:text-base">
                        Setiap rentang usia memiliki instrumen observasi yang disesuaikan dengan tahapan perkembangan psikologis anak.
                    </p>
                </div>

                <!-- Pathway Tabs Selector -->
                <div class="flex flex-wrap items-center justify-center gap-2 p-1.5 rounded-2xl bg-white/[0.02] border border-white/10 max-w-2xl mx-auto mb-10">
                    <button type="button" data-pathway-tab="tk" class="flex-1 min-w-[130px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-indigo-600 text-white shadow-lg transition-all text-center">
                        🧸 Jenjang TK (Playgroup / TK A/B)
                    </button>
                    <button type="button" data-pathway-tab="sd" class="flex-1 min-w-[130px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.03] text-zinc-400 hover:text-white transition-all text-center">
                        🎒 Jenjang SD (Kelas 1 - 6)
                    </button>
                    <button type="button" data-pathway-tab="smp" class="flex-1 min-w-[130px] py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.03] text-zinc-400 hover:text-white transition-all text-center">
                        🎓 Jenjang SMP (Kelas 7 - 9)
                    </button>
                </div>

                <!-- Pathway Panel 1: TK Anak Saleh -->
                <div data-pathway-panel="tk" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center glass-panel rounded-3xl p-8 sm:p-10 border border-emerald-500/30">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full mb-4">
                            Taman Kanak-Kanak &amp; Kelompok Bermain
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4">Observasi Sensori Motorik &amp; Kesiapan Sekolah Ramah Anak</h3>
                        <p class="text-zinc-300 text-sm leading-relaxed mb-6">
                            Menghilangkan ketakutan anak terhadap tes sekolah. Asesmen dilakukan dalam suasana kelas bermain yang hangat dengan fokus pada kemandirian dasar, motorik halus, dan kehangatan sosialisasi.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-zinc-300 font-medium">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Kesiapan toilet training mandiri &amp; makan sendiri</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Sensori motorik (memegang krayon, meronce, melompat dua kaki)</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Respon mendengar cerita &amp; pembiasaan doa harian sederhana</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-2xl bg-[#090d16] p-5 border border-white/10 flex flex-col gap-3 font-mono text-xs text-zinc-400">
                        <div class="text-white font-bold pb-2 border-b border-white/5 flex justify-between items-center">
                            <span>Alur Observasi TK Anak Saleh</span>
                            <span class="text-emerald-400 text-[10px]">PLAY &amp; SMILE</span>
                        </div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5 flex items-center justify-between">
                            <span>1. Welcome Area &amp; Ice Breaking Mainan</span>
                            <span class="text-emerald-400">10 Menit</span>
                        </div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5 flex items-center justify-between">
                            <span>2. Observasi Kelompok Guru &amp; Psikolog</span>
                            <span class="text-emerald-400">20 Menit</span>
                        </div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5 flex items-center justify-between">
                            <span>3. Sesi Diskusi Kemitraan Orang Tua</span>
                            <span class="text-indigo-400">15 Menit</span>
                        </div>
                    </div>
                </div>

                <!-- Pathway Panel 2: SD Anak Saleh (Hidden by default) -->
                <div data-pathway-panel="sd" class="hidden grid-cols-1 md:grid-cols-2 gap-8 items-center glass-panel rounded-3xl p-8 sm:p-10 border border-indigo-500/30">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono text-indigo-400 bg-indigo-500/10 px-3 py-1 rounded-full mb-4">
                            Sekolah Dasar Karakter
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4">Pemetaan Gaya Belajar &amp; Kesiapan Belajar Mandiri</h3>
                        <p class="text-zinc-300 text-sm leading-relaxed mb-6">
                            Mendiagnosis fondasi literasi numerasi bermakna, gaya belajar (Visual, Auditori, Kinestetik), serta pembiasaan ibadah sholat dan adab akhlak mulia sejak dini.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-zinc-300 font-medium">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Pemetaan multiple intelligences &amp; minat bakat unik ananda</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Observasi fokus rentang atensi dan daya juang (grit) menyelesaikan tugas</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Placement tes membaca Al-Qur'an / Iqro metode Tilawati</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-2xl bg-[#090d16] p-5 border border-white/10 flex flex-col gap-3 font-mono text-xs text-zinc-400">
                        <div class="text-white font-bold pb-2 border-b border-white/5">Instrumen Asesmen SD</div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5">
                            <div class="text-zinc-300 font-semibold mb-1">Tes Kesiapan Kognitif Kontekstual</div>
                            <div class="text-indigo-400 text-[11px]">Logika gambar, pola deret angka ramah anak, &amp; pemahaman cerita</div>
                        </div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5">
                            <div class="text-zinc-300 font-semibold mb-1">Observasi Pembiasaan Sholat &amp; Wudhu</div>
                            <div class="text-emerald-400 text-[11px]">Gerakan sholat tertib, bacaan doa, dan kerapian adab</div>
                        </div>
                    </div>
                </div>

                <!-- Pathway Panel 3: SMP Anak Saleh (Hidden by default) -->
                <div data-pathway-panel="smp" class="hidden grid-cols-1 md:grid-cols-2 gap-8 items-center glass-panel rounded-3xl p-8 sm:p-10 border border-purple-500/30">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full mb-4">
                            Sekolah Menengah Pertama
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4">Akademik Diagnostik, Kepemimpinan &amp; Tahfidz Mandiri</h3>
                        <p class="text-zinc-300 text-sm leading-relaxed mb-6">
                            Menyiapkan siswa memasuki fase remaja dengan kemandirian belajar, integritas moral, kemampuan berpikir kritis (HOTS), serta target capaian tahfidz Al-Qur'an terpersonalisasi.
                        </p>
                        <ul class="space-y-3 text-xs sm:text-sm text-zinc-300 font-medium">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Asesmen diagnostik literasi sains, numerik, dan Bahasa Inggris</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Pemetaan capaian tahfidz (Juz 30, Juz 29, dan tahsin tartil)</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Wawancara motivasi kepemimpinan &amp; kedewasaan emosional siswa</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-2xl bg-[#090d16] p-5 border border-white/10 flex flex-col gap-3 font-mono text-xs text-zinc-400">
                        <div class="text-white font-bold pb-2 border-b border-white/5">Instrumen Diagnostik SMP</div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5">
                            <div class="text-zinc-300 font-semibold mb-1">CBT Diagnostik Digital SAANS</div>
                            <div class="text-purple-400 text-[11px]">Soal kontekstual pemecahan masalah (Problem Solving)</div>
                        </div>
                        <div class="p-3 bg-white/[0.02] rounded-xl border border-white/5">
                            <div class="text-zinc-300 font-semibold mb-1">Leadership &amp; Akhlak Interview</div>
                            <div class="text-amber-400 text-[11px]">Studi kasus akhlak bergaul, bijak bermedia digital, &amp; adab keluarga</div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- TESTIMONIALS ORANG TUA SISWA ANAK SALEH -->
            <section class="max-w-6xl mx-auto px-4 lg:px-6 py-20">
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                        Pengalaman Ayah Bunda
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
                        Kisah Nyata Orang Tua Murid Sekolah Anak Saleh
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="glass-panel glass-panel-hover rounded-3xl p-6 sm:p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                ★★★★★
                            </div>
                            <p class="text-zinc-300 text-sm leading-relaxed italic mb-6">
                                "Waktu mendaftarkan ananda di TK Anak Saleh, kami sempat khawatir anak rewel. Ternyata proses observasinya sangat ceria! Pulang-pulang ananda malah minta besok ke sekolah lagi."
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                            <div class="w-10 h-10 rounded-full bg-emerald-500/30 flex items-center justify-center font-bold text-emerald-300 font-mono">
                                BA
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">Bunda Anindya</div>
                                <div class="text-[11px] text-zinc-400">Orang Tua Siswa TK Anak Saleh</div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel glass-panel-hover rounded-3xl p-6 sm:p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                ★★★★★
                            </div>
                            <p class="text-zinc-300 text-sm leading-relaxed italic mb-6">
                                "Rapor hasil asesmen diagnostik SD-nya sangat detail. Kami diberitahu bahwa tipe belajar anak adalah kinestetik, sehingga kami paham cara mendampinginya belajar di rumah tanpa marah-marah."
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/30 flex items-center justify-center font-bold text-indigo-300 font-mono">
                                AR
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">Ayah Rahmat, S.T.</div>
                                <div class="text-[11px] text-zinc-400">Orang Tua Siswa SD Anak Saleh</div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel glass-panel-hover rounded-3xl p-6 sm:p-7 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-amber-400 mb-4">
                                ★★★★★
                            </div>
                            <p class="text-zinc-300 text-sm leading-relaxed italic mb-6">
                                "Sesi wawancara kemitraan orang tua di SMP Anak Saleh sangat mencerahkan. Kami merasa sekolah ini benar-benar menjadi partner sejati orang tua dalam menjaga aqidah dan adab anak di masa remaja."
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-white/5">
                            <div class="w-10 h-10 rounded-full bg-purple-500/30 flex items-center justify-center font-bold text-purple-300 font-mono">
                                DH
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">dr. Dwi Hapsari, Sp.A</div>
                                <div class="text-[11px] text-zinc-400">Orang Tua Siswa SMP Anak Saleh</div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- FAQ SECTION ORANG TUA SISWA BARU -->
            <section id="faq" class="max-w-4xl mx-auto px-4 lg:px-6 py-20">
                <div class="text-center mb-12">
                    <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                        Pertanyaan Orang Tua
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight">
                        Tanya Jawab Seputar Asesmen SPMB Anak Saleh
                    </h2>
                </div>

                <div class="space-y-3">
                    
                    <!-- FAQ 1 -->
                    <div class="rounded-2xl border border-white/10 bg-[#090d16]/70 backdrop-blur-xl overflow-hidden">
                        <button type="button" data-faq-btn aria-expanded="false" class="w-full p-5 text-left flex items-center justify-between text-sm sm:text-base font-semibold text-white hover:text-emerald-300 transition-colors">
                            <span>Apakah anak yang belum lancar membaca dan berhitung (calistung) boleh mendaftar SD Anak Saleh?</span>
                            <svg class="faq-icon w-5 h-5 text-zinc-400 transition-transform duration-200 shrink-0 ml-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="hidden px-5 pb-5 text-xs sm:text-sm text-zinc-300 leading-relaxed border-t border-white/5 pt-3">
                            Tentu sangat boleh! Di Sekolah Anak Saleh, kami mematuhi prinsip transisi PAUD ke SD yang menyenangkan. Kami tidak mensyaratkan tes membaca dan berhitung kaku sebagai penentu penerimaan. Yang diamati adalah kematangan sosio-emosional, rasa ingin tahu, motorik, dan kemandirian ananda.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="rounded-2xl border border-white/10 bg-[#090d16]/70 backdrop-blur-xl overflow-hidden">
                        <button type="button" data-faq-btn aria-expanded="false" class="w-full p-5 text-left flex items-center justify-between text-sm sm:text-base font-semibold text-white hover:text-emerald-300 transition-colors">
                            <span>Bagaimana jika saat sesi observasi ananda merasa malu atau menangis?</span>
                            <svg class="faq-icon w-5 h-5 text-zinc-400 transition-transform duration-200 shrink-0 ml-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="hidden px-5 pb-5 text-xs sm:text-sm text-zinc-300 leading-relaxed border-t border-white/5 pt-3">
                            Hal tersebut sangat wajar pada tahapan adaptasi anak di lingkungan baru. Tim psikolog dan guru pengamat kami terlatih mendampingi ananda dengan sabar, mengajak bermain di sudut sensori, atau memperkenankan ayah bunda menemani sampai ananda merasa aman dan nyaman.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="rounded-2xl border border-white/10 bg-[#090d16]/70 backdrop-blur-xl overflow-hidden">
                        <button type="button" data-faq-btn aria-expanded="false" class="w-full p-5 text-left flex items-center justify-between text-sm sm:text-base font-semibold text-white hover:text-emerald-300 transition-colors">
                            <span>Apa yang dibahas dalam sesi wawancara kemitraan orang tua?</span>
                            <svg class="faq-icon w-5 h-5 text-zinc-400 transition-transform duration-200 shrink-0 ml-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="hidden px-5 pb-5 text-xs sm:text-sm text-zinc-300 leading-relaxed border-t border-white/5 pt-3">
                            Sesi ini berfokus pada dialog dua arah seputar riwayat kesehatan dan kebiasaan ananda di rumah, keselarasan nilai-nilai pengasuhan Islami, serta kesiapan ayah dan bunda untuk aktif berkolaborasi dalam kegiatan parenting dan pendampingan karakter anak di Sekolah Anak Saleh.
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="rounded-2xl border border-white/10 bg-[#090d16]/70 backdrop-blur-xl overflow-hidden">
                        <button type="button" data-faq-btn aria-expanded="false" class="w-full p-5 text-left flex items-center justify-between text-sm sm:text-base font-semibold text-white hover:text-emerald-300 transition-colors">
                            <span>Kapan dan dalam bentuk apa hasil asesmen SPMB diumumkan?</span>
                            <svg class="faq-icon w-5 h-5 text-zinc-400 transition-transform duration-200 shrink-0 ml-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div class="hidden px-5 pb-5 text-xs sm:text-sm text-zinc-300 leading-relaxed border-t border-white/5 pt-3">
                            Hasil asesmen disampaikan maksimal 3 hari kerja setelah sesi observasi selesai melalui Portal SPMB Online dan WhatsApp resmi panitia. Ayah bunda akan menerima Rapor Profil Potensi Digital yang memuat radar kecerdasan, catatan psikolog, serta panduan stimulasi anak di rumah.
                        </div>
                    </div>

                </div>
            </section>

            <!-- CALL TO ACTION: DAFTAR SPMB & JADWALKAN OBSERVASI -->
            <section id="daftar" class="max-w-6xl mx-auto px-4 lg:px-6 py-16">
                <div class="relative rounded-3xl p-8 sm:p-14 bg-gradient-to-br from-emerald-950/60 via-[#090d16] to-indigo-950/50 border border-emerald-500/30 overflow-hidden shadow-[0_0_60px_rgba(16,185,129,0.25)]">
                    
                    <!-- Ambient light beam inside CTA -->
                    <div class="absolute -top-32 -left-32 w-80 h-80 bg-emerald-500/30 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-indigo-500/25 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7">
                            <span class="text-xs font-mono font-bold tracking-wider uppercase text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                                Penerimaan Siswa Baru TA 2026/2027
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 tracking-tight leading-tight">
                                Wujudkan Pendidikan Karakter &amp; Prestasi Terbaik untuk Buah Hati
                            </h2>
                            <p class="text-zinc-300 mt-3 text-sm sm:text-base leading-relaxed">
                                Kuota observasi gelombang awal terbatas demi menjaga kualitas rasio pendampingan anak. Daftarkan ananda sekarang dan amankan jadwal observasi sekolah.
                            </p>
                        </div>

                        <!-- Quick SPMB Form -->
                        <div class="lg:col-span-5 bg-[#030712]/80 border border-white/10 rounded-2xl p-6 backdrop-blur-xl">
                            <form action="#daftar" method="GET" class="space-y-3.5" onsubmit="alert('Alhamdulillah! Formulir minat SPMB Sekolah Anak Saleh telah diterima. Panitia SPMB akan segera menghubungi ayah bunda via WhatsApp.'); return false;">
                                <div>
                                    <label class="block text-xs font-medium text-zinc-300 mb-1">Nama Lengkap Ananda</label>
                                    <input type="text" required placeholder="cth: Muhammad Fatih Al-Ayyubi" class="w-full px-3.5 py-2.5 rounded-xl bg-white/[0.04] border border-white/10 text-white text-xs placeholder:text-zinc-500 focus:outline-none focus:border-emerald-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-300 mb-1">Jenjang yang Dituju</label>
                                    <select class="w-full px-3.5 py-2.5 rounded-xl bg-[#090d16] border border-white/10 text-white text-xs focus:outline-none focus:border-emerald-500 transition-colors">
                                        <option value="tk">KB &amp; TK Anak Saleh (Usia 3 - 6 Tahun)</option>
                                        <option value="sd">SD Anak Saleh (Kelas 1 SD)</option>
                                        <option value="smp">SMP Anak Saleh (Kelas 7 SMP)</option>
                                        <option value="pindahan">Siswa Pindahan (SD / SMP)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-300 mb-1">Nomor WhatsApp Ayah / Bunda</label>
                                    <input type="tel" required placeholder="0812-3456-7890" class="w-full px-3.5 py-2.5 rounded-xl bg-white/[0.04] border border-white/10 text-white text-xs placeholder:text-zinc-500 focus:outline-none focus:border-emerald-500 transition-colors">
                                </div>
                                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-600 to-indigo-600 text-white font-semibold text-xs shadow-lg shadow-emerald-600/40 hover:opacity-95 transition-all">
                                    Daftar &amp; Ambil Jadwal Observasi &rarr;
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </section>

        </main>

        <!-- MODERN 21st.dev FOOTER -->
        <footer class="relative z-10 border-t border-white/10 bg-[#02040a] pt-14 pb-10 text-xs text-zinc-400">
            <div class="max-w-6xl mx-auto px-4 lg:px-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-10 pb-12 border-b border-white/5">
                    
                    <!-- Col 1: Brand Info -->
                    <div class="md:col-span-2 flex flex-col gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-white font-mono tracking-tight">SAANS</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-mono text-[10px] border border-emerald-500/30">SEKOLAH ANAK SALEH</span>
                        </div>
                        <p class="text-zinc-400 text-xs leading-relaxed max-w-sm">
                            Sistem Asesmen Adaptif Kesiapan Belajar, Multiple Intelligences, dan Pembiasaan Adab Karakter untuk calon siswa TK, SD, dan SMP Sekolah Anak Saleh.
                        </p>
                        <div class="flex items-center gap-2 pt-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[11px] text-zinc-300 font-mono">Status SPMB: Gelombang 1 Dibuka</span>
                        </div>
                    </div>

                    <!-- Col 2: Jenjang Pendidikan -->
                    <div class="flex flex-col gap-2.5">
                        <span class="text-white font-semibold text-xs tracking-wider uppercase font-mono">Jenjang Sekolah</span>
                        <a href="#jenjang" class="hover:text-white transition-colors">KB &amp; TK Anak Saleh</a>
                        <a href="#jenjang" class="hover:text-white transition-colors">SD Anak Saleh</a>
                        <a href="#jenjang" class="hover:text-white transition-colors">SMP Anak Saleh</a>
                        <a href="#jenjang" class="hover:text-white transition-colors">Program Tahfidz Cilik</a>
                    </div>

                    <!-- Col 3: Asesmen & Metode -->
                    <div class="flex flex-col gap-2.5">
                        <span class="text-white font-semibold text-xs tracking-wider uppercase font-mono">Metode Asesmen</span>
                        <a href="#filosofi" class="hover:text-white transition-colors">School Readiness</a>
                        <a href="#filosofi" class="hover:text-white transition-colors">Multiple Intelligences</a>
                        <a href="#filosofi" class="hover:text-white transition-colors">Kemitraan Orang Tua</a>
                        <a href="#core-3d" class="hover:text-white transition-colors">3D Holo-Growth Star</a>
                    </div>

                    <!-- Col 4: Informasi & Kontak -->
                    <div class="flex flex-col gap-2.5">
                        <span class="text-white font-semibold text-xs tracking-wider uppercase font-mono">Sekretariat SPMB</span>
                        <span class="text-zinc-400">Kampus Sekolah Alam Anak Saleh</span>
                        <span class="text-zinc-400">Jam Layanan: 07.30 - 15.00 WIB</span>
                        <a href="#daftar" class="text-emerald-400 hover:text-emerald-300 transition-colors">Konsultasi via WhatsApp &rarr;</a>
                    </div>

                </div>

                <!-- Footer Bottom Credits -->
                <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-zinc-400">
                    <p>&copy; 2026 Sekolah Alam Anak Saleh. Hak Cipta Dilindungi Undang-Undang.</p>
                    <div class="flex items-center gap-6">
                        <a href="#navbar-header" class="hover:text-white transition-colors">&uarr; Kembali ke Atas</a>
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
