const express = require("express");
const db = require("../db");
const asyncHandler = require("../utils/asyncHandler");

const accountsRouter = require("./users/accounts");
const categoriesRouter = require("./users/categories");
const transactionsRouter = require("./users/transactions");
const budgetsRouter = require("./users/budgets");
const goalsRouter = require("./users/goals");
const settingsRouter = require("./users/settings");

const router = express.Router();

router.post(
  "/",
  asyncHandler(async (req, res) => {
    const { email, name, passwordHash, currency = "USD" } = req.body;

    if (!email || !name || !passwordHash) {
      return res.status(400).json({ message: "Email, name, and passwordHash are required" });
    }

    const insertSql = `
      INSERT INTO users (email, name, password_hash, currency)
      VALUES (?, ?, ?, ?)
    `;

    const [result] = await db.query(insertSql, [email, name, passwordHash, currency]);

    const [users] = await db.query("SELECT id, email, name, currency, is_active, email_verified, created_at, updated_at FROM users WHERE id = ?", [result.insertId]);
    res.status(201).json(users[0]);
  })
);

router.get(
  "/by-email",
  asyncHandler(async (req, res) => {
    const { email } = req.query;
    if (!email) {
      return res.status(400).json({ message: "Email query parameter is required" });
    }

    const [users] = await db.query("SELECT * FROM users WHERE email = ? LIMIT 1", [email]);
    if (!users.length) {
      return res.status(404).json({ message: "User not found" });
    }
    res.json(users[0]);
  })
);

router.get(
  "/:userId",
  asyncHandler(async (req, res) => {
    const { userId } = req.params;
    const [users] = await db.query("SELECT * FROM users WHERE id = ? LIMIT 1", [userId]);
    if (!users.length) {
      return res.status(404).json({ message: "User not found" });
    }
    res.json(users[0]);
  })
);

router.put(
  "/:userId",
  asyncHandler(async (req, res) => {
    const { userId } = req.params;
    const { name, first_name, last_name, email, currency, password_hash } = req.body;

    const fields = [];
    const values = [];

    if (name !== undefined) {
      fields.push("name = ?");
      values.push(name);
    }
    if (first_name !== undefined) {
      fields.push("first_name = ?");
      values.push(first_name);
    }
    if (last_name !== undefined) {
      fields.push("last_name = ?");
      values.push(last_name);
    }
    if (email !== undefined) {
      fields.push("email = ?");
      values.push(email);
    }
    if (currency !== undefined) {
      fields.push("currency = ?");
      values.push(currency);
    }
    if (password_hash !== undefined) {
      fields.push("password_hash = ?");
      values.push(password_hash);
    }

    if (!fields.length) {
      return res.status(400).json({ message: "No fields provided for update" });
    }

    values.push(userId);

    const sql = `UPDATE users SET ${fields.join(", ")}, updated_at = CURRENT_TIMESTAMP WHERE id = ?`;
    await db.query(sql, values);

    const [users] = await db.query("SELECT * FROM users WHERE id = ? LIMIT 1", [userId]);
    res.json(users[0]);
  })
);

router.use(
  "/:userId/accounts",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  accountsRouter
);

router.use(
  "/:userId/categories",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  categoriesRouter
);

router.use(
  "/:userId/transactions",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  transactionsRouter
);

router.use(
  "/:userId/budgets",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  budgetsRouter
);

router.use(
  "/:userId/goals",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  goalsRouter
);

router.use(
  "/:userId/settings",
  (req, res, next) => {
    req.userId = Number(req.params.userId);
    if (!Number.isFinite(req.userId)) {
      return res.status(400).json({ message: "Invalid userId" });
    }
    next();
  },
  settingsRouter
);

module.exports = router;
