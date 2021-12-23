INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES ('0', 'aziende', 'ROOT_ATTIVE', 'il nodo padre della causali attive', 'il nodo padre della causali attive', '1000', '', '500', '2018-11-21 16:30:31', '2018-11-21 16:30:31');



INSERT INTO .`configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES ('0', 'aziende', 'ROOT_PASSIVE', 'il nodo padre della causali attive', 'il nodo padre della causali attive', '0', '', '500', '2018-11-21 16:30:31', '2018-11-21 16:30:31');

INSERT INTO `invoices_purposes` (`id`, `parent_id`, `name`, `note`, `color`, `created`, `modified`) VALUES (NULL, '1000', 'causale standard', '', '', NULL, NULL);
INSERT INTO `invoices_purposes` (`id`, `parent_id`, `name`, `note`, `color`, `created`, `modified`) select NULL, id , "causale standard", "", "", "", "" from invoices_purposes where parent_id = 1000 limit 1
