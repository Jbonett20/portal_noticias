<?php
class Business {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        $businessData = [
            'section_id' => $data['section_id'],
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'mission' => $data['mission'] ?? null,
            'vision' => $data['vision'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_published' => $data['is_published'] ?? 1,
            'created_by' => $data['created_by'] ?? null
        ];
        
        return $this->db->insert('businesses', $businessData);
    }
    
    public function findById($id) {
        $sql = "SELECT b.*, s.title as section_title, s.slug as section_slug,
                       u.full_name as created_by_name
                FROM businesses b
                LEFT JOIN sections s ON b.section_id = s.id
                LEFT JOIN users u ON b.created_by = u.id
                WHERE b.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findBySlug($slug) {
        $sql = "SELECT b.*, s.title as section_title, s.slug as section_slug
                FROM businesses b
                LEFT JOIN sections s ON b.section_id = s.id
                WHERE b.slug = ? AND b.is_published = 1";
        
        return $this->db->fetch($sql, [$slug]);
    }
    
    public function getAll($limit = null, $offset = 0, $sectionId = null) {
        $sql = "SELECT b.*, s.title as section_title 
                FROM businesses b
                LEFT JOIN sections s ON b.section_id = s.id";
        
        $params = [];
        
        if ($sectionId) {
            $sql .= " WHERE b.section_id = ?";
            $params[] = $sectionId;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getPublished($limit = null, $offset = 0, $sectionId = null) {
        $sql = "SELECT b.*, s.title as section_title 
                FROM businesses b
                LEFT JOIN sections s ON b.section_id = s.id
                WHERE b.is_published = 1";
        
        $params = [];
        
        if ($sectionId) {
            $sql .= " AND b.section_id = ?";
            $params[] = $sectionId;
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function search($query, $limit = 10) {
        $sql = "SELECT b.*, s.title as section_title,
                       MATCH(b.name, b.short_description, b.description) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
                FROM businesses b
                LEFT JOIN sections s ON b.section_id = s.id
                WHERE b.is_published = 1 
                AND MATCH(b.name, b.short_description, b.description) AGAINST(? IN NATURAL LANGUAGE MODE)
                ORDER BY relevance DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$query, $query, $limit]);
    }
    
    public function update($id, $data) {
        $updateData = [
            'section_id' => $data['section_id'],
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'mission' => $data['mission'] ?? null,
            'vision' => $data['vision'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_published' => $data['is_published'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->update('businesses', $updateData, 'id = ?', $id);
    }
    
    public function delete($id) {
        return $this->db->delete('businesses', 'id = ?', $id);
    }
    
    public function count($sectionId = null) {
        if ($sectionId) {
            return $this->db->count('businesses', 'section_id = ?', $sectionId);
        }
        return $this->db->count('businesses');
    }
    
    public function getImages($businessId) {
        $sql = "SELECT * FROM business_images 
                WHERE business_id = ? 
                ORDER BY display_order ASC, uploaded_at DESC";
        
        return $this->db->fetchAll($sql, [$businessId]);
    }
    
    public function addImage($businessId, $imagePath, $caption = null, $isFeatured = 0, $uploadedBy = null) {
        $imageData = [
            'business_id' => $businessId,
            'image_path' => $imagePath,
            'caption' => $caption,
            'is_featured' => $isFeatured,
            'display_order' => 0,
            'uploaded_by' => $uploadedBy
        ];
        
        return $this->db->insert('business_images', $imageData);
    }
    
    private function generateSlug($name) {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}
?>