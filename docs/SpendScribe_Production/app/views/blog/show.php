<div class="min-h-screen bg-white dark:bg-slate-950 transition-colors duration-300">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 dark:bg-slate-900/50 py-10 pt-24">
        <div class="container mx-auto max-w-4xl px-6">
            <nav class="mb-4 text-sm text-gray-500 dark:text-slate-400">
                <a href="<?php echo BASE_URL; ?>/" class="hover:text-primary dark:hover:text-accent">Home</a>
                <span class="mx-2">&rarr;</span>
                <a href="<?php echo BASE_URL; ?>/blog" class="hover:text-primary dark:hover:text-accent">Blog</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 dark:text-white font-medium truncate inline-block max-w-[200px] align-bottom"><?php echo htmlspecialchars($post['title']); ?></span>
            </nav>

            <a href="<?php echo BASE_URL; ?>/blog" class="inline-flex items-center text-primary dark:text-accent font-medium mb-6 hover:underline">
                <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
                Back to all articles
            </a>

            <header>
                <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-slate-400 font-bold uppercase tracking-widest mb-6">
                    <?php 
                        $displayTag = 'Budgeting';
                        if (!empty($post['tags'])) {
                            $tagsArr = is_array($post['tags']) ? $post['tags'] : explode(',', $post['tags']);
                            $displayTag = $tagsArr[0] ?? 'Budgeting';
                        }
                    ?>
                    <span class="text-primary dark:text-accent"><?php echo htmlspecialchars($displayTag); ?></span>
                    <span>•</span>
                    <span><?php echo $post['reading_time'] ?? '5'; ?> min read</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-gray-900 dark:text-white mb-8 font-outfit leading-tight">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-primary dark:bg-accent flex items-center justify-center text-white dark:text-primary font-bold">
                        BB
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">SpendScribe Team</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400"><?php echo date('F d, Y', strtotime($post['created_at'])); ?></p>
                    </div>
                </div>
            </header>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto px-6 py-12">
        <div class="grid gap-12 lg:grid-cols-[2fr,1fr] max-w-6xl mx-auto">
            <article class="prose prose-blue dark:prose-invert max-w-none">
                <!-- Featured Image -->
                <?php if ($post['cover_image_url']): ?>
                    <div class="rounded-xl overflow-hidden shadow-sm mb-10 border dark:border-white/10">
                        <img 
                            src="<?php echo htmlspecialchars($post['cover_image_url']); ?>" 
                            alt="<?php echo htmlspecialchars($post['title']); ?>"
                            class="w-full h-auto"
                        >
                    </div>
                <?php endif; ?>

                <div class="text-gray-700 dark:text-slate-300 leading-relaxed text-lg space-y-6">
                    <?php 
                    if (!empty($post['content'])): 
                        $content = $post['content'];
                        
                        // If it's a JSON string that wasn't decoded, try again just in case
                        if (is_string($content) && (strpos($content, '[') === 0 || strpos($content, '{') === 0)) {
                            $decoded = json_decode($content, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $content = $decoded;
                            }
                        }

                        if (is_array($content)): 
                            foreach ($content as $block): 
                                if (!is_array($block)) continue;
                                ?>
                                <div class="mb-8">
                                    <?php if (isset($block['type']) && $block['type'] === 'heading'): ?>
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-10 mb-4 font-outfit"><?php echo htmlspecialchars($block['text'] ?? ''); ?></h2>
                                    
                                    <?php elseif (isset($block['type']) && $block['type'] === 'paragraph'): ?>
                                        <p class="mb-4"><?php echo $block['text'] ?? ''; ?></p>

                                    <?php elseif (isset($block['type']) && $block['type'] === 'image'): ?>
                                        <figure class="my-10">
                                            <img src="<?php echo htmlspecialchars($block['url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($block['alt'] ?? ''); ?>" class="rounded-lg w-full border dark:border-white/10">
                                            <?php if (!empty($block['caption'])): ?>
                                                <figcaption class="text-center text-sm text-gray-500 dark:text-slate-400 mt-3 italic"><?php echo htmlspecialchars($block['caption']); ?></figcaption>
                                            <?php endif; ?>
                                        </figure>

                                    <?php elseif (isset($block['type']) && $block['type'] === 'quote'): ?>
                                        <blockquote class="border-l-4 border-primary dark:border-accent pl-6 py-2 my-8 italic text-xl text-gray-900 dark:text-slate-200 font-medium">
                                            "<?php echo htmlspecialchars($block['text'] ?? ''); ?>"
                                            <?php if (!empty($block['caption'])): ?>
                                                <footer class="text-sm text-gray-500 dark:text-slate-400 mt-2 not-italic">— <?php echo htmlspecialchars($block['caption']); ?></footer>
                                            <?php endif; ?>
                                        </blockquote>

                                    <?php elseif (isset($block['type']) && $block['type'] === 'list'): ?>
                                        <ul class="list-disc pl-6 space-y-2">
                                            <?php if (isset($block['items']) && is_array($block['items'])): ?>
                                                <?php foreach ($block['items'] as $item): ?>
                                                    <li><?php echo htmlspecialchars($item); ?></li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php 
                            endforeach; 
                        else: 
                            // It's a string (HTML)
                            ?>
                            <div class="raw-content prose-headings:font-outfit prose-headings:font-bold prose-p:leading-relaxed prose-a:text-primary dark:prose-a:text-accent font-medium">
                                <?php echo $content; ?>
                            </div>
                        <?php 
                        endif; 
                    endif; 
                    ?>
                </div>

                    <!-- Google AdSense - In-Article/Multiplex Ad -->
                    <div class="my-12 pt-8 border-t border-gray-100 dark:border-white/5">
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-format="autorelaxed"
                         data-ad-client="ca-pub-6388735455910610"
                         data-ad-slot="7562938709"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    </div>

                    <!-- Tags -->
                    <footer class="mt-16 pt-8 border-t border-gray-100 dark:border-white/5">
                    <div class="flex flex-wrap gap-2">
                        <?php 
                            if (!empty($post['tags'])): 
                                $tagsList = is_array($post['tags']) ? $post['tags'] : explode(',', $post['tags']);
                                foreach ($tagsList as $tag): 
                                    $tag = trim($tag);
                                    if (empty($tag)) continue;
                        ?>
                                <a href="<?php echo BASE_URL; ?>/blog?tag=<?php echo urlencode($tag); ?>" class="px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-800 text-sm text-gray-600 dark:text-slate-400 hover:bg-primary dark:hover:bg-accent hover:text-white dark:hover:text-primary transition-colors border dark:border-white/5">
                                    #<?php echo htmlspecialchars($tag); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </footer>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-slate-900 p-8 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 font-outfit">Related reads</h2>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mb-6 font-medium leading-relaxed">Explore more articles that align with this topic.</p>
                    
                    <div class="space-y-6">
                        <p class="text-sm text-gray-400 dark:text-slate-500 italic">More stories are on the way.</p>
                    </div>

                    <div class="mt-10 pt-10 border-t border-gray-200 dark:border-white/5">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2 font-outfit">Bring SpendScribe along</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-6 font-medium leading-relaxed">Track every goal, automate savings, and collaborate with the people who matter.</p>
                        <a href="<?php echo BASE_URL; ?>/register" class="block w-full text-center py-3 bg-primary dark:bg-accent text-white dark:text-primary font-black uppercase tracking-widest text-xs rounded-xl hover:scale-105 transition-all shadow-lg shadow-primary/20 dark:shadow-none">
                            Create free account
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
