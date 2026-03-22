<?php
/**
 * Admin User Management View
 */
?>

<div class="space-y-8">
    <!-- Header -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">User Management</h1>
            <p class="text-sm text-gray-500">Manage registered users and their account status.</p>
        </div>
        <div class="flex items-center gap-3">
             <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                <input type="text" placeholder="Search by name or email..." class="bg-white border border-gray-300 rounded-md py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 outline-none transition-all w-64 md:w-80">
            </div>
        </div>
    </header>

    <!-- User Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Currency</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No users found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm shrink-0 overflow-hidden">
                                        <?php echo strtoupper(substr($u['name'] ?? 'U', 0, 1)); ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($u['name']); ?></p>
                                        <p class="text-xs text-gray-500 truncate"><?php echo htmlspecialchars($u['email']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded"><?php echo $u['currency'] ?? 'USD'; ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo BASE_URL; ?>/admin/users/edit/<?php echo $u['id']; ?>" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                                    </a>
                                    <button class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                    </button>
                                </div>
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
