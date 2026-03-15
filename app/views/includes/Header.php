<?php
/**
 * Header Component - Adapted from React Header.tsx
 */
$current_uri = $_SERVER['REQUEST_URI'];
$base_url = '';

// Helper to check if a link is active
$is_active = function($path) use ($current_uri, $base_url) {
    $full_path = ($path === '/') ? '/' : $path;
    $normalized_uri = rtrim($current_uri, '/');
    $normalized_path = rtrim($full_path, '/');
    
    $is_active_link = ($path === '/' && ($normalized_uri === '' || $normalized_uri === BASE_URL || $normalized_uri === '/')) ||
                      (strpos($normalized_uri, $normalized_path) !== false && $path !== '/');

    $colors = $is_active_link ? 'text-primary dark:text-accent font-bold' : 'text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white';
    
    return [
        'active' => $is_active_link,
        'class' => $colors
    ];
};
?>
<header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-gray-200/50 dark:border-white/10 transition-all">
    <div class="container mx-auto px-4 py-2">
        <nav class="flex items-center justify-between">
            <!-- Logo Section -->
            <a href="<?php echo BASE_URL; ?>/" class="flex items-center group">
                <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="<?php echo SITE_NAME; ?> Logo" class="h-12 w-auto object-contain transition-all duration-300">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <?php 
                    $nav_links = [
                        ['path' => '/', 'label' => 'Home'],
                        ['path' => '/blog', 'label' => 'Blog'],
                        ['path' => '/contact', 'label' => 'Contact'],
                    ];
                    
                    if (isset($_SESSION['user_id'])) {
                        $nav_links[] = ['path' => '/transactions', 'label' => 'Transactions'];
                        $nav_links[] = ['path' => '/analytics', 'label' => 'Analytics'];
                    }

                    foreach ($nav_links as $link):
                        $state = $is_active($link['path']);
                ?>
                    <a href="<?php echo BASE_URL . $link['path']; ?>" class="relative py-2 transition-colors <?php echo $state['class']; ?> group/link">
                        <?php echo $link['label']; ?>
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary dark:bg-accent transform origin-left transition-transform duration-300 <?php echo $state['active'] ? 'scale-x-100' : 'scale-x-0 group-hover/link:scale-x-100'; ?>"></span>
                    </a>
                <?php endforeach; ?>
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
                            $href = BASE_URL . '/dashboard';
                            $variant = 'primary';
                            $size = 'sm';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Sign Out';
                            $type = 'a';
                            $href = BASE_URL . '/logout';
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
                            $href = BASE_URL . '/login';
                            $variant = 'outline';
                            $size = 'sm';
                            $class = 'dark:border-white/10 dark:bg-transparent dark:text-white dark:hover:bg-white/5';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                        <?php 
                            $text = 'Get Started';
                            $type = 'a';
                            $href = BASE_URL . '/register';
                            $variant = 'primary';
                            $size = 'sm';
                            include APP_PATH . '/views/includes/Button.php';
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Mobile Menu Button -->
                <button type="button" id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
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
            <div class="flex items-center">
                <img src="<?php echo BASE_URL; ?>/public/SpendScribe.png" alt="<?php echo SITE_NAME; ?> Logo" class="h-10 w-auto object-contain">
            </div>
            <button type="button" id="close-menu-button" class="p-2 rounded-xl text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-8 flex flex-col space-y-8">
            <a href="<?php echo BASE_URL; ?>/" class="text-3xl font-bold transition-all <?php echo $is_active('/'); ?>">Home</a>
            <a href="<?php echo BASE_URL; ?>/blog" class="text-3xl font-bold transition-all <?php echo $is_active('/blog'); ?>">Blog</a>
            <a href="<?php echo BASE_URL; ?>/contact" class="text-3xl font-bold transition-all <?php echo $is_active('/contact'); ?>">Contact</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="h-px w-full bg-gray-100 dark:bg-white/5"></div>
                <a href="<?php echo BASE_URL; ?>/transactions" class="text-xl font-bold text-gray-600 dark:text-slate-300 transition-colors <?php echo $is_active('/transactions'); ?>">Transactions</a>
                <a href="<?php echo BASE_URL; ?>/analytics" class="text-xl font-bold text-gray-600 dark:text-slate-300 transition-colors <?php echo $is_active('/analytics'); ?>">Analytics</a>
                <div class="pt-8 space-y-4">
                    <?php 
                        $text = 'Go to Dashboard';
                        $type = 'a';
                        $href = BASE_URL . '/dashboard';
                        $variant = 'primary';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl shadow-xl';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                    <?php 
                        $text = 'Sign Out';
                        $type = 'a';
                        $href = BASE_URL . '/logout';
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
                        $href = BASE_URL . '/login';
                        $variant = 'outline';
                        $size = 'lg';
                        $class = 'w-full py-4 rounded-2xl dark:border-white/10 dark:text-slate-300';
                        include APP_PATH . '/views/includes/Button.php';
                    ?>
                    <?php 
                        $text = 'Get Started';
                        $type = 'a';
                        $href = BASE_URL . '/register';
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
