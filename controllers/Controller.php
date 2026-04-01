<?php
// controllers/Controller.php
abstract class Controller {
    protected $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Cette méthode inclut le layout (menu + contenu)
    protected function render($view, $data = []) {
        extract($data);
        $viewFile = dirname(__DIR__) . "/views/" . $view . ".php";
        
        if (file_exists($viewFile)) {
            // Inclure le layout qui lui-même inclura la vue
            require_once dirname(__DIR__) . "/views/layouts/main.php";
        } else {
            die("View not found: " . $viewFile);
        }
    }

    // Cette méthode est pour les pages sans layout (comme login)
    protected function renderView($view, $data = []) {
        extract($data);
        $viewFile = dirname(__DIR__) . "/views/" . $view . ".php";
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $viewFile);
        }
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect("index.php?controller=auth&action=login");
        }
    }

    protected function requireRole($role) {
        $this->requireAuth();
        if (!$this->hasRole($role)) {
            $this->redirect("index.php?controller=dashboard&action=index");
        }
    }
}
?>