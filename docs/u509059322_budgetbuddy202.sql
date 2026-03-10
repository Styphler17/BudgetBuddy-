-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 28, 2025 at 05:42 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u509059322_budgetbuddy202`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('checking','savings','credit','investment') NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','moderator') DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `name`, `password_hash`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'temp.admin@budgetbuddy.com', 'Temporary Admin', 'TempAdmin!123', 'admin', 1, NULL, '2025-10-28 02:38:49', '2025-10-28 02:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `target_type` enum('user','category','transaction','system') NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `cover_image_url` text DEFAULT NULL,
  `cover_image_alt` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `content` longtext DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `reading_time` int(11) DEFAULT 0,
  `feature_embed_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `admin_id`, `title`, `slug`, `excerpt`, `cover_image_url`, `cover_image_alt`, `status`, `content`, `tags`, `meta_title`, `meta_description`, `meta_keywords`, `reading_time`, `feature_embed_url`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 1, 'Zero-Based Budget Blueprint for 2025', 'zero-based-budget-blueprint-2025', 'Give every dollar a job with a zero-based plan that keeps cash flow predictable and goals funded.', 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1400&q=80', 'Table with laptop, budgeting journal, and coffee cup', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Kickstart Your Zero-Based Budget\"},{\"type\":\"paragraph\",\"text\":\"BudgetBuddy helps you route every incoming dollar toward a precise job so nothing leaks through the cracks. Start by capturing your true monthly income, then allocate funds into must-have expenses, savings buckets, and intentional splurge categories.\"},{\"type\":\"list\",\"items\":[\"Log baseline expenses for the last 90 days to set realistic caps\",\"Direct your paycheck into BudgetBuddy\'s envelope automation\",\"Create guardrails with category alerts before overspending happens\",\"Schedule a 30-minute monthly reset to reconcile and rebalance\"]},{\"type\":\"paragraph\",\"text\":\"Once the core framework is live, use BudgetBuddy\'s scenario planner to test what-if situations. You can simulate moving rent, increasing side-income, or accelerating a savings goal and instantly see downstream impact.\"}]', 'budgeting,cash flow,planning', 'Zero-Based Budget Blueprint | BudgetBuddy Blog', 'Follow this zero-based budgeting workflow to align income, expenses, and savings inside BudgetBuddy.', 'budgeting, zero-based budget, cash flow, planning', 7, 'https://www.youtube.com/watch?v=z2X2HaTvkl8', '2025-10-28 02:38:50', '2025-10-28 10:54:55', '2025-10-28 10:54:54'),
(2, 1, 'Automate Your Cash Flow in Under an Hour', 'automate-your-cash-flow-in-under-an-hour', 'Build a dependable money system with smart automations that keep bills current and savings growing.', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1400&q=80', 'Smartphone banking app showing recurring transfers', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Build a Dependable Automation Stack\"},{\"type\":\"paragraph\",\"text\":\"Set recurring transfers once, then let BudgetBuddy push money to bills, sinking funds, and investments with zero manual effort. Automation protects your goals from procrastination and makes saving the path of least resistance.\"},{\"type\":\"list\",\"items\":[\"Group fixed expenses by due date and pay them two days early\",\"Route 5% of each paycheck into an emergency buffer before spending\",\"Create quarterly sinking funds for insurance premiums and annual software renewals\",\"Turn on smart nudges so BudgetBuddy warns you before balances dip low\"]},{\"type\":\"quote\",\"text\":\"Simple automation beats heroic willpower every time.\",\"caption\":\"BudgetBuddy success coach\"}]', 'automation,cash flow,habits', 'Cash Flow Automation Checklist | BudgetBuddy Blog', 'Use this automation checklist to keep every bill current while your savings climb in the background.', 'cash flow automation, financial habits, budgeting checklist', 6, NULL, '2025-10-28 02:38:50', '2025-10-28 10:54:58', '2025-10-28 10:54:57'),
(3, 1, 'Smart Savings Playbook: From Micro Wins to Mega Goals', 'smart-savings-playbook-micro-to-mega', 'Stack small savings wins into major milestones using BudgetBuddy\'s goal templates and progress analytics.', 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=80', 'Jar filled with labelled savings envelopes', 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Stack Micro Wins Into Long-Term Momentum\"},{\"type\":\"paragraph\",\"text\":\"Break intimidating goals into micro-milestones, then celebrate each win to keep motivation high. BudgetBuddy\'s goal templates make it effortless to see how today\'s deposits accelerate tomorrow\'s dreams.\"},{\"type\":\"list\",\"items\":[\"Give every goal a clear why, target amount, and deadline\",\"Automate weekly micro-deposits so progress never stalls\",\"Visualise momentum with BudgetBuddy\'s trajectory charts\",\"Bundle accountability by sharing dashboards with partners\"]},{\"type\":\"paragraph\",\"text\":\"As each milestone locks in, reallocate the freed-up cash toward the next priority. This compounding effect keeps your savings engine accelerating instead of starting from zero every January.\"}]', 'savings,goals,motivation', 'Smart Savings Playbook | BudgetBuddy Blog', 'Learn how to convert bite-sized deposits into game-changing results with BudgetBuddy goal automation.', 'savings goals, micro-savings, financial motivation', 8, 'https://www.youtube.com/watch?v=3X9lT9Y4QWc', '2025-10-28 02:38:50', '2025-10-28 02:38:50', '2025-10-19 02:38:50'),
(4, 1, 'Mastering Your Monthly Budget: A Complete Guide', 'mastering-your-monthly-budget-a-complete-guide', NULL, NULL, NULL, 'published', '[{\"type\":\"heading\",\"level\":2,\"text\":\"Getting Started with Budget Planning\"},{\"type\":\"paragraph\",\"text\":\"Creating a monthly budget is one of the most important steps toward financial success. By tracking your income and expenses, you can make informed decisions about your money and work toward your financial goals. Start by listing all your sources of income, then categorize your expenses into fixed and variable costs. This will help you identify areas where you can save and allocate funds more effectively.\"}]', '', NULL, NULL, '', 1, NULL, '2025-10-28 10:56:09', '2025-10-28 10:56:09', '2025-10-28 10:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `period` enum('daily','weekly','monthly','yearly') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `emoji` varchar(10) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#3b82f6',
  `budget` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) DEFAULT 0.00,
  `deadline` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('income','expense') NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `is_active` tinyint(1) DEFAULT 1,
  `email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_goals_user` (`user_id`);

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
  ADD KEY `idx_transactions_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
