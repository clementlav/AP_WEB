<section class="login-section">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>🔐 Se connecter</h1>
                <p>Connectez-vous pour valider votre panier.</p>
            </div>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="notification error-notification" id="login-error">
                    <?php echo htmlspecialchars($_SESSION['login_error']); ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form method="post" action="index.php?controleur=Utilisateur&action=verifierConnexion" class="login-form" id="login-form" novalidate>
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" autocomplete="username" required minlength="2" maxlength="120">
                    <p class="field-status" id="pseudo-status" aria-live="polite"></p>
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" name="mot_de_passe" id="mot_de_passe" autocomplete="current-password" required minlength="4">
                </div>

                <button type="submit" class="btn-login" id="login-submit">Se connecter</button>
            </form>
        </div>
    </div>
</section>
