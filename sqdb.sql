-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 07. Jun 2020 um 21:05
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `sqdb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitetan`
--

CREATE TABLE `arbeitetan` (
  `projektid` int(11) NOT NULL,
  `projektlead` varchar(255) DEFAULT NULL,
  `teamlead` varchar(255) DEFAULT NULL,
  `mitarbeiter` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `arbeitetan`
--

INSERT INTO `arbeitetan` (`projektid`, `projektlead`, `teamlead`, `mitarbeiter`) VALUES
(267, NULL, NULL, '69, 73');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeiteteannorm`
--

CREATE TABLE `arbeiteteannorm` (
  `projektid` int(11) DEFAULT NULL,
  `mitarbeiterid` varchar(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `erstelltam` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `arbeiteteannorm`
--

INSERT INTO `arbeiteteannorm` (`projektid`, `mitarbeiterid`, `position`, `erstelltam`) VALUES
(267, '69', 1, '2020-06-07 16:58:02'),
(267, '73', 1, '2020-06-07 16:58:02');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auftraggeber`
--

CREATE TABLE `auftraggeber` (
  `auftraggeberID` int(11) NOT NULL,
  `firmenname` varchar(125) NOT NULL,
  `kontaktVorname` varchar(32) NOT NULL,
  `kontaktnachname` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `telefon` varchar(32) NOT NULL,
  `strasse` varchar(32) NOT NULL,
  `ort` varchar(32) NOT NULL,
  `plz` varchar(8) NOT NULL,
  `land` varchar(16) NOT NULL,
  `branche` varchar(32) NOT NULL,
  `beschreibung` varchar(256) NOT NULL,
  `auftraggberseit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ersteller` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `berechtigung`
--

CREATE TABLE `berechtigung` (
  `berechtigungid` int(11) NOT NULL,
  `kundenverw` tinyint(1) NOT NULL,
  `mitarbeiterverw` tinyint(1) NOT NULL,
  `mitarbeiterans` tinyint(1) NOT NULL,
  `projektverw` tinyint(1) NOT NULL,
  `projektans` tinyint(1) NOT NULL,
  `subtasktans` tinyint(1) NOT NULL,
  `statistik` tinyint(1) NOT NULL,
  `rollenid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommentare`
--

CREATE TABLE `kommentare` (
  `comment_id` int(11) NOT NULL,
  `parent_comment_id` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `comment_sender_name` varchar(40) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `subtaskid` int(11) DEFAULT NULL,
  `projektid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kommentare`
--

INSERT INTO `kommentare` (`comment_id`, `parent_comment_id`, `comment`, `comment_sender_name`, `date`, `subtaskid`, `projektid`) VALUES
(75, 0, 'Wow, cool', '72', '2020-05-27 20:18:43', 329, NULL),
(76, 75, 'Ja, find ich auch', '72', '2020-05-27 20:18:53', 329, NULL),
(77, 0, 'Ok ok, sieht gut aus..', '72', '2020-06-07 17:05:24', NULL, 267),
(78, 0, 'Ok, funktioniert, dieses Subtaskkommentar! Ü', '72', '2020-06-07 17:06:00', 338, NULL),
(79, 0, 'Wie hast du das mit dem Urwald denn bitte hingekriegt?!', '72', '2020-06-07 17:08:28', 338, NULL),
(80, 78, 'Kann ich hier antworten?', '72', '2020-06-07 18:23:42', 338, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kunde`
--

CREATE TABLE `kunde` (
  `kundenID` int(11) NOT NULL,
  `firmenname` varchar(125) NOT NULL,
  `kontaktvorname` varchar(32) NOT NULL,
  `kontaktnachname` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `telefon` varchar(32) NOT NULL,
  `strasse` varchar(32) NOT NULL,
  `ort` varchar(32) NOT NULL,
  `plz` varchar(8) NOT NULL,
  `land` varchar(16) NOT NULL,
  `branche` varchar(32) NOT NULL,
  `beschreibung` varchar(256) NOT NULL,
  `kundeseit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `kunde`
--

INSERT INTO `kunde` (`kundenID`, `firmenname`, `kontaktvorname`, `kontaktnachname`, `email`, `telefon`, `strasse`, `ort`, `plz`, `land`, `branche`, `beschreibung`, `kundeseit`) VALUES
(39, 'Firmenname', 'VN', 'Firma1', 'Firma1@Firma1.Firma1', 'Firma1', 'Firma1', 'Firma1', 'Firma1', 'Firma1', 'Firma1', 'Firma1', '2020-06-05 19:56:36'),
(40, 'Firmenname1', 'Firma1VN', 'Firma1NN', 'Firma1MAIL@Firma1.dde', 'Firma1TEL', 'Firma1STR', 'Firma1ORT', 'Firma1PL', 'Firma1LAND', 'Firma1Branche', 'Firma1Beschr', '2020-06-05 19:58:37'),
(44, 'Warner Bros.', 'Tim', 'Muster', 'Max@WB.de', '01/4028668', 'WB-Straße 12', 'Wien', '1010', 'AT', 'Film-Prod.', 'Big Player', '2020-06-07 13:41:37');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media`
--

CREATE TABLE `media` (
  `mediaid` int(11) NOT NULL,
  `uploaddatum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mediapfad` varchar(256) NOT NULL,
  `erstellerid` int(11) DEFAULT NULL,
  `subtaskid` int(11) NOT NULL,
  `updatebeschreibung` varchar(1024) NOT NULL DEFAULT 'Version 1, siehe Subtaskinformationen',
  `dateiname` varchar(125) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `media`
--

INSERT INTO `media` (`mediaid`, `uploaddatum`, `mediapfad`, `erstellerid`, `subtaskid`, `updatebeschreibung`, `dateiname`) VALUES
(358, '2020-06-07 17:00:22', '../data/267_Projektname 1/338_Projektname 1/cameravideooutput.mp4', 72, 338, 'Version 1, siehe Subtaskinformationen', 'cameravideooutput.mp4'),
(359, '2020-06-07 17:07:42', '../data/267_Projektname 1/338_Projektname 1/abgaben/video_1280x720_1.mp4', 72, 338, 'Ich habe die Sequenz heller gemacht und einen Urwald mit Fluss und Boot hineinanimiert!', 'video_1280x720_1.mp4');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `message`
--

CREATE TABLE `message` (
  `messageid` int(11) NOT NULL,
  `erstelldatum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `erstellerid` int(11) NOT NULL,
  `nachricht` varchar(2048) NOT NULL,
  `subtaskid` int(11) DEFAULT NULL,
  `projektid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mitarbeiter`
--

CREATE TABLE `mitarbeiter` (
  `mitarbeiterid` int(11) NOT NULL,
  `email` varchar(32) NOT NULL,
  `vorname` varchar(32) NOT NULL,
  `nachname` varchar(32) NOT NULL,
  `strasse` varchar(32) NOT NULL,
  `ort` varchar(32) NOT NULL,
  `plz` varchar(8) NOT NULL,
  `pw` varchar(125) NOT NULL,
  `avatarpfad` varchar(125) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `position` varchar(16) NOT NULL DEFAULT '1',
  `skills` varchar(256) NOT NULL,
  `mitarbeiterseit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kundeVonKundeID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `mitarbeiter`
--

INSERT INTO `mitarbeiter` (`mitarbeiterid`, `email`, `vorname`, `nachname`, `strasse`, `ort`, `plz`, `pw`, `avatarpfad`, `status`, `position`, `skills`, `mitarbeiterseit`, `kundeVonKundeID`) VALUES
(69, 'mitarbeiter1@sq.at', 'mitarbeiter1', 'manachname', 'mastrasse', 'mastadt', 'ma123', '$2y$10$JM/gKgAO7IQnfQbIufgqtuTK0CEiYVZBcuMqDEI.rY.Y7l/x45YDG', 'nach_login/data/avatare/user-solid_1.png', 1, '1', 'CG Supervisor, VFX Supervisor', '2020-05-12 19:54:41', 1),
(70, 'teamlead1@sq.at', 'teamlead1', 'tlnachname', 'tlstrasse', 'tlstadt', 'tl123', '$2y$10$rb6tdFb8x.cH0KLZjQLeSu5g4L2YTHHilhfJ3shDh5J.fw29mcmYO', 'nach_login/data/avatare/users-solid.png', 1, '2', 'VFX Producer, CG Producer', '2020-05-12 19:54:46', 1),
(71, 'projectlead1@sq.at', 'projectlead1', 'plnachname', 'plstrasse', 'plstadt', 'pl123', '$2y$10$CbHdLlJHf8HIWDeogMjvnu5XXHjvBTHPYQwvr0tZTJtIqIj073M.e', 'nach_login/data/avatare/user-tie-solid.png', 1, '3', 'Matte Painter, Pipeline TD', '2020-05-12 19:54:51', 1),
(72, 'admin1@sq.at', 'admin1', 'adnachname', 'adstrasse', 'adstadt', 'ad123', '$2y$10$yB85PKuC18zbF65Y0Xy1leo163zfe95V9MYS9XVuey2nRjBJeklCa', 'nach_login/data/avatare/user-astronaut-solid.png', 1, '4', 'Animator, FX TD', '2020-05-12 19:54:54', 1),
(73, 'mitarbeiter2@sq.at', 'mitarbeiter2', 'mitarbeiter2nachname', 'ma2strasse', 'ma2ort', 'ma2plz', '$2y$10$Fxr/Csm37WFoiooT2qq.vuV0tgkAUywFyd.VvTpaFrPt4Agk0dcfS', 'nach_login/data/avatare/user-solid_1_1.png', 1, '1', 'Concept Artist, Modeler', '2020-05-26 15:21:46', 0),
(74, 'mitarbeiter3@sq.at', 'mitarbeiter3', 'mitarbeiter2nachname', 'ma2strasse', 'ma2ort', 'ma2plz', '$2y$10$tfSapx1nXtSBr8VWs0dvFuAJA6wm5CPz2IMz1ct8sGDQtWxdeGD1m', 'nach_login/data/avatare/user-solid_1_2.png', 1, '1', 'Matchmover, Matte Painter, Pipeline TD', '2020-05-26 15:34:25', 0),
(75, 'mitarbeiter4@sq.at', 'mitarbeiter4', 'mitarbeiter4nachname', 'ma4strasse', 'ma4ort', 'ma4plz', '$2y$10$Mm3Uwpzl9ZryQh0YCXGTG.SjUCW7yBIhip8Kf5J1U2rexY2e2raxi', 'nach_login/data/avatare/user-solid_1_3.png', 1, '1', 'Previsualization, FX TD, Rendering TD, VFX Producer', '2020-05-26 15:35:44', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projekt`
--

CREATE TABLE `projekt` (
  `projektid` int(11) NOT NULL,
  `titel` varchar(1024) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `erstelldatum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deadline` date NOT NULL,
  `beschreibung` varchar(2048) NOT NULL,
  `typ` varchar(1024) NOT NULL,
  `erstellerid` int(11) DEFAULT NULL,
  `auftraggeberid` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `projekt`
--

INSERT INTO `projekt` (`projektid`, `titel`, `status`, `erstelldatum`, `deadline`, `beschreibung`, `typ`, `erstellerid`, `auftraggeberid`) VALUES
(267, 'Projektname 1', 1, '2020-06-07 16:57:12', '2020-06-09', 'Projektbeschreibung 1', 'CG Supervisor, VFX Supervisor', 72, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rolle`
--

CREATE TABLE `rolle` (
  `rollenid` int(11) NOT NULL,
  `bezeichnung` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `screenshot`
--

CREATE TABLE `screenshot` (
  `screenshotid` int(11) NOT NULL,
  `erstelldatum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `erstellerid` int(11) NOT NULL,
  `screenshotpfad` varchar(256) NOT NULL,
  `subtaskid` int(11) NOT NULL,
  `mediaid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `screenshot`
--

INSERT INTO `screenshot` (`screenshotid`, `erstelldatum`, `erstellerid`, `screenshotpfad`, `subtaskid`, `mediaid`) VALUES
(90, '2020-06-07 17:06:49', 72, '../data/267_Projektname 1/338_Projektname 1/screenshots/image5edd1ea9b17f2.png', 338, 358),
(91, '2020-06-07 17:10:39', 72, '../data/267_Projektname 1/338_Projektname 1/screenshots/image5edd1f8f2f4b6.png', 338, 359);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `skills`
--

CREATE TABLE `skills` (
  `skillid` int(11) NOT NULL,
  `skill` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `skills`
--

INSERT INTO `skills` (`skillid`, `skill`) VALUES
(1, 'Previsualization'),
(2, 'Layout TD'),
(3, 'Concept Artist'),
(4, 'Modeler'),
(5, 'Texture Artist'),
(6, 'Rigging TD'),
(7, 'Animator'),
(8, 'FX TD'),
(9, 'Lighting TD'),
(10, 'Rendering TD'),
(11, 'Roto Artist'),
(12, 'Matchmover'),
(13, 'Matte Painter'),
(14, 'Pipeline TD'),
(15, 'VFX Producer'),
(16, 'CG Producer'),
(17, 'CG Supervisor'),
(18, 'VFX Supervisor');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subtask`
--

CREATE TABLE `subtask` (
  `subtaskid` int(11) NOT NULL,
  `titel` varchar(1024) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `erstelldatum` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date NOT NULL,
  `letzteaenderung` timestamp NULL DEFAULT NULL,
  `beschreibung` varchar(2048) NOT NULL,
  `typ` varchar(1024) DEFAULT NULL,
  `projektid` int(11) NOT NULL,
  `erstellerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `subtask`
--

INSERT INTO `subtask` (`subtaskid`, `titel`, `status`, `erstelldatum`, `deadline`, `letzteaenderung`, `beschreibung`, `typ`, `projektid`, `erstellerid`) VALUES
(338, 'Projektname 1', 1, '2020-06-07 17:00:22', '2020-06-09', NULL, 'Projektbeschreibung 1', 'CG Supervisor, VFX Supervisor', 267, 72);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tut_kommentare`
--

CREATE TABLE `tut_kommentare` (
  `comment_id` int(11) NOT NULL,
  `parent_comment_id` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `comment_sender_name` varchar(40) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `tut_kommentare`
--

INSERT INTO `tut_kommentare` (`comment_id`, `parent_comment_id`, `comment`, `comment_sender_name`, `date`) VALUES
(15, 0, 'Hallo!', 'Björn', '2020-06-07 15:48:11'),
(16, 15, 'Hey!', 'Tom', '2020-06-07 15:48:25'),
(17, 0, 'Juhuu!', 'Michael', '2020-06-07 15:57:59');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `versionslog`
--

CREATE TABLE `versionslog` (
  `versionsid` int(11) NOT NULL,
  `subtaskid` int(11) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `erstellerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `auftraggeber`
--
ALTER TABLE `auftraggeber`
  ADD PRIMARY KEY (`auftraggeberID`);

--
-- Indizes für die Tabelle `berechtigung`
--
ALTER TABLE `berechtigung`
  ADD PRIMARY KEY (`berechtigungid`);

--
-- Indizes für die Tabelle `kommentare`
--
ALTER TABLE `kommentare`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indizes für die Tabelle `kunde`
--
ALTER TABLE `kunde`
  ADD PRIMARY KEY (`kundenID`);

--
-- Indizes für die Tabelle `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`mediaid`);

--
-- Indizes für die Tabelle `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`messageid`);

--
-- Indizes für die Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  ADD PRIMARY KEY (`mitarbeiterid`);

--
-- Indizes für die Tabelle `projekt`
--
ALTER TABLE `projekt`
  ADD PRIMARY KEY (`projektid`),
  ADD UNIQUE KEY `titel` (`titel`) USING HASH;

--
-- Indizes für die Tabelle `rolle`
--
ALTER TABLE `rolle`
  ADD PRIMARY KEY (`rollenid`);

--
-- Indizes für die Tabelle `screenshot`
--
ALTER TABLE `screenshot`
  ADD PRIMARY KEY (`screenshotid`);

--
-- Indizes für die Tabelle `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skillid`);

--
-- Indizes für die Tabelle `subtask`
--
ALTER TABLE `subtask`
  ADD PRIMARY KEY (`subtaskid`);

--
-- Indizes für die Tabelle `tut_kommentare`
--
ALTER TABLE `tut_kommentare`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indizes für die Tabelle `versionslog`
--
ALTER TABLE `versionslog`
  ADD PRIMARY KEY (`versionsid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `auftraggeber`
--
ALTER TABLE `auftraggeber`
  MODIFY `auftraggeberID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `berechtigung`
--
ALTER TABLE `berechtigung`
  MODIFY `berechtigungid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `kommentare`
--
ALTER TABLE `kommentare`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT für Tabelle `kunde`
--
ALTER TABLE `kunde`
  MODIFY `kundenID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT für Tabelle `media`
--
ALTER TABLE `media`
  MODIFY `mediaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT für Tabelle `message`
--
ALTER TABLE `message`
  MODIFY `messageid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  MODIFY `mitarbeiterid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT für Tabelle `projekt`
--
ALTER TABLE `projekt`
  MODIFY `projektid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT für Tabelle `rolle`
--
ALTER TABLE `rolle`
  MODIFY `rollenid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `screenshot`
--
ALTER TABLE `screenshot`
  MODIFY `screenshotid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT für Tabelle `skills`
--
ALTER TABLE `skills`
  MODIFY `skillid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT für Tabelle `subtask`
--
ALTER TABLE `subtask`
  MODIFY `subtaskid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- AUTO_INCREMENT für Tabelle `tut_kommentare`
--
ALTER TABLE `tut_kommentare`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `versionslog`
--
ALTER TABLE `versionslog`
  MODIFY `versionsid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
