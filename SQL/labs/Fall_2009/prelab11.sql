# Pre-Lab 11: Questions
delete from webct where id=1217;
delete from webct_m where id=1217;
delete from webct where id=1218;
delete from webct_m where id=1218;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1217,'MC','IIR Nulling Filter PEZDEMO','phpimg/ITS_pzplot.php?zeros=0,1,0,-1','What is the magnitude response for the following <i>pole-zero</i> plot?',4,3,2,'lab11');
INSERT INTO webct VALUES(1218,'MC','Polynomial Multiplication','','An IIR filter <PRE class="ITS_Equation"><img src="/ece2025/cgi-bin/mimetex.exe?H(z)=\\frac{B(z)}{A(z)}=\\frac{1}{1-a_1z^{-1}-a_2z^{-2}}"></PRE> has two <b><i>poles</i></b> at: <tt>0.8e<sup>j2&pi;/3</sup></tt> and <tt>0.8e<sup>-j2&pi;/3</sup></tt>.<p>Find <tt>a<sub>1</sub></tt> and <tt>a<sub>2</sub></tt>.',5,2,1,'lab11');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1217,0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt6.png"',100,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt7.png"',0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt8.png"',0,'<img class="ITS_IMG_ANS" src="PreLabs/IIR_filt9.png"');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1218,0,'<tt>a<sub>1</sub></tt> = -1.6<BR><tt>a<sub>2</sub></tt> = -0.8',0,'<tt>a<sub>1</sub></tt> = 0.8<BR><tt>a<sub>2</sub></tt> = -0.64',0,'<tt>a<sub>1</sub></tt> = 0.4<BR><tt>a<sub>2</sub></tt> = -0.8',0,'<tt>a<sub>1</sub></tt> = 0.8<BR><tt>a<sub>2</sub></tt> = 0.64',100,'<tt>a<sub>1</sub></tt> = -0.8<BR><tt>a<sub>2</sub></tt> = -0.64');
