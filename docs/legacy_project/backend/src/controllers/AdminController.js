const Admin = require("../models/Admin");
const User = require("../models/User");

class AdminController {
  static async getStats(req, res) {
    const stats = await Admin.getStats();
    res.json(stats);
  }

  static async getLogs(req, res) {
    const { limit, offset } = req.query;
    const logs = await Admin.getLogs(limit, offset);
    res.json(logs);
  }

  static async createLog(req, res) {
    const data = {
      ...req.body,
      ipAddress: req.body.ipAddress || req.ip || req.headers["x-forwarded-for"] || req.connection.remoteAddress
    };
    await Admin.logAction(data);
    res.status(201).json({ message: "Log created" });
  }

  static async getAllAdmins(req, res) {
    const { limit, offset } = req.query;
    const admins = await Admin.findAll(limit, offset);
    res.json(admins);
  }

  static async getAdminByEmail(req, res) {
    const admin = await Admin.findByEmail(req.query.email);
    if (!admin) return res.status(404).json({ message: "Admin not found" });
    res.json(admin);
  }

  static async createAdmin(req, res) {
    const id = await Admin.create(req.body);
    res.status(201).json({ id, ...req.body });
  }

  static async updateAdmin(req, res) {
    const success = await Admin.update(req.params.id, req.body);
    if (!success) return res.status(400).json({ message: "No fields to update" });
    res.json({ message: "Admin updated" });
  }

  static async deleteAdmin(req, res) {
    await Admin.delete(req.params.id);
    res.status(204).end();
  }

  // User management for admins
  static async getAllUsers(req, res) {
    const { limit = 50, offset = 0 } = req.query;
    const [users] = await require("../db").query(
      "SELECT id, email, name, currency, is_active, email_verified, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?",
      [Number(limit), Number(offset)]
    );
    res.json(users);
  }

  static async updateUser(req, res) {
    const success = await User.update(req.params.id, req.body);
    if (!success) return res.status(400).json({ message: "No fields to update" });
    res.json({ message: "User updated" });
  }

  static async deleteUser(req, res) {
    await require("../db").query("DELETE FROM users WHERE id = ?", [req.params.id]);
    res.status(204).end();
  }
}

module.exports = AdminController;
