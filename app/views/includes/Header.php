<?php
/**
 * Header Component - Adapted from React Header.tsx
 */
$current_uri = $_SERVER['REQUEST_URI'];
$base_url = '';

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
            <a href="/" class="flex items-center space-x-3 group">
                <img src="/public/BudgetBuddy.png" alt="BudgetBuddy Logo" class="h-14 w-14 rounded-2xl object-cover shadow-sm group-hover:shadow-md transition-all duration-300">
                <span class="text-xl font-bold text-gray-900 dark:text-white font-outfit hidden md:block">BudgetBuddy</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/" class="transition-colors <?php echo $is_active('/'); ?>">Home</a>
                <a href="/blog" class="transition-colors <?php echo $is_active('/blog'); ?>">Blog</a>
                <a href="/contact" class="transition-colors <?php echo $is_active('/contact'); ?>">Contact</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/transactions" class="transition-colors <?php echo $is_active('/transactions'); ?>">Transactions</a>
                    <a href="/analytics" class="transition-colors <?php echo $is_active('/analytics'); ?>">Analytics</a>
                <?php endif; ?>
            </div>

            <!-- Auth Buttons & Theme Toggle -->
            <div class="flex items-center space-x-4">
                <!-- Theme Switcher -->
                <?php include APP_PATH . '/views/includes/ThemeSwitcher.php'; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <?php 
                            $text = 'Go to Dashboard';
                            $type = 'a';
                            $href = '/dashboard';
                            $variant = 'primary';
                            $size = 'sm';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Sign Out';
                            $type = 'a';
                            $href = '/logout';
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
                            $href = '/login';
                            $variant = 'outline';
                            $size = 'sm';
                            $class = 'dark:border-white/10 dark:bg-transparent dark:text-white dark:hover:bg-white/5';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Get Started';
                            $type = 'a';
                            $href = '/register';
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
</header>

<!-- Mobile Navigation Menu (Overlay) - Moved outside header for better stacking -->
<div id="mobile-menu" class="fixed inset-0 z-[100] bg-white dark:bg-slate-950 md:hidden transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] transform translate-x-full opacity-0 pointer-events-none">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between p-6 border-b dark:border-white/10">
            <div class="flex items-center space-x-3">
                <img src="/public/BudgetBuddy.png" alt="BudgetBuddy Logo" class="h-10 w-10 rounded-lg object-cover">
                <span class="text-xl font-bold text-gray-900 dark:text-white font-outfit">BudgetBuddy</span>
            </div>
            <button type="button" id="close-menu-button" class="p-2 rounded-xl text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-8 flex flex-col space-y-8">
            <a href="/" class="text-3xl font-bold transition-all <?php echo $is_active('/'); ?>">Home</a>
            <a href="/blog" class="text-3xl font-bold transition-all <?php echo $is_active('/blog'); ?>">Blog</a>
            <a href="/contact" class="text-3xl font-bold transition-all <?php echo $is_active('/contact'); ?>">Contact</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="h-px w-full bg-gray-100 dark:bg-white/5"></div>
                <a href="/transactions" class="text-xl font-bold text-gray-600 dark:text-slate-400 transition-colors <?php echo $is_active('/transactions'); ?>">Transactions</a>
                <a href="/analytics" class="text-xl font-bold text-gray-600 dark:text-slate-400 transition-colors <?php echo $is_active('/analytics'); ?>">Analytics</a>
                <div class="pt-8 space-y-4">
                    <?php 
                        $text = 'Go to Dashboard';
                        $type = 'a';
                        $href = '/dashboard';
                        $variant = 'primary';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl shadow-xl';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                    <?php 
                        $text = 'Sign Out';
                        $type = 'a';
                        $href = '/logout';
                        $variant = 'outline';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl border-rose-100 text-rose-600 hover:bg-rose-50';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                </div>
            <?php else: ?>
                <div class="pt-12 space-y-4">
                    <?php 
                        $text = 'Sign In';
                        $type = 'a';
                        $href = '/login';
                        $variant = 'outline';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl dark:border-white/10 dark:text-slate-300';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                    <?php 
                        $text = 'Get Started';
                        $type = 'a';
                        $href = '/register';
                        $variant = 'primary';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl shadow-xl shadow-primary/20';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeMenuButton = document.getElementById('close-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

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
