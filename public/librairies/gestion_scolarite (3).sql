-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 04 août 2022 à 11:25
-- Version du serveur :  5.7.21
-- Version de PHP :  5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gestion_scolarite`
--

-- --------------------------------------------------------

--
-- Structure de la table `apprenant`
--

DROP TABLE IF EXISTS `apprenant`;
CREATE TABLE IF NOT EXISTS `apprenant` (
  `id_apprenant` int(5) NOT NULL AUTO_INCREMENT,
  `id_formation` int(4) NOT NULL,
  `matricule` varchar(10) DEFAULT NULL,
  `saison` varchar(20) NOT NULL,
  `debut` date NOT NULL,
  `fin` date NOT NULL,
  `nomApp` varchar(40) NOT NULL,
  `prenom` varchar(40) NOT NULL,
  `sexe` char(1) NOT NULL,
  `date_naissance` varchar(13) NOT NULL,
  `CNI` varchar(40) NOT NULL,
  `telephone` int(9) NOT NULL,
  `photos` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_apprenant`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `apprenant`
--

INSERT INTO `apprenant` (`id_apprenant`, `id_formation`, `matricule`, `saison`, `debut`, `fin`, `nomApp`, `prenom`, `sexe`, `date_naissance`, `CNI`, `telephone`, `photos`) VALUES
(1, 2, '8AB2', 'jour', '2022-07-01', '2022-10-23', 'LAPORTE', 'Aurelie', 'F', '2000-04-20', '5367437473', 678590320, 'girl1.jpg'),
(2, 1, '5096', 'jour', '2022-07-23', '2022-08-23', 'DUBOIS', 'Francis', 'M', '1999-04-30', '5748587498', 658003920, 'boy1.jpg'),
(3, 2, '5E2C', 'jour', '2022-07-01', '2022-10-23', 'NWOLE', 'Elow&#039;n', 'M', '2001-10-10', '543647636', 674893940, 'boy2.png'),
(4, 3, '25CE', 'jour', '2022-07-10', '2022-09-10', 'DUPONT', 'Jeanne', 'F', '1998-03-10', '5276476334', 699003049, 'girl2.png'),
(5, 1, '8FD5', 'jour', '2022-07-23', '2022-08-23', 'NJIEGOM', 'Eloge', 'M', '2002-04-26', '6478346738', 675849302, 'boy3.png'),
(6, 1, '3A16', 'jour', '2022-07-23', '2022-08-23', 'TCHINDA', 'Augustin', 'M', '2000-05-20', '634747634', 677489594, 'boy4.jpg'),
(7, 2, '2C6C', 'soir', '2022-07-10', '2023-02-24', 'KAMDEM', 'Jean', 'M', '2000-02-10', '6556787877', 677879809, 'boy6.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id_formation` int(4) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `serie` varchar(30) NOT NULL,
  `control` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id_formation`, `nom`, `serie`, `control`) VALUES
(1, 'Art oratoire', 'Expression', 1),
(2, 'Developpement web', 'Informatique', 1),
(3, 'Pack office', 'Bureautique', 1),
(5, 'Administration réseau', 'Informatique', 0);

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `id_gestionnaire` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nom` varchar(40) NOT NULL,
  `prenomgest` varchar(40) NOT NULL,
  `sexe` char(1) NOT NULL,
  `date_naissance` varchar(25) NOT NULL,
  `telephone1` int(9) NOT NULL,
  `telephone2` int(9) DEFAULT '0',
  `CNI` varchar(40) NOT NULL,
  `privilege` varchar(15) NOT NULL,
  `autorisation` varchar(10) NOT NULL,
  PRIMARY KEY (`id_gestionnaire`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`id_gestionnaire`, `username`, `password`, `nom`, `prenomgest`, `sexe`, `date_naissance`, `telephone1`, `telephone2`, `CNI`, `privilege`, `autorisation`) VALUES
(1, 'russel', '2000', 'TCHOGA', 'Russel', 'M', '1999-05-09', 678485949, 657843948, '643744646', 'administrateur', 'oui'),
(2, 'steve', 'password', 'LAPORTE', 'Kilian', 'M', '2000-10-10', 678879797, 0, '343673474', 'sécrétaire', 'oui'),
(3, 'chris', 'password', 'DONFACK', 'Christelle', 'F', '2000-05-10', 699009302, 0, '643748393', 'sécrétaire', 'oui'),
(5, 'nono', 'password', 'NONO', 'Francis', 'M', '2000-03-10', 677939403, 0, '763498437643', 'sécrétaire', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `scolarite`
--

DROP TABLE IF EXISTS `scolarite`;
CREATE TABLE IF NOT EXISTS `scolarite` (
  `id_scolarite` int(11) NOT NULL AUTO_INCREMENT,
  `id_apprenant` int(4) NOT NULL,
  `inscription` int(6) NOT NULL,
  `tranche1` int(6) NOT NULL,
  `tranche2` int(6) NOT NULL,
  `tranche3` int(6) NOT NULL,
  `reste` int(6) NOT NULL,
  PRIMARY KEY (`id_scolarite`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `scolarite`
--

INSERT INTO `scolarite` (`id_scolarite`, `id_apprenant`, `inscription`, `tranche1`, `tranche2`, `tranche3`, `reste`) VALUES
(1, 1, 0, 95000, 80000, 50000, 225000),
(2, 2, 0, 0, 25000, 20000, 45000),
(3, 3, 0, 80000, 80000, 50000, 210000),
(4, 4, 0, 50000, 30000, 10000, 90000),
(5, 5, 0, 15000, 35000, 20000, 70000),
(6, 6, 0, 45000, 35000, 20000, 100000),
(7, 7, 0, 45000, 50000, 30000, 125000);

-- --------------------------------------------------------

--
-- Structure de la table `tarif`
--

DROP TABLE IF EXISTS `tarif`;
CREATE TABLE IF NOT EXISTS `tarif` (
  `id_tarif` int(4) NOT NULL AUTO_INCREMENT,
  `serie` varchar(20) NOT NULL,
  `saison` varchar(20) NOT NULL,
  `debut` date NOT NULL,
  `fin` date NOT NULL,
  `tranche1` int(6) NOT NULL,
  `tranche2` int(6) NOT NULL,
  `tranche3` int(6) NOT NULL,
  `limite1` date NOT NULL,
  `limite2` date NOT NULL,
  `limite3` date NOT NULL,
  `total` int(7) NOT NULL,
  `control` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tarif`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `serie`, `saison`, `debut`, `fin`, `tranche1`, `tranche2`, `tranche3`, `limite1`, `limite2`, `limite3`, `total`, `control`) VALUES
(1, 'Informatique', 'Jour', '2022-07-01', '2022-10-23', 120000, 80000, 50000, '2022-07-23', '2022-08-10', '2022-09-09', 250000, 0),
(2, 'Expression', 'Jour', '2022-07-23', '2022-08-23', 45000, 35000, 20000, '2022-07-30', '2022-08-06', '2022-08-14', 100000, 0),
(3, 'Bureautique', 'Jour', '2022-07-10', '2022-09-10', 50000, 30000, 10000, '2022-07-23', '2022-08-05', '2022-08-25', 90000, 0),
(4, 'Informatique', 'Soir', '2022-07-10', '2023-02-24', 70000, 50000, 30000, '2022-07-24', '2022-09-24', '2022-11-24', 150000, 0);

-- --------------------------------------------------------

--
-- Structure de la table `versement`
--

DROP TABLE IF EXISTS `versement`;
CREATE TABLE IF NOT EXISTS `versement` (
  `id_versement` int(6) NOT NULL AUTO_INCREMENT,
  `id_apprenant` int(4) NOT NULL,
  `id_gestionnaire` int(4) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `montant` int(6) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_versement`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `versement`
--

INSERT INTO `versement` (`id_versement`, `id_apprenant`, `id_gestionnaire`, `type`, `montant`, `date`) VALUES
(1, 1, 1, 'Espèce', 50000, '2022-07-30 07:35:12'),
(2, 2, 1, 'Espèce', 80000, '2022-07-30 07:37:05'),
(3, 3, 1, 'Paymaster', 65000, '2022-07-30 07:38:57'),
(4, 4, 1, 'Espèce', 25000, '2022-07-30 07:41:38'),
(5, 5, 3, 'Espèce', 55000, '2022-07-30 07:48:08'),
(6, 6, 3, 'Espèce', 25000, '2022-07-30 07:50:46'),
(7, 7, 1, 'Espèce', 50000, '2022-07-30 12:55:29');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
