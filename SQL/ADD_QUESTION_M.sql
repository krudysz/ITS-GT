
delete from webct where id=120;
delete from webct_m where id=120;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (120,"M","Freq Response FIR","","The frequency response of an FIR filter requires evaluation of complex-valued formulas such as<PRE class=ITS_Equation>H(&omega;) = 1 - e<sup> -j&omega;</sup> + e<sup> -j2&omega;</sup></PRE>where <B>&omega;</B> is the normalized frequency called 'omega-hat' in the text.<BR><P>Evaluate the <TT>H(&omega;)</TT> given above at each of the frequencies and match the correct answer.</P>",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (120,'At <TT>&omega; = 0, H(&omega;) =</TT> ?','<TT>H(&omega;) = 1</TT>','At <TT>&omega; = - &pi;/3, H(&omega;) =</TT> ?','<TT>H(&omega;) = 0</TT>',NULL,'<TT>H(&omega;) = 2e<SUP> j&pi;/3</SUP></TT>');

delete from webct where id=124;
delete from webct_m where id=124;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (124,"M","Freq Response FIR","","The frequency response of an FIR filter requires evaluation of complex-valued formulas such as<PRE class=ITS_Equation>H(&omega;) = 1 - e<sup> -j&omega;</sup> + e<sup> -j2&omega;</sup></PRE>where <B>&omega;</B> is the normalized frequency called 'omega-hat' in the text.<BR><P>Evaluate the <TT>H(&omega;)</TT> given above at each of the frequencies and match the correct answer.</P>",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (124,'At <TT>&omega; = &pi;, H(&omega;) =</TT> ?','<TT>H(&omega;) = 3</TT>','At <TT>&omega; = &pi;/2, H(&omega;) =</TT> ?','<TT>H(&omega;) = e<SUP> j&pi;/2</SUP></TT>',NULL,'<TT>H(&omega;) = -j</TT>');

delete from webct where id=142;
delete from webct_m where id=142;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (142,"M","Freq Response FIR","","The frequency response of an FIR filter requires evaluation of complex-valued formulas such as <PRE class=ITS_Equation>H(&omega;) = 1 + 2e<sup>-j&omega;</sup> + e<sup>-j2&omega;</sup></PRE>where <TT>&omega;</TT> is the normalized frequency called 'omega-hat' in the text.<BR><P>Evaluate the <TT>H(&omega;)</TT> given above at each of the frequencies and match the correct answer.</P>",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (142,'At <TT><B>&omega; = &pi;</B>, H(&omega;)</TT> = ? ','<TT>H(&omega;) = 0</TT>','At <tt><b>&omega; = -&pi;/2</b>, H(&omega;)</tt> = ?','<TT>H(&omega;) = 2j</TT>',NULL,'<TT>H(&omega;) = 3e<SUP>j&pi;/3</SUP></TT>');

delete from webct where id=148;
delete from webct_m where id=148;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (148,"M","Freq Response FIR","","The frequency response of an FIR filter requires evaluation of complex-valued formulas such as <PRE class=ITS_Equation>H(&omega;) = 1 + 2e<sup>-j&omega;</sup> + e<sup>-j2&omega;</sup></PRE>where <TT>&omega;</TT> is the normalized frequency called 'omega-hat' in the text.<BR><P>Evaluate the <TT>H(&omega;)</TT> given above at each of the frequencies and match the correct answer.</P>",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (148,'At <TT><B>&omega; = &pi;/2</B>, H&omega;)</TT> = ? ','<TT>H(&omega;) = 2e<SUP>-j&pi;/2</SUP></TT>','At <TT><B>&omega; = &pi;/3, </B>H(&omega;)</TT> = ?','<TT>H(&omega;) = 3e<SUP>-j&pi;/3</SUP></TT>',NULL,'<TT>H(&omega;) = -3j</SUP></TT>');

delete from webct where id=164;
delete from webct_m where id=164;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (164,"M","DLTI Demo MATLAB","","Given an input signal and a filter in the  left column (below), choose the correct output signal from the  right column.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (164,'When <TT><b>x[n] = 3cos( 2&pi;n/3 )</b></TT>, and the filter is a <B>3-pt averager</B>.','<tt>y[n] = 0</tt>','When <tt><b>x[n] = 3cos( 2&pi;n/3 )</b></tt>, and the filter is a <br><B>first difference</B> filter with coefficients <tt>{0.5,-0.5}</tt>.','<TT>y[n] = 2.6 cos( 2&pi;n/3 + &pi;/6 )</TT>',NULL,'<TT>y[n] = 0.6 cos( 2&pi;n/3 )</TT>');

delete from webct where id=283;
delete from webct_m where id=283;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (283,"M","DLTI Demo MATLAB","","Given an input signal and a filter in the  left column (below), choose the correct output signal from the  right column.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (283,'When <tt><b>x[n] = 3</b></tt>, and the filter is a <B>3-pt averager</B>.','<tt>y[n] = 3</tt>','When <tt><B>x[n] = 3cos( 2&pi;n/3 )</tt></B>, and the filter is an <BR><B>ideal high-pass</B> with cutoff at <tt>2&pi;(0.3)</tt>.','<tt>y[n] = 3 cos( 2&pi;n/3 )</tt>',NULL,'<TT>y[n] = 2.6 cos( &pi;n )</TT>');

delete from webct where id=361;
delete from webct_m where id=361;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (361,"M","Frequency Response in MATLAB","","Given the coefficients of an FIR filter as <tt>(hh)</tt> and the frequency response of this filter as <tt>(HH)</tt>, match each piece of MATLAB code on the left list with what it does from the right list. Assume the frequency vector is defined as <tt>(ww)</tt>.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (361,'plot(ww, abs(HH))','Plots the amplitude of the frequency repsonse.','plot(ww, angle(HH))','Plots the phase of the frequency repsonse.',NULL,'This is a wrong way of plotting the frequency response.');

delete from webct where id=369;
delete from webct_m where id=369;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (369,"M","Frequency Response in MATLAB","","Given the coefficients of an FIR filter as <tt>(hh)</tt> and the frequency response of this filter as <tt>(HH)</tt>, match each piece of MATLAB code on the left list with what it does from the right list. Assume the frequency vector is defined as <tt>(ww)</tt>.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (369,'HH = freqz(hh,1,ww)','Computes the frequency response of the filter with coefficients given in the vector hh.','HH = freqz(HH,1,ww)','freqz() should not use HH as an input and output.',NULL,' freqz() should not use hh as an input.');

delete from webct where id=475;
delete from webct_m where id=475;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (475,"M","Vectorizing in MATLAB","","A vector of <b>complex</b> random numbers is defined in MATLAB as:<pre class=MATLAB>aa = 2*randn(1,5) - 1.5i*randn(1,5);</pre> Match the MATLAB code with the description of what the code is doing.",4,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4) VALUES (475,"aa*aa'",'Computes the sum of the magnitude squared of all elements in <tt>aa</tt>.','(aa + conj(aa))/2','Returns a vector containing the real parts of <tt>aa</tt>.',NULL,'Returns the magnitude of each element in <tt>aa</tt>.',NULL,'Returns a vector containing the imaginary parts of <tt>aa</tt>.');

delete from webct where id=562;
delete from webct_m where id=562;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (562,"M","Vectorizing in MATLAB","","A vector of <b>complex</b> random numbers is defined in MATLAB as:<pre class=MATLAB>aa = 2*randn(1,5) - 1.5i*randn(1,5);</pre> Match the MATLAB code with the description of what the code is doing.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (562,'ones(5,1)*aa','Creates a matrix with 5 identical rows, each row containing the vector <tt>aa</tt>.','aa*ones(5,1)','Computes the sum of all elements in <tt>aa</tt>.',NULL,'Returns the vector <tt>aa</tt>.');

delete from webct where id=624;
delete from webct_m where id=624;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (624,"M","Vectorizing in MATLAB","","A vector of <B>complex</B> random numbers is defined in MATLAB as:<PRE class=MATLAB>aa = randn(1,5) - 3i*ones(1,5);</PRE>Match the MATLAB code with the description of what the code is doing.",3,1,1,'Chapter6',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (624,'aa.*(abs(aa)>0)','Returns a vector that contains all the elements in <tt>aa</tt> unmodified.','abs(aa).*abs(aa)','Returns a vector containing the magnitude squared of each element in <tt>aa</tt>.',NULL,'Returns a vector of zeros.');
