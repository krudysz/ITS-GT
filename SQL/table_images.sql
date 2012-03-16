#delete from images;
drop table if exists images;
#----------------------------------------
CREATE TABLE images (
		id          int(11)      NOT NULL AUTO_INCREMENT,
		name		varchar(127) NOT NULL,
		dir			varchar(63)  DEFAULT NULL,  
		tag			varchar(127) DEFAULT NULL,  
	
	PRIMARY KEY (id)
);
#----------------------------------------
CREATE UNIQUE INDEX img ON images (`name`,`dir`);
