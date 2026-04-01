<?php
// views/sales/pos.php
?>
<style>
    .product-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #28a745;
        background: #f8fff8;
    }
    .product-card h6 {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .product-card .price {
        color: #28a745;
        font-weight: bold;
        font-size: 16px;
    }
    .product-card .stock {
        font-size: 11px;
        color: #666;
    }
    .cart-item {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border-left: 3px solid #28a745;
    }
    .cart-total {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
    }
    .cart-header {
        background: #343a40;
        color: white;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .search-box {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        padding: 10px 0;
    }
</style>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-box"></i> Catalogue produits</h5>
            </div>
            <div class="card-body">
                <div class="search-box">
                    <input type="text" id="searchProduct" class="form-control" placeholder="🔍 Rechercher un produit...">
                </div>
                <div class="row" id="productList" style="max-height: 500px; overflow-y: auto;">
                    <?php foreach ($products as $product): ?>
                    <?php if ($product['quantity'] > 0): ?>
                    <div class="col-md-6 col-lg-4 product-item" data-name="<?php echo strtolower($product['name']); ?>">
                        <div class="product-card" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['selling_price']; ?>, <?php echo $product['quantity']; ?>)">
                            <h6><?php echo htmlspecialchars($product['name']); ?></h6>
                            <div class="price"><?php echo number_format($product['selling_price'], 2, ',', ' '); ?> MAD</div>
                            <div class="stock">📦 Stock: <?php echo $product['quantity']; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Panier</h5>
            </div>
            <div class="card-body">
                <div class="cart-header">
                    <div class="row">
                        <div class="col-6">Produit</div>
                        <div class="col-3">Qté</div>
                        <div class="col-3">Total</div>
                    </div>
                </div>
                <div id="cartItems" style="max-height: 350px; overflow-y: auto;"></div>
                <div class="cart-total mt-3">
                    Total: <span id="cartTotal">0.00 MAD</span>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <button class="btn btn-danger w-100" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Vider
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-success w-100" id="validatePayment" onclick="validatePayment()" disabled>
                            <i class="fas fa-check"></i> Payer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function addToCart(id, name, price, stock) {
    let existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.quantity < stock) {
            existing.quantity++;
        } else {
            alert('Stock insuffisant!');
            return;
        }
    } else {
        cart.push({id: id, name: name, price: price, quantity: 1});
    }
    updateCartDisplay();
}

function updateCartDisplay() {
    let html = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;
        html += `
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-6">
                        <strong>${item.name}</strong><br>
                        <small>${item.price.toFixed(2)} MAD</small>
                    </div>
                    <div class="col-3">
                        <div class="input-group input-group-sm">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, -1)">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" style="width: 40px;" readonly>
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, 1)">+</button>
                        </div>
                    </div>
                    <div class="col-2">
                        <span class="fw-bold">${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    document.getElementById('cartItems').innerHTML = html || '<div class="text-center text-muted">Panier vide</div>';
    document.getElementById('cartTotal').innerHTML = total.toFixed(2) + ' MAD';
    document.getElementById('validatePayment').disabled = cart.length === 0;
}

function updateQuantity(index, change) {
    let newQuantity = cart[index].quantity + change;
    if (newQuantity > 0) {
        cart[index].quantity = newQuantity;
    } else {
        cart.splice(index, 1);
    }
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function clearCart() {
    cart = [];
    updateCartDisplay();
}

// Dans views/sales/pos.php - partie JavaScript
function validatePayment() {
    if (cart.length === 0) {
        alert('Le panier est vide!');
        return;
    }
    
    let btn = document.getElementById('validatePayment');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
    
    fetch('index.php?controller=sale&action=processSale', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'cart=' + encodeURIComponent(JSON.stringify(cart))
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        
        if (data.success) {
            // Rediriger vers le reçu
            window.location.href = data.redirect;
        } else {
            alert('Erreur: ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Payer';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur de connexion: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Payer';
    });
}

// Recherche de produits
document.getElementById('searchProduct').addEventListener('keyup', function() {
    let search = this.value.toLowerCase();
    let products = document.querySelectorAll('.product-item');
    products.forEach(product => {
        let name = product.getAttribute('data-name');
        if (name.includes(search)) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
});
</script>