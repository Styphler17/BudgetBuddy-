<div class="space-y-8">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">Activity Logs</h1>
            <p class="text-sm text-gray-500">View system activity and administrative actions.</p>
        </div>
    </header>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No activity logs found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($log['admin_name']); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 uppercase tracking-tighter">
                                    <?php echo str_replace('_', ' ', $log['action']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo htmlspecialchars($log['target_type']); ?> 
                                <?php if ($log['target_id']): ?>
                                    <span class="text-gray-400 font-mono">(#<?php echo $log['target_id']; ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic max-w-xs truncate">
                                <?php echo htmlspecialchars($log['details'] ?? '-'); ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500 font-mono">
                                <?php echo date('M d, H:i:s', strtotime($log['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>