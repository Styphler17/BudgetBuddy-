<?php
/**
 * Dashboard Sidebar Component
 */
$current_page = $_SERVER['REQUEST_URI'];
$menu_items = [
    ['title' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'layout-dashboard'],
    ['title' => 'Transactions', 'url' => '/transactions', 'icon' => 'file-text'],
    ['title' => 'Recurring', 'url' => '/recurring', 'icon' => 'calendar-days'],
    ['title' => 'Analytics', 'url' => '/analytics', 'icon' => 'pie-chart'],
    ['title' => 'Budget Goals', 'url' => '/goals', 'icon' => 'trending-up'],
    ['title' => 'Accounts', 'url' => '/accounts', 'icon' => 'wallet'],
    ['title' => 'Categories', 'url' => '/categories', 'icon' => 'grid'],
    ['title' => 'Notifications', 'url' => '/notifications', 'icon' => 'bell'],
    ['title' => 'Settings', 'url' => '/settings', 'icon' => 'settings'],
];
?>

<aside id="dashboard-sidebar" class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full transition-all duration-300 lg:translate-x-0 bg-white dark:bg-slate-950 border-r border-gray-200 dark:border-white/5 flex flex-col shadow-xl lg:shadow-none">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-center">
        <img src="<?php echo BASE_URL; ?>/public/<?php echo SITE_NAME; ?>.png" alt="<?php echo SITE_NAME; ?> Logo" class="h-8 w-auto object-contain">
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto custom-scrollbar">
        <p class="px-4 mb-4 text-[10px] font-black text-gray-400 dark:text-slate-300 uppercase tracking-[0.2em]">System Menu</p>
        <?php foreach ($menu_items as $item): 
            $is_active = (strpos($current_page, $item['url']) !== false);
        ?>
            <a href="<?php echo BASE_URL . $item['url']; ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group w-full <?php echo $is_active ? 'bg-primary text-white shadow-lg shadow-primary/20 font-bold' : 'text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'; ?>">
                <i data-lucide="<?php echo $item['icon']; ?>" class="h-5 w-5 <?php echo $is_active ? 'text-white' : 'text-gray-400 dark:text-slate-500 group-hover:text-primary dark:group-hover:text-accent'; ?>"></i>
                <span class="text-sm tracking-tight"><?php echo $item['title']; ?></span>
            </a>
        <?php endforeach; ?>
        
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/5">
            <a href="<?php echo BASE_URL; ?>/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all w-full font-medium">
                <i data-lucide="globe" class="h-5 w-5"></i>
                <span class="text-sm">Back to Website</span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3 p-2">
            <div class="h-10 w-10 rounded-xl bg-primary dark:bg-accent flex items-center justify-center text-white dark:text-primary font-bold text-sm shadow-md overflow-hidden">
                <?php if (isset($_SESSION['user_profile_pic']) && $_SESSION['user_profile_pic']): ?>
                    <img src="<?php echo BASE_URL; ?>/public/uploads/profile_pics/<?php echo $_SESSION['user_profile_pic']; ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 2)) : 'U'; ?>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></p>
                <p class="text-[10px] text-gray-500 dark:text-slate-300 font-bold uppercase truncate">Authorized Node</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/logout" class="p-2 text-gray-400 hover:text-rose-500 transition-colors rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10" title="Log out">
                <i data-lucide="log-out" class="h-4 w-4"></i>
            </a>
        </div>
    </div>
</aside>
