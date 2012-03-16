# Pre-Lab 10: Questions
delete from webct where id=1215;
delete from webct where id=1216;
delete from webct_mc where id=1215;
delete from webct_mc where id=1216;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1215,'MC','IIR BPF PEZDEMO','PreLabs/IIR_filt1.png','What are the <b>poles</b> of this filter?',5,1,1,'lab10','');
INSERT INTO webct VALUES(1216,'MC','IIR BPF PEZDEMO','ITS_pzplot.php?poles=0,0.95,0,-0.95&zeros=-1,0,0.5,0.86,0.5,-0.86','What is the magnitude response for the following <i>pole-zero</i> plot?',4,2,2,'lab10','');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1215,100,'<tt>0.95e<sup>j3&pi;/4</sup>, 0.95e<sup>-j3&pi;/4</sup></tt>',0,'<tt>0.95, -0.95</tt>',0,'<tt>0.95e<sup>j&pi;/2</sup>, 0.95e<sup>-j&pi;/2</sup></tt>',0,'<tt>0.95e<sup>j&pi;/4</sup>, 0.95e<sup>-j&pi;/4</sup></tt>',0,'<tt>0.95e<sup>j&pi;/4</sup>, 0.95e<sup>-j&pi;/4</sup>, 0.95</tt>');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1216,0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt2.png"/>',0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt3.png"/>',100,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt4.png"/>',0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt5.png"/>');

# ALTER TABLE webct ADD COLUMN answers_config INT(11) AFTER answers;
# ALTER TABLE webct ADD COLUMN question_config INT(11) AFTER answers_config;
# ALTER TABLE webct MODIFY image VARCHAR(128);
