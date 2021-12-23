--
-- Modifiche campi genere
--
ALTER TABLE `reports_victims` CHANGE `gender` `gender_id` INT NOT NULL;
ALTER TABLE `reports_victims` ADD `gender_user_text` VARCHAR(64) NULL AFTER `gender_id`;

ALTER TABLE `reports_witnesses` CHANGE `gender` `gender_id` INT NULL;
ALTER TABLE `reports_witnesses` ADD `gender_user_text` VARCHAR(64) NULL AFTER `gender_id`;


--
-- Tabella generi
--

CREATE TABLE `reports_genders` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `user_text` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `reports_genders` (`id`, `name`, `ordering`, `user_text`, `created`, `modified`) VALUES
(1, 'Femmina', 10, 0, NOW(), NOW()),
(2, 'Maschio', 20, 0, NOW(), NOW()),
(3, 'Altro', 30, 1, NOW(), NOW());

ALTER TABLE `reports_genders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reports_genders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


--
-- Modifiche tracciato testimone
--
ALTER TABLE `reports_witnesses` 
DROP `address`,
ADD `address_legal` VARCHAR(255) NULL AFTER `piva`, 
ADD `city_id_legal` INT NULL AFTER `address_legal`, 
ADD `province_id_legal` INT NULL AFTER `city_id_legal`, 
ADD `region_id_legal` INT NULL AFTER `province_id_legal`, 
ADD `address_operational` VARCHAR(255) NULL AFTER `region_id_legal`, 
ADD `city_id_operational` INT NULL AFTER `address_operational`, 
ADD `province_id_operational` INT NULL AFTER `city_id_operational`, 
ADD `region_id_operational` INT NULL AFTER `province_id_operational`, 
ADD `telephone_legal` VARCHAR(32) NULL AFTER `legal_representative`, 
ADD `mobile_legal` VARCHAR(32) NULL AFTER `telephone_legal`, 
ADD `email_legal` VARCHAR(64) NULL AFTER `mobile_legal`, 
ADD `operational_contact` VARCHAR(255) NULL AFTER `email_legal`, 
ADD `telephone_operational` VARCHAR(32) NULL AFTER `operational_contact`, 
ADD `mobile_operational` VARCHAR(32) NULL AFTER `telephone_operational`, 
ADD `email_operational` VARCHAR(64) NULL AFTER `mobile_operational`,
ADD INDEX (`city_id_legal`), 
ADD INDEX (`province_id_legal`), 
ADD INDEX (`region_id_legal`), 
ADD INDEX (`city_id_operational`), 
ADD INDEX (`province_id_operational`), 
ADD INDEX (`region_id_operational`);