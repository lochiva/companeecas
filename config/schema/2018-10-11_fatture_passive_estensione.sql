--
-- Aggiunta chiave config FATTUREINCLOUD_METHODS
--
INSERT INTO `configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`)
VALUES ('aziende', 'FATTUREINCLOUD_METHODS', 'Conti fattureincloud', 'Conti fattureincloud', '{ "lista_conti": [ { "id": 127875, "nome_conto": "sella lochiva" }, { "id": 138943, "nome_conto": "altro di esempio" } ], "success": true }', 'json', '500', NOW(), NOW());

--
-- Aggiunta campi ritenuta_acconto e metodo per tabella invoices
--
ALTER TABLE `invoices`
ADD `ritenuta_acconto` DECIMAL(10,2) NOT NULL AFTER `amount`,
ADD `metodo` VARCHAR(64) NOT NULL AFTER `ritenuta_acconto`;


--
-- Aggiunta campo id_fattureincloud
--
ALTER TABLE `invoices` ADD `id_fattureincloud` INT NOT NULL AFTER `attachment`;
