CREATE TABLE IF NOT EXISTS `calendar_events` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `allDay` int(1) NOT NULL,
  `backgroundColor` varchar(10) NOT NULL,
  `borderColor` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;