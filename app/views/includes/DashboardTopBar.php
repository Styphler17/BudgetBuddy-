<?php
/**
 * Dashboard TopBar Component
 */
?>

<header id="dashboard-topbar" class="sticky top-0 z-30 h-16 sm:h-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-white/10 px-4 sm:px-6 flex items-center justify-between transition-all">
    <!-- Mobile Toggle -->
    <div class="flex items-center gap-2 flex-shrink-0">
        <button id="mobile-sidebar-toggle" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-slate-300 transition-colors">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
        <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="SpendScribe" class="h-8 w-auto block lg:hidden object-contain">
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-2 sm:gap-4">
        <!-- Notifications -->
        <div class="relative">
            <a href="<?php echo BASE_URL; ?>/notifications" class="p-2.5 rounded-xl bg-gray-50 dark:bg-white/5 text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/10 transition-all block relative group">
                <i data-lucide="bell" class="h-5 w-5 group-hover:shake"></i>
                <?php if (isset($_SESSION['unread_notifications']) && $_SESSION['unread_notifications'] > 0): ?>
                    <span class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ring-2 ring-white dark:ring-slate-950 animate-bounce-slow">
                        <?php echo $_SESSION['unread_notifications']; ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>

        <!-- Theme Switcher -->
        <?php include APP_PATH . '/views/includes/ThemeSwitcher.php'; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo BASE_URL; ?>/settings" class="flex items-center gap-3 pl-2 border-l border-gray-100 dark:border-white/10 group">
                <div class="flex flex-col text-right hidden sm:flex">
                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-none mb-1 group-hover:text-primary transition-colors"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                    <span class="text-[10px] font-black text-primary dark:text-accent uppercase tracking-widest">Settings</span>
                </div>
                <div class="h-9 w-9 rounded-xl bg-primary dark:bg-accent flex items-center justify-center text-white dark:text-primary font-black text-sm shadow-md cursor-pointer transition-all hover:rotate-6 overflow-hidden">
                    <?php if (isset($_SESSION['user_profile_pic']) && $_SESSION['user_profile_pic']): ?>
                        <img src="<?php echo BASE_URL; ?>/public/uploads/profile_pics/<?php echo $_SESSION['user_profile_pic']; ?>" class="h-full w-full object-cover">
                    <?php else: ?>
                        <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                    <?php endif; ?>
                </div>
            </a>
        <?php endif; ?>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');

    mobileSidebarToggle?.addEventListener('click', () => {
        const sidebar = document.getElementById('dashboard-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    });
});
</script>

<script>
    // Simple sidebar toggle for mobile
    document.getElementById('mobile-sidebar-toggle')?.addEventListener('click', () => {
        const sidebar = document.getElementById('dashboard-sidebar');
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
