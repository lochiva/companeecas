--
-- Aggiunto campo pec atti commissione per enti
--
ALTER TABLE `aziende` ADD `pec_commissione` VARCHAR(255) NOT NULL AFTER `pec`;

--
-- Aggiunto campo capienza per strutture
--
ALTER TABLE `sedi` ADD `n_posti` INT NOT NULL DEFAULT '0' AFTER `skype`;



--
-- Struttura della tabella `sedi_tipologie_ospiti`
--

CREATE TABLE `sedi_tipologie_ospiti` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `sedi_tipologie_ospiti` (`id`, `name`, `ordering`, `deleted`, `created`, `modified`) VALUES
(1, 'Donne', 10, 0, NOW(), NOW()),
(2, 'Donne e minori', 20, 0, NOW(), NOW()),
(3, 'Nuclei familiari', 30, 0, NOW(), NOW()),
(4, 'Nuclei familiari e donne', 40, 0, NOW(), NOW()),
(5, 'Nuclei familiari e minori', 50, 0, NOW(), NOW()),
(6, 'Nuclei familiari e uomini', 60, 0, NOW(), NOW()),
(7, 'Uomini', 70, 0, NOW(), NOW()),
(8, 'Uomini e donne', 80, 0, NOW(), NOW());

ALTER TABLE `sedi_tipologie_ospiti`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sedi_tipologie_ospiti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;



--
-- Struttura della tabella `sedi_sedi_to_tipologia_ospite`
--

CREATE TABLE `sedi_sedi_to_tipologie_ospiti` (
  `id` int NOT NULL,
  `sede_id` int NOT NULL,
  `tipologia_ospite_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

ALTER TABLE `sedi_sedi_to_tipologie_ospiti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sede_id` (`sede_id`),
  ADD KEY `tipologia_ospite_id` (`tipologia_ospite_id`);

ALTER TABLE `sedi_sedi_to_tipologie_ospiti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;



--
-- Campi referente ente
--
ALTER TABLE `aziende` ADD `referente_1` VARCHAR(255) NOT NULL AFTER `pec_commissione`, 
ADD `referente_2` VARCHAR(255) NOT NULL AFTER `referente_1`;



--
-- Struttura della tabella `sedi_tipologie_centro`
--

CREATE TABLE `sedi_tipologie_centro` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `sedi_tipologie_centro` (`id`, `name`, `ordering`, `deleted`, `created`, `modified`) VALUES
(1, 'CAS adulti', 10, 0, NOW(), NOW()),
(2, 'CPA', 20, 0, NOW(), NOW()),
(3, 'Hot spot', 30, 0, NOW(), NOW()),
(4, 'CAS minori', 40, 0, NOW(), NOW());

ALTER TABLE `sedi_tipologie_centro`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;


--
-- Cancellazione tabella di relazione struttura-tipologia ospiti
--
DROP TABLE `sedi_sedi_to_tipologie_ospiti`;


--
-- Chiave tipologia centro e tipologia ospiti in struttura
--
ALTER TABLE `sedi` 
ADD `id_tipologia_centro` INT NOT NULL AFTER `id_tipo`, 
ADD `id_tipologia_ospiti` INT NOT NULL AFTER `id_tipologia_centro`, 
ADD INDEX (`id_tipologia_centro`), 
ADD INDEX (`id_tipologia_ospiti`);



--
-- Nuovi campi struttura
--
ALTER TABLE `sedi` CHANGE `n_posti` `n_posti_convenzione` INT NOT NULL DEFAULT '0';
ALTER TABLE `sedi` 
ADD `n_posti_effettivi` INT NOT NULL DEFAULT '0' AFTER `n_posti_convenzione`,
ADD `id_procedura_affidamento` INT NOT NULL AFTER `n_posti_effettivi`, 
ADD `operativita` INT NOT NULL COMMENT '1: Attivo; 0: Chiuso;' AFTER `id_procedura_affidamento`, 
ADD INDEX (`id_procedura_affidamento`), ADD INDEX (`operativita`);



--
-- Struttura della tabella `sedi_procedure_affidamento`
--

CREATE TABLE `sedi_procedure_affidamento` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `sedi_procedure_affidamento` (`id`, `name`, `ordering`, `deleted`, `created`, `modified`) VALUES
(1, 'Procedura aperta', 10, 0, NOW(), NOW()),
(2, 'Procedura negoziata previa pubblicazione del bando', 20, 0, NOW(), NOW()),
(3, 'Procedura negoziata senza previa pubblicazione del bando', 30, 0, NOW(), NOW()),
(4, 'Procedura ristretta', 40, 0, NOW(), NOW()),
(5, 'Affidamento diretto', 50, 0, NOW(), NOW()),
(6, 'Convenzione tra enti locali', 60, 0, NOW(), NOW());

ALTER TABLE `sedi_procedure_affidamento`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sedi_procedure_affidamento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;
