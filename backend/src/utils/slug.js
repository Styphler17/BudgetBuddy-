const slugify = require("slugify");
const db = require("../db");

async function slugExists(slug, excludeId) {
  const params = [slug];
  let sql = "SELECT id FROM blog_posts WHERE slug = ?";
  if (excludeId) {
    sql += " AND id <> ?";
    params.push(excludeId);
  }
  const [rows] = await db.query(sql, params);
  return rows.length > 0;
}

async function generateUniqueSlug(title, providedSlug, excludeId) {
  const baseInput = (providedSlug || title || "").trim();
  let baseSlug = slugify(baseInput, { lower: true, strict: true });

  if (!baseSlug) {
    baseSlug = `post-${Date.now()}`;
  }

  let candidate = baseSlug;
  let counter = 1;
  while (await slugExists(candidate, excludeId)) {
    candidate = `${baseSlug}-${counter++}`;
  }

  return candidate;
}

module.exports = { generateUniqueSlug };
