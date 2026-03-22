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
                    <?php echo BaseController::csrfField(); ?>
                    <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
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
