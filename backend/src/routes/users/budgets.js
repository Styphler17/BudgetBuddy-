const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const { period } = req.query;
    const params = [req.userId];
    let sql =
      "SELECT id, user_id, period, amount, start_date, end_date, created_at FROM budgets WHERE user_id = ?";

    if (period) {
      sql += " AND period = ?";
      params.push(period);
    }

    sql += " ORDER BY start_date DESC, created_at DESC";

    if (period) {
      sql += " LIMIT 1";
    }

    const [rows] = await db.query(sql, params);
    res.json(rows);
  })
);

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { period, amount, startDate, endDate } = req.body;

    if (!period || !amount || !startDate || !endDate) {
      return res.status(400).json({ message: "Period, amount, startDate, and endDate are required" });
    }

    const insertSql = `
      INSERT INTO budgets (user_id, period, amount, start_date, end_date)
      VALUES (?, ?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [req.userId, period, amount, startDate, endDate]);
    const [rows] = await db.query(
      "SELECT id, user_id, period, amount, start_date, end_date, created_at FROM budgets WHERE id = ?",
      [result.insertId]
    );
    res.status(201).json(rows[0]);
  })
);

router.put(
  "/:budgetId",
  asyncHandler(async (req, res) => {
    const { budgetId } = req.params;
    const { period, amount, startDate, endDate } = req.body;

    const fields = [];
    const values = [];

    if (period !== undefined) {
      fields.push("period = ?");
      values.push(period);
    }
    if (amount !== undefined) {
      fields.push("amount = ?");
      values.push(amount);
    }
    if (startDate !== undefined) {
      fields.push("start_date = ?");
      values.push(startDate);
    }
    if (endDate !== undefined) {
      fields.push("end_date = ?");
      values.push(endDate);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(req.userId, budgetId);

    const updateSql = `UPDATE budgets SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`;
    const [result] = await db.query(updateSql, values);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Budget not found" });
    }

    const [rows] = await db.query(
      "SELECT id, user_id, period, amount, start_date, end_date, created_at FROM budgets WHERE id = ?",
      [budgetId]
    );
    res.json(rows[0]);
  })
);

router.delete(
  "/:budgetId",
  asyncHandler(async (req, res) => {
    const { budgetId } = req.params;

    const [result] = await db.query("DELETE FROM budgets WHERE user_id = ? AND id = ?", [req.userId, budgetId]);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Budget not found" });
    }

    res.status(204).end();
  })
);

module.exports = router;
