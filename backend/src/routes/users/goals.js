const express = require("express");
const GoalController = require("../../controllers/users/GoalController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(GoalController.getAll));
router.post("/", asyncHandler(GoalController.create));
router.put("/:goalId", asyncHandler(GoalController.update));
router.delete("/:goalId", asyncHandler(GoalController.delete));

module.exports = router;
