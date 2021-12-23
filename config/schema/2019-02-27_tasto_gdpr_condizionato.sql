--
-- Config per donizionare la visibilità del tasto di verifica dei dati gdpr
--
INSERT INTO `configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES ('gdpr', 'ENABLE_BTN_VERIFY_DATA', 'Abilita il tasto di verifica dei dati', 'Abilita il tasto di verifica dei dati', '0', 'checkbox', '900', NOW(), NOW());