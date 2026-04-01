<?php
// controllers/UserController.php
require_once "Controller.php";

class UserController extends Controller {
    
    public function index() {
        $this->requireRole('admin');
        
        $userModel = new User($this->db);
        $users = $userModel->getAll();
        
        // Utiliser render() au lieu de renderView()
        $this->render("users/index", ['users' => $users]);
    }
    
    public function create() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User($this->db);
            $userModel->username = $_POST['username'];
            $userModel->email = $_POST['email'];
            $userModel->password = $_POST['password'];
            $userModel->role = $_POST['role'];
            $userModel->full_name = $_POST['full_name'];
            
            if ($userModel->create()) {
                $_SESSION['success'] = "Utilisateur créé avec succès";
                $this->redirect("index.php?controller=user&action=index");
            } else {
                $error = "Erreur lors de la création";
                $this->render("users/create", ['error' => $error]);
            }
        } else {
            $this->render("users/create");
        }
    }
    
    public function edit() {
        $this->requireRole('admin');
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect("index.php?controller=user&action=index");
        }
        
        $userModel = new User($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel->id = $id;
            $userModel->username = $_POST['username'];
            $userModel->email = $_POST['email'];
            $userModel->role = $_POST['role'];
            $userModel->full_name = $_POST['full_name'];
            
            if ($userModel->update()) {
                $_SESSION['success'] = "Utilisateur modifié avec succès";
                $this->redirect("index.php?controller=user&action=index");
            } else {
                $error = "Erreur lors de la modification";
                $user = $userModel->findById($id);
                $this->render("users/edit", ['user' => $user, 'error' => $error]);
            }
        } else {
            $user = $userModel->findById($id);
            $this->render("users/edit", ['user' => $user]);
        }
    }
    
    public function delete() {
        $this->requireRole('admin');
        
        $id = $_GET['id'] ?? null;
        if ($id && $id != $_SESSION['user_id']) {
            $userModel = new User($this->db);
            $userModel->id = $id;
            $userModel->delete();
            $_SESSION['success'] = "Utilisateur supprimé avec succès";
        }
        
        $this->redirect("index.php?controller=user&action=index");
    }
}
?>