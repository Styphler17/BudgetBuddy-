const express = require("express");
const AuthController = require("../controllers/AuthController");
const asyncHandler = require("../utils/asyncHandler");

const router = express.Router();

router.post("/login", asyncHandler(AuthController.login));
router.post("/register", asyncHandler(AuthController.register));

module.exports = router;
