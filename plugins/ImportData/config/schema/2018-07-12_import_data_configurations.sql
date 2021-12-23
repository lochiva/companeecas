--
-- Creazione tabelle import_data_configurations per salvataggio configurazioni import data
--

--
-- Table structure for table `import_data_configurations`
--

CREATE TABLE IF NOT EXISTS `import_data_configurations` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `fields` text NOT NULL,
  `functions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `import_data_configurations`
--
ALTER TABLE `import_data_configurations`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `import_data_configurations`
--
ALTER TABLE `import_data_configurations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Aggiunta campo required
--
ALTER TABLE `import_data_configurations`
ADD `required` TEXT NOT NULL AFTER `table_name`;
