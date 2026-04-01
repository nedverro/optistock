<?php
// views/sales/receipt.php
?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-check-circle"></i> Vente complétée!
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    Transaction #<?php echo str_pad($sale['id'], 8, '0', STR_PAD_LEFT); ?> enregistrée avec succès.
                </div>
                
                <div class="receipt-container border p-3 mb-3" id="receipt" style="font-family: monospace;">
                    <div class="text-center">
                        <h4>🏪 Magasin Stock</h4>
                        <p>123 Rue de la Paix, Casablanca<br>Tel: 05 22 123 456</p>
                        <hr>
                        <strong>REÇU DE VENTE</strong><br>
                        N°: <?php echo str_pad($sale['id'], 8, '0', STR_PAD_LEFT); ?><br>
                        Date: <?php echo date('d/m/Y H:i:s', strtotime($sale['created_at'])); ?>
                        <hr>
                    </div>
                    
                    <p><strong>Vendeur:</strong> <?php echo htmlspecialchars($sale['cashier_name']); ?></p>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 1px solid #000;">
                                <th style="text-align: left;">Produit</th>
                                <th style="text-align: center;">Qté</th>
                                <th style="text-align: right;">Prix</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td style="text-align: left;"><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($item['unit_price'], 2, ',', ' '); ?></td>
                                <td style="text-align: right;"><?php echo number_format($item['subtotal'], 2, ',', ' '); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 1px solid #000;">
                                <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                                <td style="text-align: right;"><strong><?php echo number_format($sale['total_amount'], 2, ',', ' '); ?> MAD</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="text-center mt-3">
                        <hr>
                        <p>Merci de votre visite!<br>A bientôt!</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?controller=sale&action=pos" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle vente
                    </a>
                    <button onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                    <a href="index.php?controller=sale&action=history" class="btn btn-secondary">
                        <i class="fas fa-history"></i> Historique
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, .alert, .d-flex {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .receipt-container {
        margin: 0;
        padding: 0;
    }
}
</style>