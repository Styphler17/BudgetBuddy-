import { writeFile } from "node:fs/promises";
import { resolve, dirname } from "node:path";
import { fileURLToPath } from "node:url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const BASE_URL = "https://budgetbuddy.creativeutil.com";

const staticRoutes = [
  { path: "/", changefreq: "weekly", priority: "1.0" },
  { path: "/blog", changefreq: "daily", priority: "0.9" },
  { path: "/help", changefreq: "monthly", priority: "0.6" },
  { path: "/contact", changefreq: "monthly", priority: "0.6" },
  { path: "/privacy", changefreq: "yearly", priority: "0.5" },
  { path: "/login", changefreq: "monthly", priority: "0.4" },
  { path: "/register", changefreq: "monthly", priority: "0.4" },
  { path: "/admin-login", changefreq: "monthly", priority: "0.2" }
];

const isoDate = new Date().toISOString().split("T")[0];

const xml = [
  '<?xml version="1.0" encoding="UTF-8"?>',
  '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
];

for (const route of staticRoutes) {
  xml.push(
    "  <url>",
    `    <loc>${BASE_URL}${route.path}</loc>`,
    `    <lastmod>${isoDate}</lastmod>`,
    `    <changefreq>${route.changefreq}</changefreq>`,
    `    <priority>${route.priority}</priority>`,
    "  </url>"
  );
}

xml.push("</urlset>", "");

const sitemapPath = resolve(__dirname, "..", "public", "sitemap.xml");

await writeFile(sitemapPath, xml.join("\n"), "utf8");

console.log(`Sitemap updated at ${sitemapPath}`);
