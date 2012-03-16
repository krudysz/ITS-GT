# Lab 2: Questions
delete from webct where id=1193;
delete from webct_mc where id=1193;

delete from webct where id=1194;
delete from webct_mc where id=1194;

# id | qtype | title | image | question | answers | category

INSERT INTO webct VALUES(1194,'mc','Complex Amplitude','','The signal <pre class="ITS_Equation"><img src="/cgi-bin/mimetex.cgi?\\text{real}\\left(0.5e^{j123t}+0.5 e^{-j123t}\\right)"></pre> can also be expressed as:', 4,1,1, 'Lab2');
INSERT INTO webct VALUES(1193,'mc','Add two sinusoids','','Two sinusoids are added together to obtain a sinusoid:<pre class="ITS_Equation">10cos(77t - 1) + 8cos(77t + 2) = Acos(&omega;t + &phi;)</pre>Find the parameters: <tt>A</tt>, <tt>&omega;</tt>, and <tt>&phi;</tt>', 5,1,1, 'Lab2');

INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1193,0,'<tt>A = 15.4 | &omega; = 77</tt>',0,'<tt>&omega; = 18 | &pi; = 0.707</tt>',0,'<tt>A = 18 | &pi; = 1.81</tt>',100,'<tt>A = 18 | &omega; = 77</tt>',0,'<tt>A = 3.76 | &pi; = 0.707</tt>'); 
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1194,0,'<tt>sin(123t)</tt>',100,'<tt>cos(123t + 2&pi;)</tt>',0,'<tt>2cos(123t + &pi;)</tt>',0,'<tt>sin(123t)+cos(123t)</tt>'); 