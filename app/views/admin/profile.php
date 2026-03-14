<div class="space-y-8">
    <header>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-outfit">Profile Settings</h1>
        <p class="text-sm text-gray-500 dark:text-slate-400">Manage your admin account and security settings.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Profile Info -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Admin Details</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                
                <!-- Profile Picture Upload -->
                <div class="flex items-center gap-6 mb-6">
                    <div class="relative group">
                        <div class="h-20 w-20 rounded-2xl overflow-hidden border-2 border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-slate-800">
                            <?php if ($admin['profile_pic']): ?>
                                <img src="<?php echo BASE_URL; ?>/public/uploads/profile_pics/<?php echo $admin['profile_pic']; ?>" class="h-full w-full object-cover">
                            <?php else: ?>
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <i data-lucide="user" class="h-10 w-10"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <label for="admin_profile_pic_input" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-2xl">
                            <i data-lucide="camera" class="h-6 w-6 text-white"></i>
                        </label>
                        <input type="file" id="admin_profile_pic_input" name="profile_pic" class="hidden" accept="image/*" onchange="this.form.submit()">
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Admin Avatar</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Click to change profile picture</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="admin_display_name" class="text-sm font-medium text-gray-700 dark:text-slate-300">Display Name</label>
                    <input id="admin_display_name" type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
                </div>
                <div class="space-y-2">
                    <label for="admin_email_address" class="text-sm font-medium text-gray-700 dark:text-slate-300">Email Address</label>
                    <input id="admin_email_address" type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none" required>
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 bg-primary dark:bg-accent text-white dark:text-primary font-bold rounded-md hover:bg-primary/90 transition-all shadow-md active:scale-95">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Security & Password</h2>
            <form action="" method="POST" class="space-y-4">
                <!-- We'll use the same form but the controller handles password if not empty -->
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">
                
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">Leave password blank if you don't want to change it.</p>
                
                <div class="space-y-2">
                    <label for="admin-prof-pass" class="text-sm font-medium text-gray-700 dark:text-slate-300">New Password</label>
                    <div class="relative">
                        <input id="admin-prof-pass" type="password" name="password" placeholder="••••••••" class="w-full h-10 border border-gray-300 dark:border-white/10 bg-white dark:bg-slate-800 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 dark:focus:ring-accent/20 dark:text-white outline-none">
                        <button type="button" onclick="togglePassword('admin-prof-pass', 'admin-prof-eye')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="admin-prof-eye" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 border border-primary dark:border-accent text-primary dark:text-accent font-bold rounded-md hover:bg-primary dark:hover:bg-accent hover:text-white dark:hover:text-primary transition-all shadow-sm active:scale-95">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        lucide.createIcons();
    });
</script>
