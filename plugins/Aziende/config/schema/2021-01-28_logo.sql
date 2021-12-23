--
-- Logo nodo
--
ALTER TABLE `aziende` ADD `logo` VARCHAR(255) NULL AFTER `email_info`;

INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES 
(NULL, 'aziende', 'LOGO_PATH', 'logo upload path', 'la cartella relativa alla document root, con lo / finale , ad esempio files/', 'plugins/Aziende/webroot/images/', 'text', '900', NOW(), NOW());