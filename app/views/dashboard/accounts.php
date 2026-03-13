<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-outfit">Accounts</h1>
            <p class="text-gray-500 text-sm sm:text-base">Manage your bank accounts, credit cards, and investments</p>
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
            <button onclick="document.getElementById('transfer-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form action="<?php echo BASE_URL; ?>/accounts/transfer" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">From Account</label>
                <select name="from_account_id" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm outline-none bg-white" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?> ($<?php echo number_format($acc['balance'], 2); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">To Account</label>
                <select name="to_account_id" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm outline-none bg-white" required>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Amount</label>
                <input type="number" name="amount" step="0.01" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm outline-none focus:ring-2 focus:ring-primary/20" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Execute Transfer</button>
            </div>
            <div class="md:col-span-4">
                <input type="text" name="description" placeholder="Optional notes..." class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm outline-none">
            </div>
        </form>
    </div>

    <!-- Add Account Form (Hidden by default) -->
    <div id="add-account-form" class="hidden bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4 font-outfit">New Account</h3>
        <form action="<?php echo BASE_URL; ?>/accounts/create" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Account Name</label>
                <input type="text" name="name" placeholder="e.g. Main Checking" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Type</label>
                <select name="type" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                    <option value="credit">Credit Card</option>
                    <option value="investment">Investment</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Initial Balance</label>
                <input type="number" name="balance" step="0.01" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="px-6 h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Save Account</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($accounts)): ?>
            <div class="col-span-full bg-white p-12 rounded-xl border border-gray-200 text-center text-gray-500">
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
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4 group relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-gray-50 flex items-center justify-center">
                            <i data-lucide="<?php echo $icon; ?>" class="h-5 w-5 <?php echo $iconColor; ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 truncate"><?php echo htmlspecialchars($acc['name']); ?></h3>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium"><?php echo htmlspecialchars($acc['type']); ?></p>
                        </div>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                        <button class="p-1 text-gray-400 hover:text-gray-900 transition-colors"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                        <a href="<?php echo BASE_URL; ?>/accounts/delete/<?php echo $acc['id']; ?>" class="p-1 text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('Delete this account?')"><i data-lucide="trash-2" class="h-4 w-4"></i></a>
                    </div>
                </div>

                <div class="space-y-1">
                    <p class="text-xs text-gray-500 font-medium">Available Balance</p>
                    <p class="text-2xl font-bold text-gray-900 font-outfit">$<?php echo number_format($acc['balance'], 2); ?></p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                    <span class="text-xs text-gray-400 font-mono">**** 1234</span>
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
