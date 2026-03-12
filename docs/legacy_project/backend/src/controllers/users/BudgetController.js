const Budget = require("../../models/Budget");

class BudgetController {
  static async getAll(req, res) {
    const budgets = await Budget.findAll(req.userId, req.query);
    res.json(budgets);
  }

  static async create(req, res) {
    const { period, amount, startDate, endDate } = req.body;
    if (!period || !amount || !startDate || !endDate) {
      return res.status(400).json({ message: "Period, amount, startDate, and endDate are required" });
    }
    const id = await Budget.create(req.userId, req.body);
    const newBudget = await Budget.findById(id);
    res.status(201).json(newBudget);
  }

  static async update(req, res) {
    const success = await Budget.update(req.userId, req.params.budgetId, req.body);
    if (!success) return res.status(404).json({ message: "Budget not found" });
    const updated = await Budget.findById(req.params.budgetId);
    res.json(updated);
  }

  static async delete(req, res) {
    const success = await Budget.delete(req.userId, req.params.budgetId);
    if (!success) return res.status(404).json({ message: "Budget not found" });
    res.status(204).end();
  }
}

module.exports = BudgetController;
