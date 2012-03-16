# Pre-Lab 2: Questions
delete from webct where id=1190;
delete from webct_mc where id=1190;

delete from webct where id=1191;
delete from webct_mc where id=1191;

delete from webct where id=1195;
delete from webct_c where id=1195;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1190,'mc','Complex Amplitude','','For the signal <img src="/ece2025/cgi-bin/mimetex.exe?\\cos(123 t)"> find its correct match among the signals.', 5,1,1, 'Chapter2');
INSERT INTO webct VALUES(1191,'mc','Complex Amplitude','','For the signal <img src="/ece2025/cgi-bin/mimetex.exe?\\cos(123 t - 0.25\\p)"> find its correct match among the signals.',5,1,1, 'Chapter2');
INSERT INTO webct VALUES(1195,'c','Phasor Addition - Find Amplitude','','<pre class="MATLAB">tt = -200 : 1/3000 : 200;<br>zz = 0;<br>for k=1:2<br>   zz = zz + {beta}*exp(-j*{alpha}*k)*exp(j*88*pi*tt);<br>end<br>xx = real(zz);</pre>The result of this MATLAB code is that <B><TT>xx</TT></B> is a sinusoid.<BR>What is the <B>amplitude</B> of <B><TT>xx</TT></B>?<P><font color="red">(Hint: analyze the code, do not run it.)</font><P><i>The answer should be <B>numeric, 3 or more significant digits </B> and you can use fixed or exponential notation (e.g. 0.0015 or 1.5e-3).</i>',1,1,1,'Chapter2');

#INSERT INTO webct VALUES(1192,'mc','Complex Amplitude','','For the signal <img src="/ece2025/cgi-bin/mimetex.exe?\\\sin(123t) + cos(123t)"> find its correct match among the signals.', 1,1,1, 'Chapter2');
#INSERT INTO webct VALUES(1193,'mc','Add two sinusoids','','For the signal <img src="/ece2025/cgi-bin/mimetex.exe?\\\sin(123t) - cos(123t)"> find its correct match among the signals.', 1,1,1, 'Chapter2');

# SURVEY
# id | feedback | weight{n} | answer{n} | reason{n}
#UPDATE webct_mc SET answer2='<img src="/ece2025/cgi-bin/mimetex.exe?\\frac{5\\pi}{2}" alt="(5&pi;) / 2">' WHERE id=1000;

INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1190,0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(-je^{j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left((1-j)e^{j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(e^{j123t-j0.25\\pi}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(0.5e^{j123t}+0.5 e^{-j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(\\sqrt{2}e^{j(123t+0.25\\pi)}\\right)">'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1191,0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(-je^{j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left((1-j)e^{j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(e^{j123t-j0.25\\pi}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(0.5e^{j123t}+0.5 e^{-j123t}\\right)">',0,'<img src="/ece2025/cgi-bin/mimetex.exe?\\text{real}\\left(\\sqrt{2}e^{j(123t+0.25\\pi)}\\right)">'); 
#INSERT INTO webct_c (id,formula,val1,min_val1,max_val1,vals) VALUES(1095,'2/{alpha}','alpha',10,20,1);
INSERT INTO webct_c (id,formula,val1,min_val1,max_val1,val2,min_val2,max_val2,vals) VALUES(1195,'{beta}*sqrt(pow(cos(-{alpha})+cos(-2*{alpha}),2)+pow(sin(-{alpha})+sin(-2*{alpha}),2))','alpha',1,5,'beta',4,8,2);