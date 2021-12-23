--
-- Creazione tabelle log_file_upload per logging caricamenti file
--

--
-- Table structure for table `log_file_upload`
--

CREATE TABLE IF NOT EXISTS `log_file_upload` (
`id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `table` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_file_upload`
--
ALTER TABLE `log_file_upload`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_file_upload`
--
ALTER TABLE `log_file_upload`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Cambio nome campo table in table_name perchè table parola riservata di mySQL
--
ALTER TABLE `log_file_upload`
CHANGE `table` `table_name` varchar(255) NOT NULL;
