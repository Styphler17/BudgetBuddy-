const mysql = require('mysql2/promise');
require('dotenv').config();

async function migrate() {
  const connection = await mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
  });

  try {
    console.log("Adding google_id column...");
    await connection.execute("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) NULL AFTER email");
  } catch (err) {
    if (err.code === 'ER_DUP_FIELDNAME') console.log("google_id already exists.");
    else throw err;
  }

  try {
    console.log("Modifying password_hash column...");
    await connection.execute("ALTER TABLE users MODIFY COLUMN password_hash VARCHAR(255) NULL");
  } catch (err) {
     throw err;
  }

  console.log("Migration complete.");
  process.exit(0);
}

migrate().catch(err => {
  console.error(err);
  process.exit(1);
});
