# Pre-Lab 7: Questions
delete from webct where id=1208;
delete from webct where id=1209;
delete from webct where id=1210;

delete from webct_mc where id=1208;
delete from webct_mc where id=1209;
delete from webct_mc where id=1210;

# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(1208,'MC','first difference filter coefficients','','Determine filter coefficient vector <tt>bb</tt> needed to implement a first-difference FIR filter in MATLAB via <pre class="MATLAB">yy = conv(bb,xx)</pre>', 4, 'lab7');
INSERT INTO webct VALUES(1209,'MC','length-3 running average filter coefficients','','Determine filter coefficient vector <tt>bb</tt> needed to implement a length-3 running-average FIR filter in MATLAB via <pre class="MATLAB">yy = conv(bb,xx)</pre>', 4, 'lab7');
INSERT INTO webct VALUES(1210,'MC','lighthouse aliasing','','In the <span class="ITS_IMG_FLOAT" onmouseover="footerIMG(this,\'<img src=images/lighthouse.png>\',1)" onmouseout="footerIMG(this,\'\',0)">lighthouse</span> image, downsampling causes aliasing which can be seen where?  (pick the best one):', 5, 'lab7');

# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason{
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1208,0,'bb = [1,1]',100,'bb = [1,-1]',0,'bb = [1,1,1]',0,'bb = [1/3,1/3,1/3]');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4) VALUES(1209,0,'bb = [1,1]',0,'bb = [1,-1]',0,'bb = [1,1,1]',100,'bb = [1/3,1/3,1/3]');
INSERT INTO webct_mc (id,weight1,answer1,weight2,answer2,weight3,answer3,weight4,answer4,weight5,answer5) VALUES(1210,100,'the fence',0,'the edges of the tower',0,'the siding of the building',0,'the clouds',0,'the grass');