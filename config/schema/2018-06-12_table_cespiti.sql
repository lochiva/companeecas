--
-- Creazione tabelle cespiti
--

--
-- Table structure for table `cespiti`
--
CREATE TABLE IF NOT EXISTS `cespiti` (
  `id` int(11) NOT NULL,
  `id_azienda` int(11) NOT NULL,
  `id_fattura_passiva` int(11) NOT NULL,
  `num` varchar(16) NOT NULL,
  `descrizione` varchar(64) NOT NULL,
  `stato` tinyint(1) NOT NULL COMMENT '0:attivo, 1:dismesso',
  `note` varchar(255) NOT NULL,
  `delete` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `cespiti`
--
ALTER TABLE `cespiti`
 ADD PRIMARY KEY (`id`), ADD KEY (`id_azienda`), ADD KEY (`id_fattura_passiva`);

--
-- AUTO_INCREMENT for table `cespiti`
--
ALTER TABLE `cespiti`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Cambio nome campo delete in cancellato
--

ALTER TABLE `cespiti` CHANGE COLUMN `delete` `cancellato` tinyint(1) NOT NULL;

--
-- Aggiunta di indici
--
ALTER TABLE `cespiti` ADD INDEX(`stato`);
ALTER TABLE `cespiti` ADD INDEX(`cancellato`);
