<?php
// controllers/SaleController.php
require_once "Controller.php";

class SaleController extends Controller {
    
    public function pos() {
        $this->requireAuth();
        
        $productModel = new Product($this->db);
        $products = $productModel->getAll();
        
        // Utiliser render() au lieu de renderView()
        $this->render("sales/pos", ['products' => $products]);
    }
    
    public function processSale() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = json_decode($_POST['cart'], true);
            
            if (empty($cart)) {
                echo json_encode(['success' => false, 'message' => 'Panier vide']);
                return;
            }
            
            $saleModel = new Sale($this->db);
            $productModel = new Product($this->db);
            
            // Never trust the price or stock sent by the browser:
            // reload each product from the database and validate the quantity.
            $total = 0;
            foreach ($cart as $i => $item) {
                $product = $productModel->findById((int)$item['id']);
                $qty = (int)$item['quantity'];
                if (!$product || $qty <= 0 || $qty > (int)$product['quantity']) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Produit invalide ou stock insuffisant']);
                    return;
                }
                $cart[$i]['quantity'] = $qty;
                $cart[$i]['price'] = (float)$product['selling_price']; // official price from the DB
                $total += $qty * $cart[$i]['price'];
            }

            $sale_id = $saleModel->create($_SESSION['user_id'], $total);
            
            if ($sale_id) {
                foreach ($cart as $item) {
                    $saleModel->addItem($sale_id, $item['id'], $item['quantity'], $item['price']);
                    $productModel->updateQuantity($item['id'], -$item['quantity']);
                }
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'sale_id' => $sale_id,
                    'redirect' => 'index.php?controller=sale&action=receipt&id=' . $sale_id
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
            }
            return;
        }
        
        $this->redirect("index.php?controller=sale&action=pos");
    }
    
    public function receipt() {
        $this->requireAuth();
        
        $sale_id = $_GET['id'] ?? null;
        if (!$sale_id) {
            $this->redirect("index.php?controller=sale&action=pos");
        }
        
        $saleModel = new Sale($this->db);
        $sale = $saleModel->getSaleById($sale_id);
        $items = $saleModel->getSaleDetails($sale_id);
        
        // Utiliser render() au lieu de renderView()
        $this->render("sales/receipt", [
            'sale' => $sale,
            'items' => $items
        ]);
    }
    
    public function history() {
        $this->requireAuth();
        
        $saleModel = new Sale($this->db);
        
        if ($_SESSION['user_role'] === 'admin') {
            $sales = $saleModel->getAllSales();
        } else {
            $sales = $saleModel->getSalesByUser($_SESSION['user_id']);
        }
        
        // Utiliser render() au lieu de renderView()
        $this->render("sales/history", ['sales' => $sales]);
    }
}
?>