<?php
/**
 * Auth Layout - Standardized to match Main Layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7B4GXB3');</script>
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://spendscribe.creativeutil.com/">
    <meta property="og:title" content="<?php echo SITE_NAME; ?> – Simple Manual Budget & Spending Tracker">
    <meta property="og:description" content="<?php echo SITE_NAME; ?> is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="og:image" content="<?php echo BASE_URL; ?>/public/og-image.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://spendscribe.creativeutil.com/">
    <meta property="twitter:title" content="<?php echo SITE_NAME; ?> – Simple Manual Budget & Spending Tracker">
    <meta property="twitter:description" content="<?php echo SITE_NAME; ?> is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="twitter:image" content="<?php echo BASE_URL; ?>/public/og-image.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/animations.css">
    
    <!-- Tailwind CSS (Static Build) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #ffffff;
            transition: background-color 0.3s ease;
        }
        body.dark { background-color: #020617; color: #f1f5f9; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        h1, .text-h1 { font-family: 'Outfit', sans-serif; font-weight: 700; }
        h2, .text-h2 { font-family: 'Outfit', sans-serif; font-weight: 600; }
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
<body class="min-h-screen flex flex-col bg-white dark:bg-slate-950 text-gray-900 dark:text-slate-100 transition-colors duration-300">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7B4GXB3"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
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
    <!-- Header/Navigation -->
    <?php require_once APP_PATH . '/views/includes/Header.php'; ?>

    <!-- Flash error from session (e.g. CSRF redirect) -->
    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg">
            <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php echo (string)($content ?? ''); ?>
    </main>

    <!-- Footer -->
    <?php require_once APP_PATH . '/views/includes/Footer.php'; ?>

    <!-- Back to Top -->
    <?php require_once APP_PATH . '/views/includes/BackToTop.php'; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();

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
    </script>
</body>
</html>
