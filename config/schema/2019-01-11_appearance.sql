--
-- Table structure for table `appearance_backgrounds`
--

CREATE TABLE IF NOT EXISTS `appearance_backgrounds` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appearance_backgrounds`
--
ALTER TABLE `appearance_backgrounds`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appearance_backgrounds`
--
ALTER TABLE `appearance_backgrounds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;