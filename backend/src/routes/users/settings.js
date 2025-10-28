const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const [rows] = await db.query("SELECT id, setting_key, setting_value, created_at, updated_at FROM user_settings WHERE user_id = ?", [
      req.userId
    ]);

    const settings = rows.reduce((acc, row) => {
      acc[row.setting_key] = row.setting_value;
      return acc;
    }, {});

    res.json(settings);
  })
);

router.put(
  "/",
  asyncHandler(async (req, res) => {
    const updates = req.body;
    if (!updates || typeof updates !== "object") {
      return res.status(400).json({ message: "Request body must be an object of key/value pairs" });
    }

    const entries = Object.entries(updates);
    if (!entries.length) {
      return res.status(400).json({ message: "No settings provided" });
    }

    const insertSql = `
      INSERT INTO user_settings (user_id, setting_key, setting_value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP
    `;

    const tasks = entries.map(([key, value]) => db.query(insertSql, [req.userId, key, String(value)]));
    await Promise.all(tasks);

    res.json({ message: "Settings updated" });
  })
);

router.put(
  "/:settingKey",
  asyncHandler(async (req, res) => {
    const { settingKey } = req.params;
    const { value } = req.body;

    if (value === undefined) {
      return res.status(400).json({ message: "Value is required" });
    }

    const insertSql = `
      INSERT INTO user_settings (user_id, setting_key, setting_value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP
    `;

    await db.query(insertSql, [req.userId, settingKey, String(value)]);
    res.json({ message: "Setting updated" });
  })
);

module.exports = router;
