<?php
/**
 * 404 Not Found View
 */
?>
<div class="flex min-h-[80vh] items-center justify-center p-6">
    <div class="max-w-md w-full text-center space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700">
        <!-- Visual Illustration -->
        <div class="relative inline-block">
            <div class="text-[12rem] font-black text-slate-100 dark:text-slate-900/50 leading-none select-none font-outfit">
                404
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-24 w-24 rounded-3xl bg-primary/10 dark:bg-accent/10 flex items-center justify-center rotate-12 animate-bounce">
                    <i data-lucide="map-pin-off" class="h-12 w-12 text-primary dark:text-accent"></i>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white font-outfit tracking-tight">
                Lost in Finance?
            </h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                The page you're looking for has moved or vanished into the digital void.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="<?php echo BASE_URL; ?>/" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3.5 text-sm font-bold text-white shadow-xl shadow-primary/20 hover:bg-primary/90 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="home" class="mr-2 h-4 w-4"></i>
                Back to Home
            </a>
            <button onclick="window.history.back()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-8 py-3.5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200">
                <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
                Go Back
            </button>
        </div>

        <!-- Quick Links -->
        <div class="pt-12 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Popular Pages</p>
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
                <a href="<?php echo BASE_URL; ?>/dashboard" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-accent font-medium">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/blog" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-accent font-medium">Blog</a>
                <a href="<?php echo BASE_URL; ?>/help" class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-accent font-medium">Help Center</a>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
