<?php
/**
 * Admin Layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?> - <?php echo SITE_NAME; ?> Intelligence Hub</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    <!-- Tailwind CSS (Static Build) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tiny.cloud/1/ky4xtv1lrw74kgz3s89jm1m0tw6d1supmj4xpnbibfjk5qkz/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Custom Animations -->
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/animations.css">
        
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; transition: background-color 0.3s ease, color 0.3s ease; }
        body.dark { background-color: #020617; color: #f1f5f9; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

        .dark header { background-color: rgba(2, 6, 23, 0.8) !important; border-color: rgba(255, 255, 255, 0.1) !important; }
        .dark .bg-white { background-color: #0f172a !important; }
        .dark .text-gray-900 { color: #ffffff !important; }
        .dark .text-gray-600 { color: #cbd5e1 !important; }
    </style>
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("data-lucide", "eye-off");
            } else {
                input.type = "password";
                icon.setAttribute("data-lucide", "eye");
            }
            lucide.createIcons();
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">
    <script>
        // Dark mode initialization - 3 State Support
        (function() {
            const savedTheme = localStorage.getItem('theme-mode') || 'system';
            const isDark = savedTheme === 'dark' || 
                (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDark) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        })();
    </script>
    
    <!-- Sidebar Wrapper -->
    <div class="flex min-h-screen">
        <?php require_once APP_PATH . '/views/includes/AdminSidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-1 lg:ml-64 min-h-screen flex flex-col transition-all">
            <!-- Header -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 backdrop-blur-md border-b border-gray-200 px-4 md:px-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="admin-mobile-toggle" class="lg:hidden p-2 rounded-md hover:bg-gray-100 transition-colors">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-900 font-outfit">Admin Dashboard</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Administrator</span>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm ring-2 ring-primary/10 overflow-hidden">
                        <?php if (isset($_SESSION['admin_profile_pic']) && $_SESSION['admin_profile_pic']): ?>
                            <img src="<?php echo BASE_URL; ?>/public/uploads/profile_pics/<?php echo $_SESSION['admin_profile_pic']; ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <?php echo isset($_SESSION['admin_name']) ? strtoupper(substr($_SESSION['admin_name'], 0, 1)) : 'A'; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>
            
            <main class="flex-1 p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    <?php echo $content; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Back to Top -->
    <?php require_once APP_PATH . '/views/includes/BackToTop.php'; ?>

    <!-- Save Changes Toast -->
    <?php require_once APP_PATH . '/views/includes/ToastSave.php'; ?>

    <script>
        window.addEventListener('load', () => {
            lucide.createIcons();
        });

        // Global Glowing Effect Controller
        document.addEventListener('DOMContentLoaded', () => {
            const proximity = 150;
            
            const handlePointerMove = (e) => {
                const wrappers = document.querySelectorAll('.glowing-wrapper');
                wrappers.forEach(wrapper => {
                    const container = wrapper.querySelector('.glowing-effect-container');
                    if (!container) return;
                    
                    const rect = wrapper.getBoundingClientRect();
                    const mouseX = e.clientX;
                    const mouseY = e.clientY;

                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    const isActive = 
                        mouseX > rect.left - proximity &&
                        mouseX < rect.right + proximity &&
                        mouseY > rect.top - proximity &&
                        mouseY < rect.bottom + proximity;

                    if (isActive) {
                        container.style.setProperty('--active', '1');
                        const angle = Math.atan2(mouseY - centerY, mouseX - centerX) * (180 / Math.PI) + 90;
                        container.style.setProperty('--start', angle);
                    } else {
                        container.style.setProperty('--active', '0');
                    }
                });
            };

            window.addEventListener('pointermove', handlePointerMove);
        });

        // Mobile toggle logic
        document.getElementById('admin-mobile-toggle')?.addEventListener('click', () => {
            const sidebar = document.getElementById('admin-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        });
    </script>
</body>
</html>
