const mysql = require('mysql2/promise');
require('dotenv').config({ path: '../.env' });

async function fixTable() {
  const connection = await mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
  });

  try {
    await connection.query("ALTER TABLE accounts MODIFY COLUMN type ENUM('checking', 'savings', 'credit', 'investment', 'cash') NOT NULL");
    console.log("Table 'accounts' successfully updated with 'cash' type.");
  } catch (err) {
    console.error("Failed to update 'accounts' table:", err);
  } finally {
    await connection.end();
  }
}

fixTable();
