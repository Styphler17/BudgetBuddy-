const db = require("../db");

class Goal {
  static async findAll(userId) {
    const [rows] = await db.query(
      `
      SELECT g.*, c.name AS category_name, c.emoji AS category_emoji
      FROM goals g
      LEFT JOIN categories c ON g.category_id = c.id
      WHERE g.user_id = ?
      ORDER BY g.created_at DESC
      `,
      [userId]
    );
    return rows;
  }

  static async findById(id) {
    const [rows] = await db.query(
      `
      SELECT g.*, c.name AS category_name, c.emoji AS category_emoji
      FROM goals g
      LEFT JOIN categories c ON g.category_id = c.id
      WHERE g.id = ?
      `,
      [id]
    );
    return rows[0];
  }

  static async create(userId, data) {
    const { name, targetAmount, currentAmount = 0, deadline, categoryId } = data;
    const [result] = await db.query(
      "INSERT INTO goals (user_id, name, target_amount, current_amount, deadline, category_id) VALUES (?, ?, ?, ?, ?, ?)",
      [userId, name, targetAmount, currentAmount, deadline || null, categoryId || null]
    );
    return result.insertId;
  }

  static async update(userId, id, data) {
    const fields = [];
    const values = [];
    Object.entries(data).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key === "targetAmount" ? "target_amount" : key === "currentAmount" ? "current_amount" : key === "categoryId" ? "category_id" : key} = ?`);
        values.push(value);
      }
    });
    if (!fields.length) return false;
    values.push(userId, id);
    const [result] = await db.query(`UPDATE goals SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`, values);
    return result.affectedRows > 0;
  }

  static async delete(userId, id) {
    const [result] = await db.query("DELETE FROM goals WHERE user_id = ? AND id = ?", [userId, id]);
    return result.affectedRows > 0;
  }
}

module.exports = Goal;
