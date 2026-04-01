<?php
// models/Product.php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $purchase_price;
    public $selling_price;
    public $quantity;
    public $alert_threshold;
    public $category_id;
    public $supplier_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name = :name, 
                      description = :description, 
                      purchase_price = :purchase_price,
                      selling_price = :selling_price, 
                      quantity = :quantity,
                      alert_threshold = :alert_threshold, 
                      category_id = :category_id,
                      supplier_id = :supplier_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":purchase_price", $this->purchase_price);
        $stmt->bindParam(":selling_price", $this->selling_price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":alert_threshold", $this->alert_threshold);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);

        return $stmt->execute();
    }

    public function getAll($filters = []) {
    $query = "SELECT p.*, c.name as category_name, s.name as supplier_name
              FROM " . $this->table_name . " p
              LEFT JOIN categories c ON p.category_id = c.id
              LEFT JOIN suppliers s ON p.supplier_id = s.id
              WHERE 1=1";

    // Filtre par catégorie
    if (!empty($filters['category_id'])) {
        $query .= " AND p.category_id = :category_id";
    }
    
    // Filtre par fournisseur
    if (!empty($filters['supplier_id'])) {
        $query .= " AND p.supplier_id = :supplier_id";
    }
    
    // Filtre par nom (recherche)
    if (!empty($filters['search'])) {
        $query .= " AND p.name LIKE :search";
    }

    $query .= " ORDER BY p.name ASC";

    $stmt = $this->conn->prepare($query);

    if (!empty($filters['category_id'])) {
        $stmt->bindParam(":category_id", $filters['category_id']);
    }
    if (!empty($filters['supplier_id'])) {
        $stmt->bindParam(":supplier_id", $filters['supplier_id']);
    }
    if (!empty($filters['search'])) {
        $search = "%{$filters['search']}%";
        $stmt->bindParam(":search", $search);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function findById($id) {
        $query = "SELECT p.*, c.name as category_name, s.name as supplier_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN suppliers s ON p.supplier_id = s.id
                  WHERE p.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET name = :name, 
                      description = :description,
                      purchase_price = :purchase_price, 
                      selling_price = :selling_price,
                      quantity = :quantity, 
                      alert_threshold = :alert_threshold,
                      category_id = :category_id, 
                      supplier_id = :supplier_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":purchase_price", $this->purchase_price);
        $stmt->bindParam(":selling_price", $this->selling_price);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":alert_threshold", $this->alert_threshold);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":supplier_id", $this->supplier_id);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function updateQuantity($id, $quantity) {
        $query = "UPDATE " . $this->table_name . "
                  SET quantity = quantity + :quantity
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getLowStockProducts() {
        $query = "SELECT p.*, c.name as category_name, s.name as supplier_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN suppliers s ON p.supplier_id = s.id
                  WHERE p.quantity <= p.alert_threshold
                  ORDER BY p.quantity ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>