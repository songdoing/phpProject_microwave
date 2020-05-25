drop database if exists microwave_paths;
create database microwave_paths charset utf8;

drop user if exists microwave@localhost;
create user if not exists microwave@localhost identified by '!Microwave2!';
 grant all privileges on microwave_paths.* to microwave@localhost;

use microwave_paths;

CREATE TABLE `path` (
  `path_id` int(11) NOT NULL AUTO_INCREMENT,
  `path_name` varchar(100) NOT NULL,
  `path_frequency` double(8,4) NOT NULL,
  `path_description` varchar(255) NOT NULL,
  `path_file_name` varchar(100) NOT NULL,
  `path_note` text,
  PRIMARY KEY (`path_id`),
  UNIQUE KEY `path_name` (`path_name`)
); 

CREATE TABLE `path_end` (
  `end_id` int(11) NOT NULL AUTO_INCREMENT,
  `end_path_id` int(11) NOT NULL,
  `end_distance` double(8,4) NOT NULL,
  `end_ground_height` double(10,4) NOT NULL,
  `end_antenna_height` double(8,4) NOT NULL,
  `end_ant_cable_type` varchar(25) NOT NULL,
  `end_ant_cable_length` double(8,4) NOT NULL,
  PRIMARY KEY (`end_id`),
  UNIQUE KEY `path` (`end_path_id`,`end_distance`)
);

CREATE TABLE `path_mid` (
  `mid_id` int(11) NOT NULL AUTO_INCREMENT,
  `mid_path_id` int(11) NOT NULL,
  `mid_distance` double(8,4) NOT NULL,
  `mid_ground_height` double(10,4) NOT NULL,
  `mid_terrain_type` varchar(50) NOT NULL,
  `mid_obstruction_height` double(8,4) NOT NULL,
  `mid_obstruction_type` varchar(50) NOT NULL,
  PRIMARY KEY (`mid_id`),
  UNIQUE KEY `path` (`mid_path_id`,`mid_distance`)
);

alter table path_end add constraint fk1 foreign key (end_path_id) references path (path_id);
alter table path_mid add constraint fk2 foreign key (mid_path_id) references path (path_id);

