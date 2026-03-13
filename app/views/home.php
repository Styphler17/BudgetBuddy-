<style>
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-[#EEF2FF] to-[#F8FAFF] dark:from-slate-900 dark:to-slate-950 pt-32 pb-24 lg:pt-48 lg:pb-40 px-4">
        <!-- Soft Mesh Orbs -->
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-primary/5 dark:bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-accent/5 dark:bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="container mx-auto max-w-7xl relative z-10 text-left">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left animate-slide-up">
                    <span class="inline-flex items-center rounded-full border border-primary/10 dark:border-white/10 bg-white dark:bg-slate-900 px-4 py-1.5 text-sm font-bold text-primary dark:text-accent mb-8 shadow-sm glow-primary relative overflow-hidden">
                        <span class="relative z-10">🎉 Simple Manual Budget & Spending Tracker</span>
                        <div class="absolute inset-0 animate-shimmer opacity-30"></div>
                    </span>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-display tracking-tight text-gray-900 dark:text-white mb-8 leading-[1.1]">
                        Plan Your Budget and Track Spending <span class="text-primary dark:text-accent italic font-medium">Manually</span>, Without Spreadsheets or Bank Sync
                    </h1>
                    
                    <p class="text-xl text-gray-600 dark:text-slate-300 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-body">
                        SpendScribe is a clean, distraction-free budget tracker for people who prefer writing things down but are tired of notebooks, scraps of paper, and messy phone notes.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php 
                                $text = 'Go to Dashboard';
                                $type = 'a';
                                $href = BASE_URL . '/dashboard';
                                $variant = 'primary';
                                $size = 'lg';
                                $icon = 'arrow-right';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                        <?php else: ?>
                            <?php 
                                $text = 'Start Tracking Manually';
                                $type = 'a';
                                $href = BASE_URL . '/register';
                                $variant = 'primary';
                                $size = 'lg';
                                $icon = 'arrow-right';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                            <?php 
                                $text = 'See How It Works';
                                $type = 'a';
                                $href = '#features';
                                $variant = 'outline';
                                $size = 'lg';
                                $class = 'dark:bg-transparent dark:text-accent dark:border-accent/30';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Mockup -->
                <div class="relative lg:block animate-fade-in delay-200">
                    <div class="relative rounded-3xl border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden group">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80" alt="Dashboard Preview" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <!-- Floating elements -->
                    <div class="absolute -top-8 -right-8 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl border border-gray-100 dark:border-white/5 animate-float">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400">
                                <i data-lucide="trending-up" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Savings</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">+24% Increase</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 1: Why Manual Beats Messy Notes -->
    <section class="container mx-auto px-4 py-32 reveal" id="features">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8">
                <h2 class="text-4xl font-h1 text-gray-900 dark:text-white leading-tight">Why a Manual Budget Tracker Instead of Notes and Paper</h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="h-6 w-6 rounded-full bg-accent/20 flex items-center justify-center shrink-0 mt-1">
                            <i data-lucide="check" class="w-4 h-4 text-accent"></i>
                        </div>
                        <p class="text-lg text-gray-600 dark:text-slate-300 font-medium">Keep all your budgets, expenses, and savings plans in one organized place instead of scattered notebooks and phone notes.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="h-6 w-6 rounded-full bg-accent/20 flex items-center justify-center shrink-0 mt-1">
                            <i data-lucide="check" class="w-4 h-4 text-accent"></i>
                        </div>
                        <p class="text-lg text-gray-600 dark:text-slate-300 font-medium">See your planned budget and actual spending side by side so you instantly know where your money is going.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="h-6 w-6 rounded-full bg-accent/20 flex items-center justify-center shrink-0 mt-1">
                            <i data-lucide="check" class="w-4 h-4 text-accent"></i>
                        </div>
                        <p class="text-lg text-gray-600 dark:text-slate-300 font-medium">Stay fully in control: no bank connections, no automatic categories, just clear numbers you enter yourself.</p>
                    </div>
                </div>
            </div>
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative rounded-[1.5rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/5 z-10 bg-white dark:bg-slate-900">
                    <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=800&auto=format&fit=crop&q=60" alt="Organized Budgeting" class="w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Replace Notebooks and Spreadsheets -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-32 reveal">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="lg:order-2 space-y-8 text-left">
                    <h2 class="text-4xl font-h1 text-gray-900 dark:text-white leading-tight">Replace Notebooks, Loose Papers, and Overkill Spreadsheets</h2>
                    <ul class="space-y-4 text-lg text-gray-600 dark:text-slate-300 font-medium">
                        <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 bg-primary dark:bg-accent rounded-full"></span> Create simple monthly or weekly budgets in a few clicks.</li>
                        <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 bg-primary dark:bg-accent rounded-full"></span> Log each expense manually so you stay conscious of every purchase.</li>
                        <li class="flex items-center gap-3"><span class="w-1.5 h-1.5 bg-primary dark:bg-accent rounded-full"></span> Track savings goals without building complex formulas or templates.</li>
                    </ul>
                    <p class="text-primary dark:text-accent font-bold italic pt-4">SpendScribe gives you the structure of a spreadsheet with the simplicity of a notebook.</p>
                </div>
                <div class="lg:order-1 glowing-wrapper">
                    <div class="glowing-effect-container"></div>
                    <div class="relative rounded-[1.5rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-white/5 z-10 bg-white dark:bg-slate-900">
                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&auto=format&fit=crop&q=60" alt="Simplified Spreadsheet" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Key Features -->
    <section class="container mx-auto px-4 py-32 reveal">
        <div class="text-center mb-20 max-w-3xl mx-auto">
            <h2 class="text-4xl font-h1 text-gray-900 dark:text-white mb-4">What You Can Do With SpendScribe</h2>
            <p class="text-lg text-gray-600 dark:text-slate-300 font-medium">Distraction-free tools for precise manual tracking.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $features_list = [
                ['icon' => 'calendar', 't' => 'Flexible Periods', 'd' => 'Plan budgets by month, week, or custom period.'],
                ['icon' => 'list-plus', 't' => 'Custom Categories', 'd' => 'Add categories that match your real life (rent, food, data, etc.).'],
                ['icon' => 'touchpad', 't' => 'Any Device', 'd' => 'Log expenses manually in seconds, from any device.'],
                ['icon' => 'eye', 't' => 'Instant Clarity', 'd' => 'See remaining budget and total spending at a glance.'],
                ['icon' => 'piggy-bank', 't' => 'Goal Tracking', 'd' => 'Track savings contributions and progress toward your goals.'],
                ['icon' => 'refresh-ccw', 't' => 'Smart Templates', 'd' => 'Reset and duplicate budgets so you can reuse favorite setups.'],
            ];
            foreach ($features_list as $f): ?>
                <div class="glowing-wrapper">
                    <div class="glowing-effect-container"></div>
                    <div class="relative bg-white dark:bg-slate-900 p-8 rounded-[1.5rem] border border-gray-100 dark:border-white/5 shadow-sm transition-all h-full z-10 group">
                        <div class="w-12 h-12 bg-primary/10 dark:bg-accent/10 rounded-xl flex items-center justify-center text-primary dark:text-accent mb-6 group-hover:bg-primary group-hover:text-white dark:group-hover:bg-accent dark:group-hover:text-primary transition-all">
                            <i data-lucide="<?php echo $f['icon']; ?>" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3"><?php echo $f['t']; ?></h3>
                        <p class="text-gray-600 dark:text-slate-400 text-sm font-medium leading-relaxed"><?php echo $f['d']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Blog Section (RESTORED) -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-32 reveal">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left mb-16">
                <div>
                    <h2 class="text-4xl font-h1 text-gray-900 dark:text-white">Latest Articles</h2>
                    <p class="mt-2 text-xl text-gray-600 dark:text-slate-300 font-medium">
                        Tips and tactics to help you build better manual tracking habits.
                    </p>
                </div>
                <?php 
                    $text = 'View all stories';
                    $type = 'a';
                    $href = BASE_URL . '/blog';
                    $variant = 'outline';
                    $size = 'md';
                    $class = 'bg-white dark:bg-transparent text-gray-900 dark:text-white';
                    include APP_PATH . '/views/includes/Button.php';
                ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php
                $sample_posts = [
                    ['title' => 'Ditching Spreadsheets for Good', 'slug' => 'blog', 'tag' => 'Habits', 'img' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&auto=format&fit=crop&q=60'],
                    ['title' => 'Why Writing it Down Works', 'slug' => 'blog', 'tag' => 'Psychology', 'img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&auto=format&fit=crop&q=60'],
                    ['title' => 'Simple Budgeting for Couples', 'slug' => 'blog', 'tag' => 'Lifestyle', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=60']
                ];
                foreach ($sample_posts as $post): ?>
                    <div class="glowing-wrapper">
                        <div class="glowing-effect-container"></div>
                        <div class="relative rounded-[1.5rem] border border-gray-200 dark:border-white/5 bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 z-10 h-full group">
                            <div class="aspect-video overflow-hidden relative">
                                <span class="absolute top-4 left-4 z-20 px-3 py-1 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-lg text-[10px] font-black uppercase text-primary dark:text-accent tracking-widest shadow-sm">
                                    <?php echo $post['tag']; ?>
                                </span>
                                <img src="<?php echo $post['img']; ?>" alt="Post Image" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>
                            <div class="p-8">
                                <h3 class="text-2xl font-h2 text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-primary dark:group-hover:text-accent transition-colors"><?php echo $post['title']; ?></h3>
                                <a href="<?php echo BASE_URL; ?>/<?php echo $post['slug']; ?>" class="inline-flex items-center text-sm font-bold text-primary dark:text-accent group-hover:gap-2 transition-all">
                                    Read article <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Section 4: Privacy and Control -->
    <section class="bg-primary dark:bg-slate-900 py-32 relative overflow-hidden text-white reveal">
        <div class="absolute inset-0 bg-accent/5 pointer-events-none"></div>
        <div class="container mx-auto px-4 relative z-10 text-center max-w-4xl">
            <h2 class="text-4xl font-h1 mb-8 leading-tight">No Bank Connections. Just You and Your Numbers.</h2>
            <p class="text-xl text-primary-foreground/80 dark:text-slate-300 leading-relaxed font-medium mb-8">
                SpendScribe is built for people who want control and privacy. You don’t need to connect bank accounts or share financial data with third parties. You decide what to track, when to log it, and how your budget looks.
            </p>
            <p class="text-sm font-bold uppercase tracking-widest text-accent">
                Your data stays tied to your SpendScribe account under CreativeUtil’s infrastructure, with no selling of financial data.
            </p>
        </div>
    </section>

    <!-- Section 5: Who SpendScribe is for -->
    <section class="container mx-auto px-4 py-32 reveal">
        <div class="text-center mb-20 max-w-3xl mx-auto">
            <h2 class="text-4xl font-h1 text-gray-900 dark:text-white mb-4">Built for People Who Prefer to Budget by Hand</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-gray-50 dark:bg-slate-900/50 p-10 rounded-[2.5rem] border border-gray-100 dark:border-white/5 z-10 h-full">
                    <ul class="space-y-6">
                        <li class="flex gap-4">
                            <i data-lucide="palette" class="w-6 h-6 text-primary dark:text-accent shrink-0"></i>
                            <span class="text-gray-700 dark:text-slate-300 font-bold">Creators and freelancers who want a simple view of income vs. expenses.</span>
                        </li>
                        <li class="flex gap-4">
                            <i data-lucide="graduation-cap" class="w-6 h-6 text-primary dark:text-accent shrink-0"></i>
                            <span class="text-gray-700 dark:text-slate-300 font-bold">Students who just need to know how much they can spend each week.</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="relative bg-gray-50 dark:bg-slate-900/50 p-10 rounded-[2.5rem] border border-gray-100 dark:border-white/5 z-10 h-full">
                    <ul class="space-y-6">
                        <li class="flex gap-4">
                            <i data-lucide="users" class="w-6 h-6 text-primary dark:text-accent shrink-0"></i>
                            <span class="text-gray-700 dark:text-slate-300 font-bold">Couples tracking shared expenses and savings goals together.</span>
                        </li>
                        <li class="flex gap-4">
                            <i data-lucide="pencil-line" class="w-6 h-6 text-primary dark:text-accent shrink-0"></i>
                            <span class="text-gray-700 dark:text-slate-300 font-bold">Anyone moving from paper or notes and wanting something just as simple, but more organized.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple "How it works" steps -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-32 reveal">
        <div class="container mx-auto px-4">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-h1 text-gray-900 dark:text-white mb-4 leading-tight">How SpendScribe Works</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-12 max-w-6xl mx-auto mb-16">
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 bg-primary dark:bg-accent text-white dark:text-primary rounded-full flex items-center justify-center text-2xl font-black mx-auto shadow-xl">1</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Create your first budget</h3>
                    <p class="text-gray-600 dark:text-slate-400 font-medium">Choose a period and set how much you plan to spend in each category.</p>
                </div>
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 bg-primary dark:bg-accent text-white dark:text-primary rounded-full flex items-center justify-center text-2xl font-black mx-auto shadow-xl">2</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Log your expenses</h3>
                    <p class="text-gray-600 dark:text-slate-400 font-medium">Every time you spend, add a quick entry with amount, category, and note.</p>
                </div>
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 bg-primary dark:bg-accent text-white dark:text-primary rounded-full flex items-center justify-center text-2xl font-black mx-auto shadow-xl">3</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Watch your numbers</h3>
                    <p class="text-gray-600 dark:text-slate-400 font-medium">See how much is left in your budget, where your money goes, and how your savings grow.</p>
                </div>
            </div>
            <div class="text-center">
                <?php 
                    $text = 'Start Your First Budget';
                    $type = 'a';
                    $href = BASE_URL . '/register';
                    $variant = 'glow';
                    $size = 'lg';
                    $icon = 'plus-circle';
                    include APP_PATH . '/views/includes/Button.php';
                ?>
            </div>
        </div>
    </section>

    <!-- FAQ Block -->
    <section class="container mx-auto px-4 py-32 reveal">
        <div class="text-center mb-20">
            <h2 class="text-4xl font-h1 text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h2>
        </div>
        <div class="max-w-3xl mx-auto space-y-4">
            <?php
            $faqs_final = [
                ["q" => "Is SpendScribe free?", "a" => "Yes. SpendScribe is free to use in its current version. If we ever add paid features in the future, the core manual budget tracking will remain free."],
                ["q" => "Do I need to connect my bank account?", "a" => "No. SpendScribe is fully manual on purpose. You type in your own numbers so you stay aware of every expense and keep full control over your data."],
                ["q" => "Can I use SpendScribe on my phone and laptop?", "a" => "Yes. SpendScribe is a web-based tool that works in modern browsers on desktop, tablet, and mobile."],
                ["q" => "Is this a replacement for detailed accounting software?", "a" => "No. SpendScribe is designed for everyday budgeting and spending awareness, not for complex business accounting."],
                ["q" => "Who created SpendScribe?", "a" => "SpendScribe is built by CreativeUtil, a small web studio that creates simple tools to make everyday tasks easier."]
            ];
            foreach ($faqs_final as $i => $faq): ?>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-white/5 overflow-hidden">
                    <button onclick="toggleFaq(<?php echo $i; ?>)" class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-800 transition-all group">
                        <span class="font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-accent"><?php echo $faq['q']; ?></span>
                        <i data-lucide="plus" id="faq-icon-<?php echo $i; ?>" class="w-5 h-5 text-gray-400 dark:text-slate-500 transition-all"></i>
                    </button>
                    <div id="faq-answer-<?php echo $i; ?>" class="hidden px-8 pb-6 text-gray-600 dark:text-slate-400 leading-relaxed font-medium">
                        <?php echo $faq['a']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="bg-white dark:bg-[#020617] py-32 reveal">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto bg-primary dark:bg-slate-900 rounded-[3rem] p-16 md:p-24 relative overflow-hidden">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 text-center">
                    <h2 class="text-4xl md:text-5xl font-h1 text-white mb-6 tracking-tight">Ready to Master Your Daily Spend?</h2>
                    <p class="text-xl text-primary-foreground/80 dark:text-slate-300 mb-10 max-w-xl mx-auto font-body font-medium leading-relaxed">Join thousands of users who have ditched their spreadsheets and built consistent tracking habits today.</p>
                    <?php 
                        $text = 'Start Your First Budget';
                        $type = 'a';
                        $href = BASE_URL . '/register';
                        $variant = 'secondary';
                        $size = 'lg';
                        $icon = 'arrow-right';
                        $class = 'bg-white dark:bg-accent text-primary dark:text-primary border-none shadow-xl hover:scale-105';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
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
        
        document.querySelectorAll('[id^="faq-answer-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="faq-icon-"]').forEach(el => {
            el.style.transform = 'rotate(0deg)';
            el.setAttribute('data-lucide', 'plus');
        });
        
        if (isHidden) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(45deg)';
        }
        lucide.createIcons();
    }

    window.addEventListener('load', () => {
        lucide.createIcons();

        // Scroll Reveal Logic
        const revealCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        };

        const revealObserver = new IntersectionObserver(revealCallback, {
            threshold: 0.15
        });

        document.querySelectorAll('.reveal').forEach(el => {
            revealObserver.observe(el);
        });

        // Glowing Effect Controller
        const wrappers = document.querySelectorAll('.glowing-wrapper');
        const proximity = 150;

        const handlePointerMove = (e) => {
            wrappers.forEach(wrapper => {
                const container = wrapper.querySelector('.glowing-effect-container');
                if (!container) return;
                
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
