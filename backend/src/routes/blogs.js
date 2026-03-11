const express = require("express");
const BlogController = require("../controllers/BlogController");
const asyncHandler = require("../utils/asyncHandler");

const router = express.Router();

router.get("/", asyncHandler(BlogController.getAll));
router.get("/slug/:slug", asyncHandler(BlogController.getBySlug));
router.get("/:id/related", asyncHandler(BlogController.getRelated));
router.get("/:id", asyncHandler(BlogController.getById));
router.post("/", asyncHandler(BlogController.create));
router.put("/:id", asyncHandler(BlogController.update));
router.delete("/:id", asyncHandler(BlogController.delete));

module.exports = router;
