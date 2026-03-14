<?php
/**
 * Admin Sidebar Component
 */
$current_page = $_SERVER['REQUEST_URI'];
?>

<aside id="admin-sidebar" class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full transition-transform lg:translate-x-0 bg-white border-r border-gray-200 flex flex-col">
    <!-- Header -->
    <div class="p-6 flex items-center justify-center border-b border-gray-100">
        <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="SpendScribe Logo" class="h-8 w-auto object-contain">
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">System Control</p>
        
        <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'admin') !== false && strpos($current_page, 'users') === false && strpos($current_page, 'blog') === false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
            <span class="text-sm">Overview</span>
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/users" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'users') !== false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="users" class="h-4 w-4"></i>
            <span class="text-sm">User Management</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/admins" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'admins') !== false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="shield-check" class="h-4 w-4"></i>
            <span class="text-sm">Admin Management</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/blog" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'blog') !== false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="edit" class="h-4 w-4"></i>
            <span class="text-sm">Blog Manager</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'profile') !== false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="settings" class="h-4 w-4"></i>
            <span class="text-sm">Profile Settings</span>
        </a>

        <a href="<?php echo BASE_URL; ?>/admin/logs" class="flex items-center gap-3 px-3 py-2.5 rounded-md transition-colors w-full <?php echo (strpos($current_page, 'logs') !== false) ? 'bg-primary/10 text-primary font-bold' : 'text-gray-600 hover:bg-gray-100'; ?>">
            <i data-lucide="activity" class="h-4 w-4"></i>
            <span class="text-sm">Activity Logs</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200">
        <a href="<?php echo BASE_URL; ?>/" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 p-3 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-xs font-bold mb-4">
            <i data-lucide="monitor" class="h-4 w-4"></i>
            View Live Site
        </a>

        <div class="flex items-center gap-3 p-2">
            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-xs font-bold text-primary overflow-hidden">
                <?php if (isset($_SESSION['admin_profile_pic']) && $_SESSION['admin_profile_pic']): ?>
                    <img src="<?php echo BASE_URL; ?>/public/uploads/profile_pics/<?php echo $_SESSION['admin_profile_pic']; ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <?php echo isset($_SESSION['admin_name']) ? strtoupper(substr($_SESSION['admin_name'], 0, 1)) : 'A'; ?>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-gray-900 truncate"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?></p>
                <p class="text-[10px] text-gray-500 truncate"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'admin@system'); ?></p>
            </div>
            <a href="<?php echo BASE_URL; ?>/logout" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                <i data-lucide="log-out" class="h-4 w-4"></i>
            </a>
        </div>
    </div>
</aside>
