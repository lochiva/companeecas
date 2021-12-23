--
-- Struttura della tabella `progest_people`
--

CREATE TABLE `progest_people` (
  `id` int(11) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthtown` varchar(255) NOT NULL,
  `birthstate` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `fiscalcode` varchar(50) NOT NULL,
  `gender` char(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `progest_people`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `progest_people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Altert table orders
--
ALTER TABLE `orders` ADD `id_person` INT NOT NULL AFTER `closed`,
  ADD `id_person_type` INT NOT NULL AFTER `id_person`,
  ADD `id_invoice_type` INT NOT NULL AFTER `id_person_type`,
  ADD `protocol_number` VARCHAR(32) NOT NULL AFTER `id_invoice_type`,
  ADD `protocol_date` DATE NOT NULL AFTER `protocol_number`,
  ADD `start_date` DATE NOT NULL AFTER `protocol_date`,
  ADD `end_date` DATE NOT NULL AFTER `start_date`,
  ADD `self_sufficient` TINYINT(1) NOT NULL AFTER `end_date`,
  ADD `self_percent` INT NOT NULL AFTER `self_sufficient`,
  ADD `paid_by_asl_percent` INT NOT NULL AFTER `self_percent`,
  ADD `paid_by_azienda_percent` INT NOT NULL AFTER `paid_by_asl_percent`,
  ADD `paid_by_person_percent` INT NOT NULL AFTER `paid_by_azienda_percent`,
  ADD `paid_by_person_amount` DECIMAL(10,2) NOT NULL AFTER `paid_by_person_percent`,
  ADD INDEX (`id_person`), ADD INDEX (`id_person_type`), ADD INDEX (`id_invoice_type`);

  --
  -- Struttura della tabella `progest_person_types`
  --
  CREATE TABLE `progest_person_types` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ordering` int(11) NOT NULL,
    `color` varchar(100) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `progest_person_types`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ordering` (`ordering`);
  ALTER TABLE `progest_person_types`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `progest_person_types` ( `name`, `ordering`, `created`, `modified`) VALUES
  ( 'anziano autosufficiente', 10, NOW(), NOW()),
  ( 'anziano non autosufficiente', 20, NOW(), NOW()),
  ( 'disabile', 30, NOW(), NOW()),
  ( 'minore', 40, NOW(), NOW()),
  ( 'minore disabile', 50, NOW(), NOW()),
  ( 'adulto', 60, NOW(), NOW()),
  ( 'terminale', 70, NOW(), NOW()),
  ( 'psichiatria', 80, NOW(), NOW()),
  ( 'minori/adulti in difficoltà', 90, NOW(), NOW()),
  ( 'malattia invalidante', 100, NOW(), NOW());

  --
  -- Struttura della tabella `progest_invoice_types`
  --
  CREATE TABLE `progest_invoice_types` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ordering` int(11) NOT NULL,
    `color` varchar(100) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `progest_invoice_types`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ordering` (`ordering`);
  ALTER TABLE `progest_invoice_types`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  INSERT INTO `progest_invoice_types` ( `name`, `ordering`, `created`, `modified`) VALUES
      ( 'UVG', 10, NOW(), NOW()),
      ( 'UVAP', 20, NOW(), NOW()),
      ( 'UVM', 30, NOW(), NOW()),
      ( 'UMVD', 40, NOW(), NOW()),
      ( 'Dgr. 39', 50, NOW(), NOW()),
      ( 'Dgr. 56', 60, NOW(), NOW()),
      ( 'Carico Consorzio', 70, NOW(), NOW()),
      ( 'Carico Comune', 80, NOW(), NOW()),
      ( 'Carico ASL', 90, NOW(), NOW()),
      ( 'ADI', 100, NOW(), NOW()),
      ( 'Privato', 110, NOW(), NOW()),
      ( 'lista UVG', 120, NOW(), NOW()),
      ( 'minori adulti in difficoltà', 130, NOW(), NOW());
  --
  --  #7589: evolutiva buoni ordine e persone
  --
  --
  -- Struttura della tabella `progest_people_extension`
  --
  CREATE TABLE `progest_people_extension` (
    `id` int(11) NOT NULL,
    `id_person` int(11) NOT NULL,
    `last` tinyint(1) NOT NULL DEFAULT 1,
    `address` varchar(255) NOT NULL,
    `comune` varchar(255) NOT NULL,
    `cap`  char(5) NOT NULL,
    `provincia` char(2) NOT NULL,
    `tel` varchar(50) NOT NULL,
    `cell` varchar(50) NOT NULL,
    `email` varchar(255) NOT NULL,
    `deleted` tinyint(1) NOT NULL DEFAULT 0,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ALTER TABLE `progest_people_extension`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_person` (`id_person`),
    ADD KEY `last` (`last`),
    ADD KEY `deleted` (`deleted`);
  ALTER TABLE `progest_people_extension`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

UPDATE `province` SET `enabled` = 1 WHERE `s_prv` LIKE 'TO';
UPDATE `luoghi` SET `enabled` = 1 WHERE `c_luo` IN (401001272,401001219);
UPDATE `cap` SET `enabled` = 1 WHERE `localita` LIKE 'TORINO' OR 'localita' LIKE 'RIVOLI';

CREATE TABLE `progest_familiari` (
  `id` int(11) NOT NULL,
  `id_person` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `id_grado_parentela` int(11) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `cell` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `comune` varchar(255) NOT NULL,
  `cap`  char(5) NOT NULL,
  `provincia` char(2) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_familiari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_person` (`id_person`),
  ADD KEY `id_grado_parentela` (`id_grado_parentela`),
  ADD KEY `deleted` (`deleted`);
ALTER TABLE `progest_familiari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  --
  -- Struttura della tabella `progest_person_types`
  --degree_kinship
  CREATE TABLE `progest_grado_parentela` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ordering` int(11) NOT NULL,
    `color` varchar(100) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `progest_grado_parentela`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ordering` (`ordering`);
  ALTER TABLE `progest_grado_parentela`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  INSERT INTO `progest_grado_parentela` ( `name`, `ordering`, `created`, `modified`) VALUES
  ( 'Coniuge', 10, NOW(), NOW()),
  ( 'Figlio/Figlia', 20, NOW(), NOW()),
  ( 'Nipote', 30, NOW(), NOW()),
  ( 'Medico', 40, NOW(), NOW());
# eseguito 2017 05 12

ALTER TABLE `orders` ADD `id_richiedente` INT NOT NULL AFTER `paid_by_person_amount`,
ADD `fatturazione_nominativo` VARCHAR(255) NOT NULL AFTER `id_richiedente`,
ADD `fatturazione_indirizzo` VARCHAR(255) NOT NULL AFTER `fatturazione_nominativo`,
ADD `fatturazione_provincia` CHAR(2) NOT NULL AFTER `fatturazione_indirizzo`,
ADD `fatturazione_comune` VARCHAR(255) NOT NULL AFTER `fatturazione_provincia`,
ADD `fatturazione_cap` VARCHAR(10) NOT NULL AFTER `fatturazione_comune`,
ADD `fatturazione_cf` VARCHAR(50) NOT NULL AFTER `fatturazione_cap`,
ADD INDEX (`id_richiedente`);
--
-- #7617: correttive dopo prima consegna a utenti
--
ALTER TABLE `orders` ADD `costo_mensile` DECIMAL(10,2) NOT NULL AFTER `paid_by_person_amount`,
ADD `activation_date` DATE NOT NULL AFTER `costo_mensile`;
--
-- #7636: le percentuali vano con 2 decimali
--
ALTER TABLE `orders`
  MODIFY `paid_by_azienda_percent` DECIMAL(10,2) NOT NULL,
  MODIFY `paid_by_asl_percent` DECIMAL(10,2) NOT NULL,
  MODIFY `paid_by_person_percent` DECIMAL(10,2) NOT NULL;
--
-- #7642: oss colf, pasti, AF, telesoccorso. Servizi
--
-- Tabella progest_services
--
  CREATE TABLE `progest_services` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `ordering` int(11) NOT NULL,
    `color` varchar(100) NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  ALTER TABLE `progest_services`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ordering` (`ordering`);
  ALTER TABLE `progest_services`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  INSERT INTO `progest_services` ( `name`, `ordering`, `created`, `modified`) VALUES
  ( 'OSS', 10, NOW(), NOW()),
  ( 'Colf', 20, NOW(), NOW()),
  ( 'Pasti', 30, NOW(), NOW()),
  ( 'Assistente', 40, NOW(), NOW()),
  ( 'A.F.', 50, NOW(), NOW()),
  ( 'Telesoccorso', 60, NOW(), NOW());
--
-- tabella progest_services_orders
--
CREATE TABLE `progest_services_orders` (
  `id` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `ore_num` decimal(10,2) NOT NULL COMMENT "numero ore, in caso pasti numero pasti",
  `ore_festive` decimal(10,2) NOT NULL,
  `dettaglio` TEXT NOT NULL,
  `fle_orario` int(11) NOT NULL COMMENT "chiave progest_services_flexibility.id",
  `fle_giorni` int(11) NOT NULL COMMENT "chiave progest_services_flexibility.id",
  `fle_operatore` int(11) NOT NULL COMMENT "chiave progest_services_flexibility.id",
  `id_apl` int(11) NOT NULL,
  `cell` tinyint(1) NOT NULL DEFAULT 0,
  `chiavi` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `progest_services_orders`
ADD PRIMARY KEY (`id`),
ADD KEY `id_order` (`id_order`),
ADD KEY `id_service` (`id_service`),
ADD KEY `fle_orario` (`fle_orario`),
ADD KEY `fle_giorni` (`fle_giorni`),
ADD KEY `fle_operatore` (`fle_operatore`),
ADD KEY `id_apl` (`id_apl`);
ALTER TABLE `progest_services_orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- tabella progest_services_apl
--
CREATE TABLE `progest_services_apl` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `progest_services_apl`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `progest_services_apl`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `progest_services_apl` ( `name`, `ordering`, `created`, `modified`) VALUES
( 'synergie', 10, NOW(), NOW()),
( 'humana', 20, NOW(), NOW()),
( 'GI group spa', 30, NOW(), NOW());
--
-- tabella progest_services_flexibility
--
CREATE TABLE `progest_services_flexibility` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `progest_services_flexibility`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `progest_services_flexibility`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `progest_services_flexibility` ( `name`, `ordering`, `created`, `modified`) VALUES
( 'si', 10, NOW(), NOW()),
( 'abbastanza', 20, NOW(), NOW()),
( 'poco', 30, NOW(), NOW()),
( 'no', 40, NOW(), NOW());
--
-- #7643: referenti committenza e sanitari
--
ALTER TABLE `contatti_ruoli` ADD `active` TINYINT(1) NOT NULL AFTER `color`, ADD INDEX (`active`);
--
-- Struttura della tabella `progest_orders_contacts`
--
CREATE TABLE `progest_contacts_orders` (
  `id` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_contatto` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_contacts_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_contatto` (`id_contatto`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_order` (`id_order`);
ALTER TABLE `progest_contacts_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- #7656: Estensione del contatto con le attività svolte
--
-- Struttura della tabella `progest_skills_services`
--
CREATE TABLE `progest_skills_services` (
  `id` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_skill` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_skills_services`
ADD PRIMARY KEY (`id`),
ADD KEY `id_service` (`id_service`),
ADD KEY `id_skill` (`id_skill`);
ALTER TABLE `progest_skills_services`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `skills` ( `name`, `ordering`, `color`, `created`, `modified`) VALUES
('Oss', 10, '', NULL, NULL),
('Colf', 20, '', NULL, NULL),
('Assistente Famigliare', 30, '', NULL, NULL);

INSERT INTO `progest_skills_services` ( `id_service`, `id_skill`, `created`, `modified`) VALUES
( 1, 1, NULL, NULL),
( 2, 2, NULL, NULL),
( 3, 3, NULL, NULL),
( 4, 3, NULL, NULL),
( 5, 3, NULL, NULL);
--
-- #7668:  Creiamo una categorizzazione dei servizi
--
-- tabella progest_categories
--
CREATE TABLE `progest_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_categories`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `progest_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Struttura della tabella `progest_categories_services`
--
CREATE TABLE `progest_categories_services` (
  `id` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_categories_services`
ADD PRIMARY KEY (`id`),
ADD KEY `id_service` (`id_service`),
ADD KEY `id_category` (`id_category`);
ALTER TABLE `progest_categories_services`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
INSERT INTO `progest_categories` ( `name`, `ordering`, `color`, `created`, `modified`) VALUES
('Attività esterna', 10, '', NOW(), NOW());
INSERT INTO `progest_categories_services` ( `id_service`, `id_category`, `created`, `modified`) VALUES
( 1, 1, NOW(), NOW()),
( 2, 1, NOW(), NOW()),
( 3, 1, NOW(), NOW()),
( 4, 1, NOW(), NOW()),
( 5, 1, NOW(), NOW()),
( 6, 1, NOW(), NOW());
--
-- #7688: Gestione calendario Progest Fase 2
--
INSERT INTO `progest_categories` ( `name`, `ordering`, `color`, `created`, `modified`) VALUES
('Attività interna', 20, '', NOW(), NOW());

ALTER TABLE `progest_categories` ADD `parent` INT NOT NULL DEFAULT '0' AFTER `id`;
--
-- #7736: Servizi aggiunti passaggi settimanali
--
ALTER TABLE `progest_services_orders` ADD `passaggi_settimanali` INT NOT NULL DEFAULT '0' AFTER `ore_festive`;
--
-- #7741:  gruppi delle aziende
--
INSERT INTO `aziende_gruppi` ( `name`, `ordering`, `color`, `created`, `modified`) VALUES
('CISA', 10, '', NOW(), NOW()),
('CISAP', 20, '', NOW(), NOW()),
('Torino', 30, '', NOW(), NOW());

-- crea le relazioni per cisa e cisap
INSERT INTO `aziende_to_gruppi` (`id`, `id_gruppo`, `id_azienda`, `created`, `modified`) VALUES (NULL, '1', '1', NOW(), NOW()), (NULL, '2', '2', NOW(), NOW());

-- crea le torino
INSERT INTO `aziende_to_gruppi` (`id`, `id_gruppo`, `id_azienda`, `created`, `modified`) VALUES ('', '3', '3', NOW(), NOW()), ('', '3', '4', NOW(), NOW()), ('', '3', '5', NOW(), NOW()), ('', '3', '6', NOW(), NOW()), ('', '3', '7', NOW(), NOW()), ('', '3', '8', NOW(), NOW()), ('', '3', '9', NOW(), NOW()), ('', '3', '10', NOW(), NOW());
--
-- #7743: aggiuto campo billable ai servizi
--
ALTER TABLE `progest_services` ADD `billable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `name`;
UPDATE `progest_services` SET `billable` = 1;
--
-- #7740: aggiuto campo cloned agli eventi
--
ALTER TABLE `calendar_events` ADD `cloned` TINYINT(1) NOT NULL DEFAULT '0' AFTER `vobject`;
ALTER TABLE `calendar_repeated_events` ADD `cloned` TINYINT(1) NOT NULL DEFAULT '0' AFTER `end`;
--
-- #7745: Pianificatore fase 2
--
ALTER TABLE `progest_services_orders` ADD `frequenza` INT NOT NULL DEFAULT '1' AFTER `passaggi_settimanali`;
--
-- tabella progest_services_frequency
--
CREATE TABLE `progest_services_frequency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `progest_services_frequency`
ADD PRIMARY KEY (`id`),
ADD KEY `ordering` (`ordering`);
ALTER TABLE `progest_services_frequency`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `progest_services_frequency` ( `name`, `ordering`, `color`, `created`, `modified`) VALUES
('Settimanale', 10, '', NOW(), NOW()),
('15 giorni', 20, '', NOW(), NOW()),
('Mensile', 30, '', NOW(), NOW());
--
-- default per ore_festive servizi
--
ALTER TABLE `progest_services_orders` CHANGE `ore_festive` `ore_festive` DECIMAL(10,2) NOT NULL DEFAULT '0';
--
-- #7936: forzen calendar events
--
CREATE TABLE `calendar_events_frozen` (
  `id` int(11) NOT NULL,
  `id_google` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_contatto` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_service` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `allDay` tinyint(1) NOT NULL,
  `repeated` tinyint(4) NOT NULL,
  `backgroundColor` varchar(10) NOT NULL,
  `borderColor` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `id_parentEvent` int(11) NOT NULL,
  `vobject` text NOT NULL,
  `cloned` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `calendar_events_frozen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_azienda` (`id_azienda`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `id_contatto` (`id_contatto`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `repeated` (`repeated`),
  ADD KEY `id_service` (`id_service`);
ALTER TABLE `calendar_events_frozen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- calendar_weeks
--
CREATE TABLE `calendar_weeks` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `frozen` tinyint(1) NOT NULL DEFAULT '1',
  `frozen_date` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `calendar_weeks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);
ALTER TABLE `calendar_weeks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- #7882 aggiunto cloned_from agli eventi
--
ALTER TABLE `calendar_events` ADD `cloned_from` INT NOT NULL DEFAULT '0' AFTER `cloned`;
--
-- #8059: aggiunti servizi con titolo editabili
--
ALTER TABLE `progest_services` ADD `editable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `billable`;

--
-- #8486: aggiunto campo deceduto
---
ALTER TABLE `progest_people` ADD `deceased` VARCHAR(2) NOT NULL DEFAULT 'no' AFTER `birthdate`;
ALTER TABLE `progest_people` ADD INDEX(`deceased`);


#9088: ignora il controllo
ALTER TABLE `orders` ADD `ignora_controllo` TINYINT NOT NULL DEFAULT '0' AFTER `id_contatto`, ADD INDEX (`ignora_controllo`);
ALTER TABLE `orders` ADD `ignora_note` varchar(256) AFTER `ignora_controllo`;

#latitudine e longitudine per webappALTER TABLE `calendar_events_detail` ADD `start_lat` VARCHAR(16) NOT NULL AFTER `real_end`, ADD `stop_lat` VARCHAR(16) NOT NULL AFTER `start_lat`, ADD `start_long` VARCHAR(16) NOT NULL AFTER `stop_lat`, ADD `stop_long` VARCHAR(16) NOT NULL AFTER `start_long`;

#firma per webapp
ALTER TABLE `calendar_events_detail` ADD `signature` TEXT NOT NULL DEFAULT '' AFTER `real_end`;
