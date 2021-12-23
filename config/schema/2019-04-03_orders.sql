--
-- Aggiunto campo selectable allo stato degli ordini
--
ALTER TABLE `orders_status` ADD `selectable` TINYINT(1) NOT NULL DEFAULT '1' AFTER `color`;