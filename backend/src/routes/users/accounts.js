const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const [rows] = await db.query(
      "SELECT id, user_id, name, type, balance, currency, created_at FROM accounts WHERE user_id = ? ORDER BY created_at DESC",
      [req.userId]
    );

    const normalized = rows.map((row) => ({
      ...row,
      account_number: null,
      is_active: true
    }));

    res.json(normalized);
  })
);

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { name, type, balance = 0, currency } = req.body;

    if (!name || !type) {
      return res.status(400).json({ message: "Name and type are required" });
    }

    const insertSql = `
      INSERT INTO accounts (user_id, name, type, balance, currency)
      VALUES (?, ?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [req.userId, name, type, balance, currency || "USD"]);

    const [rows] = await db.query("SELECT id, user_id, name, type, balance, currency, created_at FROM accounts WHERE id = ?", [
      result.insertId
    ]);

    const account = rows[0];
    res.status(201).json({
      ...account,
      account_number: null,
      is_active: true
    });
  })
);

router.put(
  "/:accountId",
  asyncHandler(async (req, res) => {
    const { accountId } = req.params;
    const { name, type, balance, currency } = req.body;

    const fields = [];
    const values = [];

    if (name !== undefined) {
      fields.push("name = ?");
      values.push(name);
    }
    if (type !== undefined) {
      fields.push("type = ?");
      values.push(type);
    }
    if (balance !== undefined) {
      fields.push("balance = ?");
      values.push(balance);
    }
    if (currency !== undefined) {
      fields.push("currency = ?");
      values.push(currency);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(req.userId, accountId);

    const updateSql = `UPDATE accounts SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`;
    const [result] = await db.query(updateSql, values);

    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Account not found" });
    }

    const [rows] = await db.query("SELECT id, user_id, name, type, balance, currency, created_at FROM accounts WHERE id = ?", [
      accountId
    ]);

    const account = rows[0];
    res.json({
      ...account,
      account_number: null,
      is_active: true
    });
  })
);

router.delete(
  "/:accountId",
  asyncHandler(async (req, res) => {
    const { accountId } = req.params;

    const [result] = await db.query("DELETE FROM accounts WHERE user_id = ? AND id = ?", [req.userId, accountId]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Account not found" });
    }

    res.status(204).end();
  })
);

module.exports = router;
