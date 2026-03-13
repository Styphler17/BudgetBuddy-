<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Header/Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-20 pt-32">
        <div class="container mx-auto max-w-5xl px-6 text-center">
            <nav class="mb-8 flex justify-center text-sm text-gray-500 dark:text-slate-400 font-medium">
                <a href="/" class="hover:text-primary dark:hover:text-accent transition-colors">Home</a>
                <span class="mx-3 text-gray-300 dark:text-slate-700">/</span>
                <span class="text-gray-900 dark:text-white">Blog</span>
            </nav>
            
            <span class="inline-block px-4 py-1.5 rounded-full border border-primary/10 dark:border-accent/20 text-[10px] font-black uppercase tracking-[0.35em] text-primary dark:text-accent mb-6 bg-white dark:bg-slate-900 shadow-sm relative overflow-hidden">
                <span class="relative z-10">BudgetBuddy Blog</span>
                <div class="absolute inset-0 animate-shimmer opacity-10 dark:opacity-20 bg-gradient-to-r from-transparent via-primary/5 dark:via-accent/5 to-transparent"></div>
            </span>
            
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mb-6 font-outfit leading-tight">
                Money wisdom for every <span class="text-primary dark:text-accent italic font-medium">milestone</span>
            </h1>
            
            <p class="text-xl text-gray-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
                From foundational budgeting to next-level wealth moves, explore guides written by our financial strategy team and power users.
            </p>

            <!-- Search and Sort Interface -->
            <div class="mt-12 max-w-3xl mx-auto space-y-6">
                <form id="search-form" action="/blog" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                    <div class="relative w-full group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 dark:text-slate-500 group-focus-within:text-primary dark:group-focus-within:text-accent transition-colors"></i>
                        <input 
                            id="blog-search"
                            type="text" 
                            name="search"
                            value="<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
                            placeholder="Search articles, tactics, and insights..." 
                            class="w-full pl-12 pr-4 py-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-2xl shadow-sm focus:ring-4 focus:ring-primary/5 dark:focus:ring-accent/5 focus:border-primary/20 dark:focus:border-accent/20 outline-none transition-all text-gray-900 dark:text-white font-medium"
                            autocomplete="off"
                        >
                    </div>
                    
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="relative w-full md:w-48">
                            <select id="blog-sort" name="sort" class="w-full h-14 pl-4 pr-10 bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-2xl shadow-sm outline-none focus:border-primary/20 dark:focus:border-accent/20 transition-all appearance-none font-bold text-[10px] uppercase tracking-widest text-gray-600 dark:text-slate-400 cursor-pointer">
                                <option value="newest" <?php echo ($currentSort ?? '') === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                <option value="popular" <?php echo ($currentSort ?? '') === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-slate-500 pointer-events-none"></i>
                        </div>
                    </div>
                </form>

                <?php if (!empty($popularTags)): ?>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <span class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mr-2">Filter by:</span>
                        <?php foreach ($popularTags as $tag): 
                            $isActive = ($currentTag === $tag);
                        ?>
                            <a href="/blog?tag=<?php echo urlencode($tag); ?>" 
                               class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all <?php echo $isActive ? 'bg-primary dark:bg-accent text-white dark:text-primary shadow-md' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-400 border border-gray-200 dark:border-white/10 hover:border-primary/40 dark:hover:border-accent/40 hover:text-primary dark:hover:text-accent'; ?>">
                                <?php echo htmlspecialchars($tag); ?>
                            </a>
                        <?php endforeach; ?>
                        <?php if (!empty($currentSearch) || !empty($currentTag)): ?>
                            <a href="/blog" class="ml-2 p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors" title="Clear Filters">
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
        <div id="blog-results-container">
            <?php if (empty($posts)): ?>
                <div class="max-w-xl mx-auto py-20 text-center">
                    <div class="h-20 w-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="book-open" class="h-10 w-10 text-gray-300 dark:text-slate-700"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-outfit">No insights found</h2>
                    <p class="text-gray-600 dark:text-slate-400 mt-2 font-medium">We're preparing fresh intelligence. Check back soon or adjust your filters.</p>
                </div>
            <?php else: ?>
                <div class="space-y-24">
                    <!-- Featured Post -->
                    <div id="featured-post-section" class="<?php echo ($currentPage > 1 || !empty($currentSearch) || !empty($currentTag)) ? 'hidden' : ''; ?>">
                        <?php if ($featuredPost): ?>
                            <div class="animate-slide-up">
                                <h2 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-8 flex items-center gap-4">
                                    <span class="h-px w-12 bg-gray-200 dark:bg-slate-800"></span>
                                                                Featured Blog
                                                            </h2>
                                                            <a href="/<?php echo $featuredPost['slug']; ?>" class="group block">
                                                                <article class="grid lg:grid-cols-12 gap-0 border border-gray-100 dark:border-white/5 rounded-[2.5rem] overflow-hidden bg-white dark:bg-slate-900 shadow-2xl dark:shadow-none shadow-gray-200/50 hover:shadow-primary/10 dark:hover:bg-slate-800/50 transition-all duration-700">                                        <div class="lg:col-span-7 aspect-video relative overflow-hidden">
                                            <img 
                                                src="<?php echo htmlspecialchars($featuredPost['cover_image_url'] ?? 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&auto=format&fit=crop&q=60'); ?>" 
                                                alt="<?php echo htmlspecialchars($featuredPost['title']); ?>"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        </div>
                                        <div class="lg:col-span-5 p-10 md:p-16 flex flex-col justify-center">
                                            <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-primary dark:text-accent mb-6">
                                                <span class="px-2 py-1 bg-primary/10 dark:bg-accent/10 rounded-md">Editor's Choice</span>
                                                <span class="text-gray-400 dark:text-slate-500"><?php echo $featuredPost['reading_time'] ?? 5; ?> min read</span>
                                            </div>
                                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6 font-outfit leading-tight group-hover:text-primary dark:group-hover:text-accent transition-colors">
                                                <?php echo htmlspecialchars($featuredPost['title']); ?>
                                            </h2>
                                            <p class="text-gray-600 dark:text-slate-300 mb-8 line-clamp-3 text-lg leading-relaxed font-medium">
                                                <?php echo htmlspecialchars($featuredPost['excerpt'] ?? ''); ?>
                                            </p>
                                            <div class="flex items-center justify-between pt-8 border-t border-gray-50 dark:border-white/5">
                                                <span class="text-sm font-bold text-gray-400 dark:text-slate-500"><?php echo date('M d, Y', strtotime($featuredPost['created_at'])); ?></span>
                                                <span class="inline-flex items-center gap-2 text-sm font-black uppercase tracking-widest text-primary dark:text-accent">
                                                    Read article <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Latest Stories: Editorial Grid -->
                    <div class="animate-slide-up delay-100">
                        <h2 id="grid-title" class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-12 flex items-center gap-4">
                            <span class="h-px w-12 bg-gray-200 dark:bg-slate-800"></span>
                            <span id="grid-title-text"><?php echo (empty($currentSearch) && empty($currentTag)) ? 'Latest Articles' : 'Search Results'; ?></span>
                        </h2>
                        
                        <div id="blog-grid" class="grid gap-8 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <?php 
                            foreach ($otherPosts as $index => $post): 
                            ?>
                                <a href="/<?php echo $post['slug']; ?>" class="group block">
                                    <article class="flex flex-col h-full bg-white dark:bg-transparent transition-all">
                                        <div class="aspect-[16/11] overflow-hidden rounded-2xl relative mb-4 border border-gray-100 dark:border-white/10 shadow-sm group-hover:shadow-xl dark:group-hover:shadow-none group-hover:shadow-primary/5 dark:group-hover:bg-slate-900 transition-all duration-500">
                                            <img 
                                                src="<?php echo htmlspecialchars($post['cover_image_url'] ?? 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&auto=format&fit=crop&q=60'); ?>" 
                                                alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                            <?php if(!empty($post['tags'])): ?>
                                                <span class="absolute top-3 left-3 z-20 px-2 py-0.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-md text-[8px] font-black uppercase text-primary dark:text-accent tracking-widest shadow-sm">
                                                    <?php echo htmlspecialchars(is_array($post['tags']) ? $post['tags'][0] : explode(',', $post['tags'])[0]); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow flex flex-col">
                                            <div class="flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-2">
                                                <span><?php echo date('M d', strtotime($post['created_at'])); ?></span>
                                                <span class="h-1 w-1 rounded-full bg-gray-300 dark:bg-slate-700"></span>
                                                <span class="text-primary dark:text-accent"><?php echo $post['reading_time'] ?? 1; ?>m read</span>
                                            </div>
                                            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2 font-outfit leading-snug group-hover:text-primary dark:group-hover:text-accent transition-colors line-clamp-2">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </h3>
                                            <p class="text-xs text-gray-600 dark:text-slate-400 line-clamp-2 mb-4 font-medium leading-relaxed flex-grow">
                                                <?php echo htmlspecialchars($post['excerpt'] ?? ''); ?>
                                            </p>
                                            <span class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-900 dark:text-slate-300 group-hover:text-primary dark:group-hover:text-accent transition-all">
                                                Read article <i data-lucide="arrow-right" class="h-3 w-3 -translate-x-1 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all"></i>
                                            </span>
                                        </div>
                                    </article>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination Container -->
                        <div id="pagination-container">
                            <?php if ($totalPages > 1): ?>
                                <div class="mt-24 flex flex-col items-center gap-6">
                                    <div class="relative bg-gray-50 dark:bg-slate-900 p-2 rounded-[2rem] border border-gray-100 dark:border-white/5 shadow-inner flex items-center gap-1 group">
                                        <!-- Mobile Arrows -->
                                        <a href="?page=<?php echo max(1, $currentPage - 1); ?><?php echo !empty($currentSearch) ? '&search='.urlencode($currentSearch) : ''; ?><?php echo !empty($currentTag) ? '&tag='.urlencode($currentTag) : ''; ?>" 
                                           class="p-3 rounded-full hover:bg-white dark:hover:bg-slate-800 transition-all text-gray-400 hover:text-primary dark:hover:text-accent <?php echo $currentPage == 1 ? 'pointer-events-none opacity-20' : ''; ?>">
                                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                                        </a>

                                        <div class="flex items-center gap-1 overflow-hidden">
                                            <?php
                                            $range = 2; // Pages to show before and after current
                                            for ($i = 1; $i <= $totalPages; $i++):
                                                if ($i == 1 || $i == $totalPages || ($i >= $currentPage - $range && $i <= $currentPage + $range)):
                                                    $isActive = ($i == $currentPage);
                                            ?>
                                                <a href="?page=<?php echo $i; ?><?php echo !empty($currentSearch) ? '&search='.urlencode($currentSearch) : ''; ?><?php echo !empty($currentTag) ? '&tag='.urlencode($currentTag) : ''; ?>" 
                                                   class="relative z-10 w-12 h-12 flex items-center justify-center rounded-full text-sm font-black transition-all duration-500 <?php echo $isActive ? 'text-white dark:text-primary' : 'text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white'; ?>">
                                                    <?php if ($isActive): ?>
                                                        <div class="absolute inset-0 bg-primary dark:bg-accent rounded-full shadow-lg shadow-primary/20 dark:shadow-none animate-wheel-pop"></div>
                                                    <?php endif; ?>
                                                    <span class="relative z-20"><?php echo $i; ?></span>
                                                </a>
                                            <?php 
                                                elseif ($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1):
                                            ?>
                                                <span class="w-8 text-center text-gray-300 dark:text-slate-700">...</span>
                                            <?php 
                                                endif;
                                            endfor; 
                                            ?>
                                        </div>

                                        <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?><?php echo !empty($currentSearch) ? '&search='.urlencode($currentSearch) : ''; ?><?php echo !empty($currentTag) ? '&tag='.urlencode($currentTag) : ''; ?>" 
                                           class="p-3 rounded-full hover:bg-white dark:hover:bg-slate-800 transition-all text-gray-400 hover:text-primary dark:hover:text-accent <?php echo $currentPage == $totalPages ? 'pointer-events-none opacity-20' : ''; ?>">
                                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                        </a>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-slate-600">
                                        Viewing page <?php echo $currentPage; ?> of <?php echo $totalPages; ?> 
                                        <span class="mx-2 text-gray-200 dark:text-slate-800">•</span> 
                                        <?php echo $totalPosts; ?> Insights Total
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTAs -->
    <section class="container mx-auto px-6 py-32">
        <div class="relative rounded-[3rem] bg-primary dark:bg-slate-900 p-12 md:p-20 overflow-hidden text-center group">
            <div class="absolute inset-0 bg-accent opacity-0 group-hover:opacity-5 transition-opacity"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <h2 class="text-4xl md:text-5xl font-bold text-white font-outfit tracking-tighter mb-6">
                Stay ahead of the <span class="text-accent italic font-medium">market curve</span>
            </h2>
            <p class="text-primary-foreground/70 dark:text-slate-400 text-lg font-medium max-w-2xl mx-auto mb-12 leading-relaxed">
                Get weekly money tips, foundational budgeting tactics, and next-level wealth moves delivered to your inbox.
            </p>
            <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                <input type="email" name="user_email" placeholder="Enter your email" class="flex-grow h-14 px-6 rounded-2xl bg-white/10 dark:bg-white/5 border border-white/10 dark:border-white/10 text-white placeholder:text-white/40 outline-none focus:bg-white/20 dark:focus:bg-white/10 transition-all font-bold" required>
                <?php 
                    $text = 'Subscribe';
                    $type = 'submit';
                    $variant = 'outline';
                    $size = 'md';
                    $class = 'h-14 px-10 bg-white dark:bg-accent text-primary dark:text-primary font-black uppercase tracking-widest text-xs rounded-2xl hover:scale-105 border-none shadow-2xl';
                    include APP_PATH . '/views/includes/Button.php';
                ?>
            </form>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();

        const searchInput = document.getElementById('blog-search');
        const sortSelect = document.getElementById('blog-sort');
        const resultsContainer = document.getElementById('blog-results-container');
        const gridTitleText = document.getElementById('grid-title-text');
        const featuredSection = document.getElementById('featured-post-section');
        
        let debounceTimer;

        const updateResults = () => {
            const searchQuery = searchInput.value;
            const sortQuery = sortSelect.value;
            const url = new URL(window.location.href);
            
            url.searchParams.set('search', searchQuery);
            url.searchParams.set('sort', sortQuery);
            url.searchParams.set('ajax', '1');
            url.searchParams.set('page', '1'); // Reset to page 1 on search

            // Show loading state
            resultsContainer.style.opacity = '0.5';
            resultsContainer.style.pointerEvents = 'none';

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.getElementById('blog-results-container');
                    
                    if (newResults) {
                        resultsContainer.innerHTML = newResults.innerHTML;
                        lucide.createIcons();
                        
                        // Update URL without refreshing
                        const displayUrl = new URL(window.location.href);
                        displayUrl.searchParams.set('search', searchQuery);
                        displayUrl.searchParams.set('sort', sortQuery);
                        if (searchQuery || sortQuery !== 'newest') {
                            displayUrl.searchParams.set('page', '1');
                        }
                        window.history.pushState({}, '', displayUrl);
                    }
                    
                    resultsContainer.style.opacity = '1';
                    resultsContainer.style.pointerEvents = 'auto';
                })
                .catch(err => {
                    console.error('Search error:', err);
                    resultsContainer.style.opacity = '1';
                    resultsContainer.style.pointerEvents = 'auto';
                });
        };

        searchInput?.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateResults, 400);
        });

        sortSelect?.addEventListener('change', updateResults);

        // ... newsletter logic ...
        const newsletterForm = document.getElementById('newsletter-form');
        newsletterForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            ToastSave.show('loading', { loadingText: 'Subscribing...' });
            
            emailjs.sendForm('service_5w533ca', 'template_ic1fwsh', this)
                .then(() => {
                    ToastSave.show('success', { 
                        successText: 'Thanks for subscribing!',
                        duration: 5000 
                    });
                    newsletterForm.reset();
                }, (error) => {
                    console.error('Newsletter Error:', error);
                    ToastSave.show('initial', { 
                        initialText: 'Subscription failed.',
                        saveText: 'Try Again',
                        onSave: () => newsletterForm.dispatchEvent(new Event('submit'))
                    });
                });
        });
    });
</script>
