--
-- Table structure for table `surveys`
--

CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Modifica campo status per mettere id da tabella
--
ALTER TABLE `surveys` CHANGE `status` `status` INT NOT NULL;



--
-- Table structure for table `surveys_status`
--

CREATE TABLE IF NOT EXISTS `surveys_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `surveys_statuses` (`id`, `name`, `ordering`, `created`, `modified`) VALUES
(1, 'Pubblicato', 1, '2019-05-28 11:45:47', '2019-05-28 11:45:47'),
(2, 'Bozza', 2, '2019-05-28 11:45:47', '2019-05-28 11:45:47'),
(3, 'Annullato', 3, '2019-05-28 11:45:47', '2019-05-28 11:45:47');

ALTER TABLE `surveys_statuses`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_statuses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;



--
-- Table structure for table `surveys_chapters`
--

CREATE TABLE IF NOT EXISTS `surveys_chapters` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `chapter` int(11) NOT NULL,
  `chapter_data` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys_chapters`
 ADD PRIMARY KEY (`id`), ADD KEY `id_survey` (`id_survey`);

ALTER TABLE `surveys_chapters`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



--
-- Table structure for table `surveys_interviews`
--

CREATE TABLE IF NOT EXISTS `surveys_interviews` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys_interviews`
 ADD PRIMARY KEY (`id`), ADD KEY `id_survey` (`id_survey`);

ALTER TABLE `surveys_interviews`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



--
-- Table structure for table `surveys_answers`
--

CREATE TABLE IF NOT EXISTS `surveys_answers` (
  `id` int(11) NOT NULL,
  `id_interview` int(11) NOT NULL,
  `chapter` int(11) NOT NULL,
  `chapter_data` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys_answers`
 ADD PRIMARY KEY (`id`), ADD KEY `id_interview` (`id_interview`);

ALTER TABLE `surveys_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Aggiunto campo colore per chapters e answers
--
ALTER TABLE `surveys_chapters` ADD `color` VARCHAR(7) NOT NULL AFTER `chapter_data`;
ALTER TABLE `surveys_answers` ADD `color` VARCHAR(7) NOT NULL AFTER `chapter_data`;


--
-- Campi group_id e deleted per capitoli e risposte
--
ALTER TABLE `surveys_chapters` 
ADD `group_id` VARCHAR(255) NOT NULL AFTER `color`, 
ADD `deleted` TINYINT NOT NULL AFTER `group_id`, 
ADD INDEX (`group_id`) ;

ALTER TABLE `surveys_answers` 
ADD `group_id` VARCHAR(255) NOT NULL AFTER `color`,
ADD `deleted` TINYINT NOT NULL AFTER `group_id`,
ADD INDEX (`group_id`) ;


--
-- Stato pubblicato congelato per questionari
--
INSERT INTO `surveys_statuses` (`name`, `ordering`, `created`, `modified`) VALUES ('Pubblicato (congelato)', '2', NOW(), NOW());
UPDATE `surveys_statuses` SET `ordering` = '3' WHERE `surveys_statuses`.`id` = 2;
UPDATE `surveys_statuses` SET `ordering` = '4' WHERE `surveys_statuses`.`id` = 3;




--
-- Table structure for table `surveys_aziende_to_structures`
--

CREATE TABLE IF NOT EXISTS `surveys_to_structures` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys_to_structures`
 ADD PRIMARY KEY (`id`), ADD KEY `id_survey` (`id_survey`,`id_azienda`);

ALTER TABLE `surveys_to_structures`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



--
-- Aggiunta campi id azienda e id sede per interviste
--
ALTER TABLE `surveys_interviews` 
ADD `id_azienda` INT NOT NULL AFTER `id_survey`, 
ADD `id_sede` INT NOT NULL AFTER `id_azienda`, 
ADD INDEX (`id_azienda`, `id_sede`) ;



--
-- Aggiunta campi versione e valido da per questionario
--
ALTER TABLE `surveys` 
ADD `version` VARCHAR(64) NOT NULL DEFAULT '1' AFTER `status`, 
ADD `valid_from` DATE NOT NULL AFTER `version`;



--
-- Chiave configurazione per percorso base immagini elemento questionari
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES 
(NULL, 'surveys', 'ELEMENT_IMAGE_FILE_BASE_PATH', 'Percorso base immagini elemento questionario', 'Percorso base per le immagini caricate con l''elemento dei questionari', '/var/www/html/IRES_companee/webroot/files/survey_images/', 'text', '900', NOW(), NOW());



--
-- Campo cloned_by per questionari
--
ALTER TABLE `surveys` ADD `cloned_by` INT NOT NULL AFTER `valid_from`;


--
-- Campo status per le interviste
--
ALTER TABLE `surveys_interviews` ADD `status` INT NOT NULL AFTER `description`;



--
-- Table structure for table `surveys_interviews_statuses`
--

CREATE TABLE IF NOT EXISTS `surveys_interviews_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `surveys_interviews_statuses` (`id`, `name`, `ordering`, `created`, `modified`) VALUES
(1, 'In compilazione', 1, '2019-06-28 16:16:29', '2019-06-28 16:16:29'),
(2, 'Firmata', 2, '2019-06-28 16:16:29', '2019-06-28 16:16:29');

ALTER TABLE `surveys_interviews_statuses`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_interviews_statuses`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;


--
-- Campo cloned_by per interviste
--
ALTER TABLE `surveys_interviews` ADD `cloned_by` INT NOT NULL AFTER `status`;


--
-- Rinominato stato "In compilazione" in "Compilazione"
--
UPDATE `surveys_interviews_statuses` SET `name` = 'Compilazione' WHERE `surveys_interviews_statuses`.`id` = 1;


--
-- Campo per domande si no questionario
--
ALTER TABLE `surveys` ADD `yes_no_questions` TEXT NOT NULL AFTER `status`;


--
-- Data firma per ispezioni
--
ALTER TABLE `surveys_interviews` ADD `signature_date` DATE NOT NULL AFTER `status`;


--
-- Campo non valido per ispezioni
--
ALTER TABLE `surveys_interviews` ADD `not_valid` TINYINT(1) NOT NULL DEFAULT '0' AFTER `status`;


--
-- Table structure for table `surveys_chapters_contents`
--

CREATE TABLE IF NOT EXISTS `surveys_chapters_contents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `ordering` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `surveys_chapters_contents`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_chapters_contents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Campo chapter_data come mediumtext
--
ALTER TABLE `surveys_chapters` CHANGE `chapter_data` `chapter_data` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `surveys_answers` CHANGE `chapter_data` `chapter_data` MEDIUMTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;


--
-- Campo not-valid per interviews
--
ALTER TABLE `surveys_interviews` ADD `not_valid` TINYINT(1) NOT NULL AFTER `status`;