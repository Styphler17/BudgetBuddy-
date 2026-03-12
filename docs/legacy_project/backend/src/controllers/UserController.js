const User = require("../models/User");

class UserController {
  static async create(req, res) {
    const { email, name, passwordHash, currency } = req.body;
    if (!email || !name || !passwordHash) {
      return res.status(400).json({ message: "Email, name, and passwordHash are required" });
    }
    const userId = await User.create({ email, name, passwordHash, currency });
    const user = await User.findById(userId);
    res.status(201).json(user);
  }

  static async getByEmail(req, res) {
    const { email } = req.query;
    if (!email) return res.status(400).json({ message: "Email query parameter is required" });
    const user = await User.findByEmail(email);
    if (!user) return res.status(404).json({ message: "User not found" });
    res.json(user);
  }

  static async getById(req, res) {
    const user = await User.findById(req.params.userId);
    if (!user) return res.status(404).json({ message: "User not found" });
    res.json(user);
  }

  static async update(req, res) {
    const { userId } = req.params;
    const success = await User.update(userId, req.body);
    if (!success) return res.status(400).json({ message: "No fields provided for update" });
    const user = await User.findById(userId);
    res.json(user);
  }
}

module.exports = UserController;
