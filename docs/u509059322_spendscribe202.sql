-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2026 at 10:53 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u509059322_spendscribe202`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('checking','savings','credit','investment','cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `name`, `type`, `balance`, `currency`, `created_at`) VALUES
(1, 1, 'Wise Account', 'savings', '20.00', 'USD', '2026-03-10 06:29:54'),
(3, 3, 'African Identity', 'savings', '19780.00', 'JPY', '2026-03-14 08:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin','moderator') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `name`, `password_hash`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`, `profile_pic`) VALUES
(1, 'temp.admin@budgetbuddy.com', 'Temporary Admin', '$2b$10$aRGn7iqI8jZJL9F1tJ4WQOsc0SI04xknY9/AvU0HRWPAjThQLxth.', 'admin', 1, NULL, '2025-10-28 02:38:49', '2026-03-14 12:10:06', '32608a78e033a69c56ef2c2013fb84f9.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` enum('user','category','transaction','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `target_type`, `target_id`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'update_profile', 'system', 1, 'Updated profile details', '::1', '2026-03-14 12:10:06'),
(2, 1, 'update_blog', 'system', 4, 'Updated post: Mastering Your Monthly Budget: A Complete Guide', '::1', '2026-03-14 12:11:13'),
(3, 1, 'update_blog', 'system', 5, 'Updated post: The Best Budget App Like YNAB (Without the Bank Sync or the $100 Price Tag)', '::1', '2026-03-14 13:36:10'),
(4, 1, 'update_blog', 'system', 5, 'Updated post: The Best Budget App Like YNAB (Without the Bank Sync or the $100 Price Tag)', '::1', '2026-03-14 13:37:07'),
(5, 1, 'delete_blog', 'system', 4, 'Deleted post ID: 4', '::1', '2026-03-14 13:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `cover_image_url` text COLLATE utf8mb4_unicode_ci,
  `cover_image_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `tags` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `reading_time` int(11) DEFAULT '0',
  `feature_embed_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `admin_id`, `title`, `slug`, `excerpt`, `cover_image_url`, `cover_image_alt`, `status`, `content`, `tags`, `meta_title`, `meta_description`, `meta_keywords`, `reading_time`, `feature_embed_url`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 1, 'Zero-Based Budget Blueprint for 2025', 'zero-based-budget-blueprint-2025', 'Give every dollar a job with a zero-based plan that keeps cash flow predictable and goals funded.', 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1400&q=80', 'Table with laptop, budgeting journal, and coffee cup', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Kickstart Your Zero-Based Budget\"},{\"type\":\"paragraph\",\"text\":\"BudgetBuddy helps you route every incoming dollar toward a precise job so nothing leaks through the cracks. Start by capturing your true monthly income, then allocate funds into must-have expenses, savings buckets, and intentional splurge categories.\"},{\"type\":\"list\",\"items\":[\"Log baseline expenses for the last 90 days to set realistic caps\",\"Direct your paycheck into BudgetBuddy\'s envelope automation\",\"Create guardrails with category alerts before overspending happens\",\"Schedule a 30-minute monthly reset to reconcile and rebalance\"]},{\"type\":\"paragraph\",\"text\":\"Once the core framework is live, use BudgetBuddy\'s scenario planner to test what-if situations. You can simulate moving rent, increasing side-income, or accelerating a savings goal and instantly see downstream impact.\"}]', 'budgeting,cash flow,planning', 'Zero-Based Budget Blueprint | BudgetBuddy Blog', 'Follow this zero-based budgeting workflow to align income, expenses, and savings inside BudgetBuddy.', 'budgeting, zero-based budget, cash flow, planning', 7, 'https://www.youtube.com/watch?v=z2X2HaTvkl8', '2025-10-28 02:38:50', '2025-10-28 10:54:55', '2025-10-28 10:54:54'),
(2, 1, 'Automate Your Cash Flow in Under an Hour', 'automate-your-cash-flow-in-under-an-hour', 'Build a dependable money system with smart automations that keep bills current and savings growing.', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1400&q=80', 'Smartphone banking app showing recurring transfers', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Build a Dependable Automation Stack\"},{\"type\":\"paragraph\",\"text\":\"Set recurring transfers once, then let BudgetBuddy push money to bills, sinking funds, and investments with zero manual effort. Automation protects your goals from procrastination and makes saving the path of least resistance.\"},{\"type\":\"list\",\"items\":[\"Group fixed expenses by due date and pay them two days early\",\"Route 5% of each paycheck into an emergency buffer before spending\",\"Create quarterly sinking funds for insurance premiums and annual software renewals\",\"Turn on smart nudges so BudgetBuddy warns you before balances dip low\"]},{\"type\":\"quote\",\"text\":\"Simple automation beats heroic willpower every time.\",\"caption\":\"BudgetBuddy success coach\"}]', 'automation,cash flow,habits', 'Cash Flow Automation Checklist | BudgetBuddy Blog', 'Use this automation checklist to keep every bill current while your savings climb in the background.', 'cash flow automation, financial habits, budgeting checklist', 6, NULL, '2025-10-28 02:38:50', '2025-10-28 10:54:58', '2025-10-28 10:54:57'),
(3, 1, 'Smart Savings Playbook: From Micro Wins to Mega Goals', 'smart-savings-playbook-micro-to-mega', 'Stack small savings wins into major milestones using BudgetBuddy\'s goal templates and progress analytics.', 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=80', 'Jar filled with labelled savings envelopes', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Stack Micro Wins Into Long-Term Momentum\"},{\"type\":\"paragraph\",\"text\":\"Break intimidating goals into micro-milestones, then celebrate each win to keep motivation high. BudgetBuddy\'s goal templates make it effortless to see how today\'s deposits accelerate tomorrow\'s dreams.\"},{\"type\":\"list\",\"items\":[\"Give every goal a clear why, target amount, and deadline\",\"Automate weekly micro-deposits so progress never stalls\",\"Visualise momentum with BudgetBuddy\'s trajectory charts\",\"Bundle accountability by sharing dashboards with partners\"]},{\"type\":\"paragraph\",\"text\":\"As each milestone locks in, reallocate the freed-up cash toward the next priority. This compounding effect keeps your savings engine accelerating instead of starting from zero every January.\"}]', 'savings,goals,motivation', 'Smart Savings Playbook | BudgetBuddy Blog', 'Learn how to convert bite-sized deposits into game-changing results with BudgetBuddy goal automation.', 'savings goals, micro-savings, financial motivation', 8, 'https://www.youtube.com/watch?v=3X9lT9Y4QWc', '2025-10-28 02:38:50', '2025-10-28 02:38:50', '2025-10-19 02:38:50'),
(5, 1, 'The Best Budget App Like YNAB (Without the Bank Sync or the $100 Price Tag)', 'ynab-alternative-no-bank-sync', 'Looking for a YNAB alternative that doesn\'t force you to link your bank account? Discover why SpendScribe is the best manual budget app for conscious spenders who want premium UI without the $100 price tag.', '', NULL, 'draft', '<p>If you have ever tried to take control of your finances, you have likely heard of YNAB (You Need A Budget). It is famous for its \"Four Rules\" and its robust envelope-style budgeting system. For years, it has been the gold standard for people serious about getting out of debt. But lately, something has changed. The community is restless.</p>\r\n<p>Between steady price increases now reaching nearly $100 per year and a forced reliance on third-party bank syncing services like Plaid, many users are looking for an exit strategy. They want the intentionality of YNAB, but they don&rsquo;t want the privacy risks or the high cost. Enter&nbsp;<strong>SpendScribe</strong>: the premier manual entry alternative for the modern age.</p>\r\n<h3>The Problem with Forced Bank Syncing</h3>\r\n<p>In the world of fintech, \"convenience\" is often a Trojan horse for data collection. Most modern budgeting apps demand that you hand over your bank credentials the moment you sign up. While bank syncing promises to save time, it introduces three major problems:</p>\r\n<ol>\r\n<li><strong>The Privacy Gap:</strong> When you connect your bank, you are sharing your entire financial history with a middleman. For many, this is a bridge too far.</li>\r\n<li><strong>Passive Management:</strong> When transactions appear automatically, you become a passive observer of your money. You \"approve\" a transaction that happened three days ago, rather than making a conscious decision at the point of sale.</li>\r\n<li><strong>Technical Friction:</strong> Connections break. MFA (Multi-Factor Authentication) resets. Accounts desync. Often, you spend more time fixing the \"auto\" connection than you would have spent just typing in the number.</li>\r\n</ol>\r\n<h3>Why Manual Entry is the \"Secret Sauce\" of Wealth</h3>\r\n<p>The core philosophy of SpendScribe is that <strong>manual entry is a feature, not a bug</strong>. When you have to physically type in \"Starbucks - $6.50,\" your brain processes that expense differently. There is a psychological \"friction\" that occurs. That friction is exactly what stops impulse spending.</p>\r\n<p>YNAB used to champion this manual-first approach, but as they grew, they pushed users more and more toward automation. SpendScribe brings back that \"old school\" discipline but wraps it in a stunning, high-end user interface that makes tracking a joy rather than a chore.</p>\r\n<h3>SpendScribe vs. YNAB: A Comparative Look</h3>\r\n<p>If you are considering making the switch, here is how the two stack up:</p>\r\n<h4>1. The Cost Factor</h4>\r\n<p>YNAB currently costs about $14.99 per month or $99 per year. For a tool meant to help you save money, that is a significant hurdle. SpendScribe offers its core manual tracking for free. We believe that financial clarity should be a right, not a subscription-locked privilege.</p>\r\n<h4>2. Privacy-First Architecture</h4>\r\n<p>SpendScribe does not use Plaid. We do not use Salt Edge. We do not want your bank login. Your data is tied to your account, and we focus on providing the tools for <em>you</em> to manage it. You are the pilot, not the passenger.</p>\r\n<h4>3. Multi-Currency for the Global Citizen</h4>\r\n<p>One of the biggest frustrations for expats and travelers using YNAB is its poor handling of multiple currencies within a single budget. SpendScribe was built from the ground up to handle multiple accounts in distinct currencies, making it the perfect companion for digital nomads and global professionals.</p>\r\n<h4>4. Premium Aesthetics</h4>\r\n<p>Usually, \"manual\" apps look like they were built in 1998. They are clunky, gray, and depressing. SpendScribe uses modern design principles, glassmorphism, soft gradients, and intuitive layouts to give you that \"Apple-quality\" experience for free.</p>\r\n<h3>How to Transition Successfully</h3>\r\n<p>Switching from an automated system to SpendScribe is easier than you think. Start by following these three steps:</p>\r\n<ul>\r\n<li><strong>The 24-Hour Rule:</strong> Try to log every expense within 24 hours of it happening. The SpendScribe mobile-responsive web app makes this take less than 10 seconds.</li>\r\n<li><strong>Audit Your Categories:</strong> Don&rsquo;t overcomplicate it. Start with 5-10 broad categories (Housing, Food, Transport, Fun, Savings) and refine them as you go.</li>\r\n<li><strong>Use the Exports:</strong> Use our free CSV and PDF export tools once a month to review your progress outside of the app. It provides a \"birds-eye view\" that spreadsheets can&rsquo;t match.</li>\r\n</ul>\r\n<h3>The Verdict</h3>\r\n<p>If you are tired of paying $100 a year to an app that demands your bank password, it is time to try something different. SpendScribe gives you the premium feel of a high-end fintech app with the soul and privacy of a paper notebook.</p>\r\n<p><strong>Ready to take back control?</strong> Stop syncing and start scribing. Your financial future depends on your awareness, not an algorithm.</p>\r\n<hr>\r\n<p><em>Tired of apps demanding your bank login just to show you a pie chart? SpendScribe offers premium, glassmorphism analytics that run 100% on manual entries. No Plaid, no scraping, no tracking. <strong><a href=\"\\&quot;/register\\&quot;\">Try SpendScribe for Free Today</a></strong>.</em></p>', 'Budgeting, YNAB Alternative, Manual Tracking, Privacy', NULL, NULL, NULL, 6, NULL, '2026-03-14 13:29:00', '2026-03-14 13:37:07', '2026-03-14 13:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `period` enum('daily','weekly','monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '#3b82f6',
  `budget` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `emoji`, `color`, `budget`, `created_at`) VALUES
(1, 1, 'Car', '🚗', '#3b82f6', '5000.00', '2026-03-10 01:55:50'),
(2, 3, 'Expenses', ' 😒', '#3b82f6', '600.00', '2026-03-14 06:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` int(11) NOT NULL,
  `from_currency` varchar(3) NOT NULL,
  `to_currency` varchar(3) NOT NULL,
  `rate` decimal(15,6) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `from_currency`, `to_currency`, `rate`, `updated_at`) VALUES
(8, 'JPY', 'EUR', '0.005476', '2026-03-14 09:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) DEFAULT '0.00',
  `deadline` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_milestone` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `name`, `target_amount`, `current_amount`, `deadline`, `category_id`, `created_at`, `last_milestone`) VALUES
(1, 1, 'Car', '5000.00', '0.00', '2026-12-31', NULL, '2026-03-10 01:50:12', 0),
(2, 2, 'test', '2000.00', '0.00', '2027-03-02', NULL, '2026-03-11 09:07:15', 0),
(3, 3, 'New Phone', '900.00', '0.00', '2026-12-24', NULL, '2026-03-14 06:11:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `icon` varchar(50) DEFAULT 'bell',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recurring_transactions`
--

CREATE TABLE `recurring_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text,
  `type` enum('income','expense') NOT NULL,
  `frequency` enum('daily','weekly','monthly','yearly') NOT NULL,
  `start_date` date NOT NULL,
  `last_run_date` date DEFAULT NULL,
  `next_run_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recurring_transactions`
--

INSERT INTO `recurring_transactions` (`id`, `user_id`, `account_id`, `category_id`, `amount`, `description`, `type`, `frequency`, `start_date`, `last_run_date`, `next_run_date`, `is_active`, `created_at`) VALUES
(2, 3, 3, 2, '220.00', 'netflix', 'expense', 'monthly', '2026-03-14', '2026-03-14', '2026-04-14', 1, '2026-03-14 09:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` enum('string','number','boolean','json') COLLATE utf8mb4_unicode_ci DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('income','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_transfer` tinyint(1) DEFAULT '0',
  `transfer_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `category_id`, `account_id`, `amount`, `description`, `type`, `is_transfer`, `transfer_id`, `date`, `created_at`) VALUES
(1, 1, 1, NULL, '200.00', 'saved', 'expense', 0, NULL, '2026-03-10', '2026-03-10 01:57:17'),
(3, 3, 2, 3, '220.00', 'netflix (Recurring)', 'expense', 0, NULL, '2026-03-14', '2026-03-14 09:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `is_active` tinyint(1) DEFAULT '1',
  `email_verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT '0',
  `recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password_hash`, `currency`, `is_active`, `email_verified`, `created_at`, `updated_at`, `verification_token`, `two_factor_secret`, `two_factor_enabled`, `recovery_codes`, `profile_pic`) VALUES
(1, 'test@budgetbuddy.com', 'Test User', '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'USD', 1, 1, '2026-03-10 01:29:55', '2026-03-14 07:34:10', NULL, NULL, 0, NULL, NULL),
(2, 'livefinal@example.com', 'Live User', '$2b$10$NJA8JKFM.Kii6h.3k3b79eIrx8sDSW/tAS5Ih.yWcKj5mVA0DoZ3a', 'USD', 1, 1, '2026-03-11 08:14:31', '2026-03-14 07:34:10', NULL, NULL, 0, NULL, NULL),
(3, 'changeme@test.com', 'CHANGE Me', '$2y$10$6LCZaXSLenXGogTCT6jVZuv1sSn6Mex/XwVEeuhAShzr0rt7VQGd6', 'JPY', 1, 1, '2026-03-11 08:21:38', '2026-03-14 11:10:19', NULL, '931d046a93fe732f34de659d3b6478ba', 0, NULL, '526a3687334d5137c28eb34f956a1dfd.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 3, 'Logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 07:07:36'),
(2, 3, 'Login', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 07:34:59'),
(3, 3, 'Login', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-14 07:36:07'),
(4, 3, 'Currency Update', 'User updated preferred currency to EUR', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 07:59:35'),
(5, 3, 'Currency Update', 'User updated preferred currency to GHS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 07:59:47'),
(6, 3, 'Currency Update', 'User updated preferred currency to EUR', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 08:14:23'),
(7, 3, 'Currency Update', 'User updated preferred currency to GHS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 08:14:54'),
(8, 3, 'Currency Update', 'User updated preferred currency to EUR', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 08:22:50'),
(9, 3, 'Currency Update', 'User updated preferred currency to GHS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 08:53:41'),
(10, 3, 'Currency Update', 'User updated preferred currency to NGN', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:05:22'),
(11, 3, 'Currency Update', 'User updated preferred currency to EUR', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:07:03'),
(12, 3, 'Currency Update', 'User updated preferred currency to JPY', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:08:09'),
(13, 3, 'Export', 'User exported transactions to CSV', '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-03-14 09:22:55'),
(14, 3, 'Export', 'User exported transactions to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:37:10'),
(15, 3, 'Export', 'User exported transactions to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:37:15'),
(16, 3, 'Export', 'User exported transactions to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:49:46'),
(17, 3, 'Export', 'User exported transactions to CSV', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 09:56:49'),
(18, 3, 'Profile Update', 'User updated display name or email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 10:39:17'),
(19, 3, 'Password Change', 'User changed their password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 11:02:54'),
(20, 3, 'Profile Update', 'User updated display name or email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 11:10:19'),
(21, 3, 'Logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-14 11:10:55'),
(22, 3, 'Logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 11:40:45'),
(23, 3, 'Login', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-14 11:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `setting_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'currency', 'EUR', '2026-03-10 01:36:59', '2026-03-10 08:19:08'),
(9, 1, 'goal_reminders', 'true', '2026-03-10 08:46:31', '2026-03-10 08:46:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_accounts_user` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_logs_admin` (`admin_id`),
  ADD KEY `idx_admin_logs_created` (`created_at`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_blog_posts_status_published_at` (`status`,`published_at`),
  ADD KEY `idx_blog_posts_slug` (`slug`),
  ADD KEY `idx_blog_posts_tags` (`tags`(191));

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_budget_per_user_period` (`user_id`,`period`,`start_date`),
  ADD KEY `idx_budgets_user_period` (`user_id`,`period`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_category_per_user` (`user_id`,`name`),
  ADD KEY `idx_categories_user` (`user_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `from_currency` (`from_currency`,`to_currency`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_goals_user` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_system_settings_key` (`setting_key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transactions_user_date` (`user_id`,`date`),
  ADD KEY `idx_transactions_category` (`category_id`),
  ADD KEY `fk_transactions_account` (`account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_setting_per_user` (`user_id`,`setting_key`),
  ADD KEY `idx_settings_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD CONSTRAINT `recurring_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_transactions_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recurring_transactions_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
