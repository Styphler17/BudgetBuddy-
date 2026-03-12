<div class="p-4 sm:p-6 space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-outfit">Settings</h1>
        <p class="text-gray-500 text-sm sm:text-base">Customize your BudgetBuddy experience</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Settings -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="user" class="h-5 w-5 text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900">Profile Settings</h2>
            </div>
            
            <form action="/BudgetBuddy-/settings" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_profile">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Display Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none">
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Notification Preferences -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="bell" class="h-5 w-5 text-secondary"></i>
                <h2 class="text-lg font-bold text-gray-900">Notification Preferences</h2>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Budget Alerts</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Goal Reminders</span>
                    <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 cursor-pointer transition-all">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-1"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="shield" class="h-5 w-5 text-accent-dark text-blue-500"></i>
                <h2 class="text-lg font-bold text-gray-900">Security</h2>
            </div>
            
            <form action="/BudgetBuddy-/settings" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update_password">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">New Password</label>
                    <div class="relative">
                        <input id="set-pass" type="password" name="password" placeholder="••••••••" class="w-full h-10 border border-gray-300 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
                        <button type="button" onclick="togglePassword('set-pass', 'set-eye-1')" class="absolute right-3 top-2.5 text-gray-400">
                            <i id="set-eye-1" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Confirm New Password</label>
                    <div class="relative">
                        <input id="set-confirm" type="password" name="confirm_password" placeholder="••••••••" class="w-full h-10 border border-gray-300 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
                        <button type="button" onclick="togglePassword('set-confirm', 'set-eye-2')" class="absolute right-3 top-2.5 text-gray-400">
                            <i id="set-eye-2" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors w-full sm:w-auto">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Currency Preferences -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="coins" class="h-5 w-5 text-yellow-500"></i>
                <h2 class="text-lg font-bold text-gray-900">Currency Preferences</h2>
            </div>
            
            <p class="text-sm text-gray-500">Choose the currency BudgetBuddy should display for your budgets and reports.</p>
            
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">Preferred Currency</label>
                <select class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none appearance-none bg-white">
                    <option value="USD">USD ($) - US Dollar</option>
                    <option value="EUR">EUR (€) - Euro</option>
                    <option value="GBP">GBP (£) - British Pound</option>
                    <option value="JPY">JPY (¥) - Japanese Yen</option>
                    <option value="CAD">CAD ($) - Canadian Dollar</option>
                    <option value="AUD">AUD ($) - Australian Dollar</option>
                    <option value="GHS">GHS (₵) - Ghanaian Cedi</option>
                    <option value="NGN">NGN (₦) - Nigerian Naira</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Management -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-6">
        <h2 class="text-lg font-bold text-gray-900">Data Management</h2>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-gray-900">Export Data</p>
                <p class="text-sm text-gray-500">Download all your financial data</p>
            </div>
            <button class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Export
            </button>
        </div>

        <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-red-600">Delete Account</p>
                <p class="text-sm text-gray-500">Permanently delete your account and all data</p>
            </div>
            <button class="inline-flex items-center justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>