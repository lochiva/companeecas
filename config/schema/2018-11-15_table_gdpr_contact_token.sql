--
-- Creazione tabella remarks
--

CREATE TABLE IF NOT EXISTS `gdpr_contact_token` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `used` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `gdpr_contact_token`
--
ALTER TABLE `gdpr_contact_token`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `gdpr_contact_token`
--
ALTER TABLE `gdpr_contact_token`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;