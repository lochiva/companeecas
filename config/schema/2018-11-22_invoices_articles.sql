--
-- Table structure for table `invoices_articles`
--

CREATE TABLE IF NOT EXISTS `invoices_articles` (
  `id` int(11) NOT NULL,
  `id_invoice` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount_noiva` decimal(10,2) NOT NULL,
  `amount_iva` decimal(10,2) NOT NULL,
  `amount_tot` decimal(10,2) NOT NULL,
  `cod_iva` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `id_purpose` int(11) NOT NULL,
  `deleted` TINYINT(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `invoices_articles`
--
ALTER TABLE `invoices_articles`
 ADD PRIMARY KEY (`id`), ADD KEY `id_invoice` (`id_invoice`);

--
-- AUTO_INCREMENT for table `invoices_articles`
--
ALTER TABLE `invoices_articles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Aggiunto campo quantità
--
ALTER TABLE `invoices_articles` ADD `quantity` DECIMAL(10,2) NOT NULL AFTER `cod_iva`;