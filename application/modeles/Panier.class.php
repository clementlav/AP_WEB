<?php

//Classe statique, peut aussi être géré avec un singleton

class Panier { 

// <editor-fold defaultstate="collapsed" desc="région INITS DE PANIER">

    public static function initialiser() {
        if (!isset($_SESSION['produits'])) {
            $_SESSION['produits'] = array();
        }
    }

    public static function vider() {
        $_SESSION['produits'] = array();
    }

    public static function detruire() {
        unset($_SESSION['produits']);
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="région AJOUTS / MODIFS / SUPRESSION">

    public static function ajouterProduit($idProduit, $qte) {
        if (Panier::contains($idProduit))
            $_SESSION['produits'][$idProduit] += $qte;
        else
            $_SESSION['produits'][$idProduit] = $qte;
    }

    public static function modifierQteProduit($idProduit, $qte) {
        if (Panier::contains($idProduit))
            $_SESSION['produits'][$idProduit] = $qte;
    }

    public static function retirerProduit($idProduit) {
        if (Panier::contains($idProduit))
            unset($_SESSION['produits'][$idProduit]);
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="région FONCTIONS GET">

    public static function getProduits() {
        return isset($_SESSION['produits']) ? $_SESSION['produits'] : array();
    }

    public static function getNbProduits() {
        if (isset($_SESSION['produits'])) {
            return array_sum($_SESSION['produits']);
        }
        return 0;
        // ou en 1 ligne : 
        //return isset($_SESSION['produits'])?array_sum($_SESSION['produits']):0;
    }

    public static function getQteByProduit($idProduit) {
        if (Panier::contains($idProduit))
            return $_SESSION['produits'][$idProduit];
        return 0;
    }

// </editor-fold>    
// <editor-fold defaultstate="collapsed" desc="région FONCTIONS BOOLEENNES">
    public static function isVide() {
        return (self::getNbProduits() == 0);
        // OU
//        if (self::getNbProduits() == 0){
//            return true;
//        }
//        else {
//            return false;
//        }
    }

    public static function contains($idProduit) {
        return (array_key_exists($idProduit, self::getProduits()));

        // OU
//        if (array_key_exists($idProduit, self::getProduits())) {
//            return true;
//        } else {
//            return false;
//        }
    }

// </editor-fold> 
}
?>
