#delete from records;
drop table if exists records;
#----------------------------------------
CREATE TABLE records (
    id              int UNIQUE,
		name						varchar(256),
		username				varchar(31),
		q1              int(11),
		q2              int(11),
		q3              int(11),
		q4              int(11),
		q5              int(11),
		q6              int(11),
		q7              int(11),
		q8              int(11),
		q9              int(11),
		q10              int(11),
		q11              int(11),
		q12              int(11),
		q13              int(11),
		q14              int(11),
		q15              int(11),
		q16              int(11),
		q17              int(11),
		q18              int(11),
		q19              int(11),
		q20              int(11),
		q21              int(11),
		q22              int(11),
		q23              int(11),
		q24              int(11),
		q25              int(11),
		q26              int(11),
		q27              int(11),
		q28              int(11),
    PRIMARY KEY (id),
		FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE
);
#----------------------------------------