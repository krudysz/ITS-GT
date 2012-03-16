delete from  questions;
drop   table questions;
#----------------------------------------
CREATE TABLE questions (
    id              int UNIQUE,
	name            varchar(64),
	question_id     int(11),
    qorder			int(11),
	active			tinyint(1),
	term			varchar(32),
	
    PRIMARY KEY (id)
		#FOREIGN KEY (question_id) REFERENCES webct (id) ON DELETE CASCADE
);
#----------------------------------------
# FOREIGN KEY (term) REFERENCES class (term) ON DELETE CASCADE

/* ----------------- FALL 2009 ----------------- */
INSERT INTO questions VALUES(1,"ch01",156,1,0,"Fall_2009");
INSERT INTO questions VALUES(2,"ch01",332,2,0,"Fall_2009");
INSERT INTO questions VALUES(3,"ch01",334,3,0,"Fall_2009");
INSERT INTO questions VALUES(4,"ch01",345,4,0,"Fall_2009");
INSERT INTO questions VALUES(5,"ch01",445,5,0,"Fall_2009");


INSERT INTO questions VALUES(103,"ch02",1190,1,0,"Fall_2010");
INSERT INTO questions VALUES(133,"ch02",1193,2,0,"Spring_2010");
INSERT INTO questions VALUES(134,"ch02",1194,3,0,"Spring_2010");


INSERT INTO questions VALUES(205,"ch03",1196,1,0,"Fall_2010");
INSERT INTO questions VALUES(206,"ch03",1197,2,0,"Fall_2010");

INSERT INTO questions VALUES(307,"ch04",1199,1,0,"Fall_2010");
INSERT INTO questions VALUES(308,"ch04",1200,2,0,"Fall_2010");
INSERT INTO questions VALUES(337,"ch04",2124,3,0,"Spring_2010");
INSERT INTO questions VALUES(338,"ch04",2125,4,0,"Spring_2010");

INSERT INTO questions VALUES(9,"ch05",1201,1,0,"Fall_2010");
INSERT INTO questions VALUES(10,"ch05",1202,2,0,"Fall_2010");
INSERT INTO questions VALUES(11,"ch06",1203,1,0,"Fall_2010");
INSERT INTO questions VALUES(12,"ch06",1204,2,0,"Fall_2010");
INSERT INTO questions VALUES(13,"ch06",1205,3,0,"Fall_2010");
INSERT INTO questions VALUES(14,"ch06",1206,4,0,"Fall_2010");
INSERT INTO questions VALUES(15,"ch06",1207,5,0,"Fall_2010");

INSERT INTO questions VALUES(16,"ch07",1208,1,0,"Fall_2010");
INSERT INTO questions VALUES(17,"ch07",1209,1,0,"Fall_2010");
INSERT INTO questions VALUES(18,"ch07",1210,2,0,"Fall_2010");

INSERT INTO questions VALUES(19,"ch08",19,1,0,"Fall_2010");
INSERT INTO questions VALUES(20,"ch08",1211,2,0,"Fall_2010");
INSERT INTO questions VALUES(21,"ch08",1212,3,0,"Fall_2010");

INSERT INTO questions VALUES(22,"ch09",77,1,0,"Fall_2010");
INSERT INTO questions VALUES(23,"ch09",93,1,0,"Fall_2010");
INSERT INTO questions VALUES(24,"ch09",1214,2,0,"Fall_2010");

INSERT INTO questions VALUES(25,"ch10",1215,1,0,"Fall_2010");
INSERT INTO questions VALUES(26,"ch10",1216,2,0,"Fall_2010");

INSERT INTO questions VALUES(27,"ch11",1217,1,0,"Fall_2010");
INSERT INTO questions VALUES(28,"ch11",1218,2,0,"Fall_2010");

INSERT INTO questions VALUES(29,"ch12",1219,1,0,"Fall_2010");
INSERT INTO questions VALUES(30,"ch12",1220,2,0,"Fall_2010");

/* ----------------- SPRING 2010 ----------------- */
/*
INSERT INTO questions VALUES(32,"ch01",1097,2,0,"Spring_2010");
INSERT INTO questions VALUES(33,"ch02",1193,1,0,"Spring_2010");
INSERT INTO questions VALUES(34,"ch02",1194,2,0,"Spring_2010");
INSERT INTO questions VALUES(35,"ch03",1196,1,0,"Spring_2010");
INSERT INTO questions VALUES(36,"ch03",1197,2,0,"Spring_2010");

INSERT INTO questions VALUES(37,"ch04",2124,1,0,"Spring_2010");
INSERT INTO questions VALUES(38,"ch04",2125,2,0,"Spring_2010");

INSERT INTO questions VALUES(39,"ch05",2126,1,0,"Spring_2010");
INSERT INTO questions VALUES(40,"ch05",2127,2,0,"Spring_2010");

INSERT INTO questions VALUES(41,"ch06",226,1,0,"Spring_2010");
INSERT INTO questions VALUES(42,"ch06",982,2,0,"Spring_2010");

INSERT INTO questions VALUES(43,"ch07",2129,1,0,"Spring_2010");
INSERT INTO questions VALUES(44,"ch07",2130,2,0,"Spring_2010");

INSERT INTO questions VALUES(45,"ch08",19,1,0,"Spring_2010");
INSERT INTO questions VALUES(46,"ch08",1211,2,0,"Spring_2010");
INSERT INTO questions VALUES(47,"ch08",1212,3,0,"Spring_2010");

INSERT INTO questions VALUES(48,"ch09",2131,1,0,"Spring_2010");
INSERT INTO questions VALUES(49,"ch09",2132,2,0,"Spring_2010");
INSERT INTO questions VALUES(50,"ch09",2133,3,0,"Spring_2010");

INSERT INTO questions VALUES(51,"ch10",1215,1,0,"Spring_2010");
INSERT INTO questions VALUES(52,"ch10",1216,2,0,"Spring_2010");

INSERT INTO questions VALUES(53,"ch11",2134,1,0,"Spring_2010");
INSERT INTO questions VALUES(54,"ch11",2135,2,0,"Spring_2010");
INSERT INTO questions VALUES(55,"ch11",2136,3,0,"Spring_2010");

INSERT INTO questions VALUES(56,"ch12",1217,1,0,"Spring_2010");
INSERT INTO questions VALUES(57,"ch12",1219,3,0,"Spring_2010");

INSERT INTO questions VALUES(58,"ch13",1221,1,0,"Spring_2010");
INSERT INTO questions VALUES(59,"ch13",1222,2,0,"Spring_2010");

INSERT INTO questions VALUES(60,"survey01",1301,1,0,"Spring_2010");
INSERT INTO questions VALUES(61,"survey01",1302,2,0,"Spring_2010");
INSERT INTO questions VALUES(62,"survey01",1303,3,0,"Spring_2010");
INSERT INTO questions VALUES(63,"survey01",1304,4,0,"Spring_2010");
INSERT INTO questions VALUES(64,"survey01",1305,5,0,"Spring_2010");
INSERT INTO questions VALUES(65,"survey01",1306,6,0,"Spring_2010");

INSERT INTO questions VALUES(66,"survey01",742,7,0,"Spring_2010");
INSERT INTO questions VALUES(67,"survey01",730,8,0,"Spring_2010");
INSERT INTO questions VALUES(68,"survey01",538,9,0,"Spring_2010");
INSERT INTO questions VALUES(69,"survey01",1059,10,0,"Spring_2010");
INSERT INTO questions VALUES(70,"survey01",261,11,0,"Spring_2010");
INSERT INTO questions VALUES(71,"survey01",799,12,0,"Spring_2010");
INSERT INTO questions VALUES(72,"survey01",1078,13,0,"Spring_2010");
INSERT INTO questions VALUES(73,"survey01",400,14,0,"Spring_2010");
INSERT INTO questions VALUES(74,"survey01",2117,15,0,"Spring_2010");
INSERT INTO questions VALUES(75,"survey01",58,16,0,"Spring_2010");
INSERT INTO questions VALUES(76,"survey01",393,17,0,"Spring_2010");
INSERT INTO questions VALUES(77,"survey01",365,18,0,"Spring_2010");
INSERT INTO questions VALUES(78,"survey01",262,19,0,"Spring_2010");
INSERT INTO questions VALUES(79,"survey01",235,20,0,"Spring_2010");
INSERT INTO questions VALUES(80,"survey01",520,21,0,"Spring_2010");
INSERT INTO questions VALUES(81,"survey01",399,22,0,"Spring_2010");
INSERT INTO questions VALUES(82,"survey01",422,23,0,"Spring_2010");
*/
