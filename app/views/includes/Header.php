<?php
/**
 * Header Component - Adapted from React Header.tsx
 */
$current_uri = $_SERVER['REQUEST_URI'];
$base_url = '/BudgetBuddy-';

// Helper to check if a link is active
$is_active = function($path) use ($current_uri, $base_url) {
    $full_path = ($path === '/') ? $base_url . '/' : $base_url . $path;
    $normalized_uri = rtrim($current_uri, '/');
    $normalized_path = rtrim($full_path, '/');
    
    if ($path === '/' && ($normalized_uri === $base_url || $normalized_uri === '')) {
        return 'text-primary dark:text-accent font-bold';
    }
    
    return (strpos($normalized_uri, $normalized_path) === 0 && $path !== '/') ? 'text-primary dark:text-accent font-bold' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white';
};
?>
<header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-gray-200/50 dark:border-white/10 transition-all">
    <div class="container mx-auto px-4 py-2">
        <nav class="flex items-center justify-between">
            <!-- Logo Section -->
            <a href="/BudgetBuddy-/" class="flex items-center space-x-3 group">
                <img src="/BudgetBuddy-/public/BudgetBuddy.png" alt="BudgetBuddy Logo" class="h-14 w-14 rounded-2xl object-cover shadow-sm group-hover:shadow-md transition-all duration-300">
                <span class="text-xl font-bold text-gray-900 dark:text-white font-outfit">BudgetBuddy</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/BudgetBuddy-/" class="transition-colors <?php echo $is_active('/'); ?>">Home</a>
                <a href="/BudgetBuddy-/blog" class="transition-colors <?php echo $is_active('/blog'); ?>">Blog</a>
                <a href="/BudgetBuddy-/contact" class="transition-colors <?php echo $is_active('/contact'); ?>">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/BudgetBuddy-/transactions" class="transition-colors <?php echo $is_active('/transactions'); ?>">Transactions</a>
                    <a href="/BudgetBuddy-/analytics" class="transition-colors <?php echo $is_active('/analytics'); ?>">Analytics</a>
                <?php endif; ?>
            </div>

            <!-- Auth Buttons & Theme Toggle -->
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all border border-transparent dark:border-white/5" title="Toggle Theme">
                    <i data-lucide="sun" id="theme-toggle-light-icon" class="hidden w-5 h-5"></i>
                    <i data-lucide="moon" id="theme-toggle-dark-icon" class="hidden w-5 h-5"></i>
                </button>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <?php 
                            $text = 'Go to Dashboard';
                            $type = 'a';
                            $href = '/BudgetBuddy-/dashboard';
                            $variant = 'primary';
                            $size = 'sm';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Sign Out';
                            $type = 'a';
                            $href = '/BudgetBuddy-/logout';
                            $variant = 'outline';
                            $size = 'sm';
                            $class = 'dark:border-white/10 dark:bg-transparent dark:text-white dark:hover:bg-white/5';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                <?php else: ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <?php 
                            $text = 'Sign In';
                            $type = 'a';
                            $href = '/BudgetBuddy-/login';
                            $variant = 'outline';
                            $size = 'sm';
                            $class = 'dark:border-white/10 dark:bg-transparent dark:text-white dark:hover:bg-white/5';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Get Started';
                            $type = 'a';
                            $href = '/BudgetBuddy-/register';
                            $variant = 'primary';
                            $size = 'sm';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>
    </div>

    <!-- Mobile Navigation Menu (Overlay) -->
    <div id="mobile-menu" class="fixed inset-0 z-[60] bg-white dark:bg-slate-950 md:hidden transition-all duration-300 ease-in-out transform translate-x-full opacity-0 pointer-events-none">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-4 border-b dark:border-white/10">
                <div class="flex items-center space-x-3">
                    <img src="/BudgetBuddy-/public/BudgetBuddy.png" alt="BudgetBuddy Logo" class="h-10 w-10 rounded-lg object-cover">
                    <span class="text-xl font-bold text-gray-900 dark:text-white font-outfit">BudgetBuddy</span>
                </div>
                <button type="button" id="close-menu-button" class="p-2 rounded-md text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 flex flex-col space-y-6">
                <a href="/BudgetBuddy-/" class="text-2xl font-semibold transition-colors <?php echo $is_active('/'); ?>">Home</a>
                <a href="/BudgetBuddy-/blog" class="text-2xl font-semibold transition-colors <?php echo $is_active('/blog'); ?>">Blog</a>
                <a href="/BudgetBuddy-/contact" class="text-2xl font-semibold transition-colors <?php echo $is_active('/contact'); ?>">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <hr class="border-gray-100 dark:border-white/10" />
                    <a href="/BudgetBuddy-/transactions" class="text-2xl font-semibold text-gray-600 dark:text-slate-400 transition-colors <?php echo $is_active('/transactions'); ?>">Transactions</a>
                    <a href="/BudgetBuddy-/analytics" class="text-2xl font-semibold text-gray-600 dark:text-slate-400 transition-colors <?php echo $is_active('/analytics'); ?>">Analytics</a>
                    <div class="pt-6 space-y-4">
                        <?php 
                            $text = 'Go to Dashboard';
                            $type = 'a';
                            $href = '/BudgetBuddy-/dashboard';
                            $variant = 'primary';
                            $size = 'md';
                            $class = 'w-full py-3';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Sign Out';
                            $type = 'a';
                            $href = '/BudgetBuddy-/logout';
                            $variant = 'outline';
                            $size = 'md';
                            $class = 'w-full py-3 border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                <?php else: ?>
                    <div class="pt-8 space-y-4">
                        <?php 
                            $text = 'Sign In';
                            $type = 'a';
                            $href = '/BudgetBuddy-/login';
                            $variant = 'outline';
                            $size = 'md';
                            $class = 'w-full py-3 dark:border-white/10 dark:text-slate-300';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Get Started';
                            $type = 'a';
                            $href = '/BudgetBuddy-/register';
                            $variant = 'primary';
                            $size = 'md';
                            $class = 'w-full py-3';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeMenuButton = document.getElementById('close-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const themeToggle = document.getElementById('theme-toggle');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');

    // Update icons based on current theme
    const updateThemeIcons = () => {
        if (document.body.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
        } else {
            lightIcon.classList.add('hidden');
            darkIcon.classList.remove('hidden');
        }
    };

    updateThemeIcons();

    themeToggle?.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        updateThemeIcons();
    });

    const toggleMenu = (show) => {
        if (show) {
            mobileMenu.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
            document.body.style.overflow = 'hidden';
        } else {
            mobileMenu.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
            document.body.style.overflow = '';
        }
    };

    mobileMenuButton?.addEventListener('click', () => toggleMenu(true));
    closeMenuButton?.addEventListener('click', () => toggleMenu(false));

    // Handle ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') toggleMenu(false);
    });
});
</script>
