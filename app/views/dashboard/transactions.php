<?php
/**
 * Transactions View - Adapted from React Transactions.tsx
 */
?>

<div class="space-y-6">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">Transactions</h1>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo BASE_URL; ?>/transactions/export?<?php echo http_build_query($_GET); ?>" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i data-lucide="download" class="mr-2 h-4 w-4"></i>
                Export CSV
            </a>
            <button onclick="document.getElementById('add-tx-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors">
                <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                Add
            </button>
        </div>
    </header>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <form method="GET" action="<?php echo BASE_URL; ?>/transactions" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="space-y-1">
                <label for="filter_search" class="text-[10px] font-bold text-gray-500 uppercase">Search</label>
                <input id="filter_search" type="text" name="search" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" placeholder="Description..." class="w-full h-9 border border-gray-300 rounded-md px-3 text-xs outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div class="space-y-1">
                <label for="filter_category_id" class="text-[10px] font-bold text-gray-500 uppercase">Category</label>
                <select id="filter_category_id" name="category_id" class="w-full h-9 border border-gray-300 rounded-md px-3 text-xs outline-none bg-white">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="filter_account_id" class="text-[10px] font-bold text-gray-500 uppercase">Account</label>
                <select id="filter_account_id" name="account_id" class="w-full h-9 border border-gray-300 rounded-md px-3 text-xs outline-none bg-white">
                    <option value="">All Accounts</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>" <?php echo (isset($filters['account_id']) && $filters['account_id'] == $acc['id']) ? 'selected' : ''; ?>>
                            <?php echo $acc['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="filter_type" class="text-[10px] font-bold text-gray-500 uppercase">Type</label>
                <select id="filter_type" name="type" class="w-full h-9 border border-gray-300 rounded-md px-3 text-xs outline-none bg-white">
                    <option value="">All Types</option>
                    <option value="income" <?php echo ($filters['type'] ?? '') === 'income' ? 'selected' : ''; ?>>Income</option>
                    <option value="expense" <?php echo ($filters['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="filter_start_date" class="text-[10px] font-bold text-gray-500 uppercase">From Date</label>
                <input id="filter_start_date" type="date" name="start_date" value="<?php echo $filters['start_date'] ?? ''; ?>" class="w-full h-9 border border-gray-300 rounded-md px-3 text-xs outline-none">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 h-9 bg-gray-900 text-white text-xs font-bold rounded-md hover:bg-gray-800 transition-colors">Apply</button>
                <a href="<?php echo BASE_URL; ?>/transactions" class="h-9 w-9 flex items-center justify-center bg-gray-100 text-gray-500 rounded-md hover:bg-gray-200" title="Reset Filters">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Add Form (Hidden by default) -->
    <div id="add-tx-form" class="hidden bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4 font-outfit">New Transaction</h3>
        <form action="<?php echo BASE_URL; ?>/transactions/create" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="space-y-1">
                <label for="amount" class="text-xs font-bold text-gray-500 uppercase">Amount</label>
                <input id="amount" type="number" name="amount" step="0.01" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label for="description" class="text-xs font-bold text-gray-500 uppercase">Description</label>
                <input id="description" type="text" name="description" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label for="category_id" class="text-xs font-bold text-gray-500 uppercase">Category</label>
                <select id="category_id" name="category_id" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                    <option value="">Uncategorized</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="account_id" class="text-xs font-bold text-gray-500 uppercase">Account</label>
                <select id="account_id" name="account_id" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="type" class="text-xs font-bold text-gray-500 uppercase">Type</label>
                <select id="type" name="type" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>
            <div class="flex items-end md:col-span-5">
                <button type="submit" class="w-full md:w-auto px-10 h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Save Transaction</button>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 font-outfit">History</h3>
        </div>

        <div class="divide-y divide-gray-100">
            <?php if (empty($transactions)): ?>
                <div class="p-12 text-center text-gray-500">
                    <p>No transactions found matching your filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($transactions as $tx): ?>
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-xl">
                            <?php if ($tx['category_emoji']): ?>
                                <span><?php echo $tx['category_emoji']; ?></span>
                            <?php else: ?>
                                <i data-lucide="<?php echo $tx['type'] === 'income' ? 'arrow-up-right' : 'arrow-down-left'; ?>" class="h-5 w-5 text-gray-500"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-medium text-gray-900"><?php echo htmlspecialchars($tx['description']); ?></p>
                                <?php if ($tx['is_transfer']): ?>
                                    <span class="px-1.5 py-0.5 bg-blue-100 text-blue-600 text-[8px] font-bold uppercase rounded">Transfer</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                 <span><?php echo htmlspecialchars($tx['account_name'] ?? 'N/A'); ?></span>
                                 <span>•</span>
                                 <span><?php echo htmlspecialchars($tx['category_name'] ?? ucfirst($tx['type'])); ?></span>
                                 <span>•</span>
                                 <span><?php echo date('M d, Y', strtotime($tx['date'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <p class="font-bold <?php echo $tx['type'] === 'income' ? 'text-green-600' : 'text-gray-900'; ?>">
                            <?php echo $tx['type'] === 'income' ? '+' : '-'; ?>$<?php echo number_format($tx['amount'], 2); ?>
                        </p>
                        <a href="<?php echo BASE_URL; ?>/transactions/delete/<?php echo $tx['id']; ?>" class="p-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all" onclick="return confirm('Delete this transaction?')">
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
