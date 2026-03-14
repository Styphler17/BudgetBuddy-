<div class="space-y-6">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Transactions</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Review and manage your financial history</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="<?php echo BASE_URL; ?>/transactions/export?<?php echo http_build_query($_GET); ?>" class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 px-6 text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm w-full sm:w-auto">
                <i data-lucide="download" class="h-4 w-4 mr-2"></i>
                Export CSV
            </a>
            <button onclick="openModal('add-transaction-modal')" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-6 text-sm font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 w-full sm:w-auto">
                <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                Add Transaction
            </button>
        </div>
    </header>

    <!-- Filter Bar -->
    <div class="glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm relative">
            <form method="GET" action="<?php echo BASE_URL; ?>/transactions" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Search</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" placeholder="Description..." class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-xs outline-none focus:ring-2 focus:ring-primary/20 bg-white dark:bg-slate-900 dark:text-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Category</label>
                    <select name="category_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-xs outline-none bg-white dark:bg-slate-900 dark:text-white transition-all">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo (isset($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account</label>
                    <select name="account_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-xs outline-none bg-white dark:bg-slate-900 dark:text-white transition-all">
                        <option value="">All Accounts</option>
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?php echo $acc['id']; ?>" <?php echo (isset($filters['account_id']) && $filters['account_id'] == $acc['id']) ? 'selected' : ''; ?>>
                                <?php echo $acc['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Type</label>
                    <select name="type" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-xs outline-none bg-white dark:bg-slate-900 dark:text-white transition-all">
                        <option value="">All Types</option>
                        <option value="income" <?php echo ($filters['type'] ?? '') === 'income' ? 'selected' : ''; ?>>Income</option>
                        <option value="expense" <?php echo ($filters['type'] ?? '') === 'expense' ? 'selected' : ''; ?>>Expense</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">From Date</label>
                    <input type="date" name="start_date" value="<?php echo $filters['start_date'] ?? ''; ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-xs outline-none bg-white dark:bg-slate-900 dark:text-white transition-all">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 h-10 bg-gray-900 dark:bg-accent text-white dark:text-accent-foreground text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 dark:hover:bg-accent/90 transition-all">Apply</button>
                    <a href="<?php echo BASE_URL; ?>/transactions" class="h-10 w-10 flex items-center justify-center bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-300 rounded-xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all" title="Reset Filters">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="glass-card overflow-hidden relative">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white font-outfit">History</h3>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-white/5">
            <?php if (empty($transactions)): ?>
                <div class="p-20 text-center text-gray-500 dark:text-slate-300">
                    <div class="h-16 w-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search-x" class="h-8 w-8 opacity-20"></i>
                    </div>
                    <p class="font-medium">No transactions found matching your filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($transactions as $tx): ?>
                <div class="flex items-center justify-between p-5 hover:bg-gray-50/50 dark:hover:bg-slate-800/50 transition-all group">
                    <div class="flex items-center gap-6">
                        <div class="h-12 w-12 rounded-2xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-2xl border border-gray-200/50 dark:border-white/5">
                            <?php if ($tx['category_emoji']): ?>
                                <span><?php echo $tx['category_emoji']; ?></span>
                            <?php else: ?>
                                <i data-lucide="<?php echo $tx['type'] === 'income' ? 'arrow-up-right' : 'arrow-down-left'; ?>" class="h-6 w-6 text-gray-500 dark:text-slate-300"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($tx['description']); ?></p>
                                <?php if ($tx['is_transfer']): ?>
                                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[8px] font-black uppercase rounded-lg tracking-tighter">Transfer</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] text-gray-500 dark:text-slate-400 mt-1 uppercase tracking-widest font-black">
                                 <span class="flex items-center gap-1"><i data-lucide="wallet" class="h-3 w-3"></i> <?php echo htmlspecialchars($tx['account_name'] ?? 'N/A'); ?></span>
                                 <span class="opacity-30">•</span>
                                 <span class="flex items-center gap-1"><i data-lucide="tag" class="h-3 w-3"></i> <?php echo htmlspecialchars($tx['category_name'] ?? ucfirst($tx['type'])); ?></span>
                                 <span class="opacity-30">•</span>
                                 <span class="flex items-center gap-1"><i data-lucide="calendar" class="h-3 w-3"></i> <?php echo date('M d, Y', strtotime($tx['date'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-black <?php echo $tx['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white'; ?> font-outfit">
                            <?php echo $tx['type'] === 'income' ? '+' : '-'; ?><?php echo CurrencyHelper::format($tx['amount'], $currency); ?>
                        </p>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button onclick='openEditTransactionModal(<?php echo json_encode($tx); ?>)' class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                            <button onclick="confirmDeleteTransaction(<?php echo $tx['id']; ?>, '<?php echo htmlspecialchars($tx['description']); ?>')" class="p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>        
    </div>
</div>

<?php 
// Capture Modals for Global Stack
ob_start(); 
?>
<!-- Add Transaction Modal -->
<div id="add-transaction-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-transaction-modal')"></div>
        <div class="relative w-full max-w-xl glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">New Transaction</h3>
                    <button onclick="closeModal('add-transaction-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/transactions/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" placeholder="e.g. Weekly Groceries" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Amount</label>
                        <input type="number" name="amount" step="0.01" placeholder="0.00" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Date</label>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account</label>
                        <select name="account_id" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                            <?php foreach ($accounts as $acc): ?>
                                <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Category</label>
                        <select name="category_id" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Uncategorized</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Type</label>
                        <select name="type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <button type="submit" class="md:col-span-2 h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Save Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Transaction Modal -->
<div id="edit-transaction-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit-transaction-modal')"></div>
        <div class="relative w-full max-w-xl glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">Edit Transaction</h3>
                    <button onclick="closeModal('edit-transaction-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/transactions/update" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="id" id="edit_tx_id">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" id="edit_tx_desc" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Amount</label>
                        <input type="number" name="amount" id="edit_tx_amount" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Date</label>
                        <input type="date" name="date" id="edit_tx_date" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account</label>
                        <select name="account_id" id="edit_tx_acc" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                            <?php foreach ($accounts as $acc): ?>
                                <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Category</label>
                        <select name="category_id" id="edit_tx_cat" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Uncategorized</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Type</label>
                        <select name="type" id="edit_tx_type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <button type="submit" class="md:col-span-2 h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Update Transaction
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Transaction Delete Modal -->
<div id="delete-transaction-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete-transaction-modal')"></div>
        <div class="relative w-full max-w-sm glowing-wrapper animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative text-center">
                <div class="h-20 w-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400">
                    <i data-lucide="trash-2" class="h-10 w-10"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit mb-2 leading-tight">Delete Record?</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Are you sure you want to delete <span id="delete_tx_name" class="font-bold text-gray-900 dark:text-white"></span>? This will affect your balance.
                </p>
                <div class="flex flex-col gap-3">
                    <a id="confirm_delete_tx_btn" href="#" class="w-full h-14 bg-red-600 text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-red-700 transition-all flex items-center justify-center shadow-lg shadow-red-600/20">
                        Delete Entry
                    </a>
                    <button onclick="closeModal('delete-transaction-modal')" class="w-full h-14 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">
                        Keep Record
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$GLOBALS['view_modal_content'] = ob_get_clean(); 
?>

<script>
    function openEditTransactionModal(tx) {
        document.getElementById('edit_tx_id').value = tx.id;
        document.getElementById('edit_tx_desc').value = tx.description;
        document.getElementById('edit_tx_amount').value = tx.amount;
        document.getElementById('edit_tx_date').value = tx.date;
        document.getElementById('edit_tx_acc').value = tx.account_id;
        document.getElementById('edit_tx_cat').value = tx.category_id || "";
        document.getElementById('edit_tx_type').value = tx.type;
        openModal('edit-transaction-modal');
    }

    function confirmDeleteTransaction(id, name) {
        document.getElementById('delete_tx_name').textContent = name;
        document.getElementById('confirm_delete_tx_btn').href = '<?php echo BASE_URL; ?>/transactions/delete/' + id;
        openModal('delete-transaction-modal');
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
