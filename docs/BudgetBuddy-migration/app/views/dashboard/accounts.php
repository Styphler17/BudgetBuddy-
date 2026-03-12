<?php /** Accounts View – full CRUD */ ?>

<?php if (!empty($flash['success'])): ?>
<div class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-accent/10 border border-accent/20 text-accent font-bold text-sm">
    <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['success']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto">✕</button>
</div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
<div class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-sm">
    <i data-lucide="alert-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['error']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto">✕</button>
</div>
<?php endif; ?>

<div class="space-y-10 animate-fade-in">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 font-outfit tracking-tight">Financial Accounts</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your bank accounts, credit cards, and investments.</p>
        </div>
        <button onclick="openModal('acc-create')" class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary px-6 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Add Account
        </button>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (empty($accounts)): ?>
        <div class="col-span-3 text-center py-20 glass-card">
            <div class="h-20 w-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="wallet" class="h-10 w-10 text-primary/30"></i>
            </div>
            <p class="text-gray-500 font-bold text-xl font-outfit">No accounts detected.</p>
            <p class="text-gray-400 text-sm mt-2">Add your first account to start tracking your assets!</p>
        </div>
        <?php else: ?>
        <?php foreach ($accounts as $acc):
            $icons = ['checking'=>'credit-card','savings'=>'piggy-bank','credit'=>'credit-card','investment'=>'line-chart','cash'=>'banknote'];
            $bgs   = ['checking'=>'bg-brand','savings'=>'bg-secondary','credit'=>'bg-rose-500','investment'=>'bg-amber-500','cash'=>'bg-accent'];
            $icon  = $icons[$acc['type']] ?? 'wallet';
            $bg    = $bgs[$acc['type']]   ?? 'bg-primary';
        ?>
        <div class="glass-card p-10 group hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-12 -top-12 h-40 w-40 <?php echo $bg; ?> opacity-[0.03] rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between mb-10 relative">
                <div class="h-16 w-16 <?php echo $bg; ?> rounded-[1.25rem] flex items-center justify-center text-white shadow-xl shadow-primary/10 group-hover:rotate-6 transition-all duration-500">
                    <i data-lucide="<?php echo $icon; ?>" class="h-7 w-7"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="openEditAcc(<?php echo htmlspecialchars(json_encode($acc)); ?>)" class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-primary hover:bg-primary/5 transition-all">
                        <i data-lucide="edit-3" class="h-5 w-5"></i>
                    </button>
                    <button onclick="openDeleteAcc(<?php echo $acc['id']; ?>, '<?php echo htmlspecialchars(addslashes($acc['name'])); ?>')" class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-2 relative">
                <h3 class="text-2xl font-black text-gray-900 font-outfit tracking-tighter"><?php echo htmlspecialchars($acc['name']); ?></h3>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"><?php echo htmlspecialchars($acc['type']); ?></span>
                    <div class="h-1.5 w-1.5 rounded-full bg-gray-200"></div>
                    <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em]"><?php echo htmlspecialchars($acc['currency'] ?? 'USD'); ?></span>
                </div>
            </div>
            <div class="mt-10 flex items-end justify-between relative">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 opacity-60">Available Balance</p>
                    <p class="text-4xl font-black text-gray-900 font-outfit tracking-tighter">
                        <?php echo $acc['balance'] < 0 ? '-' : ''; ?>$<?php echo number_format(abs($acc['balance']), 2); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Add card shortcut -->
        <button onclick="openModal('acc-create')" class="glass-card border-2 border-dashed border-gray-100 p-10 flex flex-col items-center justify-center text-center gap-6 hover:border-primary/40 hover:bg-primary/[0.02] hover:shadow-2xl transition-all group min-h-[320px]">
            <div class="h-20 w-20 bg-primary/5 rounded-3xl flex items-center justify-center text-gray-400 group-hover:bg-primary group-hover:text-white group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-inner">
                <i data-lucide="plus" class="h-10 w-10"></i>
            </div>
            <div class="space-y-2">
                <p class="text-xl font-black text-gray-900 font-outfit tracking-tight">Add New Account</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Link a new card or bank</p>
            </div>
        </button>
    </div>
</div>

<!-- CREATE MODAL -->
<div id="modal-acc-create" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('acc-create')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">Add Account</h2></div>
        <form method="POST" action="/BudgetBuddy-/accounts/create" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Account Name</label>
                <input type="text" name="name" required placeholder="e.g. Main Checking" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Account Type</label>
                <select name="type" class="bb-input w-full">
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                    <option value="credit">Credit Card</option>
                    <option value="investment">Investment</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Initial Balance ($)</label>
                <input type="number" name="balance" step="0.01" placeholder="0.00" value="0" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Currency</label>
                <select name="currency" class="bb-input w-full">
                    <option value="USD">USD – US Dollar</option>
                    <option value="EUR">EUR – Euro</option>
                    <option value="GBP">GBP – Pound</option>
                    <option value="JPY">JPY – Yen</option>
                    <option value="CAD">CAD – Canadian Dollar</option>
                    <option value="AUD">AUD – Australian Dollar</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('acc-create')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90">Add Account</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="modal-acc-edit" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('acc-edit')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">Edit Account</h2></div>
        <form method="POST" action="/BudgetBuddy-/accounts/update" class="space-y-4">
            <input type="hidden" name="id" id="edit-acc-id">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Account Name</label>
                <input type="text" name="name" id="edit-acc-name" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Account Type</label>
                <select name="type" id="edit-acc-type" class="bb-input w-full">
                    <option value="checking">Checking</option>
                    <option value="savings">Savings</option>
                    <option value="credit">Credit Card</option>
                    <option value="investment">Investment</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Balance ($)</label>
                <input type="number" name="balance" id="edit-acc-balance" step="0.01" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Currency</label>
                <select name="currency" id="edit-acc-currency" class="bb-input w-full">
                    <option value="USD">USD</option><option value="EUR">EUR</option>
                    <option value="GBP">GBP</option><option value="JPY">JPY</option>
                    <option value="CAD">CAD</option><option value="AUD">AUD</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('acc-edit')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="modal-acc-delete" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 text-center">
        <div class="h-16 w-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto">
            <i data-lucide="trash-2" class="h-8 w-8 text-rose-500"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Delete Account?</h2>
            <p class="text-sm text-gray-500 mt-2" id="delete-acc-name"></p>
        </div>
        <form method="POST" action="/BudgetBuddy-/accounts/delete" class="flex gap-3">
            <input type="hidden" name="id" id="delete-acc-id">
            <button type="button" onclick="closeModal('acc-delete')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
            <button type="submit" class="flex-1 h-11 rounded-2xl bg-rose-500 text-white text-sm font-bold">Delete</button>
        </form>
    </div>
</div>

<style>
.bb-input{background:rgba(16,35,127,.03);border:1px solid rgba(16,35,127,.08);border-radius:1rem;padding:.75rem 1rem;font-size:.875rem;font-weight:600;color:#111827;outline:none;transition:all .3s}
.bb-input:focus{background:#fff;border-color:rgba(16,35,127,.2);box-shadow:0 0 0 4px rgba(16,35,127,.05)}
</style>
<script>
function openModal(k){document.getElementById('modal-'+k).classList.remove('hidden')}
function closeModal(k){document.getElementById('modal-'+k).classList.add('hidden')}
document.querySelectorAll('.modal-overlay').forEach(el=>el.addEventListener('click',e=>{if(e.target===el)el.classList.add('hidden')}));

function openEditAcc(acc){
    document.getElementById('edit-acc-id').value=acc.id;
    document.getElementById('edit-acc-name').value=acc.name;
    document.getElementById('edit-acc-type').value=acc.type;
    document.getElementById('edit-acc-balance').value=acc.balance;
    document.getElementById('edit-acc-currency').value=acc.currency||'USD';
    openModal('acc-edit');
}
function openDeleteAcc(id,name){
    document.getElementById('delete-acc-id').value=id;
    document.getElementById('delete-acc-name').textContent='This will permanently delete "'+name+'".';
    openModal('acc-delete');
}
window.addEventListener('load',()=>lucide.createIcons());
</script>
