
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `gui_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(16) NOT NULL,
  `title` varchar(265) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `gui_1` (`id`, `name`, `title`) VALUES
(1, 'con2dis', ''),
(2, 'cconvdemo', ''),
(3, 'dconvdemo', ''),
(4, 'pezdemo', ''),
(5, 'fseriesdemo', '');