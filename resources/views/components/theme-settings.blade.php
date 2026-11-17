<!-- Theme Settings Component (Shadcn Theme Customizer) -->
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

<!-- Navbar Action Buttons: Quick Mode Toggle + Palette Settings -->
<div class="flex items-center gap-1.5 shrink-0">

    <!-- Theme Customizer Modal Trigger -->
    <button 
        type="button" 
        onclick="openThemeModal()" 
        title="Buka Pengaturan Tema & Warna Lengkap"
        class="inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-700/80 bg-zinc-900/80 px-2.5 text-xs font-medium text-zinc-300 hover:text-zinc-100 hover:bg-zinc-800 transition-colors shadow-sm cursor-pointer"
    >
        <i data-lucide="palette" class="h-3.5 w-3.5 text-orange-500"></i>
        <span class="hidden sm:inline text-xs font-medium">Tema</span>
    </button>

</div>

<!-- Theme Settings Modal (Teleported to document.body on load to avoid header backdrop-blur trap) -->
<div 
    id="theme-settings-modal" 
    class="fixed inset-0 z-[99999] hidden overflow-y-auto bg-black/70 backdrop-blur-sm p-4 sm:p-6 transition-all"
    role="dialog" 
    aria-modal="true"
>
    <div class="min-h-full flex items-center justify-center py-6">
        <div class="w-full max-w-md my-auto rounded-2xl border border-zinc-700 bg-zinc-900 p-5 sm:p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            
            <!-- Modal Header with Close Button -->
            <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800 text-zinc-100">
                        <i data-lucide="paint-bucket" class="h-4 w-4 text-orange-400"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold tracking-tight text-zinc-100">Pengaturan Tampilan &amp; Tema</h2>
                        <p class="text-[11px] text-zinc-400">Sesuaikan mode, warna, dan ukuran teks</p>
                    </div>
                </div>
                <button 
                    type="button" 
                    onclick="closeThemeModal()" 
                    class="flex h-7 w-7 items-center justify-center rounded-md border border-zinc-700 bg-zinc-800 text-zinc-400 hover:text-zinc-100 transition-colors cursor-pointer"
                >
                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                </button>
            </div>

            <!-- SECTION 1: MODE TAMPILAN (DARK / LIGHT / SYSTEM) -->
            <div class="space-y-2 rounded-xl border border-zinc-700 bg-zinc-800/50 p-3">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold uppercase tracking-wider text-zinc-300 flex items-center gap-1.5">
                        <i data-lucide="sun-moon" class="h-3.5 w-3.5 text-zinc-400"></i>
                        <span>Mode Tampilan</span>
                    </label>
                    <span id="active-mode-label" class="text-xs text-orange-500 font-semibold font-mono">Gelap (Dark)</span>
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    <button 
                        type="button" 
                        onclick="setThemeMode('light')" 
                        id="btn-mode-light"
                        class="theme-mode-btn flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-900/80 p-2.5 text-xs font-medium text-zinc-300 hover:text-zinc-100 hover:bg-zinc-800 transition-all cursor-pointer"
                    >
                        <i data-lucide="sun" class="h-4 w-4 text-amber-500"></i>
                        <span class="font-semibold">Terang</span>
                        <span class="text-[10px] text-zinc-400">Light Mode</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setThemeMode('dark')" 
                        id="btn-mode-dark"
                        class="theme-mode-btn flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-900/80 p-2.5 text-xs font-medium text-zinc-300 hover:text-zinc-100 hover:bg-zinc-800 transition-all cursor-pointer"
                    >
                        <i data-lucide="moon" class="h-4 w-4 text-indigo-400"></i>
                        <span class="font-semibold">Gelap</span>
                        <span class="text-[10px] text-zinc-400">Dark Mode</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setThemeMode('system')" 
                        id="btn-mode-system"
                        class="theme-mode-btn flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-900/80 p-2.5 text-xs font-medium text-zinc-300 hover:text-zinc-100 hover:bg-zinc-800 transition-all cursor-pointer"
                    >
                        <i data-lucide="monitor" class="h-4 w-4 text-zinc-400"></i>
                        <span class="font-semibold">Sistem</span>
                        <span class="text-[10px] text-zinc-400">Otomatis OS</span>
                    </button>
                </div>
            </div>

            <!-- SECTION 2: PRESET WARNA -->
            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400 flex items-center gap-1.5">
                    <i data-lucide="palette" class="h-3.5 w-3.5 text-zinc-400"></i>
                    <span>Preset Warna Tema</span>
                </label>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    
                    <!-- Amber / Warm Stone (bKsEuMcK) -->
                    <button 
                        type="button" 
                        onclick="setThemePreset('amber')" 
                        id="btn-preset-amber"
                        class="theme-preset-btn flex items-center gap-2.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-left text-zinc-200 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="h-4 w-4 rounded-full bg-[#ea580c] ring-2 ring-orange-500/30 shrink-0"></span>
                        <div class="flex flex-col min-w-0">
                            <span class="font-semibold truncate">Warm Amber</span>
                            <span class="text-[10px] text-zinc-400 truncate">bKsEuMcK Preset</span>
                        </div>
                    </button>

                    <!-- Zinc Neutral (Shadcn Original) -->
                    <button 
                        type="button" 
                        onclick="setThemePreset('zinc')" 
                        id="btn-preset-zinc"
                        class="theme-preset-btn flex items-center gap-2.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-left text-zinc-200 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="h-4 w-4 rounded-full bg-[#71717a] ring-2 ring-zinc-500/30 shrink-0"></span>
                        <div class="flex flex-col min-w-0">
                            <span class="font-semibold truncate">Zinc Neutral</span>
                            <span class="text-[10px] text-zinc-400 truncate">Monokrom Bersih</span>
                        </div>
                    </button>

                    <!-- Emerald (Sekolah Anak Saleh) -->
                    <button 
                        type="button" 
                        onclick="setThemePreset('emerald')" 
                        id="btn-preset-emerald"
                        class="theme-preset-btn flex items-center gap-2.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-left text-zinc-200 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="h-4 w-4 rounded-full bg-[#059669] ring-2 ring-emerald-500/30 shrink-0"></span>
                        <div class="flex flex-col min-w-0">
                            <span class="font-semibold truncate">Emerald Islam</span>
                            <span class="text-[10px] text-zinc-400 truncate">Karakter Hijau</span>
                        </div>
                    </button>

                    <!-- Sapphire Blue (Academic) -->
                    <button 
                        type="button" 
                        onclick="setThemePreset('blue')" 
                        id="btn-preset-blue"
                        class="theme-preset-btn flex items-center gap-2.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-left text-zinc-200 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="h-4 w-4 rounded-full bg-[#0284c7] ring-2 ring-blue-500/30 shrink-0"></span>
                        <div class="flex flex-col min-w-0">
                            <span class="font-semibold truncate">Sapphire Blue</span>
                            <span class="text-[10px] text-zinc-400 truncate">Akademik Modern</span>
                        </div>
                    </button>

                </div>
            </div>

            <!-- SECTION 3: UKURAN HURUF -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400 flex items-center gap-1.5">
                        <i data-lucide="type" class="h-3.5 w-3.5 text-zinc-400"></i>
                        <span>Ukuran Huruf (Font)</span>
                    </label>
                    <span id="font-scale-label" class="text-xs font-mono text-zinc-400">106% (Sedang)</span>
                </div>
                <div class="grid grid-cols-4 gap-1.5">
                    <button 
                        type="button" 
                        onclick="setFontScale('100%')" 
                        id="btn-font-100"
                        class="theme-font-btn flex flex-col items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="text-xs font-semibold">Aa</span>
                        <span class="text-[10px] text-zinc-400">Standar</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setFontScale('106.25%')" 
                        id="btn-font-106"
                        class="theme-font-btn flex flex-col items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="text-sm font-semibold">Aa</span>
                        <span class="text-[10px] text-zinc-400">Sedang</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setFontScale('112.5%')" 
                        id="btn-font-112"
                        class="theme-font-btn flex flex-col items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="text-base font-semibold">Aa</span>
                        <span class="text-[10px] text-zinc-400">Besar</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setFontScale('120%')" 
                        id="btn-font-120"
                        class="theme-font-btn flex flex-col items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span class="text-lg font-semibold leading-tight">Aa</span>
                        <span class="text-[10px] text-zinc-400">Ekstra</span>
                    </button>
                </div>
            </div>

            <!-- SECTION 4: KELENGKUNGAN SUDUT -->
            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-wider text-zinc-400 flex items-center gap-1.5">
                    <i data-lucide="square" class="h-3.5 w-3.5 text-zinc-400"></i>
                    <span>Kelengkungan Sudut</span>
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <button 
                        type="button" 
                        onclick="setBorderRadius('0.375rem')" 
                        id="btn-radius-sm"
                        class="theme-radius-btn flex items-center justify-center gap-1.5 rounded-sm border border-zinc-700 bg-zinc-800/60 p-2 text-xs font-medium text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span>Compact</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setBorderRadius('0.875rem')" 
                        id="btn-radius-md"
                        class="theme-radius-btn flex items-center justify-center gap-1.5 rounded-xl border border-zinc-700 bg-zinc-800/60 p-2 text-xs font-medium text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span>Standar</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="setBorderRadius('1.25rem')" 
                        id="btn-radius-lg"
                        class="theme-radius-btn flex items-center justify-center gap-1.5 rounded-2xl border border-zinc-700 bg-zinc-800/60 p-2 text-xs font-medium text-zinc-300 hover:bg-zinc-700 transition-all cursor-pointer"
                    >
                        <span>Rounded</span>
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between border-t border-zinc-800 pt-3 text-xs">
                <span class="text-[11px] text-zinc-400">Tersimpan otomatis</span>
                <button 
                    type="button" 
                    onclick="closeThemeModal()" 
                    class="inline-flex h-8 items-center justify-center rounded-md bg-zinc-100 px-4 font-semibold text-zinc-900 shadow hover:bg-zinc-200 transition-colors cursor-pointer"
                >
                    Selesai
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Theme Controller Script -->
<script>
    function openThemeModal() {
        const modal = document.getElementById('theme-settings-modal');
        if (modal) {
            // Ensure modal is attached directly to document.body (escapes any backdrop-filter containing block!)
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.classList.remove('hidden');
            updateActiveThemeUI();
            if (window.refreshLucideIcons) window.refreshLucideIcons();
        }
    }

    function closeThemeModal() {
        const modal = document.getElementById('theme-settings-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    function toggleQuickThemeMode() {
        const isDark = document.documentElement.classList.contains('dark');
        setThemeMode(isDark ? 'light' : 'dark');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('theme-settings-modal');
        if (modal) {
            // Teleport modal to body so backdrop-blur on navbar does not trap fixed overlay
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.classList.contains('min-h-full')) {
                    closeThemeModal();
                }
            });
        }
        updateActiveThemeUI();
    });

    function setThemeMode(mode) {
        localStorage.setItem('saans-mode', mode);
        if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        updateActiveThemeUI();
        if (window.refreshLucideIcons) window.refreshLucideIcons();
    }

    function setThemePreset(preset) {
        localStorage.setItem('saans-theme', preset);
        document.documentElement.setAttribute('data-theme', preset);
        updateActiveThemeUI();
    }

    function setFontScale(scale) {
        localStorage.setItem('saans-font-scale', scale);
        document.documentElement.style.fontSize = scale;
        updateActiveThemeUI();
    }

    function setBorderRadius(radius) {
        localStorage.setItem('saans-radius', radius);
        document.documentElement.style.setProperty('--radius', radius);
        updateActiveThemeUI();
    }

    function updateActiveThemeUI() {
        const currentMode = localStorage.getItem('saans-mode') || 'dark';
        const currentTheme = localStorage.getItem('saans-theme') || 'amber';
        const currentFont = localStorage.getItem('saans-font-scale') || '106.25%';
        const currentRadius = localStorage.getItem('saans-radius') || '0.875rem';
        const isDark = document.documentElement.classList.contains('dark');

        // Update Quick Toggle Button Icons (sun vs moon)
        const sun = document.getElementById('quick-icon-sun');
        const moon = document.getElementById('quick-icon-moon');
        const quickBtn = document.getElementById('quick-theme-toggle-btn');
        if (sun && moon && quickBtn) {
            if (isDark) {
                sun.classList.remove('hidden');
                moon.classList.add('hidden');
                quickBtn.title = 'Beralih ke Mode Terang (Light)';
            } else {
                sun.classList.add('hidden');
                moon.classList.remove('hidden');
                quickBtn.title = 'Beralih ke Mode Gelap (Dark)';
            }
        }

        // Update Mode Label
        const modeLabel = document.getElementById('active-mode-label');
        if (modeLabel) {
            const labelMap = {
                'dark': 'Gelap (Dark)',
                'light': 'Terang (Light)',
                'system': 'Otomatis (Sistem)'
            };
            modeLabel.textContent = labelMap[currentMode] || 'Gelap';
        }

        // Update Mode Buttons in Modal
        ['light', 'dark', 'system'].forEach(m => {
            const btn = document.getElementById('btn-mode-' + m);
            if (!btn) return;
            if (m === currentMode) {
                btn.className = 'theme-mode-btn flex flex-col items-center justify-center gap-1.5 rounded-lg border-2 border-emerald-500 bg-emerald-500/15 p-2.5 text-xs font-semibold text-zinc-100 shadow-sm cursor-pointer';
            } else {
                btn.className = 'theme-mode-btn flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-xs font-medium text-zinc-400 hover:text-zinc-100 hover:bg-zinc-700 transition-all cursor-pointer';
            }
        });

        // Update Preset Buttons in Modal
        ['amber', 'zinc', 'emerald', 'blue'].forEach(t => {
            const btn = document.getElementById('btn-preset-' + t);
            if (!btn) return;
            if (t === currentTheme) {
                btn.className = 'theme-preset-btn flex items-center gap-2.5 rounded-lg border-2 border-emerald-500 bg-emerald-500/15 p-2.5 text-left text-zinc-100 shadow-sm cursor-pointer';
            } else {
                btn.className = 'theme-preset-btn flex items-center gap-2.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2.5 text-left text-zinc-400 hover:text-zinc-100 hover:bg-zinc-700 transition-all cursor-pointer';
            }
        });

        // Update Font Scale Buttons
        const fontMap = {
            '100%': { id: 'btn-font-100', label: '100% (Standar)' },
            '106.25%': { id: 'btn-font-106', label: '106% (Sedang)' },
            '112.5%': { id: 'btn-font-112', label: '112% (Besar)' },
            '120%': { id: 'btn-font-120', label: '120% (Ekstra)' },
        };
        Object.entries(fontMap).forEach(([scale, data]) => {
            const btn = document.getElementById(data.id);
            if (!btn) return;
            if (scale === currentFont) {
                btn.className = 'theme-font-btn flex flex-col items-center justify-center rounded-lg border-2 border-emerald-500 bg-emerald-500/15 p-2 text-zinc-100 shadow-sm cursor-pointer';
                const label = document.getElementById('font-scale-label');
                if (label) label.textContent = data.label;
            } else {
                btn.className = 'theme-font-btn flex flex-col items-center justify-center rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-zinc-400 hover:text-zinc-100 hover:bg-zinc-700 transition-all cursor-pointer';
            }
        });

        // Update Radius Buttons
        const radiusMap = {
            '0.375rem': 'btn-radius-sm',
            '0.875rem': 'btn-radius-md',
            '1.25rem': 'btn-radius-lg',
        };
        Object.entries(radiusMap).forEach(([r, id]) => {
            const btn = document.getElementById(id);
            if (!btn) return;
            if (r === currentRadius) {
                btn.className = 'theme-radius-btn flex items-center justify-center gap-1.5 rounded-lg border-2 border-emerald-500 bg-emerald-500/15 p-2 text-xs font-semibold text-zinc-100 shadow-sm cursor-pointer';
            } else {
                btn.className = 'theme-radius-btn flex items-center justify-center gap-1.5 rounded-lg border border-zinc-700 bg-zinc-800/60 p-2 text-xs font-medium text-zinc-400 hover:bg-zinc-700 transition-all cursor-pointer';
            }
        });
    }
</script>
