<?php
class News {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        $newsData = [
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'],
            'published_at' => $data['published_at'] ?? null,
            'is_published' => $data['is_published'] ?? 0,
            'author_id' => $data['author_id'] ?? null,
            'featured_image' => $data['featured_image'] ?? null
        ];
        
        return $this->db->insert('news', $newsData);
    }
    
    public function findById($id) {
        $sql = "SELECT n.*, u.full_name as author_name, u.username as author_username
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT n.*, u.full_name as author_name, u.username as author_username
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.slug = ? AND n.is_published = 1";
        
        $news = $this->db->fetch($sql, [$slug]);
        
        // Incrementar vistas
        if ($news) {
            $this->incrementViews($news['id']);
            $news['views']++;
        }
        
        return $news;
    }
    
    public function getAll($limit = null, $offset = 0, $publishedOnly = false) {
        $sql = "SELECT n.*, u.full_name as author_name 
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id";
        
        $params = [];
        
        if ($publishedOnly) {
            $sql .= " WHERE n.is_published = 1 AND n.published_at <= NOW()";
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
        $sql = "SELECT n.*, u.full_name as author_name 
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 AND n.published_at <= NOW()
                ORDER BY n.published_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function getFeatured($limit = 3) {
        $sql = "SELECT n.*, u.full_name as author_name 
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 AND n.published_at <= NOW()
                AND n.featured_image IS NOT NULL
                ORDER BY n.views DESC, n.published_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function search($query, $limit = 10) {
        $sql = "SELECT n.*, u.full_name as author_name,
                       MATCH(n.title, n.summary, n.content) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 
                AND MATCH(n.title, n.summary, n.content) AGAINST(? IN NATURAL LANGUAGE MODE)
                ORDER BY relevance DESC, n.published_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$query, $query, $limit]);
    }
    
    public function update($id, $data) {
        $updateData = [
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'],
            'published_at' => $data['published_at'] ?? null,
            'is_published' => $data['is_published'] ?? 0,
            'featured_image' => $data['featured_image'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', $id);
    }
    
    public function delete($id) {
        return $this->db->delete('news', 'id = ?', $id);
    }
    
    public function publish($id) {
        $updateData = [
            'is_published' => 1,
            'published_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', $id);
    }
    
    public function unpublish($id) {
        $updateData = [
            'is_published' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('news', $updateData, 'id = ?', $id);
    }
    
    public function incrementViews($id) {
        $sql = "UPDATE news SET views = views + 1 WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    public function count($publishedOnly = false) {
        if ($publishedOnly) {
            return $this->db->count('news', 'is_published = 1');
        }
        return $this->db->count('news');
    }
    
    public function getImages($newsId) {
        $sql = "SELECT * FROM news_images 
                WHERE news_id = ? 
                ORDER BY display_order ASC, uploaded_at DESC";
        
        return $this->db->fetchAll($sql, [$newsId]);
    }
    
    public function addImage($newsId, $imagePath, $caption = null, $uploadedBy = null) {
        $imageData = [
            'news_id' => $newsId,
            'image_path' => $imagePath,
            'caption' => $caption,
            'display_order' => 0,
            'uploaded_by' => $uploadedBy
        ];
        
        return $this->db->insert('news_images', $imageData);
    }
    
    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
?>