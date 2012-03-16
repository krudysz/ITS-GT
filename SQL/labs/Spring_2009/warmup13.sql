# Lab 12B: Warm-up Questions
# Insert into webct table
# id | qtype | title | image | question | answers | category
INSERT INTO webct VALUES(2111,'P','Notch Filter Removes Sinusoidal Interference','','Run <tt>ECGmake09</tt> and note the values for the sampling rate <tt>(f<sub>s</sub>)</tt> and interference frequency <tt>(f<sub>int</sub>)</tt>.', 1, 'Warm-up13');
INSERT INTO webct VALUES(2112,'P','Notch Filter Removes Sinusoidal Interference','','Design the notch filter by specifying its poles and zeros. Explain how the angle <tt>(&theta;)</tt> of the poles and zeros is determined by the interference frequency <tt>(f<sub>int</sub>)</tt> and the sampling rate <tt>(f<sub>s</sub>)</tt>. Draw the pole-zero diagram on the verification sheet.', 1, 'Warm-up13');
INSERT INTO webct VALUES(2113,'P','Notch Filter Removes Sinusoidal Interference','','Compute the frequency response of the notch filter, and plot the frequency response magnitude versus <i><b>analog frequency</b></i> <tt>f</tt> in Hz. Make a sketch of what you see on the screen (no print out is needed).', 1, 'Warm-up13');
INSERT INTO webct VALUES(2114,'P','Notch Filter Removes Sinusoidal Interference','','Convert the poles and zeros to filter coefficients.', 1, 'Warm-up13');
INSERT INTO webct VALUES(2115,'P','Notch Filter Removes Sinusoidal Interference','','Apply the notch filter to the ECG signal and show that you can remove all the interference. Make a two panel subplot  comparing the time-domain signals <i>before</i> and <i>after</i> filtering.  Show three or four periods of the ECG at the same time in both signals.', 0, 'Warm-up13');
INSERT INTO webct VALUES(2116,'P','Notch Filter Removes Sinusoidal Interference','','Show spectrograms of the signal before and after nulling.  Point out where you see the interference in the input signal&rsquo;s spectrogram, and also where it has been removed in the output spectrogram.', 1, 'Warm-up13');

# SURVEY
INSERT INTO webct VALUES(2117,'MC','Lab Partner','','Working with a lab partner was beneficial. Benefits might include sharing the workload, learning more, meeting new friends, etc.', 5, 'Survey');
# Insert into webct table
# id | feedback | weight{n} | answer{n} | reason{n}
INSERT INTO webct_mc (id,answer1,answer2,answer3,answer4,answer5) VALUES(2117,'Strongly Agree','Agree','Do not agree or disagree','Disagree','Strongly Disagree');
