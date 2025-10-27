import type { RowDataPacket } from 'mysql2/promise';

// Mock data storage
const mockUsers: Record<string, unknown>[] = [];
const mockAdmins: Record<string, unknown>[] = [];
const mockCategories: Record<string, unknown>[] = [];
const mockTransactions: Record<string, unknown>[] = [];
const mockBudgets: Record<string, unknown>[] = [];
const mockGoals: Record<string, unknown>[] = [];
const mockAccounts: Record<string, unknown>[] = [];
const mockUserSettings: Record<string, unknown>[] = [];
const mockAdminLogs: Record<string, unknown>[] = [];
const mockSystemSettings: Record<string, unknown>[] = [];

// Mock database functions
const mockQuery = async (sql: string, params: unknown[] = []): Promise<unknown[]> => {
  // Parse SQL to determine operation
  const sqlLower = sql.toLowerCase();

  if (sqlLower.includes('insert into users')) {
    const user = {
      id: mockUsers.length + 1,
      email: params[0],
      name: params[1],
      password_hash: params[2],
      currency: 'USD',
      is_active: true,
      email_verified: false,
      created_at: new Date().toISOString()
    };
    mockUsers.push(user);
    return [{ insertId: user.id }];
  }

  if (sqlLower.includes('select * from users where email = ?')) {
    return mockUsers.filter(u => u.email === params[0]);
  }

  if (sqlLower.includes('update users set')) {
    const userId = params[params.length - 1];
    const user = mockUsers.find(u => u.id === userId);
    if (user) {
      if (sql.includes('name = ?')) user.name = params[0];
      if (sql.includes('currency = ?')) user.currency = params[1] || params[0];
    }
    return [];
  }

  if (sqlLower.includes('insert into categories')) {
    const category = {
      id: mockCategories.length + 1,
      user_id: params[0],
      name: params[1],
      emoji: params[2],
      budget: params[3],
      created_at: new Date().toISOString()
    };
    mockCategories.push(category);
    return [{ insertId: category.id }];
  }

  if (sqlLower.includes('select * from categories where user_id = ?')) {
    return mockCategories.filter(c => c.user_id === params[0]);
  }

  if (sqlLower.includes('update categories set')) {
    const categoryId = params[params.length - 1];
    const category = mockCategories.find(c => c.id === categoryId);
    if (category) {
      if (sql.includes('name = ?')) category.name = params[0];
      if (sql.includes('emoji = ?')) category.emoji = params[1];
      if (sql.includes('budget = ?')) category.budget = params[2];
    }
    return [];
  }

  if (sqlLower.includes('delete from categories where id = ?')) {
    const filteredCategories = mockCategories.filter(c => c.id !== params[0]);
    mockCategories.length = 0;
    mockCategories.push(...filteredCategories);
    return [];
  }

  if (sqlLower.includes('select coalesce(sum(amount), 0) as spent')) {
    const spent = mockTransactions
      .filter(t => t.user_id === params[0] && t.category_id === params[1] && t.type === 'expense')
      .reduce((sum, t) => sum + parseFloat(t.amount), 0);
    return [{ spent }];
  }

  if (sqlLower.includes('insert into transactions')) {
    const transaction = {
      id: mockTransactions.length + 1,
      user_id: params[0],
      category_id: params[1],
      amount: params[2],
      description: params[3],
      type: params[4],
      date: params[5],
      created_at: new Date().toISOString()
    };
    mockTransactions.push(transaction);
    return [{ insertId: transaction.id }];
  }

  if (sqlLower.includes('select t.*, c.name as category_name')) {
    const userTransactions = mockTransactions
      .filter(t => t.user_id === params[0])
      .map(t => {
        const category = mockCategories.find(c => c.id === t.category_id);
        return {
          ...t,
          category_name: category?.name || null,
          category_emoji: category?.emoji || null
        };
      });
    return userTransactions;
  }

  if (sqlLower.includes('update transactions set')) {
    const transactionId = params[params.length - 1];
    const transaction = mockTransactions.find(t => t.id === transactionId);
    if (transaction) {
      if (sql.includes('category_id = ?')) transaction.category_id = params[0];
      if (sql.includes('amount = ?')) transaction.amount = params[1];
      if (sql.includes('description = ?')) transaction.description = params[2];
      if (sql.includes('type = ?')) transaction.type = params[3];
      if (sql.includes('date = ?')) transaction.date = params[4];
    }
    return [];
  }

  if (sqlLower.includes('delete from transactions where id = ?')) {
    const filteredTransactions = mockTransactions.filter(t => t.id !== params[0]);
    mockTransactions.length = 0;
    mockTransactions.push(...filteredTransactions);
    return [];
  }

  if (sqlLower.includes('insert into budgets')) {
    const budget = {
      id: mockBudgets.length + 1,
      user_id: params[0],
      period: params[1],
      amount: params[2],
      start_date: params[3],
      end_date: params[4],
      created_at: new Date().toISOString()
    };
    mockBudgets.push(budget);
    return [{ insertId: budget.id }];
  }

  if (sqlLower.includes('select * from budgets where user_id = ? and period = ?')) {
    return mockBudgets.filter(b => b.user_id === params[0] && b.period === params[1]);
  }

  if (sqlLower.includes('update budgets set')) {
    const budgetId = params[params.length - 1];
    const budget = mockBudgets.find(b => b.id === budgetId);
    if (budget) {
      if (sql.includes('amount = ?')) budget.amount = params[0];
      if (sql.includes('start_date = ?')) budget.start_date = params[1];
      if (sql.includes('end_date = ?')) budget.end_date = params[2];
    }
    return [];
  }

  if (sqlLower.includes('insert into goals')) {
    const goal = {
      id: mockGoals.length + 1,
      user_id: params[0],
      name: params[1],
      target_amount: params[2],
      current_amount: params[3] || 0,
      deadline: params[4],
      category_id: params[5],
      created_at: new Date().toISOString()
    };
    mockGoals.push(goal);
    return [{ insertId: goal.id }];
  }

  if (sqlLower.includes('select g.*, c.name as category_name')) {
    return mockGoals
      .filter(g => g.user_id === params[0])
      .map(g => {
        const category = mockCategories.find(c => c.id === g.category_id);
        return {
          ...g,
          category_name: category?.name || null,
          category_emoji: category?.emoji || null
        };
      });
  }

  if (sqlLower.includes('update goals set')) {
    const goalId = params[params.length - 1];
    const goal = mockGoals.find(g => g.id === goalId);
    if (goal) {
      if (sql.includes('name = ?')) goal.name = params[0];
      if (sql.includes('target_amount = ?')) goal.target_amount = params[1];
      if (sql.includes('current_amount = ?')) goal.current_amount = params[2];
      if (sql.includes('deadline = ?')) goal.deadline = params[3];
      if (sql.includes('category_id = ?')) goal.category_id = params[4];
    }
    return [];
  }

  if (sqlLower.includes('delete from goals where id = ?')) {
    const filteredGoals = mockGoals.filter(g => g.id !== params[0]);
    mockGoals.length = 0;
    mockGoals.push(...filteredGoals);
    return [];
  }

  if (sqlLower.includes('insert into accounts')) {
    const account = {
      id: mockAccounts.length + 1,
      user_id: params[0],
      name: params[1],
      type: params[2],
      balance: params[3] || 0,
      currency: params[4] || 'USD',
      created_at: new Date().toISOString()
    };
    mockAccounts.push(account);
    return [{ insertId: account.id }];
  }

  if (sqlLower.includes('select * from accounts where user_id = ?')) {
    return mockAccounts.filter(a => a.user_id === params[0]);
  }

  if (sqlLower.includes('update accounts set')) {
    const accountId = params[params.length - 1];
    const account = mockAccounts.find(a => a.id === accountId);
    if (account) {
      if (sql.includes('name = ?')) account.name = params[0];
      if (sql.includes('type = ?')) account.type = params[1];
      if (sql.includes('balance = ?')) account.balance = params[2];
      if (sql.includes('currency = ?')) account.currency = params[3];
    }
    return [];
  }

  if (sqlLower.includes('delete from accounts where id = ?')) {
    const filteredAccounts = mockAccounts.filter(a => a.id !== params[0]);
    mockAccounts.length = 0;
    mockAccounts.push(...filteredAccounts);
    return [];
  }

  if (sqlLower.includes('select setting_value from user_settings')) {
    const setting = mockUserSettings.find(s => s.user_id === params[0] && s.setting_key === params[1]);
    return setting ? [{ setting_value: setting.setting_value }] : [];
  }

  if (sqlLower.includes('insert into user_settings')) {
    const existingIndex = mockUserSettings.findIndex(s => s.user_id === params[0] && s.setting_key === params[1]);
    if (existingIndex >= 0) {
      mockUserSettings[existingIndex].setting_value = params[2];
    } else {
      mockUserSettings.push({
        id: mockUserSettings.length + 1,
        user_id: params[0],
        setting_key: params[1],
        setting_value: params[2],
        created_at: new Date().toISOString()
      });
    }
    return [];
  }

  if (sqlLower.includes('select setting_key, setting_value from user_settings')) {
    return mockUserSettings.filter(s => s.user_id === params[0]);
  }

  // Admin API mocks
  if (sqlLower.includes('insert into admins')) {
    const admin = {
      id: mockAdmins.length + 1,
      email: params[0],
      name: params[1],
      password_hash: params[2],
      role: params[3] || 'admin',
      is_active: true,
      last_login: null,
      created_at: new Date().toISOString()
    };
    mockAdmins.push(admin);
    return [{ insertId: admin.id }];
  }

  if (sqlLower.includes('select * from admins where email = ?')) {
    return mockAdmins.filter(a => a.email === params[0]);
  }

  if (sqlLower.includes('select id, email, name, role, is_active, last_login, created_at from admins')) {
    return mockAdmins;
  }

  if (sqlLower.includes('update admins set last_login')) {
    const admin = mockAdmins.find(a => a.id === params[0]);
    if (admin) {
      admin.last_login = new Date().toISOString();
    }
    return [];
  }

  if (sqlLower.includes('select id, email, name, currency, is_active, email_verified, created_at from users')) {
    return mockUsers.map(u => ({
      id: u.id,
      email: u.email,
      name: u.name,
      currency: u.currency,
      is_active: u.is_active,
      email_verified: u.email_verified,
      created_at: u.created_at
    }));
  }

  if (sqlLower.includes('select count(*) as count from')) {
    if (sql.includes('from users')) return [{ count: mockUsers.length }];
    if (sql.includes('from admins')) return [{ count: mockAdmins.length }];
    if (sql.includes('from transactions')) return [{ count: mockTransactions.length }];
    if (sql.includes('from categories')) return [{ count: mockCategories.length }];
    if (sql.includes('from goals')) return [{ count: mockGoals.length }];
    if (sql.includes('from accounts')) return [{ count: mockAccounts.length }];
  }

  if (sqlLower.includes('select al.*, a.name as admin_name')) {
    return mockAdminLogs.map(log => {
      const admin = mockAdmins.find(a => a.id === log.admin_id);
      return {
        ...log,
        admin_name: admin?.name || 'Unknown',
        admin_email: admin?.email || 'Unknown'
      };
    });
  }

  if (sqlLower.includes('insert into admin_logs')) {
    const log = {
      id: mockAdminLogs.length + 1,
      admin_id: params[0],
      action: params[1],
      target_type: params[2],
      target_id: params[3],
      details: params[4],
      ip_address: params[5],
      created_at: new Date().toISOString()
    };
    mockAdminLogs.push(log);
    return [{ insertId: log.id }];
  }

  if (sqlLower.includes('select setting_value, setting_type from system_settings')) {
    const setting = mockSystemSettings.find(s => s.setting_key === params[0]);
    return setting ? [{ setting_value: setting.setting_value, setting_type: setting.setting_type }] : [];
  }

  if (sqlLower.includes('insert into system_settings')) {
    const existingIndex = mockSystemSettings.findIndex(s => s.setting_key === params[0]);
    if (existingIndex >= 0) {
      mockSystemSettings[existingIndex].setting_value = params[1];
      mockSystemSettings[existingIndex].setting_type = params[2];
      mockSystemSettings[existingIndex].description = params[3];
    } else {
      mockSystemSettings.push({
        id: mockSystemSettings.length + 1,
        setting_key: params[0],
        setting_value: params[1],
        setting_type: params[2],
        description: params[3],
        created_at: new Date().toISOString()
      });
    }
    return [];
  }

  if (sqlLower.includes('select * from system_settings')) {
    return mockSystemSettings;
  }

  if (sqlLower.includes('delete from system_settings')) {
    const filteredSystemSettings = mockSystemSettings.filter(s => s.setting_key !== params[0]);
    mockSystemSettings.length = 0;
    mockSystemSettings.push(...filteredSystemSettings);
    return [];
  }

  // Default return for unhandled queries
  return [];
};

// Replace the database import with mock implementation
// import { query } from './database';
const query = mockQuery;

// User API
export const userAPI = {
  create: async (userData: { email: string; name: string; passwordHash: string }) => {
    const sql = 'INSERT INTO users (email, name, password_hash) VALUES (?, ?, ?)';
    const result = await query(sql, [userData.email, userData.name, userData.passwordHash]);
    return result;
  },

  findById: async (id: number) => {
    const sql = 'SELECT * FROM users WHERE id = ?';
    const result = await query(sql, [id]);
    return result[0];
  },

  findByEmail: async (email: string) => {
    const sql = 'SELECT * FROM users WHERE email = ?';
    const result = await query(sql, [email]);
    return result[0];
  },

  update: async (id: number, userData: Partial<{ name: string; currency: string; first_name: string; last_name: string; email: string; password_hash: string }>) => {
    const fields = [];
    const values = [];

    if (userData.name !== undefined) {
      fields.push('name = ?');
      values.push(userData.name);
    }
    if (userData.currency !== undefined) {
      fields.push('currency = ?');
      values.push(userData.currency);
    }
    if (userData.first_name !== undefined) {
      fields.push('first_name = ?');
      values.push(userData.first_name);
    }
    if (userData.last_name !== undefined) {
      fields.push('last_name = ?');
      values.push(userData.last_name);
    }
    if (userData.email !== undefined) {
      fields.push('email = ?');
      values.push(userData.email);
    }
    if (userData.password_hash !== undefined) {
      fields.push('password_hash = ?');
      values.push(userData.password_hash);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE users SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  }
};

// Category API
export const categoryAPI = {
  create: async (categoryData: { userId: number; name: string; emoji?: string; budget: number }) => {
    const sql = 'INSERT INTO categories (user_id, name, emoji, budget) VALUES (?, ?, ?, ?)';
    const result = await query(sql, [categoryData.userId, categoryData.name, categoryData.emoji, categoryData.budget]);
    return result;
  },

  findByUserId: async (userId: number) => {
    const sql = 'SELECT * FROM categories WHERE user_id = ? ORDER BY created_at DESC';
    return await query(sql, [userId]);
  },

  update: async (id: number, categoryData: Partial<{ name: string; emoji: string; budget: number }>) => {
    const fields = [];
    const values = [];

    if (categoryData.name !== undefined) {
      fields.push('name = ?');
      values.push(categoryData.name);
    }
    if (categoryData.emoji !== undefined) {
      fields.push('emoji = ?');
      values.push(categoryData.emoji);
    }
    if (categoryData.budget !== undefined) {
      fields.push('budget = ?');
      values.push(categoryData.budget);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE categories SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  delete: async (id: number) => {
    const sql = 'DELETE FROM categories WHERE id = ?';
    return await query(sql, [id]);
  },

  getSpendingByCategory: async (userId: number, categoryId: number) => {
    const sql = `
      SELECT COALESCE(SUM(amount), 0) as spent
      FROM transactions
      WHERE user_id = ? AND category_id = ? AND type = 'expense'
    `;
    const result = await query(sql, [userId, categoryId]);
    return result[0]?.spent || 0;
  }
};

// Transaction API
export const transactionAPI = {
  create: async (transactionData: {
    userId: number;
    categoryId?: number;
    amount: number;
    description?: string;
    type: 'income' | 'expense';
    date: string;
  }) => {
    const sql = 'INSERT INTO transactions (user_id, category_id, amount, description, type, date) VALUES (?, ?, ?, ?, ?, ?)';
    const result = await query(sql, [
      transactionData.userId,
      transactionData.categoryId,
      transactionData.amount,
      transactionData.description,
      transactionData.type,
      transactionData.date
    ]);
    return result;
  },

  findByUserId: async (userId: number, limit?: number) => {
    let sql = 'SELECT t.*, c.name as category_name, c.emoji as category_emoji FROM transactions t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? ORDER BY t.date DESC, t.created_at DESC';
    const params = [userId];

    if (limit) {
      sql += ' LIMIT ?';
      params.push(limit);
    }

    return await query(sql, params);
  },

  update: async (id: number, transactionData: Partial<{
    categoryId: number;
    amount: number;
    description: string;
    type: 'income' | 'expense';
    date: string;
  }>) => {
    const fields = [];
    const values = [];

    if (transactionData.categoryId !== undefined) {
      fields.push('category_id = ?');
      values.push(transactionData.categoryId);
    }
    if (transactionData.amount !== undefined) {
      fields.push('amount = ?');
      values.push(transactionData.amount);
    }
    if (transactionData.description !== undefined) {
      fields.push('description = ?');
      values.push(transactionData.description);
    }
    if (transactionData.type !== undefined) {
      fields.push('type = ?');
      values.push(transactionData.type);
    }
    if (transactionData.date !== undefined) {
      fields.push('date = ?');
      values.push(transactionData.date);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE transactions SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  delete: async (id: number) => {
    const sql = 'DELETE FROM transactions WHERE id = ?';
    return await query(sql, [id]);
  }
};

// Budget API
export const budgetAPI = {
  create: async (budgetData: {
    userId: number;
    period: 'daily' | 'weekly' | 'monthly' | 'yearly';
    amount: number;
    startDate: string;
    endDate: string;
  }) => {
    const sql = 'INSERT INTO budgets (user_id, period, amount, start_date, end_date) VALUES (?, ?, ?, ?, ?)';
    const result = await query(sql, [
      budgetData.userId,
      budgetData.period,
      budgetData.amount,
      budgetData.startDate,
      budgetData.endDate
    ]);
    return result;
  },

  findByUserIdAndPeriod: async (userId: number, period: string) => {
    const sql = 'SELECT * FROM budgets WHERE user_id = ? AND period = ? ORDER BY start_date DESC LIMIT 1';
    const result = await query(sql, [userId, period]);
    return result[0];
  },

  update: async (id: number, budgetData: Partial<{ amount: number; startDate: string; endDate: string }>) => {
    const fields = [];
    const values = [];

    if (budgetData.amount !== undefined) {
      fields.push('amount = ?');
      values.push(budgetData.amount);
    }
    if (budgetData.startDate !== undefined) {
      fields.push('start_date = ?');
      values.push(budgetData.startDate);
    }
    if (budgetData.endDate !== undefined) {
      fields.push('end_date = ?');
      values.push(budgetData.endDate);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE budgets SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  }
};

// Goal API
export const goalAPI = {
  create: async (goalData: {
    userId: number;
    name: string;
    targetAmount: number;
    currentAmount?: number;
    deadline?: string;
    categoryId?: number;
  }) => {
    const sql = 'INSERT INTO goals (user_id, name, target_amount, current_amount, deadline, category_id) VALUES (?, ?, ?, ?, ?, ?)';
    const result = await query(sql, [
      goalData.userId,
      goalData.name,
      goalData.targetAmount,
      goalData.currentAmount || 0,
      goalData.deadline,
      goalData.categoryId
    ]);
    return result;
  },

  findByUserId: async (userId: number) => {
    const sql = 'SELECT g.*, c.name as category_name, c.emoji as category_emoji FROM goals g LEFT JOIN categories c ON g.category_id = c.id WHERE g.user_id = ? ORDER BY g.created_at DESC';
    return await query(sql, [userId]);
  },

  update: async (id: number, goalData: Partial<{
    name: string;
    targetAmount: number;
    currentAmount: number;
    deadline: string;
    categoryId: number;
  }>) => {
    const fields = [];
    const values = [];

    if (goalData.name !== undefined) {
      fields.push('name = ?');
      values.push(goalData.name);
    }
    if (goalData.targetAmount !== undefined) {
      fields.push('target_amount = ?');
      values.push(goalData.targetAmount);
    }
    if (goalData.currentAmount !== undefined) {
      fields.push('current_amount = ?');
      values.push(goalData.currentAmount);
    }
    if (goalData.deadline !== undefined) {
      fields.push('deadline = ?');
      values.push(goalData.deadline);
    }
    if (goalData.categoryId !== undefined) {
      fields.push('category_id = ?');
      values.push(goalData.categoryId);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE goals SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  delete: async (id: number) => {
    const sql = 'DELETE FROM goals WHERE id = ?';
    return await query(sql, [id]);
  }
};

// Account API
export const accountAPI = {
  create: async (accountData: {
    userId: number;
    name: string;
    type: 'checking' | 'savings' | 'credit' | 'investment';
    balance?: number;
    currency?: string;
  }) => {
    const sql = 'INSERT INTO accounts (user_id, name, type, balance, currency) VALUES (?, ?, ?, ?, ?)';
    const result = await query(sql, [
      accountData.userId,
      accountData.name,
      accountData.type,
      accountData.balance || 0,
      accountData.currency || 'USD'
    ]);
    return result;
  },

  findByUserId: async (userId: number) => {
    const sql = 'SELECT * FROM accounts WHERE user_id = ? ORDER BY created_at DESC';
    return await query(sql, [userId]);
  },

  update: async (id: number, accountData: Partial<{
    name: string;
    type: 'checking' | 'savings' | 'credit' | 'investment';
    balance: number;
    currency: string;
  }>) => {
    const fields = [];
    const values = [];

    if (accountData.name !== undefined) {
      fields.push('name = ?');
      values.push(accountData.name);
    }
    if (accountData.type !== undefined) {
      fields.push('type = ?');
      values.push(accountData.type);
    }
    if (accountData.balance !== undefined) {
      fields.push('balance = ?');
      values.push(accountData.balance);
    }
    if (accountData.currency !== undefined) {
      fields.push('currency = ?');
      values.push(accountData.currency);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE accounts SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  delete: async (id: number) => {
    const sql = 'DELETE FROM accounts WHERE id = ?';
    return await query(sql, [id]);
  }
};

// Settings API
export const settingsAPI = {
  get: async (userId: number, key: string) => {
    const sql = 'SELECT setting_value FROM user_settings WHERE user_id = ? AND setting_key = ?';
    const result = await query(sql, [userId, key]);
    return result[0]?.setting_value;
  },

  set: async (userId: number, key: string, value: string) => {
    const sql = `
      INSERT INTO user_settings (user_id, setting_key, setting_value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
    `;
    return await query(sql, [userId, key, value]);
  },

  getAll: async (userId: number) => {
    const sql = 'SELECT setting_key, setting_value FROM user_settings WHERE user_id = ?';
    const result = await query(sql, [userId]) as RowDataPacket[];
    return result.reduce((acc: Record<string, string>, row: RowDataPacket & { setting_key: string; setting_value: string }) => {
      acc[row.setting_key] = row.setting_value;
      return acc;
    }, {});
  }
};

// Admin API
export const adminAPI = {
  create: async (adminData: { email: string; name: string; passwordHash: string; role?: 'super_admin' | 'admin' | 'moderator' }) => {
    const sql = 'INSERT INTO admins (email, name, password_hash, role) VALUES (?, ?, ?, ?)';
    const result = await query(sql, [adminData.email, adminData.name, adminData.passwordHash, adminData.role || 'admin']);
    return result;
  },

  findByEmail: async (email: string) => {
    const sql = 'SELECT * FROM admins WHERE email = ?';
    const result = await query(sql, [email]);
    return result[0];
  },

  findAll: async (limit?: number, offset?: number) => {
    let sql = 'SELECT id, email, name, role, is_active, last_login, created_at FROM admins ORDER BY created_at DESC';
    const params = [];

    if (limit) {
      sql += ' LIMIT ?';
      params.push(limit);
    }
    if (offset) {
      sql += ' OFFSET ?';
      params.push(offset);
    }

    return await query(sql, params);
  },

  update: async (id: number, adminData: Partial<{ name: string; role: string; is_active: boolean }>) => {
    const fields = [];
    const values = [];

    if (adminData.name !== undefined) {
      fields.push('name = ?');
      values.push(adminData.name);
    }
    if (adminData.role !== undefined) {
      fields.push('role = ?');
      values.push(adminData.role);
    }
    if (adminData.is_active !== undefined) {
      fields.push('is_active = ?');
      values.push(adminData.is_active);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE admins SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  updateLastLogin: async (id: number) => {
    const sql = 'UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?';
    return await query(sql, [id]);
  },

  delete: async (id: number) => {
    const sql = 'DELETE FROM admins WHERE id = ?';
    return await query(sql, [id]);
  },

  // User management for admins
  getAllUsers: async (limit?: number, offset?: number) => {
    let sql = 'SELECT id, email, name, currency, is_active, email_verified, created_at FROM users ORDER BY created_at DESC';
    const params = [];

    if (limit) {
      sql += ' LIMIT ?';
      params.push(limit);
    }
    if (offset) {
      sql += ' OFFSET ?';
      params.push(offset);
    }

    return await query(sql, params);
  },

  updateUser: async (id: number, userData: Partial<{ name: string; currency: string; is_active: boolean; email_verified: boolean }>) => {
    const fields = [];
    const values = [];

    if (userData.name !== undefined) {
      fields.push('name = ?');
      values.push(userData.name);
    }
    if (userData.currency !== undefined) {
      fields.push('currency = ?');
      values.push(userData.currency);
    }
    if (userData.is_active !== undefined) {
      fields.push('is_active = ?');
      values.push(userData.is_active);
    }
    if (userData.email_verified !== undefined) {
      fields.push('email_verified = ?');
      values.push(userData.email_verified);
    }

    if (fields.length === 0) return;

    const sql = `UPDATE users SET ${fields.join(', ')} WHERE id = ?`;
    values.push(id);
    return await query(sql, values);
  },

  deleteUser: async (id: number) => {
    const sql = 'DELETE FROM users WHERE id = ?';
    return await query(sql, [id]);
  },

  // System statistics
  getSystemStats: async () => {
    const stats = {
      totalUsers: 0,
      totalAdmins: 0,
      totalTransactions: 0,
      totalCategories: 0,
      totalGoals: 0,
      totalAccounts: 0
    };

    // Get counts
    const userCount = await query('SELECT COUNT(*) as count FROM users');
    const adminCount = await query('SELECT COUNT(*) as count FROM admins');
    const transactionCount = await query('SELECT COUNT(*) as count FROM transactions');
    const categoryCount = await query('SELECT COUNT(*) as count FROM categories');
    const goalCount = await query('SELECT COUNT(*) as count FROM goals');
    const accountCount = await query('SELECT COUNT(*) as count FROM accounts');

    stats.totalUsers = userCount[0].count;
    stats.totalAdmins = adminCount[0].count;
    stats.totalTransactions = transactionCount[0].count;
    stats.totalCategories = categoryCount[0].count;
    stats.totalGoals = goalCount[0].count;
    stats.totalAccounts = accountCount[0].count;

    return stats;
  },

  // Admin logs
  logAction: async (adminId: number, action: string, targetType: 'user' | 'category' | 'transaction' | 'system', targetId?: number, details?: string, ipAddress?: string) => {
    const sql = 'INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)';
    return await query(sql, [adminId, action, targetType, targetId, details, ipAddress]);
  },

  getLogs: async (limit?: number, offset?: number) => {
    let sql = `
      SELECT al.*, a.name as admin_name, a.email as admin_email
      FROM admin_logs al
      LEFT JOIN admins a ON al.admin_id = a.id
      ORDER BY al.created_at DESC
    `;
    const params = [];

    if (limit) {
      sql += ' LIMIT ?';
      params.push(limit);
    }
    if (offset) {
      sql += ' OFFSET ?';
      params.push(offset);
    }

    return await query(sql, params);
  }
};

// System Settings API
export const systemSettingsAPI = {
  get: async (key: string) => {
    const sql = 'SELECT setting_value, setting_type FROM system_settings WHERE setting_key = ?';
    const result = await query(sql, [key]);
    if (result[0]) {
      const { setting_value, setting_type } = result[0];
      // Parse based on type
      switch (setting_type) {
        case 'number':
          return parseFloat(setting_value);
        case 'boolean':
          return setting_value === 'true';
        case 'json':
          return JSON.parse(setting_value);
        default:
          return setting_value;
      }
    }
    return null;
  },

  set: async (key: string, value: unknown, type: 'string' | 'number' | 'boolean' | 'json' = 'string', description?: string) => {
    const stringValue = typeof value === 'object' ? JSON.stringify(value) : String(value);
    const sql = `
      INSERT INTO system_settings (setting_key, setting_value, setting_type, description)
      VALUES (?, ?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type), description = VALUES(description)
    `;
    return await query(sql, [key, stringValue, type, description]);
  },

  getAll: async () => {
    const sql = 'SELECT * FROM system_settings ORDER BY setting_key';
    const result = await query(sql) as RowDataPacket[];
    return result.map(row => ({
      ...row,
      parsed_value: (() => {
        switch (row.setting_type) {
          case 'number':
            return parseFloat(row.setting_value as string);
          case 'boolean':
            return (row.setting_value as string) === 'true';
          case 'json':
            return JSON.parse(row.setting_value as string);
          default:
            return row.setting_value;
        }
      })()
    }));
  },

  delete: async (key: string) => {
    const sql = 'DELETE FROM system_settings WHERE setting_key = ?';
    return await query(sql, [key]);
  }
};
