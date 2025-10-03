<?php
class News {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        $newsData = [
            'title' => $data['title'],
            'content' => $data['content'],
            'summary' => $data['excerpt'] ?? '',
            'slug' => $this->generateSlug($data['title']),
            'author_id' => $data['author_id'],
            'is_published' => ($data['status'] === 'published') ? 1 : 0,
            'published_at' => ($data['status'] === 'published') ? date('Y-m-d H:i:s') : null,
            'featured_image' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('news', $newsData);
    }
    
    public function findById($id) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       0 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       0 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.slug = ?";
        return $this->db->fetch($sql, [$slug]);
    }
    
    public function getAll($limit = null, $offset = 0, $publishedOnly = false) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       0 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id";
        
        $params = [];
        if ($publishedOnly) {
            $sql .= " WHERE n.is_published = 1";
        }
        
        $sql .= " ORDER BY n.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getPublished($limit = null, $offset = 0) {
        return $this->getAll($limit, $offset, true);
    }
    
    public function getLatest($limit = 5) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       0 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1
                ORDER BY n.created_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function getFeatured($limit = 3) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       1 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1
                ORDER BY n.created_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function search($query, $limit = 10) {
        $sql = "SELECT n.*, COALESCE(u.full_name, u.username, 'Admin') as author_name,
                       n.summary as excerpt,
                       CASE WHEN n.is_published = 1 THEN 'published' ELSE 'draft' END as status,
                       0 as featured, 'general' as category, 0 as views
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 
                AND (n.title LIKE ? OR n.content LIKE ? OR n.summary LIKE ?)
                ORDER BY n.created_at DESC 
                LIMIT ?";
        
        $searchTerm = '%' . $query . '%';
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }
    
    public function update($id, $data) {
        $updateData = [
            'title' => $data['title'],
            'content' => $data['content'],
            'summary' => $data['excerpt'] ?? '',
            'is_published' => ($data['status'] === 'published') ? 1 : 0,
            'published_at' => ($data['status'] === 'published') ? date('Y-m-d H:i:s') : null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('news', 'id = ?', [$id]);
    }
    
    public function publish($id) {
        $updateData = [
            'is_published' => 1,
            'published_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', [$id]);
    }
    
    public function unpublish($id) {
        $updateData = [
            'is_published' => 0,
            'published_at' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', [$id]);
    }
    
    public function incrementViews($id) {
        return true;
    }
    
    public function count($publishedOnly = false) {
        if ($publishedOnly) {
            $sql = "SELECT COUNT(*) as total FROM news WHERE is_published = 1";
        } else {
            $sql = "SELECT COUNT(*) as total FROM news";
        }
        $result = $this->db->fetch($sql);
        return $result ? $result['total'] : 0;
    }
    
    public function getImages($newsId) {
        $sql = "SELECT * FROM news_images WHERE news_id = ? ORDER BY id ASC";
        return $this->db->fetchAll($sql, [$newsId]);
    }
    
    public function addImage($newsId, $imagePath, $caption = null, $uploadedBy = null) {
        $imageData = [
            'news_id' => $newsId,
            'image_path' => $imagePath,
            'caption' => $caption,
            'uploaded_by' => $uploadedBy,
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert('news_images', $imageData);
    }
    
    private function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function slugExists($slug) {
        $sql = "SELECT COUNT(*) FROM news WHERE slug = ?";
        return $this->db->queryFirstField($sql, [$slug]) > 0;
    }
}
?>
