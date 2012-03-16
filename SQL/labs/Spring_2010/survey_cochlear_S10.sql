# Cochlear Implant Lab Survey: Questions Spring 2010
delete from webct where id=1300;
delete from webct_mc where id=1300;
delete from webct where id=1301;
delete from webct_mc where id=1301;
delete from webct where id=1302;
delete from webct_mc where id=1302;
delete from webct where id=1303;
delete from webct_mc where id=1303;
delete from webct where id=1304;
delete from webct_mc where id=1304;
delete from webct where id=1305;
delete from webct_mc where id=1305;
delete from webct where id=1306;
delete from webct_mc where id=1306;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1300,'MC','cochlear implant','','For the cochlear implant lab what sampling rate did you use?',5,1,1,'Survey','');
INSERT INTO webct VALUES(1301,'MC','cochlear implant','','What do you consider as the minimum number of filters necessary for speech intelligibility based on your implementation of the cochlear implant filterbank in Lab #11?',4,1,1,'Survey','');
INSERT INTO webct VALUES(1302,'P','cochlear implant','','If you could design a 128 channel cochlear implant what engineering design trade-offs would you make?',1,1,1,'Survey','');

INSERT INTO webct VALUES(1303,'MC','cochlear implant','','How much did you learn from the cochlear implant lab in comparison to other labs (e.g. Speech Synthesis, ECG Interference Removal).',5,1,1,'Survey','');
INSERT INTO webct VALUES(1304,'MC','cochlear implant','','Compared to other modules in the course the cochlear implant lab improved my ability to design filters?',5,1,1,'Survey','');
INSERT INTO webct VALUES(1305,'MC','cochlear implant','','The cochlear implant lab makes signal processing seem more hands-on and applied?',5,1,1,'Survey','');
INSERT INTO webct VALUES(1306,'MC','cochlear implant','','I find is easier to learn the skills and concepts in this course when they are anchored in a physiological system.',5,1,1,'Survey','');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(1300,'<tt>Fs = 8000 Hz</tt>','<tt>Fs = 16,000 Hz</tt>','<tt>Fs = 22,050 Hz</tt>','<tt>Fs = 44,100 Hz</tt>','I don\'t remember');
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4)         VALUES(1301,'<tt>2</tt>','<tt>4</tt>','<tt>8</tt>','<tt>16</tt>');
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(1303,'Much more','More','About the same','Less','Much less');
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(1304,'Strongly agree','Agree','Do not agree or disagree','Disagree','Strongly disagree');
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(1305,'Strongly agree','Agree','Do not agree or disagree','Disagree','Strongly disagree'); 
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(1306,'Strongly agree','Agree','Do not agree or disagree','Disagree','Strongly disagree');

#Your response to this survey is anonymous and will not be viewed by the instructor until after the final grades have been submitted