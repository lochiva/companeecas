ALTER TABLE `reports` ADD `type_reporter` VARCHAR(16) NOT NULL AFTER `province_code`;
-- update data before removing field
Update reports r Left join reports_witnesses w on r.witness_id= w.id  set r.type_reporter = w.type_reporter
ALTER TABLE `reports_witnesses` DROP `type_reporter`;