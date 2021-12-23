-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Gen 26, 2017 alle 09:30
-- Versione del server: 5.7.17-0ubuntu0.16.04.1
-- Versione PHP: 5.6.30-1+deb.sury.org~xenial+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intranet`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `action_log`
--

CREATE TABLE `action_log` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `id_record` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `entity` text NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `aziende`
--

CREATE TABLE `aziende` (
  `id` int(11) NOT NULL,
  `denominazione` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `cod_paese` varchar(10) NOT NULL,
  `piva` varchar(11) NOT NULL,
  `cf` varchar(50) NOT NULL,
  `cod_eori` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `email_info` varchar(50) NOT NULL,
  `email_contabilita` varchar(50) NOT NULL,
  `email_solleciti` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `cliente` int(1) NOT NULL,
  `fornitore` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_contatto` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `allDay` tinyint(1) NOT NULL,
  `repeated` tinyint(4) NOT NULL,
  `backgroundColor` varchar(10) NOT NULL,
  `borderColor` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `id_parentEvent` int(11) NOT NULL,
  `vobject` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `calendar_repeated_events`
--

CREATE TABLE `calendar_repeated_events` (
  `id` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `configurations`
--

CREATE TABLE `configurations` (
  `id` int(11) NOT NULL,
  `key_conf` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `tooltip` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `level` int(11) NOT NULL COMMENT 'Indica il livello di utenza abilitato alla modifica',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `client` int(1) NOT NULL,
  `provider` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `contatti`
--

CREATE TABLE `contatti` (
  `id` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_ruolo` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `cellulare` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `skype` varchar(50) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `num_civico` varchar(50) NOT NULL,
  `cap` int(5) NOT NULL,
  `comune` varchar(255) NOT NULL,
  `provincia` varchar(255) NOT NULL,
  `nazione` varchar(255) NOT NULL,
  `cf` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `contatti_ruoli`
--

CREATE TABLE `contatti_ruoli` (
  `id` int(11) NOT NULL,
  `ruolo` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `id_document` varchar(255) NOT NULL,
  `parent` varchar(255) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text1` mediumtext NOT NULL,
  `last_saved` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `azienda_id` int(11) NOT NULL,
  `num` varchar(16) NOT NULL,
  `emission_date` date NOT NULL,
  `due_date` date NOT NULL,
  `paid_date` date NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` mediumtext NOT NULL,
  `hidden_notes` mediumtext NOT NULL,
  `payment_conditions` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_contatto` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `scadenzario`
--

CREATE TABLE `scadenzario` (
  `id` int(11) NOT NULL,
  `descrizione` varchar(64) NOT NULL,
  `data` date NOT NULL,
  `data_eseguito` date NOT NULL,
  `note` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `sedi`
--

CREATE TABLE `sedi` (
  `id` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `num_civico` varchar(50) NOT NULL,
  `cap` int(5) NOT NULL,
  `comune` varchar(255) NOT NULL,
  `provincia` varchar(255) NOT NULL,
  `nazione` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `cellulare` varchar(50) NOT NULL,
  `fax` varchar(50) NOT NULL,
  `skype` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `sedi_tipi`
--

CREATE TABLE `sedi_tipi` (
  `id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `auth_email` int(1) NOT NULL,
  `recovery_code` varchar(255) NOT NULL,
  `auth_code` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `data_nascita` date NOT NULL,
  `cf` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `users_to_groups`
--

CREATE TABLE `users_to_groups` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `action_log`
--
ALTER TABLE `action_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `table` (`table_name`);

--
-- Indici per le tabelle `aziende`
--
ALTER TABLE `aziende`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente` (`cliente`),
  ADD KEY `fornitore` (`fornitore`);

--
-- Indici per le tabelle `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_azienda` (`id_azienda`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `id_contatto` (`id_contatto`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `repeated` (`repeated`);

--
-- Indici per le tabelle `calendar_repeated_events`
--
ALTER TABLE `calendar_repeated_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_event` (`id_event`);

--
-- Indici per le tabelle `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client` (`client`),
  ADD KEY `provider` (`provider`);

--
-- Indici per le tabelle `contatti`
--
ALTER TABLE `contatti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_azienda` (`id_azienda`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `id_ruolo` (`id_ruolo`);

--
-- Indici per le tabelle `contatti_ruoli`
--
ALTER TABLE `contatti_ruoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_saved` (`last_saved`),
  ADD KEY `id_document` (`id_document`),
  ADD KEY `parent` (`parent`);

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `azienda_id` (`azienda_id`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_azienda` (`id_azienda`);

--
-- Indici per le tabelle `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Indici per le tabelle `scadenzario`
--
ALTER TABLE `scadenzario`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sedi`
--
ALTER TABLE `sedi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_azienda` (`id_azienda`),
  ADD KEY `id_tipo` (`id_tipo`);

--
-- Indici per le tabelle `sedi_tipi`
--
ALTER TABLE `sedi_tipi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `email` (`email`),
  ADD KEY `auth_email` (`auth_email`),
  ADD KEY `recovery_code` (`recovery_code`),
  ADD KEY `username_2` (`username`,`password`);

--
-- Indici per le tabelle `users_to_groups`
--
ALTER TABLE `users_to_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_group` (`id_group`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `action_log`
--
ALTER TABLE `action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `aziende`
--
ALTER TABLE `aziende`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `calendar_repeated_events`
--
ALTER TABLE `calendar_repeated_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `contatti`
--
ALTER TABLE `contatti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `contatti_ruoli`
--
ALTER TABLE `contatti_ruoli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `scadenzario`
--
ALTER TABLE `scadenzario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `sedi`
--
ALTER TABLE `sedi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `sedi_tipi`
--
ALTER TABLE `sedi_tipi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `users_to_groups`
--
ALTER TABLE `users_to_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
INSERT INTO `aziende` ( `denominazione`, `nome`, `cognome`, `cod_paese`, `piva`, `cf`, `cod_eori`, `telefono`, `email_info`, `email_contabilita`, `email_solleciti`, `fax`, `cliente`, `fornitore`, `created`, `modified`) VALUES
( 'Lochiva', 'roberto', 'ruffinengo', 'IT', '09902690016', 'RFFRRT65H17B111X', '', '3207679531', 'info@lochiva.com', '', 'info@lochiva.com', 'aa', 0, 0, '2015-10-05 15:07:36', '2016-01-02 18:27:51');

INSERT INTO `configurations` ( `key_conf`, `label`, `tooltip`, `value`, `level`, `created`, `modified`) VALUES
( 'APP_NAME', 'App Name', 'Nome dell\'applicazione', 'MyApp', 0, '2015-08-05 08:13:11', '2015-08-05 08:13:11');

INSERT INTO `contatti` ( `id_azienda`, `id_sede`, `id_ruolo`, `nome`, `cognome`, `telefono`, `cellulare`, `email`, `fax`, `skype`, `indirizzo`, `num_civico`, `cap`, `comune`, `provincia`, `nazione`, `cf`, `created`, `modified`) VALUES
( 1, 1, 1, 'roberto', 'RUFFINENGO', '', '', '', '', '', '', '', 0, '', '', '', '', '2015-10-16 16:01:36', '2015-10-27 08:13:51'),
( 1, 1, 1, 'Marco', 'Blua', '', '', '', '', '', '', '', 0, '', '', '', '', '2015-10-27 08:43:45', '2015-10-27 08:43:45'),
( 1, 1, 1, 'Roberto', 'Faletto', '', '', '', '', '', '', '', 0, '', '', '', '', '2015-10-27 08:44:00', '2015-10-27 08:44:00'),
( 1, 1, 1, 'Silvia', 'Maistrello', '', '', '', '', '', '', '', 0, '', '', '', '', '2015-10-27 08:44:34', '2015-10-27 08:44:34'),
( 1, 1, 2, 'Rafael', 'Esposito', '', '', '', '', '', '', '', 0, '', '', '', '', '2017-01-25 16:40:50', '2017-01-25 16:40:50');

INSERT INTO `contatti_ruoli` (`id`, `ruolo`, `order`, `created`) VALUES
(1, 'Direttore', 10, '0000-00-00 00:00:00'),
(2, 'Impiegato', 20, '0000-00-00 00:00:00');

INSERT INTO `sedi` (`id`, `id_azienda`, `id_tipo`, `indirizzo`, `num_civico`, `cap`, `comune`, `provincia`, `nazione`, `telefono`, `email`, `cellulare`, `fax`, `skype`, `created`, `modified`) VALUES
(1, 1, 1, 'Via Massari', '189', 10148, 'Torino', 'TO', 'Italia', '', 'ruffinengo@lochiva.com', '', '', 'info_lochiva', '2015-10-16 16:03:20', '2015-10-16 16:03:20');

INSERT INTO `sedi_tipi` (`id`, `tipo`, `order`, `created`) VALUES
(1, 'Sede Legale', 10, 0),
(2, 'Sede Operativa', 20, 0);

INSERT INTO `users` (`id`, `username`, `password`, `role`, `level`, `email`, `auth_email`, `recovery_code`, `auth_code`, `nome`, `cognome`, `data_nascita`, `cf`, `created`, `modified`) VALUES
(1, 'rufus', '$2y$10$iauO/9UeabcM3vLO/f8eeOsm91vUlblL16KN1dJiMQyQYNT8zSZr6', 'admin', 999, 'ruffinengo@lochiva.com', 1, '', '', 'Roberto', 'Ruffinengo', '2015-09-20', '', '2015-09-20 15:13:15', '2017-01-26 09:29:13');

/*
  #7203: Configuratore per companee -- aggiunte per far funzionare il configuratore
 */
ALTER TABLE `configurations` ADD `plugin` VARCHAR(100) NOT NULL DEFAULT 'generico' AFTER `id`, ADD INDEX (`plugin`);
ALTER TABLE `configurations` ADD `value_type` VARCHAR(100) NOT NULL DEFAULT 'text' AFTER `value`, ADD INDEX (`value_type`);

INSERT INTO `configurations` ( `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES
( 'calendar', 'MAX_REPEATED', 'Data massima per la generazione di eventi ripetuti', 'Data massima per la generazione di eventi ripetuti', '2024-12-31', 'date', 500, '2017-01-30 11:36:22', '2017-01-30 13:59:17'),
( 'calendar', 'MAX_COUNT', 'Limite massimo di generazione di eventi per occorrenze.', 'Limite massimo di generazione di eventi per occorrenze.', '1825', 'number', 500, '2017-01-30 12:34:11', '2017-01-30 12:36:17'),
('registration', 'REGISTRATION_TYPE', '0:Registrazione veloce|1:Registrazione con anagrafica', '0:Registrazione veloce|1:Registrazione con anagrafica', '1', 'checkbox', 900, '0000-00-00 00:00:00', '2017-01-30 13:48:34'),
( 'registration', 'SENDER_EMAIL', 'sender dell\'email del plugin', 'sender dell\'email del plugin', 'info@lochiva.com', 'text', 500, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
( 'registration', 'SENDER_ALIAS', 'alias del sender email', 'alias del sender email', 'Lochiva', 'text', 500, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
( 'registration', 'AUTH_EMAIL', '1 => la mail deve essere autenticata | 0 => l\'utente è subito autenticato', '1 => la mail deve essere autenticata | 0 => l\'utente è subito autenticato', '0', 'checkbox', 900, '0000-00-00 00:00:00', '2017-01-30 12:53:48'),
( 'registration', 'REGISTRATION_FRONTEND', 'Abilita la registrazione dal front end', 'Abilita la registrazione dal front end', '0', 'checkbox', 900, '0000-00-00 00:00:00', '2017-01-30 13:48:12'),
( 'generico', 'LOG_DB', 'Abilita la scrittura dei log nel database', 'Abilita la scrittura dei log nel database', '1', 'checkbox', 900, '2017-01-30 13:43:56', '2017-01-30 13:59:55');

/*
  #7208:  Sistema di tagging per gli eventi di calendario
 */
 --
 -- la tabella `tags`
 --
 CREATE TABLE `tags` (
   `id` int(11) NOT NULL,
   `name` varchar(255) NOT NULL,
   `level` int(11) NOT NULL,
   `created` datetime DEFAULT NULL,
   `modified` datetime DEFAULT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 ALTER TABLE `tags`
   ADD PRIMARY KEY (`id`),
   ADD UNIQUE KEY `name` (`name`);

 ALTER TABLE `tags`
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
   --
   -- la tabella `calendar_events_to_tags`
   --
 CREATE TABLE `calendar_events_to_tags` (
     `id` int(11) NOT NULL,
     `id_event` int(11) NOT NULL,
     `id_tag` int(11) NOT NULL,
     `created` datetime DEFAULT NULL,
     `modified` datetime DEFAULT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

   ALTER TABLE `calendar_events_to_tags`
     ADD PRIMARY KEY (`id`),
     ADD KEY `id_event` (`id_event`),
     ADD KEY `id_tag` (`id_tag`);

   ALTER TABLE `calendar_events_to_tags`
     MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `scadenzario` ADD `id_user` INT NOT NULL AFTER `id`, ADD `id_event` INT NOT NULL AFTER `id_user`, ADD INDEX (`id_user`), ADD INDEX (`id_event`);

/*
 *   AGGIUNTA PEC TABELLA Aziende
 */
ALTER TABLE `aziende` ADD `pec` VARCHAR(255) NOT NULL AFTER `telefono`;
/*
 *   MODIFICA TABELLA `documents`
 */
ALTER TABLE `documents` CHANGE `id_client` `id_azienda` INT(11) NOT NULL;
ALTER TABLE `documents` CHANGE `id_project` `id_order` INT(11) NOT NULL;

/*
*   AGGIUNTA CONFIGURAZIONE SCADENZARIO
 */
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES (NULL, 'scadenzario', 'TAG', 'id del tag che si riferisce allo scadenzario', 'id del tag che si riferisce allo scadenzario', '10', 'number', '900', NOW(), '');

--
-- Struttura della tabella `access_log`
-- #7268: loggare gli accessi
--

CREATE TABLE `access_log` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `action` varchar(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `access_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `action` (`action`);

ALTER TABLE `access_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`) VALUES ( 'registration', 'LOG_ACCESS', 'Abilita il log degli accessi', 'Abilita il log degli accessi', '1', 'checkbox', '900', NOW());

--
-- #6955: Aggiunti i campi neccessari per il funzionamento della sincronizzazione con google calendar
--
ALTER TABLE `calendar_events` ADD `id_google` VARCHAR(255) NOT NULL AFTER `id`;
ALTER TABLE `users` ADD `googleAccessToken` TEXT NOT NULL AFTER `auth_code`;

--
-- #7301: la tabella `documents_to_tags`
--
CREATE TABLE `documents_to_tags` (
  `id` int(11) NOT NULL,
  `id_document` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `documents_to_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_document` (`id_document`),
  ADD KEY `id_tag` (`id_tag`);

ALTER TABLE `documents_to_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- #7288: aggiunta tabella aziende
--
ALTER TABLE `aziende` ADD `sito_web` VARCHAR(255) NOT NULL AFTER `email_solleciti`;
ALTER TABLE `aziende`
  ADD KEY `sito_web` (`sito_web`),
  ADD KEY `denominazione` (`denominazione`),
  ADD KEY `nome` (`nome`),
  ADD KEY `cognome` (`cognome`),
  ADD KEY `piva` (`piva`),
  ADD KEY `email_info` (`email_info`);

  --
  -- #7388 Gestione posizione documenti
  --
  ALTER TABLE `documents` ADD `position` INT(11) NOT NULL AFTER `parent`;
  ALTER TABLE `documents` ADD KEY `position` (`position`);

  --
  -- #7385: Modifica tabella contatti_ruoli
  --
  ALTER TABLE `contatti_ruoli` ADD `color` VARCHAR(50) NOT NULL AFTER `ruolo`;
  ALTER TABLE `contatti_ruoli` CHANGE `order` `ordering` INT(11) NOT NULL;

--
-- Struttura della tabella `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `id_creator` int(11) NOT NULL,
  `id_dest` int(11) NOT NULL,
  `message` text NOT NULL,
  `readed` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_creator` (`id_creator`),
  ADD KEY `id_dest` (`id_dest`),
  ADD KEY `readed` (`readed`);
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- #7399: stati degli ordini
--

ALTER TABLE `orders` ADD `id_status` INT NOT NULL AFTER `note`, ADD INDEX (`id_status`);
ALTER TABLE `orders` ADD `closed` datetime NOT NULL AFTER `id_status`, ADD INDEX (`closed`);
--
-- Struttura della tabella `orders_status`
--
CREATE TABLE `orders_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `orders_status` (`id`, `name`, `ordering`, `created`, `modified`) VALUES
(1, 'Aperto', 10, '2017-03-10 09:51:46', NULL),
(2, 'Chiuso', 20, '2017-03-10 09:52:05', NULL);

ALTER TABLE `orders_status`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `orders_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

  --
  -- Struttura della tabella `orders_status_history`
  --

  CREATE TABLE `orders_status_history` (
    `id` int(11) NOT NULL,
    `id_order` int(11) NOT NULL,
    `id_status` int(11) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `orders_status_history`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_status` (`id_status`),
    ADD KEY `id_order` (`id_order`);
  ALTER TABLE `orders_status_history`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- #7420: modulo fornitori
--
--
-- Struttura della tabella `invoices`
--

    DROP TABLE IF EXISTS `invoices`;
    CREATE TABLE `invoices` (
      `id` int(11) NOT NULL,
      `id_issuer` int(11) NOT NULL,
      `id_payer` int(11) NOT NULL,
      `id_order` int(11) NOT NULL,
      `id_payment_condition` int(11) NOT NULL,
      `id_purpose` int(11) NOT NULL,
      `id_scadenza` int(11) NOT NULL,
      `passive` tinyint(1) NOT NULL,
      `num` varchar(16) NOT NULL,
      `emission_date` date NOT NULL,
      `due_date` date NOT NULL,
      `paid_date` date NOT NULL,
      `paid` tinyint(1) NOT NULL,
      `split_payment` tinyint(1) NOT NULL,
      `bolli` decimal(10,2) NOT NULL,
      `amount_iva` decimal(10,2) NOT NULL,
      `amount_noiva` decimal(10,2) NOT NULL,
      `amount_topay` decimal(10,2) NOT NULL,
      `amount` decimal(10,2) NOT NULL,
      `description` mediumtext NOT NULL,
      `note` mediumtext NOT NULL,
      `attachment` varchar(255) NOT NULL,
      `deleted` tinyint(1) NOT NULL,
      `created` datetime DEFAULT NULL,
      `modified` datetime DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ALTER TABLE `invoices`
      ADD PRIMARY KEY (`id`),
      ADD KEY `id_issuer` (`id_issuer`),
      ADD KEY `id_payer` (`id_payer`),
      ADD KEY `id_order` (`id_order`),
      ADD KEY `id_payment_condition` (`id_payment_condition`),
      ADD KEY `id_purpose` (`id_purpose`),
      ADD KEY `id_scadenza` (`id_scadenza`);

    ALTER TABLE `invoices`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

      CREATE TABLE `payment_conditions` (
        `id` int(11) NOT NULL,
        `name` varchar(255) NOT NULL,
        `note` varchar(255) NOT NULL,
        `created` datetime DEFAULT NULL,
        `modified` datetime DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

      INSERT INTO `payment_conditions` (`id`, `name`, `note`, `created`, `modified`) VALUES
      (1, 'rimessa diretta', '', '2017-03-13 16:28:50', NULL),
      (2, '30 gg DF', '', '2017-03-13 16:28:50', NULL),
      (3, '30 gg FMDF', '', '2017-03-13 16:28:50', NULL),
      (4, '60 gg DF', '', '2017-03-13 16:28:50', NULL),
      (5, '60 gg FMDF', '', '2017-03-13 16:28:50', NULL);

      ALTER TABLE `payment_conditions`
        ADD PRIMARY KEY (`id`);
      ALTER TABLE `payment_conditions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
    --
    -- Struttura della tabella `invoices_purposes`
    --

        CREATE TABLE `invoices_purposes` (
          `id` int(11) NOT NULL,
          `parent_id` int(11) NOT NULL,
          `name` varchar(255) NOT NULL,
          `note` varchar(255) NOT NULL,
          `created` datetime DEFAULT NULL,
          `modified` datetime DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        INSERT INTO `invoices_purposes` (`id`, `parent_id`, `name`, `note`, `created`, `modified`) VALUES
        (1, 0, 'spese generali', '', '2017-03-13 16:37:44', NULL),
        (2, 0, 'beni e servizi', '', '2017-03-13 16:37:44', NULL),
        (3, 0, 'cogs', '', '2017-03-13 16:37:44', NULL),
        (4, 0, 'formazione', '', '2017-03-13 16:37:44', NULL),
        (5, 1, 'utilities', '', '2017-03-13 16:37:44', NULL),
        (6, 1, 'affitto', '', '2017-03-13 16:37:44', NULL),
        (7, 1, 'contabilita', '', '2017-03-13 16:37:44', NULL),
        (8, 1, 'gestione paghe', '', '2017-03-13 16:37:44', NULL),
        (9, 1, 'cancelleria', '', '2017-03-13 16:37:44', NULL),
        (10, 1, 'servizi online', '', '2017-03-13 16:37:44', NULL),
        (11, 1, 'sicurezza', '', '2017-03-13 16:37:44', NULL),
        (12, 1, 'rimborsi (viaggi, pranzi)', '', '2017-03-13 16:37:45', NULL),
        (13, 2, 'mobilio', '', '2017-03-13 16:37:45', NULL),
        (14, 2, 'hardware', '', '2017-03-13 16:37:45', NULL),
        (15, 2, 'manutenzione impianti', '', '2017-03-13 16:37:45', NULL),
        (16, 3, 'rivendita a cliente', '', '2017-03-13 16:37:45', NULL),
        (17, 3, 'rimborsi (viaggi, pranzi)', '', '2017-03-13 16:37:45', NULL),
        (18, 4, 'corsi', '', '2017-03-13 16:37:45', NULL),
        (19, 4, 'materiali', '', '2017-03-13 16:37:45', NULL);

        ALTER TABLE `invoices_purposes`
          ADD PRIMARY KEY (`id`),
          ADD KEY `parent_id` (`parent_id`);

        ALTER TABLE `invoices_purposes`
          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `aziende` ADD `interno` TINYINT(1) NOT NULL AFTER `fornitore`;
ALTER TABLE `aziende` ADD KEY `interno` (`interno`);
--
-- #7436: aggiungere ordring alla tabella payments_conditions
--
ALTER TABLE `payment_conditions` ADD `ordering` INT NOT NULL AFTER `note`, ADD INDEX (`ordering`);
--
-- #7470: aggiunte le delete logiche a varie tabelle
--
ALTER TABLE `scadenzario` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `note`, ADD INDEX (`deleted`) ;
ALTER TABLE `aziende` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `interno`, ADD INDEX (`deleted`) ;
ALTER TABLE `sedi` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `skype`, ADD INDEX (`deleted`) ;
ALTER TABLE `contatti` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `cf`, ADD INDEX (`deleted`) ;
--
-- #7468: nelle aziende il CAP deve non essere mostrato se vale 0
--
ALTER TABLE `contatti` CHANGE `cap` `cap` CHAR(5) NOT NULL;
UPDATE `contatti` SET `cap` = '' WHERE `cap` = 0;
--
-- #7467: creare l'element del widget donuts e mettere in home page i dati di fatture -> causali
--
ALTER TABLE `invoices_purposes` ADD `color` VARCHAR(50) NOT NULL AFTER `note`;
UPDATE `invoices_purposes` SET `color` = '#FFE382' WHERE `id` = 1;
UPDATE `invoices_purposes` SET `color` = '#1A3B69' WHERE `id` = 2;
UPDATE `invoices_purposes` SET `color` = '#BFBFBF' WHERE `id` = 3;
UPDATE `invoices_purposes` SET `color` = '#E0400A' WHERE `id` = 4;
--
-- #7439: modulo offerte
-- Struttura della tabella `offers`
--
CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `id_azienda_emit` int(11) NOT NULL,
  `id_contatto_emit` int(11) NOT NULL,
  `id_azienda_dest` int(11) NOT NULL,
  `id_sede_dest` int(11) NOT NULL,
  `id_contatto_dest` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `success_rate` int(11) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_azienda_emit` (`id_azienda_emit`),
  ADD KEY `id_contatto_emit` (`id_contatto_emit`),
  ADD KEY `id_azienda_dest` (`id_azienda_dest`),
  ADD KEY `id_sede_dest` (`id_sede_dest`),
  ADD KEY `id_contatto_dest` (`id_contatto_dest`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `id_status` (`id_status`);
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Struttura della tabella `offers_status`
--
  CREATE TABLE `offers_status` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ordering` int(11) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  INSERT INTO `offers_status` (`id`, `name`, `ordering`, `created`, `modified`) VALUES
  (1, 'Inviata', 10, '2017-03-21 15:33:37', NULL),
  (2, 'Rifiutata', 20, '2017-03-21 15:33:57', NULL);

  ALTER TABLE `offers_status`
    ADD PRIMARY KEY (`id`);
  ALTER TABLE `offers_status`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
  --
  -- Struttura della tabella `offers_status_history`
  --
    CREATE TABLE `offers_status_history` (
      `id` int(11) NOT NULL,
      `id_offer` int(11) NOT NULL,
      `id_status` int(11) NOT NULL,
      `created` datetime DEFAULT NULL,
      `modified` datetime DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ALTER TABLE `offers_status_history`
      ADD PRIMARY KEY (`id`),
      ADD KEY `id_status` (`id_status`),
      ADD KEY `id_offer` (`id_offer`);
    ALTER TABLE `offers_status_history`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- #7494: evolutiva offerte
--
ALTER TABLE `offers_status` ADD `color` VARCHAR(100) NOT NULL AFTER `ordering`;
UPDATE `offers_status` SET `color` = '#f39c12' WHERE `id` = 1;
UPDATE `offers_status` SET `color` = '#dd4b39' WHERE `id` = 2;
--
-- #7496: nelle offerte ci va la data di emissione esposta
--
ALTER TABLE `offers` ADD `emission_date` date NOT NULL AFTER `attachment`;
ALTER TABLE `offers` ADD KEY `emission_date` (`emission_date`);
--
-- #7509: Modifica cliente e fornitore
--
ALTER TABLE `aziende` CHANGE `fornitore` `fornitore` TINYINT(1) NOT NULL;
ALTER TABLE `aziende` CHANGE `cliente` `cliente` TINYINT(1) NOT NULL;

ALTER TABLE `sedi_tipi` CHANGE `order` `ordering` INT(11) NOT NULL;
ALTER TABLE `sedi_tipi` CHANGE `created` `created` DATETIME NULL;
ALTER TABLE `sedi_tipi` ADD `color` VARCHAR(100) NOT NULL AFTER `ordering`;
UPDATE `sedi_tipi` SET `color` = '#f39c12' WHERE `id` = 1;
UPDATE `sedi_tipi` SET `color` = '#00c0ef' WHERE `id` = 2;


INSERT INTO `configurations`
(`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`)
  VALUES ( 'generico', 'SKIN_COLOR', 'Il colore del template', 'Il colore del template', 'yellow', 'text', '500', NOW(), NOW());
INSERT INTO `configurations`
(`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`)
  VALUES ( 'generico', 'SKIN_LIGTH', 'Usa il template ligth', 'Usa il template ligth', '1', 'checkbox', '500', NOW(), NOW());


ALTER TABLE `orders_status` ADD `color` VARCHAR(100) NOT NULL AFTER `ordering`;
UPDATE `orders_status` SET `color` = '#f39c12' WHERE `id` = 1;
UPDATE `orders_status` SET `color` = '#00a65a' WHERE `id` = 2;


ALTER TABLE `orders` ADD `deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `orders` ADD INDEX(`deleted`);
--
-- #7656: Aggiunta skills
--
--
-- Struttura della tabella `progest_skills`
--
CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `skills`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `skills`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Struttura della tabella `progest_skills_contacts`
--
CREATE TABLE `skills_contacts` (
  `id` int(11) NOT NULL,
  `id_contatto` int(11) NOT NULL,
  `id_skill` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `skills_contacts`
ADD PRIMARY KEY (`id`),
ADD KEY `id_contatto` (`id_contatto`),
ADD KEY `id_skill` (`id_skill`);
ALTER TABLE `skills_contacts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `contatti` ADD `id_user` INT NOT NULL AFTER `id_ruolo`, ADD INDEX (`id_user`);
ALTER TABLE `calendar_events` ADD `id_service` INT NOT NULL AFTER `id_order`, ADD INDEX (`id_service`);
--
-- #7734: Intervallo di def nel calendario
--
INSERT INTO `configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`)
 VALUES ( 'calendar', 'DEFAULT_DURATION',
   'Durata di default di un elemento alla creazione in formato "ore:minuti:secondi" .',
   'Durata di default di un elemento alla creazione in formato "ore:minuti:secondi" .',
    '01:00:00', 'text', '500',
     NOW(), NOW());
--
-- #7741:  gruppi delle aziende
--
-- Creazione tabella aziende_gruppi
--
CREATE TABLE `aziende_gruppi` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `aziende_gruppi`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `aziende_gruppi`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Creazione tabella aziende_to_gruppi
--
CREATE TABLE `aziende_to_gruppi` (
  `id` int(11) NOT NULL,
  `id_gruppo` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `aziende_to_gruppi`
ADD PRIMARY KEY (`id`),
ADD KEY `id_gruppo` (`id_gruppo`),
ADD KEY `id_azienda` (`id_azienda`);
ALTER TABLE `aziende_to_gruppi`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- #7746: History status buoni d'ordine
--
ALTER TABLE `orders_status_history` ADD `id_user` INT NOT NULL DEFAULT '0' AFTER `id_status`;
