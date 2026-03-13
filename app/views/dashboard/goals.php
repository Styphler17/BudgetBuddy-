<div class="p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-outfit">Budget Goals</h1>
            <p class="text-gray-500 text-sm sm:text-base">Track your financial goals and your progress</p>
        </div>
        <button onclick="document.getElementById('add-goal-form').classList.toggle('hidden')" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
            <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
            New Goal
        </button>
    </div>

    <!-- Add Goal Form (Hidden by default) -->
    <div id="add-goal-form" class="hidden bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4 font-outfit">New Goal</h3>
        <form action="/goals/create" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Goal Name</label>
                <input type="text" name="name" placeholder="e.g. New Car" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Target Amount</label>
                <input type="number" name="target_amount" step="0.01" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Target Date</label>
                <input type="date" name="deadline" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="px-6 h-10 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-colors">Create Goal</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($goals)): ?>
            <div class="col-span-full bg-white p-12 rounded-xl border border-gray-200 text-center text-gray-500">
                <i data-lucide="target" class="h-12 w-12 mx-auto mb-4 opacity-20"></i>
                <p>No savings goals set yet. Create one to start tracking!</p>
            </div>
        <?php else: ?>
            <?php foreach ($goals as $goal): 
                $percent = ($goal['target_amount'] > 0) ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                $percent = min(100, round($percent));
                $isCompleted = $percent >= 100;
            ?>
            <div class="bg-white p-6 rounded-xl border <?php echo $isCompleted ? 'border-green-200 bg-green-50/30' : 'border-gray-200'; ?> shadow-sm space-y-4 group relative">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="target" class="h-5 w-5 text-primary"></i>
                        <h3 class="font-bold text-gray-900 truncate"><?php echo htmlspecialchars($goal['name']); ?></h3>
                        <?php if ($isCompleted): ?>
                            <i data-lucide="check-circle" class="h-4 w-4 text-green-600"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                        <button class="p-1 text-gray-400 hover:text-gray-900 transition-colors"><i data-lucide="edit-3" class="h-4 w-4"></i></button>
                        <a href="/goals/delete/<?php echo $goal['id']; ?>" class="p-1 text-gray-400 hover:text-red-500 transition-colors" onclick="return confirm('Delete this goal?')"><i data-lucide="trash-2" class="h-4 w-4"></i></a>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium text-gray-700">$<?php echo number_format($goal['current_amount'], 0); ?> / $<?php echo number_format($goal['target_amount'], 0); ?></span>
                        <span class="<?php echo $isCompleted ? 'text-green-600' : 'text-primary'; ?> font-bold"><?php echo $percent; ?>%</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full <?php echo $isCompleted ? 'bg-green-500' : 'bg-primary'; ?> transition-all duration-500" style="width: <?php echo $percent; ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Target: <?php echo $goal['deadline'] ? date('M d, Y', strtotime($goal['deadline'])) : 'No deadline'; ?></p>
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
