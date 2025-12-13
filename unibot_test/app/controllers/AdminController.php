<?php
require_once __DIR__ . '/../core/Controller.php';

class AdminController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->requireAdmin();
        
        $this->view('admin/dashboard', [
            'nombre_usuario' => $_SESSION['nombre_usuario']
        ]);
    }
    
    public function preguntas() {
        $this->requireAuth();
        $this->requireAdmin();
        
        require_once __DIR__ . '/../../config/database.php';
        $conn = getConnection();
        
        $sql = "SELECT * FROM preguntas_frecuentes ORDER BY id DESC LIMIT 100";
        $result = $conn->query($sql);
        $preguntas = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $preguntas[] = $row;
            }
        }
        
        $conn->close();
        
        $this->view('admin/preguntas', [
            'nombre_usuario' => $_SESSION['nombre_usuario'],
            'preguntas' => $preguntas
        ]);
    }
    
    private function requireAdmin() {
        if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
            header('Location: /chat');
            exit;
        }
    }
}
?>
