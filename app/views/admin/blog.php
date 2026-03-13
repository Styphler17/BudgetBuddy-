<?php
/**
 * Admin Blog Management View
 */
?>

<div class="space-y-8">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">Blog Stories</h1>
            <p class="text-sm text-gray-500">Create, publish, and maintain engaging content for your audience.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo BASE_URL; ?>/admin/blog/create" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors">
                <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                New Story
            </a>
        </div>
    </header>

    <!-- Stats Badges -->
    <div class="flex flex-wrap items-center gap-2 text-sm">
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">Total: <?php echo $stats['total'] ?? 0; ?></span>
        <span class="px-3 py-1 rounded-full bg-primary/10 text-primary font-medium">Published: <?php echo $stats['published'] ?? 0; ?></span>
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Drafts: <?php echo $stats['draft'] ?? 0; ?></span>
        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">Archived: <?php echo $stats['archived'] ?? 0; ?></span>
    </div>

    <!-- Articles List -->
    <div class="space-y-4">
        <?php if (empty($articles)): ?>
            <div class="bg-white p-12 rounded-xl border border-gray-200 text-center text-gray-500">
                <i data-lucide="file-text" class="h-10 w-10 mx-auto mb-4 opacity-20"></i>
                <p>No blog posts found. Create your first story to get started.</p>
            </div>
        <?php else: ?>
            <?php foreach ($articles as $a): 
                $status = $a['status'] ?? 'draft';
            ?>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="h-16 w-24 bg-gray-100 rounded-lg overflow-hidden border border-gray-100 shrink-0">
                        <?php if(!empty($a['cover_image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($a['cover_image_url']); ?>" alt="Cover" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="image" class="h-5 w-5 text-gray-300"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 truncate group-hover:text-primary transition-colors"><?php echo htmlspecialchars($a['title']); ?></h3>
                        <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-500">
                            <span class="font-medium"><?php echo $a['reading_time'] ?? 1; ?> min read</span>
                            <span>•</span>
                            <span>Updated <?php echo date('M d, Y', strtotime($a['updated_at'] ?? $a['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 shrink-0 justify-between md:justify-end">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                        <?php echo $status === 'published' ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-500'; ?>">
                        <?php echo $status; ?>
                    </span>
                    
                    <div class="flex items-center gap-1">
                        <?php if ($status === 'published'): ?>
                            <a href="<?php echo BASE_URL; ?>/blog/<?php echo $a['slug']; ?>" target="_blank" class="p-2 text-gray-400 hover:text-primary transition-colors" title="View live">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>/admin/blog/edit/<?php echo $a['id']; ?>" class="p-2 text-gray-400 hover:text-gray-900 transition-colors" title="Edit">
                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/blog/delete/<?php echo $a['id']; ?>" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Delete" onclick="return confirm('Are you sure you want to delete this post?')">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
