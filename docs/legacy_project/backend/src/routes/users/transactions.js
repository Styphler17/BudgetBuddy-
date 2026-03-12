const express = require("express");
const TransactionController = require("../../controllers/users/TransactionController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(TransactionController.getAll));
router.post("/", asyncHandler(TransactionController.create));
router.put("/:transactionId", asyncHandler(TransactionController.update));
router.delete("/:transactionId", asyncHandler(TransactionController.delete));

module.exports = router;
