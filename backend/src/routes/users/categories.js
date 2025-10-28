const express = require("express");
const db = require("../../db");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get(
  "/",
  asyncHandler(async (req, res) => {
    const [rows] = await db.query(
      "SELECT id, user_id, name, emoji, color, budget, created_at FROM categories WHERE user_id = ? ORDER BY created_at DESC",
      [req.userId]
    );
    res.json(rows);
  })
);

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { name, emoji, color = "#3b82f6", budget = 0 } = req.body;

    if (!name) {
      return res.status(400).json({ message: "Category name is required" });
    }

    const insertSql = `
      INSERT INTO categories (user_id, name, emoji, color, budget)
      VALUES (?, ?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [req.userId, name, emoji || null, color, budget]);

    const [rows] = await db.query(
      "SELECT id, user_id, name, emoji, color, budget, created_at FROM categories WHERE id = ?",
      [result.insertId]
    );

    res.status(201).json(rows[0]);
  })
);

router.put(
  "/:categoryId",
  asyncHandler(async (req, res) => {
    const { categoryId } = req.params;
    const { name, emoji, color, budget } = req.body;

    const fields = [];
    const values = [];

    if (name !== undefined) {
      fields.push("name = ?");
      values.push(name);
    }
    if (emoji !== undefined) {
      fields.push("emoji = ?");
      values.push(emoji);
    }
    if (color !== undefined) {
      fields.push("color = ?");
      values.push(color);
    }
    if (budget !== undefined) {
      fields.push("budget = ?");
      values.push(budget);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(req.userId, categoryId);

    const updateSql = `UPDATE categories SET ${fields.join(", ")} WHERE user_id = ? AND id = ?`;
    const [result] = await db.query(updateSql, values);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Category not found" });
    }

    const [rows] = await db.query(
      "SELECT id, user_id, name, emoji, color, budget, created_at FROM categories WHERE id = ?",
      [categoryId]
    );

    res.json(rows[0]);
  })
);

router.delete(
  "/:categoryId",
  asyncHandler(async (req, res) => {
    const { categoryId } = req.params;

    const [result] = await db.query("DELETE FROM categories WHERE user_id = ? AND id = ?", [req.userId, categoryId]);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Category not found" });
    }

    res.status(204).end();
  })
);

module.exports = router;
