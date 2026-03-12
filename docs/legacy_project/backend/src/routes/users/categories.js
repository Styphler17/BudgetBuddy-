const express = require("express");
const CategoryController = require("../../controllers/users/CategoryController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(CategoryController.getAll));
router.post("/", asyncHandler(CategoryController.create));
router.put("/:categoryId", asyncHandler(CategoryController.update));
router.delete("/:categoryId", asyncHandler(CategoryController.delete));

module.exports = router;
