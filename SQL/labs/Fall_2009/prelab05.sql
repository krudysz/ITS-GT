# Pre-Lab 5: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1201,'mc','Center frequency from spectrum','PreLabs/beat_frequency.png','Below is the spectrum for the signal <pre class="ITS_Equation">x(t) = e<sup>2</sup><b>cos(</b>5&pi;t<b>)cos(</b>2&pi;f<sub>c</sub>t<b>)</b></pre>Determine <tt>f<sub>c</sub></tt>.', 5, 'Chapter3');
##INSERT INTO webct VALUES(1201,'mc','Center frequency from spectrum','PreLabs/beat_frequency.png','Below is the spectrum for the signal <pre class="ITS_Equation">x(t) = e<sup>2</sup><b>cos(</b>5&pi;t<b>)cos(</b>2&pi;<select><option><tt>f<sub>c</sub></tt></option><option>2.5</option><option>5</option><option>7.5</option><option>10</option><option>15</option></select>t<b>)</b></pre>Determine <tt>f<sub>c</sub></tt>.', 5, 'Chapter3');
INSERT INTO webct VALUES(1202,'mc','Fundamental period of FM periodic signal','','A periodic FM signal <pre class="ITS_Equation">s(t) = &pi;<sup>2</sup><b>cos(</b>100&pi;t + 2<b>cos(</b>4&pi;t<b>))</b></pre>Determine the fundamental period of this signal, i.e., the shortest period.', 5, 'Chapter3');

#INSERT INTO webct VALUES(1201,'mc','Center frequency from spectrum','PreLabs/beat_frequency.png','Below is the spectrum for the the signal <pre class="ITS_Equation"><img src="/ece2025/cgi-bin/mimetex.exe?x(t)=e^{2}\\cos(5\\pi t)\\cos(2\\pi f_{c}t)"></pre>Determine <tt>f<sub>c</sub></tt>.', 5, 'Chapter3');
#INSERT INTO webct VALUES(1202,'mc','Fundamental period of FM periodic signal','','A periodic FM signal <pre class="ITS_Equation"><img src="/ece2025/cgi-bin/mimetex.exe?s(t)=\\pi^2\\cos\\left(100\\pi t + 2\\cos(4\\pi t)\\right)"></pre>Determine the fundamental period of this signal, i.e., the shortest period.', 5, 'Chapter3');

# SURVEY
# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1201,0,'f<sub>c</sub> = 2.5 Hz',0,'f<sub>c</sub> = 5 Hz',100,'f<sub>c</sub> = 7.5 Hz',0,'f<sub>c</sub> = 10 Hz',0,'f<sub>c</sub> = 15 Hz'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1202,0,'T<sub>o</sub> = 0.01',0,'T<sub>o</sub> = 0.0192',0,'T<sub>o</sub> = 0.02',0,'T<sub>o</sub> = 0.25',0,'T<sub>o</sub> = 0.5');