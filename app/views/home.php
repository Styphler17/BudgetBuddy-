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
                        <span class="relative z-10">🎉 Smart Budgeting Made Simple</span>
                        <div class="absolute inset-0 animate-shimmer opacity-30"></div>
                    </span>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-display tracking-tight text-gray-900 dark:text-white mb-8 leading-[1.1]">
                        Take Control of Your <span class="text-primary dark:text-accent italic">Finances</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 dark:text-slate-300 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-body">
                        BudgetBuddy helps you track expenses, set goals, and make smarter financial decisions.
                        Join thousands of users who have transformed their financial habits.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php 
                                $text = 'Go to Dashboard';
                                $type = 'a';
                                $href = '/BudgetBuddy-/dashboard';
                                $variant = 'primary';
                                $size = 'lg';
                                $icon = 'arrow-right';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                        <?php else: ?>
                            <?php 
                                $text = 'Start Free Today';
                                $type = 'a';
                                $href = '/BudgetBuddy-/register';
                                $variant = 'primary';
                                $size = 'lg';
                                $icon = 'arrow-right';
                                include APP_PATH . '/views/includes/Button.php';
                            ?>
                            <?php 
                                $text = 'Sign In';
                                $type = 'a';
                                $href = '/BudgetBuddy-/login';
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

    <!-- Features Section (Alternating Layout) -->
    <section class="container mx-auto px-4 py-32 space-y-32 reveal">
        <div class="text-center mb-20 max-w-3xl mx-auto">
            <h2 class="text-4xl font-h1 text-gray-900 dark:text-white mb-4">Everything You Need to Budget Better</h2>
            <p class="text-lg text-gray-600 dark:text-slate-300 font-body">Powerful features designed to help you understand and improve your financial health.</p>
        </div>

        <?php
        $features = [
            [
                'icon' => 'bar-chart-3',
                'color' => 'bg-blue-500',
                'title' => 'Transaction Tracking',
                'desc' => 'Monitor all your income and expenses in one central command center. Stay informed about every dollar.',
                'img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&auto=format&fit=crop&q=60'
            ],
            [
                'icon' => 'target',
                'color' => 'bg-green-500',
                'title' => 'Budget Goals',
                'desc' => 'Set and track financial goals with smart budgeting alerts. Turn your dreams into reality.',
                'img' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&auto=format&fit=crop&q=60'
            ],
            [
                'icon' => 'pie-chart',
                'color' => 'bg-orange-500',
                'title' => 'Analytics & Insights',
                'desc' => 'Visual reports and AI-driven analytics for better financial decisions. See the big picture instantly.',
                'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=60'
            ]
        ];

        foreach ($features as $index => $f): 
            $isReversed = $index % 2 !== 0;
        ?>
            <div class="grid lg:grid-cols-2 gap-16 items-center reveal">
                <div class="<?php echo $isReversed ? 'lg:order-2' : ''; ?> space-y-6 text-left">
                    <div class="w-16 h-16 <?php echo $f['color']; ?> rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="<?php echo $f['icon']; ?>" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-h2 text-gray-900 dark:text-white"><?php echo $f['title']; ?></h2>
                    <p class="text-lg text-gray-600 dark:text-slate-300 leading-relaxed font-body"><?php echo $f['desc']; ?></p>
                    <?php 
                        $text = 'Learn more';
                        $type = 'a';
                        $href = '/BudgetBuddy-/register';
                        $variant = 'ghost';
                        $size = 'sm';
                        $icon = 'arrow-right';
                        $class = 'text-primary dark:text-accent font-bold p-0 hover:bg-transparent';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                </div>
                <div class="<?php echo $isReversed ? 'lg:order-1' : ''; ?> relative">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-gray-100 dark:border-white/5 hover-lift transition-all duration-500">
                        <img src="<?php echo $f['img']; ?>" alt="<?php echo $f['title']; ?>" class="w-full h-auto">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Stats Section -->
    <section class="bg-primary dark:bg-slate-900 py-24 relative overflow-hidden text-white reveal">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-3">
                    <div class="text-5xl lg:text-6xl font-display tabular-nums" id="stat-users">0</div>
                    <div class="text-primary-foreground/80 dark:text-slate-400 font-black uppercase tracking-[0.2em] text-sm">Active Users</div>
                </div>
                <div class="space-y-3">
                    <div class="text-5xl lg:text-6xl font-display tabular-nums" id="stat-money">$0M</div>
                    <div class="text-primary-foreground/80 dark:text-slate-400 font-black uppercase tracking-[0.2em] text-sm">Money Tracked</div>
                </div>
                <div class="space-y-3">
                    <div class="text-5xl lg:text-6xl font-display tabular-nums" id="stat-rating">0.0★</div>
                    <div class="text-primary-foreground/80 dark:text-slate-400 font-black uppercase tracking-[0.2em] text-sm">User Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Blog Posts -->
    <section class="container mx-auto px-4 py-32 reveal">
        <div class="flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left mb-16">
            <div>
                <h2 class="text-4xl font-h1 text-gray-900 dark:text-white">Latest from the blog</h2>
                <p class="mt-2 text-xl text-gray-600 dark:text-slate-300 font-body">
                    Fresh insights and product tips to help you make smarter money moves.
                </p>
            </div>
            <?php 
                $text = 'View all stories';
                $type = 'a';
                $href = '/BudgetBuddy-/blog';
                $variant = 'outline';
                $size = 'md';
                $class = 'bg-white dark:bg-transparent text-gray-900 dark:text-white';
                include APP_PATH . '/views/includes/Button.php';
            ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php
            $sample_posts = [
                ['title' => 'Mastering Your 2024 Financial Roadmap', 'tag' => 'Savings', 'img' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&auto=format&fit=crop&q=60'],
                ['title' => 'The Psychology of Smart Spending', 'tag' => 'Planning', 'img' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&auto=format&fit=crop&q=60'],
                ['title' => 'Building Wealth in the Digital Era', 'tag' => 'Insights', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=60']
            ];
            foreach ($sample_posts as $post): ?>
                <div class="group relative rounded-3xl border border-gray-200 dark:border-white/5 bg-white dark:bg-slate-900 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 text-left reveal">
                    <div class="aspect-video overflow-hidden relative">
                        <span class="absolute top-4 left-4 z-20 px-3 py-1 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-lg text-[10px] font-black uppercase text-primary dark:text-accent tracking-widest shadow-sm">
                            <?php echo $post['tag']; ?>
                        </span>
                        <img src="<?php echo $post['img']; ?>" alt="Post Image" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-h2 text-gray-900 dark:text-white mb-4 leading-tight group-hover:text-primary dark:group-hover:text-accent transition-colors"><?php echo $post['title']; ?></h3>
                        <a href="/BudgetBuddy-/<?php echo $post['slug'] ?? 'blog'; ?>" class="inline-flex items-center text-sm font-bold text-primary dark:text-accent group-hover:gap-2 transition-all">
                            Read article <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Testimonials Slider -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-32 overflow-hidden reveal">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 text-gray-900 dark:text-white">
                <h2 class="text-4xl font-h1">Loved by thousands</h2>
                <p class="text-xl text-gray-600 dark:text-slate-300 mt-2 font-body">Real stories from real users achieving financial freedom.</p>
            </div>
            
            <div class="relative max-w-4xl mx-auto">
                <div id="testimonial-container" class="flex transition-transform duration-700 ease-in-out">
                    <?php
                    $testimonials = [
                        ['initials' => 'SJ', 'name' => 'Sarah J.', 'role' => 'Small Business Owner', 'content' => 'BudgetBuddy has transformed how I manage my business finances. The analytics are incredible!'],
                        ['initials' => 'MK', 'name' => 'Michael K.', 'role' => 'Freelancer', 'content' => 'Finally, a budgeting app that understands freelancers. The goal tracking is a game-changer.'],
                        ['initials' => 'AL', 'name' => 'Anna L.', 'role' => 'Student', 'content' => 'Simple, intuitive, and helps me stay on top of my student loans and expenses.']
                    ];
                    foreach ($testimonials as $t): ?>
                        <div class="w-full flex-shrink-0 px-4">
                            <div class="bg-white dark:bg-slate-800 p-10 md:p-16 rounded-[3rem] shadow-xl border border-gray-100 dark:border-white/5 text-center hover-lift transition-all">
                                <div class="flex flex-col items-center gap-6 mb-8">
                                    <div class="h-20 w-20 rounded-full bg-primary/10 dark:bg-accent/10 flex items-center justify-center text-primary dark:text-accent text-2xl font-black shadow-inner">
                                        <?php echo $t['initials']; ?>
                                    </div>
                                    <div>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white"><?php echo $t['name']; ?></p>
                                        <p class="text-sm font-bold text-primary dark:text-accent uppercase tracking-widest"><?php echo $t['role']; ?></p>
                                    </div>
                                </div>
                                <p class="text-2xl text-gray-600 dark:text-slate-300 italic leading-relaxed font-medium">"<?php echo $t['content']; ?>"</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation Dots -->
                <div class="flex justify-center gap-3 mt-12">
                    <?php foreach ($testimonials as $i => $t): ?>
                        <button onclick="slideTestimonial(<?php echo $i; ?>)" class="testimonial-dot w-3 h-3 rounded-full bg-gray-300 dark:bg-slate-700 transition-all hover:bg-primary dark:hover:bg-accent"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white dark:bg-[#020617] py-32 reveal">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto bg-primary dark:bg-slate-900 rounded-[3rem] p-16 md:p-24 relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-accent/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 text-center">
                    <h2 class="text-4xl md:text-5xl font-h1 text-white mb-6 tracking-tight">Ready to Transform Your Finances?</h2>
                    <p class="text-xl text-primary-foreground/80 dark:text-slate-300 mb-10 max-w-xl mx-auto font-body">Join thousands of users who have taken control of their financial future today.</p>
                    <?php 
                        $text = 'Get Started Free';
                        $type = 'a';
                        $href = '/BudgetBuddy-/register';
                        $variant = 'outline';
                        $size = 'lg';
                        $class = 'bg-white dark:bg-accent text-primary dark:text-primary border-none shadow-xl hover:scale-105';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    let currentSlide = 0;
    const totalSlides = <?php echo count($testimonials); ?>;

    function slideTestimonial(index) {
        currentSlide = index;
        const container = document.getElementById('testimonial-container');
        const dots = document.querySelectorAll('.testimonial-dot');
        
        container.style.transform = `translateX(-${index * 100}%)`;
        
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('bg-primary', 'w-8');
                dot.classList.remove('bg-gray-300');
            } else {
                dot.classList.remove('bg-primary', 'w-8');
                dot.classList.add('bg-gray-300');
            }
        });
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
        slideTestimonial(0);

        // Auto slide
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            slideTestimonial(currentSlide);
        }, 5000);

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

        // Counter Animation Logic
        const counters = [
            { id: 'stat-users', end: 10000, suffix: '+', prefix: '' },
            { id: 'stat-money', end: 2, suffix: 'M+', prefix: '$' },
            { id: 'stat-rating', end: 4.9, suffix: '★', prefix: '', decimals: 1 }
        ];

        const animateCounter = (id, end, suffix, prefix, decimals = 0) => {
            const el = document.getElementById(id);
            let start = 0;
            const duration = 2000;
            const step = (timestamp) => {
                if (!start) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);
                const current = progress * end;
                el.innerText = prefix + current.toFixed(decimals) + suffix;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        };

        // Trigger counters on reveal
        const statsSection = document.getElementById('stat-users').closest('section');
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counters.forEach(c => animateCounter(c.id, c.end, c.suffix, c.prefix, c.decimals));
                    statsObserver.disconnect();
                }
            });
        }, { threshold: 0.1 });

        statsObserver.observe(statsSection);
    });
</script>