<div class="space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-outfit">Edit User</h1>
            <p class="text-sm text-gray-500">Update account details for <?php echo htmlspecialchars($user['name']); ?>.</p>
        </div>
        <a href="/BudgetBuddy-/admin/users" class="text-sm font-medium text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Back to list
        </a>
    </header>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden max-w-2xl">
        <form action="" method="POST" class="p-8 space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Preferred Currency</label>
                    <select name="currency" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                        <option value="USD" <?php echo ($user['currency'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD ($) - US Dollar</option>
                        <option value="EUR" <?php echo ($user['currency'] ?? '') === 'EUR' ? 'selected' : ''; ?>>EUR (€) - Euro</option>
                        <option value="GBP" <?php echo ($user['currency'] ?? '') === 'GBP' ? 'selected' : ''; ?>>GBP (£) - British Pound</option>
                        <option value="JPY" <?php echo ($user['currency'] ?? '') === 'JPY' ? 'selected' : ''; ?>>JPY (¥) - Japanese Yen</option>
                        <option value="CAD" <?php echo ($user['currency'] ?? '') === 'CAD' ? 'selected' : ''; ?>>CAD ($) - Canadian Dollar</option>
                        <option value="AUD" <?php echo ($user['currency'] ?? '') === 'AUD' ? 'selected' : ''; ?>>AUD ($) - Australian Dollar</option>
                        <option value="GHS" <?php echo ($user['currency'] ?? '') === 'GHS' ? 'selected' : ''; ?>>GHS (₵) - Ghanaian Cedi</option>
                        <option value="NGN" <?php echo ($user['currency'] ?? '') === 'NGN' ? 'selected' : ''; ?>>NGN (₦) - Nigerian Naira</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2 pt-8">
                    <input type="checkbox" id="is_active" name="is_active" <?php echo ($user['is_active'] ?? 1) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Account Active</label>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="/BudgetBuddy-/admin/users" class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-all shadow-md active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>