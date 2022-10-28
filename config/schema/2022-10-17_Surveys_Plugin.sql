DROP TABLE IF EXISTS `surveys`;
DROP TABLE IF EXISTS `surveys_answers`;
DROP TABLE IF EXISTS `surveys_answer_data`;
DROP TABLE IF EXISTS `surveys_chapters`;
DROP TABLE IF EXISTS `surveys_chapters_contents`;
DROP TABLE IF EXISTS `surveys_chapters_nuovo_a_mano`;
DROP TABLE IF EXISTS `surveys_interviews`;
DROP TABLE IF EXISTS `surveys_interviews_statuses`;
DROP TABLE IF EXISTS `surveys_question_metadata`;
DROP TABLE IF EXISTS `surveys_statuses`;
DROP TABLE IF EXISTS `surveys_to_structures`;
DROP TABLE IF EXISTS `surveys_placeholders`;




CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `id_configurator` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` int(11) NOT NULL,
  `yes_no_questions` mediumtext NOT NULL,
  `version` varchar(64) NOT NULL DEFAULT '1',
  `valid_from` date NOT NULL,
  `cloned_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `surveys_answers` (
  `id` int(11) NOT NULL,
  `id_interview` int(11) NOT NULL,
  `chapter` int(11) NOT NULL,
  `chapter_data` longtext NOT NULL,
  `color` varchar(7) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_interview` (`id_interview`),
  ADD KEY `group_id` (`group_id`);

ALTER TABLE `surveys_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


  CREATE TABLE `surveys_answer_data` (
  `id` int(11) NOT NULL,
  `interview_id` int(11) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `value` text NOT NULL,
  `options` text NOT NULL,
  `type` varchar(64) NOT NULL,
  `final_value` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys_answer_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interview_id` (`interview_id`),
  ADD KEY `question_id` (`question_id`);

ALTER TABLE `surveys_answer_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `surveys_chapters` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `chapter` int(11) NOT NULL,
  `chapter_data` longtext NOT NULL,
  `color` varchar(7) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);


ALTER TABLE `surveys_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_survey` (`id_survey`),
  ADD KEY `group_id` (`group_id`);


ALTER TABLE `surveys_chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `surveys_chapters_contents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys_chapters_contents`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_chapters_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `surveys_interviews` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `id_quotation` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` int(11) NOT NULL,
  `not_valid` tinyint(1) NOT NULL,
  `signature_date` date NOT NULL,
  `cloned_by` int(11) NOT NULL,
  `version` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);


ALTER TABLE `surveys_interviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_survey` (`id_survey`),
  ADD KEY `id_quotation` (`id_quotation`);


ALTER TABLE `surveys_interviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `surveys_interviews_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);
ALTER TABLE `surveys_interviews_statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_interviews_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  CREATE TABLE `surveys_placeholders` (
  `id` int(11) NOT NULL,
  `label` varchar(36) NOT NULL,
  `description` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys_placeholders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_placeholders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


  CREATE TABLE `surveys_statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `ordering` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);

ALTER TABLE `surveys_statuses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `surveys_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `surveys_question_metadata` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `show_in_table` tinyint(1) NOT NULL DEFAULT '0',
  `show_in_export` tinyint(1) NOT NULL DEFAULT '0',
  `label_table` varchar(64) NOT NULL,
  `label_export` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
);
--
ALTER TABLE `surveys_question_metadata`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_id` (`survey_id`),
  ADD KEY `question_id` (`question_id`);

ALTER TABLE `surveys_question_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
