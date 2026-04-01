<?php
// views/products/view.php
?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-box"></i> Détails du produit
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">ID:</th>
                                <td><?php echo $product['id']; ?></td>
                            </tr>
                            <tr>
                                <th>Nom:</th>
                                <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Catégorie:</th>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'Non catégorisé'); ?></td>
                            </tr>
                            <tr>
                                <th>Fournisseur:</th>
                                <td><?php echo htmlspecialchars($product['supplier_name'] ?? 'Non spécifié'); ?></td>
                            </tr>
                            <tr>
                                <th>Prix d'achat:</th>
                                <td><?php echo number_format($product['purchase_price'], 2, ',', ' '); ?> MAD</td>
                            </tr>
                            <tr>
                                <th>Prix de vente:</th>
                                <td class="text-success fw-bold"><?php echo number_format($product['selling_price'], 2, ',', ' '); ?> MAD</td>
                            </tr>
                            <tr>
                                <th>Stock actuel:</th>
                                <td>
                                    <?php 
                                    $stockClass = $product['quantity'] <= $product['alert_threshold'] ? 'text-danger' : 'text-success';
                                    ?>
                                    <span class="<?php echo $stockClass; ?> fw-bold">
                                        <?php echo $product['quantity']; ?> unités
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Seuil d'alerte:</th>
                                <td><?php echo $product['alert_threshold']; ?> unités</td>
                            </tr>
                            <tr>
                                <th>Date création:</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <th>Dernière modification:</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($product['updated_at'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <strong>Description</strong>
                            </div>
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars($product['description'] ?: 'Aucune description')); ?>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <?php if ($product['quantity'] > 0): ?>
                                <a href="index.php?controller=sale&action=pos" class="btn btn-success">
                                    <i class="fas fa-shopping-cart"></i> Ajouter au panier
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-times-circle"></i> Rupture de stock
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="index.php?controller=product&action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div>
                        <a href="index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="javascript:void(0)" 
                           onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')"
                           class="btn btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le produit "' + name + '" ?')) {
        window.location.href = 'index.php?controller=product&action=delete&id=' + id;
    }
}
</script>