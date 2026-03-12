const Category = require("../../models/Category");

class CategoryController {
  static async getAll(req, res) {
    const categories = await Category.findAll(req.userId);
    res.json(categories);
  }

  static async create(req, res) {
    if (!req.body.name) return res.status(400).json({ message: "Category name is required" });
    const id = await Category.create(req.userId, req.body);
    const newCategory = await Category.findById(id);
    res.status(201).json(newCategory);
  }

  static async update(req, res) {
    const success = await Category.update(req.userId, req.params.categoryId, req.body);
    if (!success) return res.status(404).json({ message: "Category not found" });
    const updated = await Category.findById(req.params.categoryId);
    res.json(updated);
  }

  static async delete(req, res) {
    const success = await Category.delete(req.userId, req.params.categoryId);
    if (!success) return res.status(404).json({ message: "Category not found" });
    res.status(204).end();
  }
}

module.exports = CategoryController;
