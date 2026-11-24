<?php
require_once 'configs/variables_globales.class.php';

$rawCategory = VariablesGlobales::$libelleCategorie;
$categoryKey = strtolower(trim($rawCategory));

$categoryMeta = array(
    'casques' => array(
        'kicker' => 'Collection premium',
        'title' => 'Les casques qui dominent la montagne.',
        'badge' => 'Classic'
    ),
    'masques' => array(
        'kicker' => 'Vision signature',
        'title' => 'Les masques qui subliment chaque descente.',
        'badge' => 'Heritage'
    ),
    'skis' => array(
        'kicker' => 'Atelier performance',
        'title' => 'Les skis qui signent votre ligne.',
        'badge' => 'Limited'
    )
);

$meta = isset($categoryMeta[$categoryKey]) ? $categoryMeta[$categoryKey] : array(
    'kicker' => 'Selection boutique',
    'title' => 'Les pieces qui racontent votre saison.',
    'badge' => 'Premium'
);
?>

<section class="products-list products-showcase">
    <div class="showcase-shell">
        <div class="showcase-hero">
            <p class="showcase-kicker"><?php echo htmlspecialchars($meta['kicker']); ?></p>
            <h2><?php echo htmlspecialchars($meta['title']); ?></h2>
        </div>

        <?php if (empty(VariablesGlobales::$lesProduits)) { ?>
            <p class="products-empty">Aucun produit trouve pour cette categorie.</p>
        <?php } else { ?>
            <div class="products-grid">
                <?php foreach (VariablesGlobales::$lesProduits as $unProduit) {
                    $stored = !empty($unProduit->image) ? $unProduit->image : '';
                    $fs = Chemins::IMAGES . 'produits/' . $stored;
                    if (empty($stored) || !file_exists($fs)) {
                        $imagePath = Chemins::IMAGES . 'produits/masque_connecter_affichage.png';
                    } else {
                        $imagePath = Chemins::IMAGES . 'produits/' . $stored;
                    }

                    $rating = number_format(4.6 + (($unProduit->id % 5) * 0.1), 1, ',', '');
                ?>
                <article class="product-card">
                    <div class="product-image-wrap">
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($unProduit->nom); ?>" />
                    </div>

                    <div class="product-body">
                        <div class="product-meta-row">
                            <span class="product-badge"><?php echo htmlspecialchars($meta['badge']); ?></span>
                            <span class="product-rating">★ <?php echo $rating; ?></span>
                        </div>

                        <h3><?php echo htmlspecialchars($unProduit->nom); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($unProduit->description); ?></p>

                        <div class="product-footer-row">
                            <p class="price"><?php echo number_format($unProduit->prix, 2, ',', ' '); ?> €</p>
                            <form action="index.php?controleur=Panier&action=ajouter" method="post" class="cart-form">
                                <input type="hidden" name="idProduit" value="<?php echo $unProduit->id; ?>">
                                <input type="hidden" name="retour" value="index.php?controleur=Produits&amp;action=afficher&amp;categorie=<?php echo urlencode($rawCategory); ?>">
                                <button type="submit" class="btn-cart">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>
