--
-- Aggiunti campi chiusura segnalazione
--
ALTER TABLE `reports` 
ADD `opening_date` DATE NOT NULL AFTER `status`, 
ADD `closing_date` DATE NULL AFTER `opening_date`, 
ADD `closing_outcome_id` INT NULL AFTER `closing_date`;

UPDATE `reports` SET `opening_date` = `created`;


--
-- Struttura della tabella `reports_history`
--

CREATE TABLE `reports_history` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `event` varchar(16) NOT NULL,
  `motivation` text NULL,
  `outcome_id` int(11) NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `reports_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `node_id` (`node_id`);

ALTER TABLE `reports_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


--
-- Struttura della tabella `reports_closing_outcomes`
--

CREATE TABLE `reports_closing_outcomes` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `reports_closing_outcomes` (`id`, `name`, `ordering`, `created`, `modified`) VALUES
(1, 'Positivo', 10, '2021-05-05 13:06:30', '2021-05-05 13:06:30'),
(2, 'Negativo', 20, '2021-05-05 13:06:30', '2021-05-05 13:06:30'),
(3, 'Non definito', 30, '2021-05-05 13:06:30', '2021-05-05 13:06:30');

ALTER TABLE `reports_closing_outcomes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_closing_outcomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


--
-- Data trasferimento segnalazione
--
ALTER TABLE `reports` ADD `transfer_date` DATE NULL AFTER `closing_outcome_id`;


ALTER TABLE `surveys_interviews` CHANGE `signature_date` `signature_date` DATE NULL;


--
-- Rimosso unicità combinazione codice e codice provincia
--
ALTER TABLE `reports` DROP INDEX `code_province_code`;

--
-- Modificato lunghezza campo evento storico
--
ALTER TABLE `reports_history` CHANGE `event` `event` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;