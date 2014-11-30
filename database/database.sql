-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2014 at 12:26 AM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quizzgamez`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer_alternatives`
--

CREATE TABLE `answer_alternatives` (
`Alternative_Id` int(11) NOT NULL,
  `Question_Id` int(11) NOT NULL,
  `AnswerText` varchar(255) NOT NULL,
  `CorrectAnswer` tinyint(1) NOT NULL,
  `AlternativeOrderValue` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=549 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer_alternatives`
--

INSERT INTO `answer_alternatives` (`Alternative_Id`, `Question_Id`, `AnswerText`, `CorrectAnswer`, `AlternativeOrderValue`) VALUES
(548, 137, 'Alternative 5', 2, 5),
(547, 137, 'Alternative 4', 2, 4),
(546, 137, 'Alternative 3', 1, 3),
(545, 137, 'Alternative 2', 2, 2),
(544, 137, 'Alternative 1', 2, 1),
(543, 136, 'No alternative', 0, 5),
(542, 136, 'Alternative 4', 2, 4),
(541, 136, 'Alternative 3', 1, 3),
(540, 136, 'Alternative 2', 2, 2),
(539, 136, 'Alternative 1', 2, 1),
(538, 135, 'No alternative', 0, 5),
(537, 135, 'No alternative', 0, 4),
(536, 135, 'Alternative 3', 1, 3),
(535, 135, 'Alternative 2', 2, 2),
(534, 135, 'Alternative 1', 2, 1),
(533, 134, 'No alternative', 0, 5),
(532, 134, 'Alternative 4', 2, 4),
(531, 134, 'Alternative 3', 2, 3),
(530, 134, 'Alternative 2', 1, 2),
(529, 134, 'Alternative 1', 2, 1),
(528, 133, 'Alternative 5', 2, 5),
(527, 133, 'Alternative 4', 1, 4),
(526, 133, 'Alternative 3', 1, 3),
(525, 133, 'Alternative 2', 2, 2),
(524, 133, 'Alternative 1', 2, 1),
(523, 132, 'Alternativ 5', 2, 5),
(522, 132, 'Alternativ 4', 2, 4),
(521, 132, 'Alternativ 3', 2, 3),
(520, 132, 'Alternativ 2', 2, 2),
(519, 132, 'Alternativ 1', 1, 1),
(518, 131, 'No alternative', 0, 5),
(517, 131, 'No alternative', 0, 4),
(516, 131, 'No alternative', 0, 3),
(515, 131, 'No alternative', 0, 2),
(514, 131, 'No alternative', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `finished_quizzes`
--

CREATE TABLE `finished_quizzes` (
`Finished_Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Quizz_Id` int(11) NOT NULL,
  `ResultValue` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `finished_quizzes`
--

INSERT INTO `finished_quizzes` (`Finished_Id`, `User_Id`, `Quizz_Id`, `ResultValue`) VALUES
(60, 30, 122, 0);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
`Question_Id` int(11) NOT NULL,
  `Quizz_Id` int(11) NOT NULL,
  `QuestionName` varchar(255) NOT NULL,
  `QuestionText` varchar(255) NOT NULL,
  `QuizzOrderValue` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`Question_Id`, `Quizz_Id`, `QuestionName`, `QuestionText`, `QuizzOrderValue`) VALUES
(137, 123, '', 'Question 2 Text ', 2),
(136, 123, '', 'Question 1 Text', 1),
(135, 122, '', 'Question 4 Text', 4),
(134, 122, '', 'Question 3 Text', 3),
(133, 122, '', 'Question 2 Text', 2),
(132, 122, '', 'Question 1 Text', 1),
(131, 121, '', 'feijf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
`Quizz_Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `User` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`Quizz_Id`, `Name`, `User`) VALUES
(123, 'Quizz 2', 29),
(122, 'Quizz 1', 29);

-- --------------------------------------------------------

--
-- Table structure for table `quizz_results`
--

CREATE TABLE `quizz_results` (
`Result_Id` int(11) NOT NULL,
  `Quizz_Id` int(11) NOT NULL,
  `Question_Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `CorrectAnswer` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quizz_results`
--

INSERT INTO `quizz_results` (`Result_Id`, `Quizz_Id`, `Question_Id`, `User_Id`, `CorrectAnswer`) VALUES
(263, 122, 135, 30, 2),
(262, 122, 134, 30, 2),
(261, 122, 133, 30, 2),
(260, 122, 132, 30, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
`User_Id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_Id`, `Username`, `Password`, `Role`) VALUES
(30, 'student', 'cd73502828457d15655bbd7a63fb0bc8', 2),
(29, 'teacher', '8d788385431273d11e8b43bb78f3aa41', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer_alternatives`
--
ALTER TABLE `answer_alternatives`
 ADD PRIMARY KEY (`Alternative_Id`), ADD KEY `Question_Id` (`Question_Id`);

--
-- Indexes for table `finished_quizzes`
--
ALTER TABLE `finished_quizzes`
 ADD PRIMARY KEY (`Finished_Id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
 ADD PRIMARY KEY (`Question_Id`), ADD KEY `Quizz_Id` (`Quizz_Id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
 ADD PRIMARY KEY (`Quizz_Id`), ADD KEY `fk_User` (`User`);

--
-- Indexes for table `quizz_results`
--
ALTER TABLE `quizz_results`
 ADD PRIMARY KEY (`Result_Id`), ADD KEY `Quizz_Id` (`Quizz_Id`), ADD KEY `Question_Id` (`Question_Id`), ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`User_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer_alternatives`
--
ALTER TABLE `answer_alternatives`
MODIFY `Alternative_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=549;
--
-- AUTO_INCREMENT for table `finished_quizzes`
--
ALTER TABLE `finished_quizzes`
MODIFY `Finished_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
MODIFY `Question_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=138;
--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
MODIFY `Quizz_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=124;
--
-- AUTO_INCREMENT for table `quizz_results`
--
ALTER TABLE `quizz_results`
MODIFY `Result_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=264;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `User_Id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
