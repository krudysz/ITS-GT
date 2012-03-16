# Pre-Lab 12: Questions Fall 2009
delete from webct where id=1219;
delete from webct_mc where id=1219;
delete from webct where id=1220;
delete from webct_m where id=1220;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1219,'MC','Convolution of two rectangles, CCONVDEMO','','What is the output when the following two signals are convolved:<p><center><img src="phpimg/ITS_signal.php?t=r&d=-1,2,1&title=a(t)"><font size=10>*</font><img src="phpimg/ITS_signal.php?t=r&d=1,3,2&title=b(t)"></center>',4,3,1,'lab12');
INSERT INTO webct VALUES(1220,'M','Output signal of bandreject filter, CLTIdemo','images/brf_100.png','An input signal of the form <pre class="ITS_Equation">x(t) = cos(2&pi;<b>f</b>t)</pre>is passed through the filter as shown in the figure.<p>Determine the frequency (<b><tt>f</tt></b>) of the input signal so that the output signal has:',5,2,2,'lab11');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1219,0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_signal.php?t=t&d=0,4,3&title=a(t)*b(t)">',0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_signal.php?t=tr&d=-1,0,3,4,2&title=a(t)*b(t)">',0,'<img class="ITS_IMG_ANS" src="phpimg/ITS_signal.php?t=r&d=1,2,2&title=a(t)*b(t)">',100,'<img class="ITS_IMG_ANS" src="phpimg/ITS_signal.php?t=tr&d=0,2,3,5,4&title=a(t)*b(t)">');
INSERT INTO webct_m (id,L1,L2,L3,R1,R2,R3,R4,R5,R6) VALUES(1220,'<tt>min output amplitude</tt>','<tt>max output amplitude</tt>','<tt>output amplitude = &frac12</tt>','<tt>f = 100 Hz</tt>','<tt>f = 0 Hz</tt>','<tt>f = 85 Hz</tt>','<tt>f = 25 Hz</tt>','<tt>f = 155 Hz</tt>','<tt>f = 200 Hz</tt>');
