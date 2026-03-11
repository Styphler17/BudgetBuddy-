const express = require("express");
const UserController = require("../controllers/UserController");
const asyncHandler = require("../utils/asyncHandler");

const accountsRouter = require("./users/accounts");
const categoriesRouter = require("./users/categories");
const transactionsRouter = require("./users/transactions");
const budgetsRouter = require("./users/budgets");
const goalsRouter = require("./users/goals");
const settingsRouter = require("./users/settings");

const router = express.Router();

router.post("/", asyncHandler(UserController.create));
router.get("/by-email", asyncHandler(UserController.getByEmail));
router.get("/:userId", asyncHandler(UserController.getById));
router.put("/:userId", asyncHandler(UserController.update));

// Sub-routes middleware
const injectUserId = (req, res, next) => {
  req.userId = Number(req.params.userId);
  if (!Number.isFinite(req.userId)) {
    return res.status(400).json({ message: "Invalid userId" });
  }
  next();
};

router.use("/:userId/accounts", injectUserId, accountsRouter);
router.use("/:userId/categories", injectUserId, categoriesRouter);
router.use("/:userId/transactions", injectUserId, transactionsRouter);
router.use("/:userId/budgets", injectUserId, budgetsRouter);
router.use("/:userId/goals", injectUserId, goalsRouter);
router.use("/:userId/settings", injectUserId, settingsRouter);

module.exports = router;
