<?php
// views/users/edit.php
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit"></i> Modifier utilisateur
                </h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?controller=user&action=edit&id=<?php echo $user['id']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur *</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle *</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="seller" <?php echo $user['role'] == 'seller' ? 'selected' : ''; ?>>Vendeur (Caissier)</option>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                            <option value="supplier" <?php echo $user['role'] == 'supplier' ? 'selected' : ''; ?>>Fournisseur</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <small><i class="fas fa-info-circle"></i> Pour changer le mot de passe, utilisez la base de données.</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="index.php?controller=user&action=index" class="btn btn-secondary">
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