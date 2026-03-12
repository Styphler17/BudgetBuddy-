<div class="min-h-screen bg-white">
    <!-- Header/Hero Section -->
    <section class="bg-gray-50 py-20 pt-32">
        <div class="container mx-auto max-w-5xl px-6 text-center">
            <nav class="mb-8 flex justify-center text-sm text-gray-500 font-medium">
                <a href="/BudgetBuddy-/" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-3 text-gray-300">/</span>
                <span class="text-gray-900">Blog</span>
            </nav>
            
            <span class="inline-block px-4 py-1.5 rounded-full border border-primary/10 text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-6 bg-white shadow-sm">
                BudgetBuddy Blog
            </span>
            
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-gray-900 mb-6 font-outfit leading-tight">
                Money wisdom for every <span class="text-primary italic">milestone</span>
            </h1>
            
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed font-medium">
                From foundational budgeting to next-level wealth moves, explore guides written by our financial strategy team and power users.
            </p>

            <!-- Search and Sort Interface -->
            <div class="mt-12 max-w-3xl mx-auto space-y-6">
                <form action="/BudgetBuddy-/blog" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                    <div class="relative w-full group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        <input 
                            type="text" 
                            name="search"
                            value="<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
                            placeholder="Search articles, tactics, and insights..." 
                            class="w-full pl-12 pr-4 py-4 bg-white border border-gray-200 rounded-2xl shadow-sm focus:ring-4 focus:ring-primary/5 focus:border-primary/20 outline-none transition-all text-gray-900 font-medium"
                        >
                    </div>
                    
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="relative w-full md:w-48">
                            <select name="sort" class="w-full h-14 pl-4 pr-10 bg-white border border-gray-200 rounded-2xl shadow-sm outline-none focus:border-primary/20 transition-all appearance-none font-bold text-xs uppercase tracking-widest text-gray-600 cursor-pointer">
                                <option value="newest">Newest First</option>
                                <option value="popular">Most Popular</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                        </div>
                        <?php 
                            $text = 'Find';
                            $type = 'submit';
                            $variant = 'primary';
                            $size = 'md';
                            $class = 'h-14 px-8 rounded-2xl';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                </form>

                <?php if (!empty($popularTags)): ?>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mr-2">Filter by:</span>
                        <?php foreach ($popularTags as $tag): 
                            $isActive = ($currentTag === $tag);
                        ?>
                            <a href="/BudgetBuddy-/blog?tag=<?php echo urlencode($tag); ?>" 
                               class="px-4 py-2 rounded-xl text-xs font-bold transition-all <?php echo $isActive ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-white text-gray-600 border border-gray-200 hover:border-primary/40 hover:text-primary'; ?>">
                                #<?php echo htmlspecialchars($tag); ?>
                            </a>
                        <?php endforeach; ?>
                        <?php if (!empty($currentSearch) || !empty($currentTag)): ?>
                            <a href="/BudgetBuddy-/blog" class="ml-2 p-2 text-gray-400 hover:text-red-500 transition-colors" title="Clear Filters">
                                <i data-lucide="x-circle" class="h-5 w-5"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto px-6 py-24">
        <?php if (empty($posts)): ?>
            <div class="max-w-xl mx-auto py-20 text-center">
                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="book-open" class="h-10 w-10 text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 font-outfit">No insights found</h2>
                <p class="text-gray-600 mt-2 font-medium">We're preparing fresh intelligence. Check back soon or adjust your filters.</p>
            </div>
        <?php else: ?>
            <div class="space-y-24">
                <!-- Featured Post -->
                <?php if ($featuredPost && empty($currentSearch) && empty($currentTag)): ?>
                    <div class="animate-slide-up">
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-4">
                            <span class="h-px w-12 bg-gray-200"></span>
                            Featured Insight
                        </h2>
                        <a href="/BudgetBuddy-/blog/<?php echo $featuredPost['slug']; ?>" class="group block">
                            <article class="grid lg:grid-cols-12 gap-0 border border-gray-100 rounded-[2.5rem] overflow-hidden bg-white shadow-2xl shadow-gray-200/50 hover:shadow-primary/10 transition-all duration-700">
                                <div class="lg:col-span-7 aspect-video relative overflow-hidden">
                                    <img 
                                        src="<?php echo htmlspecialchars($featuredPost['cover_image_url'] ?? 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&auto=format&fit=crop&q=60'); ?>" 
                                        alt="<?php echo htmlspecialchars($featuredPost['title']); ?>"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                </div>
                                <div class="lg:col-span-5 p-10 md:p-16 flex flex-col justify-center">
                                    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-primary mb-6">
                                        <span class="px-2 py-1 bg-primary/10 rounded-md">Must Read</span>
                                        <span class="text-gray-400"><?php echo $featuredPost['reading_time'] ?? 5; ?> min read</span>
                                    </div>
                                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-outfit leading-tight group-hover:text-primary transition-colors">
                                        <?php echo htmlspecialchars($featuredPost['title']); ?>
                                    </h2>
                                    <p class="text-gray-600 mb-8 line-clamp-3 text-lg leading-relaxed font-medium">
                                        <?php echo htmlspecialchars($featuredPost['excerpt'] ?? ''); ?>
                                    </p>
                                    <div class="flex items-center justify-between pt-8 border-t border-gray-50">
                                        <span class="text-sm font-bold text-gray-400"><?php echo date('M d, Y', strtotime($featuredPost['created_at'])); ?></span>
                                        <span class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-widest text-primary">
                                            Read more <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Latest Stories: Editorial Grid -->
                <div class="animate-slide-up delay-100">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-12 flex items-center gap-4">
                        <span class="h-px w-12 bg-gray-200"></span>
                        <?php echo (empty($currentSearch) && empty($currentTag)) ? 'Latest Intel' : 'Search Intelligence'; ?>
                    </h2>
                    
                    <div class="grid gap-12 md:grid-cols-2">
                        <?php 
                        $displayPosts = (empty($currentSearch) && empty($currentTag)) ? $otherPosts : $posts;
                        foreach ($displayPosts as $index => $post): 
                            $isLarge = ($index % 3 === 0);
                        ?>
                            <a href="/BudgetBuddy-/blog/<?php echo $post['slug']; ?>" class="group block">
                                <article class="flex flex-col h-full bg-white transition-all">
                                    <div class="aspect-[16/10] overflow-hidden rounded-[2rem] relative mb-8 border border-gray-100 shadow-sm group-hover:shadow-2xl group-hover:shadow-primary/5 transition-all duration-500">
                                        <img 
                                            src="<?php echo htmlspecialchars($post['cover_image_url'] ?? 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&auto=format&fit=crop&q=60'); ?>" 
                                            alt="<?php echo htmlspecialchars($post['title']); ?>"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        <?php if(!empty($post['tags'])): ?>
                                            <span class="absolute top-6 left-6 z-20 px-3 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[10px] font-black uppercase text-primary tracking-widest shadow-sm">
                                                <?php echo htmlspecialchars($post['tags'][0]); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">
                                            <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                            <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                                            <span class="text-primary"><?php echo $post['reading_time'] ?? 1; ?> min read</span>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-4 font-outfit leading-tight group-hover:text-primary transition-colors">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </h3>
                                        <p class="text-gray-600 line-clamp-2 mb-6 font-medium leading-relaxed">
                                            <?php echo htmlspecialchars($post['excerpt'] ?? ''); ?>
                                        </p>
                                        <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-900 group-hover:text-primary transition-all">
                                            Read more <i data-lucide="arrow-right" class="h-4 w-4 -translate-x-2 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all"></i>
                                        </span>
                                    </div>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- CTAs -->
    <section class="container mx-auto px-6 py-32">
        <div class="relative rounded-[3rem] bg-primary p-12 md:p-20 overflow-hidden text-center group">
            <div class="absolute inset-0 bg-accent opacity-0 group-hover:opacity-5 transition-opacity"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <h2 class="text-4xl md:text-5xl font-bold text-white font-outfit tracking-tighter mb-6">
                Stay ahead of the <span class="text-accent italic">market curve</span>
            </h2>
            <p class="text-primary-foreground/70 text-lg font-medium max-w-2xl mx-auto mb-12 leading-relaxed">
                Get weekly money tips, foundational budgeting tactics, and next-level wealth moves delivered to your inbox.
            </p>
            <form action="#" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                <input type="email" placeholder="Enter your email" class="flex-grow h-14 px-6 rounded-2xl bg-white/10 border border-white/10 text-white placeholder:text-white/40 outline-none focus:bg-white/20 transition-all font-bold">
                <?php 
                    $text = 'Subscribe';
                    $type = 'submit';
                    $variant = 'outline';
                    $size = 'md';
                    $class = 'h-14 px-10 bg-white text-primary font-black uppercase tracking-widest text-xs rounded-2xl hover:scale-105 border-none shadow-2xl';
                    include APP_PATH . '/views/includes/Button.php';
                ?>
            </form>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
