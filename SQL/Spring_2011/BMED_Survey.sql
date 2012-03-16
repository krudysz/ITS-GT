# Spring 2011 
# BMED/ECE 6787 
# Survey | Prof. Bhatti

# 0
delete from webct where id=2999;
delete from webct_mc where id=2999;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (2999,"MC","BMED/ECE 6787 Spring 2011 Survey","","<center><b>Spring 2011 BMED/ECE 6787 Survey</b></center><ul><li>Your response to this survey is anonymous and will not be viewed by the instructor until after the final grades have been submitted.</li><li>Please answer <b>all</b> 7 questions.</li><li>You can answer each question only once and questions cannot be skipped.</li></ul>",1,1,1,'BMED6787',NULL);
INSERT INTO webct_mc (id,answer1) VALUES (2999,"I have read the above instructions.");

# 1
delete from webct where id=3000;
delete from webct_p where id=3000;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3000,"P","BMED/ECE 6787 Spring 2011 Survey","","Did this course enhance your knowledge in the areas of quantitative electrophysiology and/or neural engineering? Why or why not?",1,1,1,'BMED6787',NULL);
INSERT INTO webct_p (id,answer) VALUES (3000,NULL);

# 2
delete from webct where id=3001;
delete from webct_p where id=3001;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3001,"P","BMED/ECE 6787 Spring 2011 Survey","","Do you see yourself pursuing subjects either directly or indirectly associated with this course? Why or why not?",1,1,1,'BMED6787',NULL);
INSERT INTO webct_p (id,answer) VALUES (3001,NULL);

# 3
delete from webct where id=3002;
delete from webct_p where id=3002;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3002,"P","BMED/ECE 6787 Spring 2011 Survey","","During the class discussion led by Dr. Weinsheimer from CETL many students commented that more quantitative and MATLAB-based homework would enhance learning the material. Given that you have seen all the course material can you suggest key content areas where we could introduce these exercises? Do you have any specific exercises to suggest?",1,1,1,'BMED6787',NULL);
INSERT INTO webct_p (id,answer) VALUES (3002,NULL);

# 4
delete from webct where id=3003;
delete from webct_m where id=3003;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3003,"M","BMED/ECE 6787 Spring 2011 Survey","","In the list on the right-hand side, the following individuals delivered a special topic lectures in the course.  Please select three guest lecturers that you gained the <b>most</b> from.",8,1,1,'BMED6787',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4,L5,R5,L6,R6,L7,R7,L8,R8) VALUES (3003,'1<sup>st</sup>','Paul Hasler, PhD (Neuromorphic circuits)','2<sup>nd</sup>','Rosanna Esteller, PhD (NeuroPace, neural stimulation as a treatment for epilepsy)','3<sup>rd</sup>','Steve Potter, PhD (Multi-electrode closed-loop interfaces to cultured networks)',NULL,'Brian McKinnon, MD (Cochlear implants, a surgeon&rsquo;s perspective)',NULL,'Cindy Gary, AUD (Cochlear implants, an audiologist&rsquo;s perspective)',NULL,'Robert Butera, PhD (Extacellular stimulation & high frequency conduction block)',NULL,'Garrett Stanley, PhD (Reading and writing the neural code: computations in sensory pathways)',NULL,'Mikhael El Chami, MD (Sudden cardiac death prevention)');

# 5
delete from webct where id=3004;
delete from webct_m where id=3004;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3004,"M","BMED/ECE 6787 Spring 2011 Survey","","In the list on the right-hand side, the following individuals delivered a special topic lectures in the course.  Please select three guest lecturers that you gained the <b>least</b> from.",8,1,1,'BMED6787',NULL);
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4,L5,R5,L6,R6,L7,R7,L8,R8) VALUES (3004,'1<sup>st</sup>','Paul Hasler, PhD (Neuromorphic circuits)','2<sup>nd</sup>','Rosanna Esteller, PhD (NeuroPace, neural stimulation as a treatment for epilepsy)','3<sup>rd</sup>','Steve Potter, PhD (Multi-electrode closed-loop interfaces to cultured networks)',NULL,'Brian McKinnon, MD (Cochlear implants, a surgeon&rsquo;s perspective)',NULL,'Cindy Gary, AUD (Cochlear implants, an audiologist&rsquo;s perspective)',NULL,'Robert Butera, PhD (Extacellular stimulation & high frequency conduction block)',NULL,'Garrett Stanley, PhD (Reading and writing the neural code: computations in sensory pathways)',NULL,'Mikhael El Chami, MD (Sudden cardiac death prevention)');

# 6
delete from webct where id=3005;
delete from webct_p where id=3005;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3005,"P","BMED/ECE 6787 Spring 2011 Survey","","The following individuals delivered a special topic lectures in the course.<ul><li>Paul Hasler, PhD (Neuromorphic circuits)</li><li>Rosanna Esteller, PhD (NeuroPace, neural stimulation as a treatment for epilepsy)</li><li>Steve Potter, PhD (Multi-electrode closed-loop interfaces to cultured networks)</li><li>Brian McKinnon, MD (Cochlear implants, a surgeon&rsquo;s perspective)</li><li>Cindy Gary, AUD (Cochlear implants, an audiologist&rsquo;s perspective)</li><li>Robert Butera, PhD (Extacellular stimulation & high frequency conduction block)</li><li>Garrett Stanley, PhD (Reading and writing the neural code: computations in sensory pathways)</li><li>Mikhael El Chami, MD (Sudden cardiac death prevention)</li></ul>Do you have any specific comments on these lectures or guest lecturers.",1,1,1,'BMED6787',NULL);
INSERT INTO webct_p (id,answer) VALUES (3005,NULL);

# 7
delete from webct where id=3006;
delete from webct_mc where id=3006;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3006,"MC","BMED/ECE 6787 Spring 2011 Survey","",'I have completed, or will complete, the Center for Enhancement of Teaching and Learning On-line Course Survey <a href="http://www.coursesurvey.gatech.edu" target="_blank">www.coursesurvey.gatech.edu</a> by May 4, 2011.',2,1,1,'BMED6787',NULL);
INSERT INTO webct_mc (id,answer1,answer2) VALUES (3006,"Yes","No");