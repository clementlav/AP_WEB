<section class="admin-section">
    <div class="admin-container">
        <div class="admin-box">
            <div class="admin-header">
                <h1>📦 Gestion des produits</h1>
                <p>Connecté en tant que: <?php echo htmlspecialchars($_SESSION['login_admin']); ?></p>
            </div>

            <?php if (isset($_SESSION['admin_message'])) : ?>
                <div class="admin-message success">
                    ✓ <?php echo htmlspecialchars($_SESSION['admin_message']); ?>
                </div>
                <?php unset($_SESSION['admin_message']); ?>
            <?php endif; ?>

            <div class="admin-actions" style="display: flex; gap: 15px; align-items: center;">
                <a href="index.php?controleur=Admin&action=afficherAjouterProduit" class="btn-add">➕ Ajouter un produit</a>
                <a href="index.php?controleur=Admin&action=afficherIndex" class="btn-back">← Retour au tableau de bord</a>
            </div>

            <?php if (empty($lesProduits)) : ?>
                <div class="admin-empty">
                    <p>Aucun produit disponible.</p>
                </div>
            <?php else : ?>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Image</th>
                                <th>Catégorie</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lesProduits as $produit) : ?>
                                <tr>
                                    <td class="cell-id"><?php echo htmlspecialchars($produit->id); ?></td>
                                    <td class="cell-name"><?php echo htmlspecialchars($produit->nom); ?></td>
                                    <td class="cell-desc"><?php echo htmlspecialchars(substr($produit->description, 0, 50)) . (strlen($produit->description) > 50 ? '...' : ''); ?></td>
                                    <td class="cell-price"><?php echo number_format($produit->prix, 2, ',', ' '); ?> €</td>
                                    <td class="cell-image">
                                        <?php
                                        $stored = !empty($produit->image) ? $produit->image : '';
                                        $path = Chemins::IMAGES . 'produits/' . $stored;
                                        if (empty($stored) || !file_exists($path)) {
                                            $img = 'masque_connecter_affichage.png';
                                        } else {
                                            $img = $stored;
                                        }
                                        ?>
                                        <img src="<?php echo Chemins::IMAGES . 'produits/' . htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($produit->nom); ?>" />
                                        <div style="font-size:0.8rem;color:#666;margin-top:6px;">Fichier DB: <?php echo htmlspecialchars($stored ?: '[vide]'); ?></div>
                                    </td>
                                    <td class="cell-category"><?php echo htmlspecialchars($produit->idCategorie); ?></td>
                                    <td class="cell-actions">
                                        <a href="index.php?controleur=Admin&action=afficherModifierProduit&idProduit=<?php echo intval($produit->id); ?>" class="btn-edit">
                                            ✏️ Modifier
                                        </a>
                                        <a href="index.php?controleur=Admin&action=supprimerProduit&idProduit=<?php echo intval($produit->id); ?>" class="btn-delete" onclick="return confirm('Confirmation : Voulez-vous vraiment supprimer ce produit ?');">
                                            🗑️ Supprimer
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
</section>
