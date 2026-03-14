<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit">Category Management</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Manage your budget categories and spending limits</p>
        </div>
        <button onclick="openModal('add-cat-modal')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
            Add Category
        </button>
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
                $spent = 0; // This should ideally be calculated or passed from controller
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
                            <button onclick='openEditModal(<?php echo json_encode($cat); ?>)' class="p-1 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                            </button>
                            <button onclick="confirmDelete(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars($cat['name']); ?>')" class="p-1 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                            </button>
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

<!-- Add Category Modal -->
<div id="add-cat-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('add-cat-modal')"></div>
        
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-2xl relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">New Category</h3>
                    <button onclick="closeModal('add-cat-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form action="<?php echo BASE_URL; ?>/categories/create" method="POST" class="space-y-4">
                    <div class="space-y-1">
                        <label for="category_name" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Category Name</label>
                        <input id="category_name" type="text" name="name" placeholder="e.g. Groceries" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required>
                    </div>
                    <div class="space-y-1">
                        <label for="category_budget" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Monthly Budget Limit</label>
                        <input id="category_budget" type="number" name="budget" step="0.01" value="0.00" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required>
                    </div>
                    <div class="space-y-1">
                        <label for="category_emoji" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Emoji Icon</label>
                        <div class="relative">
                            <input id="category_emoji" type="text" name="emoji" placeholder="🛒" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required maxlength="4">
                            <div class="absolute right-3 top-2.5 flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-50 dark:bg-slate-700 border border-gray-100 dark:border-white/5 pointer-events-none">
                                <span class="text-[10px] font-bold text-gray-400 dark:text-slate-400">Hint:</span>
                                <kbd class="text-[10px] font-bold text-gray-500 dark:text-slate-300">Win + .</kbd>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 italic">On Desktop: Press <span class="font-bold">Win + .</span> (Windows) or <span class="font-bold">Cmd + Ctrl + Space</span> (Mac) to insert emoji.</p>
                    </div>
                    
                    <button type="submit" class="w-full h-12 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all transform active:scale-[0.98] shadow-lg shadow-primary/20 mt-4">
                        Create Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-cat-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal('edit-cat-modal')"></div>
        
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-200 dark:border-white/10 shadow-2xl relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">Edit Category</h3>
                    <button onclick="closeModal('edit-cat-modal')" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form action="<?php echo BASE_URL; ?>/categories/update" method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="edit_cat_id">
                    <div class="space-y-1">
                        <label for="edit_cat_name" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Category Name</label>
                        <input id="edit_cat_name" type="text" name="name" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required>
                    </div>
                    <div class="space-y-1">
                        <label for="edit_cat_budget" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Monthly Budget Limit</label>
                        <input id="edit_cat_budget" type="number" name="budget" step="0.01" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required>
                    </div>
                    <div class="space-y-1">
                        <label for="edit_cat_emoji" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Emoji Icon</label>
                        <div class="relative">
                            <input id="edit_cat_emoji" type="text" name="emoji" class="w-full h-11 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-800 dark:text-white transition-all" required maxlength="4">
                            <div class="absolute right-3 top-2.5 flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-50 dark:bg-slate-700 border border-gray-100 dark:border-white/5 pointer-events-none">
                                <kbd class="text-[10px] font-bold text-gray-500 dark:text-slate-300">Win + .</kbd>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full h-12 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all transform active:scale-[0.98] shadow-lg shadow-primary/20 mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Redesigned Delete Modal -->
<div id="delete-confirm-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('delete-confirm-modal')"></div>
        
        <div class="relative w-full max-w-sm glowing-wrapper animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-gray-200 dark:border-white/10 shadow-2xl relative z-10 text-center">
                <div class="h-20 w-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400">
                    <i data-lucide="alert-triangle" class="h-10 w-10"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white font-outfit mb-2">Delete Category?</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Are you sure you want to delete <span id="delete_cat_name" class="font-bold text-gray-900 dark:text-white"></span>? This action cannot be undone.
                </p>

                <div class="flex flex-col gap-3">
                    <a id="confirm_delete_btn" href="#" class="w-full h-12 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all flex items-center justify-center shadow-lg shadow-red-600/20">
                        Yes, Delete Category
                    </a>
                    <button onclick="closeModal('delete-confirm-modal')" class="w-full h-12 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(cat) {
        document.getElementById('edit_cat_id').value = cat.id;
        document.getElementById('edit_cat_name').value = cat.name;
        document.getElementById('edit_cat_budget').value = cat.budget;
        document.getElementById('edit_cat_emoji').value = cat.emoji;
        openModal('edit-cat-modal');
    }

    function confirmDelete(id, name) {
        document.getElementById('delete_cat_name').textContent = name;
        document.getElementById('confirm_delete_btn').href = '<?php echo BASE_URL; ?>/categories/delete/' + id;
        openModal('delete-confirm-modal');
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
