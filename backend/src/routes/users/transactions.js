const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const { limit, categoryId } = req.query;
    const params = [req.userId];
    let sql = `
      SELECT
        t.id,
        t.user_id,
        t.category_id,
        t.amount,
        t.description,
        t.type,
        t.date,
        t.created_at,
        c.name AS category_name,
        c.emoji AS category_emoji
      FROM transactions t
      LEFT JOIN categories c ON t.category_id = c.id
      WHERE t.user_id = ?
    `;

    if (categoryId) {
      sql += " AND t.category_id = ?";
      params.push(Number(categoryId));
    }

    sql += " ORDER BY t.date DESC, t.created_at DESC";

    if (limit) {
      sql += " LIMIT ?";
      params.push(Number(limit));
    }

    const [rows] = await db.query(sql, params);
    res.json(rows);
  })
);

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { categoryId, amount, description, type, date } = req.body;

    if (!amount || !type || !date) {
      return res.status(400).json({ message: "Amount, type, and date are required" });
    }

    const insertSql = `
      INSERT INTO transactions (user_id, category_id, amount, description, type, date)
      VALUES (?, ?, ?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [
      req.userId,
      categoryId || null,
      amount,
      description || null,
      type,
      date
    ]);

    const [rows] = await db.query(
      `
        SELECT
          t.id,
          t.user_id,
          t.category_id,
          t.amount,
          t.description,
          t.type,
          t.date,
          t.created_at,
          c.name AS category_name,
          c.emoji AS category_emoji
        FROM transactions t
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.id = ?
      `,
      [result.insertId]
    );

    res.status(201).json(rows[0]);
  })
);

router.put(
  "/:transactionId",
  asyncHandler(async (req, res) => {
    const { transactionId } = req.params;
    const { categoryId, amount, description, type, date } = req.body;

    const fields = [];
    const values = [];

    if (categoryId !== undefined) {
      fields.push("category_id = ?");
      values.push(categoryId || null);
    }
    if (amount !== undefined) {
      fields.push("amount = ?");
      values.push(amount);
    }
    if (description !== undefined) {
      fields.push("description = ?");
      values.push(description);
    }
    if (type !== undefined) {
      fields.push("type = ?");
      values.push(type);
    }
    if (date !== undefined) {
      fields.push("date = ?");
      values.push(date);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(req.userId, transactionId);

    const updateSql = `UPDATE transactions SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`;
    const [result] = await db.query(updateSql, values);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Transaction not found" });
    }

    const [rows] = await db.query(
      `
        SELECT
          t.id,
          t.user_id,
          t.category_id,
          t.amount,
          t.description,
          t.type,
          t.date,
          t.created_at,
          c.name AS category_name,
          c.emoji AS category_emoji
        FROM transactions t
        LEFT JOIN categories c ON t.category_id = c.id
        WHERE t.id = ?
      `,
      [transactionId]
    );

    res.json(rows[0]);
  })
);

router.delete(
  "/:transactionId",
  asyncHandler(async (req, res) => {
    const { transactionId } = req.params;

    const [result] = await db.query("DELETE FROM transactions WHERE user_id = ? AND id = ?", [
      req.userId,
      transactionId
    ]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Transaction not found" });
    }

    res.status(204).end();
  })
);

module.exports = router;
