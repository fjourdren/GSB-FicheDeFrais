-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 04 Juillet 2011 à 14:08
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `gsb_frais`
--

-- --------------------------------------------------------

--
-- Structure de la table `Forfait`
--

CREATE TABLE IF NOT EXISTS `Forfait` (
  `id` char(3) NOT NULL,
  `libelle` char(30) DEFAULT NULL,
  `montant` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `Etat`
--

CREATE TABLE IF NOT EXISTS `Etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `Visiteur`
--

CREATE TABLE IF NOT EXISTS `Visiteur` (
  `id` char(4) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `prenom` varchar(60)  NOT NULL, 
  `adresse` varchar(120) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` varchar(60) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  `login` char(60) NOT NULL,
  `pwd` varchar(30)  NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `FicheFrais`
--

CREATE TABLE IF NOT EXISTS `Fichefrais` (
  `id` int(11) auto_increment,
  `idVisiteur` char(4) NOT NULL,
  `mois` tinyint UNSIGNED NOT NULL,
  `annee` smallint UNSIGNED NOT NULL,
  `nbJustificatifs` tinyint UNSIGNED DEFAULT NULL,
  `montantValide` decimal(10,2) DEFAULT NULL,
  `dateModif` date DEFAULT NULL,
  `idEtat` char(2) DEFAULT 'CR',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`idEtat`) REFERENCES Etat(`id`),
  FOREIGN KEY (`idVisiteur`) REFERENCES Visiteur(`id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `LigneFraisForfait`
--

CREATE TABLE IF NOT EXISTS `LigneFraisForfait` (
  `idFicheFrais` int(11) NOT NULL,
  `idForfait` char(3) NOT NULL,
  `quantite` smallint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`idFicheFrais`, `idForfait`),
  FOREIGN KEY (`idFicheFrais`) REFERENCES FicheFrais(`id`),
  FOREIGN KEY (`idForfait`) REFERENCES Forfait(`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `LigneFraisHorsForfait`
--

CREATE TABLE IF NOT EXISTS `LigneFraisHorsForfait` (
  `idFraisHF` 	 int(11) auto_increment,
  `idFicheFrais` int(11) NOT NULL,
  `dteFraisHF` 	date DEFAULT NULL,
  `libFraisHF` 	varchar(60) DEFAULT NULL,
  `quantite` 	smallint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`idFraisHF`),
  FOREIGN KEY (`idFicheFrais`) REFERENCES FicheFrais(`id`),
) ENGINE=InnoDB;

-- --------------------------------------------------------

