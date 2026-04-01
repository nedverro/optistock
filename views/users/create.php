<?php
// views/users/create.php
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus"></i> Ajouter un utilisateur
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?controller=user&action=create">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="seller">Vendeur (Caissier)</option>
                            <option value="admin">Administrateur</option>
                            <option value="supplier">Fournisseur</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=user&action=index" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>