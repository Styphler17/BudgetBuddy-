<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME . ' – Best Budget App Without Bank Sync & Manual Tracker'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7B4GXB3');</script>
    <!-- End Google Tag Manager -->

    <!-- Canonical URL -->
    <?php if (isset($canonical)): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical); ?>">
    <?php endif; ?>

    <!-- Meta Tags -->
    <meta name="description" content="<?php echo isset($metaDescription) ? htmlspecialchars($metaDescription) : SITE_NAME . ': free manual budget app. Track expenses, plan budgets, and manage spending without bank sync or spreadsheets.'; ?>">
    
    <?php if (!empty($metaKeywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <?php
        $ogTitle  = isset($title) ? htmlspecialchars($title . ' | ' . SITE_NAME) : SITE_NAME . ' – Best Budget App Without Bank Sync & Manual Tracker';
        $ogDesc   = isset($metaDescription) ? htmlspecialchars($metaDescription) : SITE_NAME . ': free manual budget app. Track expenses, plan budgets, and manage spending without bank sync or spreadsheets.';
        $ogUrl    = isset($canonical) ? htmlspecialchars($canonical) : 'https://spendscribe.creativeutil.com/';
        $ogImage  = isset($ogImage) ? htmlspecialchars($ogImage) : BASE_URL . '/public/og-image.png';
        $ogType   = isset($ogType) ? htmlspecialchars($ogType) : 'website';
    ?>
    <meta property="og:type"        content="<?php echo $ogType; ?>">
    <meta property="og:url"         content="<?php echo $ogUrl; ?>">
    <meta property="og:title"       content="<?php echo $ogTitle; ?>">
    <meta property="og:description" content="<?php echo $ogDesc; ?>">
    <meta property="og:image"       content="<?php echo $ogImage; ?>">

    <!-- Twitter -->
    <meta property="twitter:card"        content="summary_large_image">
    <meta property="twitter:url"         content="<?php echo $ogUrl; ?>">
    <meta property="twitter:title"       content="<?php echo $ogTitle; ?>">
    <meta property="twitter:description" content="<?php echo $ogDesc; ?>">
    <meta property="twitter:image"       content="<?php echo $ogImage; ?>">

    <!-- JSON-LD Structured Data (SEO & E-E-A-T) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?php echo SITE_NAME; ?>",
      "url": "https://spendscribe.creativeutil.com/",
      "logo": "https://spendscribe.creativeutil.com/public/SpendScribe.png",
      "description": "<?php echo SITE_NAME; ?> is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.",
      "contactPoint": {
        "@type": "ContactPoint",
        "email": "brastyphler17@gmail.com",
        "contactType": "customer support"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "<?php echo SITE_NAME; ?>",
      "url": "https://SpendScribe.creativeutil.com/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://SpendScribe.creativeutil.com/blog?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/animations.css">
    
    <!-- EmailJS SDK -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            emailjs.init({
              publicKey: "_v_vSRGl_66IsEq9-",
            });
        })();
    </script>
    
    <!-- Tailwind CSS (Static Build) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">

    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6388735455910610" crossorigin="anonymous"></script>
    
    <!-- PWA -->
    <link rel="manifest" href="<?php echo BASE_URL; ?>/public/manifest.json">
    <meta name="theme-color" content="#10237f">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo BASE_URL; ?>/public/sw.js');
            });
        }
    </script>
    
    <style>
        body {
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        body.dark {
            background-color: #020617;
            color: #f1f5f9;
        }

        /* Typography Standardization */
        h1, .text-h1 { font-family: 'Outfit', sans-serif; font-weight: 700; line-height: 1.2; color: inherit; }
        h2, .text-h2 { font-family: 'Outfit', sans-serif; font-weight: 600; line-height: 1.3; color: inherit; }
        .text-display { font-family: 'Outfit', sans-serif; font-weight: 900; line-height: 1.1; color: inherit; }
        
        /* Micro-interactions */
        .btn-press:active { transform: scale(0.95); transition: transform 0.1s; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 10px;
        }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
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

    <!-- Main Content -->
    <main class="flex-grow">
        <?php echo (string)($content ?? ''); ?>
    </main>

    <!-- Footer -->
    <?php require_once APP_PATH . '/views/includes/Footer.php'; ?>

    <!-- Back to Top -->
    <?php require_once APP_PATH . '/views/includes/BackToTop.php'; ?>

    <!-- Save Changes Toast -->
    <?php require_once APP_PATH . '/views/includes/ToastSave.php'; ?>

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
