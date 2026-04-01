<?php
// views/dashboard/index.php - Version avec cartes colorées
?>
<style>
    .dashboard-card {
        border-radius: 15px;
        padding: 20px;
        color: white;
        transition: transform 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .card-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .card-warning {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    .dashboard-card h2 {
        font-size: 2rem;
        font-weight: bold;
        margin: 10px 0;
    }
    .dashboard-card i {
        font-size: 2.5rem;
        opacity: 0.7;
    }
    .dashboard-card small {
        font-size: 0.8rem;
        opacity: 0.9;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt"></i> Tableau de bord
        </h2>
        <hr>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="dashboard-card card-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small><i class="fas fa-chart-line"></i> CHIFFRE D'AFFAIRES TOTAL</small>
                    <h2><?php echo number_format($total_revenue, 2, ',', ' '); ?> MAD</h2>
                </div>
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="dashboard-card card-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small><i class="fas fa-calendar-day"></i> VENTES AUJOURD'HUI</small>
                    <h2><?php echo number_format($today_sales, 2, ',', ' '); ?> MAD</h2>
                </div>
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="dashboard-card card-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small><i class="fas fa-exclamation-triangle"></i> ALERTES STOCK</small>
                    <h2><?php echo count($low_stock_products); ?></h2>
                </div>
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
</div>

<?php if (count($low_stock_products) > 0): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Produits en stock faible
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="table-warning">
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock actuel</th>
                                <th>Seuil d'alerte</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($low_stock_products as $product): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                                    <small class="text-muted"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? '-'); ?></td>
                                <td class="text-danger fw-bold"><?php echo $product['quantity']; ?> unités</td>
                                <td><?php echo $product['alert_threshold']; ?> unités</td>
                                <td>
                                    <a href="index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-plus"></i> Réapprovisionner
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>