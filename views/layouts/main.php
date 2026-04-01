<?php
// views/layouts/main.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magasin Stock - Gestion des ventes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #f0f0f0;
            font-weight: bold;
            border-radius: 10px 10px 0 0 !important;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .btn {
            border-radius: 5px;
        }
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
        .badge-notification {
            position: relative;
            top: -8px;
            right: 5px;
            font-size: 10px;
        }
        /* Style pour les produits en stock faible */
        .stock-faible {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .stock-critique {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?controller=dashboard&action=index">
                <i class="fas fa-store"></i> Magasin Stock
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <!-- ========== MENU ADMINISTRATEUR ========== -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=dashboard&action=index">
                                <i class="fas fa-chart-line"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=product&action=index">
                                <i class="fas fa-box"></i> Produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=sale&action=pos">
                                <i class="fas fa-shopping-cart"></i> Caisse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=sale&action=history">
                                <i class="fas fa-history"></i> Historique
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=user&action=index">
                                <i class="fas fa-users"></i> Utilisateurs
                            </a>
                        </li>
                        
                    <?php elseif ($_SESSION['user_role'] === 'seller'): ?>
                        <!-- ========== MENU VENDEUR (CAISSIER) ========== -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=dashboard&action=index">
                                <i class="fas fa-chart-line"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=product&action=index">
                                <i class="fas fa-box"></i> Catalogue
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=sale&action=pos">
                                <i class="fas fa-shopping-cart"></i> Caisse
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=sale&action=history">
                                <i class="fas fa-history"></i> Mon historique
                            </a>
                        </li>
                        
                    <?php elseif ($_SESSION['user_role'] === 'supplier'): ?>
                        <!-- ========== MENU FOURNISSEUR ========== -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=product&action=index">
                                <i class="fas fa-box"></i> Mes produits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=product&action=lowStock">
                                <i class="fas fa-exclamation-triangle"></i> Alertes stock
                            </a>
                        </li>
                    <?php endif; ?>
                    
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> 
                            <?php 
                            $roleLabel = '';
                            if ($_SESSION['user_role'] === 'admin') $roleLabel = 'Administrateur';
                            elseif ($_SESSION['user_role'] === 'seller') $roleLabel = 'Vendeur';
                            elseif ($_SESSION['user_role'] === 'supplier') $roleLabel = 'Fournisseur';
                            echo htmlspecialchars($_SESSION['user_name']) . ' (' . $roleLabel . ')';
                            ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text">Rôle: <?php echo ucfirst($_SESSION['user_role']); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?controller=auth&action=logout">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php 
        if (isset($viewFile) && file_exists($viewFile)) {
            require_once $viewFile;
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>