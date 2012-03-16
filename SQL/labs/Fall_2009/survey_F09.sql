# Survey: Questions Fall 2009
delete from webct where id=1221;
delete from webct_mc where id=1221;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1221,'MC','Conceptual understanding','','My conceptual understanding of the material in this class is at:',5,1,1,'survey');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1221,0,'expert level &mdash; <i>understood all concepts well</i>',0,'advanced level &mdash; <i>understood most of the concepts well</i>',0,'intermediate level &mdash; <i>understood majority of the concepts</i>',0,'basic level &mdash; <i>understood only fundamental concepts</i>',0,'beginner level &mdash; <i>still struggle with most of the concepts</i>');
