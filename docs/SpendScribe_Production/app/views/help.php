<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-20 text-center pt-32 bg-gray-50/50 dark:bg-slate-900/50 rounded-b-[3rem]">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                How can we help you?
            </h1>
            <p class="text-xl text-gray-600 dark:text-slate-300 mb-8 font-medium">
                Find answers, get support, and learn everything you need to know about <?php echo SITE_NAME; ?>.
            </p>
            <div class="max-w-md mx-auto relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-slate-500 w-5 h-5 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                <input
                    type="text"
                    placeholder="Search for help..."
                    class="w-full pl-12 pr-4 py-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-2xl shadow-sm focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary dark:focus:border-accent outline-none transition-all dark:text-white font-medium"
                >
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mx-auto px-4 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                Browse by Category
            </h2>
            <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">
                Find the help you need organized by topic.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $categories = [
                [
                    "icon" => "book",
                    "title" => "Getting Started",
                    "description" => "Learn the basics of <?php echo SITE_NAME; ?>",
                    "articles" => ["Creating your account", "Adding your first transaction", "Setting up budgets"]
                ],
                [
                    "icon" => "credit-card",
                    "title" => "Transactions",
                    "description" => "Manage your income and expenses",
                    "articles" => ["Adding transactions", "Categorizing expenses", "Importing data"]
                ],
                [
                    "icon" => "target",
                    "title" => "Budgeting",
                    "description" => "Set and track financial goals",
                    "articles" => ["Creating budgets", "Budget categories", "Tracking progress"]
                ],
                [
                    "icon" => "pie-chart",
                    "title" => "Analytics",
                    "description" => "Understanding your financial data",
                    "articles" => ["Reading reports", "Spending insights", "Trend analysis"]
                ],
                [
                    "icon" => "trending-up",
                    "title" => "Accounts",
                    "description" => "Manage multiple accounts",
                    "articles" => ["Adding accounts", "Account types", "Balance tracking"]
                ],
                [
                    "icon" => "shield",
                    "title" => "Security",
                    "description" => "Keep your data safe",
                    "articles" => ["Data encryption", "Account privacy", "Two-factor auth"]
                ]
            ];

            foreach ($categories as $cat): ?>
                <div class="glowing-wrapper">
                    <div class="glowing-effect-container"></div>
                    <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-200 dark:border-white/10 shadow-sm transition-all h-full z-10 cursor-pointer group">
                        <div class="w-12 h-12 bg-primary/10 dark:bg-accent/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary dark:group-hover:bg-accent transition-colors">
                            <i data-lucide="<?php echo $cat['icon']; ?>" class="w-6 h-6 text-primary dark:text-accent group-hover:text-white dark:group-hover:text-primary transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-outfit"><?php echo $cat['title']; ?></h3>
                        <p class="text-gray-500 dark:text-slate-400 mb-6 text-sm font-medium"><?php echo $cat['description']; ?></p>
                        <ul class="space-y-3">
                            <?php foreach ($cat['articles'] as $article): ?>
                                <li class="text-sm text-gray-600 dark:text-slate-400 hover:text-primary dark:hover:text-accent transition-colors cursor-pointer flex items-center gap-2 font-medium">
                                    <span class="w-1 h-1 bg-gray-300 dark:bg-slate-700 rounded-full"></span>
                                    <?php echo $article; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                    Frequently Asked Questions
                </h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 font-medium">
                    Quick answers to common questions.
                </p>
            </div>
            <div class="max-w-3xl mx-auto space-y-4">
                <?php
                $faqs = [
                    [
                        "question" => "How do I add a new transaction?",
                        "answer" => "Go to the Transactions page and click the 'Add' button. Fill in the details including amount, category, and date."
                    ],
                    [
                        "question" => "Can I import transactions from my bank?",
                        "answer" => "Yes! We support CSV import. Go to Settings > Import Data to upload your transaction history."
                    ],
                    [
                        "question" => "How do I set up a budget?",
                        "answer" => "Navigate to the Goals page and click 'Create Budget'. Set your spending limit and time period."
                    ],
                    [
                        "question" => "Is my financial data secure?",
                        "answer" => "Absolutely. We use bank-level encryption and never share your personal financial information."
                    ]
                ];

                foreach ($faqs as $i => $faq): ?>
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
                        <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-800 transition-all">
                            <span class="font-bold text-gray-900 dark:text-white"><?php echo $faq['question']; ?></span>
                            <i data-lucide="chevron-down" id="faq-icon-<?php echo $i; ?>" class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-transform duration-300"></i>
                        </button>
                        <div id="faq-answer-<?php echo $i; ?>" class="hidden px-8 pb-6 text-gray-600 dark:text-slate-400 leading-relaxed font-medium">
                            <?php echo $faq['answer']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Support Section -->
    <section class="py-24 bg-white dark:bg-slate-950">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-outfit">
                    Still Need Help?
                </h2>
                <p class="text-lg text-gray-600 dark:text-slate-400 mb-12 font-medium">
                    Can't find what you're looking for? Our support team is here to help.
                </p>
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <div class="bg-gray-50 dark:bg-slate-900 p-8 rounded-[2rem] border border-gray-100 dark:border-white/5 text-center">
                        <div class="w-16 h-16 bg-primary/10 dark:bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="mail" class="w-8 h-8 text-primary dark:text-accent"></i>
                        </div>
                        <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-2 font-outfit">Email Support</h3>
                        <p class="text-gray-600 dark:text-slate-400 mb-8 text-sm font-medium">Get help from our support team via email.</p>
                        <a href="mailto:support@<?php echo SITE_NAME; ?>.com" class="inline-flex items-center justify-center px-8 py-3 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                            Send Email
                            <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                        </a>
                    </div>
                    <div class="bg-primary/5 dark:bg-accent/5 p-8 rounded-[2rem] border border-primary/10 dark:border-accent/10 text-center">
                        <div class="w-16 h-16 bg-primary/10 dark:bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="message-square" class="w-8 h-8 text-primary dark:text-accent"></i>
                        </div>
                        <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-2 font-outfit">Contact Page</h3>
                        <p class="text-gray-600 dark:text-slate-400 mb-8 text-sm font-medium">Use our contact form for detailed inquiries.</p>
                        <?php 
                            $text = 'Visit Contact';
                            $type = 'a';
                            $href = BASE_URL . '/contact';
                            $variant = 'primary';
                            $size = 'md';
                            $icon = 'send';
                            $class = 'w-full rounded-xl';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function toggleFaq(id) {
        const answer = document.getElementById('faq-answer-' + id);
        const icon = document.getElementById('faq-icon-' + id);
        const isHidden = answer.classList.contains('hidden');
        
        // Close all others
        document.querySelectorAll('[id^="faq-answer-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="faq-icon-"]').forEach(el => el.style.transform = 'rotate(0deg)');
        
        if (isHidden) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    }

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
