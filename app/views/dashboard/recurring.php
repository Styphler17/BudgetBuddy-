<div class="space-y-6">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Recurring Transactions</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Automate your repeating income and expenses</p>
        </div>
        <button onclick="openModal('add-recurring-modal')" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-5 text-sm font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 w-full sm:w-auto">
            <i data-lucide="calendar-plus" class="h-4 w-4 mr-2"></i>
            Add Recurring
        </button>
    </header>

    <!-- Recurring Rules List -->
    <div class="glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="glass-card overflow-hidden relative">
            <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white font-outfit">Active Automations</h3>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-white/5">
            <?php if (empty($recurring)): ?>
                <div class="p-20 text-center text-gray-500 dark:text-slate-300">
                    <div class="h-16 w-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="h-8 w-8 opacity-20"></i>
                    </div>
                    <p class="font-medium">No recurring transactions set up yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($recurring as $item): ?>
                <div class="flex items-center justify-between p-5 hover:bg-gray-50/50 dark:hover:bg-slate-800/50 transition-all group">
                    <div class="flex items-center gap-6">
                        <div class="h-12 w-12 rounded-2xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center border border-gray-200/50 dark:border-white/5">
                            <i data-lucide="refresh-cw" class="h-6 w-6 text-primary animate-spin-slow"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($item['description']); ?></p>
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 text-[8px] font-black uppercase rounded-lg tracking-widest"><?php echo $item['frequency']; ?></span>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] text-gray-500 dark:text-slate-400 mt-1 uppercase tracking-widest font-black">
                                 <span class="flex items-center gap-1"><i data-lucide="wallet" class="h-3 w-3"></i> <?php echo htmlspecialchars($item['account_name']); ?></span>
                                 <span class="opacity-30">•</span>
                                 <span class="flex items-center gap-1 text-primary dark:text-accent"><i data-lucide="calendar" class="h-3 w-3"></i> Next: <?php echo date('M d, Y', strtotime($item['next_run_date'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <p class="text-lg font-black <?php echo $item['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white'; ?> font-outfit">
                            <?php echo $item['type'] === 'income' ? '+' : '-'; ?>$<?php echo number_format($item['amount'], 2); ?>
                        </p>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button onclick='openEditRecurringModal(<?php echo json_encode($item); ?>)' class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                            <button onclick="confirmDeleteRecurring(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['description']); ?>')" class="p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
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
<!-- Add Recurring Modal -->
<div id="add-recurring-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-recurring-modal')"></div>
        <div class="relative w-full max-w-xl glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">New Automation</h3>
                    <button onclick="closeModal('add-recurring-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/recurring/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" placeholder="e.g. Rent, Netflix, Salary" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Amount</label>
                        <input type="number" name="amount" step="0.01" placeholder="0.00" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Frequency</label>
                        <select name="frequency" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
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
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Type</label>
                        <select name="type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Start Date</label>
                        <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <button type="submit" class="md:col-span-2 h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Activate Automation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Recurring Modal -->
<div id="edit-recurring-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit-recurring-modal')"></div>
        <div class="relative w-full max-w-xl glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">Edit Automation</h3>
                    <button onclick="closeModal('edit-recurring-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/recurring/update" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="id" id="edit_rec_id">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" id="edit_rec_desc" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Amount</label>
                        <input type="number" name="amount" id="edit_rec_amount" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Frequency</label>
                        <select name="frequency" id="edit_rec_freq" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account</label>
                        <select name="account_id" id="edit_rec_acc" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                            <?php foreach ($accounts as $acc): ?>
                                <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Category</label>
                        <select name="category_id" id="edit_rec_cat" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="">Uncategorized</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji']; ?> <?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Type</label>
                        <select name="type" id="edit_rec_type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="expense">Expense</option>
                            <option value="income">Income</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Start Date</label>
                        <input type="date" name="start_date" id="edit_rec_date" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <button type="submit" class="md:col-span-2 h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Update Automation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Recurring Delete Modal -->
<div id="delete-recurring-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete-recurring-modal')"></div>
        <div class="relative w-full max-w-sm glowing-wrapper animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative text-center">
                <div class="h-20 w-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400">
                    <i data-lucide="clock-off" class="h-10 w-10"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit mb-2 leading-tight">Stop Automation?</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Are you sure you want to cancel <span id="delete_rec_name" class="font-bold text-gray-900 dark:text-white"></span>? Future transactions won't be created.
                </p>
                <div class="flex flex-col gap-3">
                    <a id="confirm_delete_rec_btn" href="#" class="w-full h-14 bg-red-600 text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-red-700 transition-all flex items-center justify-center shadow-lg shadow-red-600/20">
                        Cancel Automation
                    </a>
                    <button onclick="closeModal('delete-recurring-modal')" class="w-full h-14 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">
                        Keep Active
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
    function openEditRecurringModal(item) {
        document.getElementById('edit_rec_id').value = item.id;
        document.getElementById('edit_rec_desc').value = item.description;
        document.getElementById('edit_rec_amount').value = item.amount;
        document.getElementById('edit_rec_freq').value = item.frequency;
        document.getElementById('edit_rec_acc').value = item.account_id;
        document.getElementById('edit_rec_cat').value = item.category_id || "";
        document.getElementById('edit_rec_type').value = item.type;
        document.getElementById('edit_rec_date').value = item.start_date;
        openModal('edit-recurring-modal');
    }

    function confirmDeleteRecurring(id, name) {
        document.getElementById('delete_rec_name').textContent = name;
        document.getElementById('confirm_delete_rec_btn').href = '<?php echo BASE_URL; ?>/recurring/delete/' + id;
        openModal('delete-recurring-modal');
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
