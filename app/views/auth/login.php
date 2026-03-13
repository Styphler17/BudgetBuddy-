<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 flex items-center justify-center px-4 py-12 transition-colors duration-300">
    <div class="relative w-full max-w-6xl grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:gap-16 items-center">
        
        <!-- Left Side: Content -->
        <div class="flex flex-col justify-center space-y-10 text-center lg:text-left relative">
            <!-- Decorative Mockup Element -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/5 dark:bg-accent/5 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            
            <div class="flex flex-col items-center gap-6 lg:items-start relative z-10">
                <div class="max-w-xl space-y-6">
                    <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="SpendScribe Logo" class="h-12 w-auto object-contain animate-fade-in">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight text-gray-900 dark:text-white font-outfit">
                        Empower your finances with <span class="text-primary dark:text-accent italic">clarity</span> and confidence.
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-slate-300 leading-relaxed font-medium">
                        SpendScribe helps you visualize spending, plan smarter budgets, and stay in control with real-time insights designed for peace of mind.
                    </p>

                    <div class="flex flex-wrap gap-3 pt-4 justify-center lg:justify-start font-bold">
                        <div class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 py-2 text-xs shadow-sm border border-gray-100 dark:border-white/5 text-gray-700 dark:text-slate-300 hover-lift transition-all">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-500"></i>
                            Analytics
                        </div>
                        <div class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 py-2 text-xs shadow-sm border border-gray-100 dark:border-white/5 text-gray-700 dark:text-slate-300 hover-lift transition-all">
                            <i data-lucide="target" class="w-4 h-4 text-green-500"></i>
                            Goals
                        </div>
                        <div class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 py-2 text-xs shadow-sm border border-gray-100 dark:border-white/5 text-gray-700 dark:text-slate-300 hover-lift transition-all">
                            <i data-lucide="globe" class="w-4 h-4 text-orange-500"></i>
                            Multi-currency
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Stats Widget Mockup -->
            <div class="hidden lg:block relative h-32 animate-float">
                <div class="absolute top-0 left-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-6 rounded-3xl border border-white dark:border-white/10 shadow-2xl shadow-primary/10 w-64 rotate-[-2deg]">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400">
                            <i data-lucide="trending-up" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Efficiency</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">92.4%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto flex w-fit flex-col items-start gap-2 rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-white/5 p-6 text-sm font-medium text-gray-700 dark:text-slate-300 shadow-sm xl:mx-0 max-w-sm text-left relative z-10">
                <div class="flex gap-1 text-yellow-400 text-lg">
                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                </div>
                <span class="text-gray-600 dark:text-slate-400 leading-relaxed italic">
                    "SpendScribe completely changed how I manage my finances. The visual analytics make it incredibly easy to see exactly where my money is going."
                </span>
                <span class="text-primary dark:text-accent font-bold mt-1">— Sarah Jenkins, Designer</span>
            </div>
        </div>

        <!-- Right Side: Login Card -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="relative w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden animate-slide-in-right z-10">
                <div class="p-8 flex flex-col items-center space-y-4 text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Welcome Back</h2>
                    <p class="text-gray-500 dark:text-slate-400 font-medium">
                        Sign in to your SpendScribe account
                    </p>
                </div>
                
                <div class="p-8 pt-0">
                    <!-- Google OAuth Option -->
                    <?php 
                        $text = 'Continue with Google';
                        $type = 'button';
                        $variant = 'outline';
                        $size = 'md';
                        $class = 'w-full h-12 rounded-xl mb-6 dark:border-white/10 dark:text-white dark:hover:bg-white/5';
                        $attr = 'onclick="alert(\'Google Login is coming soon!\')"';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>

                    <div class="relative flex items-center gap-4 mb-6">
                        <div class="h-px flex-grow bg-gray-100 dark:bg-white/5"></div>
                        <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Or email</span>
                        <div class="h-px flex-grow bg-gray-100 dark:bg-white/5"></div>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/login" method="POST" class="space-y-5 text-left">
                        <?php if (isset($error)): ?>
                            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest ml-1">
                                Email Address
                            </label>
                            <div class="relative group">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    placeholder="name@example.com"
                                    class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl pl-12 pr-4 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between ml-1">
                                <label for="password" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">
                                    Password
                                </label>
                                <a
                                    href="<?php echo BASE_URL; ?>/forgot-password"
                                    class="text-xs font-bold text-primary dark:text-accent hover:underline"
                                >
                                    Forgot?
                                </a>
                            </div>
                            <div class="relative group">
                                <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="login-password"
                                    name="password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full h-12 border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl pl-12 pr-12 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                                <button type="button" onclick="togglePassword('login-password', 'login-eye')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-white transition-colors">
                                    <i id="login-eye" data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-1 pb-2 ml-1">
                            <input
                                type="checkbox"
                                id="remember"
                                class="h-4 w-4 rounded border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 text-primary dark:text-accent focus:ring-primary dark:focus:ring-accent"
                            />
                            <label for="remember" class="text-sm font-bold text-gray-600 dark:text-slate-400">
                                Remember me for 30 days
                            </label>
                        </div>

                        <?php 
                            $text = 'Sign In';
                            $type = 'submit';
                            $variant = 'primary';
                            $size = 'md';
                            $icon = 'arrow-right';
                            $class = 'w-full h-12 rounded-xl';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600 dark:text-slate-400 font-medium">
                            Don't have an account?
                            <a href="<?php echo BASE_URL; ?>/register" class="font-bold text-primary dark:text-accent hover:underline ml-1">
                                Create one free
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
