--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL,
  `context` varchar(255) NOT NULL,
  `id_item` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_size` float NOT NULL,
  `upload_date` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `attachments`
 ADD PRIMARY KEY (`id`), ADD KEY `id_item` (`id_item`);

ALTER TABLE `attachments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;