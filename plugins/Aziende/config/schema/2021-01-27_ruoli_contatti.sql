--
-- Aggiunti nuovi ruoli contatti
--
INSERT INTO `contatti_ruoli` (`id`, `ruolo`, `color`, `ordering`, `created`) VALUES 
(NULL, 'Dirigente', '#21c900', '1', NOW()), 
(NULL, 'Responsabile', '#3d46ff', '2', NOW()), 
(NULL, 'Operatrice/operatore', '#8a00ed', '3', NOW());
