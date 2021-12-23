--
-- CREAZIONE TABELLA calendar_events_detail_activities
--

CREATE TABLE `calendar_events_detail_activities`(
    `id` INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `id_event_detail` INT NOT NULL,
	`id_activity` INT NOT NULL,
    `note` VARCHAR(256),
    `created` DATETIME,
    `modified` DATETIME
);
