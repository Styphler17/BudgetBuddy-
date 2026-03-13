<div class="relative min-h-screen overflow-hidden bg-slate-950 flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-slate-950 to-indigo-950"></div>
        <div class="absolute -left-24 top-1/3 h-72 w-72 rounded-full bg-purple-500/20 blur-3xl"></div>
        <div class="absolute -right-16 -top-16 h-80 w-80 rounded-full bg-indigo-500/20 blur-[140px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="w-full h-fit border border-white/20 bg-white/10 rounded-2xl shadow-[0_30px_60px_rgba(0,0,0,0.5)] backdrop-blur-2xl overflow-hidden">
            <div class="p-8 flex flex-col items-center space-y-4 text-center text-slate-100">
                <h2 class="text-2xl font-bold font-outfit">Admin Portal</h2>
                <p class="text-slate-200/80 text-sm">
                    Sign in to access the administration dashboard
                </p>
            </div>
            
            <div class="p-8 pt-0">
                <form action="<?php echo BASE_URL; ?>/admin-login" method="POST" class="space-y-4 text-left">
                    <?php if (isset($error)): ?>
                        <div class="p-3 rounded-md bg-red-500/20 border border-red-500/50 text-red-200 text-sm">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-100">
                            Admin Email
                        </label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-3 h-4 w-4 text-slate-300"></i>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="admin@system"
                                class="w-full h-10 border border-white/20 bg-white/85 rounded-md pl-10 text-slate-900 placeholder:text-slate-500 focus:ring-2 focus:ring-primary outline-none"
                                required
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="admin-password" class="text-sm font-medium text-slate-100">
                            Password
                        </label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-3 h-4 w-4 text-slate-300"></i>
                            <input
                                id="admin-password"
                                name="password"
                                type="password"
                                placeholder="Enter admin password"
                                class="w-full h-10 border border-white/20 bg-white/85 rounded-md pl-10 pr-10 text-slate-900 placeholder:text-slate-500 focus:ring-2 focus:ring-primary outline-none"
                                required
                            >
                            <button type="button" onclick="togglePassword('admin-password', 'admin-eye')" class="absolute right-3 top-3 text-slate-500 hover:text-slate-700 transition-colors">
                                <i id="admin-eye" data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full h-11 bg-primary hover:bg-primary/90 text-white font-bold rounded-md shadow-lg transition-all active:scale-95 mt-2"
                    >
                        Admin Sign In
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-200/85">
                        User login?
                        <a href="<?php echo BASE_URL; ?>/login" class="font-medium text-primary hover:text-white underline underline-offset-4">
                            Go to User Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
