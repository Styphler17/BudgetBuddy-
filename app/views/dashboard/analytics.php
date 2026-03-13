<div class="p-4 sm:p-6 space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-outfit">Analytics</h1>
        <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Financial insights and trends for your account</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Spending by Category -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm lg:col-span-1">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 font-outfit">Spending by Category</h3>
            <div class="h-[300px] w-full flex items-center justify-center relative">
                <canvas id="spendingPieChart"></canvas>
            </div>
        </div>

        <!-- Trends -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm md:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 font-outfit">Income vs Expenses Trends</h3>
            <div class="h-[300px] w-full">
                <canvas id="trendsBarChart"></canvas>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 font-outfit">Budget Overview</h3>
            <?php 
                $net = $income - $expense; 
                $budgetTotal = array_reduce($categories, function($c, $item) { return $c + $item['budget']; }, 0);
                $budgetUsed = $budgetTotal > 0 ? ($expense / $budgetTotal) * 100 : 0;
            ?>
            <div class="space-y-6">
                <div>
                    <span class="text-sm text-gray-500 dark:text-slate-400 font-medium">Net Balance</span>
                    <div class="text-3xl font-bold <?php echo $net >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'; ?>">
                        $<?php echo number_format($net, 2); ?>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Total Income</span>
                        <span class="font-bold text-green-600 dark:text-green-400">$<?php echo number_format($income, 2); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Total Expenses</span>
                        <span class="font-bold text-red-600 dark:text-red-400">$<?php echo number_format($expense, 2); ?></span>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100 dark:border-white/5 space-y-2">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-slate-400">Budget Used</span>
                        <span class="font-bold text-gray-900 dark:text-white"><?php echo round($budgetUsed); ?>%</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary dark:bg-accent" style="width: <?php echo min(100, $budgetUsed); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cumulative Trend -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm md:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 font-outfit">Expense Trend</h3>
            <div class="h-[300px] w-full">
                <canvas id="expenseLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.addEventListener('load', () => {
        lucide.createIcons();

        const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1'];

        // Process Category Data
        <?php
        $catLabels = [];
        $catSpent = [];
        foreach ($categoryData as $cd) {
            if ($cd['spent'] > 0) {
                $catLabels[] = $cd['name'];
                $catSpent[] = $cd['spent'];
            }
        }
        // Fallback if empty
        if (empty($catLabels)) {
            $catLabels = ['No Data'];
            $catSpent = [1];
        }
        ?>

        new Chart(document.getElementById('spendingPieChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($catLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($catSpent); ?>,
                    backgroundColor: colors
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // Trends Data (Last 30 days)
        <?php
        $trendLabels = [];
        $incomeData = [];
        $expenseData = [];
        foreach ($dailyStats as $stat) {
            $trendLabels[] = date('M d', strtotime($stat['date']));
            $incomeData[] = (float)$stat['income'];
            $expenseData[] = (float)$stat['expense'];
        }
        ?>

        new Chart(document.getElementById('trendsBarChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($trendLabels); ?>,
                datasets: [
                    { label: 'Income', data: <?php echo json_encode($incomeData); ?>, backgroundColor: '#10b981' },
                    { label: 'Expenses', data: <?php echo json_encode($expenseData); ?>, backgroundColor: '#ef4444' }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        new Chart(document.getElementById('expenseLineChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($trendLabels); ?>,
                datasets: [{
                    label: 'Expense Trend',
                    data: <?php echo json_encode($expenseData); ?>,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
