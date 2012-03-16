
# MULTIPLE CHOICE
delete from webct where id=3000;
delete from webct_mc where id=3000;

# Did this course enhance your knowledge in the areas of quantitative electrophysiology and/or neural engineering? Why or why not?
# Do you see yourself pursuing subjects either directly or indirectly associated with this course? Why or why not?
# During the class discussion led by Dr. Weinsheimer from CETL many students commented that more quantitative and MATLAB-based homework would
#enhance learning the material. Given that you have seen all the course material
#can you suggest key content areas where we could introduce these exercises?
#Do you have any specific exercises to suggest?




INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (2137,"MC","","phpimg/ITS_pzplot.php?poles=0.45,0.78,0.45,-0.78&zeros=0.56,0.96,0.56,-0.96","Identify this filter.",4,1,1,'Chapter8',NULL);
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES (2137,0,"Lowpass",0,"Bandpass",0,"Notch",100,"Allpass");

# MATCHING
delete from webct where id=3003;
delete from webct_m where id=3003;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (120,"M","Freq Response FIR","","Please select three guest lecturers that you gained the <b>most</b> from.",3,1,1,'Chapter6',NULL);

The following individuals delivered a special topic lectures in the course.
<ul>
<li>Paul Hasler, PhD (Neuromorphic circuits)</li>
<li>Rosanna Esteller, PhD (NeuroPace, neural stimulation as a treatment for
epilepsy)</li>
<li>Steve Potter, PhD (Multi-electrode closed-loop interfaces to cultured networks)</li>
<li>Brian McKinnon, MD (Cochlear implants, a surgeon’s perspective)</li>
<li>Cindy Gary, AUD (Cochlear implants, an audiologist’s perspective)</li>
<li>Robert Butera, PhD (Extacellular stimulation & high frequency conduction block)</li>
<li>Garrett Stanley, PhD (Reading and writing the neural code: computations in
sensory pathways)</li>
<li>Mikhael El Chami, MD (Sudden cardiac death prevention)</li>
</ul>
INSERT INTO webct_m (id,L1,R1,L2,R2,L3,R3,L4,R4,L5,R5,L6,R6,L7,R7) VALUES (120,'At <TT>&omega; = 0, H(&omega;) =</TT> ?','<TT>H(&omega;) = 1</TT>','At <TT>&omega; = - &pi;/3, H(&omega;) =</TT> ?','<TT>H(&omega;) = 0</TT>',NULL,'<TT>H(&omega;) = 2e<SUP> j&pi;/3</SUP></TT>');

b. Please select three guest lecturers that you gained the least from.

c. Comments?

# 5
delete from webct where id=3004;
delete from webct_mc where id=3004;

INSERT INTO webct (id,qtype,title,image,question,answers,answers_config,question_config,category,tag_id) VALUES (3004,"MC","","","I have completed, or will complete, the Center for Enhancement of Teaching and Learning On-line Course Survey http://www.coursesurvey.gatech.edu by May 4, 2011.",2,1,1,'BMED/ECE_6787_Survey',NULL);
INSERT INTO webct_mc (id,answer1,answer2) VALUES (3004,"Yes","No");