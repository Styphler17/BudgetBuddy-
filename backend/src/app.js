require("dotenv").config();
const express = require("express");
const cors = require("cors");

const blogsRouter = require("./routes/blogs");

const app = express();

const corsOptions = {
  origin: process.env.CLIENT_ORIGIN || "*",
  credentials: false
};

app.use(cors(corsOptions));
app.use(express.json());

app.get("/health", (_req, res) => {
  res.json({ status: "ok" });
});

app.use("/api/blogs", blogsRouter);

app.use((_req, res) => {
  res.status(404).json({ message: "Not found" });
});

const port = process.env.PORT || 5000;
app.listen(port, () => {
  console.log(`BudgetBuddy API running on http://localhost:${port}`);
});
