<?php
/**
 * Sitemap Generator Script
 */
require_once __DIR__ . '/../config/database.php';

// Define the base URL of the site
$siteUrl = 'https://spendscribe.creativeutil.com/';
$today = date('Y-m-d');

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// 1. Core Static Pages
$staticPages = [
    ['loc' => '', 'priority' => '1.0', 'freq' => 'weekly'],
    ['loc' => 'blog', 'priority' => '0.9', 'freq' => 'daily'],
    ['loc' => 'help', 'priority' => '0.6', 'freq' => 'monthly'],
    ['loc' => 'contact', 'priority' => '0.6', 'freq' => 'monthly'],
    ['loc' => 'privacy', 'priority' => '0.5', 'freq' => 'yearly'],
    ['loc' => 'login', 'priority' => '0.4', 'freq' => 'monthly'],
    ['loc' => 'register', 'priority' => '0.4', 'freq' => 'monthly'],
    ['loc' => 'admin-login', 'priority' => '0.2', 'freq' => 'monthly'],
];

foreach ($staticPages as $page) {
    $xml .= '  <url>' . PHP_EOL;
    $xml .= '    <loc>' . $siteUrl . $page['loc'] . '</loc>' . PHP_EOL;
    $xml .= '    <lastmod>' . $today . '</lastmod>' . PHP_EOL;
    $xml .= '    <changefreq>' . $page['freq'] . '</changefreq>' . PHP_EOL;
    $xml .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
    $xml .= '  </url>' . PHP_EOL;
}

// 2. Dynamic Blog Posts
try {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT slug, updated_at, created_at FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $post) {
        $lastMod = date('Y-m-d', strtotime($post['updated_at'] ?? $post['created_at']));
        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . $siteUrl . $post['slug'] . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $lastMod . '</lastmod>' . PHP_EOL;
        $xml .= '    <changefreq>monthly</changefreq>' . PHP_EOL;
        $xml .= '    <priority>0.7</priority>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Warning: Could not fetch blog posts for sitemap: " . $e->getMessage() . PHP_EOL;
}

$xml .= '</urlset>' . PHP_EOL;

$file = __DIR__ . '/../public/sitemap.xml';
if (file_put_contents($file, $xml)) {
    echo "Sitemap generated successfully at $file" . PHP_EOL;
} else {
    echo "Error: Failed to write sitemap file." . PHP_EOL;
}
