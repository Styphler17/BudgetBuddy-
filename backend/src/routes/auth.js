const express = require("express");
const { OAuth2Client } = require("google-auth-library");
const jwt = require("jsonwebtoken");
const db = require("../db");
const asyncHandler = require("../utils/asyncHandler");

const router = express.Router();

const googleClient = new OAuth2Client(process.env.GOOGLE_CLIENT_ID);
const JWT_SECRET = process.env.JWT_SECRET || "fallback_secret_key_change_in_production";

router.post(
  "/google",
  asyncHandler(async (req, res) => {
    const { credential, email: bodyEmail, name: bodyName, google_id: bodyGoogleId } = req.body;

    let email, name, google_id;

    if (credential) {
      try {
        const ticket = await googleClient.verifyIdToken({
          idToken: credential,
          audience: process.env.GOOGLE_CLIENT_ID,
        });
        const payload = ticket.getPayload();
        email = payload.email;
        name = payload.name;
        google_id = payload.sub;
      } catch (err) {
        console.error("JWT verification failed:", err);
        return res.status(401).json({ message: "Invalid Google token" });
      }
    } else if (bodyEmail && bodyName && bodyGoogleId) {
      // Internal/dev flow where we already have verified info
      email = bodyEmail;
      name = bodyName;
      google_id = bodyGoogleId;
    } else {
      return res.status(400).json({ message: "No Google authentication info provided" });
    }

      // 2. Check if user exists in database
      const [users] = await db.query("SELECT * FROM users WHERE email = ? LIMIT 1", [email]);
      
      let user;

      if (users.length > 0) {
        user = users[0];
        // If they exist but don't have a google_id, update them
        if (!user.google_id) {
          await db.query("UPDATE users SET google_id = ? WHERE id = ?", [google_id, user.id]);
          user.google_id = google_id;
        }
      } else {
        // 3. Create new user if they don't exist
        const defaultCurrency = "USD";
        const insertSql = `
          INSERT INTO users (email, name, google_id, currency)
          VALUES (?, ?, ?, ?)
        `;
        const [result] = await db.query(insertSql, [email, name, google_id, defaultCurrency]);
        
        const [newUsers] = await db.query("SELECT * FROM users WHERE id = ?", [result.insertId]);
        user = newUsers[0];
      }

      // 4. Generate JWT for our own session
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

      // 5. Send back user data and token
      res.json({
        user: {
          id: user.id,
          email: user.email,
          name: user.name,
          currency: user.currency,
        },
        token
      });
  })
);

module.exports = router;
