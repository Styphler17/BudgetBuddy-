<div class="p-4 sm:p-6 space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white font-outfit">Settings</h1>
        <p class="text-gray-500 dark:text-slate-400 text-sm sm:text-base">Customize your SpendScribe experience</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Settings -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="user" class="h-5 w-5 text-primary dark:text-accent"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Profile Settings</h2>
            </div>
            
            <form action="<?php echo BASE_URL; ?>/settings" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_profile">
                <div class="space-y-2">
                    <label for="profile_name" class="text-sm font-medium text-gray-700 dark:text-slate-300">Display Name</label>
                    <input id="profile_name" type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none">
                </div>
                <div class="space-y-2">
                    <label for="profile_email" class="text-sm font-medium text-gray-700 dark:text-slate-300">Email Address</label>
                    <input id="profile_email" type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none">
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary dark:bg-accent px-4 py-2 text-sm font-medium text-white dark:text-primary hover:bg-primary/90 transition-colors w-full sm:w-auto">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Notification Preferences -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="bell" class="h-5 w-5 text-secondary dark:text-accent"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Notification Preferences</h2>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Email Notifications</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary dark:bg-accent cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Budget Alerts</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary dark:bg-accent cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300">Goal Reminders</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-slate-700 cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="shield" class="h-5 w-5 text-blue-500"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Security</h2>
            </div>
            
            <form action="<?php echo BASE_URL; ?>/settings" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_password">
                <div class="space-y-2">
                    <label for="set-pass" class="text-sm font-medium text-gray-700 dark:text-slate-300">New Password</label>
                    <div class="relative">
                        <input id="set-pass" type="password" name="password" placeholder="••••••••" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
                        <button type="button" onclick="togglePassword('set-pass', 'set-eye-1')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="set-eye-1" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="set-confirm" class="text-sm font-medium text-gray-700 dark:text-slate-300">Confirm New Password</label>
                    <div class="relative">
                        <input id="set-confirm" type="password" name="confirm_password" placeholder="••••••••" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
                        <button type="button" onclick="togglePassword('set-confirm', 'set-eye-2')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="set-eye-2" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary dark:bg-accent px-4 py-2 text-sm font-medium text-white dark:text-primary hover:bg-primary/90 transition-colors w-full sm:w-auto">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Currency Preferences -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="coins" class="h-5 w-5 text-yellow-500"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Currency Preferences</h2>
            </div>
            
            <p class="text-sm text-gray-500 dark:text-slate-400">Choose the currency SpendScribe should display for your budgets and reports.</p>
            
            <form action="<?php echo BASE_URL; ?>/settings" method="POST" class="space-y-2">
                <input type="hidden" name="action" value="update_currency">
                <label for="preferred_currency" class="text-sm font-medium text-gray-700 dark:text-slate-300">Preferred Currency</label>
                <div class="flex gap-2">
                    <select id="preferred_currency" name="currency" class="w-full h-10 border border-gray-300 dark:border-white/10 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none appearance-none bg-white dark:bg-slate-800">
                        <option value="USD">USD ($) - US Dollar</option>
                        <option value="EUR">EUR (€) - Euro</option>
                        <option value="GBP">GBP (£) - British Pound</option>
                        <option value="JPY">JPY (¥) - Japanese Yen</option>
                        <option value="CAD">CAD ($) - Canadian Dollar</option>
                        <option value="AUD">AUD ($) - Australian Dollar</option>
                        <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                        <option value="NGN">NGN (₦) - Nigerian Naira</option>
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary dark:bg-accent px-4 text-sm font-medium text-white dark:text-primary hover:bg-primary/90 transition-colors">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Management -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Data Management</h2>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Export Data</p>
                <p class="text-sm text-gray-500 dark:text-slate-400">Download all your financial data</p>
            </div>
            <button class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                Export
            </button>
        </div>

        <div class="border-t border-gray-100 dark:border-white/5 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-red-600">Delete Account</p>
                <p class="text-sm text-gray-500 dark:text-slate-400">Permanently delete your account and all data</p>
            </div>
            <form action="<?php echo BASE_URL; ?>/settings" method="POST" onsubmit="return confirm('CRITICAL: This will permanently delete your account and all transaction history. This action cannot be undone. Proceed?');">
                <input type="hidden" name="action" value="delete_account">
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors w-full sm:w-auto">
                    Delete My Account
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
