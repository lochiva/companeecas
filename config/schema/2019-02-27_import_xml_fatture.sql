--
-- Campo per salvataggio path file xml in fatture
--
ALTER TABLE `invoices` ADD `xml` VARCHAR(255) NOT NULL AFTER `attachment`;