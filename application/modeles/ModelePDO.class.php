<?php

// La classe a besoin des constantes de connexion définies dans MysqlConfig.
// On inclut le fichier de configuration depuis l'emplacement réel de ce modèle
// (dossier "application/modeles"), ce qui évite de dépendre du dossier courant.
require_once __DIR__ . '/../../configs/mysql_config.class.php';

/**
 * Modèle PDO générique du projet.
 *
 * Regroupe la connexion à la base (MysqlConfig) et les opérations de base
 * (liste des tuples d'une table, recherche / suppression sur un champ).
 * Les classes métier (ex : GestionBoutique) en héritent pour réutiliser
 * la connexion et ces méthodes sans les réécrire.
 */
class ModelePDO
{

#region Champs statiques

    //Attributs utiles pour la connexion
    protected static $serveur = MysqlConfig::SERVEUR;
    protected static $port = MysqlConfig::PORT;
    protected static $base = MysqlConfig::BASE;
    protected static $utilisateur = MysqlConfig::UTILISATEUR;
    protected static $passe = MysqlConfig::MOT_DE_PASSE;

    //Attributs utiles pour la manipulation PDO de la BD

    /**
     * Objet de la classe PDO
     * @var PDO
     */
    protected static $pdo = null;

    /**
     * Objet de la classe PDOStatement
     * @var PDOStatement
     */
    protected static $pdoStResults = null;

    protected static $requete = ""; //texte de la requête

    protected static $resultat = null; //résultat de la requête

#endregion

#region Connexion

    /**
     * Permet de se connecter à la base de données.
     * La connexion n'est ouverte qu'une seule fois.
     * @return PDO
     */
    public static function seConnecter()
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . self::$serveur . ';port=' . self::$port . ';dbname=' . self::$base,
                    self::$utilisateur,
                    self::$passe
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                self::$pdo->query("SET CHARACTER SET utf8");
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    /**
     * Ferme la connexion à la base de données
     */
    public static function seDeconnecter()
    {
        self::$pdo = null;
        self::$pdoStResults = null;
        self::$resultat = null;
    }

#endregion

#region Méthodes génériques

    /**
     * Retourne tous les tuples d'une table
     * @param string $table Nom de la table
     * @return array Tableau d'objets
     */
    protected static function getLesTuples($table)
    {
        self::seConnecter();

        self::$requete = "SELECT * FROM " . $table;
        self::$pdoStResults = self::$pdo->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Retourne le premier tuple d'une table correspondant à un champ et une valeur
     * @param string $table Nom de la table
     * @param string $nomChamp Nom du champ recherché
     * @param mixed $valeurChamp Valeur recherchée
     * @return object|null Un seul tuple (objet) ou null
     */
    protected static function getPremierTupleByChamp($table, $nomChamp, $valeurChamp)
    {
        self::seConnecter();

        self::$requete = "SELECT * FROM " . $table . " WHERE " . $nomChamp . " = :valeurChamp";
        self::$pdoStResults = self::$pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue(':valeurChamp', $valeurChamp);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat; //un seul tuple retourné : utilisation de fetch()
    }

    /**
     * Supprime les tuples d'une table correspondant à un champ et une valeur
     * @param string $table Nom de la table
     * @param string $nomChamp Nom du champ de recherche
     * @param mixed $valeurChamp Valeur recherchée
     * @return int Nombre de tuples supprimés
     */
    protected static function supprimerTupleByChamp($table, $nomChamp, $valeurChamp)
    {
        self::seConnecter();

        self::$requete = "DELETE FROM " . $table . " WHERE " . $nomChamp . " = :valeurChamp";
        self::$pdoStResults = self::$pdo->prepare(self::$requete);
        self::$pdoStResults->bindValue(':valeurChamp', $valeurChamp);
        self::$pdoStResults->execute();
        $nbSupprimes = self::$pdoStResults->rowCount();

        self::$pdoStResults->closeCursor();

        return $nbSupprimes;
    }

    /**
     * Exécute une requête SELECT libre
     * @param string $champs Champs à sélectionner
     * @param string $tables Table(s) utilisée(s)
     * @param string|null $conditions Clause WHERE (sans le mot WHERE)
     * @return array Tableau associatif
     */
    protected static function select($champs, $tables, $conditions = null)
    {
        self::seConnecter();

        self::$requete = "SELECT " . $champs . " FROM " . $tables;
        if ($conditions != null) {
            self::$requete .= " WHERE " . $conditions;
        }

        self::$pdoStResults = self::$pdo->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetchAll(PDO::FETCH_ASSOC);

        self::$pdoStResults->closeCursor();

        return $resultat;
    }

#endregion

}
