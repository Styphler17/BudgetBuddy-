<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 flex items-center justify-center px-4 py-12 transition-colors duration-300">
    <div class="max-w-md w-full glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-2xl overflow-hidden p-8 text-center relative z-10">
            <div class="h-20 w-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 text-primary dark:text-accent">
                <i data-lucide="shield-check" class="h-10 w-10"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit mb-2">Two-Factor Auth</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mb-8">
                Enter the 6-digit code from your authenticator app.
            </p>

            <form action="<?php echo BASE_URL; ?>/login/2fa" method="POST" class="space-y-6">
                <?php echo BaseController::csrfField(); ?>
                <?php if (isset($error)): ?>
                    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 text-xs font-medium">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="space-y-2">
                    <input
                        type="text"
                        name="code"
                        placeholder="000000"
                        maxlength="6"
                        class="w-full h-14 text-center text-2xl tracking-[0.5em] font-bold border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all transform active:scale-95 shadow-lg shadow-primary/20">
                    Verify & Sign In
                </button>
            </form>

            <div class="mt-8">
                <a href="<?php echo BASE_URL; ?>/login" class="text-sm font-bold text-gray-400 hover:text-primary transition-colors">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
