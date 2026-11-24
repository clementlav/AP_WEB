-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 24 nov. 2026 à 14:21
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lavigne_boutique`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `_deleteProduitById`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `_deleteProduitById` (IN `paramIdProduit` INT)   BEGIN
DELETE FROM produit WHERE id = paramIdProduit;
END$$

DROP PROCEDURE IF EXISTS `_selectClients`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `_selectClients` ()   BEGIN
	SELECT id, CONCAT(nom, ' ', prenom) AS Patronyme
	FROM client;
END$$

DROP PROCEDURE IF EXISTS `_selectNbCommandesByClient_PS`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `_selectNbCommandesByClient_PS` (IN `paramIdClient` INT)   BEGIN

SELECT COUNT(id) AS nbCommandes FROM commande WHERE commande.idClient = paramIdCLient;

END$$

DROP PROCEDURE IF EXISTS `_selectNbTuplesByTable`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `_selectNbTuplesByTable` (IN `paramNomTable` VARCHAR(50))   BEGIN
SET @req = CONCAT('SELECT COUNT(*) AS nbTuples FROM ', paramNomTable);
PREPARE exe FROM @req;
EXECUTE exe;
DEALLOCATE PREPARE exe;
END$$

--
-- Fonctions
--
DROP FUNCTION IF EXISTS `_selectNbCommandesByClient_FS`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `_selectNbCommandesByClient_FS` (`paramIdClient` INT) RETURNS INT NO SQL BEGIN

DECLARE nbCommandes INTEGER;

SET nbCommandes = (SELECT COUNT(id) FROM commande WHERE commande.idClient = paramIdClient);

RETURN nbCommandes;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
(1, 'Casques'),
(2, 'Masques'),
(3, 'Skis');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `rue` varchar(255) NOT NULL,
  `codePostal` int NOT NULL,
  `ville` varchar(255) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `passe` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `rue`, `codePostal`, `ville`, `tel`, `email`, `pseudo`, `passe`) VALUES
(1, 'Monteillet', 'Arthur', '1 paul cruize', 34410, 'Beziers', '0898786765', 'arthur.monteillet@gmail.com', 'arthur', '3c5ea4769c6160dfca95af6c4ddd1c5714360137'),
(2, 'Roux', 'Camille', '5 avenue des Sommets', 73120, 'Courchevel', '0479654321', 'camille.roux@mail.com', 'camille', '6c39579e8fe28516ba57713ef01cf1c6c43a04b4');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `idClient` int DEFAULT NULL,
  `attenteDeReglement` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `date`, `idClient`, `attenteDeReglement`) VALUES
(1, '2026-10-15', 1, 0),
(2, '2026-01-18', 2, 0),
(3, '2026-02-02', 1, 1),
(4, '2026-02-14', 2, 0),
(5, '2026-10-15', 1, 0),
(6, '2026-10-15', 1, 0),
(7, '2026-10-15', 1, 0),
(8, '2026-02-02', 1, 0),
(9, '2026-01-18', 2, 0),
(10, '2026-10-15', 1, 0),
(11, '2026-10-15', 1, 0),
(12, '2026-10-15', 2, 0),
(13, '2026-10-15', 1, 0),
(14, '2026-10-15', 1, 0),
(15, '2026-10-15', 1, 0),
(16, '2026-02-02', 1, 0),
(17, '2026-02-02', 1, 0),
(19, '2026-11-12', 2, 0),
(20, '2026-11-12', 2, 0),
(21, '2026-11-12', 2, 0),
(22, '2026-11-12', 2, 0),
(23, '2026-11-12', 2, 0),
(24, '2026-11-12', 2, 0),
(25, '2026-11-12', 2, 0),
(26, '2026-11-12', 2, 0);

--
-- Déclencheurs `commande`
--
DROP TRIGGER IF EXISTS `_before_insert_COMMANDE_verifPaiement`;
DELIMITER $$
CREATE TRIGGER `_before_insert_COMMANDE_verifPaiement` BEFORE INSERT ON `commande` FOR EACH ROW BEGIN
DECLARE myIdClient INTEGER;
DECLARE myCompteur INTEGER;
DECLARE myMessage varchar(100);

SET myIdClient = NEW.idClient;
SET myCompteur = (SELECT COUNT(*) FROM commande
						WHERE commande.idClient = myIdClient
						AND attenteDeReglement = 1);
						
if(myCompteur > 0) then 
SET myMessage = "Désolé, il faut d'abord régler les anciennes commande";
SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = myMessage;
END if;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `codePostal` int NOT NULL,
  `ville` varchar(255) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id`, `nom`, `rue`, `codePostal`, `ville`, `tel`, `email`) VALUES
(1, 'iSnow Tech', '8 rue de l Innovation', 38000, 'Grenoble', '0476112233', 'contact@isnowtech.com'),
(2, 'AlpinConnect', '22 rue des Cimes', 74000, 'Annecy', '0450223344', 'contact@alpinconnect.com');

-- --------------------------------------------------------

--
-- Structure de la table `lignedecommande`
--

DROP TABLE IF EXISTS `lignedecommande`;
CREATE TABLE IF NOT EXISTS `lignedecommande` (
  `idCommande` int NOT NULL,
  `idProduit` int NOT NULL,
  `quantite` int DEFAULT NULL,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `idProduit` (`idProduit`),
  KEY `idCommande` (`idCommande`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lignedecommande`
--

INSERT INTO `lignedecommande` (`idCommande`, `idProduit`, `quantite`) VALUES
(1, 1, 1),
(1, 3, 1),
(2, 6, 1),
(2, 9, 1),
(3, 2, 1),
(4, 5, 1),
(4, 8, 1),
(6, 4, 1),
(6, 8, 3),
(7, 8, 2),
(8, 2, 1),
(9, 8, 1),
(10, 1, 1),
(11, 1, 1),
(12, 1, 4),
(13, 1, 4),
(14, 1, 4),
(15, 4, 1),
(16, 2, 1),
(17, 2, 1),
(19, 1, 1),
(20, 1, 3),
(24, 2, 2);

--
-- Déclencheurs `lignedecommande`
--
DROP TRIGGER IF EXISTS `_before_insert_LIGNEDECOMMANDE_limite3Produits`;
DELIMITER $$
CREATE TRIGGER `_before_insert_LIGNEDECOMMANDE_limite3Produits` BEFORE INSERT ON `lignedecommande` FOR EACH ROW BEGIN
DECLARE myQuantite INTEGER;
DECLARE myMessage varchar(100);

SET myQuantite = NEW.quantite;

if(myQuantite > 3) then 
SET myMessage = "Désolé, vous ne pouvez pas commander plus de 3 exemplaires d'un produit";
SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = myMessage;
END if;

END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `_before_insert_LIGNEDECOMMANDE_quantiteInsuffisante`;
DELIMITER $$
CREATE TRIGGER `_before_insert_LIGNEDECOMMANDE_quantiteInsuffisante` BEFORE INSERT ON `lignedecommande` FOR EACH ROW BEGIN
DECLARE myIdProduit INTEGER;
DECLARE myStock INTEGER;
DECLARE myMessage varchar(100);

SET myIdProduit = NEW.idProduit;
SET myStock = (SELECT quantite FROM produit
						WHERE produit.id = myIdProduit);

if(myStock < NEW.quantite) then 
SET myMessage = "Désolé, le stock est insuffisant pour ce produit";
SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = myMessage;
END if;

END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `_before_insert_LIGNEDECOMMANDE_updateQteStockProduit`;
DELIMITER $$
CREATE TRIGGER `_before_insert_LIGNEDECOMMANDE_updateQteStockProduit` BEFORE INSERT ON `lignedecommande` FOR EACH ROW BEGIN

	DECLARE myQteCom INT; 
	DECLARE myIdProduit INT;
	
	SET myQteCom = NEW.quantite;
	SET myIdProduit = NEW.idProduit;
	
	UPDATE produit
	SET quantite = quantite - myQteCom
	WHERE id = myIdProduit;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  `prix` decimal(6,2) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `idCategorie` int DEFAULT NULL,
  `idFournisseur` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCategorie` (`idCategorie`),
  KEY `idFournisseur` (`idFournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `quantite`, `prix`, `image`, `idCategorie`, `idFournisseur`) VALUES
(1, 'Casque iSnow Vision', 'Casque connecté anti-choc, stabilisation de position et alertes météoo', 10, '219.90', 'casque_isnowvision.jpg', 1, 1),
(2, 'Casque SmartTrack', 'Casque avec localisation d urgence et audio intégré', 1, '189.50', 'casque_smartrack.jpg', 1, 2),
(3, 'Masque AR FrostGuard', 'Masque connecté avec HUD et correction anti-buée', 7, '149.00', 'masque_frostguard.jpg', 2, 1),
(4, 'Masque Visiotech', 'Masque premium avec affichage altitude et synchro phone', 2, '179.90', 'masque_visiotech.jpg', 2, 2),
(5, 'Casque ThermoGuard', 'Casque isolé thermique avec ventilation ajustable', 6, '199.50', 'casque_connecter.jpg', 1, 2),
(6, 'Masque NightVision', 'Masque avec vision nocturne et GPS intégré', 1, '299.90', 'masque_connecter.webp', 2, 2),
(7, 'Ski AllMountain X1', 'Ski polyvalent pour toutes les pistes', 5, '399.90', 'ski_allmountainx1.jpg', 3, 1),
(8, 'Ski Freestyle Pro', 'Ski freestyle avec cambre spécial park', 5, '349.50', 'ski_freestyle.webp', 3, 2),
(9, 'Ski E-Skimo', 'Ski électrique connecté', 3, '499.90', 'E-Skimo.webp', 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(120) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifiant` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `passe`, `nom`, `role`, `created_at`, `updated_at`) VALUES
(1, 'adminSki', 'a227047a05306bce6fa7dc6d8f6dea6e9b01c9b0', 'Administrateur', 'admin', '2026-09-03 07:09:11', '2026-09-03 09:06:53'),
(2, 'gerantBoutique', '$2y$10$gN7/M6jP/s7K2H1wQ/jUZeX4aVf/Zn4n2kH/N3L6v9I4N7A3y9O8b', 'Gérant Boutique', 'admin', '2026-09-03 07:09:11', '2026-09-03 07:09:11'),
(3, 'vendeur1', '$2y$10$rT5/K4hQ/x7K2H1wQ/jUZeX4aVf/Zn4n2kH/N3L6v9I4N7A3y9O8b', 'Vendeur', 'user', '2026-09-03 07:09:11', '2026-09-03 07:09:11');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `lignedecommande`
--
ALTER TABLE `lignedecommande`
  ADD CONSTRAINT `lignedecommande_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `lignedecommande_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`id`),
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`idFournisseur`) REFERENCES `fournisseur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
