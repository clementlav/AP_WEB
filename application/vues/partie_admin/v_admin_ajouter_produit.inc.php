<section class="admin-section">
    <div class="admin-container">
        <div class="admin-box">
            <div class="admin-header">
                <h1>➕ Ajouter un produit</h1>
                <p>Ajouter un nouveau produit à la boutique</p>
            </div>

            <?php if (isset($_SESSION['admin_error'])) : ?>
                <div class="admin-message error" style="margin: 20px 40px 0 40px; border-left-color: #ff6b6b; background: #ffebee; color: #c62828;">
                    ! <?php echo htmlspecialchars($_SESSION['admin_error']); ?>
                </div>
                <?php unset($_SESSION['admin_error']); ?>
            <?php endif; ?>

            <form method="post" action="index.php?controleur=Admin&action=ajouterProduit" class="admin-form">
                <fieldset class="admin-fieldset">
                    <div class="form-group">
                        <label for="nom">Nom du produit *</label>
                        <input type="text" name="nom" id="nom" value="" required placeholder="Ex: Casque Pro" />
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea name="description" id="description" rows="4" style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required placeholder="Description du produit..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="prix">Prix (EUR) *</label>
                            <input type="number" name="prix" id="prix" value="" step="0.01" min="0" required placeholder="Ex: 99.99" />
                        </div>

                        <div class="form-group">
                            <label for="idCategorie">Categorie *</label>
                            <select name="idCategorie" id="idCategorie" required style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.95rem;">
                                <option value="">-- Selectionnez une categorie --</option>
                                <?php foreach ($lesCategories as $categorie) : ?>
                                    <option value="<?php echo intval($categorie->id); ?>">
                                        <?php echo htmlspecialchars($categorie->libelle); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Nom de l'image</label>
                        <input type="text" name="image" id="image" value="" placeholder="Ex: mon_produit.jpg" />
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-admin">✅ Ajouter le produit</button>
                        <a href="index.php?controleur=Admin&action=afficherGestionProduits" class="btn-back">Annuler</a>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</section>
