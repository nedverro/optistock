<?php
// models/Sale.php
class Sale {
    private $conn;
    private $table_name = "sales";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user_id, $total_amount) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, total_amount) 
                  VALUES (:user_id, :total_amount)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":total_amount", $total_amount);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function addItem($sale_id, $product_id, $quantity, $unit_price) {
        $subtotal = $quantity * $unit_price;
        
        $query = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, subtotal)
                  VALUES (:sale_id, :product_id, :quantity, :unit_price, :subtotal)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sale_id", $sale_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":unit_price", $unit_price);
        $stmt->bindParam(":subtotal", $subtotal);
        
        return $stmt->execute();
    }

    public function getSaleById($id) {
        $query = "SELECT s.*, u.username, u.full_name as cashier_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN users u ON s.user_id = u.id
                  WHERE s.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSaleDetails($sale_id) {
        $query = "SELECT si.*, p.name as product_name
                  FROM sale_items si
                  LEFT JOIN products p ON si.product_id = p.id
                  WHERE si.sale_id = :sale_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sale_id", $sale_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
public function getSalesByUser($user_id) {
    $query = "SELECT s.*, u.username, u.full_name, COUNT(si.id) as items_count
              FROM " . $this->table_name . " s
              LEFT JOIN users u ON s.user_id = u.id
              LEFT JOIN sale_items si ON s.id = si.sale_id
              WHERE s.user_id = :user_id
              GROUP BY s.id
              ORDER BY s.created_at DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
   
public function getAllSales() {
    $query = "SELECT s.*, u.username, u.full_name, COUNT(si.id) as items_count
              FROM " . $this->table_name . " s
              LEFT JOIN users u ON s.user_id = u.id
              LEFT JOIN sale_items si ON s.id = si.sale_id
              GROUP BY s.id
              ORDER BY s.created_at DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getTotalRevenue() {
        $query = "SELECT SUM(total_amount) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getTodaySales() {
        $query = "SELECT SUM(total_amount) as total
                  FROM " . $this->table_name . "
                  WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>