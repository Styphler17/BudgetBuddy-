const express = require("express");
const SettingController = require("../../controllers/users/SettingController");
const asyncHandler = require("../../utils/asyncHandler");

const router = express.Router({ mergeParams: true });

router.get("/", asyncHandler(SettingController.getAll));
router.put("/", asyncHandler(SettingController.updateAll));
router.put("/:settingKey", asyncHandler(SettingController.updateOne));

module.exports = router;
