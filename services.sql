-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 20 mars 2025 à 18:28
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `services`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('manager','editor','viewer','service_reparation','services_domicile') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Mugisha', 'Claude', 'claudemug4@gmail.com', '$2y$10$RV0te.k/hRGDdpeOZu5sienYruDPnUSLPv/eyacKulb.1z5UoZKM6', 'manager'),
(4, 'Mugisha', 'Bruce', 'Mugishabruce@gmail.com', '$2y$10$zqnKrIMd1IkraGs0eKgOOevo/xs3qRV89vaiON6K4lgTTDlFEc5Tu', 'viewer');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `telephone` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `sexe` enum('Homme','Femme') NOT NULL,
  `newsletter` tinyint(1) DEFAULT 0,
  `partner_offers` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `prenom`, `telephone`, `email`, `mot_de_passe`, `pays`, `ville`, `sexe`, `newsletter`, `partner_offers`, `created_at`) VALUES
(1, 'Claude', 'Mugisha Claude', NULL, 'claudemug4@gmail.com', '$2y$10$0INXt9v7RAYda1cdWcx1v.uXUBQ.0bNJlnRZldt/UQyN6Il/ylAVq', 'Burundi', 'Bujumbura', 'Homme', 1, 0, '2025-03-02 11:45:36'),
(3, 'Claude', 'Claude', NULL, 'claudemug7@gmail.com', '$2y$10$PPiR.eRjlUIMQzVGhJqHoOLPfA82VqZaWL4bqvMtuow4XBYW47sjW', 'Burundi', 'Bujumbura', 'Homme', 1, 0, '2025-03-02 12:20:42');

-- --------------------------------------------------------

--
-- Structure de la table `commanddomicile`
--

CREATE TABLE `commanddomicile` (
  `id` int(11) NOT NULL,
  `nom_service` varchar(255) NOT NULL,
  `domaine` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `other_service` varchar(255) DEFAULT NULL,
  `request_type` varchar(50) NOT NULL,
  `request_date` date DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_comments` text DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `statut` enum('En attente','En cours','Terminée') NOT NULL DEFAULT 'En attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commanddomicile`
--

INSERT INTO `commanddomicile` (`id`, `nom_service`, `domaine`, `service`, `other_service`, `request_type`, `request_date`, `user_name`, `user_email`, `user_phone`, `user_address`, `user_comments`, `prix`, `created_at`, `user_id`, `statut`) VALUES
(2, 'service_a_domicile', 'soins_hygiene_infirmiers', 'soins_infirmiers', '', 'normal', '2025-03-20', 'Claude', 'claudemug4@gmail.com', '76906021', 'Burundi', 'Merci de me repondre au plus vite', 145000.00, '2025-03-02 16:13:12', 1, 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`id`, `service`, `prix`, `user_name`, `user_email`, `statut`, `created_at`) VALUES
(1, 'suivi_medical', 223300.00, 'cc', 'claudemug4@gmail.com', 'Terminée', '2025-03-08 09:53:29');

-- --------------------------------------------------------

--
-- Structure de la table `historique_connexion`
--

CREATE TABLE `historique_connexion` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `date_connexion` datetime NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ;

--
-- Déchargement des données de la table `historique_connexion`
--

INSERT INTO `historique_connexion` (`id`, `client_id`, `admin_id`, `date_connexion`, `nom`, `email`) VALUES
(35, 1, NULL, '2025-03-08 15:14:15', 'Claude', 'claudemug4@gmail.com'),
(36, 1, NULL, '2025-03-08 16:57:11', 'Claude', 'claudemug4@gmail.com'),
(37, 1, NULL, '2025-03-08 17:01:31', 'Claude', 'claudemug4@gmail.com'),
(38, 1, NULL, '2025-03-08 17:05:48', 'Claude', 'claudemug4@gmail.com'),
(39, 1, NULL, '2025-03-08 17:10:34', 'Claude', 'claudemug4@gmail.com'),
(40, 1, NULL, '2025-03-08 17:13:59', 'Claude', 'claudemug4@gmail.com'),
(41, 1, NULL, '2025-03-08 17:16:43', 'Claude', 'claudemug4@gmail.com'),
(42, 1, NULL, '2025-03-08 17:20:09', 'Mugisha', 'claudemug4@gmail.com'),
(43, NULL, 1, '2025-03-08 17:22:43', 'Mugisha', 'claudemug4@gmail.com'),
(44, 1, NULL, '2025-03-10 20:15:30', 'Claude', 'claudemug4@gmail.com'),
(45, 1, NULL, '2025-03-10 22:24:40', 'Claude', 'claudemug4@gmail.com'),
(46, 1, NULL, '2025-03-10 22:45:33', 'Claude', 'claudemug4@gmail.com'),
(47, 1, NULL, '2025-03-12 16:52:04', 'Claude', 'claudemug4@gmail.com'),
(48, NULL, 1, '2025-03-18 16:31:21', 'Mugisha', 'claudemug4@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_prestataire` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT 0,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_client`, `id_prestataire`, `id_admin`, `message`, `file_name`, `file_path`, `is_private`, `timestamp`) VALUES
(1, 1, NULL, 1, 'claude', NULL, NULL, 1, '2025-03-12 22:31:37'),
(26, 1, NULL, 4, 'hello', NULL, NULL, 0, '2025-03-12 20:12:26'),
(61, 1, NULL, NULL, 'bonsoir', '', 'uploads/', 1, '2025-03-12 22:29:47'),
(63, 1, NULL, NULL, 'bonsoir ir', NULL, NULL, 1, '2025-03-12 22:38:12'),
(64, 1, NULL, 1, 'cc mug', NULL, NULL, 1, '2025-03-12 22:40:01'),
(65, 1, NULL, NULL, 'cocous', NULL, NULL, 0, '2025-03-12 22:40:22'),
(66, 1, NULL, 4, 'salut bruce', NULL, NULL, 1, '2025-03-18 17:40:50'),
(67, 1, 3, NULL, 'hello', 'Horaire Examens.jpg', 'uploads/Horaire Examens.jpg', 1, '2025-03-18 17:49:48'),
(68, 1, NULL, 1, 'salut claude', NULL, NULL, 1, '2025-03-18 18:23:38'),
(69, 1, NULL, 1, 'merci pour vos message', NULL, NULL, 1, '2025-03-18 19:06:16');

-- --------------------------------------------------------

--
-- Structure de la table `nosservices`
--

CREATE TABLE `nosservices` (
  `id` int(11) NOT NULL,
  `service_domicile` varchar(255) DEFAULT NULL,
  `service_reparation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `nosservices`
--

INSERT INTO `nosservices` (`id`, `service_domicile`, `service_reparation`) VALUES
(1, 'Systèmes Électroménagers', 'Électricité'),
(2, 'Sécurité à Domicile', 'Réparation d\'Appareils Électroniques'),
(3, 'Déménagement', 'Plomberie'),
(4, 'Garde d\'Enfants', 'Mécanique'),
(5, 'Livraison', 'Soudure'),
(6, 'Organisation d\'Événements', 'Réparation de Caméras'),
(7, 'Coaching Personnel', 'Réparation de Montres et Accessoires'),
(8, 'Conseil Financier', 'Réparation de Vélo'),
(9, 'Peinture et Nettoyage', 'Réparation de Climatisation et Micro-ondes'),
(10, 'Soins d’hygiène et infirmiers', 'Réparation de Systèmes Audio et Mixeurs'),
(11, 'Entretien Ménager et Jardinage', 'Réparation d\'Instruments de Musique');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type_destinataire` enum('client','prestataire') NOT NULL,
  `ids_clients` text DEFAULT NULL,
  `ids_prestataires` text DEFAULT NULL,
  `message` text NOT NULL,
  `type_message` enum('email','whatsapp') NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `nombre_destinataires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type_destinataire`, `ids_clients`, `ids_prestataires`, `message`, `type_message`, `date_envoi`, `nombre_destinataires`) VALUES
(1, 'client', '1', '', 'salut', '', '2025-03-18 22:05:09', 1),
(2, 'prestataire', '', '3', 'he', '', '2025-03-18 22:06:33', 1);

-- --------------------------------------------------------

--
-- Structure de la table `prestataire`
--

CREATE TABLE `prestataire` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `services` varchar(255) NOT NULL,
  `experience` text NOT NULL,
  `disponibilite` varchar(50) NOT NULL,
  `lettre_motivation` text NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prestataire`
--

INSERT INTO `prestataire` (`id`, `nom`, `email`, `telephone`, `adresse`, `services`, `experience`, `disponibilite`, `lettre_motivation`, `cv`, `portfolio`, `date_inscription`) VALUES
(3, 'Mugisha Claude', 'claudemug4@gmail.com', '76906021', 'Burundi', 'Reparation', 'Je suis pationner dans le boulon et tres motivé ', 'temps plein', 'Merci de votre reponse', '8.jpg', '6.jpg', '2025-03-04 19:04:24');

-- --------------------------------------------------------

--
-- Structure de la table `revenus`
--

CREATE TABLE `revenus` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `numero` varchar(15) NOT NULL,
  `type_paiement` enum('mobile','carte','paypal') NOT NULL,
  `date_paiement` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `revenus`
--

INSERT INTO `revenus` (`id`, `user_id`, `montant`, `numero`, `type_paiement`, `date_paiement`) VALUES
(1, 1, 10.00, '+243123456789', 'mobile', '2025-03-07 19:44:26'),
(2, 1, 100.00, '12-12-132-25', 'carte', '2025-03-07 20:08:32');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_domicile` enum('Systèmes Électroménagers','Sécurité à Domicile','Déménagement','Garde d''Enfants','Livraison','Organisation d''Événements','Coaching Personnel','Conseil Financier','Peinture et Nettoyage','Soins d’hygiène et infirmiers','Entretien Ménager et Jardinage') NOT NULL,
  `service_reparation` enum('Électricité','Réparation d''Appareils Électroniques','Plomberie','Mécanique','Soudure','Réparation de Caméras','Réparation de Montres et Accessoires','Réparation de Vélo','Réparation de Climatisation et Micro-ondes','Réparation de Systèmes Audio et Mixeurs','Réparation d''Instruments de Musique') NOT NULL,
  `prix` int(200) NOT NULL,
  `idClient` int(11) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `idPrestataire` int(11) NOT NULL,
  `idCommandDomicile` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` enum('Actif','Inactif') DEFAULT 'Actif',
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `couleur_primaire` varchar(7) NOT NULL,
  `couleur_secondaire` varchar(7) NOT NULL,
  `police` varchar(100) NOT NULL,
  `taille_police` int(11) NOT NULL,
  `fond_image` varchar(255) DEFAULT NULL,
  `bordure_style` varchar(50) DEFAULT NULL,
  `bordure_couleur` varchar(7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `themes`
--

INSERT INTO `themes` (`id`, `nom`, `couleur_primaire`, `couleur_secondaire`, `police`, `taille_police`, `fond_image`, `bordure_style`, `bordure_couleur`, `created_at`, `updated_at`) VALUES
(1, 'Theme 1', '#fefbfb', '#2f7b8e', 'Georgia', 16, '', 'dashed', '#000000', '2025-03-18 18:18:00', '2025-03-18 18:52:25'),
(2, '2.', '#000000', '#000000', 'Verdana', 45, '', 'dotted', '#962c2c', '2025-03-18 18:36:53', '2025-03-18 18:36:53');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `commanddomicile`
--
ALTER TABLE `commanddomicile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_connexion`
--
ALTER TABLE `historique_connexion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_prestataire` (`id_prestataire`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Index pour la table `nosservices`
--
ALTER TABLE `nosservices`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `prestataire`
--
ALTER TABLE `prestataire`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `revenus`
--
ALTER TABLE `revenus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idClient` (`idClient`),
  ADD KEY `idAdmin` (`idAdmin`),
  ADD KEY `idPrestataire` (`idPrestataire`),
  ADD KEY `idCommandDomicile` (`idCommandDomicile`);

--
-- Index pour la table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commanddomicile`
--
ALTER TABLE `commanddomicile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `historique_connexion`
--
ALTER TABLE `historique_connexion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `nosservices`
--
ALTER TABLE `nosservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `prestataire`
--
ALTER TABLE `prestataire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `revenus`
--
ALTER TABLE `revenus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commanddomicile`
--
ALTER TABLE `commanddomicile`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `clients` (`id`);

--
-- Contraintes pour la table `historique_connexion`
--
ALTER TABLE `historique_connexion`
  ADD CONSTRAINT `historique_connexion_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historique_connexion_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id`);

--
-- Contraintes pour la table `revenus`
--
ALTER TABLE `revenus`
  ADD CONSTRAINT `revenus_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`idAdmin`) REFERENCES `admins` (`id`),
  ADD CONSTRAINT `services_ibfk_3` FOREIGN KEY (`idPrestataire`) REFERENCES `prestataire` (`id`),
  ADD CONSTRAINT `services_ibfk_4` FOREIGN KEY (`idCommandDomicile`) REFERENCES `commanddomicile` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
