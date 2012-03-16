#----------------------------------------
CREATE TABLE course (
    id 			 				int,
    name       varchar(32),
    term		 	 varchar(32),
    year        	 varchar(32),
		filename		 varchar(64),
		num_students 	 int,
		category_id		 int
);
#----------------------------------------

INSERT INTO course VALUES(1,"admin","admin",NULL,"admin.csv",NULL,NULL);
INSERT INTO course VALUES(2,"Fall_2008","Fall","2008","Fall_2008.csv",NULL,NULL);
INSERT INTO course VALUES(3,"Spring_2009","Spring","2009","Spring_2009.csv",NULL,NULL);
INSERT INTO course VALUES(4,"Fall_2009","Fall","2009","Fall_2009.csv",NULL,NULL);
INSERT INTO course VALUES(5,"Spring_2010","Spring","2010","Spring_2010.csv",NULL,NULL);
INSERT INTO course VALUES(6,"Fall_2010","Fall","2010","Fall_2010.csv",NULL,NULL);
INSERT INTO course VALUES(7,"Spring_2011","Spring","2011","Spring_2011.csv",NULL,NULL);