<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-16 pt-32">
        <div class="container mx-auto max-w-4xl px-6 text-center">
            <nav class="mb-6 flex justify-center text-sm text-gray-500 dark:text-slate-400">
                <a href="/" class="hover:text-primary dark:hover:text-accent">Home</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 dark:text-white font-medium">Security</span>
            </nav>

            <div class="w-20 h-20 bg-primary/10 dark:bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shield-check" class="w-10 h-10 text-primary dark:text-accent"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                Security Infrastructure
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                Your financial data is protected by industry-leading security protocols and encryption standards.
            </p>
            <span class="inline-block px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold uppercase tracking-widest">
                Bank-Level Encryption
            </span>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="lock" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">Data Encryption</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">
                        We use AES-256 encryption to protect your data at rest and TLS 1.2+ for data in transit. Your sensitive information is never stored in plain text.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="user-check" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">Authentication</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">
                        Secure password hashing using bcrypt ensures your credentials remain safe even in the unlikely event of a data breach.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="eye-off" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">Privacy First</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">
                        We follow a privacy-by-design approach. We never sell your personal data to third parties or advertisers.
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">Regular Audits</h3>
                    <p class="text-gray-600 dark:text-slate-400 leading-relaxed">
                        Our systems undergo regular security audits and penetration testing to identify and patch vulnerabilities before they can be exploited.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reporting Section -->
    <section class="py-20 bg-gray-900 dark:bg-slate-950 text-white">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold mb-4 font-outfit">Found a security issue?</h2>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    We take security seriously. If you've discovered a vulnerability, please report it to our security team immediately.
                </p>
                <a href="/contact" class="inline-flex items-center justify-center px-8 py-3 bg-white dark:bg-accent text-gray-900 dark:text-primary font-bold rounded-xl hover:scale-105 transition-all shadow-xl dark:shadow-none">
                    Report Vulnerability
                </a>
            </div>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
