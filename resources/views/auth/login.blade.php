<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk — SAANS Assessment SPMB Anak Saleh</title>
        
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
    <body class="h-full bg-zinc-950 text-zinc-100 antialiased font-sans">

        <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2">
            
            <!-- Left Side: Brand Panel (Shadcn Authentication Pattern) -->
            <div class="relative hidden lg:flex flex-col justify-between border-r border-zinc-800 bg-zinc-900/30 p-10 overflow-hidden">
                
                <!-- Background Geometric Grid (Minimalist) -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#27272a33_1px,transparent_1px),linear-gradient(to_bottom,#27272a33_1px,transparent_1px)] bg-[size:32px_32px]"></div>

                <!-- Brand Header -->
                <div class="relative z-10 flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md border border-zinc-700 bg-zinc-800 text-zinc-100 shadow-sm">
                        <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold tracking-tight text-zinc-100">SAANS Assessment</span>
                        <span class="text-[11px] text-zinc-400">Perguruan Anak Saleh (TK • SD • SMP)</span>
                    </div>
                </div>

                <!-- Center 3D Wireframe Canvas (Subtle & Elegant) -->
                <div class="relative z-10 my-auto flex flex-col items-center">
                    <div id="three-login-container" class="h-72 w-full max-w-sm cursor-grab active:cursor-grabbing"></div>
                    <div class="text-center">
                        <div class="inline-flex items-center gap-2 rounded-full border border-zinc-800 bg-zinc-900/80 px-3 py-1 text-xs text-zinc-400">
                            <i data-lucide="sparkles" class="h-3 w-3 text-emerald-400"></i>
                            <span>Sistem Observasi &amp; Kesiapan Siswa Baru 2026/2027</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Quote -->
                <div class="relative z-10 border-t border-zinc-800/80 pt-6">
                    <blockquote class="space-y-2">
                        <p class="text-xs text-zinc-400 leading-relaxed flex gap-2">
                            <i data-lucide="quote" class="h-3.5 w-3.5 shrink-0 text-zinc-600"></i>
                            <span>Mengenali keunikan gaya belajar, kemandirian emosi, dan adab ananda sejak dini untuk menghadirkan pendampingan pendidikan terbaik.</span>
                        </p>
                        <footer class="text-xs text-zinc-500 pl-5.5">
                            Tim Psikologi &amp; Panitia SPMB Sekolah Anak Saleh
                        </footer>
                    </blockquote>
                </div>

            </div>

            <!-- Right Side: Login Form (Clean Shadcn Card) -->
            <div class="relative flex items-center justify-center p-6 sm:p-10">
                <!-- Theme Settings Trigger -->
                <div class="absolute top-6 right-6">
                    <x-theme-settings />
                </div>

                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[420px]">
                    
                    <!-- Mobile Brand Logo -->
                    <div class="flex lg:hidden items-center gap-2.5 justify-center mb-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-md border border-zinc-700 bg-zinc-800 text-zinc-100">
                            <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                        </div>
                        <span class="text-sm font-semibold text-zinc-100">SAANS Assessment SPMB</span>
                    </div>

                    <!-- Form Header -->
                    <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                        <h1 class="text-xl font-semibold tracking-tight text-zinc-100">
                            Masuk ke Akun
                        </h1>
                        <p id="role-hint" class="text-xs text-zinc-400">
                            Pilih peran untuk diarahkan langsung ke halaman kerja Anda
                        </p>
                    </div>

                    <!-- Shadcn 2-Way Role Selector Tabs -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium leading-none text-zinc-300">
                            Pilih Peran Masuk
                        </label>
                        <div class="grid grid-cols-2 gap-1.5 rounded-lg border border-zinc-800 bg-zinc-900/70 p-1 text-xs">
                            <button type="button" id="tab-guru" class="inline-flex items-center justify-center gap-1.5 rounded-md bg-zinc-800 px-2.5 py-1.5 font-medium text-zinc-100 shadow-sm transition-all border border-zinc-700/80">
                                <i data-lucide="user-check" class="h-3.5 w-3.5 text-emerald-400"></i>
                                <span>Guru Pengamat</span>
                            </button>
                            <button type="button" id="tab-admin" class="inline-flex items-center justify-center gap-1.5 rounded-md px-2.5 py-1.5 font-medium text-zinc-400 hover:text-zinc-200 transition-all border border-transparent">
                                <i data-lucide="shield-check" class="h-3.5 w-3.5 text-zinc-400"></i>
                                <span>Admin</span>
                            </button>
                        </div>
                    </div>

                    <!-- Flash Notification -->
                    @if (session('status'))
                        <div class="rounded-md border border-emerald-900/50 bg-emerald-950/30 p-3 text-xs text-emerald-400 flex items-center gap-2">
                            <i data-lucide="check" class="h-3.5 w-3.5 shrink-0"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="role" id="input-role" value="guru">

                        <div class="space-y-1.5">
                            <label class="text-xs font-medium leading-none text-zinc-300 flex items-center gap-1.5" for="input-email">
                                <i data-lucide="mail" class="h-3.5 w-3.5 text-zinc-500"></i>
                                <span>Email / ID Akun</span>
                            </label>
                            <input 
                                id="input-email" 
                                name="email" 
                                type="email" 
                                required
                                value="guru@anaksaleh.sch.id"
                                placeholder="name@anaksaleh.sch.id" 
                                class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 py-1 text-xs text-zinc-100 shadow-sm placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium leading-none text-zinc-300 flex items-center gap-1.5" for="input-password">
                                    <i data-lucide="lock" class="h-3.5 w-3.5 text-zinc-500"></i>
                                    <span>Kata Sandi</span>
                                </label>
                                <span class="text-[11px] text-zinc-500">Demo bebas</span>
                            </div>
                            <input 
                                id="input-password" 
                                name="password" 
                                type="password" 
                                required
                                value="guru123"
                                placeholder="••••••••" 
                                class="flex h-9 w-full rounded-md border border-zinc-800 bg-zinc-900/40 px-3 py-1 text-xs text-zinc-100 shadow-sm placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-400 transition-colors font-mono"
                            />
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <label class="flex items-center gap-2 text-zinc-400 cursor-pointer">
                                <input type="checkbox" checked class="h-3.5 w-3.5 rounded border-zinc-700 bg-zinc-900 text-zinc-100 focus:ring-0">
                                <span>Ingat sesi masuk</span>
                            </label>
                            <span class="text-zinc-500 hover:text-zinc-400 text-[11px] cursor-pointer">Lupa sandi?</span>
                        </div>

                        <!-- Shadcn Primary Button with Lucide Icon -->
                        <button type="submit" id="btn-submit" class="inline-flex h-9 w-full items-center justify-center gap-2 rounded-md bg-zinc-100 px-4 py-2 text-xs font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors">
                            <span id="btn-submit-label">Masuk ke Halaman Observasi</span>
                            <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </button>
                    </form>

                    <p class="px-4 text-center text-[11px] text-zinc-500 leading-normal">
                        Perguruan Anak Saleh &bull; Sistem Asesmen Siswa Baru Terpadu
                    </p>

                </div>
            </div>

        </div>

        <!-- 2-Role Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tabGuru = document.getElementById('tab-guru');
                const tabAdmin = document.getElementById('tab-admin');

                const inputRole = document.getElementById('input-role');
                const inputEmail = document.getElementById('input-email');
                const inputPass = document.getElementById('input-password');
                const roleHint = document.getElementById('role-hint');
                const btnSubmitLabel = document.getElementById('btn-submit-label');

                const tabs = [
                    {
                        btn: tabGuru,
                        role: 'guru',
                        email: 'guru@anaksaleh.sch.id',
                        pass: 'guru123',
                        hint: 'Mengarahkan langsung ke Halaman Observasi Siswa Baru.',
                        label: 'Masuk ke Halaman Observasi'
                    },
                    {
                        btn: tabAdmin,
                        role: 'admin',
                        email: 'admin.sd@anaksaleh.sch.id',
                        pass: 'admin123',
                        hint: 'Gunakan email unit Anda: admin.tk / admin.sd / admin.smp@anaksaleh.sch.id',
                        label: 'Masuk ke Dashboard Admin'
                    },
                ];

                function activateTab(role) {
                    tabs.forEach(t => {
                        const isActive = t.role === role;
                        t.btn?.classList.toggle('bg-zinc-800', isActive);
                        t.btn?.classList.toggle('text-zinc-100', isActive);
                        t.btn?.classList.toggle('shadow-sm', isActive);
                        t.btn?.classList.toggle('border-zinc-700/80', isActive);
                        t.btn?.classList.toggle('text-zinc-400', !isActive);
                        t.btn?.classList.toggle('border-transparent', !isActive);

                        if (isActive) {
                            inputRole.value = t.role;
                            inputEmail.value = t.email;
                            inputPass.value = t.pass;
                            roleHint.textContent = t.hint;
                            btnSubmitLabel.textContent = t.label;
                        }
                    });
                    if (window.refreshLucideIcons) window.refreshLucideIcons();
                }

                tabGuru?.addEventListener('click', () => activateTab('guru'));
                tabAdmin?.addEventListener('click', () => activateTab('admin'));
            });
        </script>

    </body>
</html>
