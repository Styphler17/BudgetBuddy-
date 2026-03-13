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
        <button onclick="document.getElementById('add-tx-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground hover:bg-primary/90 transition-colors">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Add
        </button>
    </header>

    <!-- Add Form (Hidden by default) -->
    <div id="add-tx-form" class="hidden bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4 font-outfit">New Transaction</h3>
        <form action="<?php echo BASE_URL; ?>/transactions/create" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Amount</label>
                <input type="number" name="amount" step="0.01" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Description</label>
                <input type="text" name="description" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Type</label>
                <select name="type" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Save</button>
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
                    <p>No transactions found.</p>
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
                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($tx['description']); ?></p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
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
