<div class="min-h-screen bg-white">
    <!-- Breadcrumb and Hero Section -->
    <section class="bg-gray-50 py-10">
        <div class="container mx-auto max-w-4xl px-6">
            <nav class="mb-4 text-sm text-gray-500">
                <a href="/BudgetBuddy-/" class="hover:text-primary">Home</a>
                <span class="mx-2">&rarr;</span>
                <a href="/BudgetBuddy-/blog" class="hover:text-primary">Blog</a>
                <span class="mx-2">&rarr;</span>
                <span class="text-gray-900 font-medium truncate inline-block max-w-[200px] align-bottom"><?php echo htmlspecialchars($post['title']); ?></span>
            </nav>

            <a href="/BudgetBuddy-/blog" class="inline-flex items-center text-primary font-medium mb-6 hover:underline">
                <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>
                Back to all articles
            </a>

            <header>
                <div class="flex items-center gap-3 text-sm text-gray-500 font-bold uppercase tracking-widest mb-6">
                    <span class="text-primary"><?php echo $post['tags'][0] ?? 'Budgeting'; ?></span>
                    <span>•</span>
                    <span><?php echo $post['reading_time'] ?? '5'; ?> min read</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-gray-900 mb-8 font-outfit leading-tight">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                        BB
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">BudgetBuddy Team</p>
                        <p class="text-xs text-gray-500"><?php echo date('F d, Y', strtotime($post['created_at'])); ?></p>
                    </div>
                </div>
            </header>
        </div>
    </section>

    <!-- Main Content -->
    <section class="container mx-auto px-6 py-12">
        <div class="grid gap-12 lg:grid-cols-[2fr,1fr] max-w-6xl mx-auto">
            <article class="prose prose-blue max-w-none">
                <!-- Featured Image -->
                <?php if ($post['cover_image_url']): ?>
                    <div class="rounded-xl overflow-hidden shadow-sm mb-10">
                        <img 
                            src="<?php echo htmlspecialchars($post['cover_image_url']); ?>" 
                            alt="<?php echo htmlspecialchars($post['title']); ?>"
                            class="w-full h-auto"
                        >
                    </div>
                <?php endif; ?>

                <div class="text-gray-700 leading-relaxed text-lg space-y-6">
                    <?php if (!empty($post['content'])): ?>
                        <?php if (is_array($post['content'])): ?>
                            <?php foreach ($post['content'] as $block): ?>
                                <div class="mb-8">
                                    <?php if ($block['type'] === 'heading'): ?>
                                        <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-4"><?php echo htmlspecialchars($block['text']); ?></h2>
                                    
                                    <?php elseif ($block['type'] === 'paragraph'): ?>
                                        <p class="mb-4"><?php echo $block['text']; ?></p>

                                    <?php elseif ($block['type'] === 'image'): ?>
                                        <figure class="my-10">
                                            <img src="<?php echo htmlspecialchars($block['url']); ?>" alt="<?php echo htmlspecialchars($block['alt'] ?? ''); ?>" class="rounded-lg w-full">
                                            <?php if (!empty($block['caption'])): ?>
                                                <figcaption class="text-center text-sm text-gray-500 mt-3 italic"><?php echo htmlspecialchars($block['caption']); ?></figcaption>
                                            <?php endif; ?>
                                        </figure>

                                    <?php elseif ($block['type'] === 'quote'): ?>
                                        <blockquote class="border-l-4 border-primary pl-6 py-2 my-8 italic text-xl text-gray-900 font-medium">
                                            "<?php echo htmlspecialchars($block['text']); ?>"
                                            <?php if (!empty($block['caption'])): ?>
                                                <footer class="text-sm text-gray-500 mt-2 not-italic">— <?php echo htmlspecialchars($block['caption']); ?></footer>
                                            <?php endif; ?>
                                        </blockquote>

                                    <?php elseif ($block['type'] === 'list'): ?>
                                        <ul class="list-disc pl-6 space-y-2">
                                            <?php foreach ($block['items'] as $item): ?>
                                                <li><?php echo htmlspecialchars($item); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php echo $post['content']; // Render raw HTML from TinyMCE ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Tags -->
                <footer class="mt-16 pt-8 border-t border-gray-100">
                    <div class="flex flex-wrap gap-2">
                        <?php if (!empty($post['tags'])): ?>
                            <?php foreach ($post['tags'] as $tag): ?>
                                <a href="/BudgetBuddy-/blog?tag=<?php echo urlencode(trim($tag)); ?>" class="px-3 py-1 rounded-full bg-gray-100 text-sm text-gray-600 hover:bg-primary hover:text-white transition-colors">
                                    #<?php echo htmlspecialchars(trim($tag)); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </footer>
            </article>

            <!-- Sidebar -->
            <aside class="space-y-8">
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-8 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Related reads</h2>
                    <p class="text-sm text-gray-600 mb-6">Explore more articles that align with this topic.</p>
                    
                    <div class="space-y-6">
                        <p class="text-sm text-gray-400 italic">More stories are on the way.</p>
                    </div>

                    <div class="mt-10 pt-10 border-t border-gray-200">
                        <h3 class="font-bold text-gray-900 mb-2">Bring BudgetBuddy along</h3>
                        <p class="text-sm text-gray-600 mb-6">Track every goal, automate savings, and collaborate with the people who matter.</p>
                        <a href="/BudgetBuddy-/register" class="block w-full text-center py-3 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">
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

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
