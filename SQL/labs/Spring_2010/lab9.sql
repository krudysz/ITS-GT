delete from webct where id=2131;
delete from webct_mc where id=2131;

delete from webct where id=2132;
delete from webct_mc where id=2132;

delete from webct where id=2133;
delete from webct_mc where id=2133;

# Lab 9: Questions
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(2131,'mc','MATLAB factoring polynomial','','What is the MATLAB function for factoring polynomials:',5,1,1,'Chapter7','');
INSERT INTO webct VALUES(2132,'mc','MATLAB polynomial roots','','What is the MATLAB function for creating a polynomial from its roots:',5,1,1,'Chapter7','');
INSERT INTO webct VALUES(2133,'mc','polynomial roots','','Find the roots of the polynomial <pre class="ITS_Equation">1 + z<sup>-4</sup></pre>',5,1,1,'Chapter7','');

# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2131,0,'<code>factor</code>',100,'<code>roots</code>',0,'<code>poly</code>',0,'<code>makepoly</code>',0,'<code>zeros</code>');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2132,0,'<code>factor</code>',0,'<code>roots</code>',100,'<code>poly</code>',0,'<code>makepoly</code>',0,'<code>zeros</code>');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(2133,0,'<tt>1, j, -1, -j</tt>',100,'<tt>e<sup>j&pi;/4</sup>, e<sup>j3&pi;/4</sup>, e<sup>-j&pi;/4</sup>, e<sup>-j3&pi;/4</sup></tt>',0,'<tt>-1, -1, -1, -1</tt>',0,'<tt>1, 1, 1, 1</tt>',0,'<tt>j, j, j, j</tt>');
