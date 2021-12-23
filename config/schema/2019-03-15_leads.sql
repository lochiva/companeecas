--
-- Table structure for table `leads_ensemble`
--

CREATE TABLE IF NOT EXISTS `leads_ensembles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `leads_ensembles`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `leads_ensembles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Campo deleted per ensembles
--
ALTER TABLE `leads_ensembles` ADD `deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `active`;


--
-- Table structure for table `leads_questions`
--

CREATE TABLE IF NOT EXISTS `leads_questions` (
  `id` int(11) NOT NULL,
  `id_ensemble` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_type` int(11) NOT NULL,
  `info` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `leads_questions`
 ADD PRIMARY KEY (`id`), ADD KEY `id_ensemble` (`id_ensemble`);

ALTER TABLE `leads_questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `leads_question_types`
--

CREATE TABLE IF NOT EXISTS `leads_question_types` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `leads_question_types`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `question_types`
--
ALTER TABLE `leads_question_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `leads_question_types` (`id`, `type`, `label`, `created`, `modified`) VALUES 
(NULL, 'varchar', 'Testo libero', NOW(), NOW()),
(NULL, 'date', 'Data', NOW(), NOW()),
(NULL, 'boolean', 'Sì/No', NOW(), NOW());


--
-- Table structure for table `leads_interviews`
--

CREATE TABLE IF NOT EXISTS `leads_interviews` (
  `id` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_ensemble` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `leads_interviews`
 ADD PRIMARY KEY (`id`), ADD KEY `id_azienda` (`id_azienda`,`id_ensemble`);

ALTER TABLE `leads_interviews`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `leads_answers`
--

CREATE TABLE IF NOT EXISTS `leads_answers` (
  `id` int(11) NOT NULL,
  `id_interview` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `question_answer` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `leads_answers`
 ADD PRIMARY KEY (`id`), ADD KEY `id_interview` (`id_interview`,`id_question`);

ALTER TABLE `leads_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Campo deleted per interviste
--
ALTER TABLE `leads_interviews` ADD `deleted` TINYINT NOT NULL AFTER `id_ensemble`;


--
-- Campo nome per interviste
--
ALTER TABLE `leads_interviews` ADD `name` VARCHAR(255) NOT NULL AFTER `id_ensemble`;


--
-- TIpo scelta singola e file per le domande
--
INSERT INTO `leads_question_types` (`type`, `label`, `created`, `modified`) 
VALUES ('select', 'Scelta singola', NOW(), NOW());
INSERT INTO `leads_question_types` (`type`, `label`, `created`, `modified`) 
VALUES ('file', 'Carica file', NOW(), NOW());



--
-- Campo opzioni per le domande
--
ALTER TABLE `leads_questions` ADD `options` TEXT NOT NULL AFTER `info`;


--
-- Chiave config con path per upload file interviste
--
INSERT INTO `configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES ('leads', 'INTERVIEWS_UPLOAD_PATH', 'upload path', 'la cartella relativa alla document root, con lo / finale , ad esempio files/', 'webroot/files/', 'text', '0', NOW(), NOW());



--
--  Campo id_contatto per interviews
--
ALTER TABLE `leads_interviews` ADD `id_contatto` INT NOT NULL AFTER `id_azienda`;