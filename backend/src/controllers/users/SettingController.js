const Setting = require("../../models/Setting");

class SettingController {
  static async getAll(req, res) {
    const settings = await Setting.findAll(req.userId);
    res.json(settings);
  }

  static async updateAll(req, res) {
    if (!req.body || typeof req.body !== "object") {
      return res.status(400).json({ message: "Request body must be an object" });
    }
    await Setting.updateAll(req.userId, req.body);
    res.json({ message: "Settings updated" });
  }

  static async updateOne(req, res) {
    const { settingKey } = req.params;
    const { value } = req.body;
    if (value === undefined) return res.status(400).json({ message: "Value is required" });
    await Setting.updateOne(req.userId, settingKey, value);
    res.json({ message: "Setting updated" });
  }
}

module.exports = SettingController;
