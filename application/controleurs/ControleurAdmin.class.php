<?php

class ControleurAdmin {

    public function __construct() {
        // si on séparait les modèles, le constructeur donnerait son chemin
        // require_once Chemins::MODELES.'gestion_xxxxx.class.php';    
    }

    public function afficherIndex() {
        if (isset($_COOKIE['login_admin']))
        $_SESSION['login_admin'] = $_COOKIE['login_admin'];
        
        if (isset($_SESSION['login_admin']))
            require Chemins::VUES_ADMIN . 'v_index_admin.inc.php';
        else
            require Chemins::VUES_ADMIN . 'v_connexion.inc.php';
    }

    public function verifierConnexion() {
        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $passe = isset($_POST['passe']) ? $_POST['passe'] : '';

        if (GestionBoutique::isAdminOK($login, $passe)) {
            $_SESSION['login_admin'] = $login;

            if (isset($_POST['connexion_auto']))
                setcookie('login_admin', $login, time() + 7 * 24 * 3600, null, null, false, true); // valable 7 jours

            require Chemins::VUES_ADMIN . 'v_index_admin.inc.php';
        } else {
            $_SESSION['admin_login_error'] = 'Identifiants invalides.';
            require Chemins::VUES_ADMIN . 'v_connexion.inc.php';
        }
    }

    public function seDeconnecter() {
        // Suppression des variables de session et de la session
        $_SESSION = array();
        session_destroy();

        //suppression du cookie
        setcookie('login_admin', '');

        header("Location:index.php");
    }

    public function afficherGestionCategories() {
        if (isset($_SESSION['login_admin']))
        {
            $lesCategories = GestionBoutique::getLesCategories();
            VariablesGlobales::$lesCategories = $lesCategories;
            require Chemins::VUES_ADMIN . 'v_gestion_categories.inc.php';
        }
        else
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
    }

    // Affiche le formulaire pour ajouter une catégorie
    public function afficherAjouterCategorie() {
        if (isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_gestion_categories.inc.php';
        } else
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
    }

    public function ajouterCategorie() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $libelle = isset($_POST['libelle']) ? trim($_POST['libelle']) : '';
        if (empty($libelle)) {
            $_SESSION['admin_error'] = 'Le nom de la catégorie est requis.';
            $this->afficherGestionCategories();
            return;
        }
        GestionBoutique::ajouterCategorie($libelle);
        $_SESSION['admin_message'] = 'Catégorie ajoutée.';
        header('Location: index.php?controleur=Admin&action=afficherGestionCategories');
        exit;
    }

    public function afficherModifierCategorie() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $id = isset($_GET['idCategorie']) ? intval($_GET['idCategorie']) : 0;
        if ($id <= 0) {
            header('Location: index.php?controleur=Admin&action=afficherGestionCategories');
            exit;
        }
        $categorie = GestionBoutique::getCategorieById($id);
        $lesCategories = GestionBoutique::getLesCategories();
        require Chemins::VUES_ADMIN . 'v_gestion_categories.inc.php';
    }

    public function modifierCategorie() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $id = isset($_POST['idCategorie']) ? intval($_POST['idCategorie']) : 0;
        $libelle = isset($_POST['libelle']) ? trim($_POST['libelle']) : '';
        if ($id <= 0 || empty($libelle)) {
            $_SESSION['admin_error'] = 'Veuillez fournir un nom valide.';
            $this->afficherGestionCategories();
            return;
        }
        GestionBoutique::updateCategorieLibelle($libelle, $id);
        $_SESSION['admin_message'] = 'Catégorie modifiée.';
        header('Location: index.php?controleur=Admin&action=afficherGestionCategories');
        exit;
    }

    public function supprimerCategorie() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $id = isset($_GET['idCategorie']) ? intval($_GET['idCategorie']) : 0;
        if ($id > 0) {
            // Supprimer les produits liés puis la catégorie
            GestionBoutique::supprimerProduitsParCategorie($id);
            GestionBoutique::supprimerCategorie($id);
            $_SESSION['admin_message'] = 'Catégorie supprimée.';
        }
        header('Location: index.php?controleur=Admin&action=afficherGestionCategories');
        exit;
    }

    public function afficherGestionProduits() {
        if (isset($_SESSION['login_admin'])) {
            // Préparer les données pour la vue
            $lesProduits = GestionBoutique::getLesProduits();
            $lesCategories = GestionBoutique::getLesCategories();
            require Chemins::VUES_ADMIN . 'v_gestion_produits.inc.php';
        } else
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
    }

    // Affiche le formulaire pour ajouter un produit
    public function afficherAjouterProduit() {
        if (isset($_SESSION['login_admin'])) {
            $lesCategories = GestionBoutique::getLesCategories();
            require Chemins::VUES_ADMIN . 'v_admin_ajouter_produit.inc.php';
        } else
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
    }

    public function ajouterProduit() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }

        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
        $image = isset($_POST['image']) ? trim($_POST['image']) : '';
        if (empty($image)) {
            $image = 'masque_connecter_affichage.png';
        }
        $idCategorie = isset($_POST['idCategorie']) ? intval($_POST['idCategorie']) : 0;

        if (empty($nom) || empty($description) || $prix <= 0 || $idCategorie <= 0) {
            $_SESSION['admin_error'] = 'Veuillez remplir tous les champs obligatoires correctement.';
            $this->afficherAjouterProduit();
            return;
        }

        GestionBoutique::ajouterProduitComplet($nom, $description, $prix, $image, $idCategorie);
        $_SESSION['admin_message'] = 'Produit ajouté.';
        header('Location: index.php?controleur=Admin&action=afficherGestionProduits');
        exit;
    }

    public function afficherModifierProduit() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $idProduit = isset($_GET['idProduit']) ? intval($_GET['idProduit']) : 0;
        if ($idProduit <= 0) {
            header('Location: index.php?controleur=Admin&action=afficherGestionProduits');
            exit;
        }
        $produit = GestionBoutique::getLesProduitsById($idProduit);
        $lesCategories = GestionBoutique::getLesCategories();
        require Chemins::VUES_ADMIN . 'v_admin_modifier_produit.inc.php';
    }

    public function modifierProduit() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $prix = isset($_POST['prix']) ? floatval($_POST['prix']) : 0;
        $image = isset($_POST['image']) ? trim($_POST['image']) : '';
        if (empty($image)) {
            $image = 'masque_connecter_affichage.png';
        }
        $idCategorie = isset($_POST['idCategorie']) ? intval($_POST['idCategorie']) : 0;

        if ($id <= 0 || empty($nom) || empty($description) || $prix <= 0 || $idCategorie <= 0) {
            $_SESSION['admin_error'] = 'Veuillez remplir tous les champs obligatoires correctement.';
            // Rebuild produit object to refill the form with submitted values
            $produit = new stdClass();
            $produit->id = $id;
            $produit->nom = $nom;
            $produit->description = $description;
            $produit->prix = $prix;
            $produit->image = $image;
            $produit->idCategorie = $idCategorie;
            $lesCategories = GestionBoutique::getLesCategories();
            require Chemins::VUES_ADMIN . 'v_admin_modifier_produit.inc.php';
            return;
        }

        GestionBoutique::updateProduit($id, $nom, $description, $prix, $image, $idCategorie);
        $_SESSION['admin_message'] = 'Produit modifié.';
        header('Location: index.php?controleur=Admin&action=afficherGestionProduits');
        exit;
    }

    public function supprimerProduit() {
        if (!isset($_SESSION['login_admin'])) {
            require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            return;
        }
        $idProduit = isset($_GET['idProduit']) ? intval($_GET['idProduit']) : 0;
        if ($idProduit > 0) {
            GestionBoutique::supprimerProduits($idProduit);
            $_SESSION['admin_message'] = 'Produit supprimé.';
        }
        header('Location: index.php?controleur=Admin&action=afficherGestionProduits');
        exit;
    }

}

?>