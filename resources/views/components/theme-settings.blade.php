{{-- Dark / Light Mode Toggle Button --}}
<button
    id="theme-toggle-btn"
    type="button"
    onclick="toggleDarkMode()"
    title="Ganti mode tampilan"
    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-border bg-card text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer"
>
    {{-- Sun icon (shown in dark mode → click to go light) --}}
    <i id="theme-icon-sun" data-lucide="sun" class="h-3.5 w-3.5 hidden"></i>
    {{-- Moon icon (shown in light mode → click to go dark) --}}
    <i id="theme-icon-moon" data-lucide="moon" class="h-3.5 w-3.5 hidden"></i>
</button>

<script>
    function toggleDarkMode() {
        const isDark = document.documentElement.classList.contains('dark');
        const newMode = isDark ? 'light' : 'dark';
        localStorage.setItem('saans-mode', newMode);
        if (newMode === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        updateThemeToggleIcon();
    }

    function updateThemeToggleIcon() {
        const isDark = document.documentElement.classList.contains('dark');
        const sun  = document.getElementById('theme-icon-sun');
        const moon = document.getElementById('theme-icon-moon');
        if (!sun || !moon) return;
        // In dark mode show sun (to switch to light), in light mode show moon (to switch to dark)
        if (isDark) {
            sun.classList.remove('hidden');
            moon.classList.add('hidden');
        } else {
            sun.classList.add('hidden');
            moon.classList.remove('hidden');
        }
        if (window.lucide) window.lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', updateThemeToggleIcon);
</script>
