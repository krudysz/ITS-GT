delete from webct where id=2134;
delete from webct_mc where id=2134;

delete from webct where id=2135;
delete from webct_mc where id=2135;

delete from webct where id=2136;
delete from webct_mc where id=2136;

#<img src="images/cochlear1.jpg" ><img src="images/cochlear2.jpg">

# Lab 11: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(2134,'mc','cochlear','','In a cochlear implant acoustic frequency is conveyed by:',3,1,1,'','');
INSERT INTO webct VALUES(2135,'mc','cochlear filter bank','','A  cochlear implant signal processor separates acoustic signals by using what type of filter bank:',5,1,1,'','');
INSERT INTO webct VALUES(2136,'mc','cochlear implant','images/cochlear.png','In the diagram, select the number that illustrates the location of the cochlear implant signal processing electronics.',6,1,2,'','');

# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3) VALUES(2134,100,'Electrical stimulation',0,'Mechanical stimulation',0,'Visual stimulation');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2135,0,'Lowpass',0,'Sidepass',0,'Highpass',100,'Bandpass',0,'Bandreject');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5,weight6,answer6) VALUES(2136,100,'1 or 2',0,'3',0,'4',0,'5',0,'6',0,'7');