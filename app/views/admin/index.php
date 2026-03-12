<?php
/**
 * Admin Overview View
 */
?>

<div class="space-y-8">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">System Overview</h1>
            <p class="text-sm text-gray-500">Monitor system performance and user activity.</p>
        </div>
        <div class="flex items-center gap-3">
            <?php 
                $text = 'Export Stats';
                $type = 'button';
                $variant = 'outline';
                $size = 'sm';
                $icon = 'download';
                include APP_PATH . '/views/includes/Button.php';
            ?>
            <?php 
                $text = 'Refresh Data';
                $type = 'button';
                $variant = 'primary';
                $size = 'sm';
                $icon = 'refresh-cw';
                include APP_PATH . '/views/includes/Button.php';
            ?>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                <i data-lucide="users" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['totalUsers'] ?? 0); ?></div>
            <p class="text-xs text-gray-500 mt-1">Registered accounts</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Total Admins</h3>
                <i data-lucide="shield" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['totalAdmins'] ?? 0); ?></div>
            <p class="text-xs text-gray-500 mt-1">System administrators</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Total Transactions</h3>
                <i data-lucide="activity" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-2xl font-bold text-gray-900"><?php echo number_format($stats['totalTransactions'] ?? 0); ?></div>
            <p class="text-xs text-gray-500 mt-1">Recorded activities</p>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Categories</h3>
                <i data-lucide="grid" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-xl font-bold text-gray-900"><?php echo $stats['totalCategories'] ?? 0; ?></div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Goals</h3>
                <i data-lucide="target" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-xl font-bold text-gray-900"><?php echo $stats['totalGoals'] ?? 0; ?></div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Accounts</h3>
                <i data-lucide="wallet" class="h-4 w-4 text-gray-400"></i>
            </div>
            <div class="text-xl font-bold text-gray-900"><?php echo $stats['totalAccounts'] ?? 0; ?></div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 font-outfit italic">Recent Activity</h3>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">System Logs</span>
        </div>
        <div class="divide-y divide-gray-100">
            <?php foreach ($logs as $log): ?>
            <div class="p-4 hover:bg-gray-50 transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                        <i data-lucide="activity" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">
                            <?php echo htmlspecialchars($log['admin_name']); ?> 
                            <span class="font-medium text-gray-500"><?php echo str_replace('_', ' ', $log['action']); ?></span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo date('M d, H:i', strtotime($log['created_at'])); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 rounded bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        <?php echo $log['target_type']; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
            <a href="/BudgetBuddy-/admin/logs" class="text-xs font-bold text-primary hover:underline uppercase tracking-widest">View all logs</a>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
