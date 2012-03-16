# Pre-Lab 3: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1196,'mc','Sum of cosines from spectrum plot','','A signal <tt>x(t)</tt> has the following spectrum representation:<p><center><img class"ITS" src="PreLabs/spectrum.png"></center><p>What is the equation for <tt>x(t)</tt>?', 4, 'Chapter3');
INSERT INTO webct VALUES(1197,'mc','Fundamental frequency from spectrum plot','PreLabs/spectrum2.png','What is the <b>fundamental frequency</b> for the signal shown below:', 4, 'Chapter3');
INSERT INTO webct VALUES(1198,'c','Negative frequencies in spectrum','','Explain why <b>negative</b> frequencies are needed in the spectrum.',1, 'Chapter3');

# SURVEY
# id | feedback | weight{n} | answer{n} | reason{n}
#UPDATE webct_mc SET answer2='<img src="/ece2025/cgi-bin/mimetex.exe?\\frac{5\\pi}{2}" alt="(5&pi;) / 2">' WHERE id=1000;

INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1196,0,'<tt>x(t) = 0.5 + cos(100&pi;t)</tt>',0,'<tt>x(t) = cos(50&pi;t)</tt>',0,'<tt>x(t) = 0.5 + 1.5cos(100&pi;t)</tt>',100,'<tt>x(t) = 0.5 + 3cos(100&pi;t)</tt>',0,'<tt>x(t) = 0.5 + 3cos(50&pi;t)</tt>'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1197,0,'0',100,'10',0,'20',0,'35',0,'50'); 