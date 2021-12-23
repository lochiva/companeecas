CREATE TABLE calendar_events_detail (
	id int auto_increment,
	event_id int not null,
	user_id int not null,
	user_start datetime,
	user_end datetime,
	real_start datetime,
	real_end datetime,
	created datetime,
	modified datetime,
	PRIMARY KEY(id)
);

ALTER TABLE calendar_events
ADD status varchar(50) not null DEFAULT 'TODO' AFTER `end`;

ALTER TABLE calendar_events_detail
ADD start_lat varchar(16) AFTER real_end,
ADD stop_lat varchar(16) AFTER start_lat,
ADD start_long varchar(16) AFTER stop_lat,
ADD stop_long varchar(16) AFTER start_long;
