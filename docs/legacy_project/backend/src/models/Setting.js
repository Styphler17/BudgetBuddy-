const db = require("../db");

class Setting {
  static async findAll(userId) {
    const [rows] = await db.query(
      "SELECT setting_key, setting_value FROM user_settings WHERE user_id = ?",
      [userId]
    );
    return rows.reduce((acc, row) => {
      acc[row.setting_key] = row.setting_value;
      return acc;
    }, {});
  }

  static async updateAll(userId, updates) {
    const insertSql = `
      INSERT INTO user_settings (user_id, setting_key, setting_value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP
    `;
    const entries = Object.entries(updates);
    const tasks = entries.map(([key, value]) => db.query(insertSql, [userId, key, String(value)]));
    await Promise.all(tasks);
    return true;
  }

  static async updateOne(userId, key, value) {
    const insertSql = `
      INSERT INTO user_settings (user_id, setting_key, setting_value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP
    `;
    await db.query(insertSql, [userId, key, String(value)]);
    return true;
  }
}

module.exports = Setting;
