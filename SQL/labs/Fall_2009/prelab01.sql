# Pre-Lab 1: Questions-Fall_2009
delete from webct where id=1096;
delete from webct_mc where id=1096;

delete from webct where id=1097;
delete from webct_c where id=1097;

# id | qtype | title | image | question | answers | category
INSERT INTO webct (id,qtype,title,image,question,answers,category) VALUES(1096,'MC','Frequency from Period','','In the question above, which equation is <b>the most</b> usefull?', 1, 'Chapter2');
INSERT INTO webct (id,qtype,title,image,question,answers,category) VALUES(1097,'C','Period','','You are given the following MATLAB code: <pre class="MATLAB">Fs = 11000;<br>tt = 0:1/Fs:1;<br>xx = cos(pi*10*(tt-0.02));</pre>What is the <b>period (in seconds)</b> of the cosine function defined above?<p><i>The answer should be <b>numeric, with 2-3 significant digits</b>. You can use fixed or exponential notation (e.g. 0.0015 or 1.5e-3).', 1, 'Chapter2');

# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,answer1) VALUES(1096,'<img src="/ece2025/cgi-bin/mimetex.exe?\\frac{5\\pi}{2}" alt="">');
INSERT INTO webct_c (id,formula,val1,min_val1,max_val1,vals) VALUES(1097,'2/{alpha}','alpha',10,20,1);
