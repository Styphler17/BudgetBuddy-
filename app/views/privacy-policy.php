<div class="min-h-screen bg-white">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 py-16 pt-32">
        <div class="container mx-auto max-w-4xl px-6 text-center">
            <nav class="mb-6 flex justify-center text-sm text-gray-500">
                <a href="/BudgetBuddy-/" class="hover:text-primary">Home</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 font-medium">Privacy Policy</span>
            </nav>

            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shield" class="w-10 h-10 text-primary"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 font-outfit">
                Privacy Policy
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                Your privacy and security are our top priorities. Learn how we protect
                and handle your personal and financial information.
            </p>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest">
                Last updated: December 2024
            </span>
        </div>
    </section>

    <!-- Overview Section -->
    <section class="container mx-auto px-6 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm mb-12">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-3 mb-6 font-outfit">
                    <i data-lucide="lock" class="w-6 h-6 text-primary"></i>
                    Our Commitment to Privacy
                </h3>
                <div class="text-lg text-gray-600 space-y-4 leading-relaxed">
                    <p>
                        At BudgetBuddy, we believe your financial data belongs to you. We are committed to
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
    <section class="bg-gray-50 py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit">
                        Detailed Privacy Information
                    </h2>
                    <p class="text-lg text-gray-600">
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
                                "To provide and maintain your BudgetBuddy account",
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
                        <div class="bg-white p-8 rounded-xl border border-gray-200 border-l-4 border-l-emerald-500 shadow-sm">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 font-outfit"><?php echo $section['title']; ?></h3>
                            <ul class="space-y-3">
                                <?php foreach ($section['content'] as $item): ?>
                                    <li class="flex items-start gap-3">
                                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-2 shrink-0"></div>
                                        <span class="text-gray-600"><?php echo $item; ?></span>
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
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-2xl mx-auto">
                <i data-lucide="eye" class="w-16 h-16 text-primary mx-auto mb-6"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit">
                    Questions About Privacy?
                </h2>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    If you have any questions about our privacy policy or how we handle your data,
                    please don't hesitate to contact us.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/BudgetBuddy-/contact" class="inline-flex items-center justify-center px-8 py-3 bg-emerald-500 text-white font-bold rounded-md hover:bg-emerald-600 transition-colors">
                        Contact Us
                        <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                    </a>
                    <a href="mailto:privacy@budgetbuddy.com" class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 bg-white text-gray-700 font-bold rounded-md hover:bg-gray-50 transition-colors">
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