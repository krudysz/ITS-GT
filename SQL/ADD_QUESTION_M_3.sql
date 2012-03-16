
delete from webct where id=2219;
delete from webct_m where id=2219;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (2219,"M","Match time-domain to Fourier Transform","","For each of the following time&ndash;domain signals, select the correct match from the list of Fourier transforms below. <ul class=ITS_note_units><li>The operator <b>&#42;</b> denotes convoution.</li></ul>",3,1,1,'Chapter12',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (2219,'<latex>-e^{-t}u(t)\\quad+\\quad\\delta(t)</latex>','<latex>\\frac{j\\omega}{1\\quad+\\quad j\\omega}</latex>','<latex>\\delta(t-2)\\quad\\ast\\quad e^{-t+1}u(t-1)\\quad\\ast\\quad \\delta(t-1)</latex>','<latex>\\frac{e^{-j4\\omega}}{1\\quad+\\quad j\\omega}</latex>','<latex>\\int_{-\\infty}^{t} e^{-t+\\tau}\\quad\\delta(\\tau) d\\tau</latex>','<latex>\\frac{1}{1\\quad+\\quad j\\omega}</latex>');

delete from webct where id=2220;
delete from webct_m where id=2220;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (2220,"M","Match time-domain to Fourier Transform","","For each of the following time&ndash;domain signals, select the correct match from the list of Fourier transforms below. <ul class=ITS_note_units><li>The operator <b>&#42;</b> denotes convoution.</li></ul>",3,1,1,'Chapter12',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3) VALUES (2220,'<latex>u(t+4)\\quad-\\quad u(t-4)</latex>','<latex>\\frac{\\sin(4\\omega)}{\\omega/2}</latex>','<latex>\\delta(t)\\quad-\\quad \\delta(t-8)</latex>','<latex>j 2 e^{-j4\\omega}\\, \\sin(4\\omega)</latex>','<latex>e^{-(t-4)}\\quad\\delta(t-4)</latex>','<latex>e^{-j4\\omega}</latex>');

delete from webct where id=2221;
delete from webct_m where id=2221;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (2221,"M","Match time-domain to Fourier Transform","","For each of the following time&ndash;domain signals, select the correct match from the list of Fourier transforms below. <ul class=ITS_note_units><li>The operator <b>&#42;</b> denotes convoution.</li></ul>",2,1,1,'Chapter12',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2) VALUES (2221,'<latex>\\cos(\\pi t)\\quad\\ast\\quad \\delta(t-4)</latex>','<latex>e^{-j4\\omega}\\quad\\left[ \\pi\\delta(\\omega-\\pi)\\quad+\\quad\\pi\\delta(\\omega+\\pi)\\right]</latex>','<latex>u(t-3)\\quad-\\quad u(t-5)</latex>','<latex>2 e^{-j4\\omega}\\quad\\frac{\\sin(\\omega)}{\\omega}</latex>');
