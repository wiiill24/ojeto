<?php
require_once __DIR__ . '/../core/Controller.php';

class ChatController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
        
        $this->view('chat/index', [
            'nombre_usuario' => $nombre_usuario
        ]);
    }
}

