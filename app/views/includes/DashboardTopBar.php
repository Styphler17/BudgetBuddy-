<?php
/**
 * Dashboard TopBar Component
 */
?>

<header id="dashboard-topbar" class="sticky top-0 z-30 h-16 sm:h-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-white/10 px-4 sm:px-6 flex items-center justify-between transition-all">
    <!-- Mobile Toggle -->
    <div class="flex items-center gap-2 flex-shrink-0">
        <button id="mobile-sidebar-toggle" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-slate-400 transition-colors">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
        <img src="/BudgetBuddy-/public/BudgetBuddy.png" alt="BudgetBuddy" class="h-7 w-auto block lg:hidden">
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-2 sm:gap-4">
        <!-- Theme Toggle -->
        <button id="dash-theme-toggle" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all border border-transparent dark:border-white/5" title="Toggle Theme">
            <i data-lucide="sun" id="dash-theme-light-icon" class="hidden w-5 h-5"></i>
            <i data-lucide="moon" id="dash-theme-dark-icon" class="hidden w-5 h-5"></i>
        </button>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="flex items-center gap-3 pl-2 border-l border-gray-100 dark:border-white/10">
                <div class="flex flex-col text-right hidden sm:flex">
                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-none mb-1"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                    <span class="text-[10px] font-black text-primary dark:text-accent uppercase tracking-widest">Active</span>
                </div>
                <div class="h-9 w-9 rounded-xl bg-primary dark:bg-accent flex items-center justify-center text-white dark:text-primary font-black text-sm shadow-md cursor-pointer transition-transform hover:rotate-6">
                    <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
    const dashThemeToggle = document.getElementById('dash-theme-toggle');
    const lightIcon = document.getElementById('dash-theme-light-icon');
    const darkIcon = document.getElementById('dash-theme-dark-icon');

    const updateDashThemeIcons = () => {
        if (document.body.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
        } else {
            lightIcon.classList.add('hidden');
            darkIcon.classList.remove('hidden');
        }
    };

    updateDashThemeIcons();

    dashThemeToggle?.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        updateDashThemeIcons();
    });

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
