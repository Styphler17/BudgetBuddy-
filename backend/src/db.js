const mysql = require("mysql2/promise");

let pool;

function getPool() {
  if (!pool) {
    pool = mysql.createPool({
      host: process.env.DB_HOST,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME,
      waitForConnections: true,
      connectionLimit: Number(process.env.DB_CONNECTION_LIMIT || 10),
      charset: "utf8mb4_general_ci"
    });
  }

  return pool;
}

module.exports = {
  query: async (...args) => {
    const activePool = getPool();
    return activePool.query(...args);
  },
  getConnection: async () => {
    const activePool = getPool();
    return activePool.getConnection();
  }
};
