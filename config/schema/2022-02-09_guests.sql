--
-- Struttura della tabella `guests`
--

CREATE TABLE `guests` (
  `id` int NOT NULL,
  `sede_id` int NOT NULL,
  `cui` varchar(7) NOT NULL,
  `vestanet_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `country_birth` int NOT NULL,
  `sex` varchar(1) NOT NULL,
  `minor` tinyint(1) NOT NULL,
  `suspended` tinyint(1) NOT NULL,
  `draft` tinyint(1) NOT NULL,
  `draft_expiration` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sede_id` (`sede_id`),
  ADD KEY `country_birth` (`country_birth`),
  ADD KEY `cui` (`cui`),
  ADD KEY `vestanet_id` (`vestanet_id`);

ALTER TABLE `guests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;
