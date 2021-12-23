INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES 
(NULL, 'registration', 'GOOGLE_OAUTH_CLIENT_ID', 'Google Client ID', 'Id dell''applicazione su google per l''autenticazione', '713787601734-nsdpv93lku7js2fkt9srkj5ona0qkbem.apps.googleusercontent.com', 'string', '0', '', ''), 
(NULL, 'registration', 'GOOGLE_OAUTH_CLIENT_SECRET', 'Google Client secret', 'Chiave segreta applicazione google per autenticazione', 'string', 'text', '0', '', '')

INSERT INTO `configurations`
(`plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES
('registration', 'GOOGLE_OAUTH_ENABLE', 'Google Login abilitato', 'Indica se è abilitato il login con google', '1', 'number', '0', '2018-11-06 10:00:00', '2018-11-06 10:00:00');