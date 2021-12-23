-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Nov 30, 2018 alle 17:05
-- Versione del server: 5.5.44-0+deb8u1
-- Versione PHP: 5.6.13-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `CON_consulenza`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `id_user_created` int(11) NOT NULL,
  `id_user_sended` int(11) NOT NULL,
  `attribute` varchar(100) NOT NULL,
  `id_submission_type` int(11) NOT NULL,
  `template` varchar(100) NOT NULL DEFAULT 'default',
  `name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `object` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` int(11) NOT NULL,
  `status_text` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_attachements`
--

CREATE TABLE `submissions_attachements` (
  `id` int(11) NOT NULL,
  `id_submission` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_attributes`
--

CREATE TABLE `submissions_attributes` (
  `id` int(11) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_emails`
--

CREATE TABLE `submissions_emails` (
  `id` int(11) NOT NULL,
  `id_submission` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sended` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_emails_attachements`
--

CREATE TABLE `submissions_emails_attachements` (
  `id` int(11) NOT NULL,
  `id_submission_email` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_emails_customs`
--

CREATE TABLE `submissions_emails_customs` (
  `id` int(11) NOT NULL,
  `id_submission_email` int(11) NOT NULL,
  `custom_key` varchar(100) NOT NULL,
  `custom_value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_templates`
--

CREATE TABLE `submissions_templates` (
  `id` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `object` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `template` varchar(100) NOT NULL DEFAULT 'default',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_type`
--

CREATE TABLE `submissions_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `submissions_type_submissions_attributes`
--

CREATE TABLE `submissions_type_submissions_attributes` (
  `id` int(11) NOT NULL,
  `id_submission_type` int(11) NOT NULL,
  `id_submission_attribute` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user_created` (`id_user_created`,`id_user_sended`),
  ADD KEY `id_submission_type` (`id_submission_type`),
  ADD KEY `sender_email` (`sender_email`),
  ADD KEY `attribute` (`attribute`);

--
-- Indici per le tabelle `submissions_attachements`
--
ALTER TABLE `submissions_attachements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_submission` (`id_submission`);

--
-- Indici per le tabelle `submissions_attributes`
--
ALTER TABLE `submissions_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `submissions_emails`
--
ALTER TABLE `submissions_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_submission` (`id_submission`,`sended`);

--
-- Indici per le tabelle `submissions_emails_attachements`
--
ALTER TABLE `submissions_emails_attachements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_submission_email` (`id_submission_email`);

--
-- Indici per le tabelle `submissions_emails_customs`
--
ALTER TABLE `submissions_emails_customs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_submission_mail` (`id_submission_email`,`custom_key`,`custom_value`);

--
-- Indici per le tabelle `submissions_templates`
--
ALTER TABLE `submissions_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`);

--
-- Indici per le tabelle `submissions_type`
--
ALTER TABLE `submissions_type`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `submissions_type_submissions_attributes`
--
ALTER TABLE `submissions_type_submissions_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_submission_type` (`id_submission_type`,`id_submission_attribute`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_attachements`
--
ALTER TABLE `submissions_attachements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_attributes`
--
ALTER TABLE `submissions_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_emails`
--
ALTER TABLE `submissions_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_emails_attachements`
--
ALTER TABLE `submissions_emails_attachements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_emails_customs`
--
ALTER TABLE `submissions_emails_customs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_templates`
--
ALTER TABLE `submissions_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_type`
--
ALTER TABLE `submissions_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `submissions_type_submissions_attributes`
--
ALTER TABLE `submissions_type_submissions_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
