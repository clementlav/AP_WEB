<!DOCTYPE html>
<html lang="fr">
    <head>        
        <title>Ski Connect Boutique</title>
        <meta http-equiv="Content-Language" content="fr">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo Chemins::STYLES.'style.css'; ?>" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" type="image/jpeg" href="<?php echo Chemins::IMAGES.'ziknco_titre.jpg'; ?>">
    </head>
    <body>
        <header>
            <div id="main-nav">
                <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="left-menu right-menu" aria-label="Ouvrir le menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav class="left-nav" id="left-menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php?controleur=Produits&action=afficher&categorie=Casques">Casques</a></li>
                        <li><a href="index.php?controleur=Produits&action=afficher&categorie=Masques">Masques</a></li>
                        <li><a href="index.php?controleur=Produits&action=afficher&categorie=Skis">Skis</a></li>
                    </ul>
                </nav>
                <nav class="right-nav" id="right-menu">
                    <ul>
                        <li class="panier-link"><a href="index.php?controleur=Panier&action=afficher">🛒 Panier</a></li>
                        <?php if (isset($_SESSION['login_user'])): ?>
                            <li class="login-link"><a href="#" class="user-name">👤 <?php echo htmlspecialchars($_SESSION['login_user_nom']); ?></a></li>
                            <li class="login-link"><a href="index.php?controleur=Utilisateur&action=seDeconnecter">Déconnexion</a></li>
                        <?php else: ?>
                            <li class="login-link"><a href="index.php?controleur=Utilisateur&action=afficherConnexion">🔐 Se connecter</a></li>
                        <?php endif; ?>
                        <li class="admin-link"><a href="index.php?controleur=Admin&action=afficherIndex">👤 Admin</a></li>
                    </ul>
                </nav>
            </div>
        </header>
