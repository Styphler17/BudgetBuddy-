<?php
/**
 * Transactions View – full CRUD (create / edit / delete)
 */

// Shared modal helper CSS/JS injected once via a data-bb-modals attribute on body
$GLOBALS['_modal_styles_loaded'] = true;
?>

<?php if (!empty($flash['success'])): ?>
<div id="flash-success" class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-accent/10 border border-accent/20 text-accent font-bold text-sm">
    <i data-lucide="check-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['success']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto text-accent/60 hover:text-accent">✕</button>
</div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
<div id="flash-error" class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-sm">
    <i data-lucide="alert-circle" class="h-5 w-5 flex-shrink-0"></i>
    <?php echo htmlspecialchars($flash['error']); ?>
    <button onclick="this.parentElement.remove()" class="ml-auto text-rose-400 hover:text-rose-600">✕</button>
</div>
<?php endif; ?>

<div class="space-y-10 animate-fade-in">

    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 font-outfit tracking-tight">Financial Transactions</h1>
            <p class="text-gray-500 font-medium mt-1">Keep track of every dollar you spend or earn.</p>
        </div>
        <button onclick="openModal('tx-create')" class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary px-6 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            New Transaction
        </button>
    </header>

    <!-- Filters -->
    <div class="glass-card p-6 flex flex-col lg:flex-row gap-6 items-center">
        <div class="relative flex-1 group w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-primary transition-all duration-300"></i>
            <input type="text" id="tx-search" placeholder="Search by description or category…" class="w-full bg-primary/5 border border-primary/5 rounded-2xl py-3.5 pl-12 pr-4 text-sm font-medium outline-none focus:bg-white focus:border-primary/20 focus:ring-8 focus:ring-primary/5 transition-all duration-500">
        </div>
        <div class="flex flex-wrap gap-4 w-full lg:w-auto">
            <select id="tx-type-filter" class="bg-primary/5 border border-primary/5 rounded-2xl py-3.5 px-6 text-xs font-black uppercase tracking-widest text-gray-700 outline-none focus:bg-white transition-all appearance-none cursor-pointer">
                <option value="">All Types</option>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
            <select id="tx-sort" class="bg-primary/5 border border-primary/5 rounded-2xl py-3.5 px-6 text-xs font-black uppercase tracking-widest text-gray-700 outline-none focus:bg-white transition-all appearance-none cursor-pointer">
                <option value="date-desc">Newest First</option>
                <option value="date-asc">Oldest First</option>
                <option value="amount-desc">Highest Amount</option>
                <option value="amount-asc">Lowest Amount</option>
            </select>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="glass-card overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-white/50">
            <h3 class="text-xl font-bold text-gray-900 font-outfit">History</h3>
            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-lg uppercase tracking-tighter" id="tx-count"><?php echo count($transactions); ?> records</span>
        </div>

        <div id="tx-list" class="divide-y divide-gray-100/50">
            <?php if (empty($transactions)): ?>
            <div class="p-20 text-center">
                <div class="h-20 w-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="file-text" class="h-10 w-10 text-primary/30"></i>
                </div>
                <p class="text-gray-500 font-bold text-xl font-outfit">No activity yet.</p>
                <p class="text-gray-400 text-sm mt-2">Start adding transactions to see them here.</p>
            </div>
            <?php else: ?>
            <?php foreach ($transactions as $tx): ?>
            <div class="tx-row flex flex-col sm:flex-row sm:items-center justify-between p-7 gap-6 hover:bg-primary/[0.02] transition-all group relative"
                 data-desc="<?php echo strtolower(htmlspecialchars($tx['description'] ?? '')); ?>"
                 data-cat="<?php echo strtolower(htmlspecialchars($tx['category_name'] ?? '')); ?>"
                 data-type="<?php echo $tx['type']; ?>"
                 data-amount="<?php echo $tx['amount']; ?>"
                 data-date="<?php echo $tx['date']; ?>">
                <div class="flex items-center gap-6">
                    <div class="h-14 w-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                        <?php if ($tx['category_emoji']): ?>
                            <span><?php echo $tx['category_emoji']; ?></span>
                        <?php else: ?>
                            <i data-lucide="<?php echo $tx['type'] === 'income' ? 'arrow-up-right' : 'arrow-down-left'; ?>" class="h-6 w-6 text-gray-400"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900 leading-tight group-hover:text-primary transition-colors"><?php echo htmlspecialchars($tx['description'] ?: ($tx['category_name'] ?? 'Transaction')); ?></p>
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] bg-primary/5 px-2.5 py-1 rounded-md border border-primary/5">
                                <?php echo htmlspecialchars($tx['category_name'] ?? ucfirst($tx['type'])); ?>
                            </span>
                            <div class="h-1 w-1 rounded-full bg-gray-300"></div>
                            <span class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"><?php echo date('M d, Y', strtotime($tx['date'])); ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-10 pl-20 sm:pl-0">
                    <div class="text-right">
                        <p class="text-xl font-black font-outfit <?php echo $tx['type'] === 'income' ? 'text-accent' : 'text-gray-900'; ?>">
                            <?php echo $tx['type'] === 'income' ? '+' : '-'; ?>$<?php echo number_format($tx['amount'], 2); ?>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                        <button onclick="openEditTx(<?php echo htmlspecialchars(json_encode($tx)); ?>)"
                            class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-primary hover:bg-primary/5 transition-all">
                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                        </button>
                        <button onclick="openDeleteTx(<?php echo $tx['id']; ?>, '<?php echo htmlspecialchars(addslashes($tx['description'] ?: 'this transaction')); ?>')"
                            class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════ -->
<!-- CREATE MODAL -->
<!-- ══════════════════════════════════════════════════════════════ -->
<div id="modal-tx-create" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('tx-create')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all">✕</button>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">New Transaction</h2>
            <p class="text-sm text-gray-500 mt-1">Record an income or expense.</p>
        </div>
        <form method="POST" action="/BudgetBuddy-/transactions/create" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-2 p-3 rounded-2xl border-2 border-accent bg-accent/5 cursor-pointer has-[:checked]:border-accent has-[:checked]:bg-accent/10">
                    <input type="radio" name="type" value="income" checked class="accent-accent"> <span class="text-sm font-bold text-gray-700">Income</span>
                </label>
                <label class="flex items-center gap-2 p-3 rounded-2xl border-2 border-gray-200 cursor-pointer has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50">
                    <input type="radio" name="type" value="expense" class="accent-rose-500"> <span class="text-sm font-bold text-gray-700">Expense</span>
                </label>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Amount</label>
                <input type="number" name="amount" step="0.01" min="0" required placeholder="0.00" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Date</label>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category</label>
                <select name="category_id" class="bb-input w-full">
                    <option value="">No category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji'] ?? ''; ?> <?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Description</label>
                <input type="text" name="description" placeholder="What was this for?" class="bb-input w-full">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('tx-create')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Add Transaction</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="modal-tx-edit" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('tx-edit')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all">✕</button>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Edit Transaction</h2>
            <p class="text-sm text-gray-500 mt-1">Update this transaction's details.</p>
        </div>
        <form method="POST" action="/BudgetBuddy-/transactions/update" class="space-y-4" id="edit-tx-form">
            <input type="hidden" name="id" id="edit-tx-id">
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-2 p-3 rounded-2xl border-2 border-gray-200 cursor-pointer">
                    <input type="radio" name="type" value="income" id="edit-tx-type-income" class="accent-accent"> <span class="text-sm font-bold text-gray-700">Income</span>
                </label>
                <label class="flex items-center gap-2 p-3 rounded-2xl border-2 border-gray-200 cursor-pointer">
                    <input type="radio" name="type" value="expense" id="edit-tx-type-expense" class="accent-rose-500"> <span class="text-sm font-bold text-gray-700">Expense</span>
                </label>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Amount</label>
                <input type="number" name="amount" id="edit-tx-amount" step="0.01" min="0" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Date</label>
                <input type="date" name="date" id="edit-tx-date" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category</label>
                <select name="category_id" id="edit-tx-category" class="bb-input w-full">
                    <option value="">No category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji'] ?? ''; ?> <?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Description</label>
                <input type="text" name="description" id="edit-tx-desc" class="bb-input w-full">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('tx-edit')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="modal-tx-delete" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 relative text-center">
        <div class="h-16 w-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto">
            <i data-lucide="trash-2" class="h-8 w-8 text-rose-500"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Delete Transaction?</h2>
            <p class="text-sm text-gray-500 mt-2" id="delete-tx-name"></p>
        </div>
        <form method="POST" action="/BudgetBuddy-/transactions/delete">
            <input type="hidden" name="id" id="delete-tx-id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('tx-delete')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-rose-500 text-white text-sm font-bold hover:bg-rose-600 transition-all">Delete</button>
            </div>
        </form>
    </div>
</div>

<style>
.bb-input { background: rgba(16,35,127,0.03); border: 1px solid rgba(16,35,127,0.08); border-radius: 1rem; padding: 0.75rem 1rem; font-size: 0.875rem; font-weight: 600; color: #111827; outline: none; transition: all 0.3s; }
.bb-input:focus { background: #fff; border-color: rgba(16,35,127,0.2); box-shadow: 0 0 0 4px rgba(16,35,127,0.05); }
.bb-input select, select.bb-input { appearance: none; }
</style>

<script>
function openModal(key) { document.getElementById('modal-'+key).classList.remove('hidden'); }
function closeModal(key) { document.getElementById('modal-'+key).classList.add('hidden'); }

// Close modal on backdrop click
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden'); });
});

function openEditTx(tx) {
    document.getElementById('edit-tx-id').value      = tx.id;
    document.getElementById('edit-tx-amount').value  = tx.amount;
    document.getElementById('edit-tx-date').value    = tx.date;
    document.getElementById('edit-tx-desc').value    = tx.description || '';
    document.getElementById('edit-tx-category').value = tx.category_id || '';
    document.getElementById('edit-tx-type-' + tx.type).checked = true;
    openModal('tx-edit');
}

function openDeleteTx(id, name) {
    document.getElementById('delete-tx-id').value  = id;
    document.getElementById('delete-tx-name').textContent = 'This will permanently delete "' + name + '".';
    openModal('tx-delete');
}

// Client-side filtering
function filterTx() {
    const q    = document.getElementById('tx-search').value.toLowerCase();
    const type = document.getElementById('tx-type-filter').value;
    const sort = document.getElementById('tx-sort').value;
    const rows = Array.from(document.querySelectorAll('.tx-row'));

    let visible = rows.filter(r => {
        const matchQ    = !q || r.dataset.desc.includes(q) || r.dataset.cat.includes(q);
        const matchType = !type || r.dataset.type === type;
        return matchQ && matchType;
    });

    visible.sort((a, b) => {
        switch (sort) {
            case 'date-asc':    return a.dataset.date.localeCompare(b.dataset.date);
            case 'amount-desc': return parseFloat(b.dataset.amount) - parseFloat(a.dataset.amount);
            case 'amount-asc':  return parseFloat(a.dataset.amount) - parseFloat(b.dataset.amount);
            default:            return b.dataset.date.localeCompare(a.dataset.date);
        }
    });

    const list = document.getElementById('tx-list');
    rows.forEach(r => r.style.display = 'none');
    visible.forEach(r => { r.style.display = ''; list.appendChild(r); });
    document.getElementById('tx-count').textContent = visible.length + ' records';
}

document.getElementById('tx-search').addEventListener('input', filterTx);
document.getElementById('tx-type-filter').addEventListener('change', filterTx);
document.getElementById('tx-sort').addEventListener('change', filterTx);

window.addEventListener('load', () => { lucide.createIcons(); });
</script>
