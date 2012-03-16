CREATE TABLE concept(
    id              int UNIQUE,
    name            varchar(64),
    node_number     int,
    chapter_number  int,
    concept_file    varchar(64),
    book_ref        varchar(8),
    parents         varchar(64),

    PRIMARY KEY (id)
);
#----------------------------------------
CREATE TABLE concept_synonyms(
    id          int,
#   name        varchar(64),
    synonyms    varchar(64),

    PRIMARY KEY (id, synonyms),
    FOREIGN KEY (id) REFERENCES concept (id) ON DELETE CASCADE
);
#----------------------------------------
CREATE TABLE question(
    id              int,
    concept_id      int,
    question_file   varchar(64),
    answer_file     varchar(64),
    format          varchar(8),
    solution        varchar(64),

    PRIMARY KEY (id)
#   FOREIGN KEY (concept_id) REFERENCES concept (id) ON DELETE NO ACTION
);
#----------------------------------------
CREATE TABLE users(
    id          int,
    first_name  varchar(32),
    last_name   varchar(32),
    username    varchar(32) UNIQUE,
    password    varchar(32),
		status			varchar(32),
    
    PRIMARY KEY (id)
);
#----------------------------------------
#CREATE TABLE user_state(
#    user_id         int,
#    question_id     int,
#    user_answer	 varchar(32),
#    
#    PRIMARY KEY (user_id),
#    FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL
#);
#----------------------------------------
#CREATE TABLE statistics(
#    user_id         int,
##   concept_name    varchar(64),
#    question_id     int,
#    answered        varchar(32),
#    score           float,
#    
##   PRIMARY KEY (user_id, question_id),
#    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
#    FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL
#);
#----------------------------------------
CREATE TABLE labview(
    concept_id      int,
    gui_name        varchar(32),
    
    FOREIGN KEY (concept_id) REFERENCES concept (id) ON DELETE NO ACTION
);
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

INSERT INTO users VALUES(1,"Greg","Krudysz","gte269x","root","admin");
INSERT INTO users VALUES(1,"Jim","McClellan","jm53","root","admin");
