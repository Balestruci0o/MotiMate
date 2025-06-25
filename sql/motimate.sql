-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Po 09.Jún 2025, 10:03
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `motimate`
--
CREATE DATABASE motimate;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 10),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `daily_logs`
--

INSERT INTO `daily_logs` (`id`, `user_id`, `log_date`, `rating`, `comment`) VALUES
(5, 2, '2025-06-07', 7, 'Pracovala som na článku'),
(6, 2, '2025-06-08', 5, 'Neprišlo sa mi do ničoho'),
(7, 3, '2025-06-08', 8, 'Učil som sa React'),
(8, 4, '2025-06-06', 4, 'Zlý deň'),
(9, 4, '2025-06-07', 7, 'Trochu lepšie'),
(10, 4, '2025-06-08', 9, 'Výborná nálada!'),
(13, 2, '2025-06-09', 9, 'Produktívny deň, dokončila som viac ako som čakala.'),
(14, 2, '2025-06-06', 4, 'Slabá energia, veľa rozptýlení.'),
(15, 3, '2025-06-09', 7, 'Napísal som nový kód, ktorý funguje!'),
(16, 3, '2025-06-07', 5, 'Málo času na učenie.'),
(17, 4, '2025-06-09', 8, 'Dnes som začala s kresliacim kurzom.'),
(18, 4, '2025-06-05', 6, 'Niečo som upratala, niečo ešte ostáva.'),
(42, 1, '2025-06-06', 8, 'Dobrý deň, učil som sa PHP'),
(43, 1, '2025-06-07', 6, 'Bol som unavený'),
(44, 1, '2025-06-08', 9, 'Skvelý deň!'),
(45, 1, '2025-06-09', 7, 'Trochu stres, ale ide to.'),
(46, 1, '2025-06-05', 6, 'Dnes som sa nevedel sústrediť.'),
(47, 1, '2025-06-04', 7, 'Zopakoval som si Flexbox.');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `deadline` date NOT NULL,
  `is_done` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `goals`
--

INSERT INTO `goals` (`id`, `user_id`, `title`, `description`, `category`, `deadline`, `is_done`, `created_at`) VALUES
(7, 2, 'Napísať článok', 'O motivácii', 'work', '2025-06-14', 0, '2025-06-09 08:56:07'),
(8, 2, 'Beh', '5 km trikrát týždenne', 'health', '2025-06-30', 1, '2025-06-09 08:56:07'),
(9, 3, 'Naučiť sa React', 'Vytvoriť todo appku', 'school', '2025-06-19', 1, '2025-06-09 08:56:07'),
(10, 3, 'Meditácia', '10 minút denne', 'personal', '2025-08-08', 1, '2025-06-09 08:56:07'),
(11, 4, 'Upratať izbu', 'Vyhodiť nepotrebné veci', 'personal', '2025-06-12', 0, '2025-06-09 08:56:07'),
(12, 4, 'Uvariť nové jedlo', 'Každý týždeň jedno', 'health', '2025-07-07', 1, '2025-06-09 08:56:07'),
(15, 2, 'Usporiadať pracovné súbory', 'Zorganizovať dokumenty na Google Drive', 'work', '2025-06-13', 1, '2025-06-09 09:06:54'),
(16, 2, 'Zúčastniť sa workshopu', 'Téma: sebarozvoj', 'personal', '2025-06-21', 0, '2025-06-09 09:06:54'),
(17, 3, 'Zlepšiť angličtinu', 'Každý deň 15 minút Duolingo', 'personal', '2025-06-30', 0, '2025-06-09 09:06:54'),
(18, 3, 'Programovať každý deň', 'Aspoň 1 hodinu denne', 'school', '2025-06-23', 1, '2025-06-09 09:06:54'),
(19, 4, 'Viac sa hýbať', 'Chôdza 8000 krokov denne', 'health', '2025-06-19', 0, '2025-06-09 09:06:54'),
(20, 4, 'Naučiť sa kresliť', 'Dokončiť kurz na YouTube', 'personal', '2025-06-29', 1, '2025-06-09 09:06:54'),
(23, 2, 'Naučiť sa Excel', 'Zvládnuť pokročilé funkcie', 'work', '2025-06-19', 1, '2025-06-09 09:13:28'),
(24, 2, 'Prečítať motivačnú knihu', 'Dokončiť do konca mesiaca', 'personal', '2025-06-29', 0, '2025-06-09 09:13:28'),
(25, 3, 'Dokončiť školský projekt', 'Odovzdať na čas', 'school', '2025-06-15', 0, '2025-06-09 09:13:28'),
(26, 3, 'Zlepšiť fyzičku', 'Cvičiť 3x do týždňa', 'health', '2025-07-09', 1, '2025-06-09 09:13:28'),
(27, 4, 'Písať denník', 'Aspoň 5 minút denne', 'personal', '2025-06-24', 0, '2025-06-09 09:13:28'),
(28, 4, 'Piť viac vody', '2 litre denne', 'health', '2025-06-19', 1, '2025-06-09 09:13:28'),
(29, 1, 'Dokončiť školský projekt', 'Odovzdať do piatku', 'school', '2025-06-11', 0, '2025-06-09 09:52:00'),
(30, 1, 'Zdravé stravovanie', 'Bez cukru počas týždňa', 'health', '2025-06-16', 0, '2025-06-09 09:52:00'),
(31, 1, 'Učiť sa PHP', 'Dokončiť projekt MotiMate', 'school', '2025-06-16', 0, '2025-06-09 09:52:16'),
(32, 1, 'Cvičiť jógu', '30 minút denne', 'health', '2025-06-23', 1, '2025-06-09 09:52:16'),
(33, 1, 'Prečítať knihu', 'Atomic Habits', 'personal', '2025-07-09', 0, '2025-06-09 09:52:16'),
(34, 1, 'Zopakovať si CSS', 'Prejsť Flexbox a Grid', 'school', '2025-06-14', 0, '2025-06-09 09:52:35'),
(35, 1, 'Chodiť skôr spať', 'Najneskôr o 22:30', 'health', '2025-06-23', 0, '2025-06-09 09:52:35');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quote` text NOT NULL,
  `author` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `quotes`
--

INSERT INTO `quotes` (`id`, `user_id`, `quote`, `author`) VALUES
(1, NULL, 'The only way to do great work is to love what you do.', 'Steve Jobs'),
(2, NULL, 'Believe you can and you are halfway there.', 'Theodore Roosevelt'),
(3, NULL, 'It does not matter how slowly you go as long as you do not stop.', 'Confucius'),
(6, 2, 'Jediným obmedzením sú tvoje myšlienky.', NULL),
(7, NULL, 'Úspech je súčet malých snáh opakovaných každý deň.', 'Robert Collier'),
(8, 3, 'Ak to dokážeš snívať, dokážeš to aj dosiahnuť.', 'Walt Disney'),
(9, 4, 'Najlepší čas na výsadbu stromu bol pred 20 rokmi. Druhý najlepší čas je teraz.', 'Čínske príslovie'),
(10, NULL, 'Život je ako bicykel. Aby si udržal rovnováhu, musíš sa hýbať.', 'Albert Einstein'),
(12, 2, 'Každý deň je nová príležitosť.', 'Neznámy autor'),
(13, 3, 'Tvrdo pracuj potichu, nech tvoj úspech kričí.', NULL),
(14, 4, 'Nebuď perfektný, buď konzistentný.', NULL),
(16, 2, 'Najdôležitejší krok je ten prvý.', 'Neznámy autor'),
(17, 3, 'Pokrok je lepší ako dokonalosť.', 'Mark Twain'),
(18, 4, 'Každý deň je šanca začať znova.', NULL),
(19, 1, 'Cesta tisíc míľ začína jedným krokom.', 'Lao-c'),
(20, 1, 'Budúcnosť záleží na tom, čo urobíš dnes.', 'Mahatma Gandhi'),
(21, 1, 'Nevzdávaj sa. Začni znova a choď ďalej.', NULL),
(22, 1, 'Nečakaj na správny moment, vytvor ho.', NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Pavlik', 'pavlikmartin@gmail.com', '$2y$10$uWDMLW73dH5czouGvJj7Semsz/PsagPQr39IDrj9Cla2197SeeEWu', '2025-06-09 09:50:08'),
(2, 'Kuruc', 'kurucmatus@gmail.com', '$2y$10$ZUWM0wEZeXVRK8ua.FuL4.BvR9W4B4B9fVnLA59lja7vTzNS.Bz4W', '2025-06-09 08:44:20'),
(3, 'Hyben', 'hybenadam@gmail.com', '$2y$10$SMA4uDlZ.OTdYfkAoTix5.8srnFB8z0FC0c9zmLOAMWUObGpdWWmK', '2025-06-09 08:45:19'),
(4, 'Klokočka', 'klokockadominik@gmail.com', '$2y$10$O0IY4pE0t2nCiPGLmgR2tuvIr4OMIY47SaG9jS1vLDkBcwZQtP4JS', '2025-06-09 08:48:56');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`log_date`);

--
-- Indexy pre tabuľku `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pre tabuľku `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pre tabuľku `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD CONSTRAINT `daily_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
