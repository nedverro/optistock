<?php
// controllers/DashboardController.php
require_once "Controller.php";

class DashboardController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $productModel = new Product($this->db);
        $saleModel = new Sale($this->db);
        
        // Si c'est un fournisseur, rediriger vers la page des stocks
        if ($_SESSION['user_role'] === 'supplier') {
            $this->redirect("index.php?controller=product&action=index");
        }
        
        // Pour admin et vendeur
        $low_stock_products = $productModel->getLowStockProducts();
        $total_revenue = $saleModel->getTotalRevenue();
        $today_sales = $saleModel->getTodaySales();
        
        $this->render("dashboard/index", [
            'low_stock_products' => $low_stock_products,
            'total_revenue' => $total_revenue,
            'today_sales' => $today_sales
        ]);
    }
}
?>