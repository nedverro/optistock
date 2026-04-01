// public/js/script.js

// Fonction pour confirmer la suppression
function confirmDelete(id, name) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ' + name + ' ?')) {
        window.location.href = 'index.php?controller=product&action=delete&id=' + id;
    }
}

// Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Format price inputs
    $('input[type="number"]').on('change', function() {
        let val = parseFloat($(this).val());
        if (!isNaN(val)) {
            $(this).val(val.toFixed(2));
        }
    });
});

// POS System (à ajouter dans sales/pos.php)
let cart = [];

function addToCart(productId, productName, price) {
    let existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    updateCartDisplay();
}

function updateCartDisplay() {
    let cartHtml = '';
    let total = 0;
    
    cart.forEach((item, index) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;
        
        cartHtml += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.name}</strong><br>
                        <small>${item.price} MAD x ${item.quantity}</small>
                    </div>
                    <div>
                        <span class="fw-bold">${subtotal.toFixed(2)} MAD</span>
                        <button class="btn btn-sm btn-danger ms-2" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#cart-items').html(cartHtml);
    $('#cart-total').html(total.toFixed(2) + ' MAD');
    
    if (cart.length > 0) {
        $('#validatePayment').prop('disabled', false);
    } else {
        $('#validatePayment').prop('disabled', true);
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function clearCart() {
    cart = [];
    updateCartDisplay();
}

function validatePayment() {
    if (cart.length === 0) {
        alert('Le panier est vide!');
        return;
    }
    
    $('#validatePayment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Traitement...');
    
    $.ajax({
        url: 'index.php?controller=sale&action=pos',
        method: 'POST',
        data: {
            cart: JSON.stringify(cart)
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.href = response.redirect;
            } else {
                alert('Erreur: ' + response.message);
                $('#validatePayment').prop('disabled', false).html('<i class="fas fa-check"></i> Valider le paiement');
            }
        },
        error: function() {
            alert('Erreur de connexion au serveur');
            $('#validatePayment').prop('disabled', false).html('<i class="fas fa-check"></i> Valider le paiement');
        }
    });
}