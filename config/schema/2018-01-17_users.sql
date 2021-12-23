--
-- Aggiunto campo deleted
--
ALTER TABLE `users` ADD `deleted` TINYINT(1) NULL DEFAULT '0' AFTER `cf`;
