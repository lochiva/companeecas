# inserire nel DB sapendo che va tolta la X iniziale dall'api uid ( perchè è quello di lochiva e non va usato a caso)


INSERT INTO `configurations` ( `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES
( 'ficgtw', 'API_UID', 'api_uid', 'l\'id utente per fatture in cloud', 'X146653', 'text', 500, '2018-07-11 12:00:01', '2018-07-12 11:30:41'),
( 'ficgtw', 'API_KEY', 'api_key', 'la chiave per accedere al fatture in cloud', '7b3e93aa37f5e519c51b0c1949e23c5a', 'text', 500, '2018-07-11 12:00:01', '2018-07-12 11:31:03');
