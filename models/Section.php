<?php
class Section {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($data) {
        $sectionData = [
            'slug' => $this->generateSlug($data['title']),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0
        ];
        
        return $this->db->insert('sections', $sectionData);
    }
    
    public function findById($id) {
        return $this->db->fetch("SELECT * FROM sections WHERE id = ?", [$id]);
    }
    
    public function findBySlug($slug) {
        return $this->db->fetch("SELECT * FROM sections WHERE slug = ?", [$slug]);
    }
    
    public function getAll($orderBy = 'sort_order') {
        return $this->db->fetchAll("SELECT * FROM sections ORDER BY {$orderBy} ASC");
    }
    
    public function getAllWithBusinessCount() {
        $sql = "SELECT s.*, COUNT(b.id) as business_count 
                FROM sections s 
                LEFT JOIN businesses b ON s.id = b.section_id AND b.is_published = 1
                GROUP BY s.id 
                ORDER BY s.sort_order ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function update($id, $data) {
        $updateData = [
            'slug' => $this->generateSlug($data['title']),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0
        ];
        
        return $this->db->update('sections', $updateData, 'id = ?', $id);
    }
    
    public function delete($id) {
        return $this->db->delete('sections', 'id = ?', $id);
    }
    
    public function count() {
        return $this->db->count('sections');
    }
    
    private function generateSlug($title) {
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    public function getBusinesses($sectionId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM businesses WHERE section_id = ? AND is_published = 1 
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$sectionId, $limit, $offset]);
        }
        
        return $this->db->fetchAll($sql, [$sectionId]);
    }
    
    public function findAll() {
        return $this->db->fetchAll("SELECT * FROM sections ORDER BY sort_order ASC, title ASC");
    }
}
?>