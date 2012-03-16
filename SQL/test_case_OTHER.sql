# user account
# id | first_name | last_name | username | password
INSERT INTO user VALUES(111111111, 'John', 'Smith', 'gtg111x', 'gtg111x');
INSERT INTO user VALUES(999999999, 'Paul', 'White', 'gtg999x', 'gtg999x');
INSERT INTO User values(777777777, 'Mark', 'Casey', 'gt', 'student');

# concepts
# id | name | chapter_number | concept_file | book_ref | parents
#INSERT INTO concept VALUES(1, 'FIR:output', 6, 'FIR_output', '(6.5)', 'FIR:FR, CE:signal:discrete, frequency:DT');
#INSERT INTO concept VALUES(2, 'CE:signal:discrete', 6, 'CE_SIGNAL_DISCRETE', NULL, 'frequency:DT, CE:signal');

INSERT INTO concept VALUES(7001, 'ZD', 7, 'ZD', '', '');
INSERT INTO concept VALUES(7002, 'ZT', 7, 'ZT', '(7.2)', 'FL signal');
INSERT INTO concept VALUES(7003, 'inverse ZT', 7, 'inverse_ZT', '(7.3)', 'ZT');
INSERT INTO concept VALUES(7004, 'ZT pair', 7, 'ZT_pair', '', 'Zt, inverse ZT');
INSERT INTO concept VALUES(7005, 'FIR:system function', 7, 'FIR__System_function', '(7.7)', 'ZT, FIR:impulse response');
INSERT INTO concept VALUES(7006, 'ZT:superposition Prop', 7, 'ZT__superposition_Prop', '(7.9)', 'ZT');
INSERT INTO concept VALUES(7007, 'ZT:unit delay', 7, 'ZT__unit_delay', '(7.12)', 'ZT:delay Prop');
INSERT INTO concept VALUES(7008, 'ZT:delay Prop', 7, 'ZT__delay_Prop', '(7.13)', 'ZT');
INSERT INTO concept VALUES(7009, 'ZT:multiplicative Prop', 7, 'ZT__multiplicative_Prop', '', 'LTI system:cascaded');
INSERT INTO concept VALUES(7010, 'ZT:LTI system', 7, 'ZT__LTI_system', '(7.19)', 'ZT, LTI system');
INSERT INTO concept VALUES(7011, 'FIR:cascade filters', 7, 'FIR__cascade_filters', '[7.2]', 'FIR:system function, ZT:multiplicative Prop');
INSERT INTO concept VALUES(7012, 'FIR:cascade system', 7, 'FIR:cascade system', '[7.2]', 'FIR:cascade filters, ZT:LTI system');
INSERT INTO concept VALUES(7013, 'FIR:deconv', 7, 'FIR__deconv', '', 'poly factor, FIR:cascade system');
INSERT INTO concept VALUES(7014, 'poly factor', 7, 'poly_factor', '', '');
INSERT INTO concept VALUES(7015, 'zeros', 7, 'zeros', '', 'poly factor, FIR:system function');
INSERT INTO concept VALUES(7016, 'poles', 7, 'poles', '', 'poly factor, FIR:system function');
INSERT INTO concept VALUES(7017, 'z-plane', 7, 'z-plane', '[7.4]', 'complex plane');
INSERT INTO concept VALUES(7018, 'unit circle', 7, 'unit_circle', '[7.4]', 'z-plane, Def:z');
INSERT INTO concept VALUES(7019, 'ZD2FD', 7, 'ZD2FD', '', 'FIR:system function, Def:z');
INSERT INTO concept VALUES(7020, 'Def:z', 7, 'Def__z', '(7.26)', 'FIR:system function');
INSERT INTO concept VALUES(7021, 'pole-zero plot', 7, 'pole-zero_plot', '[7.5]', 'z-plane, poles, zeros, unit circle');
INSERT INTO concept VALUES(7022, 'L-th roots of unity', 7, 'L-th_roots_of_unity', '(7.2)', 'Def:z');
INSERT INTO concept VALUES(7023, 'nulling filter', 7, 'nulling_filer', '', 'zeros, FIR:FR, L-th roots of unity');
INSERT INTO concept VALUES(7024, 'L-pt running filter', 7, 'L-pt_running_filter', '', 'nulling filter, lowpass filter, geometric series');
INSERT INTO concept VALUES(7025, 'FIR:complex BPF', 7, 'FIR__complex_BPF', '', 'L-pt running filter');
INSERT INTO concept VALUES(7026, 'FIR:complex BPF:coeffs', 7, 'FIR__complex_BPF__coeffs', '(7.40)', 'FIR:complex BPF');
INSERT INTO concept VALUES(7027, 'FIR:real BPF', 7, 'FIR__real_BPF', '', 'FIR:complex BPF');
INSERT INTO concept VALUES(7028, 'linear phase filter', 7, 'linear_phase_filter', '', 'nulling filter');
INSERT INTO concept VALUES(7029, 'linear phase filter:zeros', 7, 'linear_phase_filter__zeros', '', 'linear phase filter');

# questions
# id | concept_id | question_file | answer_file | format | solution
INSERT INTO question VALUES(70001, zzplane, '7_1', 'a70001', 'MC', 'B');
INSERT INTO question VALUES(70002, Def:z, '7_2', 'a70002', 'MC', 'B');
INSERT INTO question VALUES(70003, unit circle, '7_3', 'a70003', 'MC', 'D');
INSERT INTO question VALUES(70004, FIR:FR, '7_4', 'a70004', 'MC', 'B');
INSERT INTO question VALUES(70005, nulling filter, '7_5', 'a70005', 'MC', 'A');
INSERT INTO question VALUES(70006, pole-zero plot, '7_6', 'a70006', 'MC', 'C');
INSERT INTO question VALUES(70007, linear phase filter:zeros, '7_7', 'a70007', 'MC', 'C');
INSERT INTO question VALUES(70008, L-pt running filter, '7_8', 'a70008', 'MC', 'C');
INSERT INTO question VALUES(70009, L-pt running filter, '7_9', 'a70009', 'MC', 'E');
INSERT INTO question VALUES(70010, FIR:complex BPF:coeffs, '7_10', 'a70010', 'MC', 'D');
INSERT INTO question VALUES(70011, FIR:impulse response, '7_11', 'a70011', 'MC', 'B');
INSERT INTO question VALUES(70012, LTI system, '7_12', 'a70012', 'MC', 'C');
INSERT INTO question VALUES(70013, lowpass filter, '7_13', 'a70013', 'MC', 'A');
INSERT INTO question VALUES(70014, poles, '7_14', 'a70014', 'MC', 'A');
INSERT INTO question VALUES(70015, ZD, '7_15', 'a70015', 'MC', 'A');
INSERT INTO question VALUES(70016, ZT pair, '7_16', 'a70016', 'MC', 'D');
INSERT INTO question VALUES(70017, FIR:complex BPF, '7_17', 'a70017', 'MC', 'C');
INSERT INTO question VALUES(70018, FIR:real BPF, '7_18', 'a70018', 'MC', 'A');
INSERT INTO question VALUES(70019, ZT:delay Prop, '7_19', 'a70019', 'MC', 'D');
INSERT INTO question VALUES(70020, FIR:FR, '7_20', 'a70020', 'MC', 'D');
INSERT INTO question VALUES(70021, ZD2FD, '7_21', 'a70021', 'MC', 'B');
INSERT INTO question VALUES(70022, L-th roots of unity, '7_22', 'a70022', 'MC', 'C');
INSERT INTO question VALUES(70023, ZT:multiplicative Prop, '7_23', 'a70023', 'MC', 'D');
INSERT INTO question VALUES(70024, FL signal, '7_24', 'a70024', 'MC', 'C');
INSERT INTO question VALUES(70025, geometric series, '7_25', 'a70025', 'MC', 'D');
INSERT INTO question VALUES(70026, ZT, '7_26', 'a70026', 'MC', 'A');
INSERT INTO question VALUES(70027, linear phase filter, '7_27', 'a70027', 'MC', 'B');
INSERT INTO question VALUES(70028, ZT:unit delay, '7_28', 'a70028', 'MC', 'A');
INSERT INTO question VALUES(70029, ZT:LTI system, '7_29', 'a70029', 'MC', 'C');
INSERT INTO question VALUES(70030, ZT:Superposition Prop, '7_30', 'a70030', 'MC', 'C');
INSERT INTO question VALUES(70031, FIR:cascade filters, '7_31', 'a70031', 'MC', 'B');
INSERT INTO question VALUES(70032, FIR Block Diagram, '7_32', 'a70032', 'MC', 'C');
INSERT INTO question VALUES(70033, inverse ZT, '7_33', 'a70033', 'MC', 'D');
INSERT INTO question VALUES(70034, FIR:cascade system, '7_34', 'a70034', 'MC', 'A');
INSERT INTO question VALUES(70035, FIR:system function, '7_35', 'a70035', 'MC', 'B');
INSERT INTO question VALUES(70036, zeros, '7_36', 'a70036', 'MC', 'C');
INSERT INTO question VALUES(70037, FIR:deconv, '7_37', 'a70037', 'MC', 'C');
INSERT INTO question VALUES(70038, poly factor, '7_38', 'a70038', 'MC', 'D');
INSERT INTO question VALUES(70039, complex plane, '7_39', 'a70039', 'MC', 'A');
INSERT INTO question VALUES(70040, LTI system:cascaded, '7_40', 'a70040', 'MC', 'B');


#SELECT question.id, concept.name, question.question_file, answer_file, format FROM question, concept;
