<?php /** Goals View – full CRUD with progress */ ?>

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
            <h1 class="text-3xl font-extrabold text-gray-900 font-outfit tracking-tight">Savings Goals</h1>
            <p class="text-gray-500 font-medium mt-1">Dream big and track your progress towards financial freedom.</p>
        </div>
        <button onclick="openModal('goal-create')" class="inline-flex h-11 items-center justify-center rounded-2xl bg-primary px-6 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="target" class="mr-2 h-4 w-4"></i>
            Set New Goal
        </button>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php if (empty($goals)): ?>
        <div class="col-span-2 text-center py-20 glass-card">
            <div class="h-20 w-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="target" class="h-10 w-10 text-primary/30"></i>
            </div>
            <p class="text-gray-500 font-bold text-xl font-outfit">No savings goals set yet.</p>
            <p class="text-gray-400 text-sm mt-2">Create one to start tracking your financial dreams!</p>
        </div>
        <?php else: ?>
        <?php foreach ($goals as $goal):
            $pct = $goal['target_amount'] > 0
                ? min(100, ($goal['current_amount'] / $goal['target_amount']) * 100)
                : 0;
            $complete   = $pct >= 100;
            $colorClass = $complete ? 'bg-accent' : 'bg-primary';
            $accentCls  = $complete ? 'text-primary' : 'text-accent';
        ?>
        <div class="glass-card p-10 group relative overflow-hidden hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500">
            <div class="absolute -right-12 -top-12 h-64 w-64 <?php echo $colorClass; ?> opacity-[0.03] rounded-full blur-3xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>
            <div class="flex items-start justify-between mb-10 relative">
                <div class="h-20 w-20 <?php echo $colorClass; ?> rounded-3xl flex items-center justify-center text-white shadow-[0_20px_40px_-15px_rgba(16,35,127,0.3)] group-hover:rotate-6 transition-all duration-500">
                    <i data-lucide="<?php echo $complete ? 'check-circle' : 'target'; ?>" class="h-10 w-10"></i>
                </div>
                <div class="flex items-center gap-2">
                    <div class="text-right mr-2">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">Target Date</span>
                        <span class="text-xs font-bold text-gray-900"><?php echo $goal['deadline'] ? date('M d, Y', strtotime($goal['deadline'])) : 'Open Ended'; ?></span>
                    </div>
                    <button onclick="openEditGoal(<?php echo htmlspecialchars(json_encode($goal)); ?>)" class="h-10 w-10 flex items-center justify-center rounded-2xl text-gray-400 hover:text-primary hover:bg-primary/5 transition-all">
                        <i data-lucide="edit-3" class="h-5 w-5"></i>
                    </button>
                    <button onclick="openDeleteGoal(<?php echo $goal['id']; ?>, '<?php echo htmlspecialchars(addslashes($goal['name'])); ?>')" class="h-10 w-10 flex items-center justify-center rounded-2xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-8 relative">
                <div>
                    <h3 class="text-3xl font-black text-gray-900 font-outfit tracking-tighter"><?php echo htmlspecialchars($goal['name']); ?></h3>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-4xl font-black text-primary font-outfit tracking-tight">$<?php echo number_format($goal['current_amount']); ?></span>
                        <span class="text-sm font-bold text-gray-400">saved</span>
                        <div class="h-1 w-4 bg-gray-200 rounded-full"></div>
                        <span class="text-xl font-bold text-gray-400">$<?php echo number_format($goal['target_amount']); ?></span>
                        <span class="text-[10px] font-black uppercase text-gray-300 tracking-widest">Goal</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-[0.2em]">
                        <span class="text-gray-400">Progress</span>
                        <span class="<?php echo $accentCls; ?> bg-primary px-3 py-1 rounded-lg"><?php echo round($pct); ?>% Completed</span>
                    </div>
                    <div class="h-5 w-full bg-primary/5 rounded-2xl overflow-hidden border border-primary/5 p-1">
                        <div class="h-full <?php echo $colorClass; ?> rounded-full transition-all duration-1000 ease-out" style="width:<?php echo $pct; ?>%"></div>
                    </div>
                </div>
                <div class="pt-8 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">
                        <?php if ($complete): ?>
                            <span class="text-accent bg-primary px-2 py-0.5 rounded-md font-bold">Goal Achieved!</span>
                        <?php else: ?>
                            Needs <span class="text-primary font-bold">$<?php echo number_format($goal['target_amount'] - $goal['current_amount']); ?></span> more.
                        <?php endif; ?>
                    </p>
                    <button onclick="openProgressGoal(<?php echo $goal['id']; ?>, <?php echo $goal['current_amount']; ?>, <?php echo $goal['target_amount']; ?>)" class="h-12 px-6 rounded-2xl bg-primary/5 text-xs font-black uppercase tracking-widest text-primary hover:bg-primary hover:text-white transition-all active:scale-95">
                        Update Progress
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- CREATE MODAL -->
<div id="modal-goal-create" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('goal-create')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">New Savings Goal</h2></div>
        <form method="POST" action="/BudgetBuddy-/goals/create" class="space-y-4">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Goal Name</label>
                <input type="text" name="name" required placeholder="e.g. Emergency Fund, Vacation" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Target Amount ($)</label>
                <input type="number" name="target_amount" step="0.01" min="0.01" required placeholder="0.00" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Current Saved ($)</label>
                <input type="number" name="current_amount" step="0.01" min="0" value="0" placeholder="0.00" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Target Date</label>
                <input type="date" name="deadline" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category (optional)</label>
                <select name="category_id" class="bb-input w-full">
                    <option value="">No category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji'] ?? ''; ?> <?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('goal-create')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20">Create Goal</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="modal-goal-edit" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 relative">
        <button onclick="closeModal('goal-edit')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">✕</button>
        <div><h2 class="text-2xl font-black text-gray-900 font-outfit">Edit Goal</h2></div>
        <form method="POST" action="/BudgetBuddy-/goals/update" class="space-y-4">
            <input type="hidden" name="id" id="edit-goal-id">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Goal Name</label>
                <input type="text" name="name" id="edit-goal-name" required class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Target Amount ($)</label>
                <input type="number" name="target_amount" id="edit-goal-target" step="0.01" min="0.01" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Current Saved ($)</label>
                <input type="number" name="current_amount" id="edit-goal-current" step="0.01" min="0" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Target Date</label>
                <input type="date" name="deadline" id="edit-goal-deadline" class="bb-input w-full">
            </div>
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Category (optional)</label>
                <select name="category_id" id="edit-goal-category" class="bb-input w-full">
                    <option value="">No category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['emoji'] ?? ''; ?> <?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('goal-edit')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- PROGRESS MODAL -->
<div id="modal-goal-progress" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 relative">
        <button onclick="closeModal('goal-progress')" class="absolute top-5 right-5 h-8 w-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">✕</button>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Update Progress</h2>
            <p class="text-sm text-gray-500 mt-1" id="progress-goal-info"></p>
        </div>
        <form method="POST" action="/BudgetBuddy-/goals/update" class="space-y-4">
            <input type="hidden" name="id" id="progress-goal-id">
            <input type="hidden" name="name" id="progress-goal-name-hidden">
            <input type="hidden" name="target_amount" id="progress-goal-target-hidden">
            <input type="hidden" name="deadline" id="progress-goal-deadline-hidden">
            <input type="hidden" name="category_id" id="progress-goal-cat-hidden">
            <div>
                <label class="block text-xs font-black text-primary uppercase tracking-widest mb-2">Amount Currently Saved ($)</label>
                <input type="number" name="current_amount" id="progress-goal-current" step="0.01" min="0" class="bb-input w-full">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('goal-progress')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
                <button type="submit" class="flex-1 h-11 rounded-2xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE MODAL -->
<div id="modal-goal-delete" class="modal-overlay hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 space-y-6 text-center">
        <div class="h-16 w-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto">
            <i data-lucide="trash-2" class="h-8 w-8 text-rose-500"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 font-outfit">Delete Goal?</h2>
            <p class="text-sm text-gray-500 mt-2" id="delete-goal-name"></p>
        </div>
        <form method="POST" action="/BudgetBuddy-/goals/delete" class="flex gap-3">
            <input type="hidden" name="id" id="delete-goal-id">
            <button type="button" onclick="closeModal('goal-delete')" class="flex-1 h-11 rounded-2xl border border-gray-200 text-sm font-bold text-gray-600">Cancel</button>
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

function openEditGoal(g){
    document.getElementById('edit-goal-id').value=g.id;
    document.getElementById('edit-goal-name').value=g.name;
    document.getElementById('edit-goal-target').value=g.target_amount;
    document.getElementById('edit-goal-current').value=g.current_amount;
    document.getElementById('edit-goal-deadline').value=g.deadline||'';
    document.getElementById('edit-goal-category').value=g.category_id||'';
    openModal('goal-edit');
}
function openProgressGoal(id, current, target){
    document.getElementById('progress-goal-id').value=id;
    document.getElementById('progress-goal-current').value=current;
    document.getElementById('progress-goal-info').textContent='Target: $'+parseFloat(target).toLocaleString();
    // We also need to carry over non-editable fields; find them from the page data
    const allGoals = <?php echo json_encode($goals); ?>;
    const g = allGoals.find(x=>x.id==id);
    if(g){
        document.getElementById('progress-goal-name-hidden').value=g.name;
        document.getElementById('progress-goal-target-hidden').value=g.target_amount;
        document.getElementById('progress-goal-deadline-hidden').value=g.deadline||'';
        document.getElementById('progress-goal-cat-hidden').value=g.category_id||'';
    }
    openModal('goal-progress');
}
function openDeleteGoal(id,name){
    document.getElementById('delete-goal-id').value=id;
    document.getElementById('delete-goal-name').textContent='This will permanently delete "'+name+'".';
    openModal('goal-delete');
}
window.addEventListener('load',()=>lucide.createIcons());
</script>
