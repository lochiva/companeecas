--
-- CREAZIONE TABELLA progest_activities
--

CREATE TABLE `progest_activities`(
    `id` INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `id_service` INT NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `hasNote` TINYINT(1) NOT NULL DEFAULT 0,
    `order_value` INT,
    `created` DATETIME,
    `modified` DATETIME
);

--
-- Attività per servizio OSS
--
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Igiene personale', 0, 10, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Bagno', 0, 20, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, '1° cambio pannolone', 0, 30, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, '2° cambio pannolone', 0, 40, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Igiene ambientale', 0, 50, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Rifacimento letto', 0, 60, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Preparazione pasti', 0, 70, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Alzata dal letto', 0, 80, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Messa a letto', 0, 90, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Deambulazione', 0, 100, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Compagnia', 0, 110, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Spesa', 0, 120, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Axx. visite mediche', 0, 130, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'A. cimitero', 0, 140, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Uscite', 0, 150, NOW(), NOW());
INSERT INTO `progest_activities` (`id_service`, `name`, `hasNote`, `order_value`, `created`, `modified`)
VALUES (1, 'Altro', 1, 160, NOW(), NOW());
