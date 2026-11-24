<?php
require_once 'configs/chemins.class.php';
require_once Chemins::CONFIGS . 'mysql_config.class.php';
require_once Chemins::MODELES . 'GestionBoutique.class.php';

// // Add more products for Casques
// GestionBoutique::ajouterProduitComplet('Casque Avalanche Pro', 'Casque avec système d\'alerte avalanche intégré', 249.90, 'casque_isnowvision.jpg', 1);
// GestionBoutique::ajouterProduitComplet('Casque ThermoGuard', 'Casque isolé thermique avec ventilation ajustable', 199.50, 'casque_smartrack.jpg', 1);

// // Add more products for Masques
// GestionBoutique::ajouterProduitComplet('Masque CrystalClear', 'Masque anti-buée avec lentilles interchangeables', 129.00, 'masque_frostguard.jpg', 2);
// GestionBoutique::ajouterProduitComplet('Masque NightVision', 'Masque avec vision nocturne et GPS intégré', 299.90, 'masque_visiotech.jpg', 2);

// Add products for Skis
// GestionBoutique::ajouterProduitComplet('Ski AllMountain X1', 'Ski polyvalent pour toutes les pistes', 399.90, 'ski_allmountain_x1.jpg', 3);
GestionBoutique::ajouterProduitComplet('Ski Freestyle Pro', 'Ski freestyle avec cambre spécial park', 349.50, 'ski_freestylepro.webp', 3);


echo "Produits ajoutés avec succès.";
?>