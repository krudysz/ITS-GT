# Lab 13: Questions
delete from webct where id=1221;
delete from webct where id=1222;

delete from webct_mc where id=1221;
delete from webct_mc where id=1222;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1221,'MC','Notch filter Pole-zero plot','','Select the appropriate pole-zero configuration for a <b>notch</b> filter.',5,1,1, 'lab13','');
INSERT INTO webct VALUES(1222,'MC','Notch filter sampling frequency','images/notch50Hz.png','An ECG device (in Europe) must remove <tt>50-Hz</tt> interferance. Suppose that this is done with a digital IIR notch filter whose frequency response is shown in the figure.<p><p>Determine the sampling frequency used when the ECG was recorded.',5,1,2, 'lab13','');

#<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=0.64,0.64,0.64,-0.64&zeros=0.7,0.7,0.7,-0.7">

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1221,0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=0.9,0&zeros=0,1,0,-1">',0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=0.45,0.78,0.45,-0.78&zeros=0.56,0.96,0.56,-0.96">',100,'<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=0.45,0.78,0.45,-0.78&zeros=0.5,0.87,0.5,-0.87">',0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=0.45,0.78,0.45,-0.78&zeros=-0.5,0.87,-0.5,-0.87">',0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_pzplot.php?poles=-0.9,0.1,-0.9,-0.1&zeros=0.9,0.1,0.9,-0.1">');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1222,0,'f<sub>s</sub> = 100 Hz',100,'f<sub>s</sub> = 400 Hz',0,'f<sub>s</sub> = 800 Hz',0,'f<sub>s</sub> = 1200 Hz',0,'f<sub>s</sub> = 8000 Hz');

#UPDATE stats_1 SET comment="1,3,4,2,5,6" WHERE question_id=1211;