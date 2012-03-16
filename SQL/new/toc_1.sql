-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2010 at 11:06 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `its`
--

-- --------------------------------------------------------

--
-- Table structure for table `toc_1`
--

CREATE TABLE IF NOT EXISTS `toc_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(16) NOT NULL,
  `section` varchar(16) NOT NULL,
  `title` varchar(265) NOT NULL,
  `last_pageNum` int(11) NOT NULL,
  `concept_id` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `toc_1`
--

INSERT INTO `toc_1` (`id`, `name`, `section`, `title`, `last_pageNum`, `concept_id`) VALUES
(1, '1', 'chapter', 'Introduction', 6, ''),
(2, '2', 'chapter', 'Sinusoids', 35, ''),
(3, '3', 'chapter', 'Spectrum Representation', 70, ''),
(4, '4', 'chapter', 'Sampling and Aliasing', 100, ''),
(5, '5', 'chapter', 'FIR Filters', 129, ''),
(6, '6', 'chapter', 'Frequency Response of FIR Filters', 162, ''),
(7, '7', 'chapter', 'z-Transforms', 195, ''),
(8, '8', 'chapter', 'IIR Filters', 244, ''),
(9, '9', 'chapter', 'Continuous-Time Signals and LTI Systems', 284, ''),
(10, '10', 'chapter', 'Frequency Response', 306, ''),
(11, '11', 'chapter', 'Continuous-Time Fourier Transform', 345, ''),
(12, '12', 'chapter', 'Filtering, Modulation, and Sampling', 388, ''),
(13, '13', 'chapter', 'Computing the Spectrum', 426, ''),
(14, 'A', 'appendix', 'Complex Numbers', 442, ''),
(15, 'B', 'appendix', 'Programming in MATLAB', 454, ''),
(16, 'C', 'appendix', 'Laboratory Projects', 477, ''),
(17, 'D', 'appendix', 'CD-ROM Demos', 481, ''),
(18, '', 'index', '', 489, '');
