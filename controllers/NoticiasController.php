<?php
require_once 'models/News.php';
require_once 'config/config.php';

class NoticiasController {
    public function newsDelete($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarRedactor();
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];
        $newsId = $segments[2] ?? null;
        if (!$newsId) {
            $response['message'] = 'ID de noticia no proporcionado.';
            echo json_encode($response);
            return;
        }
        try {
            $deletedRows = $this->newsModel->delete($newsId);
            if ($deletedRows > 0) {
                $response['success'] = true;
                $response['message'] = 'Noticia eliminada correctamente.';
            } else {
                $response['message'] = 'No se pudo eliminar la noticia.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Error: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    public function newsUpdate($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarRedactor();
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];
        $newsId = $segments[2] ?? null;
        if (!$newsId) {
            $response['message'] = 'ID de noticia no proporcionado.';
            echo json_encode($response);
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $summary = trim($_POST['summary'] ?? ($_POST['excerpt'] ?? ''));
            $status = $_POST['status'] ?? 'draft';
            if (empty($title) || empty($content)) {
                $response['message'] = 'Título y contenido son obligatorios';
                echo json_encode($response);
                return;
            }
            try {
                $updateData = [
                    'title' => $title,
                    'content' => $content,
                    'summary' => $summary,
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                // Imagen nueva (opcional)
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/news/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $uploadPath = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $updateData['image_url'] = 'uploads/news/' . $fileName;
                    }
                }
                // Video nuevo (opcional)
                if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                    $videoDir = __DIR__ . '/../uploads/news/videos/';
                    if (!is_dir($videoDir)) {
                        mkdir($videoDir, 0755, true);
                    }
                    $videoName = time() . '_' . basename($_FILES['video']['name']);
                    $videoPath = $videoDir . $videoName;
                    if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
                        $updateData['video_url'] = 'uploads/news/videos/' . $videoName;
                    }
                }
                $result = $this->newsModel->update($newsId, $updateData);
                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Noticia actualizada correctamente.';
                } else {
                    $response['message'] = 'Error al actualizar la noticia.';
                }
            } catch (Exception $e) {
                $response['message'] = 'Error: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'Método no permitido.';
        }
        echo json_encode($response);
    }
    private $db;
    private $newsModel;
    public function __construct($database) {
        $this->db = $database;
        $this->newsModel = new News($database);
    }

    public function newsList($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarRedactor();
        $userId = $_SESSION['user']['id'] ?? null;
        $userRole = $_SESSION['user']['role'] ?? null;
        if ($userRole === 'admin') {
            $news = $this->newsModel->getAllWithDetails();
        } else {
            $news = $this->newsModel->getAllWithDetailsByUser($userId);
        }
        $data = [
            'title' => 'Gestionar Noticias',
            'news' => $news
        ];
        $this->render('noticias/news-list', $data);
    }

    public function newsCreate($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarRedactor();
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $summary = trim($_POST['summary'] ?? '');
            $status = $_POST['status'] ?? 'draft';
            $sectionId = $_POST['section_id'] ?? null;
            $author = trim($_POST['author'] ?? '');
            $publicationDate = $_POST['publication_date'] ?? date('Y-m-d H:i:s');
            if (empty($title) || empty($content) || empty($author)) {
                $response['message'] = 'Título, contenido y autor son obligatorios';
                echo json_encode($response);
                return;
            }
            try {
                $newsData = [
                    'title' => $title,
                    'content' => $content,
                    'summary' => $summary,
                    'status' => $status,
                    'section_id' => $sectionId,
                    'author' => $author,
                    'published_at' => $publicationDate,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                // Guardar imagen si se subió
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/news/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $uploadPath = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $newsData['image_url'] = 'uploads/news/' . $fileName;
                    }
                }
                // Guardar video si se subió
                if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                    $videoDir = __DIR__ . '/../uploads/news/videos/';
                    if (!is_dir($videoDir)) {
                        mkdir($videoDir, 0755, true);
                    }
                    $videoName = time() . '_' . basename($_FILES['video']['name']);
                    $videoPath = $videoDir . $videoName;
                    if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
                        $newsData['video_url'] = 'uploads/news/videos/' . $videoName;
                    }
                }
                $newsId = $this->newsModel->create($newsData);
                if ($newsId) {
                    $response['success'] = true;
                    $response['message'] = 'Noticia creada exitosamente.';
                } else {
                    $response['message'] = 'Error al crear la noticia.';
                }
            } catch (Exception $e) {
                $response['message'] = 'Error: ' . $e->getMessage();
            }
            echo json_encode($response);
            return;
        }
        $data = [
            'title' => 'Crear Noticia - Redactor'
        ];
        $this->render('noticias/news-create', $data);
    }

    public function newsEdit($segments = []) {
        require_once __DIR__ . '/../seguridad.php';
        verificarRedactor();
        $newsId = $segments[1] ?? null;
        if (!$newsId) {
            redirect(BASE_URL . 'noticias/news-list');
        }
        $error = '';
        $success = '';
        $news = $this->newsModel->findById($newsId);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            // Aceptar excerpt como summary si summary no existe
            $summary = trim($_POST['summary'] ?? ($_POST['excerpt'] ?? ''));
            $status = $_POST['status'] ?? 'draft';
            if (empty($title) || empty($content)) {
                $error = 'Título y contenido son obligatorios';
            } else {
                try {
                    $updateData = [
                        'title' => $title,
                        'content' => $content,
                        'summary' => $summary,
                        'status' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    // Manejar nuevas imágenes si se subieron
                    if (!empty($_FILES['images']['name'][0])) {
                        // Puedes implementar la función handleImageUploads igual que en AdminController
                        // $images = $this->handleImageUploads($_FILES['images']);
                        // if ($images) {
                        //     $updateData['image_url'] = $images[0];
                        // }
                    }
                    $this->newsModel->update($newsId, $updateData);
                    $success = 'Noticia actualizada exitosamente.';
                    $news = $this->newsModel->findById($newsId);
                } catch (Exception $e) {
                    $error = 'Error al actualizar la noticia: ' . $e->getMessage();
                }
            }
        }
        $data = [
            'title' => 'Editar Noticia - Redactor',
            'news' => $news,
            'error' => $error,
            'success' => $success
        ];
        $this->render('noticias/news-edit', $data);
    }

    private function render($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            include __DIR__ . '/../views/layout/main.php';
        } else {
            echo "Vista no encontrada: {$view}";
        }
    }
}
