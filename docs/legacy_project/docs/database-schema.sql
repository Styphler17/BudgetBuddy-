-- BudgetBuddy Database Schema
-- Run this script in MySQL Workbench to create the database

CREATE DATABASE IF NOT EXISTS budgetbuddy;
USE budgetbuddy;

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin', 'admin', 'moderator') DEFAULT 'admin',
  is_active BOOLEAN DEFAULT TRUE,
  last_login TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Seed temporary admin account (update these credentials after first login)
INSERT INTO admins (email, name, password_hash, role, is_active)
VALUES ('temp.admin@budgetbuddy.com', 'Temporary Admin', 'TempAdmin!123', 'admin', TRUE)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password_hash = VALUES(password_hash),
  role = VALUES(role),
  is_active = VALUES(is_active);

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  currency VARCHAR(10) DEFAULT 'USD',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  emoji VARCHAR(10),
  color VARCHAR(20) DEFAULT '#3b82f6',
  budget DECIMAL(10,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_category_per_user (user_id, name)
);

-- Transactions table
CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  category_id INT,
  amount DECIMAL(10,2) NOT NULL,
  description TEXT,
  type ENUM('income', 'expense') NOT NULL,
  date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Budgets table
CREATE TABLE IF NOT EXISTS budgets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  period ENUM('daily', 'weekly', 'monthly', 'yearly') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_budget_per_user_period (user_id, period, start_date)
);

-- Goals table
CREATE TABLE IF NOT EXISTS goals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  target_amount DECIMAL(10,2) NOT NULL,
  current_amount DECIMAL(10,2) DEFAULT 0,
  deadline DATE,
  category_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Accounts table
CREATE TABLE IF NOT EXISTS accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  type ENUM('checking', 'savings', 'credit', 'investment') NOT NULL,
  balance DECIMAL(10,2) DEFAULT 0,
  currency VARCHAR(10) DEFAULT 'USD',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table
CREATE TABLE IF NOT EXISTS user_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  setting_key VARCHAR(255) NOT NULL,
  setting_value TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_setting_per_user (user_id, setting_key)
);

-- Admin logs table
CREATE TABLE IF NOT EXISTS admin_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  action VARCHAR(255) NOT NULL,
  target_type ENUM('user', 'category', 'transaction', 'system') NOT NULL,
  target_id INT,
  details TEXT,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

-- System settings table
CREATE TABLE IF NOT EXISTS system_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(255) UNIQUE NOT NULL,
  setting_value TEXT,
  setting_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt TEXT,
  cover_image_url TEXT,
  cover_image_alt VARCHAR(255),
  status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
  content LONGTEXT,
  tags TEXT,
  meta_title VARCHAR(255),
  meta_description TEXT,
  meta_keywords TEXT,
  reading_time INT DEFAULT 0,
  feature_embed_url TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  published_at TIMESTAMP NULL,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

-- Sample data (optional - remove in production)
-- INSERT INTO users (email, name, password_hash) VALUES ('demo@example.com', 'Demo User', '$2b$10$example.hash.here');

-- Indexes for better performance
CREATE INDEX idx_transactions_user_date ON transactions(user_id, date);
CREATE INDEX idx_transactions_category ON transactions(category_id);
CREATE INDEX idx_categories_user ON categories(user_id);
CREATE INDEX idx_budgets_user_period ON budgets(user_id, period);
CREATE INDEX idx_goals_user ON goals(user_id);
CREATE INDEX idx_accounts_user ON accounts(user_id);
CREATE INDEX idx_settings_user ON user_settings(user_id);
CREATE INDEX idx_admin_logs_admin ON admin_logs(admin_id);
CREATE INDEX idx_admin_logs_created ON admin_logs(created_at);
CREATE INDEX idx_system_settings_key ON system_settings(setting_key);
CREATE INDEX idx_blog_posts_status_published_at ON blog_posts(status, published_at);
CREATE INDEX idx_blog_posts_slug ON blog_posts(slug);
CREATE INDEX idx_blog_posts_tags ON blog_posts(tags(191));

-- Seed optimized blog posts for launch
INSERT INTO blog_posts (
  admin_id,
  title,
  slug,
  excerpt,
  cover_image_url,
  cover_image_alt,
  status,
  content,
  tags,
  meta_title,
  meta_description,
  meta_keywords,
  reading_time,
  feature_embed_url,
  published_at
)
VALUES
  (
    1,
    'Zero-Based Budget Blueprint for 2025',
    'zero-based-budget-blueprint-2025',
    'Give every dollar a job with a zero-based plan that keeps cash flow predictable and goals funded.',
    'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1400&q=80',
    'Table with laptop, budgeting journal, and coffee cup',
    'published',
    '[{"type":"heading","level":2,"text":"Kickstart Your Zero-Based Budget"},{"type":"paragraph","text":"BudgetBuddy helps you route every incoming dollar toward a precise job so nothing leaks through the cracks. Start by capturing your true monthly income, then allocate funds into must-have expenses, savings buckets, and intentional splurge categories."},{"type":"list","items":["Log baseline expenses for the last 90 days to set realistic caps","Direct your paycheck into BudgetBuddy''s envelope automation","Create guardrails with category alerts before overspending happens","Schedule a 30-minute monthly reset to reconcile and rebalance"]},{"type":"paragraph","text":"Once the core framework is live, use BudgetBuddy''s scenario planner to test what-if situations. You can simulate moving rent, increasing side-income, or accelerating a savings goal and instantly see downstream impact."}]',
    'budgeting,cash flow,planning',
    'Zero-Based Budget Blueprint | BudgetBuddy Blog',
    'Follow this zero-based budgeting workflow to align income, expenses, and savings inside BudgetBuddy.',
    'budgeting, zero-based budget, cash flow, planning',
    7,
    'https://www.youtube.com/watch?v=z2X2HaTvkl8',
    DATE_SUB(NOW(), INTERVAL 28 DAY)
  ),
  (
    1,
    'Automate Your Cash Flow in Under an Hour',
    'automate-your-cash-flow-in-under-an-hour',
    'Build a dependable money system with smart automations that keep bills current and savings growing.',
    'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1400&q=80',
    'Smartphone banking app showing recurring transfers',
    'published',
    '[{"type":"heading","level":2,"text":"Build a Dependable Automation Stack"},{"type":"paragraph","text":"Set recurring transfers once, then let BudgetBuddy push money to bills, sinking funds, and investments with zero manual effort. Automation protects your goals from procrastination and makes saving the path of least resistance."},{"type":"list","items":["Group fixed expenses by due date and pay them two days early","Route 5% of each paycheck into an emergency buffer before spending","Create quarterly sinking funds for insurance premiums and annual software renewals","Turn on smart nudges so BudgetBuddy warns you before balances dip low"]},{"type":"quote","text":"Simple automation beats heroic willpower every time.","caption":"BudgetBuddy success coach"}]',
    'automation,cash flow,habits',
    'Cash Flow Automation Checklist | BudgetBuddy Blog',
    'Use this automation checklist to keep every bill current while your savings climb in the background.',
    'cash flow automation, financial habits, budgeting checklist',
    6,
    NULL,
    DATE_SUB(NOW(), INTERVAL 18 DAY)
  ),
  (
    1,
    'Smart Savings Playbook: From Micro Wins to Mega Goals',
    'smart-savings-playbook-micro-to-mega',
    'Stack small savings wins into major milestones using BudgetBuddy''s goal templates and progress analytics.',
    'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1400&q=80',
    'Jar filled with labelled savings envelopes',
    'published',
    '[{"type":"heading","level":2,"text":"Stack Micro Wins Into Long-Term Momentum"},{"type":"paragraph","text":"Break intimidating goals into micro-milestones, then celebrate each win to keep motivation high. BudgetBuddy''s goal templates make it effortless to see how today''s deposits accelerate tomorrow''s dreams."},{"type":"list","items":["Give every goal a clear why, target amount, and deadline","Automate weekly micro-deposits so progress never stalls","Visualise momentum with BudgetBuddy''s trajectory charts","Bundle accountability by sharing dashboards with partners"]},{"type":"paragraph","text":"As each milestone locks in, reallocate the freed-up cash toward the next priority. This compounding effect keeps your savings engine accelerating instead of starting from zero every January."}]',
    'savings,goals,motivation',
    'Smart Savings Playbook | BudgetBuddy Blog',
    'Learn how to convert bite-sized deposits into game-changing results with BudgetBuddy goal automation.',
    'savings goals, micro-savings, financial motivation',
    8,
    'https://www.youtube.com/watch?v=3X9lT9Y4QWc',
    DATE_SUB(NOW(), INTERVAL 9 DAY)
  )
ON DUPLICATE KEY UPDATE
  title = VALUES(title),
  excerpt = VALUES(excerpt),
  cover_image_url = VALUES(cover_image_url),
  cover_image_alt = VALUES(cover_image_alt),
  status = VALUES(status),
  content = VALUES(content),
  tags = VALUES(tags),
  meta_title = VALUES(meta_title),
  meta_description = VALUES(meta_description),
  meta_keywords = VALUES(meta_keywords),
  reading_time = VALUES(reading_time),
  feature_embed_url = VALUES(feature_embed_url),
  published_at = VALUES(published_at);
