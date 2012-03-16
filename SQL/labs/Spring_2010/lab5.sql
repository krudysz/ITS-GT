delete from webct where id=2126;
delete from webct_mc where id=2126;

delete from webct where id=2127;
delete from webct_mc where id=2127;

# Lab 5: Questions
# id | qtype | title | image | question | answers | category
#INSERT INTO webct VALUES(2126,'mc','Center frequency from spectrum','PreLabs/beat_frequency1.png','For the FM signal <pre class="ITS_Equation">x(t) = &pi;<sup>2</sup><b>cos</b><sup>2</sup><b>(</b>2&pi;f<sub>c</sub>t<b>)</b></pre>determine <tt>f<sub>c</sub></tt>.',5,1,1,'Chapter3');
INSERT INTO webct VALUES(2126,'mc','Center frequency from spectrum','PreLabs/ITSspecgramLab5.png','For a periodic signal <tt>s(t)</tt>, the spectrogram shows horizontal lines<br>at the following set of frequencies <tt>{ 400, 800, 1000, 2000, 2500 }</tt> Hz.<p>Determine the fundamental period of <tt>s(t)</tt>.',5,1,2,'Chapter3');
INSERT INTO webct VALUES(2127,'mc','Fundamental period of FM periodic signal','','Given a periodic FM signal <pre class="ITS_Equation">s(t) = &pi; <b>cos( </b> 5 <b>cos( </b>5&pi;t<b> ) )</b></pre>Determine the fundamental period of this signal, i.e., the shortest period.',5,1,1,'Chapter3');

# id | feedback | weight{n} | answer{n} | reason{n}
#INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2126,0,'f<sub>o</sub> = 0 Hz',100,'f<sub>c</sub> = 2.5 Hz',0,'f<sub>c</sub> = 5 Hz',0,'f<sub>c</sub> = 7.5 Hz',0,'f<sub>c</sub> = 10 Hz');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2126,0,'T<sub>o</sub> = 10 msec',100,'T<sub>o</sub> = 5 msec',0,'T<sub>o</sub> = 2.5 msec',0,'T<sub>o</sub> = 2 msec',100,'T<sub>o</sub> = 1 msec');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2127,0,'T<sub>o</sub> = 0.04',0,'T<sub>o</sub> = 0.1',0,'T<sub>o</sub> = 0.25',100,'T<sub>o</sub> = 0.4',0,'T<sub>o</sub> = 0.5');