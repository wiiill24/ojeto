<?php

class Controller {
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $view);
        }
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE;
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
}
?>