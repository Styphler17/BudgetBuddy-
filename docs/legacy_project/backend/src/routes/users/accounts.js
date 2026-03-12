const express = require("express");
const AccountController = require("../../controllers/users/AccountController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(AccountController.getAll));
router.post("/", asyncHandler(AccountController.create));
router.put("/:accountId", asyncHandler(AccountController.update));
router.delete("/:accountId", asyncHandler(AccountController.delete));

module.exports = router;
