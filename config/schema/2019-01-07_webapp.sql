--
-- Table structure for table `calendar_events_detail`
--

CREATE TABLE IF NOT EXISTS `calendar_events_detail` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `user_start` datetime DEFAULT NULL,
  `user_end` datetime DEFAULT NULL,
  `real_start` datetime DEFAULT NULL,
  `real_end` datetime DEFAULT NULL,
  `start_lat` varchar(16) NOT NULL,
  `stop_lat` varchar(16) NOT NULL,
  `start_long` varchar(16) NOT NULL,
  `stop_long` varchar(16) NOT NULL,
  `signature` text NOT NULL,
  `note` text,
  `note_importanza` tinyint(1) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_events_detail`
--
ALTER TABLE `calendar_events_detail`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_events_detail`
--
ALTER TABLE `calendar_events_detail`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Aggiunto campo status all'evento
--

ALTER TABLE `calendar_events` ADD `status` VARCHAR(16) NOT NULL DEFAULT 'TODO' AFTER `project_timetask`;


--
-- Aggiunto campo anagrafica_timetask all'user
--
ALTER TABLE `users` ADD `anagrafica_timetask` TEXT NOT NULL AFTER `cf`;


--
-- Aggiunto campo id_time_timetask all'evento
--
ALTER TABLE `calendar_events` ADD `id_time_timetask` INT NOT NULL AFTER `status`;


--
-- Campo timetask_token all'user
--
ALTER TABLE `users` ADD `timetask_token` VARCHAR(255) NOT NULL AFTER `anagrafica_timetask`;