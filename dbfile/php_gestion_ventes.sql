-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 oct. 2023 à 01:32
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `php_gestion_ventes`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `Code_Client` bigint(20) UNSIGNED NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Pseudo` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`Code_Client`, `Nom`, `Pseudo`, `Password`) VALUES
(1, 'Marechal', 'Client-001', 'Cli-001'),
(2, 'Harel', 'Client-002', 'Cli-002'),
(3, 'Guichar', 'Client-003', 'Cli-003'),
(4, 'Landecy', 'Client-004', 'Cli-004'),
(5, 'Lapeyer', 'Client-005', 'Cli-005'),
(6, 'Villain', 'Client-006', 'Cli-006'),
(7, 'Belorgey', 'Client-007', 'Cli-007'),
(8, 'Boufares', 'Client-008', 'Cli-008'),
(9, 'Grenier', 'Client-009', 'Cli-009'),
(10, 'Souque', 'Client-010', 'Cli-010'),
(11, 'Keromnes', 'Client-011', 'Cli-011'),
(12, 'Labrune', 'Client-012', 'Cli-012'),
(13, 'Masson', 'Client-013', 'Cli-013'),
(14, 'Cussy', 'Client-014', 'Cli-014'),
(15, 'Vandendriessche', 'Client-015', 'Cli-015'),
(16, 'yassir', 'Client-016', '123456'),
(17, 'yassir', 'Client-1', '123456'),
(18, 'yassir', 'Client-2', '123456'),
(19, 'yassir', 'Client-123456', '123456'),
(20, 'yassir', 'Client123456', '123456');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `Numéro_Commande` bigint(20) UNSIGNED NOT NULL,
  `Code_Client` bigint(20) NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`Numéro_Commande`, `Code_Client`, `Date`) VALUES
(1, 13, '1992-01-12'),
(2, 14, '1992-01-12'),
(3, 1, '1992-02-18'),
(4, 2, '1992-03-25'),
(5, 5, '1992-08-30'),
(6, 10, '1992-12-27'),
(7, 8, '1993-02-12'),
(8, 11, '1993-03-18'),
(9, 2, '1993-04-20'),
(10, 7, '1993-05-05'),
(11, 15, '1993-05-15'),
(12, 13, '1993-05-29'),
(13, 14, '1993-06-06'),
(14, 13, '1993-06-22'),
(15, 15, '1993-07-01'),
(16, 1, '1993-07-24'),
(17, 4, '1993-07-30'),
(18, 6, '1993-07-31'),
(19, 7, '1993-08-01'),
(21, 1, '2022-01-22'),
(23, 1, '2022-01-22'),
(24, 1, '2022-01-22'),
(25, 1, '2022-01-22'),
(26, 15, '2022-02-09'),
(27, 1, '2022-03-09');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `Code_F` bigint(20) UNSIGNED NOT NULL,
  `Nom_F` varchar(50) NOT NULL,
  `Ville_F` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`Code_F`, `Nom_F`, `Ville_F`) VALUES
(1, 'Mohammedi', 'Vannes Cedex'),
(2, 'Hazouard', 'Troys Cedex'),
(3, 'Perrigault', 'Villeneuve d\'Asco'),
(4, 'Supriez', 'Cosnes Longwy'),
(5, 'Kern', 'SChiltigheim'),
(6, 'Cornette', 'Lieusaint'),
(7, 'Arabyan', 'Avon'),
(8, 'Baudru', 'Auch'),
(9, 'Lebreton', 'Octeville'),
(10, 'De Bry', 'Magnanville'),
(11, 'Vrevin', 'Every Cedex'),
(12, 'Helin', 'Vienne'),
(13, 'Perrault', 'Saint Malo'),
(14, 'Olivier', 'Bobigny'),
(15, 'Barbe', 'Nimes'),
(16, 'Prat', 'Niort'),
(17, 'Nakache', 'Gap Cedex'),
(18, 'Ordronneau', 'Meaux'),
(19, '', ''),
(20, '', ''),
(21, 'Bubler', 'Saint-Denis');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `Numéro_Commande` bigint(20) NOT NULL,
  `Code_Produit` bigint(20) NOT NULL,
  `Qte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`Numéro_Commande`, `Code_Produit`, `Qte`) VALUES
(1, 13, 2),
(2, 5, 1),
(2, 7, 1),
(3, 7, 3),
(3, 8, 1),
(3, 10, 1),
(4, 12, 5),
(5, 2, 3),
(6, 6, 2),
(7, 7, 6),
(8, 13, 10),
(9, 12, 50),
(10, 2, 8),
(11, 7, 40),
(12, 1, 2),
(13, 11, 10),
(13, 12, 1),
(14, 2, 1),
(14, 10, 1),
(15, 7, 1),
(15, 8, 2),
(16, 3, 6),
(16, 4, 4),
(16, 8, 10),
(18, 9, 3),
(19, 2, 2),
(20, 1, 10),
(20, 2, 10),
(21, 1, 10),
(21, 2, 10),
(21, 3, 10),
(22, 1, 9),
(23, 1, 10),
(23, 2, 11),
(24, 1, 20),
(25, 11, 1),
(26, 1, 10),
(27, 1, 12),
(27, 8, 5);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `Code_Produit` bigint(20) UNSIGNED NOT NULL,
  `Désignation` varchar(50) DEFAULT NULL,
  `Prix_Unitaire` double(10,2) DEFAULT NULL,
  `Famille` varchar(2) DEFAULT NULL,
  `Code_F` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`Code_Produit`, `Désignation`, `Prix_Unitaire`, `Famille`, `Code_F`) VALUES
(1, 'Coins à lettres', 27.60, NULL, 1),
(2, 'Etiquettes', 54.50, NULL, 1),
(3, 'Imprimantes la Jolie', 3450.00, NULL, 3),
(4, 'Manuel Utile', 137.00, NULL, 16),
(5, 'Micro Super Plus', 8990.00, NULL, 15),
(6, 'Informatique Facile', 150.00, NULL, 15),
(7, 'Souris Optique', 235.00, NULL, 15),
(8, 'Agenda', 47.50, NULL, 4),
(9, 'Guide d\'achat des micros', 174.95, NULL, 5),
(10, 'Ecran Protection', 120.00, NULL, 6),
(11, 'Clé USB', 89.00, NULL, 6),
(12, 'Machine à écrire', 1700.00, NULL, 1),
(13, 'Trombones', 12.95, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Code_Client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`Numéro_Commande`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`Code_F`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`Numéro_Commande`,`Code_Produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`Code_Produit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `Code_Client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `Numéro_Commande` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `Code_F` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `Code_Produit` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
