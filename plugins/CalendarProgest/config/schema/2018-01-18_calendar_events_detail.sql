--
-- Aggiunti campi note e note_importanza
--
ALTER TABLE `calendar_events_detail`
ADD `note` TEXT NULL  AFTER `signature`,
ADD `note_importanza` TINYINT(1) NULL  AFTER `note`;

--
-- Modificato nome campo user_id in operator_id
--

ALTER TABLE `calendar_events_detail` CHANGE `user_id` `operator_id` INT(11) NOT NULL;
