<div class="space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit"><?php echo $mode === 'create' ? 'Add New Admin' : 'Edit Admin'; ?></h1>
            <p class="text-sm text-gray-500"><?php echo $mode === 'create' ? 'Provision a new administrative user.' : 'Update account details for ' . htmlspecialchars($admin['name'] ?? 'Admin') . '.'; ?></p>
        </div>
        <a href="/admin/admins" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to list
        </a>
    </header>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden max-w-2xl">
        <form id="admin-edit-form" action="" method="POST" class="p-8 space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Role</label>
                    <select name="role" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none bg-white dark:bg-slate-800">
                        <option value="admin" <?php echo ($admin['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                        <option value="super_admin" <?php echo ($admin['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                        <option value="moderator" <?php echo ($admin['role'] ?? '') === 'moderator' ? 'selected' : ''; ?>>Moderator</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 dark:text-slate-300"><?php echo $mode === 'create' ? 'Initial Password' : 'New Password (leave blank to keep)'; ?></label>
                    <div class="relative">
                        <input id="admin-edit-pass" type="password" name="password" placeholder="••••••••" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" <?php echo $mode === 'create' ? 'required' : ''; ?>>
                        <button type="button" onclick="togglePassword('admin-edit-pass', 'admin-edit-eye')" class="absolute right-3 top-2.5 text-gray-400">
                            <i id="admin-edit-eye" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 dark:border-white/5 flex justify-end gap-3">
                <a href="/admin/admins" class="px-6 py-2 border border-gray-300 dark:border-white/10 rounded-md text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary dark:bg-accent text-white dark:text-primary font-bold rounded-md hover:bg-primary/90 transition-all shadow-md active:scale-95">
                    <?php echo $mode === 'create' ? 'Create Admin' : 'Save Changes'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();

        const form = document.getElementById('admin-edit-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            ToastSave.loading();
            setTimeout(() => {
                form.submit();
            }, 800);
        });
    });
</script>
