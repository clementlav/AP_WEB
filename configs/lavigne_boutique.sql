-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 sep. 2026 à 07:26
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
  `prenom` varchar(30) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `codePostal` int NOT NULL,
  `ville` varchar(255) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `rue`, `codePostal`, `ville`, `tel`, `email`) VALUES
(1, 'Lucas Meyer', '12 rue des Glaciers', 74400, 'Chamonix', '0450123456', 'lucas.meyer@mail.com'),
(2, 'Camille Roux', '5 avenue des Sommets', 73120, 'Courchevel', '0479654321', 'camille.roux@mail.com');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `idClient` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `date`, `idClient`) VALUES
(1, '2026-01-05', 1),
(2, '2026-01-18', 2),
(3, '2026-02-02', 1),
(4, '2026-02-14', 2);

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
(2, 9, 1),
(2, 6, 1),
(3, 2, 1),
(4, 5, 1),
(4, 8, 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `prix` decimal(6,2) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `idCategorie` int DEFAULT NULL,
  `idFournisseur` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCategorie` (`idCategorie`),
  KEY `idFournisseur` (`idFournisseur`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `image`, `idCategorie`, `idFournisseur`) VALUES
(1, 'Casque iSnow Vision', 'Casque connecté anti-choc, stabilisation de position et alertes météo', '219.90', 'casque_isnow_vision.jpg', 1, 1),
(2, 'Casque SmartTrack', 'Casque avec localisation d urgence et audio intégré', '189.50', 'casque_smartrack.jpg', 1, 2),
(3, 'Masque AR FrostGuard', 'Masque connecté avec HUD et correction anti-buée', '149.00', 'masque_frostguard.jpg', 2, 1),
(4, 'Masque Visiotech', 'Masque premium avec affichage altitude et synchro phone', '179.90', 'masque_visiotech.jpg', 2, 2),
(5, 'Casque ThermoGuard', 'Casque isolé thermique avec ventilation ajustable', '199.50', 'casque_connecter.jpg', 1, 2),
(6, 'Masque NightVision', 'Masque avec vision nocturne et GPS intégré', '299.90', 'masque_connecter.webp', 2, 2),
(7, 'Ski AllMountain X1', 'Ski polyvalent pour toutes les pistes', '399.90', 'ski_allmountainx1.jpg', 3, 1),
(8, 'Ski Freestyle Pro', 'Ski freestyle avec cambre spécial park', '349.50', 'ski_freestyle.webp', 3, 2),
(9, 'Ski E-Skimo', 'Ski électrique connecté', '499.90', 'E-Skimo.webp', 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
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
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--
-- Remarque : remplacez ces mots de passe par vos propres hachages bcrypt avant mise en production.
--

INSERT INTO `user` (`id`, `login`, `passe`, `nom`, `role`, `created_at`, `updated_at`) VALUES
(1, 'adminSki', '$2y$10$8L7/S6hR/t7K2H1wQ/jUZeX4aVf/Zn4n2kH/N3L6v9I4N7A3y9O8b', 'Administrateur', 'admin', '2026-09-03 07:09:11', '2026-09-03 07:14:40'),
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