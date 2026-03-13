<?php /** Categories View – full CRUD with budget progress */ ?>

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
            <h1 class="text-3xl font-extrabold text-gray-900 font-outfit tracking-tight">Budget Categories</h1>
            <p class="text-gray-500 font-medium mt-1">Organise your spending and set limits for different areas of life.</p>
        </div>
        <button onclick="openModal('cat-create')" class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary px-6 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            Add Category
        </button>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php if (empty($categories)): ?>
        <div class="col-span-4 text-center py-20 glass-card">
            <div class="h-20 w-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="grid" class="h-10 w-10 text-primary/30"></i>
            </div>
            <p class="text-gray-500 font-bold text-xl font-outfit">No categories yet.</p>
            <p class="text-gray-400 text-sm mt-2">Add your first category to start organising your budget!</p>
        </div>
        <?php else: ?>
        <?php foreach ($categories as $cat):
            $spent   = $cat['spent'] ?? 0;
            $budget  = $cat['budget'] > 0 ? $cat['budget'] : 1;
            $percent = min(100, max(0, ($spent / $budget) * 100));
            $progressColor = $percent >= 90 ? 'bg-rose-500' : ($percent >= 70 ? 'bg-amber-500' : 'bg-accent');
            $badgeCls = $percent >= 100 ? 'bg-rose-50 text-rose-500' : ($percent >= 80 ? 'bg-amber-50 text-amber-600' : 'bg-accent/10 text-accent');
            $badgeLabel = $percent >= 100 ? 'Over Budget' : ($percent >= 80 ? 'Near Limit' : 'On Track');
        ?>
        <div class="glass-card p-8 flex flex-col group hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 h-32 w-32 <?php echo $progressColor; ?> opacity-[0.05] rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative">
                <div class="h-14 w-14 bg-primary/5 rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 border border-primary/5">
                    <?php echo $cat['emoji'] ?? '📁'; ?>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-1 rounded-xl <?php echo $badgeCls; ?>"><?php echo $badgeLabel; ?></span>
                    <button onclick="openEditCat(<?php echo htmlspecialchars(json_encode($cat)); ?>)" class="h-9 w-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-primary hover:bg-primary/5 transition-all">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </button>
                    <button onclick="openDeleteCat(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['name'])); ?>')" class="h-9 w-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-1 mb-8 relative">
                <h3 class="text-xl font-black text-gray-900 font-outfit tracking-tight"><?php echo htmlspecialchars($cat['name']); ?></h3>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Monthly Limit</span>
                    <span class="text-xs font-bold text-primary">$<?php echo number_format($cat['budget']); ?></span>
                </div>
            </div>
            <div class="mt-auto space-y-4 relative">
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Spent</span>
                        <span class="text-lg font-black text-gray-900 font-outfit">$<?php echo number_format($spent, 2); ?></span>
                    </div>
                    <span class="text-[10px] font-black <?php echo $percent >= 90 ? 'text-rose-500' : 'text-primary'; ?> uppercase tracking-widest bg-primary/5 px-2 py-1 rounded-md"><?php echo round($percent); ?>% Used</span>
                </div>
                <div class="h-2.5 w-full bg-primary/5 rounded-full overflow-hidden border border-primary/5">
                    <div class="h-full <?php echo $progressColor; ?> rounded-full shadow-[0_0_10px_rgba(16,35,127,0.2)] transition-all duration-1000 ease-out" style="width:<?php echo $percent; ?>%"></div>
                </div>
                <p class="text-xs text-gray-400 font-bold">$<?php echo number_format(max(0, $cat['budget'] - $spent), 2); ?> remaining</p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- CREATE MODAL -->
<div id="modal-cat-create" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('cat-create')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">New Category</h2></div>
        <form method="POST" action="/SpendScribe-/categories/create" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category Name</label>
                <input type="text" name="name" required placeholder="e.g. Groceries, Transport" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Icon / Emoji</label>
                <input type="text" name="emoji" id="create-cat-emoji" placeholder="📁" maxlength="4" class="bb-input w-full text-2xl">
                <div class="flex flex-wrap gap-2 mt-3">
                    <?php foreach (['🛒','🏠','🚗','🍽️','🎬','💡','🏥','📚','🎵','✈️','🎮','💄','🏃','📱','🛍️'] as $e): ?>
                    <button type="button" onclick="document.getElementById('create-cat-emoji').value='<?php echo $e; ?>'" class="text-2xl hover:scale-110 transition-transform p-1.5 rounded-xl hover:bg-primary/5 border border-gray-100"><?php echo $e; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Monthly Budget ($)</label>
                <input type="number" name="budget" step="0.01" min="0" placeholder="0.00" class="bb-input w-full">
            </div>
            <input type="hidden" name="color" value="#3b82f6">
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('cat-create')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20">Add Category</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="modal-cat-edit" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('cat-edit')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">Edit Category</h2></div>
        <form method="POST" action="/SpendScribe-/categories/update" class="space-y-4">
            <input type="hidden" name="id" id="edit-cat-id">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category Name</label>
                <input type="text" name="name" id="edit-cat-name" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Icon / Emoji</label>
                <input type="text" name="emoji" id="edit-cat-emoji" maxlength="4" class="bb-input w-full text-2xl">
                <div class="flex flex-wrap gap-2 mt-3">
                    <?php foreach (['🛒','🏠','🚗','🍽️','🎬','💡','🏥','📚','🎵','✈️','🎮','💄','🏃','📱','🛍️'] as $e): ?>
                    <button type="button" onclick="document.getElementById('edit-cat-emoji').value='<?php echo $e; ?>'" class="text-2xl hover:scale-110 transition-transform p-1.5 rounded-xl hover:bg-primary/5 border border-gray-100"><?php echo $e; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Monthly Budget ($)</label>
                <input type="number" name="budget" id="edit-cat-budget" step="0.01" min="0" class="bb-input w-full">
            </div>
            <input type="hidden" name="color" value="#3b82f6">
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('cat-edit')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="modal-cat-delete" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 text-center">
        <div class="h-16 w-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto">
            <i data-lucide="trash-2" class="h-8 w-8 text-rose-500"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Delete Category?</h2>
            <p class="text-sm text-gray-500 mt-2" id="delete-cat-name"></p>
            <p class="text-xs text-rose-500 mt-1">Transactions in this category will be uncategorised.</p>
        </div>
        <form method="POST" action="/SpendScribe-/categories/delete" class="flex gap-3">
            <input type="hidden" name="id" id="delete-cat-id">
            <button type="button" onclick="closeModal('cat-delete')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
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

function openEditCat(cat){
    document.getElementById('edit-cat-id').value=cat.id;
    document.getElementById('edit-cat-name').value=cat.name;
    document.getElementById('edit-cat-emoji').value=cat.emoji||'📁';
    document.getElementById('edit-cat-budget').value=cat.budget||0;
    openModal('cat-edit');
}
function openDeleteCat(id,name){
    document.getElementById('delete-cat-id').value=id;
    document.getElementById('delete-cat-name').textContent='This will permanently delete "'+name+'".';
    openModal('cat-delete');
}
window.addEventListener('load',()=>lucide.createIcons());
</script>
