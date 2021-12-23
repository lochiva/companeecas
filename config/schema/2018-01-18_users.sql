--
-- Aggiunto campo deleted
--
ALTER TABLE `users` ADD `active` TINYINT(1) NULL DEFAULT '1' AFTER `cf`;
