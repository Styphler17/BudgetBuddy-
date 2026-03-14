<div class="p-4 sm:p-6 space-y-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white font-outfit">Settings</h1>
        <p class="text-gray-500 dark:text-slate-300 text-sm sm:text-base">Customize your SpendScribe experience</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Settings -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 relative h-full">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="h-5 w-5 text-primary dark:text-accent"></i>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Profile Settings</h2>
                    </div>
                    <?php if ($user['email_verified']): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                            Verified
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400">
                            Unverified
                        </span>
                    <?php endif; ?>
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
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary dark:bg-accent px-4 py-2 text-sm font-medium text-white dark:text-primary hover:bg-primary/90 transition-colors w-full sm:w-auto">
                            Save Changes
                        </button>
                        <?php if (!$user['email_verified']): ?>
                            <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors w-full sm:w-auto" onclick="alert('Verification link resent!')">
                                Resend Verification
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 relative h-full">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="shield-check" class="h-5 w-5 text-primary dark:text-accent"></i>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Two-Factor Authentication</h2>
                </div>
                
                <p class="text-sm text-gray-500 dark:text-slate-300">Add an extra layer of security to your account using TOTP.</p>
                
                <form action="<?php echo BASE_URL; ?>/settings" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="update_2fa">
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800/50 rounded-lg border border-gray-100 dark:border-white/5">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-check" class="h-5 w-5 text-primary dark:text-accent"></i>
                            <div class="space-y-0.5">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Enable TOTP 2FA</span>
                                <p class="text-xs text-gray-500 dark:text-slate-400">Use an app like Google Authenticator</p>
                            </div>
                        </div>
                        <label class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-all <?php echo ($user['two_factor_enabled'] ?? 0) ? 'bg-primary dark:bg-accent' : 'bg-gray-200 dark:bg-slate-700'; ?>">
                            <input type="checkbox" name="enable_2fa" class="opacity-0 absolute h-0 w-0" <?php echo ($user['two_factor_enabled'] ?? 0) ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform <?php echo ($user['two_factor_enabled'] ?? 0) ? 'translate-x-6' : 'translate-x-1'; ?>"></span>
                        </label>
                    </div>
                </form>

                <?php if ($user['two_factor_enabled'] ?? 0): ?>
                    <div class="p-4 border border-blue-100 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/10 rounded-lg">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0"></i>
                            <div class="text-xs text-blue-700 dark:text-blue-300">
                                2FA is active. Your secret key is: <code class="font-mono font-bold bg-blue-100 dark:bg-blue-900/50 px-1 rounded"><?php echo $user['two_factor_secret'] ?? 'N/A'; ?></code>. 
                                <p class="mt-1">Enter this secret into your authenticator app.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notification Preferences -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 relative h-full">
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
        </div>

        <!-- Security -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 relative h-full">
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
                            <button type="button" onclick="togglePassword('set-pass', 'set-eye-1')" class="absolute right-3 top-2.5 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-white transition-colors">
                                <i id="set-eye-1" data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="set-confirm" class="text-sm font-medium text-gray-700 dark:text-slate-300">Confirm New Password</label>
                        <div class="relative">
                            <input id="set-confirm" type="password" name="confirm_password" placeholder="••••••••" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
                            <button type="button" onclick="togglePassword('set-confirm', 'set-eye-2')" class="absolute right-3 top-2.5 text-gray-400 dark:text-slate-500 hover:text-gray-600 dark:hover:text-white transition-colors">
                                <i id="set-eye-2" data-lucide="eye" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary dark:bg-accent px-4 py-2 text-sm font-medium text-white dark:text-primary hover:bg-primary/90 transition-colors w-full sm:w-auto">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Currency Preferences -->
        <div class="glowing-wrapper">
            <div class="glowing-effect-container"></div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-4 relative h-full">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="coins" class="h-5 w-5 text-yellow-500"></i>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Currency Preferences</h2>
                </div>
                
                <p class="text-sm text-gray-500 dark:text-slate-300">Choose the currency SpendScribe should display for your budgets and reports.</p>
                
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
    </div>

    <!-- Data Management -->
    <div class="glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-6 relative">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Data Management</h2>
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Export Data</p>
                    <p class="text-sm text-gray-500 dark:text-slate-300">Download all your financial data</p>
                </div>
                <button class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Export
                </button>
            </div>

            <div class="border-t border-gray-100 dark:border-white/5 pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-red-600">Delete Account</p>
                    <p class="text-sm text-gray-500 dark:text-slate-300">Permanently delete your account and all data</p>
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

    <!-- Recent Activity -->
    <div class="glowing-wrapper">
        <div class="glowing-effect-container"></div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm space-y-6 relative">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity</h2>
                <i data-lucide="history" class="h-5 w-5 text-gray-400"></i>
            </div>
            
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <?php if (empty($activityLogs)): ?>
                        <p class="text-sm text-gray-500 dark:text-slate-400 py-4 text-center">No recent activity recorded.</p>
                    <?php else: ?>
                        <?php foreach ($activityLogs as $index => $log): ?>
                        <li>
                            <div class="relative pb-8">
                                <?php if ($index !== count($activityLogs) - 1): ?>
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-slate-800" aria-hidden="true"></span>
                                <?php endif; ?>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center ring-8 ring-white dark:ring-slate-900">
                                            <i data-lucide="<?php 
                                                echo strpos($log['action'], 'Login') !== false ? 'log-in' : 
                                                    (strpos($log['action'], 'Password') !== false ? 'key' : 'info'); 
                                            ?>" class="h-4 w-4 text-gray-500 dark:text-slate-400"></i>
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-900 dark:text-white"><?php echo htmlspecialchars($log['action']); ?> <span class="text-gray-500 dark:text-slate-400 font-normal"><?php echo htmlspecialchars($log['details']); ?></span></p>
                                            <p class="text-[10px] text-gray-400 mt-0.5"><?php echo htmlspecialchars($log['ip_address']); ?> • <?php echo htmlspecialchars(substr($log['user_agent'], 0, 50)); ?>...</p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xs text-gray-500 dark:text-slate-400">
                                            <time datetime="<?php echo $log['created_at']; ?>"><?php echo date('M d, H:i', strtotime($log['created_at'])); ?></time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
