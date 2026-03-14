<?php
/**
 * 500 Internal Server Error View
 */
?>
<div class="flex min-h-[80vh] items-center justify-center p-6">
    <div class="max-w-md w-full text-center space-y-8 animate-in fade-in slide-in-from-bottom-10 duration-700">
        <!-- Visual Illustration -->
        <div class="relative inline-block">
            <div class="text-[12rem] font-black text-rose-50 dark:text-rose-950/20 leading-none select-none font-outfit">
                500
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-24 w-24 rounded-3xl bg-rose-500/10 dark:bg-rose-500/10 flex items-center justify-center -rotate-12 animate-pulse">
                    <i data-lucide="server-off" class="h-12 w-12 text-rose-600 dark:text-rose-500"></i>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white font-outfit tracking-tight">
                System Glitch!
            </h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                Something went wrong on our end. Our specialized finance team is investigating the audit trail.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="<?php echo BASE_URL; ?>/" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-slate-900 dark:bg-white dark:text-slate-900 px-8 py-3.5 text-sm font-bold text-white shadow-xl hover:bg-slate-800 dark:hover:bg-slate-100 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="refresh-cw" class="mr-2 h-4 w-4"></i>
                Try Refreshing
            </a>
            <a href="<?php echo BASE_URL; ?>/help" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-8 py-3.5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200">
                <i data-lucide="life-buoy" class="mr-2 h-4 w-4"></i>
                Support Center
            </a>
        </div>

        <!-- Debug Info (Optional) -->
        <?php if (defined('APP_ENV') && APP_ENV === 'development' && isset($error_message)): ?>
        <div class="mt-8 p-4 bg-slate-100 dark:bg-slate-900 rounded-lg text-left overflow-x-auto border border-slate-200 dark:border-slate-800">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Development Details</p>
            <code class="text-xs text-rose-600 dark:text-rose-400 font-mono">
                <?php echo htmlspecialchars($error_message); ?>
            </code>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
