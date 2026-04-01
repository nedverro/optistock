<?php
// views/sales/history.php
?>
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-history"></i> Historique des ventes</h5>
    </div>
    <div class="card-body">
        <?php if (empty($sales)): ?>
        <div class="alert alert-info">Aucune vente enregistrée.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Vendeur</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td>#<?php echo str_pad($sale['id'], 8, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($sale['created_at'])); ?></td>
                        <td>
                            <?php 
                            // Afficher le nom du vendeur (full_name ou username)
                            if (!empty($sale['full_name'])) {
                                echo htmlspecialchars($sale['full_name']);
                            } elseif (!empty($sale['username'])) {
                                echo htmlspecialchars($sale['username']);
                            } else {
                                echo 'Utilisateur #' . $sale['user_id'];
                            }
                            ?>
                        </td>
                        <td><?php echo $sale['items_count'] ?? 0; ?></td>
                        <td class="fw-bold text-success"><?php echo number_format($sale['total_amount'], 2, ',', ' '); ?> MAD</td>
                        <td>
                            <a href="index.php?controller=sale&action=receipt&id=<?php echo $sale['id']; ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-receipt"></i> Reçu
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>