const db = require("../db");

const SUMMARY_COLUMNS = `
  id, admin_id, title, slug, excerpt, cover_image_url, cover_image_alt, 
  status, tags, meta_title, meta_description, meta_keywords, 
  reading_time, feature_embed_url, published_at, created_at, updated_at
`;

const DETAIL_COLUMNS = `${SUMMARY_COLUMNS}, content`;

class Blog {
  static parseTags(value) {
    return typeof value === "string"
      ? value.split(",").map((tag) => tag.trim()).filter(Boolean)
      : [];
  }

  static parseJson(value, fallback) {
    if (!value) return fallback;
    try {
      return JSON.parse(value);
    } catch (error) {
      return fallback;
    }
  }

  static serializeTags(tags = []) {
    return Array.isArray(tags) ? tags.filter(Boolean).join(",") : "";
  }

  static toSummary(row) {
    if (!row) return null;
    return {
      id: row.id,
      title: row.title,
      slug: row.slug,
      excerpt: row.excerpt,
      coverImageUrl: row.cover_image_url,
      coverImageAlt: row.cover_image_alt,
      status: row.status,
      tags: Blog.parseTags(row.tags),
      readingTime: row.reading_time ?? 1,
      publishedAt: row.published_at,
      createdAt: row.created_at,
      updatedAt: row.updated_at,
      authorId: row.admin_id
    };
  }

  static toDetail(row) {
    if (!row) return null;
    return {
      ...Blog.toSummary(row),
      contentBlocks: Blog.parseJson(row.content, []),
      metaTitle: row.meta_title,
      metaDescription: row.meta_description,
      metaKeywords: Blog.parseTags(row.meta_keywords),
      featureEmbedUrl: row.feature_embed_url
    };
  }

  static async findAll(filters = {}) {
    const { status = "published", limit, offset, search, tag, excludeId } = filters;
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
    if (where.length) sql += ` WHERE ${where.join(" AND ")}`;
    
    sql += (status === "published") ? " ORDER BY published_at DESC" : " ORDER BY updated_at DESC";

    if (limit) {
      sql += " LIMIT ?";
      params.push(Number(limit));
      if (offset) {
        sql += " OFFSET ?";
        params.push(Number(offset));
      }
    }

    const [rows] = await db.query(sql, params);
    return rows.map(Blog.toSummary);
  }

  static async findBySlug(slug) {
    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE slug = ? LIMIT 1`,
      [slug]
    );
    return Blog.toDetail(rows[0]);
  }

  static async findById(id) {
    const [rows] = await db.query(
      `SELECT ${DETAIL_COLUMNS} FROM blog_posts WHERE id = ? LIMIT 1`,
      [id]
    );
    return Blog.toDetail(rows[0]);
  }

  static async create(data) {
    const insertSql = `
      INSERT INTO blog_posts (
        admin_id, title, slug, excerpt, cover_image_url, cover_image_alt, 
        status, content, tags, meta_title, meta_description, meta_keywords, 
        reading_time, feature_embed_url, published_at
      )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `;
    const [result] = await db.query(insertSql, data);
    return result.insertId;
  }

  static async update(id, fields, params) {
    const sql = `
      UPDATE blog_posts 
      SET ${fields.join(", ")}, updated_at = CURRENT_TIMESTAMP 
      WHERE id = ?
    `;
    params.push(id);
    await db.query(sql, params);
    return true;
  }

  static async delete(id) {
    await db.query("DELETE FROM blog_posts WHERE id = ?", [id]);
    return true;
  }
}

module.exports = Blog;
