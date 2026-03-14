<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-16 pt-32">
        <div class="container mx-auto max-w-4xl px-6 text-center">
            <nav class="mb-6 flex justify-center text-sm text-gray-500 dark:text-slate-400">
                <a href="<?php echo BASE_URL; ?>/" class="hover:text-primary dark:hover:text-accent">Home</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 dark:text-white font-medium">Cookie Policy</span>
            </nav>

            <div class="w-20 h-20 bg-primary/10 dark:bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="cookie" class="w-10 h-10 text-primary dark:text-accent"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                Cookie Policy
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                We use cookies to enhance your experience and ensure the security of your account. 
                Learn more about how we use them.
            </p>
            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-bold uppercase tracking-widest">
                Last updated: March 2026
            </span>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto space-y-12">
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">What are cookies?</h3>
                    <p class="text-lg text-gray-600 dark:text-slate-400 leading-relaxed">
                        Cookies are small text files that are stored on your device when you visit a website. They help us remember your preferences, keep you logged in, and understand how you use our service.
                    </p>
                </div>
            </div>

            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">How we use them</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Essential Cookies</h4>
                            <p class="text-gray-600 dark:text-slate-400 text-sm">Necessary for the website to function. They enable core features like security and session management.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Preference Cookies</h4>
                            <p class="text-gray-600 dark:text-slate-400 text-sm">Allow us to remember your settings, such as your preferred currency and theme.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Performance Cookies</h4>
                            <p class="text-gray-600 dark:text-slate-400 text-sm">Help us understand how visitors interact with our site by collecting anonymous usage data.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">No Tracking</h4>
                            <p class="text-gray-600 dark:text-slate-400 text-sm">We do not use cookies for targeted advertising or selling your data to third parties.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">Managing Cookies</h3>
                    <p class="text-lg text-gray-600 dark:text-slate-400 leading-relaxed mb-4">
                        Most web browsers allow you to control cookies through their settings. However, disabling essential cookies may limit your ability to use certain features of SpendScribe.
                    </p>
                    <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-lg text-sm text-gray-500 dark:text-slate-500 italic">
                        Note: Your session token is stored in a secure cookie to keep you logged in during your visit.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();

        // Glowing Effect Controller
        const wrappers = document.querySelectorAll('.glowing-wrapper');
        const proximity = 100;

        const handlePointerMove = (e) => {
            wrappers.forEach(wrapper => {
                const container = wrapper.querySelector('.glowing-effect-container');
                const rect = wrapper.getBoundingClientRect();
                const mouseX = e.clientX;
                const mouseY = e.clientY;
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;

                const isActive = 
                    mouseX > rect.left - proximity &&
                    mouseX < rect.right + proximity &&
                    mouseY > rect.top - proximity &&
                    mouseY < rect.bottom + proximity;

                if (isActive) {
                    container.style.setProperty('--active', '1');
                    const angle = Math.atan2(mouseY - centerY, mouseX - centerX) * (180 / Math.PI) + 90;
                    container.style.setProperty('--start', angle);
                } else {
                    container.style.setProperty('--active', '0');
                }
            });
        };

        window.addEventListener('pointermove', handlePointerMove);
    });
</script>
