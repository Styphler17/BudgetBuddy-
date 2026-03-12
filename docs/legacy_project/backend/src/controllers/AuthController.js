const jwt = require("jsonwebtoken");
const bcrypt = require("bcryptjs");
const User = require("../models/User");

const JWT_SECRET = process.env.JWT_SECRET || "fallback_secret_key_change_in_production";

class AuthController {
  static async login(req, res) {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({ message: "Email and password are required" });
    }

    const user = await User.findByEmail(email);
    
    if (!user) {
      return res.status(401).json({ message: "Invalid credentials" });
    }

    const isValid = await bcrypt.compare(password, user.password_hash);
    
    if (!isValid) {
      return res.status(401).json({ message: "Invalid credentials" });
    }

    const token = jwt.sign(
      { 
        id: user.id, 
        email: user.email, 
        name: user.name,
        currency: user.currency
      },
      JWT_SECRET,
      { expiresIn: "30d" }
    );

    res.json({
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
        currency: user.currency,
      },
      token
    });
  }

  static async register(req, res) {
    const { email, name, passwordHash, currency } = req.body;
    
    const existingUser = await User.findByEmail(email);
    if (existingUser) {
      return res.status(400).json({ message: "User already exists" });
    }

    const userId = await User.create({ email, name, passwordHash, currency });
    const user = await User.findById(userId);

    res.status(201).json({
      message: "User registered successfully",
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
        currency: user.currency
      }
    });
  }
}

module.exports = AuthController;
