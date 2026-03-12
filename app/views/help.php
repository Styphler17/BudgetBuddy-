<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-20 text-center pt-32">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 font-outfit">
                How can we help you?
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Find answers, get support, and learn everything you need to know about BudgetBuddy.
            </p>
            <div class="max-w-md mx-auto relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                <input
                    type="text"
                    placeholder="Search for help..."
                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                >
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit">
                Browse by Category
            </h2>
            <p class="text-lg text-gray-600">
                Find the help you need organized by topic.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $categories = [
                [
                    "icon" => "book",
                    "title" => "Getting Started",
                    "description" => "Learn the basics of BudgetBuddy",
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
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                        <i data-lucide="<?php echo $cat['icon']; ?>" class="w-6 h-6 text-primary group-hover:text-white transition-colors"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 font-outfit"><?php echo $cat['title']; ?></h3>
                    <p class="text-gray-500 mb-6 text-sm"><?php echo $cat['description']; ?></p>
                    <ul class="space-y-2">
                        <?php foreach ($cat['articles'] as $article): ?>
                            <li class="text-sm text-gray-600 hover:text-primary transition-colors cursor-pointer">
                                • <?php echo $article; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit">
                    Frequently Asked Questions
                </h2>
                <p class="text-lg text-gray-600">
                    Quick answers to common questions.
                </p>
            </div>
            <div class="max-w-3xl mx-auto space-y-6">
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

                foreach ($faqs as $faq): ?>
                    <div class="p-6 border-l-4 border-primary bg-gray-50 rounded-r-lg shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-2">
                            <i data-lucide="help-circle" class="w-5 h-5 text-primary"></i>
                            <?php echo $faq['question']; ?>
                        </h3>
                        <p class="text-gray-600"><?php echo $faq['answer']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Support Section -->
    <section class="py-20">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-4 font-outfit">
                    Still Need Help?
                </h2>
                <p class="text-lg text-gray-600 mb-8">
                    Can't find what you're looking for? Our support team is here to help.
                </p>
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm mb-8">
                    <i data-lucide="mail" class="w-10 h-10 text-primary mx-auto mb-4"></i>
                    <h3 class="font-bold text-lg mb-2">Email Support</h3>
                    <p class="text-gray-600 mb-6">Get help from our support team</p>
                    <a href="mailto:support@budgetbuddy.com" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Send Email
                        <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                    </a>
                </div>
                <a href="/BudgetBuddy-/contact" class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors text-lg">
                    Visit Contact Page
                    <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
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