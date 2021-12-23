--
-- Table structure for table `segnalazioni_documents`
--

CREATE TABLE `reports_documents` (
  `id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `reports_documents`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reports_documents` ADD `report_id` INT NOT NULL AFTER `id`, ADD INDEX (`report_id`);

--
-- Config upload path documenti
--
INSERT INTO `configurations` (`id`, `plugin`, `key_conf`, `label`, `tooltip`, `value`, `value_type`, `level`, `created`, `modified`) VALUES 
(NULL, 'reports', 'DOCUMENTS_UPLOAD_PATH', 'upload path documenti', 'la cartella relativa alla document root, con lo / finale , ad esempio files/', 'files/reports/documents/', 'text', '900', NOW(), NOW());
