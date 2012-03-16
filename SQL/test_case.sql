#---------------------------------------------------------------------------------------
#                               USER table
# id | first_name | last_name | username | password
#---------------------------------------------------------------------------------------
INSERT INTO users VALUES(000000001, 'Greg', 'Krudysz', 'krudysz', 'rootcsip','admin');
INSERT INTO users VALUES(000000002, 'GT', 'Buzz', 'gt', 'student','student');

#---------------------------------------------------------------------------------------
#                               CONCEPT table
# id | name | chapter_number | concept_file | book_ref | parents
#---------------------------------------------------------------------------------------
#INSERT INTO concept VALUES(1, 'FIR:output', , 6, 'FIR_output', '(6.5)', 'FIR:FR, CE:signal:discrete, frequency:DT');
#INSERT INTO concept VALUES(2, 'CE:signal:discrete', 6, 'CE_SIGNAL_DISCRETE', NULL, 'frequency:DT, CE:signal');

INSERT INTO concept VALUES(2020, 'complex plane', 6, 2, 'complex_plane', '[2.10]', '');

INSERT INTO concept VALUES(5009, 'FL signal', 2, 5, 'FL_signal', '', 'signal support');
INSERT INTO concept VALUES(5010, 'FIR:impulse response', 3, 5, 'FIR__impulse_response', '[5.8]','FL signal, FIR:system,unit impulse');
INSERT INTO concept VALUES(5016, 'FIR:block diagram', 37, 5, 'FIR__block_diagram', '[5.13]', '');
INSERT INTO concept VALUES(5020, 'LTI system', 5, 5, 'LTI_system', '', 'time invariance,linearity,conv:sum');
INSERT INTO concept VALUES(5024, 'LTI system:cascaded', 4, 5, 'LTI system__cascaded', '', 'LTI system');

INSERT INTO concept VALUES(6002, 'FIR:FR', 7, 6, 'FIR__FR', '(6.4)', 'FIR:system,CE:signal:discrete');
INSERT INTO concept VALUES(6015, 'lowpass filter', 8, 6, 'lowpass_filter', '', '');
INSERT INTO concept VALUES(6018, 'geometric series', 9, 6, 'geometric_series', '(6.23)', '');

INSERT INTO concept VALUES(7001, 'ZD', 0, 7, 'ZD', '', '');
INSERT INTO concept VALUES(7002, 'ZT', 10, 7, 'ZT', '(7.2)', 'FL signal');
INSERT INTO concept VALUES(7003, 'inverse ZT', 12, 7, 'inverse_ZT', '(7.3)', 'ZT');
INSERT INTO concept VALUES(7004, 'ZT pair', 18, 7, 'ZT_pair', '', 'Zt, inverse ZT');
INSERT INTO concept VALUES(7005, 'FIR:system function', 13, 7, 'FIR__System_function', '(7.7)', 'ZT, FIR:impulse response');
INSERT INTO concept VALUES(7006, 'ZT:superposition Prop', 14, 7, 'ZT__superposition_Prop', '(7.9)', 'ZT');
INSERT INTO concept VALUES(7007, 'ZT:unit delay', 23, 7, 'ZT__unit_delay', '(7.12)', 'ZT:delay Prop');
INSERT INTO concept VALUES(7008, 'ZT:delay Prop', 15, 7, 'ZT__delay_Prop', '(7.13)', 'ZT');
INSERT INTO concept VALUES(7009, 'ZT:multiplicative Prop', 16, 7, 'ZT__multiplicative_Prop', '', 'LTI system:cascaded');
INSERT INTO concept VALUES(7010, 'ZT:LTI system', 17, 7, 'ZT__LTI_system', '(7.19)', 'ZT, LTI system');
INSERT INTO concept VALUES(7011, 'FIR:cascade filters', 19, 7, 'FIR__cascade_filters', '[7.2]', 'FIR:system function, ZT:multiplicative Prop');
INSERT INTO concept VALUES(7012, 'FIR:cascade system', 24, 7, 'FIR__cascade_system', '[7.2]', 'FIR:cascade filters, ZT:LTI system');
INSERT INTO concept VALUES(7013, 'FIR:deconv', 28, 7, 'FIR__deconv', '', 'poly factor, FIR:cascade system');
INSERT INTO concept VALUES(7014, 'poly factor', 1, 7, 'poly_factor', '', '');
INSERT INTO concept VALUES(7015, 'zeros', 20, 7, 'zeros', '', 'poly factor, FIR:system function');
INSERT INTO concept VALUES(7016, 'poles', 21, 7, 'poles', '', 'poly factor, FIR:system function');
INSERT INTO concept VALUES(7017, 'z-plane', 11, 7, 'z-plane', '[7.4]', 'complex plane');
INSERT INTO concept VALUES(7018, 'unit circle', 25, 7, 'unit_circle', '[7.4]', 'z-plane, Def:z');
INSERT INTO concept VALUES(7019, 'ZD2FD', 26, 7, 'ZD2FD', '', 'FIR:system function, Def:z');
INSERT INTO concept VALUES(7020, 'Def:z', 22, 7, 'Def__z', '(7.26)', 'FIR:system function');
INSERT INTO concept VALUES(7021, 'pole-zero plot', 29, 7, 'pole-zero_plot', '[7.5]', 'z-plane, poles, zeros, unit circle');
INSERT INTO concept VALUES(7022, 'L-th roots of unity', 27, 7, 'L-th_roots_of_unity', '(7.2)', 'Def:z');
INSERT INTO concept VALUES(7023, 'nulling filter', 30, 7, 'nulling_filer', '', 'zeros, FIR:FR, L-th roots of unity');
INSERT INTO concept VALUES(7024, 'L-pt running filter', 31, 7, 'L-pt_running_filter', '', 'nulling filter, lowpass filter, geometric series');
INSERT INTO concept VALUES(7025, 'FIR:complex BPF', 33, 7, 'FIR__complex_BPF', '', 'L-pt running filter');
INSERT INTO concept VALUES(7026, 'FIR:complex BPF:coeffs', 35, 7, 'FIR__complex_BPF__coeffs', '(7.40)', 'FIR:complex BPF');
INSERT INTO concept VALUES(7027, 'FIR:real BPF', 36, 7, 'FIR__real_BPF', '', 'FIR:complex BPF');
INSERT INTO concept VALUES(7028, 'linear phase filter', 32, 7, 'linear_phase_filter', '', 'nulling filter');
INSERT INTO concept VALUES(7029, 'linear phase filter:zeros', 34, 7, 'linear_phase_filter__zeros', '', 'linear phase filter');

#---------------------------------------------------------------------------------------
#                               QUESTION table
# id | concept_id | question_file | answer_file | format | solution
#---------------------------------------------------------------------------------------
INSERT INTO question VALUES(70001, 7017, '7_1', 'a70001', 'MC', 'B');
INSERT INTO question VALUES(70002, 7020, '7_2', 'a70002', 'MC', 'B');
INSERT INTO question VALUES(70003, 7018, '7_3', 'a70003', 'MC', 'D');
INSERT INTO question VALUES(70004, 6002, '7_4', 'a70004', 'MC', 'B');
INSERT INTO question VALUES(70005, 7023, '7_5', 'a70005', 'MC', 'A');
INSERT INTO question VALUES(70006, 7021, '7_6', 'a70006', 'MC', 'C');
INSERT INTO question VALUES(70007, 7029, '7_7', 'a70007', 'MC', 'C');
INSERT INTO question VALUES(70008, 7024, '7_8', 'a70008', 'MC', 'C');
INSERT INTO question VALUES(70009, 7024, '7_9', 'a70009', 'MC', 'E');
INSERT INTO question VALUES(70010, 7026, '7_10', 'a70010', 'MC', 'D');
INSERT INTO question VALUES(70011, 5010, '7_11', 'a70011', 'MC', 'B');
INSERT INTO question VALUES(70012, 5020, '7_12', 'a70012', 'MC', 'C');
INSERT INTO question VALUES(70013, 6015, '7_13', 'a70013', 'MC', 'A');
INSERT INTO question VALUES(70014, 7016, '7_14', 'a70014', 'MC', 'A');
INSERT INTO question VALUES(70015, 7001, '7_15', 'a70015', 'MC', 'A');
INSERT INTO question VALUES(70016, 7004, '7_16', 'a70016', 'MC', 'D');
INSERT INTO question VALUES(70017, 7025, '7_17', 'a70017', 'MC', 'C');
INSERT INTO question VALUES(70018, 7027, '7_18', 'a70018', 'MC', 'A');
INSERT INTO question VALUES(70019, 7008, '7_19', 'a70019', 'MC', 'D');
INSERT INTO question VALUES(70020, 6002, '7_20', 'a70020', 'MC', 'D');
INSERT INTO question VALUES(70021, 7019, '7_21', 'a70021', 'MC', 'B');
INSERT INTO question VALUES(70022, 7022, '7_22', 'a70022', 'MC', 'C');
INSERT INTO question VALUES(70023, 7009, '7_23', 'a70023', 'MC', 'D');
INSERT INTO question VALUES(70024, 5009, '7_24', 'a70024', 'MC', 'C');
INSERT INTO question VALUES(70025, 6018, '7_25', 'a70025', 'MC', 'D');
INSERT INTO question VALUES(70026, 7002, '7_26', 'a70026', 'MC', 'A');
INSERT INTO question VALUES(70027, 7028, '7_27', 'a70027', 'MC', 'B');
INSERT INTO question VALUES(70028, 7007, '7_28', 'a70028', 'MC', 'A');
INSERT INTO question VALUES(70029, 7010, '7_29', 'a70029', 'MC', 'C');
INSERT INTO question VALUES(70030, 7006, '7_30', 'a70030', 'MC', 'C');
INSERT INTO question VALUES(70031, 7011, '7_31', 'a70031', 'MC', 'B');
INSERT INTO question VALUES(70032, 5016, '7_32', 'a70032', 'MC', 'C');
INSERT INTO question VALUES(70033, 7003, '7_33', 'a70033', 'MC', 'D');
INSERT INTO question VALUES(70034, 7012, '7_34', 'a70034', 'MC', 'A');
INSERT INTO question VALUES(70035, 7005, '7_35', 'a70035', 'MC', 'B');
INSERT INTO question VALUES(70036, 7015, '7_36', 'a70036', 'MC', 'C');
INSERT INTO question VALUES(70037, 7013, '7_37', 'a70037', 'MC', 'C');
INSERT INTO question VALUES(70038, 7014, '7_38', 'a70038', 'MC', 'D');
INSERT INTO question VALUES(70039, 2020, '7_39', 'a70039', 'MC', 'A');
INSERT INTO question VALUES(70040, 5024, '7_40', 'a70040', 'MC', 'B');

INSERT INTO question VALUES(70089, 6002, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70090, 7015, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70091, 7016, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70092, 7017, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70093, 7018, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70094, 7019, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70095, 7021, '7_90', 'a70090', 'LV', '');
INSERT INTO question VALUES(70096, 7027, '7_96', 'a70090', 'LV', '');

#---------------------------------------------------------------------------------------
#                               LABVIEW table
# concept_id | qui_name
#---------------------------------------------------------------------------------------
INSERT INTO labview VALUES(6002, 'Pez');
INSERT INTO labview VALUES(7015, 'Pez');
INSERT INTO labview VALUES(7016, 'Pez');
INSERT INTO labview VALUES(7017, 'Pez');
INSERT INTO labview VALUES(7018, 'Pez');
INSERT INTO labview VALUES(7019, 'Pez');
INSERT INTO labview VALUES(7021, 'Pez');
INSERT INTO labview VALUES(7027, 'SineDrill');
#---------------------------------------------------------------------------------------

#SELECT question.id, concept.name, question.question_file, answer_file, format FROM question, concept;
