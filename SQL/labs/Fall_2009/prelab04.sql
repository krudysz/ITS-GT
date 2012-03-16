# Pre-Lab 4: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1199,'mc','Chirp instantaneous frequency from Matlab plot','','A chirp signal has the following spectrogram:<p><center><img class"ITS" src="PreLabs/spectrogram_chirp.png"></center><p>Which MATLAB code generated this signal?', 4, 'Chapter3');
INSERT INTO webct VALUES(1200,'mc','Fundamental frequency from spectrum plot','PreLabs/spectrogram_periodic.png','What is the <b>fundamental frequency</b> for the periodic signal whose spectrogram is shown below:', 5, 'Chapter3');

# SURVEY
# id | feedback | weight{n} | answer{n} | reason{n}
#UPDATE webct_mc SET answer2='<img src="/ece2025/cgi-bin/mimetex.exe?\\frac{5\\pi}{2}" alt="(5&pi;) / 2">' WHERE id=1000;

INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1199,0,'<div class="MATLAB">xx = real(exp(-j*6000*pi.*tt.^2 + j*2000*pi.*tt + j*6000));<br>spectrogram(xx,512,[],[],fsamp,<font class="ML_s">&#39;yaxis&#39;</font>);</div>',0,'<div class="MATLAB">xx = real(exp(-j*2000*pi.*tt.^2 + j*4000*pi.*tt + j*200));<br>spectrogram(xx,512,[],[],fsamp,<font class="ML_s">&#39;yaxis&#39;</font>);</div>',0,'<div class="MATLAB">xx = real(exp(j*1000*pi.*tt.^2 + j*6000*pi.*tt));<br>spectrogram(xx,512,[],[],fsamp,<font class="ML_s">&#39;yaxis&#39;</font>);</div>',100,'<div class="MATLAB">xx = real(exp(-j*2000*pi.*tt.^2 + j*6000*pi.*tt + j*100));<br>spectrogram(xx,512,[],[],fsamp,<font class="ML_s">&#39;yaxis&#39;</font>);</div>'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1200,0,'100 Hz',100,'250 Hz',0,'500 Hz',0,'1000 Hz',0,'Insufficient information');