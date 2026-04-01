<?php
// views/products/edit.php
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">
                    <i class="fas fa-edit"></i> Modifier le produit: <?php echo htmlspecialchars($product['name']); ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du produit *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label">Fournisseur</label>
                            <select class="form-select" id="supplier_id" name="supplier_id">
                                <option value="">Sélectionner un fournisseur</option>
                                <?php foreach ($suppliers as $sup): ?>
                                <option value="<?php echo $sup['id']; ?>" 
                                    <?php echo ($product['supplier_id'] == $sup['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($sup['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label">Prix d'achat (MAD) *</label>
                            <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" 
                                   value="<?php echo $product['purchase_price']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="selling_price" class="form-label">Prix de vente (MAD) *</label>
                            <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" 
                                   value="<?php echo $product['selling_price']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantité en stock *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="<?php echo $product['quantity']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alert_threshold" class="form-label">Seuil d'alerte</label>
                            <input type="number" class="form-control" id="alert_threshold" name="alert_threshold" 
                                   value="<?php echo $product['alert_threshold']; ?>">
                            <small class="text-muted">Alerte quand stock ≤ ce seuil</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=product&action=index" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>