--
-- Aggiunto campo pec atti commissione per enti
--
ALTER TABLE `aziende` ADD `pec_commissione` VARCHAR(255) NOT NULL AFTER `pec`;

--
-- Aggiunto campo capienza per strutture
--
ALTER TABLE `sedi` ADD `n_posti` INT NOT NULL DEFAULT '0' AFTER `skype`;



--
-- Struttura della tabella `sedi_tipologie_ospiti`
--

CREATE TABLE `sedi_tipologie_ospiti` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ordering` int NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `sedi_tipologie_ospiti` (`id`, `name`, `ordering`, `deleted`, `created`, `modified`) VALUES
(1, 'Donne', 10, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(2, 'Donne e minori', 20, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(3, 'Nuclei familiari', 30, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(4, 'Nuclei familiari e donne', 40, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(5, 'Nuclei familiari e minori', 50, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(6, 'Nuclei familiari e uomini', 60, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(7, 'Uomini', 70, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18'),
(8, 'Uomini e donne', 80, 0, '2022-02-04 14:14:18', '2022-02-04 14:14:18');

ALTER TABLE `sedi_tipologie_ospiti`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sedi_tipologie_ospiti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;



--
-- Struttura della tabella `sedi_sedi_to_tipologia_ospite`
--

CREATE TABLE `sedi_sedi_to_tipologie_ospiti` (
  `id` int NOT NULL,
  `sede_id` int NOT NULL,
  `tipologia_ospite_id` int NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

ALTER TABLE `sedi_sedi_to_tipologie_ospiti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sede_id` (`sede_id`),
  ADD KEY `tipologia_ospite_id` (`tipologia_ospite_id`);

ALTER TABLE `sedi_sedi_to_tipologie_ospiti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;
