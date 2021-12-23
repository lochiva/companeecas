--
-- Chiave di configurazione PASSWORD_RECOVERY_FRONTEND
--

INSERT INTO `ITO_companee`.`configurations` (`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`)
VALUES ('registration', 'PASSWORD_RECOVERY_FRONTEND', 'Abilita il recupero password dal front end', 'Abilita il recupero password dal front end', '1', 'checkbox', '900', NOW(), NOW());
