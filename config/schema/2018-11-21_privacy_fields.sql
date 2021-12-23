--
-- Aggiunta campi per la privacy a contatti
--
ALTER TABLE `contatti` 
ADD `read_privacy` INT(1) NOT NULL AFTER `cf`, 
ADD `accepted_privacy` INT(1) NOT NULL AFTER `read_privacy`, 
ADD `marketing_privacy` INT(1) NOT NULL AFTER `accepted_privacy`, 
ADD `third_party_privacy` INT(1) NOT NULL AFTER `marketing_privacy`, 
ADD `profiling_privacy` INT(1) NOT NULL AFTER `third_party_privacy`, 
ADD `spread_privacy` INT(1) NOT NULL AFTER `profiling_privacy`, 
ADD `notify_privacy` INT(1) NOT NULL AFTER `spread_privacy`;