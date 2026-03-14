<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit tracking-tight">Budget Goals</h1>
            <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Track your financial goals and your progress</p>
        </div>
        <button onclick="openModal('add-goal-modal')" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-6 text-sm font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 w-full sm:w-auto">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
            New Goal
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($goals)): ?>
            <div class="col-span-full glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-16 rounded-[2rem] border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-slate-300 relative">
                    <div class="h-20 w-20 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="target" class="h-10 w-10 opacity-20"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Goals Set</h3>
                    <p class="max-w-xs mx-auto">Set your first savings goal to start tracking your progress towards your dreams!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($goals as $goal): 
                $percent = ($goal['target_amount'] > 0) ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                $percent = min(100, round($percent));
                $isCompleted = $percent >= 100;
            ?>
            <div class="glowing-wrapper">
                <div class="glowing-effect-container"></div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border <?php echo $isCompleted ? 'border-green-200 dark:border-green-900/50 bg-green-50/30 dark:bg-green-900/10' : 'border-gray-200 dark:border-white/10'; ?> shadow-sm space-y-4 group relative h-full transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl bg-gray-50 dark:bg-slate-800 flex items-center justify-center border border-gray-100 dark:border-white/5">
                                <i data-lucide="target" class="h-6 w-6 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white truncate max-w-[120px]"><?php echo htmlspecialchars($goal['name']); ?></h3>
                                <?php if ($isCompleted): ?>
                                    <span class="text-[10px] text-green-600 dark:text-green-400 uppercase tracking-widest font-black flex items-center gap-1"><i data-lucide="check-circle" class="h-3 w-3"></i> Achieved</span>
                                <?php else: ?>
                                    <p class="text-[10px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">In Progress</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button onclick='openEditGoalModal(<?php echo json_encode($goal); ?>)' class="p-2 text-gray-400 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                            <button onclick="confirmDeleteGoal(<?php echo $goal['id']; ?>, '<?php echo htmlspecialchars($goal['name']); ?>')" class="p-2 text-gray-400 dark:text-slate-500 hover:text-red-500 transition-colors bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-white/5"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-gray-700 dark:text-slate-300">$<?php echo number_format($goal['current_amount'], 0); ?> <span class="text-xs font-normal text-gray-400">/ $<?php echo number_format($goal['target_amount'], 0); ?></span></span>
                            <span class="<?php echo $isCompleted ? 'text-green-600 dark:text-green-400' : 'text-primary dark:text-accent'; ?> font-black"><?php echo $percent; ?>%</span>
                        </div>
                        <div class="h-3 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden border border-gray-200/50 dark:border-white/5">
                            <div class="h-full <?php echo $isCompleted ? 'bg-green-500' : 'bg-primary dark:bg-accent'; ?> transition-all duration-700 relative" style="width: <?php echo $percent; ?>%">
                                <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] uppercase tracking-widest font-black text-gray-400 dark:text-slate-500 mt-2">
                            <span>Target Date</span>
                            <span><?php echo $goal['deadline'] ? date('M d, Y', strtotime($goal['deadline'])) : 'No deadline'; ?></span>
                        </div>
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
<!-- Add Goal Modal -->
<div id="add-goal-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('add-goal-modal')"></div>
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">New Goal</h3>
                    <button onclick="closeModal('add-goal-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/goals/create" method="POST" class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Goal Name</label>
                        <input type="text" name="name" placeholder="e.g. Dream Vacation" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Target Amount</label>
                        <input type="number" name="target_amount" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Target Date</label>
                        <input type="date" name="deadline" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <button type="submit" class="w-full h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Set Goal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Goal Modal -->
<div id="edit-goal-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit-goal-modal')"></div>
        <div class="relative w-full max-w-md glowing-wrapper animate-in fade-in zoom-in duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit">Edit Goal</h3>
                    <button onclick="closeModal('edit-goal-modal')" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form action="<?php echo BASE_URL; ?>/goals/update" method="POST" class="space-y-5">
                    <input type="hidden" name="id" id="edit_goal_id">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Goal Name</label>
                        <input type="text" name="name" id="edit_goal_name" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Target Amount</label>
                        <input type="number" name="target_amount" id="edit_goal_target" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Current Saved</label>
                        <input type="number" name="current_amount" id="edit_goal_current" step="0.01" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Target Date</label>
                        <input type="date" name="deadline" id="edit_goal_deadline" class="w-full h-12 border border-gray-300 dark:border-white/10 rounded-xl px-4 text-sm bg-white dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <button type="submit" class="w-full h-14 bg-primary text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Goal Delete Modal -->
<div id="delete-goal-modal" class="fixed inset-0 modal-container hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('delete-goal-modal')"></div>
        <div class="relative w-full max-w-sm glowing-wrapper animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-200 dark:border-white/10 shadow-2xl relative text-center">
                <div class="h-20 w-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400">
                    <i data-lucide="alert-triangle" class="h-10 w-10"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white font-outfit mb-2 leading-tight">Remove Goal?</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Are you sure you want to delete <span id="delete_goal_name" class="font-bold text-gray-900 dark:text-white"></span>? This progress will be lost.
                </p>
                <div class="flex flex-col gap-3">
                    <a id="confirm_delete_goal_btn" href="#" class="w-full h-14 bg-red-600 text-white font-black uppercase tracking-widest text-sm rounded-2xl hover:bg-red-700 transition-all flex items-center justify-center shadow-lg shadow-red-600/20">
                        Delete Permanently
                    </a>
                    <button onclick="closeModal('delete-goal-modal')" class="w-full h-14 bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-slate-700 transition-all">
                        Keep Goal
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
    function openEditGoalModal(goal) {
        document.getElementById('edit_goal_id').value = goal.id;
        document.getElementById('edit_goal_name').value = goal.name;
        document.getElementById('edit_goal_target').value = goal.target_amount;
        document.getElementById('edit_goal_current').value = goal.current_amount;
        document.getElementById('edit_goal_deadline').value = goal.deadline;
        openModal('edit-goal-modal');
    }

    function confirmDeleteGoal(id, name) {
        document.getElementById('delete_goal_name').textContent = name;
        document.getElementById('confirm_delete_goal_btn').href = '<?php echo BASE_URL; ?>/goals/delete/' + id;
        openModal('delete-goal-modal');
    }

    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
