<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-outfit">Accounts</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Manage your bank accounts, credit cards, and investments</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <button onclick="document.getElementById('transfer-modal').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors w-full sm:w-auto">
                <i data-lucide="repeat" class="h-4 w-4 mr-2"></i>
                Transfer
            </button>
            <button onclick="document.getElementById('add-account-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
                <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                Add Account
            </button>
        </div>
    </div>

    <!-- Transfer Modal (Hidden by default) -->
    <div id="transfer-modal" class="hidden bg-white p-6 rounded-xl border border-gray-200 shadow-sm animate-fade-in">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 font-outfit">Transfer Between Accounts</h3>
            <button onclick="document.getElementById('transfer-modal').classList.add('hidden')" class="text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-white">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form action="<?php echo BASE_URL; ?>/accounts/transfer" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1">
                <label for="from_account_id" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">From Account</label>
                <select id="from_account_id" name="from_account_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm outline-none bg-white dark:bg-slate-900" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?> ($<?php echo number_format($acc['balance'], 2); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="to_account_id" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">To Account</label>
                <select id="to_account_id" name="to_account_id" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm outline-none bg-white dark:bg-slate-900" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label for="transfer_amount" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Amount</label>
                <input id="transfer_amount" type="number" name="amount" step="0.01" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm outline-none focus:ring-2 focus:ring-primary/20 bg-white dark:bg-slate-900" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Execute Transfer</button>
            </div>
            <div class="md:col-span-4">
                <input id="transfer_description" type="text" name="description" placeholder="Optional notes..." class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm outline-none bg-white dark:bg-slate-900">
            </div>
        </form>
    </div>

    <!-- Add Account Form (Hidden by default) -->
    <div id="add-account-form" class="hidden bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 font-outfit">New Account</h3>
        <form action="<?php echo BASE_URL; ?>/accounts/create" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label for="account_name" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Account Name</label>
                <input id="account_name" type="text" name="name" placeholder="e.g. Main Checking" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900" required>
            </div>
            <div class="space-y-1">
                <label for="account_type" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Type</label>
                <select id="account_type" name="type" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900">
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                    <option value="credit">Credit Card</option>
                    <option value="investment">Investment</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="account_balance" class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase">Initial Balance</label>
                <input id="account_balance" type="number" name="balance" step="0.01" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white dark:bg-slate-900" required>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="px-6 h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Save Account</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($accounts)): ?>
            <div class="col-span-full bg-white dark:bg-slate-900 p-12 rounded-xl border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-slate-300">
                <i data-lucide="wallet" class="h-12 w-12 mx-auto mb-4 opacity-20"></i>
                <p>No accounts found. Add your first account to start tracking!</p>
            </div>
        <?php else: ?>
            <?php foreach ($accounts as $acc): 
                $icon = 'wallet';
                $iconColor = 'text-primary';
                
                switch($acc['type']) {
                    case 'checking': $icon = 'credit-card'; $iconColor = 'text-blue-500'; break;
                    case 'savings': $icon = 'piggy-bank'; $iconColor = 'text-green-500'; break;
                    case 'credit': $icon = 'credit-card'; $iconColor = 'text-red-500'; break;
                    case 'investment': $icon = 'trending-up'; $iconColor = 'text-purple-500'; break;
                    case 'cash': $icon = 'banknote'; $iconColor = 'text-orange-500'; break;
                }
            ?>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 group relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                            <i data-lucide="<?php echo $icon; ?>" class="h-5 w-5 <?php echo $iconColor; ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white truncate"><?php echo htmlspecialchars($acc['name']); ?></h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-medium"><?php echo htmlspecialchars($acc['type']); ?></p>
                        </div>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                        <button class="p-1 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                        <a href="<?php echo BASE_URL; ?>/accounts/delete/<?php echo $acc['id']; ?>" class="p-1 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors" onclick="return confirm('Delete this account?')"><i data-lucide="trash-2" class="h-4 w-4"></i></a>
                    </div>
                </div>

                <div class="space-y-1">
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Available Balance</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white font-outfit">$<?php echo number_format($acc['balance'], 2); ?></p>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                        Active
                    </span>
                    <span class="text-xs text-gray-400 dark:text-slate-500 font-mono">**** 1234</span>
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
