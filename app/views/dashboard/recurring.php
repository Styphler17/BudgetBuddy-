<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit">Recurring Transactions</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Automate your repeating income and expenses</p>
        </div>
        <button onclick="document.getElementById('add-recurring-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
            <i data-lucide="calendar-plus" class="h-4 w-4 mr-2"></i>
            Add Recurring
        </button>
    </div>

    <!-- Add Recurring Form (Hidden by default) -->
    <div id="add-recurring-form" class="hidden bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm animate-fade-in">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 font-outfit">New Recurring Rule</h3>
        <form action="<?php echo BASE_URL; ?>/recurring/create" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1">
                <label for="recurring_amount" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Amount</label>
                <input id="recurring_amount" type="number" name="amount" step="0.01" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
            </div>
            <div class="space-y-1">
                <label for="recurring_description" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Description</label>
                <input id="recurring_description" type="text" name="description" placeholder="e.g. Netflix Subscription" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
            </div>
            <div class="space-y-1">
                <label for="recurring_frequency" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Frequency</label>
                <select id="recurring_frequency" name="frequency" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly" selected>Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="recurring_type" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Type</label>
                <select id="recurring_type" name="type" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="recurring_account_id" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Account</label>
                <select id="recurring_account_id" name="account_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="recurring_category_id" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Category</label>
                <select id="recurring_category_id" name="category_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white">
                    <option value="">Uncategorized</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="recurring_start_date" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Start Date</label>
                <input id="recurring_start_date" type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900 dark:text-white" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Activate Rule</button>
            </div>
        </form>
    </div>

    <!-- Recurring Rules List -->
    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white font-outfit">Active Rules</h3>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-white/5">
            <?php if (empty($recurring)): ?>
                <div class="p-12 text-center text-gray-500 dark:text-slate-300">
                    <p>No recurring transactions set up yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recurring as $item): ?>
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-lg">
                            <i data-lucide="refresh-cw" class="h-5 w-5 text-gray-400 dark:text-slate-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($item['description']); ?></p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-300 mt-1">
                                 <span class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 rounded-md font-bold uppercase"><?php echo $item['frequency']; ?></span>
                                 <span>•</span>
                                 <span><?php echo htmlspecialchars($item['account_name']); ?></span>
                                 <span>•</span>
                                 <span>Next: <?php echo date('M d, Y', strtotime($item['next_run_date'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <p class="font-bold <?php echo $item['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white'; ?>">
                            <?php echo $item['type'] === 'income' ? '+' : '-'; ?>$<?php echo number_format($item['amount'], 2); ?>
                        </p>
                        <a href="<?php echo BASE_URL; ?>/recurring/delete/<?php echo $item['id']; ?>" class="p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-all opacity-0 group-hover:opacity-100" onclick="return confirm('Stop this recurring transaction?')">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>        
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
