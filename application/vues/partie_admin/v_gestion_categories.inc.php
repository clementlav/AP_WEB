<?php
// Simple admin view to list categories
if (!isset($lesCategories) && isset(VariablesGlobales::$lesCategories)) {
    $lesCategories = VariablesGlobales::$lesCategories;
}
?>
<section class="admin-section">
    <div class="admin-container">
        <div class="admin-box">
            <div class="admin-header">
                <h1>Gestion des catégories</h1>
            </div>
            <div class="admin-content">
                        <?php if (isset($_SESSION['admin_message'])) : ?>
                            <div class="admin-message success"><?php echo htmlspecialchars($_SESSION['admin_message']); ?></div>
                            <?php unset($_SESSION['admin_message']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['admin_error'])) : ?>
                            <div class="admin-message error"><?php echo htmlspecialchars($_SESSION['admin_error']); ?></div>
                            <?php unset($_SESSION['admin_error']); ?>
                        <?php endif; ?>

                        <?php if (isset($categorie) && !empty($categorie)) : ?>
                            <div style="margin-bottom:20px;padding:12px;border:1px solid #ddd;border-radius:6px;background:#fafafa;">
                                <form method="post" action="index.php?controleur=Admin&action=modifierCategorie" style="display:flex;gap:8px;align-items:center;">
                                    <input type="hidden" name="idCategorie" value="<?php echo intval($categorie->id); ?>" />
                                    <input type="text" name="libelle" value="<?php echo htmlspecialchars($categorie->libelle); ?>" required style="flex:1;padding:8px;border-radius:4px;border:1px solid #ccc;" />
                                    <button class="btn-admin" type="submit">Enregistrer</button>
                                    <a href="index.php?controleur=Admin&action=afficherGestionCategories" class="btn-back" style="margin-left:8px;">Annuler</a>
                                </form>
                            </div>
                        <?php endif; ?>

                        <div style="margin-bottom:20px;">
                            <form method="post" action="index.php?controleur=Admin&action=ajouterCategorie" style="display:flex;gap:8px;align-items:center;">
                                <input type="text" name="libelle" placeholder="Nouvelle catégorie" required style="flex:1;padding:8px;border-radius:4px;border:1px solid #ccc;" />
                                <button class="btn-admin" type="submit">Ajouter</button>
                            </form>
                        </div>

                        <?php if (empty($lesCategories)) : ?>
                            <p>Aucune catégorie trouvée.</p>
                        <?php else : ?>
                            <table class="admin-table">
                                <thead>
                                    <tr><th>ID</th><th>Libellé</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                <?php foreach ($lesCategories as $categorie) : ?>
                                    <tr>
                                        <td><?php echo intval($categorie->id); ?></td>
                                        <td><?php echo htmlspecialchars($categorie->libelle); ?></td>
                                        <td>
                                            <a href="index.php?controleur=Admin&action=afficherModifierCategorie&idCategorie=<?php echo intval($categorie->id); ?>">✏️ Modifier</a>
                                            &nbsp;|&nbsp;
                                            <a href="index.php?controleur=Admin&action=supprimerCategorie&idCategorie=<?php echo intval($categorie->id); ?>" onclick="return confirm('Voulez-vous supprimer cette catégorie ?')">🗑️ Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <p style="margin-top:12px;"><a href="index.php?controleur=Admin&action=afficherIndex">← Retour</a></p>
            </div>
        </div>
    </div>
</section>
