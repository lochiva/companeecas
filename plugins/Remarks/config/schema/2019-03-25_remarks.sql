--
-- Table structure for table `remarks`
--

CREATE TABLE IF NOT EXISTS `remarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `remark` text NOT NULL,
  `rating` int(1) NOT NULL,
  `visibility` int(1) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `deleted` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `remarks`
 ADD PRIMARY KEY (`id`), ADD KEY `reference` (`reference`,`reference_id`), ADD KEY `user_id` (`user_id`);

ALTER TABLE `remarks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Path upload allegati in configurations
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES (NULL, 'remarks', 'REMARKS_UPLOAD_PATH', 'upload path', 'la cartella relativa alla document root, con  lo / finale , ad esempio files/', 'webroot/attachments/', 'text', '', NOW(), NOW());


--
-- Path upload file tinymce in configurations
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) 
VALUES (NULL, 'remarks', 'REMARKS_UPLOAD_PATH_TINYMCE', 'upload path', 'la cartella relativa alla webroot, con  lo / finale , ad esempio files/', 'files/', 'text', '', NOW(), NOW());
