# SpendScribe – Project TODO

## Done
- [x] Block `/admin-login` from Google indexing
  - Added `Disallow: /admin-login` and `Disallow: /admin` to `public/robots.txt`
  - Added `noindex, nofollow` meta tag to auth layout (triggered via `noIndex => true` in AuthController)
  - Removed `/admin-login` entry from `scripts/generate_sitemap.php`
- [x] Fix dynamic SEO meta tags for blog posts
  - `main.layout.php` now uses `$metaDescription`, `$metaKeywords`, `$canonicalUrl`, `$ogImage`, `$ogType` variables with homepage fallbacks
  - `BlogController::view()` now passes post's meta fields to the layout
  - `blog/show.php` now outputs `BlogPosting` JSON-LD structured data
- [x] New blog content — 6 SEO-optimised articles in `scripts/seed_blog_articles.sql`
  - `50-30-20-budget-rule`
  - `how-to-budget-irregular-income`
  - `envelope-budgeting-digital`
  - `monthly-budget-reset-checklist`
  - `budget-as-a-couple`
  - `net-worth-tracker-no-bank-sync`

## To Do

### Urgent
- [ ] **Run the sitemap generator** after seeding articles
  - `php scripts/generate_sitemap.php` from project root
- [ ] **Import seed articles** into the live database
  - `mysql -u your_user -p u509059322_SpendScribe202 < scripts/seed_blog_articles.sql`
- [ ] **Add cover images** for the 6 new articles to `public/images/blog/`:
  - `50-30-20.png`, `irregular-income.png`, `envelope-budget.png`, `budget-reset.png`, `couple-budget.png`, `net-worth.png`
- [ ] **Request URL removal** of `https://spendscribe.creativeutil.com/admin-login` in Google Search Console
  - Submit updated sitemap while you are there
- [ ] **Publish the YNAB alternative page** (`ynab-alternative-no-bank-sync`) — currently draft but already in the sitemap

### SEO & Content
- [ ] Add meta_title, meta_description, meta_keywords to the 3 existing blog posts via admin panel
- [ ] Add cover images to existing posts if missing
- [ ] Add related articles logic to `blog/show.php` sidebar (currently shows "More stories are on the way")
- [ ] Add Open Graph image upload to the admin blog editor

### Features
- [ ] Add reading progress bar to blog post view
- [ ] Add estimated reading time auto-calculation in the admin blog editor
- [ ] Add social share buttons (Twitter/X, LinkedIn, copy link) to blog posts
- [ ] Implement related articles query in `Blog::getRelated($slug, $tags, $limit = 3)`

### Maintenance
- [ ] Update `robot.txt` (root-level duplicate) to match `public/robots.txt` — or delete the duplicate
- [ ] Audit `public/sitemap.xml` after running the sitemap generator to confirm all 9+ posts appear
- [ ] Review `/login` and `/register` — consider whether they need to be in the sitemap
