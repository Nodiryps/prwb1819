-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 25 fév. 2019 à 18:41
-- Version du serveur :  5.7.11
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prwb_1819_pg02`
--

-- --------------------------------------------------------
DROP DATABASE IF EXISTS `prwb_1819_pg02`;
CREATE DATABASE IF NOT EXISTS `prwb_1819_pg02` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1819_pg02`;
--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `isbn` char(13) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `editor` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `nbCopies` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `isbn`, `title`, `author`, `editor`, `picture`, `nbCopies`) VALUES
(1, '1111111111111', 'Harrys Potter à l Ecole des Sorciers', 'JK Rowlings', 'Pocket', 'Harrys Potter à l Ecole des Sorciers.jpg', 1),
(2, '2222222222222', 'Mobyz Dick', 'Jules Verges', 'Pocket', 'Mobyz Dick.jpg', 1),
(3, '3333333333333', 'Les Fleurs du Male', 'Beau De Lair', 'Pockets', 'Les Fleurs du Male.jpg', 1),
(4, '4444444444444', 'Madame Bovary', 'Gustave Flaubert', 'Pocket', 'Madame Bovary.jpg', 1),
(5, '5555555555555', 'Peter Pan', 'Linus Torvalds', 'Pocket', 'Peter Pan.jpg', 1),
(6, '6666666666666', 'A Brief History of Time', 'Stephen Hawking', 'Pocket', NULL, 1),
(7, '7777777777777', '1984', 'George Orwell', 'Pocket', NULL, 1),
(8, '8888888888888', 'To Kill a Mockingbird', 'Harper Lee', 'Pocket', 'To Kill a Mockingbird.jpg', 1),
(9, '9999999999999', 'Lord of the Flies', 'William Goldings', 'Pocket', NULL, 1),
(10, '1111111111110', 'La Danse de la Colere', 'Lerner', 'Pocket', 'La Danse de la Colere.jpg', 1),
(11, '2222222222220', 'Why Does He Do That', 'Lundy Bancroft', 'Pocket', NULL, 1),
(12, '3333333333330', 'The Stranger', 'Albert Camus', 'Pocket', 'The Stranger.jpg', 1),
(13, '4444444444440', 'Heart of Darkness', 'Joseph Conrad', 'Pocket', NULL, 1),
(14, '5555555555550', 'Men Without Women', 'Ernest Hemingway', 'Pocket', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rental`
--

CREATE TABLE `rental` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  `rentaldate` datetime DEFAULT NULL,
  `returndate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(64) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `role` enum('admin','manager','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `email`, `birthdate`, `role`) VALUES
(1, 'admin', '903001ff9a17773d4a0b4cff3666f1e9', 'chaffi', 'admin@epfc.com', NULL, 'admin'),
(2, 'manager', '903001ff9a17773d4a0b4cff3666f1e9', 'Spyridon', 'manager@epfc.com', NULL, 'manager'),
(3, 'member', '903001ff9a17773d4a0b4cff3666f1e9', 'virginie', 'member@epfc.com', NULL, 'member');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn_UNIQUE` (`isbn`);

--
-- Index pour la table `rental`
--
ALTER TABLE `rental`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rentalitem_book1_idx` (`book`),
  ADD KEY `fk_rentalitem_user1_idx` (`user`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`) USING BTREE,
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `rental`
--
ALTER TABLE `rental`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `rental`
--
ALTER TABLE `rental`
  ADD CONSTRAINT `fk_rentalitem_book` FOREIGN KEY (`book`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rentalitem_user1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
