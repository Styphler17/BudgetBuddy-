const Account = require("../../models/Account");

class AccountController {
  static async getAll(req, res) {
    const accounts = await Account.findAll(req.userId);
    res.json(accounts);
  }

  static async create(req, res) {
    const { name, type } = req.body;
    if (!name || !type) return res.status(400).json({ message: "Name and type are required" });
    const id = await Account.create(req.userId, req.body);
    const newAccount = await Account.findById(id);
    res.status(201).json(newAccount);
  }

  static async update(req, res) {
    const success = await Account.update(req.userId, req.params.accountId, req.body);
    if (!success) return res.status(404).json({ message: "Account not found" });
    const updated = await Account.findById(req.params.accountId);
    res.json(updated);
  }

  static async delete(req, res) {
    const success = await Account.delete(req.userId, req.params.accountId);
    if (!success) return res.status(404).json({ message: "Account not found" });
    res.status(204).end();
  }
}

module.exports = AccountController;
