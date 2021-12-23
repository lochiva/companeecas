-- --------------------------------------------------------

--
-- Struttura della tabella `trading_errevicodes`
--

CREATE TABLE IF NOT EXISTS `trading_errevicodes` (
`id` int(11) NOT NULL,
  `errevicode` varchar(6) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `trading_errevicodes`
--
ALTER TABLE `trading_errevicodes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `errevicode` (`errevicode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `trading_errevicodes`
--
ALTER TABLE `trading_errevicodes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;