const db = require("../db");

class Budget {
  static async findAll(userId, filters = {}) {
    const { period } = filters;
    const params = [userId];
    let sql = "SELECT * FROM budgets WHERE user_id = ?";
    if (period) { sql += " AND period = ?"; params.push(period); }
    sql += " ORDER BY start_date DESC, created_at DESC";
    if (period) { sql += " LIMIT 1"; }
    const [rows] = await db.query(sql, params);
    return rows;
  }

  static async findById(id) {
    const [rows] = await db.query("SELECT * FROM budgets WHERE id = ?", [id]);
    return rows[0];
  }

  static async create(userId, data) {
    const { period, amount, startDate, endDate } = data;
    const [result] = await db.query(
      "INSERT INTO budgets (user_id, period, amount, start_date, end_date) VALUES (?, ?, ?, ?, ?)",
      [userId, period, amount, startDate, endDate]
    );
    return result.insertId;
  }

  static async update(userId, id, data) {
    const fields = [];
    const values = [];
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key === "startDate" ? "start_date" : key === "endDate" ? "end_date" : key} = ?`);
        values.push(value);
      }
    });
    if (!fields.length) return false;
    values.push(userId, id);
    const [result] = await db.query(`UPDATE budgets SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`, values);
    return result.affectedRows > 0;
  }

  static async delete(userId, id) {
    const [result] = await db.query("DELETE FROM budgets WHERE user_id = ? AND id = ?", [userId, id]);
    return result.affectedRows > 0;
  }
}

module.exports = Budget;
