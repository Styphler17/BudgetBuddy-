<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 flex items-center justify-center px-4 py-12 transition-colors duration-300">
    <div class="relative w-full max-w-6xl grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,420px)] lg:gap-16 items-center">
        
        <!-- Left Side -->
        <div class="flex flex-col justify-center space-y-10 text-center lg:text-left relative">
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-primary/5 dark:bg-accent/5 rounded-full blur-3xl pointer-events-none animate-pulse"></div>

            <div class="flex flex-col items-center gap-6 lg:items-start relative z-10">
                <div class="max-w-xl space-y-6">
                    <img src="<?php echo BASE_URL; ?>/public/<?php echo SITE_NAME; ?>.png" alt="<?php echo SITE_NAME; ?> Logo" class="h-12 w-auto object-contain animate-fade-in">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight text-gray-900 dark:text-white font-outfit">
                        Start building better money habits with <span class="text-primary dark:text-accent italic">tailored</span> insights.
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-slate-300 leading-relaxed font-medium">
                        Create your account to unlock goal tracking, budgeting dashboards, and smart recommendations.
                    </p>
                </div>
            </div>

            <!-- Visual Checklist of Benefits -->
            <div class="grid gap-4 max-w-md mx-auto lg:mx-0 relative z-10">
                <div class="flex items-center gap-3 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm p-4 rounded-2xl border border-white dark:border-white/10 shadow-sm hover-lift transition-all">
                    <div class="h-8 w-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-400">
                        <i data-lucide="check" class="h-5 w-5"></i>
                    </div>
                    <span class="font-bold text-gray-700 dark:text-slate-300">Free forever plan</span>
                </div>
                <div class="flex items-center gap-3 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm p-4 rounded-2xl border border-white dark:border-white/10 shadow-sm hover-lift transition-all">
                    <div class="h-8 w-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-400">
                        <i data-lucide="check" class="h-5 w-5"></i>
                    </div>
                    <span class="font-bold text-gray-700 dark:text-slate-300">No credit card required</span>
                </div>
                <div class="flex items-center gap-3 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm p-4 rounded-2xl border border-white dark:border-white/10 shadow-sm hover-lift transition-all">
                    <div class="h-8 w-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-400">
                        <i data-lucide="check" class="h-5 w-5"></i>
                    </div>
                    <span class="font-bold text-gray-700 dark:text-slate-300">Cancel anytime</span>
                </div>
            </div>

            <!-- Decorative Mockup (Optional, keeping small version) -->
            <div class="hidden lg:block relative h-12">
                <div class="absolute -top-10 left-10 bg-white/40 dark:bg-slate-800/40 backdrop-blur-md p-4 rounded-xl border border-white/50 dark:border-white/10 shadow-sm w-48 rotate-[2deg] opacity-50">
                    <div class="h-2 w-24 bg-gray-200 dark:bg-slate-700 rounded-full mb-2"></div>
                    <div class="h-2 w-16 bg-gray-100 dark:bg-slate-600 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Card -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="relative w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden animate-slide-up z-10">
                <div class="p-8 flex flex-col items-center space-y-3 text-center border-b border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-slate-800/30">
                    <div class="px-3 py-1 rounded-full bg-primary/10 dark:bg-accent/10 text-[10px] font-black uppercase tracking-[0.2em] text-primary dark:text-accent mb-2">
                        Step 1 of 2 — Account Details
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Create Account</h2>
                    <p class="text-gray-500 dark:text-slate-300 font-medium">
                        Join <?php echo SITE_NAME; ?> to start managing your finances
                    </p>
                </div>

                <div class="p-8 pt-6">
                    <!-- Google OAuth Option -->
                    <?php 
                        $text = 'Sign up with Google';
                        $type = 'button';
                        $variant = 'outline';
                        $size = 'md';
                        $class = 'w-full h-12 rounded-xl mb-6 dark:border-white/10 dark:text-white dark:hover:bg-white/5';
                        $attr = 'onclick="alert(\'Google Sign Up is coming soon!\')"';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>

                    <div class="relative flex items-center gap-4 mb-6">
                        <div class="h-px flex-grow bg-gray-100 dark:bg-white/5"></div>
                        <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Or email</span>
                        <div class="h-px flex-grow bg-gray-100 dark:bg-white/5"></div>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/register" method="POST" class="space-y-4 text-left">
                        <?php echo BaseController::csrfField(); ?>
                        <?php if (isset($error)): ?>
                            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-2">
                            <label for="name" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest ml-1">
                                Full Name
                            </label>
                            <div class="relative group">
                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Enter your full name"
                                    class="w-full h-12 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl pl-12 pr-4 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest ml-1">
                                Email
                            </label>
                            <div class="relative group">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    placeholder="Enter your email"
                                    class="w-full h-12 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl pl-12 pr-4 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="reg-password" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest ml-1">
                                Password
                            </label>
                            <div class="relative group">
                                <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                <input
                                    id="reg-password"
                                    name="password"
                                    type="password"
                                    placeholder="Create a password"
                                    class="w-full h-12 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-xl pl-12 pr-12 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-slate-500 focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all font-medium"
                                    onkeyup="checkPasswordStrength(this.value)"
                                    required
                                >
                                <button type="button" onclick="togglePassword('reg-password', 'reg-eye-1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-white transition-colors">
                                    <i id="reg-eye-1" data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                            </div>
                            <!-- Password Strength Indicator -->
                            <div class="mt-2 px-1">
                                <div class="h-1.5 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden flex gap-1">
                                    <div id="strength-bar-1" class="h-full w-1/3 bg-gray-200 dark:bg-slate-700 transition-all duration-500"></div>
                                    <div id="strength-bar-2" class="h-full w-1/3 bg-gray-200 dark:bg-slate-700 transition-all duration-500"></div>
                                    <div id="strength-bar-3" class="h-full w-1/3 bg-gray-200 dark:bg-slate-700 transition-all duration-500"></div>
                                </div>
                                <p id="strength-text" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mt-1.5">Strength: None</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="currency" class="text-xs font-bold text-gray-500 dark:text-slate-300 uppercase tracking-widest ml-1">
                                Preferred Currency
                            </label>
                            <div class="relative group">
                                <i data-lucide="coins" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                                                        <select 
                                                            id="currency" 
                                                            name="currency"
                                                            class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl pl-12 pr-10 text-gray-900 dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none appearance-none bg-white dark:bg-slate-800 font-medium transition-all"
                                                        >
                                                            <option value="USD">USD ($) - US Dollar</option>
                                                            <option value="EUR">EUR (€) - Euro</option>
                                                            <option value="GBP">GBP (£) - British Pound</option>
                                                            <option value="JPY">JPY (¥) - Japanese Yen</option>
                                                            <option value="CAD">CAD ($) - Canadian Dollar</option>
                                                            <option value="AUD">AUD ($) - Australian Dollar</option>
                                                            <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                                                            <option value="NGN">NGN (₦) - Nigerian Naira</option>
                                                        </select>                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="flex items-start space-x-3 pt-2 ml-1">
                            <input
                                type="checkbox"
                                id="terms"
                                name="terms"
                                class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 text-primary dark:text-accent focus:ring-primary dark:focus:ring-accent"
                                required
                            />
                            <label for="terms" class="text-xs font-medium text-gray-600 dark:text-slate-300 leading-relaxed">
                                I agree to the <a href="<?php echo BASE_URL; ?>/terms" class="text-primary dark:text-accent hover:underline font-bold">Terms of Service</a> and <a href="<?php echo BASE_URL; ?>/privacy-policy" class="text-primary dark:text-accent hover:underline font-bold">Privacy Policy</a>.
                            </label>
                        </div>

                        <?php 
                            $text = 'Create Account';
                            $type = 'submit';
                            $variant = 'primary';
                            $size = 'md';
                            $class = 'w-full h-12 rounded-xl mt-4';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600 dark:text-slate-300 font-medium font-body">
                            Already have an account?
                            <a href="<?php echo BASE_URL; ?>/login" class="font-bold text-primary dark:text-accent hover:underline ml-1">
                                Sign in instead
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkPasswordStrength(password) {
        const bar1 = document.getElementById('strength-bar-1');
        const bar2 = document.getElementById('strength-bar-2');
        const bar3 = document.getElementById('strength-bar-3');
        const text = document.getElementById('strength-text');
        
        let strength = 0;
        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/) || password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Reset
        [bar1, bar2, bar3].forEach(b => {
            b.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
            b.classList.add('bg-gray-200');
        });
        
        if (strength === 0) {
            text.innerText = 'Strength: None';
            text.className = 'text-[10px] font-bold uppercase tracking-widest text-gray-400 mt-1.5';
        } else if (strength === 1) {
            bar1.classList.add('bg-red-500'); bar1.classList.remove('bg-gray-200');
            text.innerText = 'Strength: Weak';
            text.className = 'text-[10px] font-bold uppercase tracking-widest text-red-500 mt-1.5';
        } else if (strength === 2) {
            bar1.classList.add('bg-yellow-500'); bar1.classList.remove('bg-gray-200');
            bar2.classList.add('bg-yellow-500'); bar2.classList.remove('bg-gray-200');
            text.innerText = 'Strength: Fair';
            text.className = 'text-[10px] font-bold uppercase tracking-widest text-yellow-500 mt-1.5';
        } else if (strength === 3) {
            [bar1, bar2, bar3].forEach(b => { b.classList.add('bg-green-500'); b.classList.remove('bg-gray-200'); });
            text.innerText = 'Strength: Strong';
            text.className = 'text-[10px] font-bold uppercase tracking-widest text-green-500 mt-1.5';
        }
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
