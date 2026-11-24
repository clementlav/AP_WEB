<section class="cart">
    <div class="cart-header">
        <h2>🛒 Mon Panier</h2>
    </div>
    <?php
    if (isset($_SESSION['panier_message'])) {
        echo '<div class="notification success-notification">' . htmlspecialchars($_SESSION['panier_message']) . '</div>';
        unset($_SESSION['panier_message']);
    }
    if (Panier::isVide()) {
        echo '<div class="cart-empty"><p>Votre panier est vide.</p></div>';
    } else {
        $total = 0;
        $produitsPanier = Panier::getProduits();
        echo '<div class="cart-items">';
        foreach ($produitsPanier as $idProduit => $qty) {
            $produit = GestionBoutique::getLesProduitsById($idProduit);
            if ($produit) {
                $imagePath = Chemins::IMAGES . 'produits/' . ($produit->image ?: 'masque_connecter_affichage.png');
                $sousTotal = $produit->prix * $qty;
                echo '<div class="cart-item">';
                echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($produit->nom) . '" />';
                echo '<div class="cart-item-info">';
                echo '<h3>' . htmlspecialchars($produit->nom) . '</h3>';
                echo '<p class="description">' . htmlspecialchars($produit->description) . '</p>';
                echo '<div class="cart-item-details">';
                echo '<span class="price">Prix: <strong>' . number_format($produit->prix, 2, ',', ' ') . ' €</strong></span>';
                echo '<div class="quantity-control" aria-label="Quantité de ' . htmlspecialchars($produit->nom) . '">';
                echo '<a href="index.php?controleur=Panier&action=diminuer&idProduit=' . $produit->id . '" class="qty-button" aria-label="Retirer une unité">−</a>';
                echo '<form method="post" action="index.php?controleur=Panier&action=modifierQuantite" class="qty-form">';
                echo '<input type="hidden" name="idProduit" value="' . $produit->id . '">';
                echo '<input type="number" name="qte" value="' . $qty . '" min="1" max="99" aria-label="Quantité">';
                echo '</form>';
                echo '<a href="index.php?controleur=Panier&action=augmenter&idProduit=' . $produit->id . '" class="qty-button" aria-label="Ajouter une unité">+</a>';
                echo '</div>';
                echo '</div>';
                echo '<p class="subtotal">Sous-total: <strong>' . number_format($sousTotal, 2, ',', ' ') . ' €</strong></p>';
                echo '</div>';
                echo '<a href="index.php?controleur=Panier&action=supprimer&idProduit=' . $produit->id . '" class="btn-remove">✕</a>';
                echo '</div>';
                $total += $sousTotal;
            }
        }
        echo '</div>';
        echo '<div class="cart-footer">';
        echo '<div class="cart-total">Total: <span>' . number_format($total, 2, ',', ' ') . ' €</span></div>';
        echo '<div class="cart-actions">';
        echo '<a href="index.php?controleur=Panier&action=vider" class="btn-clear">Vider le panier</a>';
        echo '<a href="index.php?controleur=Panier&action=valider" class="btn-checkout">Procéder au paiement</a>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</section>