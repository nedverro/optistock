<?php
// controllers/AuthController.php
require_once "Controller.php";

class AuthController extends Controller {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userModel = new User($this->db);
            $user = $userModel->findByUsername($username);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];
                
                $this->redirect("index.php?controller=dashboard&action=index");
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect";
                $this->renderView("auth/login", ['error' => $error]);
            }
        } else {
            $this->renderView("auth/login");
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect("index.php?controller=auth&action=login");
    }
}
?>