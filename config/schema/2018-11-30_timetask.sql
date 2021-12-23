--
-- Campi id_timetask, number_timetask, client_timetask, project_timetask in calendar_events
--

ALTER TABLE `calendar_events` 
ADD `id_timetask` INT(11) NOT NULL AFTER `vobject`,
ADD `number_timetask` VARCHAR(32) NOT NULL AFTER `id_timetask`,
ADD `client_timetask` VARCHAR(255) NOT NULL AFTER `number_timetask`,
ADD `project_timetask` VARCHAR(255) NOT NULL AFTER `client_timetask`;


--
-- Cambiato tipologia campo note evento da varchar 255 a text
--

ALTER TABLE `calendar_events` CHANGE `note` `note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


--
-- configurazioni per l'abilitazione della connessione a timetask e della webapp
--

INSERT INTO 
`ITO_companee`.`configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES 
(NULL, 'calendar', 'TIMETASK_CONNECTION', 'Abilitazione connessione a Timetask', 'Abilita la i servizi per la connessione a Timetask (sincronizzazione task, invio del tempo, recupero anagrafica, ecc.)', '1', 'checkbox', '900', NOW(), NOW()), 
(NULL, 'calendar', 'CALENDAR_APP_ACTIVATED', 'Abilitazione web app per il calendario', 'Abilita la web app per il calendario', '1', 'checkbox', '900', NOW(), NOW());
