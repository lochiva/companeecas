--
-- Delete report
--
ALTER TABLE `reports` ADD `deleted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `transfer_date`;