const Transaction = require("../../models/Transaction");

class TransactionController {
  static async getAll(req, res) {
    const transactions = await Transaction.findAll(req.userId, req.query);
    res.json(transactions);
  }

  static async create(req, res) {
    const id = await Transaction.create(req.userId, req.body);
    const newTransaction = await Transaction.findById(id);
    res.status(201).json(newTransaction);
  }

  static async update(req, res) {
    const success = await Transaction.update(req.userId, req.params.transactionId, req.body);
    if (!success) return res.status(404).json({ message: "Transaction not found" });
    const updated = await Transaction.findById(req.params.transactionId);
    res.json(updated);
  }

  static async delete(req, res) {
    const success = await Transaction.delete(req.userId, req.params.transactionId);
    if (!success) return res.status(404).json({ message: "Transaction not found" });
    res.status(204).end();
  }
}

module.exports = TransactionController;
