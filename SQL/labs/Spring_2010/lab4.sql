delete from webct where id=2124;
delete from webct_mc where id=2124;

delete from webct where id=2125;
delete from webct_mc where id=2125;

# Lab 4: Questions
# id | qtype | title | image | question | answers | category
#INSERT INTO webct VALUES(2124,'mc','Chirp instantaneous frequency from Matlab code','','A chirp signal is implemented by the following MATLAB code:<pre class="MATLAB">xx = real(exp(j*1000*pi.*tt.^2 + j*6000*pi.*tt + j*300));<br>spectrogram(xx,512,[],[],fsamp,<font class="ML_s">&#39;yaxis&#39;</font>);</pre>Which spectrogram is described by this signal?<p>',4,3,1,'Chapter3');
INSERT INTO webct VALUES(2124,'mc','Chirp instantaneous frequency from Matlab code','','A chirp signal is implemented by the following MATLAB code:<pre class="MATLAB">xx =  real( exp( -j*1000*pi.*tt.^2 + j*6000*pi.*tt + j*1000 ) );</pre>Which spectrogram is described by this signal?<p>',4,3,1,'Chapter3');
INSERT INTO webct VALUES(2125,'mc','Fundamental frequency from spectrum plot','PreLabs/spectrogram_periodic1.png','What is the <b>fundamental frequency</b> for the periodic signal whose spectrogram is shown below:',5,1,1,'Chapter3');

# SURVEY
# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2124,0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spec_chirp1.png">',0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spec_chirp2.png">',0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spec_chirp3.png">',100,'<img class="ITS_IMG_ANSWER" src="PreLabs/spec_chirp4.png">'); 

#INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2124,0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spectrogram_chirp.png">',0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spectrogram_chirp1.png">',0,'<img class="ITS_IMG_ANSWER" src="PreLabs/spectrogram_chirp2.png">',100,'<img class="ITS_IMG_ANSWER" src="PreLabs/spectrogram_chirp3.png">'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2125,0,'250 Hz',100,'500 Hz',0,'1000 Hz',0,'1500 Hz',0,'Insufficient information');