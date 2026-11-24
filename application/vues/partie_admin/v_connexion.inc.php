<section class="admin-section">
    <div class="admin-container">
        <div class="admin-box">
            <div class="admin-header">
                <h1>🔐 Connexion Admin</h1>
                <p>Administration du site (Acces reserve)</p>
            </div>
            <?php if (isset($_SESSION['admin_login_error'])): ?>
                <div class="notification error-notification"><?php echo htmlspecialchars($_SESSION['admin_login_error']); ?></div>
                <?php unset($_SESSION['admin_login_error']); ?>
            <?php endif; ?>
            <form method="post" action="index.php?controleur=Admin&action=verifierConnexion" class="admin-form">
                <fieldset class="admin-fieldset">
                    <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" name="login" id="login" required autofocus />
                    </div>
                    <div class="form-group">
                        <label for="passe">Mot de passe</label>
                        <input type="password" name="passe" id="passe" required />
                    </div>
                    <div class="form-group checkbox">
                        <input type="checkbox" name="connexion_auto" id="connexion_auto" />
                        <label for="connexion_auto" class="checkbox-label">Rester connecte</label>
                    </div>
                    <button type="submit" class="btn-admin">Se connecter</button>
                </fieldset>
            </form>
        </div>
    </div>
</section>
