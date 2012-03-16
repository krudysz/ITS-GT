
delete from webct where id=644;
delete from webct_m where id=644;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (644,"M","Match Equation to FIR Frequency Response","","Match each FIR system to its frequency response.",3,1,1,'Chapter13',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (644,'<TT>y[n] = &frac12;x[n] - &frac12;x[n-1]</TT>','<img src="RESOURCE_PATHimages/FIR_1.png" alt="RESOURCE_PATHimages/FIR_1.png">','<TT>y[n] = ( &frac12;x[n] - &frac12;x[n-1] )<sup>2</sup></TT>','<img src="RESOURCE_PATHimages/FIR_2.png">','<tt>y[n] = (1/3)( x[n] + x[n-1] + x[n-2] )</tt>','<img src="RESOURCE_PATHimages/FIR_3.png">');

delete from webct where id=946;
delete from webct_m where id=946;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (946,"M","Match System Equation to output signal","","Select the correct output signal (from the list on the right) for each signal description and input signal.",4,1,1,'Chapter13',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4) VALUES (946,'<TT>y[n] = cos(&pi;n/2) * ( u[n] - u[n-4] )</TT>','<TT>y[n] = 0</TT> for all <TT>n</TT>','<TT>x[n] = &radic;3cos(2&pi;n/3 - &pi;/2 )</ TT> and <BR><TT>y[n] = x[n-1] - x[n-3]</TT>','<TT>y[n] = 3</TT> for all <TT>n</TT>',NULL,'<TT>y[n] = &delta;[n-1] + &delta;[n-3]</TT>',NULL,'<TT>y[n] = 3cos(2&pi;n/3 + 2&pi;n/3)</TT> for all <TT>n</TT>');

delete from webct where id=1039;
delete from webct_m where id=1039;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1039,"M","Match System Equation to output signal","","Select the correct output signal (from the list on the right) for each signal description and input signal.",4,1,1,'Chapter13',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4) VALUES (1039,'<TT>yy = conv( [0,1,0,-1] , [0,1,0,0,0] )</TT>','<TT>y[n] = &delta;[n-3]</TT>','<TT>y[n] = ( &delta;[n-1] - &delta;[n-2] ) * ( &delta;[n] + &delta;[n-1] )</TT>','<TT>y[n] = &delta;[n-1] - &delta;[n-3]</TT>',NULL,'<TT>y[n] = &delta;[n-2] - &delta;[n-4]</TT>',NULL,'<TT>y[n] = &delta;[n-3] - &delta;[n-5]</TT>');

delete from webct where id=1089;
delete from webct_m where id=1089;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1089,"M","Match System Equation to frequency response","","Select the correct frequency response (from the list on the right) for each time-domain description.",4,1,1,'Chapter13',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4) VALUES (1089,"<latex>y[n]=\\sum_{k=0}^{3} x[n-k]</latex>",'<latex>H(e^{j\\hat\\omega})=\\frac{\\sin(\\hat\\omega)}{\\sin(\\frac{1}{2}\\hat\\omega)}e^{-j\\hat\\omega/2}</latex>','<tt>h[n] = u[n] - u[n - 2]</tt>','<latex>H(e^{j\\hat\\omega})=e^{-j\\hat\\omega}(1+2\\cos(\\hat\\omega))</latex>',NULL,'<latex>H(e^{j\\hat\\omega})=\\frac{\\sin(2\\hat\\omega)}{\\sin(\\frac{1}{2}\\hat\\omega)}e^{-j3\\hat\\omega/2}</latex>',NULL,'<latex>H(e^{j\\hat\\omega})=1-e^{-j2\\hat\\omega}</latex>');

delete from webct where id=1096;
delete from webct_m where id=1096;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (1096,"M","Match System Equation to frequency response","","Select the correct frequency response (from the list on the right) for each time-domain description.",4,1,1,'Chapter13',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4) VALUES (1096,'<latex>y[n]=\\sum_{k=0}^{3}x[n-k]</latex>','<latex>H(e^{j\\hat\\omega})=\\frac{\\sin(\\hat\\omega)}{\\sin(\\frac{1}{2}\\hat\\omega)}e^{-j\\hat\\omega/2}</latex>','<latex>h[n] = u[n] - u[n - 2]</latex>','<latex>H(e^{j\\hat\\omega})=e^{-j\\hat\\omega}(1+2\\cos(\\hat\\omega))</latex>',NULL,'<latex>H(e^{j\\hat\\omega})=\\frac{\\sin(2\\hat\\omega)}{\\sin(\\frac{1}{2}\\hat\\omega)}e^{-j3\\hat\\omega/2}</latex>',NULL,'<latex>H(e^{j\\hat\\omega})=1-e^{-j2\\hat\\omega}</latex>');
