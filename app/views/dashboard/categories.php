<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit">Category Management</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Manage your budget categories and spending limits</p>
        </div>
        <button onclick="document.getElementById('add-cat-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
            Add Category
        </button>
    </div>

    <!-- Add Category Form (Hidden by default) -->
    <div id="add-cat-form" class="hidden glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm relative z-10">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 font-outfit">New Category</h3>
            <form action="<?php echo BASE_URL; ?>/categories/create" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label for="category_name" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Category Name</label>
                    <input id="category_name" type="text" name="name" placeholder="e.g. Groceries" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
                </div>
                <div class="space-y-1">
                    <label for="category_budget" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Budget Limit</label>
                    <input id="category_budget" type="number" name="budget" step="0.01" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
                </div>
                <div class="space-y-1">
                    <label for="category_emoji" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Emoji</label>
                    <input id="category_emoji" type="text" name="emoji" placeholder="🛒" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required maxlength="2">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="px-6 h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Create Category</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php if (empty($categories)): ?>
            <div class="col-span-full glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-12 rounded-xl border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-slate-300 relative z-10">
                    <i data-lucide="grid" class="h-12 w-12 mx-auto mb-4 opacity-20"></i>
                    <p>No categories yet. Add your first category to start organizing!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($categories as $cat): 
                $spent = 0; 
                $percentage = ($cat['budget'] > 0) ? ($spent / $cat['budget']) * 100 : 0;
                $percentage = min(100, round($percentage));
                $remaining = $cat['budget'] - $spent;
                $isOverBudget = $percentage >= 100;
            ?>
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border <?php echo $isOverBudget ? 'border-red-200 dark:border-red-900/50 bg-red-50/30 dark:bg-red-900/10' : 'border-gray-200 dark:border-white/10'; ?> shadow-sm space-y-4 group relative z-10 h-full">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-0">
                            <span class="text-2xl"><?php echo $cat['emoji'] ?? '📊'; ?></span>
                            <h3 class="font-bold text-gray-900 dark:text-white truncate"><?php echo htmlspecialchars($cat['name']); ?></h3>
                        </div>
                        <div class="flex gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-all">
                            <button class="p-1 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                            <a href="<?php echo BASE_URL; ?>/categories/delete/<?php echo $cat['id']; ?>" class="p-1 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors" onclick="return confirm('Delete this category?')"><i data-lucide="trash-2" class="h-4 w-4"></i></a>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-700 dark:text-slate-300">$<?php echo number_format($spent, 2); ?> / $<?php echo number_format($cat['budget'], 2); ?></span>
                            <span class="<?php echo $isOverBudget ? 'text-red-600 dark:text-red-400' : 'text-primary dark:text-accent'; ?> font-bold"><?php echo $percentage; ?>%</span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full <?php echo $isOverBudget ? 'bg-red-500' : 'bg-primary dark:bg-accent'; ?> transition-all duration-500" style="width: <?php echo $percentage; ?>%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-2">
                            <span class="<?php echo $remaining >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'; ?> font-medium">
                                <?php echo $remaining >= 0 ? '$'.number_format($remaining, 2).' remaining' : '$'.number_format(abs($remaining), 2).' over'; ?>
                            </span>
                            <span class="text-gray-400 dark:text-slate-500"><?php echo date('M d, Y', strtotime($cat['created_at'])); ?></span>
                        </div>
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
