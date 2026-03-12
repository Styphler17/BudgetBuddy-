const db = require("../db");

class Admin {
  static async getStats() {
    const [[userCount]] = await db.query("SELECT COUNT(*) as count FROM users");
    const [[adminCount]] = await db.query("SELECT COUNT(*) as count FROM admins");
    const [[transactionCount]] = await db.query("SELECT COUNT(*) as count FROM transactions");
    const [[categoryCount]] = await db.query("SELECT COUNT(*) as count FROM categories");
    const [[goalCount]] = await db.query("SELECT COUNT(*) as count FROM goals");
    const [[accountCount]] = await db.query("SELECT COUNT(*) as count FROM accounts");
    const [[blogCount]] = await db.query("SELECT COUNT(*) as count FROM blog_posts");

    return {
      totalUsers: userCount.count,
      totalAdmins: adminCount.count,
      totalTransactions: transactionCount.count,
      totalCategories: categoryCount.count,
      totalGoals: goalCount.count,
      totalAccounts: accountCount.count,
      totalBlogPosts: blogCount.count
    };
  }

  static async getLogs(limit = 50, offset = 0) {
    const [logs] = await db.query(
      `
      SELECT al.*, a.name as admin_name, a.email as admin_email
      FROM admin_logs al
      LEFT JOIN admins a ON al.admin_id = a.id
      ORDER BY al.created_at DESC
      LIMIT ? OFFSET ?
      `,
      [Number(limit), Number(offset)]
    );
    return logs;
  }

  static async logAction(data) {
    const { adminId, action, targetType, targetId, details, ipAddress } = data;
    await db.query(
      "INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)",
      [adminId, action, targetType, targetId, details, ipAddress]
    );
    return true;
  }

  static async findAll(limit = 50, offset = 0) {
    const [admins] = await db.query(
      "SELECT id, email, name, role, is_active, last_login, created_at FROM admins ORDER BY created_at DESC LIMIT ? OFFSET ?",
      [Number(limit), Number(offset)]
    );
    return admins;
  }

  static async findByEmail(email) {
    const [admins] = await db.query("SELECT * FROM admins WHERE email = ? LIMIT 1", [email]);
    return admins[0];
  }

  static async create(data) {
    const { email, name, passwordHash, role = "admin" } = data;
    const [result] = await db.query(
      "INSERT INTO admins (email, name, password_hash, role) VALUES (?, ?, ?, ?)",
      [email, name, passwordHash, role]
    );
    return result.insertId;
  }

  static async update(id, data) {
    const fields = [];
    const values = [];
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key} = ?`);
        values.push(value);
      }
    });
    if (!fields.length) return false;
    values.push(id);
    await db.query(`UPDATE admins SET ${fields.join(", ")} WHERE id = ?`, values);
    return true;
  }

  static async delete(id) {
    await db.query("DELETE FROM admins WHERE id = ?", [id]);
    return true;
  }
}

module.exports = Admin;
