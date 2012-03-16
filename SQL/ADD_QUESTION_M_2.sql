
delete from webct where id=1098;
delete from webct_mc where id=1098;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1098,"MC","FIR frequency response of moving averager","/images/FIR_4.png","The frequency response for an <tt>L</tt>-point moving averager is shown in the figure.<p>What is <tt>L</tt>?</p>",5,1,1,'Chapter13',NULL);
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES (1098,0,"<tt>L = 5</tt>",0,"<tt>L = 6</tt>",100,"<tt>L = 7</tt>",0,"<tt>L = 8</tt>",0,"<tt>L = 9</tt>");

delete from webct where id=1099;
delete from webct_mc where id=1099;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1099,"MC","FIR frequency response of moving averager","","What is the frequency response of the following system <br><pre class=ITS_Equation><latex>y[n]=\\frac{1}{4}\\sum_{k=0}^{3}x[n-k]</latex></pre>",4,1,1,'Chapter13',NULL);
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES (1099,100,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_6.png">',0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_2.png">',0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_3.png">',0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_7.png">');

delete from webct where id=1100;
delete from webct_mc where id=1100;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1100,"MC","FIR frequency response of moving averager","","An <b><tt>8</tt></b>-point running-average filter has which of the following frequency response?",4,1,1,'Chapter13',NULL);
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES (1100,0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_4.png">',0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_1.png">',0,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_7.png">',100,'<IMG class=ITS_IMG_ANS src="RESOURCE_PATH/images/FIR_8.png">');
