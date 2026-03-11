const db = require("../db");

class User {
  static async findByEmail(email) {
    const [users] = await db.query("SELECT * FROM users WHERE email = ? LIMIT 1", [email]);
    return users[0];
  }

  static async findById(id) {
    const [users] = await db.query("SELECT * FROM users WHERE id = ? LIMIT 1", [id]);
    return users[0];
  }

  static async create(userData) {
    const { email, name, passwordHash, currency = "USD" } = userData;
    const [result] = await db.query(
      "INSERT INTO users (email, name, password_hash, currency) VALUES (?, ?, ?, ?)",
      [email, name, passwordHash, currency]
    );
    return result.insertId;
  }

  static async update(id, userData) {
    const fields = [];
    const values = [];

    Object.entries(userData).forEach(([key, value]) => {
      if (value !== undefined) {
        fields.push(`${key} = ?`);
        values.push(value);
      }
    });

    if (fields.length === 0) return false;

    values.push(id);
    await db.query(`UPDATE users SET ${fields.join(", ")} WHERE id = ?`, values);
    return true;
  }
}

module.exports = User;
