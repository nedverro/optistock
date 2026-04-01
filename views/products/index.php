<?php
// views/products/index.php - Version complète
?>
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-boxes"></i> Liste des produits
            </h5>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?controller=product&action=create" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Nouveau produit
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
<!-- Dans la barre de filtres -->
<form method="GET" class="row mb-4 g-2">
    <input type="hidden" name="controller" value="product">
    <input type="hidden" name="action" value="index">
    
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un produit..." 
               value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
    </div>
    
    <div class="col-md-3">
        <select name="category_id" class="form-select">
            <option value="">Toutes catégories</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" 
                <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-md-3">
        <select name="supplier_id" class="form-select">
            <option value="">Tous fournisseurs</option>
            <?php foreach ($suppliers as $sup): ?>
            <option value="<?php echo $sup['id']; ?>" 
                <?php echo ($filters['supplier_id'] == $sup['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($sup['name']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-filter"></i> Filtrer
        </button>
    </div>
</form>
        
        <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucun produit trouvé.
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix achat</th>
                        <th>Prix vente</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                            <small class="text-muted"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($product['category_name'] ?? 'Non catégorisé'); ?></td>
                        <td><?php echo number_format($product['purchase_price'], 2, ',', ' '); ?> MAD</td>
                        <td class="fw-bold text-success"><?php echo number_format($product['selling_price'], 2, ',', ' '); ?> MAD</td>
                        <td>
                            <?php 
                            $stockClass = $product['quantity'] <= $product['alert_threshold'] ? 'text-danger fw-bold' : 'text-success';
                            ?>
                            <span class="<?php echo $stockClass; ?>">
                                <?php echo $product['quantity']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($product['quantity'] <= $product['alert_threshold']): ?>
                                <span class="badge bg-warning text-dark">Stock faible</span>
                            <?php elseif ($product['quantity'] == 0): ?>
                                <span class="badge bg-danger">Rupture</span>
                            <?php else: ?>
                                <span class="badge bg-success">Disponible</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?controller=product&action=view&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <a href="index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)" 
                                   onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')"
                                   class="btn btn-danger" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le produit "' + name + '" ?')) {
        window.location.href = 'index.php?controller=product&action=delete&id=' + id;
    }
}
</script>