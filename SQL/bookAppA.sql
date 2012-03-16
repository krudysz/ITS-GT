# Quiz 1: Appendix Questions
delete from webct where id=2118;
delete from webct where id=2119;
delete from webct where id=2120;
delete from webct where id=2121;
delete from webct where id=2122;
delete from webct where id=2123;

delete from webct_mc where id=2118;
delete from webct_mc where id=2119;
delete from webct_mc where id=2120;
delete from webct_mc where id=2121;
delete from webct_mc where id=2122;
delete from webct_mc where id=2123;

# Insert into webct table
# id | qtype | title | image | question | answers | answers_config | question_config | category
INSERT INTO webct VALUES(2118, 'MC', '', '', 'Convert the following equation to Polar form: <b>7 - 5j</b>',4,1,1,'AppendixA');
INSERT INTO webct VALUES(2119, 'MC', '', '', 'Find the conjugate of <tt><b>i(1-i)</tt></b>? ',4,1,1,'AppendixA');
INSERT INTO webct VALUES(2120, 'MC', '', '', 'Convert the following equation to Rectangular form: 7.32 (<) -270deg',4,1,1,'AppendixA');
INSERT INTO webct VALUES(2121, 'MC', '', '', 'Reduce the following equation to the standard form: <b>(2-i)/(3+2i)</b>',4,1,1,'AppendixA');
INSERT INTO webct VALUES(2122, 'MC', '', '', 'Which of the following is a solution of <b>[2{e<sup>i</sup>(-5&pi;/6)}]<sup>30</sup></b>',4,1,1,'AppendixA');
INSERT INTO webct VALUES(2123, 'MC', '', '', 'Which of the following is one of the correct solutions for <b>z<sup>5</sup> = -1</b>',4,1,1,'AppendixA');

# Insert into webct_mc table
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2118,0,'7.6 (<) 324.5deg',0,'8.6 (<) 120.5deg',0,'8.6 (<) 324.5deg',100,'7.6 (<) 120.5deg');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2119,0,'<tt>-1 + i</tt>',0,'<tt>1 + i</tt>',100,'<tt>1 - i</tt>',0,'<tt>-1 - i</tt>');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2120,0,'1.14j',100,'7.32j',0,'3.72j',0,'6.14j');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2121,0,'<tt>5 - 7/13i</tt>',0,'<tt>4/13 + 7/13i</tt>',100,'<tt>4/13 - 7/13i</tt>',0,'<tt>5 + 7/13i</tt>');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2122,100,'',0,'',0,'',0,'');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2123,100,'e<sup>j10&pi;/7</sup>',0,'e<sup>2&pi;</sup>',0,'e<sup>j5&pi;/7</sup>',0,'e<sup>-j5&pi;/7</sup>');