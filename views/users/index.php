<?php
// views/users/index.php
?>
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Gestion des utilisateurs
            </h5>
            <a href="index.php?controller=user&action=create" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Nouvel utilisateur
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
        <div class="alert alert-info">Aucun utilisateur trouvé.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php 
                            $badgeClass = $user['role'] === 'admin' ? 'bg-danger' : ($user['role'] === 'seller' ? 'bg-warning' : 'bg-info');
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?controller=user&action=edit&id=<?php echo $user['id']; ?>" 
                                   class="btn btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?controller=user&action=delete&id=<?php echo $user['id']; ?>" 
                                   class="btn btn-danger" title="Supprimer"
                                   onclick="return confirm('Supprimer cet utilisateur ?')">
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