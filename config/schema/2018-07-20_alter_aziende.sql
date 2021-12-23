--
-- Aggiunta campo id_fattureincloud
--
ALTER TABLE `aziende` ADD `id_cliente_fattureincloud` INT NOT NULL AFTER `interno`;
ALTER TABLE `aziende` ADD `id_fornitore_fattureincloud` INT NOT NULL AFTER `id_cliente_fattureincloud`;

--
-- Aggiunta campo pa_codice
--
ALTER TABLE `aziende` ADD `pa_codice` VARCHAR(32) NOT NULL DEFAULT '' AFTER `id_fornitore_fattureincloud`;
