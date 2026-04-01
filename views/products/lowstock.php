<?php
// views/products/lowstock.php
?>
<div class="card">
    <div class="card-header bg-warning">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle"></i> Alertes stock - Mes produits
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($products)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> Aucun produit en stock faible.
        </div>
        <?php else: ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> Attention! <?php echo count($products); ?> produit(s) en stock faible:
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Produit</th>
                        <th>Stock actuel</th>
                        <th>Seuil d'alerte</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td class="text-danger fw-bold"><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['alert_threshold']; ?></td>
                        <td>
                            <span class="badge bg-warning">À réapprovisionner</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="index.php?controller=product&action=index" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Voir tous mes produits
            </a>
        </div>
    </div>
</div>