const Goal = require("../../models/Goal");

class GoalController {
  static async getAll(req, res) {
    const goals = await Goal.findAll(req.userId);
    res.json(goals);
  }

  static async create(req, res) {
    const { name, targetAmount } = req.body;
    if (!name || !targetAmount) return res.status(400).json({ message: "Name and targetAmount are required" });
    const id = await Goal.create(req.userId, req.body);
    const newGoal = await Goal.findById(id);
    res.status(201).json(newGoal);
  }

  static async update(req, res) {
    const success = await Goal.update(req.userId, req.params.goalId, req.body);
    if (!success) return res.status(404).json({ message: "Goal not found" });
    const updated = await Goal.findById(req.params.goalId);
    res.json(updated);
  }

  static async delete(req, res) {
    const success = await Goal.delete(req.userId, req.params.goalId);
    if (!success) return res.status(404).json({ message: "Goal not found" });
    res.status(204).end();
  }
}

module.exports = GoalController;
