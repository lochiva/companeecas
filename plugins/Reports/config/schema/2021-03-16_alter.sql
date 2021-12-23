--
-- Tipologia segnalante anagrafica vittima
--
ALTER TABLE `reports_witnesses` ADD `type_reporter` VARCHAR(16) NOT NULL AFTER `id`;
