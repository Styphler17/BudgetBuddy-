const express = require("express");
const multer = require("multer");
const path = require("path");
const fs = require("fs");
const BlogController = require("../controllers/BlogController");
const asyncHandler = require("../utils/asyncHandler");

const router = express.Router();

// Multer configuration for blog image uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    const uploadPath = path.join(__dirname, "../../../public/blog");
    if (!fs.existsSync(uploadPath)) {
      fs.mkdirSync(uploadPath, { recursive: true });
    }
    cb(null, uploadPath);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + "-" + Math.round(Math.random() * 1e9);
    cb(null, "blog-" + uniqueSuffix + path.extname(file.originalname));
  }
});

const upload = multer({ 
  storage: storage,
  limits: { fileSize: 5 * 1024 * 1024 }, // 5MB limit
  fileFilter: (req, file, cb) => {
    const allowedTypes = /jpeg|jpg|png|webp|gif/;
    const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
    const mimetype = allowedTypes.test(file.mimetype);
    if (extname && mimetype) {
      return cb(null, true);
    }
    cb(new Error("Only image files are allowed!"));
  }
});

router.get("/", asyncHandler(BlogController.getAll));
router.get("/slug/:slug", asyncHandler(BlogController.getBySlug));
router.get("/:id/related", asyncHandler(BlogController.getRelated));
router.get("/:id", asyncHandler(BlogController.getById));
router.post("/", asyncHandler(BlogController.create));

// Image upload endpoint
router.post("/upload", upload.single("image"), (req, res) => {
  if (!req.file) {
    return res.status(400).json({ message: "No file uploaded" });
  }
  const filePath = `/blog/${req.file.filename}`;
  res.json({ url: filePath });
});

router.put("/:id", asyncHandler(BlogController.update));
router.delete("/:id", asyncHandler(BlogController.delete));

module.exports = router;
