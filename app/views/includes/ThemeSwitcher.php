<!-- Theme Switcher Component -->
<div class="relative inline-flex items-center bg-gray-100/80 dark:bg-slate-800/80 backdrop-blur-sm p-1 rounded-xl border border-gray-200 dark:border-white/10" id="theme-switcher">
    <!-- Active Indicator -->
    <div id="theme-indicator" class="absolute h-8 w-8 border border-primary/20 dark:border-accent/50 rounded-lg transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)] shadow-sm"></div>
    
    <!-- Options -->
    <button data-theme="system" title="System Theme" class="relative z-10 flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
        <i data-lucide="monitor-cog" class="h-4 w-4"></i>
    </button>
    <button data-theme="light" title="Light Theme" class="relative z-10 flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
        <i data-lucide="sun" class="h-4 w-4"></i>
    </button>
    <button data-theme="dark" title="Dark Theme" class="relative z-10 flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
        <i data-lucide="moon-star" class="h-4 w-4"></i>
    </button>
</div>

<script>
const ThemeSwitcher = {
    switcher: null,
    indicator: null,
    buttons: null,

    init() {
        this.switcher = document.getElementById('theme-switcher');
        if (!this.switcher) return;
        
        this.indicator = document.getElementById('theme-indicator');
        this.buttons = this.switcher.querySelectorAll('button[data-theme]');

        const savedTheme = localStorage.getItem('theme-mode') || 'system';
        this.applyTheme(savedTheme, false);

        this.buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const theme = btn.getAttribute('data-theme');
                this.applyTheme(theme, true);
            });
        });

        // Watch for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (localStorage.getItem('theme-mode') === 'system' || !localStorage.getItem('theme-mode')) {
                this.updateDocumentClass('system');
            }
        });
    },

    applyTheme(theme, save = true) {
        if (save) localStorage.setItem('theme-mode', theme);
        
        // Update document
        this.updateDocumentClass(theme);

        // Update UI
        this.buttons.forEach((btn, index) => {
            if (btn.getAttribute('data-theme') === theme) {
                btn.classList.remove('text-gray-500', 'dark:text-slate-400');
                btn.classList.add('text-primary', 'dark:text-accent', 'font-bold');
                
                // Move indicator
                const offset = 4 + (index * 32);
                this.indicator.style.left = `${offset}px`;
            } else {
                btn.classList.add('text-gray-500', 'dark:text-slate-400');
                btn.classList.remove('text-primary', 'dark:text-accent', 'font-bold');
            }
        });
    },

    updateDocumentClass(theme) {
        const isDark = theme === 'dark' || 
            (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        if (isDark) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
        
        // Broadcast for other components (like charts)
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme, isDark } }));
    }
};

document.addEventListener('DOMContentLoaded', () => {
    ThemeSwitcher.init();
    // Initialize icons for this component specifically if needed
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>