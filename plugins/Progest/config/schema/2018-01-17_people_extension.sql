--
-- Aggiunti campi per latitudine e longitudine indirizzo
--
ALTER TABLE `progest_people_extension`
ADD `address_lat` VARCHAR(16) NULL AFTER `address`,
ADD `address_long` VARCHAR(16) NULL AFTER `address_lat`;
