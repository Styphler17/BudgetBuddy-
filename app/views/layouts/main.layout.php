<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | SpendScribe' : 'SpendScribe – Simple Manual Budget & Spending Tracker'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    
    <!-- Meta Tags -->
    <meta name="description" content="SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://spendscribe.creativeutil.com/">
    <meta property="og:title" content="SpendScribe – Simple Manual Budget & Spending Tracker">
    <meta property="og:description" content="SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="og:image" content="<?php echo BASE_URL; ?>/public/og-image.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://spendscribe.creativeutil.com/">
    <meta property="twitter:title" content="SpendScribe – Simple Manual Budget & Spending Tracker">
    <meta property="twitter:description" content="SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="twitter:image" content="<?php echo BASE_URL; ?>/public/og-image.png">

    <!-- JSON-LD Structured Data (SEO & E-E-A-T) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "SpendScribe",
      "url": "https://spendscribe.creativeutil.com/",
      "logo": "https://spendscribe.creativeutil.com/public/SpendScribe.png",
      "description": "SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.",
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
      "name": "SpendScribe",
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
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: "#10237f", // Navy
                            foreground: "#ffffff",
                        },
                        secondary: {
                            DEFAULT: "#1e293b", // Slate-800
                            foreground: "#ffffff",
                        },
                        accent: {
                            DEFAULT: "#a2db21", // Lime
                            foreground: "#10237f",
                        },
                        brand: {
                            DEFAULT: "#100fb0", // Indigo
                            foreground: "#ffffff",
                        },
                        success: "#10b981",
                        warning: "#f59e0b",
                        destructive: "#ef4444",
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    fontWeight: {
                        display: '900',
                        h1: '700',
                        h2: '600',
                        body: '400',
                    },
                    spacing: {
                        '8px': '8px',
                        '16px': '16px',
                        '24px': '24px',
                        '32px': '32px',
                    },
                    boxShadow: {
                        toast: "0px 32px 64px -16px rgba(0,0,0,0.30), 0px 16px 32px -8px rgba(0,0,0,0.30), 0px 8px 16px -4px rgba(0,0,0,0.24), 0px 4px 8px -2px rgba(0,0,0,0.24), 0px -8px 16px -1px rgba(0,0,0,0.16), 0px 2px 4px -1px rgba(0,0,0,0.24), 0px 0px 0px 1px rgba(0,0,0,1.00), inset 0px 0px 0px 1px rgba(255,255,255,0.08), inset 0px 1px 0px 0px rgba(255,255,255,0.20)"
                    }
                }
            }
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
        
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.5);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.1);
        }

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
        <?php echo $content; ?>
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
