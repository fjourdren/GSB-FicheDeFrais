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
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(60) NOT NULL,
  `prenom` varchar(60)  NOT NULL,
  `adresse` varchar(120) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` varchar(60) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  `login` char(60) NOT NULL,
  `pwd` char(32)  NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Structure de la table `FicheFrais`
--

CREATE TABLE IF NOT EXISTS `FicheFrais` (
  `id` int(11) UNSIGNED auto_increment,
  `idVisiteur` int UNSIGNED NOT NULL,
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
  `idFicheFrais` int(11) UNSIGNED NOT NULL,
  `idForfait` char(3) NOT NULL,
  `quantite` smallint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`idFicheFrais`, `idForfait`)
) ENGINE=InnoDB;

ALTER TABLE `LigneFraisForfait` ADD CONSTRAINT `LigneFraisForfait_FicheFrais` FOREIGN KEY (`idFicheFrais`) REFERENCES `FicheFrais`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `LigneFraisForfait` ADD CONSTRAINT `LigneFraisForfait_Forfait` FOREIGN KEY (`idForfait`) REFERENCES `Forfait`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Structure de la table `LigneFraisHorsForfait`
--

CREATE TABLE IF NOT EXISTS `LigneFraisHorsForfait` (
  `idFraisHF`    int(11) UNSIGNED auto_increment,
  `idFicheFrais` int(11) UNSIGNED NOT NULL,
  `dteFraisHF`  date DEFAULT NULL,
  `libFraisHF`  varchar(60) DEFAULT NULL,
  `quantite`  smallint UNSIGNED DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT 0,
  PRIMARY KEY (`idFraisHF`)
) ENGINE=InnoDB;

ALTER TABLE `LigneFraisHorsForfait` ADD CONSTRAINT `LigneFraisHorsForfait_FicheFrais` FOREIGN KEY (`idFicheFrais`) REFERENCES `FicheFrais`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------


--
-- Insert `Visiteur`
--

INSERT INTO Visiteur(nom, prenom, login, pwd, DateEmbauche) VALUES
('comptable', 'comptable', 'comptable', md5('comptable'), NOW()),
('admin', 'admin', 'admin', md5('admin'), NOW());

-- --------------------------------------------------------


--
-- Insert `Etat`
--

INSERT INTO `Etat` (`id`, `libelle`) VALUES
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('RB', 'Remboursée'),
('VA', 'Validée et mise en paiement');

-- --------------------------------------------------------


--
-- Insert `Forfait`
--


INSERT INTO `Forfait` (`id`, `libelle`, `montant`) VALUES
('ETP', 'Forfait Etape', '110.00'),
('KM', 'Frais Kilométrique', '0.62'),
('REP', 'Repas Restaurant', '25.00'),
('NUI', 'Nuitée Hôtel', '80.00');
