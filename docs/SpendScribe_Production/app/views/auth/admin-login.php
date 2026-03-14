<div class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-slate-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 flex items-center justify-center px-4 py-12 transition-colors duration-300 relative overflow-hidden">
    
    <!-- Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-primary/10 dark:bg-primary/20 blur-[100px] animate-pulse"></div>
        <div class="absolute -right-16 -top-16 h-80 w-80 rounded-full bg-secondary/10 dark:bg-accent/10 blur-[120px] animate-pulse delay-500"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg g-12 items-center">
        <!-- Login Card -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="relative w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden animate-slide-in-right z-10">
                <div class="p-8 flex flex-col items-center space-y-4 text-center">
                    <div class="h-16 w-16 bg-primary/10 dark:bg-accent/10 rounded-2xl flex items-center justify-center border border-primary/20 dark:border-accent/20 mb-2 shadow-inner">
                        <i data-lucide="shield-check" class="h-8 w-8 text-primary dark:text-accent"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Admin Portal</h2>
                    <p class="text-gray-500 dark:text-slate-400 font-medium">
                        Secure access to the administration dashboard
                    </p>
                </div>
                
                <div class="p-8 pt-0">
                    <form action="<?php echo BASE_URL; ?>/admin-login" method="POST" class="space-y-5 text-left">
                        <?php if (isset($error)): ?>
                            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium flex items-center gap-2">
                                <i data-lucide="alert-circle" class="h-4 w-4 shrink-0"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest ml-1">
                                Administrator Email
                            </label>
                            <div class="relative group">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    placeholder="admin@spendscribe.com"
                                    class="w-full h-12 border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-slate-800/50 rounded-xl pl-12 pr-4 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between ml-1">
                                <label for="admin-password" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest">
                                    Admin Password
                                </label>
                            </div>
                            <div class="relative group">
                                <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="admin-password"
                                    name="password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full h-12 border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-slate-800/50 rounded-xl pl-12 pr-12 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                                <button type="button" onclick="togglePassword('admin-password', 'admin-eye')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-white transition-colors">
                                    <i id="admin-eye" data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full h-12 bg-primary dark:bg-accent text-white dark:text-slate-900 font-black uppercase tracking-widest text-sm rounded-xl hover:opacity-90 transition-all shadow-lg hover:-translate-y-0.5 active:translate-y-0 mt-6 flex items-center justify-center gap-2">
                            <i data-lucide="shield" class="h-4 w-4"></i>
                            Authenticate
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/5 text-center">
                        <p class="text-sm text-gray-600 dark:text-slate-400 font-medium">
                            <i data-lucide="arrow-left" class="inline-block h-3 w-3 mr-1 text-gray-400"></i>
                            Return to 
                            <a href="<?php echo BASE_URL; ?>/login" class="font-bold text-primary dark:text-accent hover:underline ml-1">
                                User Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
