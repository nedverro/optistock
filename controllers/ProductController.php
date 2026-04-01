<?php
// controllers/ProductController.php
require_once "Controller.php";

class ProductController extends Controller {
    
   public function index() {
    $this->requireAuth();
    
    $productModel = new Product($this->db);
    $categoryModel = new Category($this->db);
    $supplierModel = new Supplier($this->db);
    
    $filters = [
        'category_id' => $_GET['category_id'] ?? null,
        'supplier_id' => $_GET['supplier_id'] ?? null,
        'search' => $_GET['search'] ?? null
    ];
    
    // Si l'utilisateur est fournisseur, ne voir que ses produits
    if ($_SESSION['user_role'] === 'supplier') {
        $userModel = new User($this->db);
        $user = $userModel->findById($_SESSION['user_id']);
        if ($user && !empty($user['supplier_id'])) {
            $filters['supplier_id'] = $user['supplier_id'];
            // Le fournisseur ne voit que ses produits, pas de filtre fournisseur
            unset($_GET['supplier_id']);
        }
        // Le fournisseur n'a pas besoin des catégories
        $categories = [];
        $suppliers = [];
    } else {
        $categories = $categoryModel->getAll();
        $suppliers = $supplierModel->getAll();
    }
    
    $products = $productModel->getAll($filters);
    
    $this->render("products/index", [
        'products' => $products,
        'categories' => $categories,
        'suppliers' => $suppliers,
        'filters' => $filters
    ]);
}
    
    public function create() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product($this->db);
            $productModel->name = $_POST['name'];
            $productModel->description = $_POST['description'];
            $productModel->purchase_price = $_POST['purchase_price'];
            $productModel->selling_price = $_POST['selling_price'];
            $productModel->quantity = $_POST['quantity'];
            $productModel->alert_threshold = $_POST['alert_threshold'];
            $productModel->category_id = ($_POST['category_id'] ?? '') ?: null;
            $productModel->supplier_id = ($_POST['supplier_id'] ?? '') ?: null;
            
            if ($productModel->create()) {
                $_SESSION['success'] = "Produit créé avec succès";
                $this->redirect("index.php?controller=product&action=index");
            } else {
                $error = "Erreur lors de la création du produit";
                $this->render("products/create", $this->productFormData(['error' => $error]));
            }
        } else {
            $this->render("products/create", $this->productFormData());
        }
    }

    // Categories and suppliers for the product form dropdowns
    private function productFormData($extra = []) {
        return array_merge([
            'categories' => (new Category($this->db))->getAll(),
            'suppliers'  => (new Supplier($this->db))->getAll(),
        ], $extra);
    }
    
    public function edit() {
    $this->requireRole('admin');
    
    $id = $_GET['id'] ?? null;
    if (!$id) {
        $this->redirect("index.php?controller=product&action=index");
    }
    
    $productModel = new Product($this->db);
    $categoryModel = new Category($this->db);
    $supplierModel = new Supplier($this->db);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productModel->id = $id;
        $productModel->name = $_POST['name'];
        $productModel->description = $_POST['description'];
        $productModel->purchase_price = $_POST['purchase_price'];
        $productModel->selling_price = $_POST['selling_price'];
        $productModel->quantity = $_POST['quantity'];
        $productModel->alert_threshold = $_POST['alert_threshold'];
        $productModel->category_id = $_POST['category_id'] ?: null;
        $productModel->supplier_id = $_POST['supplier_id'] ?: null;
        
        if ($productModel->update()) {
            $_SESSION['success'] = "Produit modifié avec succès";
            $this->redirect("index.php?controller=product&action=index");
        } else {
            $error = "Erreur lors de la modification";
        }
    }
    
    // Récupérer les données pour le formulaire
    $product = $productModel->findById($id);
    $categories = $categoryModel->getAll();
    $suppliers = $supplierModel->getAll();
    
    $this->render("products/edit", [
        'product' => $product,
        'categories' => $categories,
        'suppliers' => $suppliers,
        'error' => $error ?? null
    ]);
}
    
    public function delete() {
        $this->requireRole('admin');
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $productModel = new Product($this->db);
            $productModel->id = $id;
            $productModel->delete();
            $_SESSION['success'] = "Produit supprimé avec succès";
        }
        
        $this->redirect("index.php?controller=product&action=index");
    }
    
    public function view() {
        $this->requireAuth();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect("index.php?controller=product&action=index");
        }
        
        $productModel = new Product($this->db);
        $product = $productModel->findById($id);
        
        $this->render("products/view", ['product' => $product]);
    }

  
public function lowStock() {
    $this->requireAuth();
    
    if ($_SESSION['user_role'] !== 'supplier') {
        $this->redirect("index.php?controller=product&action=index");
    }
    
    $productModel = new Product($this->db);
    
    $userModel = new User($this->db);
    $user = $userModel->findById($_SESSION['user_id']);
    $supplier_id = $user['supplier_id'] ?? null;
    
    if ($supplier_id) {
        $filters = ['supplier_id' => $supplier_id];
        $allProducts = $productModel->getAll($filters);
        
        $lowStockProducts = array_filter($allProducts, function($product) {
            return $product['quantity'] <= $product['alert_threshold'];
        });
    } else {
        $lowStockProducts = [];
    }
    
    $this->render("products/lowstock", ['products' => $lowStockProducts]);
}

}
?>