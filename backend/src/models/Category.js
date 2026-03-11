const db = require("../db");

class Category {
  static async findAll(userId) {
    const [rows] = await db.query(
      "SELECT * FROM categories WHERE user_id = ? ORDER BY created_at DESC",
      [userId]
    );
    return rows;
  }

  static async findById(id) {
    const [rows] = await db.query("SELECT * FROM categories WHERE id = ?", [id]);
    return rows[0];
  }

  static async create(userId, data) {
    const { name, emoji, color = "#3b82f6", budget = 0 } = data;
    const [result] = await db.query(
      "INSERT INTO categories (user_id, name, emoji, color, budget) VALUES (?, ?, ?, ?, ?)",
      [userId, name, emoji || null, color, budget]
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
    const [result] = await db.query(`UPDATE categories SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`, values);
    return result.affectedRows > 0;
  }

  static async delete(userId, id) {
    const [result] = await db.query("DELETE FROM categories WHERE user_id = ? AND id = ?", [userId, id]);
    return result.affectedRows > 0;
  }
}

module.exports = Category;
