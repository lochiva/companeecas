--
-- Table structure for table `reports_educational_qualifications`
--

CREATE TABLE `reports_educational_qualifications` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_educational_qualifications` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Nessuno', 10, 0, NOW(), NOW()),
(2, 'Licenza elementare', 20, 0, NOW(), NOW()),
(3, 'Licenza media', 30, 0, NOW(), NOW()),
(4, 'Diploma di scuola superiore', 40, 0, NOW(), NOW()),
(5, 'Qualifica professionale', 50, 0, NOW(), NOW()),
(6, 'Laurea', 60, 0, NOW(), NOW()),
(7, 'Formazione post-laurea', 70, 0, NOW(), NOW());

ALTER TABLE `reports_educational_qualifications`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_educational_qualifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


--
-- Table structure for table `reports_religions`
--

CREATE TABLE `reports_religions` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_religions` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Cristiana', 10, 1, NOW(), NOW()),
(2, 'Musulmana', 20, 1, NOW(), NOW()),
(3, 'Ebraica', 30, 0, NOW(), NOW()),
(4, 'Altro', 40, 1, NOW(), NOW()),
(5, 'Nessuna', 50, 0, NOW(), NOW());

ALTER TABLE `reports_religions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_religions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


--
-- Table structure for table `reports_occupation_types`
--

CREATE TABLE `reports_occupation_types` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_occupation_types` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Disoccupata/o', 10, 0, NOW(), NOW()),
(2, 'Inattiva/o', 20, 0, NOW(), NOW()),
(3, 'Studentessa/studente', 30, 0, NOW(), NOW()),
(4, 'Pensionata/o', 40, 0, NOW(), NOW()),
(5, 'Cassintegrata/o', 50, 0, NOW(), NOW()),
(6, 'Dipendente a tempo indeterminato', 60, 0, NOW(), NOW()),
(7, 'Dipendente a tempo determinato', 70, 0, NOW(), NOW()),
(8, 'Lavoro parasubordinato', 80, 0, NOW(), NOW()),
(9, 'Lavoro autonomo', 90, 0, NOW(), NOW()),
(10, 'Lavoro irregolare', 100, 0, NOW(), NOW());

ALTER TABLE `reports_occupation_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_occupation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;


--
-- Table structure for table `reports_marital_statuses`
--

CREATE TABLE `reports_marital_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_marital_statuses` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Nubile/celibe', 10, 0, NOW(), NOW()),
(2, 'Coniugata/o', 20, 0, NOW(), NOW()),
(3, 'Unita/o civilmente', 30, 0, NOW(), NOW()),
(4, 'Separata/o', 40, 0, NOW(), NOW()),
(5, 'Divorziata/o', 50, 0, NOW(), NOW()),
(6, 'Già unita/o civilmente', 60, 0, NOW(), NOW()),
(7, 'Convivente', 70, 0, NOW(), NOW());

ALTER TABLE `reports_marital_statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_marital_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


--
-- Table structure for table `reports_residency_permits`
--

CREATE TABLE `reports_residency_permits` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_residency_permits` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Nessuno', 10, 0, NOW(), NOW()),
(2, 'Richiesta asilo', 20, 0, NOW(), NOW()),
(3, 'Protezione internazionale', 30, 0, NOW(), NOW()),
(4, 'Motivi di lavoro', 40, 0, NOW(), NOW()),
(5, 'Motivi familiari', 50, 0, NOW(), NOW()),
(6, 'Permesso soggiornanti di lungo periodo', 60, 0, NOW(), NOW()),
(7, 'Altro', 70, 1, NOW(), NOW());

ALTER TABLE `reports_residency_permits`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_residency_permits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


--
-- Modifica tracciati anagrafiche
--
ALTER TABLE `reports_victims` CHANGE `educational_qualification` `educational_qualification_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `educational_qualification_user_text` VARCHAR(64) NULL AFTER `educational_qualification_id`;
ALTER TABLE `reports_victims` CHANGE `religion` `religion_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `religion_user_text` VARCHAR(64) NULL AFTER `religion_id`;
ALTER TABLE `reports_victims` CHANGE `type_occupation` `type_occupation_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `type_occupation_user_text` VARCHAR(64) NULL AFTER `type_occupation_id`;
ALTER TABLE `reports_victims` CHANGE `marital_status` `marital_status_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `marital_status_user_text` VARCHAR(64) NULL AFTER `marital_status_id`;
ALTER TABLE `reports_victims` CHANGE `residency_permit` `residency_permit_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `residency_permit_user_text` VARCHAR(64) NULL AFTER `residency_permit_id`;

ALTER TABLE `reports_witnesses` CHANGE `educational_qualification` `educational_qualification_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `educational_qualification_user_text` VARCHAR(64) NULL AFTER `educational_qualification_id`;
ALTER TABLE `reports_witnesses` CHANGE `religion` `religion_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `religion_user_text` VARCHAR(64) NULL AFTER `religion_id`;
ALTER TABLE `reports_witnesses` CHANGE `type_occupation` `type_occupation_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `type_occupation_user_text` VARCHAR(64) NULL AFTER `type_occupation_id`;
ALTER TABLE `reports_witnesses` CHANGE `marital_status` `marital_status_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `marital_status_user_text` VARCHAR(64) NULL AFTER `marital_status_id`;
ALTER TABLE `reports_witnesses` CHANGE `residency_permit` `residency_permit_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `residency_permit_user_text` VARCHAR(64) NULL AFTER `residency_permit_id`;


--
-- Aggiunto coice provincia in anagrafica aziende
--
ALTER TABLE `aziende` ADD `codice_provincia` VARCHAR(5) NOT NULL AFTER `denominazione`;

--
-- Modifica codice segnalazione
--
ALTER TABLE `reports` CHANGE `code` `code` VARCHAR(5) NOT NULL;
ALTER TABLE `reports` DROP INDEX code ;
ALTER TABLE `reports` ADD `province_code` VARCHAR(5) NOT NULL AFTER `code`;
ALTER TABLE `reports` ADD CONSTRAINT code_province_code UNIQUE(`code`, `province_code`);


--
-- Modifiche a tabella luoghi
--
ALTER TABLE `luoghi` ADD `user_text` BOOLEAN NOT NULL AFTER `enabled`;
INSERT INTO `luoghi` (`c_luo`, `in_luo`, `in_luo_orig`, `des_luo`, `c_rgn`, `c_prv`, `c_cat`, `s_prv`, `enabled`, `user_text`) VALUES 
('2', '5', '', 'DOPPIA O PLURIMA CITTADINANZA', '', '', '', '', '1', '1');

ALTER TABLE `reports_victims` CHANGE `citizenship` `citizenship_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_victims` ADD `citizenship_user_text` VARCHAR(64) NULL AFTER `citizenship_id`;

ALTER TABLE `reports_witnesses` CHANGE `citizenship` `citizenship_id` INT NULL DEFAULT NULL;
ALTER TABLE `reports_witnesses` ADD `citizenship_user_text` VARCHAR(64) NULL AFTER `citizenship_id`;