# Pre-Lab 9: Questions
delete from webct where id=1214;
delete from webct_m where id=1214;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1214,'M','Z transform polynomial zeros roots','','Match the following <i>z</i>-transform polynomials <tt>H(z)</tt> with all their <b>ZEROS</b>.<br>System Functions, <tt>H(z)</tt>, on the left; <b>zeros</b> on the right.<p>In MATLAB, the functions <code>roots()</code> and <code>poly()</code> might be useful.',7,1,1,'lab09');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason
INSERT INTO webct_m (id,L1,L2,L3,L4,R1,R2,R3,R4,R5,R6,R7) VALUES(1214,'<tt>H(z) = 1 + z<sup>-2</sup></tt>','<tt>H(z) = 1 - z<sup>-2</sup></tt>','<tt>H(z) = 1 + z<sup>-4</sup></tt>','<tt>H(z) = 1 - z<sup>-4</sup></tt>','<tt>z = e<sup>j&pi;/2</sup>, e<sup>-j&pi;/2</sup></tt>','<tt>z = e<sup>j&pi;</sup>, 0</tt>','<tt>z = e<sup>j&pi;/4</sup>, e<sup>j3&pi;/4</sup>, e<sup>j5&pi;/4</sup>, e<sup>j7&pi;/4</sup></tt>','<tt>z = 1, e<sup>j&pi;/2</sup>, e<sup>j&pi;</sup>, e<sup>j3&pi;/2</sup></tt>','<tt>z = e<sup>j&pi;</sup></tt>, infinity','<tt>z = e<sup>j&pi;/2</sup>, e<sup>j&pi;</sup></tt>','<tt>z = e<sup>j&pi;</sup>, e<sup>j2&pi;</sup></tt>');
