const express = require("express");
const db = require("../db");
const { estimateReadingTime } = require("../utils/readingTime");
const { generateUniqueSlug } = require("../utils/slug");

const router = express.Router();

const SUMMARY_COLUMNS = `
  id,
  admin_id,
  title,
  slug,
  excerpt,
  cover_image_url,
  cover_image_alt,
  status,
  tags,
  meta_title,
  meta_description,
  meta_keywords,
  reading_time,
  feature_embed_url,
  published_at,
  created_at,
  updated_at
`;

const DETAIL_COLUMNS = `${SUMMARY_COLUMNS}, content`;

const serializeTags = (tags = []) =>
  Array.isArray(tags) ? tags.filter(Boolean).join(",") : "";

const serializeKeywords = (keywords = []) =>
  Array.isArray(keywords) ? keywords.filter(Boolean).join(",") : "";

const parseTags = (value) =>
  typeof value === "string"
    ? value
        .split(",")
        .map((tag) => tag.trim())
        .filter(Boolean)
    : [];

const parseJson = (value, fallback) => {
  if (!value) return fallback;
  try {
    return JSON.parse(value);
  } catch (error) {
    console.warn("Failed to parse JSON column", error);
    return fallback;
  }
};

const toSummary = (row) => ({
  id: row.id,
  title: row.title,
  slug: row.slug,
  excerpt: row.excerpt,
  coverImageUrl: row.cover_image_url,
  coverImageAlt: row.cover_image_alt,
  status: row.status,
  tags: parseTags(row.tags),
  readingTime: row.reading_time ?? 1,
  publishedAt: row.published_at,
  createdAt: row.created_at,
  updatedAt: row.updated_at,
  authorId: row.admin_id
});

const toDetail = (row) => ({
  ...toSummary(row),
  contentBlocks: parseJson(row.content, []),
  metaTitle: row.meta_title,
  metaDescription: row.meta_description,
  metaKeywords: parseTags(row.meta_keywords),
  featureEmbedUrl: row.feature_embed_url
});

router.get("/", async (req, res) => {
  try {
    const {
      status = "published",
      limit,
      offset,
      search,
      tag,
      excludeId
    } = req.query;

    const where = [];
    const params = [];

    if (status && status !== "all") {
      where.push("status = ?");
      params.push(status);
    }

    if (search) {
      where.push("(title LIKE ? OR excerpt LIKE ? OR meta_title LIKE ?)");
      params.push(`%${search}%`, `%${search}%`, `%${search}%`);
    }

    if (tag) {
      where.push("FIND_IN_SET(?, tags)");
      params.push(tag);
    }

    if (excludeId) {
      where.push("id <> ?");
      params.push(Number(excludeId));
    }

    let sql = `SELECT ${SUMMARY_COLUMNS} FROM blog_posts`;
    if (where.length) {
      sql += ` WHERE ${where.join(" AND ")}`;
    }

    if (status === "published") {
      sql += " ORDER BY published_at DESC";
    } else {
      sql += " ORDER BY updated_at DESC";
    }

    if (limit) {
      sql += " LIMIT ?";
      params.push(Number(limit));
      if (offset) {
        sql += " OFFSET ?";
        params.push(Number(offset));
      }
    } else if (offset) {
      sql += " LIMIT 18446744073709551615 OFFSET ?";
      params.push(Number(offset));
    }

    const [rows] = await db.query(sql, params);
    res.json(rows.map(toSummary));
  } catch (error) {
    console.error("Failed to list blogs:", error);
    res.status(500).json({ message: "Unable to load blog posts" });
  }
});

router.get("/slug/:slug", async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE slug = ? LIMIT 1`,
      [req.params.slug]
    );
    if (!rows.length) {
      return res.status(404).json({ message: "Post not found" });
    }
    const row = rows[0];
    if (row.status !== "published") {
      return res.status(404).json({ message: "Post not published" });
    }

    res.json(toDetail(row));
  } catch (error) {
    console.error("Failed to fetch blog by slug:", error);
    res.status(500).json({ message: "Unable to load blog post" });
  }
});

router.get("/:id/related", async (req, res) => {
  try {
    const id = Number(req.params.id);
    const limit = Number(req.query.limit || 4);

    const [currentRows] = await db.query(
      `SELECT ${SUMMARY_COLUMNS}, content FROM blog_posts WHERE id = ? LIMIT 1`,
      [id]
    );
    if (!currentRows.length) {
      return res.status(404).json({ message: "Post not found" });
    }

    const current = currentRows[0];
    const currentTags = parseTags(current.tags).map((tag) => tag.toLowerCase());

    if (!currentTags.length) {
      return res.json([]);
    }

    const placeholders = currentTags.map(() => "FIND_IN_SET(?, tags)").join(" OR ");
    const params = [...currentTags, id, limit];

    const [rows] = await db.query(
      `
        SELECT ${SUMMARY_COLUMNS}
        FROM blog_posts
        WHERE status = 'published'
          AND id <> ?
          AND (${placeholders})
        ORDER BY published_at DESC
        LIMIT ?
      `,
      [id, ...currentTags, limit]
    );

    res.json(rows.map(toSummary));
  } catch (error) {
    console.error("Failed to fetch related posts:", error);
    res.status(500).json({ message: "Unable to load related content" });
  }
});

router.get("/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE id = ? LIMIT 1`,
      [id]
    );
    if (!rows.length) {
      return res.status(404).json({ message: "Post not found" });
    }
    res.json(toDetail(rows[0]));
  } catch (error) {
    console.error("Failed to fetch blog by id:", error);
    res.status(500).json({ message: "Unable to load blog post" });
  }
});

router.post("/", async (req, res) => {
  try {
    const {
      adminId,
      title,
      slug,
      excerpt,
      coverImageUrl,
      coverImageAlt,
      status = "draft",
      contentBlocks = [],
      tags = [],
      metaTitle,
      metaDescription,
      metaKeywords = [],
      featureEmbedUrl,
      publishedAt
    } = req.body;

    if (!adminId || !title || !contentBlocks.length) {
      return res.status(400).json({
        message: "adminId, title and contentBlocks are required"
      });
    }

    const uniqueSlug = await generateUniqueSlug(title, slug, null);
    const readingTime = estimateReadingTime(contentBlocks);
    const publishTimestamp =
      status === "published"
        ? publishedAt || new Date().toISOString()
        : null;

    const insertSql = `
      INSERT INTO blog_posts (
        admin_id,
        title,
        slug,
        excerpt,
        cover_image_url,
        cover_image_alt,
        status,
        content,
        tags,
        meta_title,
        meta_description,
        meta_keywords,
        reading_time,
        feature_embed_url,
        published_at
      )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;

    const params = [
      adminId,
      title,
      uniqueSlug,
      excerpt ?? null,
      coverImageUrl ?? null,
      coverImageAlt ?? null,
      status,
      JSON.stringify(contentBlocks),
      serializeTags(tags),
      metaTitle ?? null,
      metaDescription ?? null,
      serializeKeywords(metaKeywords),
      readingTime,
      featureEmbedUrl ?? null,
      publishTimestamp
    ];

    const [result] = await db.query(insertSql, params);
    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE id = ? LIMIT 1`,
      [result.insertId]
    );

    res.status(201).json(toDetail(rows[0]));
  } catch (error) {
    console.error("Failed to create blog:", error);
    res.status(500).json({ message: "Unable to create blog post" });
  }
});

router.put("/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    const {
      title,
      slug,
      excerpt,
      coverImageUrl,
      coverImageAlt,
      status,
      contentBlocks,
      tags,
      metaTitle,
      metaDescription,
      metaKeywords,
      featureEmbedUrl,
      publishedAt
    } = req.body;

    const [existingRows] = await db.query(
      `SELECT title, slug, status, content FROM blog_posts WHERE id = ? LIMIT 1`,
      [id]
    );
    if (!existingRows.length) {
      return res.status(404).json({ message: "Post not found" });
    }

    const existing = existingRows[0];
    const nextStatus = status || existing.status;

    const updateFields = [];
    const params = [];

    if (title !== undefined) {
      updateFields.push("title = ?");
      params.push(title);
    }

    if (slug !== undefined || title !== undefined) {
      const baseTitle = title ?? existing.title;
      const requestedSlug = slug ?? existing.slug;
      const uniqueSlug = await generateUniqueSlug(baseTitle, requestedSlug, id);
      updateFields.push("slug = ?");
      params.push(uniqueSlug);
    }

    if (excerpt !== undefined) {
      updateFields.push("excerpt = ?");
      params.push(excerpt);
    }

    if (coverImageUrl !== undefined) {
      updateFields.push("cover_image_url = ?");
      params.push(coverImageUrl);
    }

    if (coverImageAlt !== undefined) {
      updateFields.push("cover_image_alt = ?");
      params.push(coverImageAlt);
    }

    if (status !== undefined) {
      updateFields.push("status = ?");
      params.push(status);
    }

    if (contentBlocks) {
      updateFields.push("content = ?");
      params.push(JSON.stringify(contentBlocks));
      const readingTime = estimateReadingTime(contentBlocks);
      updateFields.push("reading_time = ?");
      params.push(readingTime);
    }

    if (tags) {
      updateFields.push("tags = ?");
      params.push(serializeTags(tags));
    }

    if (metaTitle !== undefined) {
      updateFields.push("meta_title = ?");
      params.push(metaTitle);
    }

    if (metaDescription !== undefined) {
      updateFields.push("meta_description = ?");
      params.push(metaDescription);
    }

    if (metaKeywords) {
      updateFields.push("meta_keywords = ?");
      params.push(serializeKeywords(metaKeywords));
    }

    if (featureEmbedUrl !== undefined) {
      updateFields.push("feature_embed_url = ?");
      params.push(featureEmbedUrl);
    }

    if (nextStatus === "published") {
      updateFields.push("published_at = ?");
      params.push(publishedAt || new Date().toISOString());
    } else if (nextStatus !== "published") {
      updateFields.push("published_at = NULL");
    }

    if (!updateFields.length) {
      return res.status(400).json({ message: "No fields provided" });
    }

    params.push(id);

    const updateSql = `
      UPDATE blog_posts
      SET ${updateFields.join(", ")}, updated_at = CURRENT_TIMESTAMP
      WHERE id = ?
    `;

    await db.query(updateSql, params);

    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE id = ? LIMIT 1`,
      [id]
    );
    res.json(toDetail(rows[0]));
  } catch (error) {
    console.error("Failed to update blog:", error);
    res.status(500).json({ message: "Unable to update blog post" });
  }
});

router.delete("/:id", async (req, res) => {
  try {
    const id = Number(req.params.id);
    await db.query("DELETE FROM blog_posts WHERE id = ?", [id]);
    res.status(204).send();
  } catch (error) {
    console.error("Failed to delete blog:", error);
    res.status(500).json({ message: "Unable to delete blog post" });
  }
});

module.exports = router;
