% MATLAB code for images in WebCT

%
title = 'LPF1'
switch title
    case 'cosine-1'
title = 'cosine-1';
str = 't =-1:0.01:1.5;,wo = 2*pi/1.4;, y = 50*cos(wo*t-wo*0.6);, plot(t,y); xlabel(''TIME  (sec)''),title(''Sinusoide: x(t) = Acos(\omega_ot + \phi)''),grid(''on'')';
eval(str)   
    case 'LPF1'
        x = [-3 -0.5 -0.5 0.5 0.5 3];
        y = [0 0 1 1 0 0];
        plot(x,y)
        xlabel('\omega'),ylabel('|H|'),axis([-3 3 0 1.5])
    otherwise
        error('No appropriate title found');
end


