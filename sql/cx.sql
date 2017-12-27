-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 27 Décembre 2017 à 16:20
-- Version du serveur :  5.7.20-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cx`
--

-- --------------------------------------------------------

--
-- Structure de la table `avoirlignes`
--

CREATE TABLE `avoirlignes` (
  `ligneId` int(11) NOT NULL,
  `ligneAvoirId` int(11) NOT NULL,
  `ligneProduitId` int(11) DEFAULT NULL,
  `ligneUniteId` int(11) NOT NULL,
  `ligneDesignation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ligneQte` decimal(8,2) NOT NULL,
  `lignePrixUnitaire` decimal(8,2) DEFAULT NULL,
  `ligneRemise` decimal(8,2) NOT NULL,
  `lignePrixNet` decimal(8,2) NOT NULL,
  `ligneTotalHT` decimal(8,2) DEFAULT NULL,
  `ligneTauxTVA` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `avoirs`
--

CREATE TABLE `avoirs` (
  `avoirId` int(11) NOT NULL,
  `avoirPdvId` int(11) NOT NULL,
  `avoirFactureId` int(11) NOT NULL,
  `avoirDate` int(11) NOT NULL,
  `avoirClientId` int(11) NOT NULL,
  `avoirTotalHT` decimal(8,2) DEFAULT NULL,
  `avoirTotalTVA` decimal(8,2) DEFAULT NULL,
  `avoirTotalTTC` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `avoirtva`
--

CREATE TABLE `avoirtva` (
  `tvaAvoirId` int(11) NOT NULL,
  `tvaTaux` int(11) NOT NULL,
  `tvaMontant` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `bdc`
--

CREATE TABLE `bdc` (
  `bdcId` int(11) NOT NULL,
  `bdcPdvId` int(11) NOT NULL,
  `bdcCollaborateurId` int(11) NOT NULL DEFAULT '1',
  `bdcDevisId` int(11) NOT NULL,
  `bdcDateCreation` int(11) NOT NULL,
  `bdcDate` int(11) NOT NULL,
  `bdcClientId` int(11) NOT NULL,
  `bdcNbArticles` decimal(8,2) DEFAULT NULL,
  `bdcTotalHT` decimal(8,2) DEFAULT NULL,
  `bdcAcompte` decimal(8,2) NOT NULL,
  `bdcPoids` decimal(8,2) NOT NULL,
  `bdcEtat` tinyint(4) NOT NULL COMMENT '0 = encours 1 = partiel 2 = livré',
  `bdcCommentaire` longtext COLLATE utf8_bin NOT NULL,
  `bdcTotalTVA` decimal(8,2) DEFAULT NULL,
  `bdcTotalTTC` decimal(8,2) DEFAULT NULL,
  `bdcDelete` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `bdcarticles`
--

CREATE TABLE `bdcarticles` (
  `articleId` int(11) NOT NULL,
  `articleBdcId` int(11) NOT NULL,
  `articleProduitId` int(11) DEFAULT NULL,
  `articleDesignation` varchar(255) COLLATE utf8_bin NOT NULL,
  `articleQte` decimal(8,2) NOT NULL,
  `articlePrixUnitaire` decimal(8,2) NOT NULL,
  `articleRemise` decimal(8,2) NOT NULL,
  `articlePrixNet` decimal(8,2) NOT NULL,
  `articleApproId` int(11) DEFAULT NULL,
  `articleUniteId` tinyint(4) NOT NULL,
  `articleQteLivree` decimal(8,2) NOT NULL,
  `articleTotalHT` decimal(8,2) DEFAULT NULL,
  `articleTauxTVA` decimal(8,2) DEFAULT '20.00',
  `articleDelete` tinyint(1) DEFAULT NULL,
  `articleAction` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `bdctva`
--

CREATE TABLE `bdctva` (
  `tvaBdcId` int(11) NOT NULL,
  `tvaTaux` int(11) NOT NULL,
  `tvaMontant` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `bl`
--

CREATE TABLE `bl` (
  `blId` int(11) NOT NULL,
  `blPdvId` int(11) NOT NULL,
  `blBdcId` int(11) NOT NULL,
  `blDate` int(11) NOT NULL,
  `blFactureId` int(11) DEFAULT NULL,
  `blDelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `bltva`
--

CREATE TABLE `bltva` (
  `tvaBlId` int(11) NOT NULL,
  `tvaTaux` int(11) NOT NULL,
  `tvaMontant` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `caisseId` int(11) NOT NULL,
  `caissePdvId` int(11) NOT NULL,
  `caisseDate` int(11) NOT NULL,
  `caisseMontant` decimal(8,2) NOT NULL,
  `caisseType` tinyint(4) NOT NULL COMMENT '1=Sortie 2=fond de caisse',
  `caisseDetail` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8_bin NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_bin NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `clientId` int(11) NOT NULL,
  `clientPdvId` int(11) DEFAULT NULL,
  `clientType` tinyint(4) NOT NULL COMMENT '1 particulier 2 professionel',
  `clientCodeComptable` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientNom` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `clientPrenom` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `clientAdresse1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `clientAdresse2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `clientCp` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  `clientVille` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `clientPays` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientTel` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `clientPortable` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `clientFax` varchar(25) COLLATE utf8_bin NOT NULL,
  `clientEmail` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `clientIntracom` varchar(255) COLLATE utf8_bin NOT NULL,
  `clientModeReglementId` int(11) DEFAULT NULL,
  `clientConditionReglementId` int(11) DEFAULT NULL,
  `clientRaisonSociale` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `clientExonerationTVA` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='\n';

-- --------------------------------------------------------

--
-- Structure de la table `clotures`
--

CREATE TABLE `clotures` (
  `clotureId` int(11) NOT NULL,
  `clotureDate` int(11) NOT NULL,
  `clotureType` tinyint(4) NOT NULL COMMENT '1=jour 2=mois 3=annee',
  `clotureMontant` decimal(6,2) NOT NULL,
  `clotureToken` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `collaborateurs`
--

CREATE TABLE `collaborateurs` (
  `collaborateurId` int(11) NOT NULL,
  `collaborateurPdvId` int(11) NOT NULL,
  `collaborateurNom` varchar(255) NOT NULL,
  `collaborateurActive` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `conditionsreglement`
--

CREATE TABLE `conditionsreglement` (
  `conditionReglementId` int(11) NOT NULL,
  `conditionReglementNom` varchar(120) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `devisId` int(11) NOT NULL,
  `devisPdvId` int(11) NOT NULL,
  `devisCollaborateurId` int(11) NOT NULL DEFAULT '1',
  `devisDateCreation` int(11) NOT NULL,
  `devisDate` int(11) NOT NULL,
  `devisClientId` int(11) NOT NULL,
  `devisNbArticles` decimal(8,2) DEFAULT NULL,
  `devisTotalHT` decimal(8,2) DEFAULT NULL,
  `devisPoids` decimal(8,2) NOT NULL,
  `devisTotalTVA` decimal(8,2) DEFAULT NULL,
  `devisTotalTTC` decimal(8,2) DEFAULT NULL,
  `devisEtat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0= encours 1 = Perdu',
  `devisDelete` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `devisarticles`
--

CREATE TABLE `devisarticles` (
  `articleId` int(11) NOT NULL,
  `articleDevisId` int(11) NOT NULL,
  `articleProduitId` int(11) DEFAULT NULL,
  `articleDesignation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `articleQte` decimal(8,2) NOT NULL,
  `articlePrixUnitaire` decimal(8,2) DEFAULT NULL,
  `articleRemise` decimal(8,2) NOT NULL,
  `articleUniteId` tinyint(4) NOT NULL,
  `articleTotalHT` decimal(8,2) DEFAULT NULL,
  `articleTauxTVA` decimal(8,2) DEFAULT '20.00',
  `articlePrixNet` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `devistva`
--

CREATE TABLE `devistva` (
  `tvaDevisId` int(11) NOT NULL,
  `tvaTaux` int(4) NOT NULL,
  `tvaMontant` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `facturelignes`
--

CREATE TABLE `facturelignes` (
  `ligneId` int(11) NOT NULL,
  `ligneFactureId` int(11) NOT NULL,
  `ligneBlId` int(11) DEFAULT NULL,
  `ligneLivraisonId` int(11) DEFAULT NULL,
  `ligneProduitId` int(11) DEFAULT NULL,
  `ligneUniteId` int(11) NOT NULL,
  `ligneDesignation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ligneQte` decimal(8,2) NOT NULL,
  `lignePrixUnitaire` decimal(8,2) DEFAULT NULL,
  `ligneRemise` decimal(8,2) NOT NULL,
  `lignePrixNet` decimal(8,2) NOT NULL,
  `ligneTotalHT` decimal(8,2) DEFAULT NULL,
  `ligneTauxTVA` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `factureId` int(11) NOT NULL,
  `facturePdvId` int(11) NOT NULL,
  `factureDate` int(11) NOT NULL,
  `factureClientId` int(11) NOT NULL,
  `factureTotalHT` decimal(8,2) DEFAULT NULL,
  `factureTotalTVA` decimal(8,2) DEFAULT NULL,
  `factureConditionsReglementId` int(11) NOT NULL,
  `factureDelete` tinyint(1) NOT NULL,
  `factureTotalTTC` decimal(8,2) DEFAULT NULL,
  `factureSolde` decimal(8,2) DEFAULT '0.00',
  `factureEcheance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `facturetva`
--

CREATE TABLE `facturetva` (
  `tvaFactureId` int(11) NOT NULL,
  `tvaTaux` int(11) NOT NULL,
  `tvaMontant` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `familles`
--

CREATE TABLE `familles` (
  `familleId` int(11) NOT NULL,
  `famillePdvId` int(11) NOT NULL,
  `familleNom` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `livraisonId` int(11) NOT NULL,
  `livraisonBlId` int(11) NOT NULL,
  `livraisonArticleId` int(11) NOT NULL COMMENT 'Id de l''article du bdc',
  `livraisonStockId` int(11) DEFAULT NULL,
  `livraisonQte` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `modesreglement`
--

CREATE TABLE `modesreglement` (
  `modeReglementId` int(11) NOT NULL,
  `modeReglementNom` varchar(120) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `pdv`
--

CREATE TABLE `pdv` (
  `pdvId` int(11) NOT NULL,
  `pdvRaisonSociale` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pdvNomCommercial` varchar(255) COLLATE utf8_bin NOT NULL,
  `pdvApe` varchar(5) COLLATE utf8_bin NOT NULL,
  `pdvSiren` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `pdvTvaIntracom` varchar(30) COLLATE utf8_bin NOT NULL,
  `pdvAdresse1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `pdvAdresse2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `pdvCp` int(5) DEFAULT NULL,
  `pdvVille` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `pdvTelephone` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `pdvEmail` varchar(120) COLLATE utf8_bin DEFAULT NULL,
  `pdvFax` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `pdvWww` varchar(255) COLLATE utf8_bin NOT NULL,
  `pdvTelephoneCommercial` varchar(40) COLLATE utf8_bin NOT NULL,
  `pdvEmailCommercial` varchar(255) COLLATE utf8_bin NOT NULL,
  `pdvTelephoneTechnique` varchar(40) COLLATE utf8_bin NOT NULL,
  `pdvEmailTechnique` varchar(255) COLLATE utf8_bin NOT NULL,
  `logo` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `produitId` int(11) NOT NULL,
  `produitPdvId` int(11) NOT NULL,
  `produitRefUsine` varchar(255) CHARACTER SET utf8 NOT NULL,
  `produitDesignation` varchar(255) CHARACTER SET utf8 NOT NULL,
  `produitUsineId` int(11) NOT NULL,
  `produitFamilleId` int(11) NOT NULL,
  `produitUniteId` tinyint(4) NOT NULL,
  `produitMultiple` decimal(8,2) NOT NULL,
  `produitPrixAchatUnitaire` decimal(8,2) NOT NULL,
  `produitPrixAchatPalette` decimal(8,2) NOT NULL,
  `produitSeuilPalette` decimal(8,2) NOT NULL,
  `produitPrixVente` decimal(8,2) NOT NULL,
  `produitPoids` decimal(8,2) NOT NULL COMMENT 'poids en Kg d''une unité',
  `produitGestionStock` tinyint(4) NOT NULL COMMENT '0 pas de gestion de stock 1 gestion',
  `produitGestionBain` tinyint(4) NOT NULL COMMENT '1 indique une gestion des bains et des calibres dans le stock',
  `produitEAN` int(11) DEFAULT '0',
  `produitTVA` float(4,2) DEFAULT '20.00',
  `produitArchive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `reglements`
--

CREATE TABLE `reglements` (
  `reglementId` int(11) NOT NULL,
  `reglementType` tinyint(1) NOT NULL COMMENT '1 Acompte 2 Solde',
  `reglementSourceId` int(11) DEFAULT NULL,
  `reglementUtile` tinyint(1) NOT NULL COMMENT '0 SI il a été modifié et donc remplacé par un autre',
  `reglementToken` varchar(255) COLLATE utf8_bin NOT NULL,
  `reglementBdcId` int(11) DEFAULT NULL,
  `reglementFactureId` int(11) DEFAULT NULL,
  `reglementClientId` int(11) NOT NULL,
  `reglementModeId` int(4) DEFAULT NULL,
  `reglementRemiseId` int(11) DEFAULT NULL COMMENT 'Id de la reqmise de cheque',
  `reglementMontant` decimal(8,2) DEFAULT NULL,
  `reglementDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `remisecheques`
--

CREATE TABLE `remisecheques` (
  `remiseId` int(11) NOT NULL,
  `remisePdvId` int(11) NOT NULL,
  `remiseDate` int(11) NOT NULL,
  `remiseTotal` decimal(8,2) NOT NULL,
  `remiseBanque` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE `stocks` (
  `stockId` int(11) NOT NULL,
  `stockProduitId` int(11) NOT NULL,
  `stockQte` decimal(8,2) NOT NULL,
  `stockBain` varchar(120) CHARACTER SET utf8 NOT NULL,
  `stockCalibre` varchar(120) CHARACTER SET utf8 NOT NULL,
  `stockPrixAchat` decimal(8,2) NOT NULL,
  `stockEmplacement` varchar(120) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `pdvId` int(11) NOT NULL COMMENT 'Id du point de vente rattaché',
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `usines`
--

CREATE TABLE `usines` (
  `usineId` int(11) NOT NULL,
  `usinePdvId` int(11) NOT NULL,
  `usineNom` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `usineEmail` varchar(255) CHARACTER SET utf8 NOT NULL,
  `usineCodeClient` varchar(120) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `avoirlignes`
--
ALTER TABLE `avoirlignes`
  ADD PRIMARY KEY (`ligneId`),
  ADD KEY `ligneFactureId` (`ligneAvoirId`),
  ADD KEY `ligneProduitId` (`ligneProduitId`);

--
-- Index pour la table `avoirs`
--
ALTER TABLE `avoirs`
  ADD PRIMARY KEY (`avoirId`),
  ADD KEY `facturePdvId` (`avoirPdvId`,`avoirDate`,`avoirClientId`),
  ADD KEY `factureClientId` (`avoirClientId`),
  ADD KEY `avoirFactureId` (`avoirFactureId`);

--
-- Index pour la table `avoirtva`
--
ALTER TABLE `avoirtva`
  ADD PRIMARY KEY (`tvaAvoirId`,`tvaTaux`);

--
-- Index pour la table `bdc`
--
ALTER TABLE `bdc`
  ADD PRIMARY KEY (`bdcId`),
  ADD KEY `bdcPdvId` (`bdcPdvId`,`bdcCollaborateurId`,`bdcDevisId`,`bdcClientId`),
  ADD KEY `bdcPdvId_2` (`bdcPdvId`,`bdcCollaborateurId`,`bdcDevisId`,`bdcClientId`),
  ADD KEY `bdcUserId` (`bdcCollaborateurId`),
  ADD KEY `bdcDevisId` (`bdcDevisId`),
  ADD KEY `bdcClientId` (`bdcClientId`),
  ADD KEY `bdcEtat` (`bdcEtat`),
  ADD KEY `bdcDelete` (`bdcDelete`),
  ADD KEY `bdcDate` (`bdcDate`);

--
-- Index pour la table `bdcarticles`
--
ALTER TABLE `bdcarticles`
  ADD PRIMARY KEY (`articleId`),
  ADD KEY `articleBdcId` (`articleBdcId`),
  ADD KEY `articleProduitId` (`articleProduitId`);

--
-- Index pour la table `bdctva`
--
ALTER TABLE `bdctva`
  ADD PRIMARY KEY (`tvaBdcId`,`tvaTaux`);

--
-- Index pour la table `bl`
--
ALTER TABLE `bl`
  ADD PRIMARY KEY (`blId`),
  ADD KEY `blBdcId` (`blBdcId`),
  ADD KEY `blFactureId` (`blFactureId`),
  ADD KEY `blPdvId` (`blPdvId`),
  ADD KEY `blDelete` (`blDelete`);

--
-- Index pour la table `bltva`
--
ALTER TABLE `bltva`
  ADD PRIMARY KEY (`tvaBlId`,`tvaTaux`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`caisseId`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`clientId`),
  ADD KEY `fk_cx_clients_cx_pointdeventes_idx` (`clientPdvId`);

--
-- Index pour la table `clotures`
--
ALTER TABLE `clotures`
  ADD PRIMARY KEY (`clotureId`),
  ADD KEY `clotureDate` (`clotureDate`),
  ADD KEY `clotureType` (`clotureType`);

--
-- Index pour la table `collaborateurs`
--
ALTER TABLE `collaborateurs`
  ADD PRIMARY KEY (`collaborateurId`);

--
-- Index pour la table `conditionsreglement`
--
ALTER TABLE `conditionsreglement`
  ADD PRIMARY KEY (`conditionReglementId`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`devisId`),
  ADD KEY `devisPdvId` (`devisPdvId`,`devisCollaborateurId`,`devisDate`,`devisClientId`),
  ADD KEY `devisClientId` (`devisClientId`),
  ADD KEY `devisUserId` (`devisCollaborateurId`),
  ADD KEY `devisEtat` (`devisEtat`);

--
-- Index pour la table `devisarticles`
--
ALTER TABLE `devisarticles`
  ADD PRIMARY KEY (`articleId`),
  ADD KEY `articleDevisId` (`articleDevisId`,`articleProduitId`),
  ADD KEY `articleProduitId` (`articleProduitId`);

--
-- Index pour la table `devistva`
--
ALTER TABLE `devistva`
  ADD PRIMARY KEY (`tvaDevisId`,`tvaTaux`);

--
-- Index pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  ADD PRIMARY KEY (`ligneId`),
  ADD KEY `ligneFactureId` (`ligneFactureId`),
  ADD KEY `ligneProduitId` (`ligneProduitId`),
  ADD KEY `ligneBlId` (`ligneBlId`),
  ADD KEY `ligneLivraisonId` (`ligneLivraisonId`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`factureId`),
  ADD KEY `facturePdvId` (`facturePdvId`,`factureDate`,`factureClientId`),
  ADD KEY `factureClientId` (`factureClientId`);

--
-- Index pour la table `facturetva`
--
ALTER TABLE `facturetva`
  ADD PRIMARY KEY (`tvaFactureId`,`tvaTaux`);

--
-- Index pour la table `familles`
--
ALTER TABLE `familles`
  ADD PRIMARY KEY (`familleId`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`livraisonId`),
  ADD KEY `livraisonBlId` (`livraisonBlId`),
  ADD KEY `livraisonArticleId` (`livraisonArticleId`),
  ADD KEY `livraisonStockId` (`livraisonStockId`);

--
-- Index pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `modesreglement`
--
ALTER TABLE `modesreglement`
  ADD PRIMARY KEY (`modeReglementId`);

--
-- Index pour la table `pdv`
--
ALTER TABLE `pdv`
  ADD PRIMARY KEY (`pdvId`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`produitId`);

--
-- Index pour la table `reglements`
--
ALTER TABLE `reglements`
  ADD PRIMARY KEY (`reglementId`),
  ADD KEY `reglementBdcId` (`reglementBdcId`),
  ADD KEY `reglementFactureId` (`reglementFactureId`),
  ADD KEY `reglementClientId` (`reglementClientId`),
  ADD KEY `reglementSourceId` (`reglementSourceId`);

--
-- Index pour la table `remisecheques`
--
ALTER TABLE `remisecheques`
  ADD PRIMARY KEY (`remiseId`);

--
-- Index pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stockId`),
  ADD KEY `stockProduitId` (`stockProduitId`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `pdvId` (`pdvId`);

--
-- Index pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Index pour la table `usines`
--
ALTER TABLE `usines`
  ADD PRIMARY KEY (`usineId`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `avoirlignes`
--
ALTER TABLE `avoirlignes`
  MODIFY `ligneId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `avoirs`
--
ALTER TABLE `avoirs`
  MODIFY `avoirId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bdc`
--
ALTER TABLE `bdc`
  MODIFY `bdcId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bdcarticles`
--
ALTER TABLE `bdcarticles`
  MODIFY `articleId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `bl`
--
ALTER TABLE `bl`
  MODIFY `blId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `caisseId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `clientId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `clotures`
--
ALTER TABLE `clotures`
  MODIFY `clotureId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `collaborateurs`
--
ALTER TABLE `collaborateurs`
  MODIFY `collaborateurId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `devisId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `devisarticles`
--
ALTER TABLE `devisarticles`
  MODIFY `articleId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  MODIFY `ligneId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `factureId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `familles`
--
ALTER TABLE `familles`
  MODIFY `familleId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `livraisonId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `modesreglement`
--
ALTER TABLE `modesreglement`
  MODIFY `modeReglementId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `pdv`
--
ALTER TABLE `pdv`
  MODIFY `pdvId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `produitId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `reglements`
--
ALTER TABLE `reglements`
  MODIFY `reglementId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `remisecheques`
--
ALTER TABLE `remisecheques`
  MODIFY `remiseId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stockId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `usines`
--
ALTER TABLE `usines`
  MODIFY `usineId` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `bdc`
--
ALTER TABLE `bdc`
  ADD CONSTRAINT `bdc_ibfk_1` FOREIGN KEY (`bdcPdvId`) REFERENCES `pdv` (`pdvId`),
  ADD CONSTRAINT `bdc_ibfk_2` FOREIGN KEY (`bdcCollaborateurId`) REFERENCES `collaborateurs` (`collaborateurId`),
  ADD CONSTRAINT `bdc_ibfk_3` FOREIGN KEY (`bdcDevisId`) REFERENCES `devis` (`devisId`),
  ADD CONSTRAINT `bdc_ibfk_4` FOREIGN KEY (`bdcClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `bdcarticles`
--
ALTER TABLE `bdcarticles`
  ADD CONSTRAINT `bdcarticles_ibfk_1` FOREIGN KEY (`articleBdcId`) REFERENCES `bdc` (`bdcId`) ON DELETE CASCADE,
  ADD CONSTRAINT `bdcarticles_ibfk_2` FOREIGN KEY (`articleProduitId`) REFERENCES `produits` (`produitId`);

--
-- Contraintes pour la table `bdctva`
--
ALTER TABLE `bdctva`
  ADD CONSTRAINT `bdctva_ibfk_1` FOREIGN KEY (`tvaBdcId`) REFERENCES `bdc` (`bdcId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bl`
--
ALTER TABLE `bl`
  ADD CONSTRAINT `bl_ibfk_1` FOREIGN KEY (`blBdcId`) REFERENCES `bdc` (`bdcId`),
  ADD CONSTRAINT `bl_ibfk_2` FOREIGN KEY (`blFactureId`) REFERENCES `factures` (`factureId`),
  ADD CONSTRAINT `bl_ibfk_3` FOREIGN KEY (`blPdvId`) REFERENCES `pdv` (`pdvId`);

--
-- Contraintes pour la table `bltva`
--
ALTER TABLE `bltva`
  ADD CONSTRAINT `bltva_ibfk_1` FOREIGN KEY (`tvaBlId`) REFERENCES `bl` (`blId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_ibfk_1` FOREIGN KEY (`devisClientId`) REFERENCES `clients` (`clientId`),
  ADD CONSTRAINT `devis_ibfk_2` FOREIGN KEY (`devisCollaborateurId`) REFERENCES `collaborateurs` (`collaborateurId`),
  ADD CONSTRAINT `devis_ibfk_3` FOREIGN KEY (`devisClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `devisarticles`
--
ALTER TABLE `devisarticles`
  ADD CONSTRAINT `devisarticles_ibfk_2` FOREIGN KEY (`articleDevisId`) REFERENCES `devis` (`devisId`) ON DELETE CASCADE,
  ADD CONSTRAINT `devisarticles_ibfk_3` FOREIGN KEY (`articleProduitId`) REFERENCES `produits` (`produitId`);

--
-- Contraintes pour la table `devistva`
--
ALTER TABLE `devistva`
  ADD CONSTRAINT `devistva_ibfk_1` FOREIGN KEY (`tvaDevisId`) REFERENCES `devis` (`devisId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `facturelignes`
--
ALTER TABLE `facturelignes`
  ADD CONSTRAINT `facturelignes_ibfk_1` FOREIGN KEY (`ligneFactureId`) REFERENCES `factures` (`factureId`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturelignes_ibfk_2` FOREIGN KEY (`ligneProduitId`) REFERENCES `produits` (`produitId`),
  ADD CONSTRAINT `facturelignes_ibfk_3` FOREIGN KEY (`ligneBlId`) REFERENCES `bl` (`blId`) ON DELETE SET NULL,
  ADD CONSTRAINT `facturelignes_ibfk_4` FOREIGN KEY (`ligneLivraisonId`) REFERENCES `livraisons` (`livraisonId`) ON DELETE SET NULL;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`facturePdvId`) REFERENCES `pdv` (`pdvId`),
  ADD CONSTRAINT `factures_ibfk_2` FOREIGN KEY (`factureClientId`) REFERENCES `clients` (`clientId`);

--
-- Contraintes pour la table `facturetva`
--
ALTER TABLE `facturetva`
  ADD CONSTRAINT `facturetva_ibfk_1` FOREIGN KEY (`tvaFactureId`) REFERENCES `factures` (`factureId`);

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `livraisons_ibfk_1` FOREIGN KEY (`livraisonBlId`) REFERENCES `bl` (`blId`),
  ADD CONSTRAINT `livraisons_ibfk_2` FOREIGN KEY (`livraisonArticleId`) REFERENCES `bdcarticles` (`articleId`),
  ADD CONSTRAINT `livraisons_ibfk_3` FOREIGN KEY (`livraisonStockId`) REFERENCES `stocks` (`stockId`);

--
-- Contraintes pour la table `reglements`
--
ALTER TABLE `reglements`
  ADD CONSTRAINT `reglements_ibfk_1` FOREIGN KEY (`reglementBdcId`) REFERENCES `bdc` (`bdcId`),
  ADD CONSTRAINT `reglements_ibfk_2` FOREIGN KEY (`reglementFactureId`) REFERENCES `factures` (`factureId`),
  ADD CONSTRAINT `reglements_ibfk_3` FOREIGN KEY (`reglementClientId`) REFERENCES `clients` (`clientId`),
  ADD CONSTRAINT `reglements_ibfk_4` FOREIGN KEY (`reglementSourceId`) REFERENCES `reglements` (`reglementId`) ON DELETE SET NULL;

--
-- Contraintes pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`stockProduitId`) REFERENCES `produits` (`produitId`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`pdvId`) REFERENCES `pdv` (`pdvId`);

--
-- Contraintes pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
