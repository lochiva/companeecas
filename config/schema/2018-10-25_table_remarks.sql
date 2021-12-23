--
-- Creazione tabella remarks
--

CREATE TABLE IF NOT EXISTS `remarks` (
  `id` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `remark` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
ADD PRIMARY KEY (`id`), ADD KEY `reference` (`reference`,`reference_id`);

--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Aggiunta campo user_id
--

ALTER TABLE `remarks` ADD `user_id` INT NOT NULL AFTER `id`, ADD INDEX (`user_id`);

--
-- Aggiunta campo deleted
--

ALTER TABLE `remarks` ADD `deleted` INT(1) NOT NULL AFTER `remark`;

--
-- Aggiunto campo rating
--

ALTER TABLE `remarks` ADD `rating` INT(1) NOT NULL AFTER `remark`;

--
-- Aggiunto campo visibility
--

ALTER TABLE `remarks` ADD `visibility` INT(1) NOT NULL AFTER `rating`;

--
-- Aggiunto campo attachment
--

ALTER TABLE `remarks` ADD `attachment` VARCHAR(255) NOT NULL AFTER `visibility`;
