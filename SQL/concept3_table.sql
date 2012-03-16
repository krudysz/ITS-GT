CREATE TABLE concept3(
    id              int UNIQUE,
    name            varchar(64),
    node_number     int,
    chapter_number  int,
		code 						varchar(256),
    concept_file    varchar(64),
    book_ref        varchar(32),
    parents         varchar(128),

    PRIMARY KEY (id)
);
INSERT INTO `concept3` VALUES
(1001,'signal',NULL,1,NULL,NULL,'[1-1]',NULL),
(1002,'system',NULL,1,NULL,NULL,'[5-1]','signal'),
(1003,'signal:CT',NULL,1,'$s(t)$   ',NULL,NULL,'signal '),
(1004,'sampling',NULL,1,'$s[n] = s(nT_{s}) \\quad n\\thinspace\\epsilon\\thinspace \\mathbb Z$ ',NULL,NULL,'signal:CT'),
(1005,'sampling:period',NULL,1,'$T_{s}$ ',NULL,NULL,'sampling'),
(1006,'signal:DT',NULL,1,'$s[n] = s(nT_{s}) \\quad n\\thinspace\\epsilon\\thinspace \\mathbb Z$  ',NULL,'[1-2]','signal:CT'),
(1007,'signal:CT:image',NULL,1,'$p(x,y)$ ',NULL,'[1-3]','signal:CT '),
(1008,'signal:DT:image',NULL,1,'$p[m,n] = p(m \\triangle _{x},n \\triangle _{y}) \\quad m,n\\thinspace\\epsilon\\thinspace \\mathbb Z$ ',NULL,'[1-3]','signal:CT:image'),
(1009,'signal:CT:video',NULL,1,'$v(x,y,t)$ ',NULL,NULL,'signal:CT:image'),
(1010,'system:CT',NULL,1,'$y(t) = \\tau\\{x(t)\\}$ ',NULL,'(1.1)[1-5]','system '),
(1011,'system:DT',NULL,1,'$y[n] = \\tau\\{x[n]\\}$ ',NULL,NULL,'system'),
(1012,'block diagram',NULL,1,NULL,NULL,'[1-5]','system'),
(2001,'sin',NULL,2,'$\\sin(\\theta)=\\frac{y}{r} \\quad y=r\\sin(\\theta)$ ',NULL,'[2-4]',NULL),
(2002,'cos',NULL,2,'$\\cos(\\theta)=\\frac{x}{r} \\quad y=r\\cos(\\theta)$ ',NULL,'[2-4]',NULL),
(2003,'sin:props',NULL,2,NULL,NULL,'\\{2-1\\}','sin'),
(2004,'cos:props',NULL,2,NULL,NULL,'\\{2-1\\}','sin'),
(2005,'sin:derivative',NULL,2,'$\\frac{d \\sin(\\theta)}{d\\theta}=\\cos(\\theta)$   ',NULL,NULL,'sin'),
(2006,'cos:derivative',NULL,2,'$\\frac{d \\cos(\\theta)}{d\\theta}=-\\sin(\\theta)$ ',NULL,NULL,'sin'),
(2007,'sine:identities',NULL,2,NULL,NULL,'\\{2-2\\}','sin'),
(2008,'cos:identities',NULL,2,NULL,NULL,'\\{2-2\\}','sin'),
(2009,'sinusoid',NULL,2,'$x(t)=A\\cos(\\omega_{o}t+\\phi)$ ',NULL,'( 2.1,2.2) [2-1]','sin'),
(2010,'sinusoid:amplitude',NULL,2,'$A$ ',NULL,NULL,'sinusoid'),
(2011,'sinusoid:frequency:radian',NULL,2,'$\\omega_{o}=2\\pi f_{o}=2\\pi/T_{o}$ ',NULL,NULL,'sinusoid'),
(2012,'sinusoid:frequency:cyclic',NULL,2,'$f_{o}=\\omega_{o}/{2\\pi}$          ',NULL,NULL,'sinusoid:frequency:radian'),
(2013,'sinusoid:period',NULL,2,'$T_{o}=1/f_{o}$  ',NULL,'(2.4)','sinusoid:frequency:cyclic '),
(2014,'sinusoid:phase',NULL,2,'$\\phi$ ',NULL,NULL,'sinusoid'),
(2015,'sampling:period',NULL,2,'$T_{s}$ ',NULL,NULL,'sampling$^1$'),
(2016,'time-shift:CT',NULL,2,'$x(t): x(t-t_{o})$ ',NULL,NULL,'signal:CT$^1$'),
(2017,'sinusoid:time-shift',NULL,2,'$cos(\\omega_o (t-t_1))=cos(\\omega_o -\\phi) \\quad \\phi = -2\\pi(\\frac{t_1}{T_o})$ ',NULL,'(2.7)','sinusoid:phase'),
(2018,'reducing mod$2\\pi$',NULL,2,'$mod (\\phi,2\\pi)$ ',NULL,NULL,'sinusoid:time-shift'),
(2019,'principle value',NULL,2,'$-\\pi<\\phi<\\pi$ ',NULL,NULL,'reducing mod$2\\pi$'),
(2020,'complex plane',NULL,2,'$\\text{Domain} := \\Re e\\{z\\},\\text{Range} := \\Im m\\{z\\}$ ',NULL,'[2-10]',NULL),
(2021,'complex number:CF',NULL,2,'$z = x+jy$ ',NULL,NULL,'complex plane'),
(2022,'complex number:PF',NULL,2,'$z = re^{j\\theta} = r\\cos({\\theta})+jr\\sin(\\theta)$ ',NULL,'(2.11)[1-5]','complex number:CF'),
(2023,'complex:CF:magnitude',NULL,2,'$|z|=\\sqrt{zz^*}=\\sqrt{x^{2}+y^{2}} $ ',NULL,'(2.9)','complex number:CF'),
(2024,'complex:CF:argument',NULL,2,'$\\angle\\theta = \\arctan(\\frac{y}{x})$ ',NULL,'(2.9)','complex number:CF'),
(2025,'complex:PF:magnitude',NULL,2,'$|z| = r$ ',NULL,NULL,'complex number:PF'),
(2026,'complex:PF:argument',NULL,2,'$\\angle\\theta = \\theta$ ',NULL,NULL,'complex number:PF'),
(2027,'conjugate',NULL,2,'$z^*=x-jy \\quad , z^*=re^{-j\\theta}$ ',NULL,'A-4','complex number:CF'),
(2028,'CE',NULL,2,'$e^{j\\theta} = \\cos({\\theta})+j\\sin(\\theta)$ ',NULL,'(2.10)','complex number:PF'),
(2029,'CE:signal',NULL,2,'$z(t) = Ae^{j\\omega_{o}t+\\phi}=Xe^{j\\omega_{o}t}$ ',NULL,'( 2.12,2.16)','complex number:PF'),
(2030,'phasor',NULL,2,'$X = Ae^{j\\phi}$ ',NULL,'(2.15)','CE:signal'),
(2031,'sin:CE',NULL,2,'$\\sin(\\theta)=\\frac{e^{j\\theta}-e^{-j\\theta}}{2j}$ ',NULL,'(2.17)','sin'),
(2032,'cos:CE',NULL,2,'$\\cos(\\theta)=\\frac{e^{j\\theta}+e^{-j\\theta}}{2}$ ',NULL,'(2.18)','cos'),
(2033,'sinusoid:CE',NULL,2,'$A\\cos(\\omega_{o}t+\\phi)=A\\frac{e^{j(\\omega_{o}t+\\phi)}+e^{-j(\\omega_{o}t+\\phi)}}{2}$ ',NULL,NULL,'sinusoid'),
(2034,'phasor:addition',NULL,2,'$\\sum\\limits_{k=1}^{N} A_{k}\\cos(\\omega_{o}t+\\phi_{k})=A\\cos(\\omega_{o}t+\\phi)$ ',NULL,'(2.19)','sinusoid'),
(2035,'phasor:addition:CE',NULL,2,'$\\sum\\limits_{k=1}^{N} A_{k}e^{j\\phi_{k}}=Ae^{j\\phi}$ ',NULL,'(2.22)','phasor'),
(3001,'SS',NULL,3,'$x(t) = A_{o}+\\sum\\limits_{k=1}^{N} A_{k}\\cos(2\\pi f_{k}t+\\phi_{k})$     ',NULL,'(3.1)','sinusoid$^2$'),
(3002,'SS:CE',NULL,3,'$x(t) = X_{o}+ \\sum\\limits_{k=1}^{N} \\mathfrak{R}e \\{ X_{k} e^{j2\\pi f_{k}t} \\}$     ',NULL,'(3.2)','SS'),
(3003,'FD',NULL,3,'$(f_{k},\\frac{1}{2}X_{k}),(-f_{k},\\frac{1}{2}X_{k}^{*})$ pairs ',NULL,'(3.4)','SS:CE'),
(3004,'FD:DC',NULL,3,'$(0,X_{0}=A_{0})$  ',NULL,NULL,'FD'),
(3005,'spectrum:plot',NULL,3,NULL,NULL,NULL,'FD'),
(3006,'SS:beat note',NULL,3,'$x(t)=\\cos(2\\pi f_{1}t)+\\cos(2\\pi f_{2}t)=2\\cos(2\\pi f_{\\triangle}t)\\cos(2\\pi f_{c}t)$ ',NULL,'(3.10,3.11)','SS'),
(3007,'beat note:center frequency',NULL,3,'$f_{c}=\\frac{1}{2}(f_{1}+f_{2})$ ',NULL,NULL,'SS:beat note'),
(3008,'beat note:deviation frequency',NULL,3,'$f_{\\triangle}=\\frac{1}{2}(f_{2}-f_{1})$ ',NULL,NULL,'SS:beat note'),
(3009,'signal:AM',NULL,3,'$x(t)=v(t)\\cos(2\\pi f_{c}t)$ ',NULL,'(3.13)','SS:beat note'),
(3010,'AM:carrier frequency',NULL,3,'$f_{c}$ ',NULL,NULL,'signal:AM'),
(3011,'signal:periodic',NULL,3,'$x(t+T_{o}) = x(t)$ ',NULL,NULL,'signal$^1$'),
(3012,'signal:period:fundamental',NULL,3,'$smallest \\quad T_{o}$ ',NULL,NULL,'signal:periodic'),
(3013,'frequency:harmonic',NULL,3,'$f_{k}=kf_{o}$ ',NULL,NULL,'sinusoid:frequency:cyclic$^2$'),
(3014,'frequency:fundamental',NULL,3,'$f_{o}=\\frac{1}{T_{o}} \\quad , f_{o}=\\gcd\\{f_{k} \\} \\quad , largest \\, f_{o} \\, : \\, f_{k}=kf_{o}$ ',NULL,NULL,'signal:period:fundamental'),
(3015,'SS:harmonic',NULL,3,'$x(t) = A_{o}+\\sum\\limits_{k=1}^{N} A_{k}\\cos(2\\pi f_{o}t+\\phi_{k})$ ',NULL,'(3.17)','SS'),
(3016,'SS:harmonic:CE',NULL,3,'$x(t) = \\sum\\limits_{k=-N}^{N} a_{k}e^{j2\\pi k f_{o}t} = a_{o}+ 2\\mathfrak{R}e\\{\\sum\\limits_{k=1}^{N}a_{k} e^{j2\\pi k f_{o}t} \\}$ ',NULL,'(3.18)','SS:harmonic'),
(3017,'FS:synthesis',NULL,3,'$x(t) = \\sum\\limits_{k=-\\infty}^{\\infty} a_{k}e^{j(2\\pi/T_{o}) kt}$ ',NULL,'(3.19)','SS:harmonic:CE'),
(3018,'CS',NULL,3,'$a_{-k} = a_{k}^{*}$ ',NULL,NULL,'conjugate$^2$'),
(3019,'FS:synthesis:CS',NULL,3,'$x(t) = A_{o}+\\sum\\limits_{k=1}^{N} A_{k}\\cos((2\\pi/T_{o})kt+\\phi_{k})$ ',NULL,'(3.20)','FS:synthesis'),
(3020,'FS:analysis',NULL,3,'$a_{k}=\\frac{1}{T_{o}}\\int\\limits_{0}^{T_{o}} x(t)e^{-j(2\\pi/T_{o}) kt}dt$ ',NULL,'(3.21)','FS:synthesis:CS'),
(3021,'FS:analysis:DC',NULL,3,'$a_{o}=\\frac{1}{T_{o}}\\int\\limits_{0}^{T_{o}} x(t) dt$ ',NULL,'(3.22)','FS:analysis'),
(3022,'orthognality property',NULL,3,'$\\int\\limits_{0}^{T_{o}} v_{k}(t)v_{l}^{*}(t)dt = \\left\\{\\begin{array}{ll} 0 \\sep \\text{if } k\\neq l \\\\ T_{o} \\sep \\text{if } k = l \\end{array} \\right.$ ',NULL,'(3.25)',NULL),
(3023,'signal:periodic:square wave',NULL,3,'$s(t) = \\left\\{\\begin{array}{ll} 1 \\sep \\text{for} 0\\leq t\\leq\\frac{1}{2}T_{o} \\\\ 0 \\sep \\text{for } \\frac{1}{2}T_{o}\\leq t\\leq T_{o}\\end{array} \\right.$ ',NULL,'(3.31)[ 3-15]','signal:periodic'),
(3024,'signal:periodic:triangle wave',NULL,3,'$x(t) = \\left\\{\\begin{array}{ll}2t/T_{o}\\sep \\text{for } 0\\leq t\\leq\\frac{1}{2}T_{o} \\\\2(T_{o}-t)/T_{o} \\sep \\text{for}\\frac{1}{2}T_{o}\\leq t\\leq T_{o}\\end{array} \\right.$ ',NULL,'(3.31)[3-18]','signal:periodic'),
(3025,'signal:FM',NULL,3,'$x(t)=A\\cos(\\psi(t))$ ',NULL,'(3.43)','signal$^1$'),
(3026,'FM:angle function',NULL,3,'$\\psi(t)= 2\\pi\\mu t^{2}+2\\pi F_{o}t+\\phi$ ',NULL,'(3.44)','signal:FM'),
(3027,'FM:instantaneous frequency',NULL,3,'$\\omega_{i}(t)=\\frac{d}{dt}\\psi(t)$ ',NULL,'(3.45)','FM:angle function'),
(4001,'sampling:rate',NULL,4,'$f_{s} = \\frac{1}{T_{s}}$ ',NULL,NULL,'sampling:period$^1$'),
(4002,'frequency:DT',NULL,4,'$\\hat{\\omega}=\\omega T_{s}=\\frac{2\\pi f}{f_s}$ ',NULL,'(4.4)','sampling:period$^1$'),
(4003,'sinusoid:DT',NULL,4,'$x[n]=A\\cos(\\hat{\\omega}t+\\phi)$  ',NULL,'(4.3)','frequency:DT '),
(4004,'aliasing',NULL,4,NULL,NULL,NULL,'shannon sampling theorem'),
(4005,'principal alias',NULL,4,'$\\text{frequencies in} -\\pi <\\hat{\\omega}\\leq \\pi$  ',NULL,NULL,'aliasing'),
(4006,'sinusoid:alias',NULL,4,'$\\hat{\\omega}_o, \\hat{\\omega}_o +2\\pi l,2\\pi l-\\hat{\\omega}_o \\quad l\\thinspace\\epsilon\\thinspace \\mathbb Z $ ',NULL,'(4.8)','aliasing'),
(4007,'shannon sampling theorem',NULL,4,'$f_{s} > 2f_{max} \\quad ,0 \\leq f_k \\leq f_{max}$ ',NULL,NULL,'sampling:rate'),
(4008,'nyquist rate',NULL,4,'$2f_{max}$ ',NULL,NULL,'shannon sampling theorem'),
(4009,'D-C converter:sinusoid',NULL,4,'$y(t)=y[n]|_{n=f_{s}t} \\quad -\\infty<n<\\infty$ ',NULL,'(4.11)[4-6]','sampling:rate'),
(4010,'frequency:analog',NULL,4,'$\\omega = \\hat{\\omega}f_{s} \\quad -\\frac{1}{2}f_{s}<\\omega<\\frac{1}{2}f_{s}$ ',NULL,'(4.12)','frequency:DT'),
(4011,'system:C-D-C',NULL,4,NULL,NULL,'[4-7]','frequency:analog'),
(4012,'sampling:over-sampling',NULL,4,'$f_{s} \\gg 2f_{max}$ ',NULL,NULL,'sampling:rate'),
(4013,'sampling:under-sampling',NULL,4,'$f_{s} < 2f_{max}$ ',NULL,NULL,'sampling:rate'),
(4014,'D-C converter',NULL,4,'$y(t) = \\sum\\limits_{n=-\\infty}^{\\infty} y[n]p(t-nT_{s})$ ',NULL,'(4.19)','sampling$^1$ '),
(4015,'D-C converter:pulse',NULL,4,'$p(t)$ ',NULL,NULL,'D-C converter'),
(4016,'pulse:zero-order hold',NULL,4,'$p(t)= \\left\\{\\begin{array}{ll} 1 \\sep -\\frac{1}{2}T_{s}< t\\leq\\ frac{1}{2}T_{s} \\\\ 0 \\sep  \\text{otherwise}\\end{array}\\right.$ ',NULL,'(4.20)','D-C converter'),
(4017,'pulse:linear',NULL,4,'$p(t)= \\left\\{\\begin{array}{ll} 1-|t|/T_{s} \\sep -T_{s}\\leq t\\leq\\ T_{s}\\\\ 0 \\sep \\text{otherwise}\\end{array}\\right.$ ',NULL,'(4.21)','D-C converter '),
(4018,'pulse:cubic spline',NULL,4,'$p(t)=0 \\text{ for } t=\\pm T_{s}, \\pm 2T_{s}$ ',NULL,NULL,'D-C converter '),
(4019,'pulse:ideal bandlimited',NULL,4,'$p(t) =\\frac{\\sin\\frac{\\pi}{T_{s}}t}{\\frac{\\pi}{T_s}t} \\text{ for } -\\infty<t<\\infty$ ',NULL,'(4.22)','D-C converter '),
(4020,'signal:bandlimited',NULL,4,'$x(t) = \\sum\\limits_{k=0}^{N} A\\cos(2\\pi f_{k}t+\\phi_{k})\\quad 0 \\leq f_k \\leq f_{max}$ ',NULL,'(4.23/24)','SS$^3$'),
(5001,'signal support',NULL,5,'$y[n] \\neq 0 \\quad \\text{for some } n$ ',NULL,NULL,NULL),
(5002,'difference equation',NULL,5,'$y[n] = \\frac{1}{N}\\sum\\limits_{k=0}^N x[k]$ ',NULL,'(5.1)','system:DT$^1$'),
(5003,'causal filter',NULL,5,'$h[n]=0 \\quad \\forall \\thinspace n < 0$ ',NULL,NULL,'system:DT$^1$'),
(5004,'causal running averager',NULL,5,'$y[n] = \\frac{1}{N} \\sum\\limits_{k=0}^N x[n-k]$    ',NULL,'(5.2)','difference equation'),
(5005,'FIR:system',NULL,5,'$y[n] = \\sum\\limits_{k=0}^M b_{k}x[n-k]$ ',NULL,'(5.3)','causal running averager'),
(5006,'FIR:filter:order',NULL,5,'$M$ ',NULL,NULL,'FIR:system'),
(5007,'FIR:filter:length',NULL,5,'$L = M+1$ ',NULL,NULL,'FIR:filter:order'),
(5008,'unit impulse',NULL,5,'$\\delta [n] = 1 \\text{ if } n=1 $ ',NULL,'(5.6)','FL signal'),
(5009,'FL signal',NULL,5,'$x[n] = \\sum\\limits_{k} x[k]\\delta [n-k]$ ',NULL,NULL,'signal support'),
(5010,'FIR:impulse response',NULL,5,'$h[n] = \\sum\\limits_{k=0}^{M} b_{k}\\delta[n-k] = b_{n} \\text{ for } n = 0, 1, ... M$ ',NULL,'[5-8]','FL signal'),
(5011,'delay system',NULL,5,'$y[n] = x[n-n_{o}]$ delay by $n_{o}$ ',NULL,'(5.9)','difference equation'),
(5012,'conv:sum:finite',NULL,5,'$y[n] = \\sum\\limits_{k=0}^{M} h[k]x[n-k]$ ',NULL,'(5.10)','FIR:impulse response'),
(5013,'block diagram:multiplier',NULL,5,'$y[n] = \\beta x[n]$ ',NULL,'[5-12(a)]','block diagram$^1$'),
(5014,'block diagram:adder',NULL,5,'$y[n] = x_{1}[n] + x_{2}[n]$ ',NULL,'[5-12(b)]','block diagram$^1$'),
(5015,'block diagram:unit delay',NULL,5,'$y[n] = x[n-1]$ ',NULL,'[5-12(c)]','block diagram$^1$'),
(5016,'FIR:block diagram',NULL,5,NULL,NULL,'[5-13]','block diagram:multiplier'),
(5017,'time invariance',NULL,5,'$x[n-n_{o}] \\mapsto y[n-n_{o}]$ ',NULL,'(5.15)','delay system '),
(5018,'linearity',NULL,5,'$\\text{If } x_{1}[n] \\mapsto y_{1}[n] \\text{ and } x_{2}[n] \\mapsto y_{2}[n]$ , then ',NULL,NULL,NULL),
(5019,'conv:sum',NULL,5,'$y[n] = \\sum\\limits_{l=-\\infty}^{\\infty} x[l]h[n-l] = x[n]\\ast h[n]$  ',NULL,'(5.23)','conv:sum:finite'),
(5020,'LTI system',NULL,5,'$y[n] = x[n] \\ast h[n]$ ',NULL,NULL,'time invariance'),
(5021,'conv:with impulse',NULL,5,'$x[n]\\ast \\delta [n-n_{o}] = x[n-n_{o}]$ ',NULL,'(5.24)','conv:sum'),
(5022,'conv:commutative Prop',NULL,5,'$x[n]\\ast h[n] = h[n]\\ast x[n]$ ',NULL,'(5.25)','conv:sum'),
(5023,'conv:associative Prop',NULL,5,'$(x_{1}[n]\\ast x_{2}[n]) \\ast x_{3}[n] = x_{1}[n]\\ast (x_{2}[n] \\ast x_{3}[n])$ ',NULL,NULL,'conv:sum'),
(5024,'LTI system:cascaded',NULL,5,'$h[n] = h_{1}[n] \\ast h_{2}[n]$ ',NULL,'[5-19/20]','LTI system '),
(6001,'CE:signal:discrete',NULL,6,'$x[n] = Ae^{j\\phi}e^{\\jw n}$    ',NULL,NULL,'frequency:DT$^4$'),
(6002,'FIR:FR',NULL,6,'$H(e^{\\jw}) = \\sum\\limits_{k=0}^M b_{k} e^{-\\jw k} = \\sum\\limits_{k=0}^M h[k] e^{-\\jw k}$ ',NULL,'(6.4)','FIR:system$^5$'),
(6003,'FIR:output',NULL,6,'$y[n] = (A|H(e^{\\jw})|) \\cdot e^{j\\angle( H(e^{\\jw})+\\phi)} e^{\\jw n}$ ',NULL,'(6.5)','FIR:FR'),
(6004,'FIR:gain',NULL,6,'$|H(e^{\\jw})|$ ',NULL,NULL,'FIR:output'),
(6005,'FIR:FR:superposition',NULL,6,'$y[n]=H(e^{j0})X_{o}+\\sum\\limits_{k=1}^N (H(e^{\\jw_{k}})\\frac{X_k}{2}e^{\\jw_k n} + H(e^{-\\jw_{k}})\\frac{X_k^*}{2}e^{-\\jw_k n})$ ',NULL,'(6.7)','FIR:output '),
(6006,'TD',NULL,6,NULL,NULL,NULL,NULL),
(6007,'FIR:transient region',NULL,6,NULL,NULL,NULL,'FIR:output'),
(6008,'FIR:steady-state region',NULL,6,NULL,NULL,NULL,'FIR:output'),
(6009,'TD2FD',NULL,6,'$h[n] = \\sum\\limits_{k=0}^M b_k \\delta [n-k] \\leftrightarrow H(e^{\\jw})=\\sum\\limits_{k=0}^M h[k]e^{-\\jw k}$ ',NULL,NULL,'TD'),
(6010,'FIR:FR:periodicity',NULL,6,NULL,NULL,NULL,'FIR:FR'),
(6011,'FIR:FR:CS',NULL,6,'$H(e^{-\\jw})=H^{*}(e^{\\jw})$ ',NULL,'(6.16)','FIR:FR '),
(6012,'FIR:FR:delay system',NULL,6,NULL,NULL,NULL,'delay system$^5$'),
(6013,'FIR:FR:first-diff system',NULL,6,NULL,NULL,NULL,'FIR:FR:delay system'),
(6014,'highpass filter',NULL,6,NULL,NULL,NULL,'lowpass filter'),
(6015,'lowpass filter',NULL,6,NULL,NULL,NULL,NULL),
(6016,'FIR:FR:cascade LTI system',NULL,6,'$h_{1}[n]\\ast h_{2}[n] \\leftrightarrow H_{1}(e^{\\jw})H_{2}(e^{\\jw})$ ',NULL,'(6.20)','TD2FD '),
(6017,'FIR:FR:running-average filter',NULL,6,'$H(e^{\\jw}) = \\frac{1}{L}\\sum\\limits_{k=0}^{L-1}e^{-\\jw k}$ ',NULL,NULL,'TD2FD'),
(6018,'geometric series',NULL,6,'$\\sum\\limits_{k=0}^{L-1}\\alpha ^k = \\frac{1-\\alpha^{L}}{1-\\alpha}$, where $(\\alpha \\neq 1)$ ',NULL,'(6.23)',NULL),
(6019,'dirichlet function',NULL,6,'$D_{L}(e^{\\jw})=\\frac{\\sin(\\frac{\\hat{\\omega}L}{2})}{L \\sin(\\frac{\\hat{\\omega}}{2})}$ ',NULL,'(6.27)','FIR:FR:running-average filter'),
(6020,'FIR:FR:plot',NULL,6,NULL,NULL,NULL,'principle value$^2$'),
(6021,'w2what',NULL,6,'$|\\omega| < \\frac{\\pi}{T_{s}} \\rightarrow |\\hat{\\omega}| < \\pi $ ',NULL,NULL,'frequency:DT$^4$'),
(7001,'ZD',NULL,7,NULL,NULL,NULL,NULL),
(7002,'ZT',NULL,7,'$x[k] = \\sum\\limits_{k=0}^{N}x[k]\\delta[n-k] \\rightarrow X(z)=\\sum\\limits_{k=0}^{N}x[k]z^{-k}$ ',NULL,'(7.2)','FL signal$^5$'),
(7003,'inverse ZT',NULL,7,'$X(z) \\rightarrow x[n]$        ',NULL,'(7.3)','ZT'),
(7004,'ZT pair',NULL,7,'$x[n] \\leftrightarrow X(z)$    ',NULL,NULL,'ZT'),
(7005,'FIR:system function',NULL,7,'$h[n]=\\sum\\limits_{k=0}^{M}b_{k}\\delta[n-k]\\leftrightarrow H(z)=\\sum\\limits_{k=0}^{M}b_{k}z^{-k}$ ',NULL,'(7.7)','ZT'),
(7006,'ZT:superposition Prop',NULL,7,'$\\alpha x_{1}[n] + \\beta x_{2}[n] \\mapsto \\alpha X_{1}(z) + \\beta X_{2}(z)$ ',NULL,'(7.9)','ZT'),
(7007,'ZT:unit delay',NULL,7,'$x[n-1] \\mapsto z^{-1}X(z)$          ',NULL,'(7.12)','ZT:delay Prop'),
(7008,'ZT:delay Prop',NULL,7,'$x[n-n_{o}] \\mapsto z^{-n_{o}}X(z)$ ',NULL,'(7.13)','ZT'),
(7009,'ZT:multiplicative Prop',NULL,7,'$h[n] = h_{1}[n]\\ast h_{2}[n] \\leftrightarrow H(z) = H_{1}(z)H_{2}(z)$ ',NULL,NULL,'ZT'),
(7010,'ZT:LTI system',NULL,7,'$Y(z) = H(z)X(z)$ ',NULL,'(7.19)','ZT'),
(7011,'FIR:cascade filters',NULL,7,NULL,NULL,'[7-2]','FIR:system function'),
(7012,'FIR:cascade system',NULL,7,NULL,NULL,'[7-2]','FIR:cascade filters'),
(7013,'FIR:deconv',NULL,7,'$H_{1}(z)H_{2}(z)=1$ ',NULL,NULL,'poly factor '),
(7014,'poly factor',NULL,7,NULL,NULL,NULL,NULL),
(7015,'zeros',NULL,7,'$B(z) = 0$ , where $H(z) = \\frac{B(z)}{A(z)}$ ',NULL,NULL,'poly factor'),
(7016,'poles',NULL,7,'$A(z) = 0$ , where $H(z) = \\frac{B(z)}{A(z)}$ ',NULL,NULL,'FIR:system function '),
(7017,'z-plane',NULL,7,NULL,NULL,'[7-4]','complex plane$^2$'),
(7018,'unit circle',NULL,7,'$z = e^{j\\omega} \\quad , |z|=1$ ',NULL,'[7-4]','z-plane'),
(7019,'ZD2FD',NULL,7,'$H(z)= \\sum\\limits_{k=0}^M b_{k}z^{-k} \\rightarrow H(e^{\\jw})=\\sum\\limits_{k=0}^M b_{k}e^{-\\jw k}$ ',NULL,NULL,'FIR:system function'),
(7020,'Def:z',NULL,7,'$z = e^{j\\omega}$ ',NULL,'(7.26)','FIR:system function '),
(7021,'pole-zero plot',NULL,7,NULL,NULL,'[7-5]','z-plane '),
(7022,'L-th roots of unity',NULL,7,'$z^{L} = 1$ ',NULL,NULL,'Def:z'),
(7023,'nulling filter',NULL,7,'$H(z)=\\sum\\limits_{k=0}^{L-1}z^{-k}=0$ at $\\hat{\\omega}=\\frac{2\\pi k}{L}$ ',NULL,NULL,'zeros'),
(7024,'L-pt running filter',NULL,7,'$H(z)=\\sum\\limits_{k=0}^{L-1}z^{-k}=\\prod_{k=1}^{L-1}(1-e^{\\frac{j2\\pi k}{L}}z^{-1})$ ',NULL,NULL,'nulling filter'),
(7025,'FIR:complex BPF',NULL,7,'$H(z)=\\prod_{k=1k \\neq k_{o}}^{L-1}(1-e^{\\frac{j2\\pi k}{L}}z^{-1})$ ',NULL,NULL,'L-pt running filter'),
(7026,'FIR:complex BPF:coeffs',NULL,7,'$b_{k}=e^{\\frac{j2\\pi k_o k}{L}}$ ',NULL,'(7.40)','FIR:complex BPF'),
(7027,'FIR:real BPF',NULL,7,'$b_{k}=\\cos(2\\pi k_o k) \\text{ for } k=0,1,..., L-1 $ ',NULL,NULL,'FIR:complex BPF '),
(7028,'linear phase filter',NULL,7,'$b_k=b_{M-k} \\quad , k=0,1,... M$ ',NULL,NULL,'nulling filter '),
(7029,'linear phase filter:zeros',NULL,7,'$H(z_o)=H(z_o^*)=H(\\frac{1}{z_o})=H(\\frac{1}{z_o^*})=0$ ',NULL,NULL,'linear phase filter ');