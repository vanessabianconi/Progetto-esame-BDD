-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 26, 2022 alle 18:36
-- Versione del server: 10.4.24-MariaDB
-- Versione PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `worldblog`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `co_author` int(11) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `font` varchar(255) NOT NULL,
  `background` varchar(200) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `user_id`, `co_author`, `image`, `font`, `background`, `id_cat`, `date_time`) VALUES
(58, 'Dolci', 69, 70, 'ricette.jpg', 'monaco', '#b0fe7c', 3, '2022-08-25 18:58:32'),
(59, 'Curiosità sulla tecnologia', 70, NULL, 'tecnologia.jpg', 'futura', '#7792e4', 26, '2022-08-25 19:06:29');

-- --------------------------------------------------------

--
-- Struttura della tabella `category`
--

CREATE TABLE `category` (
  `id_cat` int(11) NOT NULL,
  `name_cat` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `category`
--

INSERT INTO `category` (`id_cat`, `name_cat`) VALUES
(1, 'Altro'),
(2, 'Danza'),
(3, 'Cucina'),
(4, 'Canto'),
(5, 'Beauty'),
(6, 'Animali'),
(7, 'Musica'),
(8, 'Viaggi'),
(9, 'Social Media'),
(10, 'Sport'),
(11, 'Lifestyle'),
(12, 'Economia'),
(13, 'Chimica'),
(14, 'Matematica'),
(15, 'Geometria'),
(16, 'Teatro'),
(17, 'Finanza'),
(18, 'Amore'),
(19, 'Salute'),
(20, 'Gossip'),
(21, 'Politica'),
(22, 'Moda'),
(23, 'Marketing'),
(24, 'Legge'),
(25, 'Storia'),
(26, 'Tecnologia');

-- --------------------------------------------------------

--
-- Struttura della tabella `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `textc` varchar(500) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `textc`, `date_time`) VALUES
(93, 68, 67, 'Non lo sapevo', '2022-08-25 19:14:04'),
(94, 69, 66, 'Interessante', '2022-08-25 20:51:38'),
(101, 68, 66, 'Buonissima', '2022-08-26 18:00:59');

-- --------------------------------------------------------

--
-- Struttura della tabella `likes`
--

CREATE TABLE `likes` (
  `likeid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `likes`
--

INSERT INTO `likes` (`likeid`, `postid`, `userid`) VALUES
(82, 66, 69),
(83, 70, 69),
(84, 66, 68),
(85, 70, 68);

-- --------------------------------------------------------

--
-- Struttura della tabella `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` mediumtext NOT NULL,
  `image1` varchar(200) DEFAULT NULL,
  `image2` varchar(200) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `image1`, `image2`, `user_id`, `blog_id`, `date_time`) VALUES
(66, 'Torta tenerina', 'La torta tenerina è un dolce tipico della città di Ferrara, che grazie alla sua golosità ha conquistato tutto il paese...nessuno infatti riesce a resistere ad una fetta di questa fantastica torta al cioccolato! Sarà merito della fragrante crosticina esterna o della sua consistenza fondente che si scioglie in bocca ad ogni assaggio? Noi ci siamo innamorati di entrambi, ma una cosa è certa: il suo intenso sapore di cioccolato mette d&#039;accordo tutti! La torta tenerina è una torta con pochi ingredienti, senza lievito che ha la particolarità di rimanere bassa e umida all&#039;interno, proprio come il nome suggerisce: nel dialetto ferrarese veniva chiamata anche &quot; Torta Taclenta&quot;, che in italiano significa appiccicosa.', 'tenerina.jpg', 'tenerina1.jpg', 70, 58, '2022-08-25 19:04:19'),
(67, 'Cortana', 'L’assistente personale elettronico fornito con i telefoni Windows è chiamato Cortana, in onore del personaggio AI di Halo', 'Cortana.jpg', NULL, 70, 59, '2022-08-25 19:08:20'),
(70, 'Crostata alla nutella', 'La crostata alla Nutella è un dolce golosissimo che mette insieme una delle torte più popolari a una crema spalmabile alla nocciola ​amata da tutti e, inoltre, ricorda una merendina della nostra infanzia. Insomma, è un po&#039; una provocazione ma una volta nella vita la dovrebbero provare tutti!', 'nutella.jpg', NULL, 69, 58, '2022-08-26 17:59:05');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `name`, `last_name`, `phone`, `id_card`, `password`, `date_time`) VALUES
(68, 'test123', 'test@libero.it', 'Test', 'Test', '3311081923', 'AS12345ER', '$2y$10$YAVaziMIHgiSo53QGRt13Ox6/4GUw8W.TYCuhvh5K9mjyyNuu1tDa', '2022-08-25 18:48:15'),
(69, 'vanessab', 'vanessa.bianconi@hotmail.it', 'Vanessa', 'Bianconi', '320123435', 'AY56789RT', '$2y$10$Zre6wgLIpAQiQPXYh2QvvOQZkCCLIM6PQUBQq1/Zsrag0UTQM1jIm', '2022-08-25 18:50:21'),
(70, 'mrossi', 'mariorossi@gmail.com', 'Mario', 'Rossi', '057341234', 'SD23456TY', '$2y$10$ZOr7ZlBFnd3EfUVuwQOhpOwocJe2dLvz8W3si6EU.CNEJt9N1kuCu', '2022-08-25 18:54:16'),
(78, 'vanessab12', 'vanebianconi@hotmail.it', 'Vanessa', 'Bianconi', '057262148', 'SC12345ER', '$2y$10$Qka0R2yH3U2DlmKNDjt2lOBh928T3QKqUFZdwdFDNcbw0s.uMaBqW', '2022-08-26 11:38:27');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogs_ibfk_2` (`user_id`),
  ADD KEY `blogs_ibfk_1` (`id_cat`),
  ADD KEY `co_author` (`co_author`);

--
-- Indici per le tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_cat`);

--
-- Indici per le tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `postid` (`postid`);

--
-- Indici per le tabelle `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT per la tabella `category`
--
ALTER TABLE `category`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT per la tabella `likes`
--
ALTER TABLE `likes`
  MODIFY `likeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT per la tabella `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blogs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blogs_ibfk_3` FOREIGN KEY (`co_author`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limiti per la tabella `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`postid`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
