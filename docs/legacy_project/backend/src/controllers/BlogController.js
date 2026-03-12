const Blog = require("../models/Blog");
const { estimateReadingTime } = require("../utils/readingTime");
const { generateUniqueSlug } = require("../utils/slug");

class BlogController {
  static async getAll(req, res) {
    const blogs = await Blog.findAll(req.query);
    res.json(blogs);
  }

  static async getBySlug(req, res) {
    const blog = await Blog.findBySlug(req.params.slug);
    if (!blog) return res.status(404).json({ message: "Post not found" });
    if (blog.status !== "published") return res.status(404).json({ message: "Post not published" });
    res.json(blog);
  }

  static async getById(req, res) {
    const blog = await Blog.findById(Number(req.params.id));
    if (!blog) return res.status(404).json({ message: "Post not found" });
    res.json(blog);
  }

  static async getRelated(req, res) {
    const id = Number(req.params.id);
    const limit = Number(req.query.limit || 4);
    const blog = await Blog.findById(id);
    
    if (!blog) return res.status(404).json({ message: "Post not found" });
    if (!blog.tags.length) return res.json([]);

    const blogs = await Blog.findAll({
      tag: blog.tags[0], // Simplified related logic for now
      excludeId: id,
      limit
    });
    res.json(blogs);
  }

  static async create(req, res) {
    const {
      adminId, title, slug, excerpt, coverImageUrl, coverImageAlt,
      status = "draft", contentBlocks = [], tags = [],
      metaTitle, metaDescription, metaKeywords = [],
      featureEmbedUrl, publishedAt
    } = req.body;

    if (!adminId || !title || !contentBlocks.length) {
      return res.status(400).json({ message: "adminId, title and contentBlocks are required" });
    }

    const uniqueSlug = await generateUniqueSlug(title, slug, null);
    const readingTime = estimateReadingTime(contentBlocks);
    const publishTimestamp = status === "published" ? (publishedAt || new Date().toISOString()) : null;

    const blogId = await Blog.create([
      adminId, title, uniqueSlug, excerpt || null, coverImageUrl || null,
      coverImageAlt || null, status, JSON.stringify(contentBlocks),
      Blog.serializeTags(tags), metaTitle || null, metaDescription || null,
      Blog.serializeTags(metaKeywords), readingTime, featureEmbedUrl || null,
      publishTimestamp
    ]);

    const newBlog = await Blog.findById(blogId);
    res.status(201).json(newBlog);
  }

  static async update(req, res) {
    const id = Number(req.params.id);
    const existing = await Blog.findById(id);
    if (!existing) return res.status(404).json({ message: "Post not found" });

    const {
      title, slug, excerpt, coverImageUrl, coverImageAlt,
      status, contentBlocks, tags, metaTitle, metaDescription,
      metaKeywords, featureEmbedUrl, publishedAt
    } = req.body;

    const fields = [];
    const params = [];
    const nextStatus = status || existing.status;

    if (title !== undefined) { fields.push("title = ?"); params.push(title); }
    
    if (slug !== undefined || title !== undefined) {
      const uniqueSlug = await generateUniqueSlug(title || existing.title, slug || existing.slug, id);
      fields.push("slug = ?"); params.push(uniqueSlug);
    }

    if (excerpt !== undefined) { fields.push("excerpt = ?"); params.push(excerpt); }
    if (coverImageUrl !== undefined) { fields.push("cover_image_url = ?"); params.push(coverImageUrl); }
    if (coverImageAlt !== undefined) { fields.push("cover_image_alt = ?"); params.push(coverImageAlt); }
    if (status !== undefined) { fields.push("status = ?"); params.push(status); }

    if (contentBlocks) {
      fields.push("content = ?"); params.push(JSON.stringify(contentBlocks));
      fields.push("reading_time = ?"); params.push(estimateReadingTime(contentBlocks));
    }

    if (tags) { fields.push("tags = ?"); params.push(Blog.serializeTags(tags)); }
    if (metaTitle !== undefined) { fields.push("meta_title = ?"); params.push(metaTitle); }
    if (metaDescription !== undefined) { fields.push("meta_description = ?"); params.push(metaDescription); }
    if (metaKeywords) { fields.push("meta_keywords = ?"); params.push(Blog.serializeTags(metaKeywords)); }
    if (featureEmbedUrl !== undefined) { fields.push("feature_embed_url = ?"); params.push(featureEmbedUrl); }

    if (nextStatus === "published") {
      fields.push("published_at = ?"); params.push(publishedAt || new Date().toISOString());
    } else {
      fields.push("published_at = ?"); params.push(null);
    }

    if (!fields.length) return res.status(400).json({ message: "No fields provided" });

    await Blog.update(id, fields, params);
    const updated = await Blog.findById(id);
    res.json(updated);
  }

  static async delete(req, res) {
    await Blog.delete(Number(req.params.id));
    res.status(204).send();
  }
}

module.exports = BlogController;
