const db = require("../db");

class Account {
  static async findAll(userId) {
    const [rows] = await db.query(
      "SELECT * FROM accounts WHERE user_id = ? ORDER BY created_at DESC",
      [userId]
    );
    return rows.map(acc => ({ ...acc, account_number: null, is_active: true }));
  }

  static async findById(id) {
    const [rows] = await db.query("SELECT * FROM accounts WHERE id = ?", [id]);
    if (!rows[0]) return null;
    return { ...rows[0], account_number: null, is_active: true };
  }

  static async create(userId, data) {
    const { name, type, balance = 0, currency = "USD" } = data;
    const [result] = await db.query(
      "INSERT INTO accounts (user_id, name, type, balance, currency) VALUES (?, ?, ?, ?, ?)",
      [userId, name, type, balance, currency]
    );
    return result.insertId;
  }

  static async update(userId, id, data) {
    const fields = [];
    const values = [];
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key} = ?`);
        values.push(value);
      }
    });
    if (!fields.length) return false;
    values.push(userId, id);
    const [result] = await db.query(`UPDATE accounts SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`, values);
    return result.affectedRows > 0;
  }

  static async delete(userId, id) {
    const [result] = await db.query("DELETE FROM accounts WHERE user_id = ? AND id = ?", [userId, id]);
    return result.affectedRows > 0;
  }
}

module.exports = Account;
