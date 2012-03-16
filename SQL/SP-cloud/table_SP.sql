#delete from SP;
drop table if exists SP;
#----------------------------------------
CREATE TABLE SP (
    id              int UNIQUE auto_increment,
		name						varchar(256),
    PRIMARY KEY (id)
);
#----------------------------------------

#delete from SP_keywords;
drop table if exists SP_keywords;
#----------------------------------------
CREATE TABLE SP_keywords (
    sp_id             int UNIQUE,
		keywords					varchar(255),
    PRIMARY KEY (sp_id)
);
#----------------------------------------

INSERT INTO SP VALUES(1,"value 1");
INSERT INTO SP VALUES(2,"value 2");

INSERT INTO SP_keywords VALUES(1,"key1 key10 key100");
INSERT INTO SP_keywords VALUES(2,"key2 key20 key200 key10");