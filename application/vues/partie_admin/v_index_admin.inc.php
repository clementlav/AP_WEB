<section class="admin-section">
    <div class="admin-container">
        <div class="admin-box">
            <div class="admin-header">
                <h1>🏢 Tableau de bord Admin</h1>
                <p>Bienvenue <?php echo htmlspecialchars($_SESSION['login_admin']); ?></p>
            </div>
            <div class="admin-menu">
                <a href="index.php?controleur=Admin&action=afficherGestionProduits" class="admin-menu-item">
                    <span class="icon">📦</span>
                    <span class="label">Gestion des produits</span>
                </a>
                <a href="index.php?controleur=Admin&action=afficherGestionCategories" class="admin-menu-item">
                    <span class="icon">📋</span>
                    <span class="label">Gestion des categories</span>
                </a>
            </div>
            <div class="admin-footer">
                <a href="index.php?controleur=Admin&action=seDeconnecter" class="btn-deconnect">Deconnexion</a>
                <a href="index.php" class="btn-back">Retour au site</a>
            </div>
        </div>
    </div>
</section>
