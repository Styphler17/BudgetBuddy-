const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const [rows] = await db.query(
      `
        SELECT
          g.id,
          g.user_id,
          g.name,
          g.target_amount,
          g.current_amount,
          g.deadline,
          g.category_id,
          g.created_at,
          c.name AS category_name,
          c.emoji AS category_emoji
        FROM goals g
        LEFT JOIN categories c ON g.category_id = c.id
        WHERE g.user_id = ?
        ORDER BY g.created_at DESC
      `,
      [req.userId]
    );
    res.json(rows);
  })
);

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { name, targetAmount, currentAmount = 0, deadline, categoryId } = req.body;

    if (!name || !targetAmount) {
      return res.status(400).json({ message: "Name and targetAmount are required" });
    }

    const insertSql = `
      INSERT INTO goals (user_id, name, target_amount, current_amount, deadline, category_id)
      VALUES (?, ?, ?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [
      req.userId,
      name,
      targetAmount,
      currentAmount,
      deadline || null,
      categoryId || null
    ]);

    const [rows] = await db.query(
      `
        SELECT
          g.id,
          g.user_id,
          g.name,
          g.target_amount,
          g.current_amount,
          g.deadline,
          g.category_id,
          g.created_at,
          c.name AS category_name,
          c.emoji AS category_emoji
        FROM goals g
        LEFT JOIN categories c ON g.category_id = c.id
        WHERE g.id = ?
      `,
      [result.insertId]
    );
    res.status(201).json(rows[0]);
  })
);

router.put(
  "/:goalId",
  asyncHandler(async (req, res) => {
    const { goalId } = req.params;
    const { name, targetAmount, currentAmount, deadline, categoryId } = req.body;

    const fields = [];
    const values = [];

    if (name !== undefined) {
      fields.push("name = ?");
      values.push(name);
    }
    if (targetAmount !== undefined) {
      fields.push("target_amount = ?");
      values.push(targetAmount);
    }
    if (currentAmount !== undefined) {
      fields.push("current_amount = ?");
      values.push(currentAmount);
    }
    if (deadline !== undefined) {
      fields.push("deadline = ?");
      values.push(deadline || null);
    }
    if (categoryId !== undefined) {
      fields.push("category_id = ?");
      values.push(categoryId || null);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(req.userId, goalId);

    const updateSql = `UPDATE goals SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`;
    const [result] = await db.query(updateSql, values);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Goal not found" });
    }

    const [rows] = await db.query(
      `
        SELECT
          g.id,
          g.user_id,
          g.name,
          g.target_amount,
          g.current_amount,
          g.deadline,
          g.category_id,
          g.created_at,
          c.name AS category_name,
          c.emoji AS category_emoji
        FROM goals g
        LEFT JOIN categories c ON g.category_id = c.id
        WHERE g.id = ?
      `,
      [goalId]
    );
    res.json(rows[0]);
  })
);

router.delete(
  "/:goalId",
  asyncHandler(async (req, res) => {
    const { goalId } = req.params;
    const [result] = await db.query("DELETE FROM goals WHERE user_id = ? AND id = ?", [req.userId, goalId]);

    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Goal not found" });
    }

    res.status(204).end();
  })
);

module.exports = router;
