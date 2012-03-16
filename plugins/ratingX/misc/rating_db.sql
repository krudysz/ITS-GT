CREATE DATABASE 'test';

CREATE TABLE `test`.`test` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`total_rates` INT NOT NULL ,
	`initial_rating` DECIMAL NOT NULL ,
	`final_rating` DECIMAL NOT NULL ,
	`last_modified` TIMESTAMP NOT NULL
) ENGINE = MYISAM ;