--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `victim_id` int(11) NULL,
  `witness_id` int(11) NULL,
  `node_id` int(11) NULL,
  `user_create_id` int(11) NOT NULL,
  `user_update_id` int(11) NOT NULL,
  `status` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `victim_id` (`victim_id`),
  ADD KEY `witness_id` (`witness_id`),
  ADD KEY `node_id` (`node_id`),
  ADD KEY `user_create_id` (`user_create_id`),
  ADD KEY `status` (`status`),
  ADD KEY `user_update_id` (`user_update_id`);

ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `reports_victims`
--

CREATE TABLE `reports_victims` (
  `id` int(11) NOT NULL,
  `user_update_id` int(11) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `gender` varchar(32) NOT NULL,
  `country_id` int(11) NULL,
  `birth_year` int(11) NULL,
  `citizenship` varchar(32) NULL,
  `educational_qualification` varchar(64) NULL,
  `religion` varchar(64) NULL,
  `type_occupation` varchar(64) NULL,
  `marital_status` varchar(64) NULL,
  `in_italy_from_year` int(11) NULL,
  `residency_permit` varchar(32) NULL,
  `lives_with` varchar(255) NULL,
  `telephone` varchar(32) NULL,
  `mobile` varchar(32) NULL,
  `email` varchar(64) NULL,
  `city_id` int(11) NULL,
  `province_id` int(11) NULL,
  `region_id` int(11) NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `reports_victims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_update_id` (`user_update_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `region_id` (`region_id`);

ALTER TABLE `reports_victims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `reports_witnesses`
--

CREATE TABLE `reports_witnesses` (
  `id` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `user_update_id` int(11) NOT NULL,
  `firstname` varchar(64) NULL,
  `lastname` varchar(64) NULL,
  `gender` varchar(32) NULL,
  `country_id` int(11) NULL,
  `birth_year` int(11) NULL,
  `citizenship` varchar(32) NULL,
  `educational_qualification` varchar(64) NULL,
  `religion` varchar(64) NULL,
  `type_occupation` varchar(64) NULL,
  `marital_status` varchar(64) NULL,
  `in_italy_from_year` int(11) NULL,
  `residency_permit` varchar(32) NULL,
  `lives_with` varchar(255) NULL,
  `telephone` varchar(32) NULL,
  `mobile` varchar(32) NULL,
  `email` varchar(64) NULL,
  `city_id` int(11) NULL,
  `province_id` int(11) NULL,
  `region_id` int(11) NULL,
  `business_name` varchar(255) NULL, 
  `address` varchar(255) NULL, 
  `piva` varchar(32) NULL, 
  `legal_representative` varchar(255) NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `reports_witnesses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_update_id` (`user_update_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `region_id` (`region_id`);

ALTER TABLE `reports_witnesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Aggiunto id interview a report
-- 
ALTER TABLE `reports` ADD `interview_id` INT NULL AFTER `witness_id`, ADD INDEX (`interview_id`);


--
-- Codice per segnalazione
--
ALTER TABLE `reports` ADD `code` INT(5) UNSIGNED ZEROFILL NOT NULL AFTER `id`, ADD UNIQUE (`code`);