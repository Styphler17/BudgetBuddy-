<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 flex items-center justify-center px-4 py-12 transition-colors duration-300">
    <div class="max-w-md w-full glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-2xl overflow-hidden p-8 text-center relative z-10">
            <div class="h-20 w-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-green-600 dark:text-green-400">
                <i data-lucide="mail-check" class="h-10 w-10"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit mb-4">Check your email</h2>
            <p class="text-gray-600 dark:text-slate-300 mb-8 leading-relaxed">
                We've sent a verification link to <span class="font-bold text-primary dark:text-accent"><?php echo htmlspecialchars($email); ?></span>. Please click the link to activate your account.
            </p>
            <div class="space-y-4">
                <a href="<?php echo BASE_URL; ?>/login" class="block w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-colors">
                    Back to Login
                </a>
                <p class="text-xs text-gray-400 dark:text-slate-500">
                    Didn't receive the email? Check your spam folder or try again later.
                </p>
            </div>
        </div>
    </div>
</div>
