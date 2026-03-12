<div class="space-y-8">
    <header>
        <h1 class="text-2xl font-bold text-gray-900 font-outfit">Profile Settings</h1>
        <p class="text-sm text-gray-500">Manage your admin account and security settings.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Profile Info -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Admin Details</h2>
            <form action="" method="POST" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Display Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="w-full h-10 border border-gray-300 rounded-md px-3 text-sm focus:ring-2 focus:ring-primary/20 outline-none" required>
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 bg-primary text-white font-bold rounded-md hover:bg-primary/90 transition-all shadow-md active:scale-95">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Security & Password</h2>
            <form action="" method="POST" class="space-y-4">
                <!-- We'll use the same form but the controller handles password if not empty -->
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">
                
                <p class="text-sm text-gray-500 mb-4">Leave password blank if you don't want to change it.</p>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">New Password</label>
                    <div class="relative">
                        <input id="admin-prof-pass" type="password" name="password" placeholder="••••••••" class="w-full h-10 border border-gray-300 rounded-md pl-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 outline-none">
                        <button type="button" onclick="togglePassword('admin-prof-pass', 'admin-prof-eye')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i id="admin-prof-eye" data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 border border-primary text-primary font-bold rounded-md hover:bg-primary hover:text-white transition-all shadow-sm active:scale-95">
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