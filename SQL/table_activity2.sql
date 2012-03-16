#----------------------------------------
CREATE TABLE activity (
    id              int UNIQUE,
    name            varchar(64),
    questions       varchar(256),
		term						varchar(32),
    PRIMARY KEY (id)
);
#----------------------------------------
# FOREIGN KEY (term) REFERENCES class (term) ON DELETE CASCADE

INSERT INTO activity VALUES(10,"lab01","1000,1097","Fall_2009");
INSERT INTO activity VALUES(11,"lab02","1190,1195","Fall_2009");
INSERT INTO activity VALUES(12,"lab03","1196,1197","Fall_2009");
INSERT INTO activity VALUES(13,"lab04","1199,1200","Fall_2009");
INSERT INTO activity VALUES(14,"lab05","1201,1202","Fall_2009");
