<div class="space-y-8">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">Admin Management</h1>
            <p class="text-sm text-gray-500">Manage system administrators and their roles.</p>
        </div>
        <a href="/BudgetBuddy-/admin/admins/create" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white hover:bg-primary/90 transition-colors">
            <i data-lucide="user-plus" class="mr-2 h-4 w-4"></i>
            Add Admin
        </a>
    </header>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Administrator</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($admins as $a): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                    <?php echo strtoupper(substr($a['name'], 0, 1)); ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($a['name']); ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?php echo htmlspecialchars($a['email']); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase tracking-tighter">
                                <?php echo htmlspecialchars($a['role']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?php echo $a['last_login'] ? date('M d, H:i', strtotime($a['last_login'])) : 'Never'; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/BudgetBuddy-/admin/admins/edit/<?php echo $a['id']; ?>" class="p-2 text-gray-400 hover:text-primary transition-colors" title="Edit">
                                    <i data-lucide="edit-3" class="h-4 w-4"></i>
                                </a>
                                <button class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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