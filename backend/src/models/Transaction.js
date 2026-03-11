const db = require("../db");

class Transaction {
  static async findAll(userId, filters = {}) {
    const { limit, categoryId, startDate, endDate } = filters;
    const params = [userId];
    let sql = `
      SELECT t.*, c.name AS category_name, c.emoji AS category_emoji
      FROM transactions t
      LEFT JOIN categories c ON t.category_id = c.id
      WHERE t.user_id = ?
    `;

    if (categoryId) { sql += " AND t.category_id = ?"; params.push(Number(categoryId)); }
    if (startDate) { sql += " AND t.date >= ?"; params.push(startDate); }
    if (endDate) { sql += " AND t.date <= ?"; params.push(endDate); }

    sql += " ORDER BY t.date DESC, t.created_at DESC";
    if (limit) { sql += " LIMIT ?"; params.push(Number(limit)); }

    const [rows] = await db.query(sql, params);
    return rows;
  }

  static async findById(id) {
    const [rows] = await db.query(
      `
      SELECT t.*, c.name AS category_name, c.emoji AS category_emoji
      FROM transactions t
      LEFT JOIN categories c ON t.category_id = c.id
      WHERE t.id = ?
      `,
      [id]
    );
    return rows[0];
  }

  static async create(userId, data) {
    const { categoryId, amount, description, type, date } = data;
    const [result] = await db.query(
      "INSERT INTO transactions (user_id, category_id, amount, description, type, date) VALUES (?, ?, ?, ?, ?, ?)",
      [userId, categoryId || null, amount, description || null, type, date]
    );
    return result.insertId;
  }

  static async update(userId, id, data) {
    const fields = [];
    const values = [];
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key} = ?`);
        values.push(value === "" ? null : value);
      }
    });
    if (!fields.length) return false;
    values.push(userId, id);
    const [result] = await db.query(`UPDATE transactions SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`, values);
    return result.affectedRows > 0;
  }

  static async delete(userId, id) {
    const [result] = await db.query("DELETE FROM transactions WHERE user_id = ? AND id = ?", [userId, id]);
    return result.affectedRows > 0;
  }
}

module.exports = Transaction;
