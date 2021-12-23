--
-- Sender per verifica email
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES 
(NULL, 'tooltility', 'VERIFY_EMAIL_SENDER', 'sender per la verifica dell''email', 'sender per la verifica dell''email', 'info@itoa.it', 'text', '900', NOW(), NOW());

--
-- IP autorizzati
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES 
(NULL, 'tooltility', 'AUTHORIZED_IPS', 'lista ip autorizzati', 'lista ip autorizzati (separati da virgola)', '172.18.0.1', 'text', '900', NOW(), NOW());