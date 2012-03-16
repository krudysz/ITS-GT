delete from webct where id=2129;
delete from webct_mc where id=2129;

delete from webct where id=2130;
delete from webct_mc where id=2130;

# Lab 7: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(2129,'mc','first-difference FIR filter','','An input signal <div class="ITS_Equation"><pre class="ITS_Equation">x[n] = &delta;[n-1] + &delta;[n-2]</pre><span class="ITS_plot" onClick="ITS_plot(this)">plot</span></div> is passed through a first-difference FIR filter, what is the output?',4,3,1,'Chapter5','');
INSERT INTO webct VALUES(2130,'mc','length-3 running-average FIR filter','phpimg//ITS_stem.php?t=s&d=1,0.3,2,0.6,3,0.6,4,0.3&title=y[n]','For an input signal <pre class="ITS_Equation">x[n] = &delta;[n-1] + &delta;[n-2]</pre> an FIR filter outputs the follwing signal.<p>Determine the filter coefficients for this system.</p>',4,1,1,'Chapter5','');

# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2129,100,'<img src="phpimg/ITS_stem.php?t=s&d=1,1,2,0,3,-1&title=y[n]" class="ITS_pzplot">',0,'<img src="phpimg/ITS_stem.php?t=s&d=1,1,2,1&title=y[n]" class="ITS_pzplot">',0,'<img src="phpimg/ITS_stem.php?t=s&d=1,1,2,2,3,1&title=y[n]" class="ITS_pzplot">',0,'<img src="phpimg/ITS_stem.php?t=s&d=1,0.3,2,0.6,3,0.6,4,0.3&title=y[n]" class="ITS_pzplot">');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(2130,100,'<tt>{1 , -1}</tt>',0,'<tt>{1 , 0}</tt>',100,'<tt>{1/3 , 1/3 , 1/3}</tt>',0,'<tt>{1 , -1 , 1}</tt>');