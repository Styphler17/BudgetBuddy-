const express = require("express");
const AdminController = require("../controllers/AdminController");
const asyncHandler = require("../utils/asyncHandler");

const router = express.Router();

router.get("/stats", asyncHandler(AdminController.getStats));
router.get("/logs", asyncHandler(AdminController.getLogs));
router.post("/logs", asyncHandler(AdminController.createLog));
router.get("/admins", asyncHandler(AdminController.getAllAdmins));
router.get("/find-by-email", asyncHandler(AdminController.getAdminByEmail));
router.post("/create", asyncHandler(AdminController.createAdmin));
router.put("/update/:id", asyncHandler(AdminController.updateAdmin));
router.delete("/delete/:id", asyncHandler(AdminController.deleteAdmin));

// User management
router.get("/users", asyncHandler(AdminController.getAllUsers));
router.put("/users/:id", asyncHandler(AdminController.updateUser));
router.delete("/users/:id", asyncHandler(AdminController.deleteUser));

module.exports = router;
