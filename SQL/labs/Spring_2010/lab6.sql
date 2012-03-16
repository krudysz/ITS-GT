delete from webct where id=2128;
delete from webct_mc where id=2128;

# Lab 6: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(2128,'mc','over-sampled or under-sampled','','For the FM signal <pre class="ITS_Equation">x(t) = &pi;<sup>2</sup><b>cos</b><sup>2</sup><b>(</b>2&pi;f<sub>c</sub>t<b>)</b></pre>determine <tt>f<sub>c</sub></tt>.',5,1,1,'Chapter3');

# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2126,0,'T<sub>o</sub> = 10 msec',100,'T<sub>o</sub> = 5 msec',0,'T<sub>o</sub> = 2.5 msec',0,'T<sub>o</sub> = 2 msec',100,'T<sub>o</sub> = 1 msec');