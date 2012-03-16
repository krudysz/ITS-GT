# Pre-Lab 8: Questions
delete from webct where id=1211;
delete from webct where id=1212;
delete from webct where id=1213;

delete from webct_m where id=1211;
delete from webct_m where id=1212;
delete from webct_m where id=1213;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1211,'M','Frequency Response FIR','','The frequency response of a FIR filter requires evaluation of complex-valued formulas such as<PRE class="ITS_Equation">H(e<sup>j&omega;</sup>) = 1 - e<sup>-j2&omega;</sup></PRE>where <b><tt>&omega;</tt></b> is the normalized frequency called "omega-hat" in the text.<p>Evaluate the <tt>H(e<sup>j&omega;</sup>)</tt> given above at each of the frequencies and match the correct answer.',6,1,1, 'lab8');
INSERT INTO webct VALUES(1212,'M','DLTI Demo MATLAB filters','','Given the FIR filter description in the left column (below), choose the correct frequency response type from the right column. DLTIdemo might be helpful for these questions.',5,1,1, 'lab8');
INSERT INTO webct VALUES(1213,'M','Sine Cosine Functions','','Match the following:',4,1,1, 'chapter 2');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_m (id,L1,L2,L3,R1,R2,R3,R4,R5,R6) VALUES(1211,'At <tt>&omega; = 0</tt>, <tt>H(e<sup>j&omega;</sup>) =</tt> ?','At <tt>&omega; = &pi;/4</tt>, <tt>H(e<sup>j&omega;</sup>) =</tt> ?','At <tt>&omega; = &pi;/2</tt>, <tt>H(e<sup>j&omega;</sup>) =</tt> ?','1. <tt>H(e<sup>j&omega;</sup>) = 0</tt>','2. <tt>H(e<sup>j&omega;</sup>) = 1 + j</tt>','3. <tt>H(e<sup>j&omega;</sup>) = 2</tt>','4. <tt>H(e<sup>j&omega;</sup>) = 1.413 e<sup>-j&pi;/4</sup></tt>','5. <tt>H(e<sup>j&omega;</sup>) = -1 + j</tt>','6. <tt>H(e<sup>j&omega;</sup>) = -2</tt>');
INSERT INTO webct_m (id,L1,L2,R1,R2,R3,R4,R5) VALUES(1212,'First difference filter','10-point running average filter','Lowpass Filter (LPF)','Highpass Filter (HPF)','Bandpass Filter (BPF)','Adaptive Filter','Coffee Filter');
INSERT INTO webct_m (id,L1,L2,R1,R2,R3,R4) VALUES(1213,'<tt>sin(&pi;) =</tt> ?','<tt>cos(0) =</tt> ?','<tt>-1</tt>','<tt>0</tt>','<tt>1/2</tt>','<tt>1</tt>');

UPDATE stats_1 SET comment="1,3,4,2,5,6" WHERE question_id=1211;