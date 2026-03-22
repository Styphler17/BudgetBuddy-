<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Accounts</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Manage your bank accounts, credit cards, and investments</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="openModal('transfer-modal')" class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-slate-900 px-6 text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition-all shadow-sm w-full sm:w-auto">
                <i data-lucide="repeat" class="h-4 w-4 mr-2"></i>
                Transfer
            </button>
            <button onclick="openModal('add-account-modal')" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-6 text-sm font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 w-full sm:w-auto">
                <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                Add Account
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($accounts)): ?>
            <div class="col-span-full glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-16 rounded-[2rem] border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-slate-300 relative">
                    <div class="h-20 w-20 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="wallet" class="h-10 w-10 opacity-20"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Accounts Yet</h3>
                    <p class="max-w-xs mx-auto">Add your first account to start tracking your net worth and transactions!</p>
                </div>
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
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-200 dark:border-white/10 shadow-sm space-y-4 group relative h-full transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-gray-50 dark:bg-slate-800 flex items-center justify-center border border-gray-100 dark:border-white/5">
                                <i data-lucide="<?php echo $icon; ?>" class="h-6 w-6 <?php echo $iconColor; ?>"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white truncate max-w-[120px]"><?php echo htmlspecialchars($acc['name']); ?></h3>
                                <p class="text-[10px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black"><?php echo htmlspecialchars($acc['type']); ?></p>
                            </div>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button onclick='openEditAccountModal(<?php echo json_encode($acc); ?>)' class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                            <button onclick="confirmDeleteAccount(<?php echo $acc['id']; ?>, '<?php echo htmlspecialchars($acc['name']); ?>')" class="p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                        </div>
                    </div>

                    <div class="space-y-1 py-2">
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 uppercase tracking-widest font-black">Available Balance</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white font-outfit tracking-tight leading-none"><?php echo CurrencyHelper::format($acc['balance'], $acc['currency'] ?? $currency); ?></p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                            Active
                        </span>
                        <span class="text-xs text-gray-400 dark:text-slate-500 font-mono">**** 1234</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php 
// Capture Modals for Global Stack
ob_start(); 
?>
<!-- Transfer Modal -->
<div id="transfer-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('transfer-modal')"></div>
        <div class="relative w-full max-w-2xl glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">Transfer Funds</h3>
                    <button onclick="closeModal('transfer-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/accounts/transfer" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php echo BaseController::csrfField(); ?>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">From Account</label>
                        <select name="from_account_id" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                            <?php foreach ($accounts as $acc): ?>
                                <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?> ($<?php echo number_format($acc['balance'], 2); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">To Account</label>
                        <select name="to_account_id" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                            <?php foreach ($accounts as $acc): ?>
                                <option value="<?php echo $acc['id']; ?>"><?php echo $acc['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Amount</label>
                        <input type="number" name="amount" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Date</label>
                        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Notes</label>
                        <input type="text" name="description" placeholder="Optional notes..." class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <button type="submit" class="md:col-span-2 h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Complete Transfer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div id="add-account-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-account-modal')"></div>
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">New Account</h3>
                    <button onclick="closeModal('add-account-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/accounts/create" method="POST" class="space-y-5">
                    <?php echo BaseController::csrfField(); ?>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Name</label>
                        <input type="text" name="name" placeholder="e.g. Daily Spending" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Type</label>
                        <select name="type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="checking">Checking</option>
                            <option value="savings">Savings</option>
                            <option value="credit">Credit Card</option>
                            <option value="investment">Investment</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Currency</label>
                        <select name="currency" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="USD">USD ($) - US Dollar</option>
                            <option value="EUR">EUR (€) - Euro</option>
                            <option value="GBP">GBP (£) - British Pound</option>
                            <option value="JPY">JPY (¥) - Japanese Yen</option>
                            <option value="CAD">CAD ($) - Canadian Dollar</option>
                            <option value="AUD">AUD ($) - Australian Dollar</option>
                            <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                            <option value="NGN">NGN (₦) - Nigerian Naira</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Initial Balance</label>
                        <input type="number" name="balance" step="0.01" value="0.00" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>                    <button type="submit" class="w-full h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div id="edit-account-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit-account-modal')"></div>
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">Edit Account</h3>
                    <button onclick="closeModal('edit-account-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/accounts/update" method="POST" class="space-y-5">
                    <?php echo BaseController::csrfField(); ?>
                    <input type="hidden" name="id" id="edit_acc_id">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Name</label>
                        <input type="text" name="name" id="edit_acc_name" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Type</label>
                        <select name="type" id="edit_acc_type" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="checking">Checking</option>
                            <option value="savings">Savings</option>
                            <option value="credit">Credit Card</option>
                            <option value="investment">Investment</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Account Currency</label>
                        <select name="currency" id="edit_acc_currency" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                            <option value="USD">USD ($) - US Dollar</option>
                            <option value="EUR">EUR (€) - Euro</option>
                            <option value="GBP">GBP (£) - British Pound</option>
                            <option value="JPY">JPY (¥) - Japanese Yen</option>
                            <option value="CAD">CAD ($) - Canadian Dollar</option>
                            <option value="AUD">AUD ($) - Australian Dollar</option>
                            <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                            <option value="NGN">NGN (₦) - Nigerian Naira</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Current Balance</label>
                        <input type="number" name="balance" id="edit_acc_balance" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>                    <button type="submit" class="w-full h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Account Delete Modal -->
<div id="delete-account-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete-account-modal')"></div>
        <div class="relative w-full max-w-sm glowing-wrapper animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative text-center">
                <div class="h-20 w-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400">
                    <i data-lucide="alert-triangle" class="h-10 w-10"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit mb-2 leading-tight">Delete Account?</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Are you sure you want to delete <span id="delete_acc_name" class="font-bold text-gray-900 dark:text-white"></span>? This will also remove all associated transactions.
                </p>
                <div class="flex flex-col gap-3">
                    <a id="confirm_delete_acc_btn" href="#" class="w-full h-14 bg-red-600 text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-red-700 transition-all flex items-center justify-center shadow-lg shadow-red-600/20">
                        Delete Permanently
                    </a>
                    <button onclick="closeModal('delete-account-modal')" class="w-full h-14 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">
                        Keep Account
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
    function openEditAccountModal(acc) {
        document.getElementById('edit_acc_id').value = acc.id;
        document.getElementById('edit_acc_name').value = acc.name;
        document.getElementById('edit_acc_type').value = acc.type;
        document.getElementById('edit_acc_balance').value = acc.balance;
        document.getElementById('edit_acc_currency').value = acc.currency || 'USD';
        openModal('edit-account-modal');
    }

    function confirmDeleteAccount(id, name) {
        document.getElementById('delete_acc_name').textContent = name;
        document.getElementById('confirm_delete_acc_btn').href = '<?php echo BASE_URL; ?>/accounts/delete/' + id;
        openModal('delete-account-modal');
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
