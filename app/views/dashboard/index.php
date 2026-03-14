<?php
/**
 * Main Dashboard View - Adapted from React AdminDashboard.tsx
 */
?>

<div class="space-y-10 animate-fade-in">
    <!-- Welcome Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white font-outfit tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 dark:text-slate-300 font-medium mt-1">Welcome back, <?php echo $_SESSION['user_name'] ?? 'User'; ?>! Here's what's happening with your money.</p>
        </div>
        <div class="flex items-center gap-3">
            <?php 
                $text = 'Add Transaction';
                $type = 'button';
                $variant = 'primary';
                $size = 'md';
                $icon = 'plus';
                $class = 'rounded-2xl';
                include APP_PATH . '/views/includes/Button.php';
            ?>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php 
            require_once APP_PATH . '/views/includes/BudgetCard.php'; 
            
            // Total Balance Card
            $data = [
                'title' => 'Total Balance',
                'amount' => '$' . number_format($metrics['balance'], 2),
                'icon' => 'wallet',
                'variant' => 'default'
            ];
            extract($data);
            include APP_PATH . '/views/includes/BudgetCard.php';

            // Total Income Card
            $data = [
                'title' => 'Total Income',
                'amount' => '$' . number_format($metrics['income'], 2),
                'icon' => 'trending-up',
                'variant' => 'success'
            ];
            extract($data);
            include APP_PATH . '/views/includes/BudgetCard.php';

            // Total Expenses Card
            $data = [
                'title' => 'Total Expenses',
                'amount' => '$' . number_format($metrics['expense'], 2),
                'icon' => 'trending-down',
                'variant' => 'destructive',
                'percentage' => $metrics['income'] > 0 ? round(($metrics['expense'] / $metrics['income']) * 100) : 0
            ];
            extract($data);
            include APP_PATH . '/views/includes/BudgetCard.php';

            // Savings Card
            $data = [
                'title' => 'Total Savings',
                'amount' => '$' . number_format($metrics['savings'], 2),
                'icon' => 'target',
                'variant' => 'accent'
            ];
            extract($data);
            include APP_PATH . '/views/includes/BudgetCard.php';
        ?>
    </div>

    <!-- Charts & Lists Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="glass-card p-6 relative h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">Recent Transactions</h3>
                    <a href="<?php echo BASE_URL; ?>/transactions" class="text-sm font-medium text-primary dark:text-accent hover:underline">View All</a>
                </div>

                <div class="space-y-4">
                    <?php if (empty($recentTransactions)): ?>
                        <div class="text-center py-10 text-gray-500 dark:text-slate-300">
                            <p>No recent activity</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php foreach ($recentTransactions as $tx): ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-900/50 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors border border-transparent dark:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 flex items-center justify-center text-lg">
                                <?php if ($tx['category_emoji']): ?>
                                    <span><?php echo $tx['category_emoji']; ?></span>
                                <?php else: ?>
                                    <i data-lucide="<?php echo $tx['type'] === 'income' ? 'arrow-up-right' : 'arrow-down-left'; ?>" class="h-5 w-5 text-gray-500 dark:text-slate-300"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($tx['description']); ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-300 mt-1">
                                    <?php echo date('M d, Y', strtotime($tx['date'])); ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold <?php echo $tx['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white'; ?>">
                                <?php echo $tx['type'] === 'income' ? '+' : '-'; ?>$<?php echo number_format($tx['amount'], 2); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Budget Progress -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="glass-card p-6 relative h-full">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit mb-6">Budget Progress</h3>
                
                <div class="space-y-6">
                    <?php if (empty($budgetProgress)): ?>
                        <div class="text-center py-10 text-gray-500 dark:text-slate-300">
                            <p>No budgets set</p>
                            <a href="<?php echo BASE_URL; ?>/categories" class="text-sm text-primary dark:text-accent hover:underline mt-2 inline-block">Manage Categories</a>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($budgetProgress as $budget): ?>
                    <div class="space-y-2">
                        <div class="flex justify-between items-end">
                            <p class="text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo htmlspecialchars($budget['name']); ?></p>
                            <p class="text-sm text-gray-500 dark:text-slate-300">$<?php echo number_format($budget['spent'], 0); ?> / $<?php echo number_format($budget['limit'], 0); ?></p>
                        </div>
                        <div class="h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all <?php 
                                if ($budget['percentage'] >= 100) echo 'bg-red-500';
                                elseif ($budget['percentage'] >= 80) echo 'bg-orange-500';
                                else echo 'bg-primary dark:bg-accent';
                            ?>" style="width: <?php echo min($budget['percentage'], 100); ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Savings Goals -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="glass-card p-6 relative h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white font-outfit">Savings Goals</h3>
                    <a href="<?php echo BASE_URL; ?>/goals" class="text-sm font-medium text-primary dark:text-accent hover:underline">Manage</a>
                </div>
                
                <div class="space-y-6">
                    <?php if (empty($goals)): ?>
                        <div class="text-center py-10 text-gray-500 dark:text-slate-300">
                            <p>No goals set yet</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($goals as $goal): 
                        $goalPercentage = $goal['target_amount'] > 0 ? ($goal['current_amount'] / $goal['target_amount']) * 100 : 0;
                    ?>
                    <div class="space-y-2">
                        <div class="flex justify-between items-end">
                            <p class="text-sm font-medium text-gray-700 dark:text-slate-300"><?php echo htmlspecialchars($goal['name']); ?></p>
                            <p class="text-xs text-gray-500 dark:text-slate-300"><?php echo round($goalPercentage); ?>%</p>
                        </div>
                        <div class="h-2 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-accent rounded-full transition-all" style="width: <?php echo min($goalPercentage, 100); ?>%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 text-right">$<?php echo number_format($goal['current_amount'], 0); ?> / $<?php echo number_format($goal['target_amount'], 0); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
