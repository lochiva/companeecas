--
-- Aggiunto campo ordering ai contatti
--
ALTER TABLE `contatti` ADD `ordering` INT NOT NULL AFTER `notify_privacy`;

--
-- Aggiunto campo ordering alle sedi
--
ALTER TABLE `sedi` ADD `ordering` INT NOT NULL AFTER `skype`;
