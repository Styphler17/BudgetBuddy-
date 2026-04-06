-- Fix blog image paths for the 6 seeded articles
-- Run this on the live database to correct the cover_image_url column

UPDATE blog_posts SET cover_image_url = '/public/blog/50-30-20.webp'         WHERE slug = '50-30-20-budget-rule';
UPDATE blog_posts SET cover_image_url = '/public/blog/irregular-income.webp'  WHERE slug = 'how-to-budget-irregular-income';
UPDATE blog_posts SET cover_image_url = '/public/blog/envelope-budget.webp'   WHERE slug = 'envelope-budgeting-digital';
UPDATE blog_posts SET cover_image_url = '/public/blog/budget-reset.webp'      WHERE slug = 'monthly-budget-reset-checklist';
UPDATE blog_posts SET cover_image_url = '/public/blog/couple-budget.webp'     WHERE slug = 'budget-as-a-couple';
UPDATE blog_posts SET cover_image_url = '/public/blog/net-worth.webp'         WHERE slug = 'net-worth-tracker-no-bank-sync';
