-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql
-- Généré le :  mar. 14 mai 2019 à 16:45
-- Version du serveur :  5.5.61
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `matcha`
--

-- --------------------------------------------------------

--
-- Structure de la table `Address`
--

CREATE TABLE `Address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `formatted_address` varchar(512) DEFAULT NULL,
  `street_number` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `postal_code` int(11) DEFAULT NULL,
  `country` varchar(255) DEFAULT 'France',
  `source` enum('js','remote_addr','user') NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Blacklist_entries`
--

CREATE TABLE `Blacklist_entries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blacklisted_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Chat`
--

CREATE TABLE `Chat` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `Chat`
--

INSERT INTO `Chat` (`id`, `user1_id`, `user2_id`, `creation_date`) VALUES
(1, 2, 30, '2019-04-08 10:57:29'),
(2, 2, 29, '2019-04-08 10:58:32'),
(3, 2, 1, '2019-04-15 10:25:15'),
(4, 2, 27, '2019-04-15 10:26:13'),
(5, 489, 5948, '2019-04-26 12:24:22'),
(7, 489, 13289, '2019-05-02 15:11:24'),
(8, 489, 6465, '2019-05-03 15:42:31'),
(9, 489, 13586, '2019-05-08 21:46:22'),
(10, 13600, 13519, '2019-05-14 13:31:10'),
(11, 13704, 13519, '2019-05-14 14:02:32');

-- --------------------------------------------------------

--
-- Structure de la table `Chat_messages`
--

CREATE TABLE `Chat_messages` (
  `id` int(11) NOT NULL,
  `chatroom_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'unread',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Likes`
--

CREATE TABLE `Likes` (
  `like_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Notifications`
--

CREATE TABLE `Notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `object` varchar(255) COLLATE utf8_bin NOT NULL,
  `content` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'unread',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `Pictures`
--

CREATE TABLE `Pictures` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT 'assets/uploads/default_user.jpeg',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Reports`
--

CREATE TABLE `Reports` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `reported_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Tags`
--

CREATE TABLE `Tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `Tags`
--

INSERT INTO `Tags` (`id`, `name`, `creation_date`) VALUES
(22, '#PHP', '2019-04-25 16:35:55'),
(23, '#Glasses', '2019-04-25 16:35:55'),
(24, '#Sport', '2019-04-25 16:35:55'),
(25, '#Fitness', '2019-04-25 16:35:55'),
(26, '#Vegan', '2019-04-25 16:35:55'),
(27, '#Food', '2019-04-25 16:35:55'),
(28, '#Burger', '2019-04-25 16:35:55'),
(29, '#Books', '2019-04-25 16:35:55'),
(30, '#Cinema', '2019-04-25 16:35:55'),
(31, '#Bicycle', '2019-04-25 16:35:55'),
(32, '#Natation', '2019-04-25 16:35:55');

-- --------------------------------------------------------

--
-- Structure de la table `Tag_entries`
--

CREATE TABLE `Tag_entries` (
  `id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `age` tinyint(4) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic_id` int(11) NOT NULL,
  `gender` enum('Male','Female','Non-binary') DEFAULT NULL,
  `target_gender` enum('Male','Female','Non-binary','') DEFAULT NULL,
  `bio` text NOT NULL,
  `role` enum('user','admin','','') NOT NULL,
  `notification_mails` enum('0','1') NOT NULL DEFAULT '1',
  `active_key` varchar(32) NOT NULL,
  `mail_confirm` int(11) NOT NULL DEFAULT '0',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `lat` decimal(9,6) NOT NULL,
  `lng` decimal(9,6) NOT NULL,
  `ip_adress` varchar(255) NOT NULL,
  `theme` tinyint(1) NOT NULL DEFAULT '1',
  `last_connection` timestamp NOT NULL DEFAULT '2019-04-10 13:08:04',
  `popularity_score` int(11) DEFAULT '0',
  `bot` tinyint(1) NOT NULL DEFAULT '0',
  `oauth_uid` varchar(25) NOT NULL,
  `google` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `Visits`
--

CREATE TABLE `Visits` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index pour la table `Blacklist_entries`
--
ALTER TABLE `Blacklist_entries`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Chat`
--
ALTER TABLE `Chat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Chat_messages`
--
ALTER TABLE `Chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Index pour la table `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Pictures`
--
ALTER TABLE `Pictures`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Reports`
--
ALTER TABLE `Reports`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `Tag_entries`
--
ALTER TABLE `Tag_entries`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Index pour la table `Visits`
--
ALTER TABLE `Visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Address`
--
ALTER TABLE `Address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Blacklist_entries`
--
ALTER TABLE `Blacklist_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Chat`
--
ALTER TABLE `Chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `Chat_messages`
--
ALTER TABLE `Chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Likes`
--
ALTER TABLE `Likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Pictures`
--
ALTER TABLE `Pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Reports`
--
ALTER TABLE `Reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Tags`
--
ALTER TABLE `Tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `Tag_entries`
--
ALTER TABLE `Tag_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Visits`
--
ALTER TABLE `Visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
