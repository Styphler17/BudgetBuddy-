<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-16 pt-32">
        <div class="container mx-auto max-w-4xl px-6 text-center">
            <nav class="mb-6 flex justify-center text-sm text-gray-500 dark:text-slate-400">
                <a href="<?php echo BASE_URL; ?>/" class="hover:text-primary dark:hover:text-accent">Home</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 dark:text-white font-medium">Privacy Policy</span>
            </nav>

            <div class="w-20 h-20 bg-primary/10 dark:bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shield" class="w-10 h-10 text-primary dark:text-accent"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                Privacy Policy
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                Your privacy and security are our top priorities. Learn how we protect
                and handle your personal and financial information.
            </p>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest">
                Last updated: March 2026
            </span>
        </div>
    </section>

    <!-- Overview Section -->
    <section class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm mb-12">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-6 font-outfit">
                    <i data-lucide="lock" class="w-6 h-6 text-primary dark:text-accent"></i>
                    Our Commitment to Privacy
                </h3>
                <div class="text-lg text-gray-600 dark:text-slate-400 space-y-4 leading-relaxed">
                    <p>
                        At <?php echo SITE_NAME; ?>, we believe your financial data belongs to you. We are committed to
                        protecting your privacy and ensuring the security of your personal information.
                        This privacy policy explains how we collect, use, and protect your data.
                    </p>
                    <p>
                        We use bank-level encryption and security measures to keep your information safe.
                        We never sell your data to third parties, and we only use it to provide you with
                        the best possible budgeting experience.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Sections -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                        Detailed Privacy Information
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-slate-400">
                        Here's a breakdown of how we handle different aspects of your privacy.
                    </p>
                </div>

                <div class="space-y-8">
                    <?php
                    $sections = [
                        [
                            "title" => "Information We Collect",
                            "content" => [
                                "Personal information you provide (name, email, account details)",
                                "Financial data you input (transactions, budgets, goals)",
                                "Usage data and analytics to improve our service",
                                "Device and browser information for security"
                            ]
                        ],
                        [
                            "title" => "How We Use Your Information",
                            "content" => [
                                "To provide and maintain your <?php echo SITE_NAME; ?> account",
                                "To process and display your financial data",
                                "To send important updates and notifications",
                                "To improve our services and develop new features",
                                "To ensure security and prevent fraud"
                            ]
                        ],
                        [
                            "title" => "Information Sharing",
                            "content" => [
                                "We never sell your personal information to third parties",
                                "Data is only shared with service providers who help us operate",
                                "We may share anonymized, aggregated data for analytics",
                                "Legal requirements may compel us to share data when required by law"
                            ]
                        ],
                        [
                            "title" => "Data Security",
                            "content" => [
                                "Bank-level encryption protects your data in transit and at rest",
                                "Regular security audits and updates",
                                "Access controls and authentication requirements",
                                "Secure data centers with physical and digital protections"
                            ]
                        ]
                    ];

                    foreach ($sections as $section): ?>
                        <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 border-l-4 border-l-emerald-500 dark:border-l-emerald-500 shadow-sm">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 font-outfit"><?php echo $section['title']; ?></h3>
                            <ul class="space-y-3">
                                <?php foreach ($section['content'] as $item): ?>
                                    <li class="flex items-start gap-3">
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-2 shrink-0"></div>
                                        <span class="text-gray-600 dark:text-slate-400"><?php echo $item; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-white dark:bg-slate-950">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-2xl mx-auto">
                <i data-lucide="eye" class="w-16 h-16 text-primary dark:text-accent mx-auto mb-6"></i>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                    Questions About Privacy?
                </h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 mb-8 leading-relaxed">
                    If you have any questions about our privacy policy or how we handle your data,
                    please don't hesitate to contact us.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <?php 
                        $text = 'Contact Us';
                        $type = 'a';
                        $href = BASE_URL . '/contact';
                        $variant = 'secondary';
                        $size = 'md';
                        $icon = 'arrow-right';
                        $class = 'bg-emerald-500 hover:bg-emerald-600 text-white border-none';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                    <a href="mailto:privacy@SpendScribe.com" class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                        Email Privacy Team
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
