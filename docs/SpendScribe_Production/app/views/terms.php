<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-16 pt-32">
        <div class="container mx-auto max-w-4xl px-6 text-center">
            <nav class="mb-6 flex justify-center text-sm text-gray-500 dark:text-slate-400">
                <a href="<?php echo BASE_URL; ?>/" class="hover:text-primary dark:hover:text-accent">Home</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 dark:text-white font-medium">Terms of Service</span>
            </nav>

            <div class="w-20 h-20 bg-primary/10 dark:bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="file-text" class="w-10 h-10 text-primary dark:text-accent"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                Terms of Service
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                Please read these terms carefully before using <?php echo SITE_NAME; ?>. By accessing or using our service, 
                you agree to be bound by these terms.
            </p>
            <span class="inline-block px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-bold uppercase tracking-widest">
                Last updated: March 2026
            </span>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto space-y-12">
            <!-- Section 1 -->
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">1. Acceptance of Terms</h3>
                    <div class="text-lg text-gray-600 dark:text-slate-400 space-y-4 leading-relaxed">
                        <p>
                            By creating an account or using the <?php echo SITE_NAME; ?> platform, you agree to comply with and be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 2 -->
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">2. User Accounts</h3>
                    <div class="text-lg text-gray-600 dark:text-slate-400 space-y-4 leading-relaxed">
                        <p>
                            You are responsible for maintaining the confidentiality of your account credentials. You agree to notify us immediately of any unauthorized use of your account. <?php echo SITE_NAME; ?> cannot and will not be liable for any loss or damage arising from your failure to protect your account.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 3 -->
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">3. Service Usage</h3>
                    <div class="text-lg text-gray-600 dark:text-slate-400 space-y-4 leading-relaxed">
                        <p>
                            Our service is designed to help you track your personal finances. You agree not to use the service for any illegal purposes or to interfere with the proper working of the platform. We reserve the right to suspend accounts that violate our usage policies.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section 4 -->
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm z-10">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 font-outfit">4. Limitation of Liability</h3>
                    <div class="text-lg text-gray-600 dark:text-slate-400 space-y-4 leading-relaxed">
                        <p>
                            <?php echo SITE_NAME; ?> provides tools for financial tracking, but we do not provide professional financial advice. You are solely responsible for your financial decisions. <?php echo SITE_NAME; ?> shall not be liable for any direct or indirect damages resulting from your use of the service.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Section -->
    <section class="py-20 bg-gray-50 dark:bg-slate-900/50">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                    Questions About Our Terms?
                </h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 mb-8 leading-relaxed">
                    If you have any questions regarding these terms, please reach out to our support team.
                </p>
                <a href="<?php echo BASE_URL; ?>/contact" class="inline-flex items-center justify-center px-8 py-3 bg-primary dark:bg-accent text-white dark:text-primary font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 dark:shadow-none hover:scale-105">
                    Contact Support
                    <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                </a>
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
