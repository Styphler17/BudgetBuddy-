const express = require("express");
const BudgetController = require("../../controllers/users/BudgetController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(BudgetController.getAll));
router.post("/", asyncHandler(BudgetController.create));
router.put("/:budgetId", asyncHandler(BudgetController.update));
router.delete("/:budgetId", asyncHandler(BudgetController.delete));

module.exports = router;
