<?php

// GestionBoutique hérite de ModelePDO : la connexion à la base et les méthodes
// génériques (getLesTuples, getPremierTupleByChamp, ...) sont ainsi héritées.
require_once __DIR__ . '/ModelePDO.class.php';

class GestionBoutique extends ModelePDO
{

#region Méthodes statiques

    /**
     * Retourne la liste des catégories
     * @return array Tableau d'objets
     */
    public static function getLesCategories()
    {
        return self::getLesTuples("Categorie");
    }

    /**
     * Retourne la liste des produits
     * @return array Tableau d'objets
     */
    public static function getLesProduits()
    {
        return self::getLesTuples("Produit");
    }

    /**
     * Retourne les produits d'une catégorie
     * @param string $libelleCategorie Libellé de la catégorie
     * @return array Tableau d'objets
     */
    public static function getLesProduitsByCategorie($libelleCategorie)
    {
        $pdo = self::seConnecter();

        self::$requete = "SELECT P.*, C.libelle AS categorie FROM Produit P
        JOIN Categorie C ON P.idCategorie = C.id
        WHERE LOWER(C.libelle) = LOWER(:libCateg)";

        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('libCateg', $libelleCategorie);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Retourne un produit par son id
     * @param int $idProduit
     * @return object|null
     */
    public static function getLesProduitsById($idProduit)
    {
        return self::getPremierTupleByChamp("Produit", "id", $idProduit);
    }

    /**
     * Retourne une catégorie par son id
     * @param int $id
     * @return object|null
     */
    public static function getCategorieById($id)
    {
        return self::getPremierTupleByChamp("Categorie", "id", $id);
    }

    /**
     * Retourne le nombre de produits
     * @return object Nombre de produits (propriété nbProduits)
     */
    public static function getNbProduits()
    {
        $pdo = self::seConnecter();

        $requete = "SELECT COUNT(*) AS nbProduits FROM Produit";
        $pdoStatement = $pdo->prepare($requete);
        $pdoStatement->execute();
        $resultat = $pdoStatement->fetch(PDO::FETCH_OBJ);

        $pdoStatement->closeCursor();

        return $resultat;
    }

    /**
     * Ajoute une ligne dans la table Catégorie
     * @param string $libelleCateg Libellé de la Catégorie
     */
    public static function ajouterCategorie($libelleCateg)
    {
        $pdo = self::seConnecter();

        self::$requete = "insert into Categorie(libelle) values(:libelle)";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('libelle', $libelleCateg);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function ajouterProduits($nomProduit, $idCategorie)
    {
        $pdo = self::seConnecter();

        self::$requete = "insert into Produit(nom, idCategorie) values(:nom, :idCategorie)";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('nom', $nomProduit);
        self::$pdoStResults->bindValue('idCategorie', $idCategorie);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function ajouterProduitComplet($nom, $description, $prix, $image, $idCategorie)
    {
        $pdo = self::seConnecter();

        self::$requete = "INSERT INTO Produit (nom, description, prix, image, idCategorie) VALUES (:nom, :description, :prix, :image, :idCategorie)";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('nom', $nom);
        self::$pdoStResults->bindValue('description', $description);
        self::$pdoStResults->bindValue('prix', $prix);
        self::$pdoStResults->bindValue('image', $image);
        self::$pdoStResults->bindValue('idCategorie', $idCategorie);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function updateProduitImage($image, $nom)
    {
        $pdo = self::seConnecter();

        self::$requete = "UPDATE Produit SET image = :image WHERE nom = :nom";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('image', $image);
        self::$pdoStResults->bindValue('nom', $nom);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function updateProduit($id, $nom, $description, $prix, $image, $idCategorie)
    {
        $pdo = self::seConnecter();

        self::$requete = "UPDATE Produit SET nom = :nom, description = :description, prix = :prix, image = :image, idCategorie = :idCategorie WHERE id = :id";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('id', $id, PDO::PARAM_INT);
        self::$pdoStResults->bindValue('nom', $nom);
        self::$pdoStResults->bindValue('description', $description);
        self::$pdoStResults->bindValue('prix', $prix);
        self::$pdoStResults->bindValue('image', $image);
        self::$pdoStResults->bindValue('idCategorie', $idCategorie, PDO::PARAM_INT);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function updateCategorieLibelle($libelle, $id)
    {
        $pdo = self::seConnecter();

        self::$requete = "UPDATE categorie SET libelle = :libelle WHERE id = :id";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('libelle', $libelle);
        self::$pdoStResults->bindValue('id', $id);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function supprimerProduits($idProduit)
    {
        $pdo = self::seConnecter();

        self::$requete = "delete from Produit where id = :idProduit";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('idProduit', $idProduit);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    public static function supprimerProduitsParCategorie($idCategorie)
    {
        $pdo = self::seConnecter();

        self::$requete = "DELETE FROM Produit WHERE idCategorie = :idCategorie";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('idCategorie', $idCategorie);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    /**
     * Supprime une catégorie par id
     * @param int $id
     */
    public static function supprimerCategorie($id)
    {
        $pdo = self::seConnecter();

        self::$requete = "DELETE FROM Categorie WHERE id = :id";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('id', $id, PDO::PARAM_INT);
        self::$pdoStResults->execute();
        self::$pdoStResults->closeCursor();
    }

    /**
     * Vérifie si l'utilisateur est un administrateur présent dans la base
     * @param string $login Login de l'utilisateur
     * @param string $passe Mot de passe en clair de l'utilisateur
     * @return bool
     */
    public static function isAdminOK($login, $passe)
    {
        $pdo = self::seConnecter();

        self::$requete = "SELECT * FROM User where login=:login and passe=:passe and role='admin'";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('login', $login);
        self::$pdoStResults->bindValue('passe', sha1($passe));
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat != null;
    }

    /**
     * Vérifie les identifiants d'un client du site.
     */
    public static function isUserOK($login, $passe)
    {
        $pdo = self::seConnecter();

        $requete = "SELECT id, pseudo, passe, nom, prenom FROM client WHERE pseudo = :pseudo LIMIT 1";
        $pdoStatement = $pdo->prepare($requete);
        $pdoStatement->bindValue('pseudo', $login);
        $pdoStatement->execute();
        $utilisateur = $pdoStatement->fetch(PDO::FETCH_OBJ);

        $pdoStatement->closeCursor();

        if (!$utilisateur) {
            return false;
        }

        $motDePasseValide = hash_equals($utilisateur->passe, sha1($passe));

        if (!$motDePasseValide && substr($utilisateur->passe, 0, 4) === '$2y$') {
            $motDePasseValide = password_verify($passe, $utilisateur->passe);
        }

        if (!$motDePasseValide) {
            return false;
        }

        $utilisateur->login = $utilisateur->pseudo;
        $utilisateur->nom = trim($utilisateur->prenom . ' ' . $utilisateur->nom);
        unset($utilisateur->pseudo, $utilisateur->passe, $utilisateur->prenom);

        return $utilisateur;
    }

    /**
     * Recherche si un pseudo existe exactement ou propose des pseudos commençant par la saisie.
     */
    public static function rechercherPseudos($terme, $limite = 8)
    {
        $pdo = self::seConnecter();
        $terme = trim($terme);

        if ($terme === '') {
            return array();
        }

        self::$requete = "SELECT pseudo FROM client WHERE pseudo LIKE :terme ORDER BY pseudo ASC LIMIT :limite";
        self::$pdoStResults = $pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue('terme', $terme . '%');
        self::$pdoStResults->bindValue('limite', (int) $limite, PDO::PARAM_INT);
        self::$pdoStResults->execute();
        $resultats = self::$pdoStResults->fetchAll(PDO::FETCH_COLUMN);
        self::$pdoStResults->closeCursor();

        return $resultats;
    }

#endregion

}
