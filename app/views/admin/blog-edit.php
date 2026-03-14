<div class="space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit"><?php echo $mode === 'create' ? 'Create New Post' : 'Edit Post'; ?></h1>
            <p class="text-sm text-gray-500"><?php echo $mode === 'create' ? 'Draft a new story for your audience.' : 'Update your existing blog story.'; ?></p>
        </div>
        <a href="<?php echo BASE_URL; ?>/admin/blog" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to list
        </a>
    </header>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
        <form id="blog-edit-form" action="" method="POST" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="blog_title" class="text-sm font-bold text-gray-700 dark:text-slate-300">Title</label>
                    <input id="blog_title" type="text" name="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" placeholder="Enter post title" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800" required>
                </div>
                <div class="space-y-2">
                    <label for="blog_slug" class="text-sm font-bold text-gray-700 dark:text-slate-300">Slug</label>
                    <input id="blog_slug" type="text" name="slug" value="<?php echo htmlspecialchars($post['slug'] ?? ''); ?>" placeholder="leave-blank-for-auto" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800">
                </div>
            </div>

            <div class="space-y-2">
                <label for="blog_excerpt" class="text-sm font-bold text-gray-700 dark:text-slate-300">Excerpt</label>
                <textarea id="blog_excerpt" name="excerpt" rows="3" class="w-full border border-gray-300 dark:border-white/10 rounded-md p-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800 resize-none" placeholder="Short summary for the listing page..."><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
            </div>

            <div class="space-y-2">
                <label for="blog-content" class="text-sm font-bold text-gray-700 dark:text-slate-300">Content Body</label>
                <textarea id="blog-content" name="content" class="hidden"><?php 
                    if (isset($post['content'])) {
                        if (is_array($post['content'])) {
                            echo htmlspecialchars(json_encode($post['content']));
                        } else {
                            echo htmlspecialchars($post['content']);
                        }
                    }
                ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="cover_image_url" class="text-sm font-bold text-gray-700 dark:text-slate-300">Cover Image URL</label>
                    <div class="flex gap-2">
                        <input type="text" id="cover_image_url" name="cover_image_url" value="<?php echo htmlspecialchars($post['cover_image_url'] ?? ''); ?>" placeholder="https://..." class="flex-1 h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800">
                        <input type="file" id="blog-image-input" class="hidden" accept="image/*">
                        <button type="button" onclick="document.getElementById('blog-image-input').click()" class="px-3 h-10 border border-gray-300 dark:border-white/10 rounded-md bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors" title="Upload Image">
                            <i data-lucide="upload" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="blog_reading_time" class="text-sm font-bold text-gray-700 dark:text-slate-300">Reading Time (minutes)</label>
                    <input id="blog_reading_time" type="number" name="reading_time" value="<?php echo $post['reading_time'] ?? 5; ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="blog_status" class="text-sm font-bold text-gray-700 dark:text-slate-300">Status</label>
                    <select id="blog_status" name="status" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none appearance-none bg-white dark:bg-slate-800">
                        <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="archived" <?php echo ($post['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="blog_tags" class="text-sm font-bold text-gray-700 dark:text-slate-300">Tags (comma separated)</label>
                    <input id="blog_tags" type="text" name="tags" value="<?php echo htmlspecialchars(is_array($post['tags'] ?? null) ? implode(',', $post['tags']) : ($post['tags'] ?? '')); ?>" placeholder="budgeting, goals, news" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex justify-end gap-3">
                <a href="<?php echo BASE_URL; ?>/admin/blog" class="px-6 py-2 border border-gray-300 dark:border-white/10 rounded-md text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary dark:bg-accent text-white dark:text-primary font-bold rounded-md hover:bg-primary/90 transition-all shadow-md active:scale-95">
                    <?php echo $mode === 'create' ? 'Create Post' : 'Save Changes'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();

        const form = document.getElementById('blog-edit-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            ToastSave.loading();
            setTimeout(() => {
                form.submit();
            }, 800);
        });

        // Image upload logic
        document.getElementById('blog-image-input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            fetch('<?php echo BASE_URL; ?>/admin/blog/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    document.getElementById('cover_image_url').value = data.url;
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during upload.');
            });
        });

        if (window.tinymce) {
            tinymce.init({
                selector: '#blog-content',
                height: 500,
                menubar: false,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                content_style: 'body { font-family:Inter,Arial,sans-serif; font-size:16px; }',
                branding: false,
                promotion: false
            });
        }
    });
</script>
