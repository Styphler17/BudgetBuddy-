<?php
/**
 * Blog Model
 */

class Blog {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all published blog posts with pagination
     */
    public function getAllPublished($search = '', $tag = '', $limit = null, $offset = 0) {
        $sql = "SELECT * FROM blog_posts WHERE status = 'published'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR excerpt LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($tag)) {
            $sql .= " AND tags LIKE ?";
            $params[] = "%$tag%";
        }

        $sql .= " ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($limit !== null) {
            $stmt->bindValue(count($params) + 1, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(count($params) + 2, (int)$offset, PDO::PARAM_INT);
            // We need to execute without params array if we use bindValue for some
            // So let's bind all
            foreach ($params as $i => $val) {
                $stmt->bindValue($i + 1, $val);
            }
            $stmt->execute();
        } else {
            $stmt->execute($params);
        }
        
        $posts = $stmt->fetchAll();
        
        return array_map([$this, 'formatPost'], $posts);
    }

    /**
     * Count total published posts
     */
    public function countPublished($search = '', $tag = '') {
        $sql = "SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE ? OR excerpt LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($tag)) {
            $sql .= " AND tags LIKE ?";
            $params[] = "%$tag%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'];
    }

    /**
     * Find a blog post by slug
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM blog_posts WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
        
        return $post ? $this->formatPost($post) : null;
    }

    /**
     * Get all blog posts (for admin)
     */
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Format post data (e.g. decode JSON content)
     */
    private function formatPost($post) {
        if (isset($post['content']) && is_string($post['content'])) {
            $decoded = json_decode($post['content'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $post['content'] = $decoded;
            }
            // If it's not valid JSON, we leave it as a string (HTML)
        }
        if (isset($post['tags']) && is_string($post['tags'])) {
            $post['tags'] = explode(',', $post['tags']);
        }
        return $post;
    }

    /**
     * Create new blog post
     */
    public function create($data) {
        $sql = "INSERT INTO blog_posts (admin_id, title, slug, excerpt, cover_image_url, status, content, tags, reading_time, created_at, updated_at) 
                VALUES (:admin_id, :title, :slug, :excerpt, :cover_image_url, :status, :content, :tags, :reading_time, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':admin_id' => $data['admin_id'],
            ':title' => $data['title'],
            ':slug' => $data['slug'],
            ':excerpt' => $data['excerpt'] ?? null,
            ':cover_image_url' => $data['cover_image_url'] ?? null,
            ':status' => $data['status'] ?? 'draft',
            ':content' => $data['content'] ?? '',
            ':tags' => is_array($data['tags'] ?? null) ? implode(',', $data['tags']) : ($data['tags'] ?? ''),
            ':reading_time' => $data['reading_time'] ?? 0
        ]);
    }

    /**
     * Update blog post
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach (['title', 'slug', 'excerpt', 'cover_image_url', 'status', 'reading_time', 'content'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }

        if (isset($data['tags'])) {
            $fields[] = "tags = :tags";
            $params[':tags'] = is_array($data['tags']) ? implode(',', $data['tags']) : $data['tags'];
        }

        if (empty($fields)) return false;

        $sql = "UPDATE blog_posts SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete blog post
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM blog_posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Find post by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();
        return $post ? $this->formatPost($post) : null;
    }
}
