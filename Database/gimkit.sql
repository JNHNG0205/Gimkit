-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 26, 2024 at 09:15 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gimkit`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` int NOT NULL AUTO_INCREMENT,
  `answer_text` text NOT NULL,
  `question_id` int NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `answer_ibfk_1` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `answer_text`, `question_id`) VALUES
(32, '{p, r, s}', 102),
(33, 'many to one', 103),
(34, '7', 104),
(35, '{–3, –1, 1, 3}', 105),
(36, '-3', 106),
(37, '{(–4, 8), (3, 3), (4, 8)}', 107),
(38, '{–4, 3, 4}', 108),
(39, 'm = 4 + 3n', 109),
(40, '1', 110),
(41, 'p = (1-q)/5', 111),
(42, 'h-1(x) = (x-1)/3', 112),
(43, '115', 122),
(44, 'x+y=215', 123),
(45, 'x+y=90', 124),
(46, 'x+y=224', 125),
(47, 'x+y=270', 126),
(48, 'x=150', 127),
(49, 'x=33', 128),
(50, 'x=60', 129),
(51, '132cm', 144),
(52, '12cm', 145),
(53, '462cm2 ', 146),
(54, '98cm', 147),
(55, '77cm2 ', 148),
(56, '63.34m', 149),
(57, '1188cm2 ', 158),
(58, '132cm2 ', 159),
(59, '66 cm2 ', 160),
(60, '4.2cm', 161),
(61, '96cm2', 162),
(62, '308', 169),
(63, '3cm', 170),
(64, '13cm', 171),
(65, '768cm3', 172),
(66, ' 6cm', 173),
(67, '1232 cm3', 174),
(68, '110 bottles', 175),
(69, '3cm', 176),
(70, '6930cm3', 177),
(71, '866cm3', 178),
(72, '400cm3', 179),
(73, '0.44m', 180),
(74, '3.5cm', 181),
(75, '74.8', 201),
(76, '10.0', 202),
(77, '10.3', 203),
(78, '281.14 ft', 204),
(79, '33', 205);

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

DROP TABLE IF EXISTS `badge`;
CREATE TABLE IF NOT EXISTS `badge` (
  `badge_id` int NOT NULL AUTO_INCREMENT,
  `badge_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`badge_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`badge_id`, `badge_name`, `description`) VALUES
(8, 'The All Knowing', 'Complete All Level'),
(9, 'The Overachiever', 'Reach User Level 50'),
(10, 'The Mathematician', 'Complete a single level with all correct answer.'),
(11, 'Welcome to Gimkit', 'Register an account in Gimkit.'),
(12, 'The Warrior', 'Win a PvP match'),
(13, 'The Hardworker', 'Reach User Level 100'),
(14, 'Maestro of Learning', 'Reach User Level  150'),
(15, 'Mr. Prof All Knowing', 'Reach User Level  200'),
(16, 'The Sigma Grinder', 'Reach User Level 250'),
(17, 'The Gigachad', 'Reach User Level 300');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int NOT NULL AUTO_INCREMENT,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `discussion_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `discussion_id` (`discussion_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `comment_text`, `created_at`, `discussion_id`, `user_id`) VALUES
(29, 'The Pythagorean Theorem is really interesting! I recently used it to solve a problem in my physics class involving vector components. It\\\'s amazing how this simple formula can be applied in so many ways.', '2024-08-23 16:16:24', 23, 1),
(30, 'I always have trouble remembering the theorem. Are there any tricks to easily recall or apply it?', '2024-08-23 16:26:30', 24, 8),
(31, 'I find calculus quite challenging but fascinating. The concept of limits is especially mind-boggling. Can anyone recommend a good introductory book or online course?', '2024-08-23 16:26:46', 25, 8),
(32, '\\\"Integral calculus is useful for calculating areas under curves, but it can be quite complex. Does anyone have tips for solving definite integrals more efficiently?', '2024-08-23 16:26:58', 26, 8),
(33, 'Probability can be tricky. I appreciate the clear explanation. What are some common pitfalls to avoid when calculating probabilities?', '2024-08-23 16:27:35', 27, 8),
(34, 'Understanding circles is essential for many geometry problems. How do you approach solving complex problems involving arcs and sectors?', '2024-08-23 16:27:48', 28, 8),
(35, 'I always get confused with the area of circles when it comes to practical applications. Any tips on applying these concepts to real-world problems?', '2024-08-23 16:28:04', 29, 8),
(36, 'Number theory is a fundamental part of mathematics. I’m especially interested in how prime numbers are used in cryptography.', '2024-08-23 16:28:21', 30, 8),
(37, 'Great introduction! Are there any interesting problems or puzzles related to number theory that I can work on?', '2024-08-23 16:28:31', 31, 8),
(38, 'Great introduction! Are there any interesting problems or puzzles related to number theory that I can work on?', '2024-08-23 16:29:18', 22, 8);

-- --------------------------------------------------------

--
-- Table structure for table `discussion`
--

DROP TABLE IF EXISTS `discussion`;
CREATE TABLE IF NOT EXISTS `discussion` (
  `discussion_id` int NOT NULL AUTO_INCREMENT,
  `discussion_title` text NOT NULL,
  `discussion_text` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`discussion_id`),
  KEY `discussion_ibfk_1` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discussion`
--

INSERT INTO `discussion` (`discussion_id`, `discussion_title`, `discussion_text`, `created_at`, `user_id`) VALUES
(22, 'Was mathematics invented or discovered?', 'Both discovered and invented. When humans perceive the world through consciousness, everything is an abstract entity without a pre-defined representation. When we discover something in the world (such as ability, physical object, event, causality, pattern, etc.), we start to use our minds to describe it. Our consciousness will create personal ‘representations’ of everything for reasoning and thinking. The moment we begin the perception, we start to invent our ‘representations.’ We show our invented ‘representations’ through gestures, language, symbols, and drawings when we communicate to others. ‘Representations’ are actually ‘tools’’. With the invention of these ‘tools’, we can describe, manipulate and go beyond our thinking(e.g., with the help of paper/pencil) without being limited by our capacity of memory/consciousness.\\r\\n\\r\\nMathematics initially started when we discovered our ability to compare and accumulate. And then, we invent math ‘representations’ as ‘tools’ to assist us in advanced thinking.\\r\\n\\r\\n', '2024-08-23 16:24:44', 1),
(23, 'Understanding the Pythagorean Theorem', 'The Pythagorean Theorem is a fundamental principle in geometry that states that in a right-angled triangle, the square of the length of the hypotenuse (the side opposite the right angle) is equal to the sum of the squares of the lengths of the other two sides. This can be expressed as \\r\\n𝑎\\r\\n2\\r\\n+\\r\\n𝑏\\r\\n2\\r\\n=\\r\\n𝑐\\r\\n2\\r\\na \\r\\n2\\r\\n +b \\r\\n2\\r\\n =c \\r\\n2\\r\\n , where \\r\\n𝑐\\r\\nc is the length of the hypotenuse and \\r\\n𝑎\\r\\na and \\r\\n𝑏\\r\\nb are the lengths of the other two sides.', '2024-08-23 16:24:51', 1),
(24, 'Exploring the Basics of Calculus', 'Calculus is a branch of mathematics that studies continuous change. It is divided into two main areas: differential calculus, which deals with the concept of a derivative, and integral calculus, which focuses on the concept of an integral. Differential calculus is used to find rates of change, while integral calculus is used to find areas under curves.', '2024-08-23 16:24:58', 1),
(25, 'The Beauty and Applications of Fractals', 'Fractals are intricate geometric shapes that can be split into parts, each of which is a reduced-scale copy of the whole. They are used in various fields such as computer graphics, modeling of natural structures, and signal processing. The Mandelbrot set is a well-known example of a fractal with infinitely complex boundaries.', '2024-08-23 16:25:04', 1),
(26, 'Solving Linear Equations Made Simple', ' Linear equations are fundamental in algebra. They represent relationships between variables that can be expressed as \\r\\n𝑎\\r\\n𝑥\\r\\n+\\r\\n𝑏\\r\\n=\\r\\n𝑐\\r\\nax+b=c. To solve a linear equation, isolate the variable \\r\\n𝑥\\r\\nx by performing inverse operations. For example, in \\r\\n2\\r\\n𝑥\\r\\n+\\r\\n3\\r\\n=\\r\\n7\\r\\n2x+3=7, subtract 3 from both sides to get \\r\\n2\\r\\n𝑥\\r\\n=\\r\\n4\\r\\n2x=4, then divide by 2 to find \\r\\n𝑥\\r\\n=\\r\\n2\\r\\nx=2.', '2024-08-23 16:25:09', 1),
(27, 'Introduction to Probability', 'Probability theory deals with the likelihood of events occurring. It is quantified as a number between 0 and 1, where 0 means an event will not occur and 1 means it will definitely occur. Basic probability calculations involve determining the ratio of favorable outcomes to the total number of possible outcomes. For example, the probability of rolling a 3 on a fair six-sided die is \\r\\n1\\r\\n6\\r\\n6\\r\\n1\\r\\n​\\r\\n .', '2024-08-23 16:25:14', 1),
(28, 'Introduction to Probability', 'Probability theory deals with the likelihood of events occurring. It is quantified as a number between 0 and 1, where 0 means an event will not occur and 1 means it will definitely occur. Basic probability calculations involve determining the ratio of favorable outcomes to the total number of possible outcomes. For example, the probability of rolling a 3 on a fair six-sided die is \\r\\n1\\r\\n6\\r\\n6\\r\\n1\\r\\n​\\r\\n .', '2024-08-24 00:19:55', 1),
(29, 'Exploring the Geometry of Circles', 'Circles are a basic geometric shape with important properties. The circumference of a circle is given by \\r\\n2\\r\\n𝜋\\r\\n𝑟\\r\\n2πr, where \\r\\n𝑟\\r\\nr is the radius. The area is given by \\r\\n𝜋\\r\\n𝑟\\r\\n2\\r\\nπr \\r\\n2\\r\\n . Understanding these properties helps in solving problems related to circular objects and shapes. For example, calculating the area of a circular garden helps in determining how much material is needed for landscaping.', '2024-08-23 16:24:37', 1),
(30, 'Basics of Matrix Operations', 'Matrices are used in various mathematical applications including systems of linear equations and transformations. Key operations include addition, subtraction, and multiplication. The product of two matrices \\r\\n𝐴\\r\\nA and \\r\\n𝐵\\r\\nB is found by multiplying rows of \\r\\n𝐴\\r\\nA with columns of \\r\\n𝐵\\r\\nB. Understanding these operations is crucial in fields such as computer graphics and data analysis.', '2024-08-24 00:22:04', 1),
(31, 'Number Theory Fundamentals', 'Number theory explores properties of integers. Fundamental topics include prime numbers, divisibility, and congruences. For instance, the Fundamental Theorem of Arithmetic states that every integer greater than 1 is either a prime number or can be factorized into prime numbers uniquely. Number theory is foundational in cryptography and various algorithms.', '2024-08-24 00:22:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `faq_id` int NOT NULL AUTO_INCREMENT,
  `faq_title` text NOT NULL,
  `faq_contents` text NOT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`faq_id`, `faq_title`, `faq_contents`) VALUES
(9, 'Does the leaderboard score affect your matchmaking in PvP?', 'No, it does not, your opponent will either be random or the one you share your room password with.'),
(10, 'How is the target audience of this website?', 'Undergraduate students.'),
(11, 'What are the goals/aims in creating this website?', 'To increase student engagement and motivation in online learning.'),
(12, 'Are there any payment services?', 'As of right now, we do not have any service that involves payment, everything is free to access.'),
(13, 'Is this website available in other languages?', 'No, we only support English unfortunately.'),
(14, 'How do I contact support?', 'Log into your account and select submit ticket, insert your question and the support will reply to you within 5 working days.'),
(15, 'What subjects/topic is available within the chapters?', 'For now the only subject available is mathematics.'),
(16, 'Do you plan to update this service frequently?', 'Unfortunately we do not plan to update this website until further announcements.'),
(17, 'Is there any restriction when posting in forums?', 'We welcome all sorts of discussion as long as it does not involves sensitive topic.'),
(18, 'Are all levels unlocked in default?', 'Yes, all levels are accessible upon the creation of an account? However, do note that in PvP, the question pool are shuffled and it is advised to review all learning materials before attempting PvP.'),
(19, 'Is there any restriction when posting in forums?', 'We welcome all sorts of discussion as long as it does not involves sensitive topic.'),
(20, 'What subjects/topic is available within the chapters?', 'For now the only subject available is mathematics.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `feedback_text` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `user_id` int NOT NULL,
  `rating` mediumint NOT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `feedback_ibfk_1` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `feedback_text`, `created_at`, `user_id`, `rating`) VALUES
(6, 'The website was easy to navigate and the information was well-organized. Great job!', '2024-08-24 03:46:00', 1, 5),
(7, 'The gamification elements, like PvP and badges, made learning fun and engaging. I really enjoyed it!', '2024-08-24 03:47:35', 1, 5),
(8, 'The challenges were a bit too difficult at times. Could they be adjusted to different skill levels?', '2024-08-24 03:48:02', 1, 3),
(9, 'I like the variety of learning activities. However, I would appreciate more options for customization.', '2024-08-24 03:48:18', 1, 4),
(10, 'The social features, like leaderboards and PvP added a competitive element that motivated me to learn.', '2024-08-24 03:50:58', 8, 5),
(11, 'The system is too slow and often crashes. This is frustrating and hinders the learning process.', '2024-08-24 03:51:17', 8, 2),
(12, 'The learning content could be more interactive. More RPG elements can make the quiz more interesting.', '2024-08-24 03:52:13', 8, 2),
(13, 'The interface is quite distracting sometimes.  Less elements in some parts of the system could make the players more focused on the questions/PvP.', '2024-08-24 03:55:34', 10, 2),
(14, 'The background music is very distracting when answering questions/PvP.', '2024-08-24 03:56:12', 10, 1),
(15, 'The game mechanics sometimes felt repetitive and got boring after a while.', '2024-08-24 03:56:44', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `game_sessions`
--

DROP TABLE IF EXISTS `game_sessions`;
CREATE TABLE IF NOT EXISTS `game_sessions` (
  `session_id` int NOT NULL AUTO_INCREMENT,
  `player1_id` int NOT NULL,
  `player2_id` int DEFAULT NULL,
  `game_state` enum('in_progress','completed','','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `roompassword` int NOT NULL,
  `player1_timetaken` time DEFAULT NULL,
  `player2_timetaken` time DEFAULT NULL,
  `player1_correct_answers` int DEFAULT NULL,
  `player2_correct_answers` int DEFAULT NULL,
  `player1_pvpEXP` int DEFAULT NULL,
  `player2_pvpEXP` int DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `game_sessions_ibfk_1` (`player1_id`),
  KEY `game_sessions_ibfk_2` (`player2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `game_sessions`
--

INSERT INTO `game_sessions` (`session_id`, `player1_id`, `player2_id`, `game_state`, `roompassword`, `player1_timetaken`, `player2_timetaken`, `player1_correct_answers`, `player2_correct_answers`, `player1_pvpEXP`, `player2_pvpEXP`) VALUES
(65, 1, 8, 'completed', 0, '00:00:06', '00:00:01', 0, 0, 10, 20),
(66, 1, 8, 'completed', 0, '00:00:37', '00:06:12', 0, 1, NULL, NULL),
(67, 1, NULL, 'in_progress', 831116, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 1, NULL, 'in_progress', 221060, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 8, NULL, 'in_progress', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 8, NULL, 'in_progress', 665910, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 8, NULL, 'in_progress', 985845, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 10, NULL, 'in_progress', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 10, NULL, 'in_progress', 518916, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 10, NULL, 'in_progress', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 1, NULL, 'in_progress', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 8, NULL, 'in_progress', 445670, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 8, NULL, 'in_progress', 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

DROP TABLE IF EXISTS `leaderboard`;
CREATE TABLE IF NOT EXISTS `leaderboard` (
  `leaderboard_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `totalexp` int NOT NULL,
  PRIMARY KEY (`leaderboard_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leaderboard`
--

INSERT INTO `leaderboard` (`leaderboard_id`, `user_id`, `user_name`, `totalexp`) VALUES
(56, 8, 'player1', 37),
(57, 9, 'player2', 14),
(58, 10, 'player3', 15),
(59, 13, 'player4', 15),
(60, 14, 'player5', 15),
(61, 1, 'teh', 10);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `level_id` int NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `level_name`) VALUES
(58, 'Functions (1)'),
(59, 'Functions (2)'),
(60, 'Shapes (1)'),
(61, 'Shapes (2)'),
(62, 'Shapes (3)'),
(67, 'Shapes (4)'),
(68, 'Shapes (5)'),
(69, 'Shapes (6)'),
(70, 'Trigonometry (1)'),
(71, 'Trigonometry (2)'),
(76, 'Test1');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
CREATE TABLE IF NOT EXISTS `materials` (
  `material_id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`material_id`, `title`, `description`, `created_at`) VALUES
(11, 'Shape (1) Notes', 'Notes 1 for Shape', '2024-08-24 14:05:33'),
(12, 'Shape (2) Notes', 'Notes 2 for Shapes', '2024-08-24 14:18:00'),
(13, 'Shape (3) Notes', 'Notes 3 for Shapes', '2024-08-24 14:18:23'),
(14, 'Shape (4) Notes', 'Notes 4 for Shapes', '2024-08-24 14:18:42'),
(15, 'Quadratic Functions (1)', 'Notes  for Quadratic Functions', '2024-08-24 14:19:34'),
(16, 'Trigonometry (1)', 'Notes for Trigonometry', '2024-08-24 14:20:06'),
(19, 'Quadratic Equations Video Tutorial (1)', 'How To Solve Quadratic Equations By Factoring - Quick & Simple! | Algebra Online Course by The Organic Chemistry Tutor.', '2024-08-25 05:02:58'),
(20, 'Shapes Video Tutorial (1)', 'Area of a Rectangle, Triangle, Circle & Sector, Trapezoid, Square, Parallelogram, Rhombus, Geometry by The Organic Chemistry Tutor.', '2024-08-25 05:04:31'),
(21, 'Shapes Video Tutorial (2)', 'Introduction to Geometry by The Organic Chemistry Tutor.', '2024-08-25 05:06:14'),
(22, 'Trigonometry Video Tutorial (1)', 'Trigonometry For Beginners!', '2024-08-25 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `media_id` int NOT NULL AUTO_INCREMENT,
  `media_type` enum('image','video','slides','') NOT NULL,
  `media_URL` varchar(255) NOT NULL,
  `material_id` int DEFAULT NULL,
  `discussion_id` int DEFAULT NULL,
  `comment_id` int DEFAULT NULL,
  `question_id` int DEFAULT NULL,
  `response_id` int DEFAULT NULL,
  `badge_id` int DEFAULT NULL,
  `faq_id` int DEFAULT NULL,
  PRIMARY KEY (`media_id`),
  KEY `media_ibfk_1` (`discussion_id`),
  KEY `comment_id` (`comment_id`),
  KEY `material_id` (`material_id`),
  KEY `question_id` (`question_id`),
  KEY `response_id` (`response_id`),
  KEY `badge_id` (`badge_id`),
  KEY `faq_id` (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`media_id`, `media_type`, `media_URL`, `material_id`, `discussion_id`, `comment_id`, `question_id`, `response_id`, `badge_id`, `faq_id`) VALUES
(60, 'image', 'Q14.jpeg', NULL, NULL, NULL, 115, NULL, NULL, NULL),
(72, 'image', 'Q13.png', NULL, NULL, NULL, 134, NULL, NULL, NULL),
(75, 'image', 'Q17.jpeg', NULL, NULL, NULL, 138, NULL, NULL, NULL),
(116, 'image', 'Q9.JPG', NULL, NULL, NULL, 194, NULL, NULL, NULL),
(120, 'image', 'Q15.JPG', NULL, NULL, NULL, 199, NULL, NULL, NULL),
(123, 'image', 'Q18.JPG', NULL, NULL, NULL, 202, NULL, NULL, NULL),
(127, 'image', 'uploads/badge_8.PNG', NULL, NULL, NULL, NULL, NULL, 8, NULL),
(128, 'image', 'uploads/badge_9.PNG', NULL, NULL, NULL, NULL, NULL, 9, NULL),
(129, 'image', 'uploads/badge_10.PNG', NULL, NULL, NULL, NULL, NULL, 10, NULL),
(130, 'image', 'uploads/badge_11.PNG', NULL, NULL, NULL, NULL, NULL, 11, NULL),
(131, 'image', 'uploads/badge_12.PNG', NULL, NULL, NULL, NULL, NULL, 12, NULL),
(132, 'image', 'uploads/badge_13.PNG', NULL, NULL, NULL, NULL, NULL, 13, NULL),
(133, 'image', 'uploads/badge_14.PNG', NULL, NULL, NULL, NULL, NULL, 14, NULL),
(134, 'image', 'uploads/badge_15.PNG', NULL, NULL, NULL, NULL, NULL, 15, NULL),
(135, 'image', 'uploads/badge_16.PNG', NULL, NULL, NULL, NULL, NULL, 16, NULL),
(136, 'image', 'uploads/badge_17.PNG', NULL, NULL, NULL, NULL, NULL, 17, NULL),
(140, 'image', 'question_102_1724499669.png', NULL, NULL, NULL, 102, NULL, NULL, NULL),
(141, 'image', 'question_103_1724499721.png', NULL, NULL, NULL, 103, NULL, NULL, NULL),
(142, 'image', 'question_104_1724499746.png', NULL, NULL, NULL, 104, NULL, NULL, NULL),
(143, 'image', 'question_105_1724499758.png', NULL, NULL, NULL, 105, NULL, NULL, NULL),
(144, 'image', 'question_106_1724499770.png', NULL, NULL, NULL, 106, NULL, NULL, NULL),
(145, 'image', 'question_107_1724499795.png', NULL, NULL, NULL, 107, NULL, NULL, NULL),
(147, 'image', 'question_108_1724499832.png', NULL, NULL, NULL, 108, NULL, NULL, NULL),
(148, 'image', 'question_120_1724499925.jpeg', NULL, NULL, NULL, 120, NULL, NULL, NULL),
(149, 'image', 'question_122_1724500143.png', NULL, NULL, NULL, 122, NULL, NULL, NULL),
(150, 'image', 'question_123_1724500154.png', NULL, NULL, NULL, 123, NULL, NULL, NULL),
(151, 'image', 'question_124_1724500170.png', NULL, NULL, NULL, 124, NULL, NULL, NULL),
(152, 'image', 'question_125_1724500189.png', NULL, NULL, NULL, 125, NULL, NULL, NULL),
(153, 'image', 'question_126_1724500205.png', NULL, NULL, NULL, 126, NULL, NULL, NULL),
(154, 'image', 'question_127_1724500215.png', NULL, NULL, NULL, 127, NULL, NULL, NULL),
(155, 'image', 'question_128_1724500230.png', NULL, NULL, NULL, 128, NULL, NULL, NULL),
(156, 'image', 'question_129_1724500280.png', NULL, NULL, NULL, 129, NULL, NULL, NULL),
(157, 'image', 'question_132_1724500315.png', NULL, NULL, NULL, 132, NULL, NULL, NULL),
(158, 'image', 'question_135_1724500400.png', NULL, NULL, NULL, 135, NULL, NULL, NULL),
(159, 'image', 'question_137_1724500423.png', NULL, NULL, NULL, 137, NULL, NULL, NULL),
(160, 'image', 'question_139_1724500469.jpeg', NULL, NULL, NULL, 139, NULL, NULL, NULL),
(161, 'image', 'question_140_1724500483.jpeg', NULL, NULL, NULL, 140, NULL, NULL, NULL),
(162, 'image', 'question_141_1724500495.jpeg', NULL, NULL, NULL, 141, NULL, NULL, NULL),
(163, 'image', 'question_142_1724500524.jpeg', NULL, NULL, NULL, 142, NULL, NULL, NULL),
(164, 'image', 'question_143_1724500542.jpeg', NULL, NULL, NULL, 143, NULL, NULL, NULL),
(165, 'image', 'question_144_1724500559.png', NULL, NULL, NULL, 144, NULL, NULL, NULL),
(166, 'image', 'question_145_1724500572.png', NULL, NULL, NULL, 145, NULL, NULL, NULL),
(167, 'image', 'question_146_1724500593.png', NULL, NULL, NULL, 146, NULL, NULL, NULL),
(168, 'image', 'question_147_1724500614.png', NULL, NULL, NULL, 147, NULL, NULL, NULL),
(169, 'image', 'question_148_1724500626.png', NULL, NULL, NULL, 148, NULL, NULL, NULL),
(170, 'image', 'question_149_1724500646.png', NULL, NULL, NULL, 149, NULL, NULL, NULL),
(171, 'image', 'question_157_1724500713.png', NULL, NULL, NULL, 157, NULL, NULL, NULL),
(172, 'image', 'question_158_1724500769.png', NULL, NULL, NULL, 158, NULL, NULL, NULL),
(173, 'image', 'question_159_1724500783.png', NULL, NULL, NULL, 159, NULL, NULL, NULL),
(174, 'image', 'question_160_1724500794.png', NULL, NULL, NULL, 160, NULL, NULL, NULL),
(175, 'image', 'question_161_1724500808.png', NULL, NULL, NULL, 161, NULL, NULL, NULL),
(176, 'image', 'question_162_1724500819.png', NULL, NULL, NULL, 162, NULL, NULL, NULL),
(177, 'image', 'question_163_1724500835.jpeg', NULL, NULL, NULL, 163, NULL, NULL, NULL),
(178, 'image', 'question_164_1724500852.jpeg', NULL, NULL, NULL, 164, NULL, NULL, NULL),
(179, 'image', 'question_166_1724500878.png', NULL, NULL, NULL, 166, NULL, NULL, NULL),
(180, 'image', 'question_167_1724500891.png', NULL, NULL, NULL, 167, NULL, NULL, NULL),
(181, 'image', 'question_168_1724500905.png', NULL, NULL, NULL, 168, NULL, NULL, NULL),
(182, 'image', 'question_169_1724500916.png', NULL, NULL, NULL, 169, NULL, NULL, NULL),
(183, 'image', 'question_170_1724500933.png', NULL, NULL, NULL, 170, NULL, NULL, NULL),
(184, 'image', 'question_171_1724500948.png', NULL, NULL, NULL, 171, NULL, NULL, NULL),
(185, 'image', 'question_172_1724500962.png', NULL, NULL, NULL, 172, NULL, NULL, NULL),
(186, 'image', 'question_174_1724500978.png', NULL, NULL, NULL, 174, NULL, NULL, NULL),
(187, 'image', 'question_177_1724501005.png', NULL, NULL, NULL, 177, NULL, NULL, NULL),
(188, 'image', 'question_178_1724501019.png', NULL, NULL, NULL, 178, NULL, NULL, NULL),
(189, 'image', 'question_179_1724501034.png', NULL, NULL, NULL, 179, NULL, NULL, NULL),
(190, 'image', 'question_180_1724501049.png', NULL, NULL, NULL, 180, NULL, NULL, NULL),
(191, 'image', 'question_181_1724501064.png', NULL, NULL, NULL, 181, NULL, NULL, NULL),
(192, 'image', 'question_182_1724501077.jpeg', NULL, NULL, NULL, 182, NULL, NULL, NULL),
(193, 'image', 'question_185_1724501087.png', NULL, NULL, NULL, 185, NULL, NULL, NULL),
(194, 'image', 'question_188_1724501161.png', NULL, NULL, NULL, 188, NULL, NULL, NULL),
(195, 'image', 'question_189_1724501170.jpeg', NULL, NULL, NULL, 189, NULL, NULL, NULL),
(196, 'image', 'question_190_1724501181.png', NULL, NULL, NULL, 190, NULL, NULL, NULL),
(197, 'image', 'question_191_1724501195.png', NULL, NULL, NULL, 191, NULL, NULL, NULL),
(198, 'image', 'question_192_1724501213.png', NULL, NULL, NULL, 192, NULL, NULL, NULL),
(199, 'image', 'question_193_1724501232.png', NULL, NULL, NULL, 193, NULL, NULL, NULL),
(200, 'image', 'question_195_1724501267.png', NULL, NULL, NULL, 195, NULL, NULL, NULL),
(201, 'image', 'question_196_1724501288.png', NULL, NULL, NULL, 196, NULL, NULL, NULL),
(202, 'image', 'question_198_1724501307.png', NULL, NULL, NULL, 198, NULL, NULL, NULL),
(203, 'image', 'question_200_1724501348.png', NULL, NULL, NULL, 200, NULL, NULL, NULL),
(204, 'image', 'question_201_1724501375.png', NULL, NULL, NULL, 201, NULL, NULL, NULL),
(205, 'image', 'question_203_1724501422.jpeg', NULL, NULL, NULL, 203, NULL, NULL, NULL),
(206, 'image', 'question_204_1724501435.png', NULL, NULL, NULL, 204, NULL, NULL, NULL),
(207, 'image', 'question_205_1724501447.png', NULL, NULL, NULL, 205, NULL, NULL, NULL),
(208, 'slides', 'Shapes Chapter 1.pdf', 11, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 'slides', 'Shapes Chapter 2.pdf', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(210, 'slides', 'Shapes Chapter 3.pdf', 13, NULL, NULL, NULL, NULL, NULL, NULL),
(211, 'slides', 'Shapes Chapter 4.pdf', 14, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 'slides', 'Quadratic Function.pdf', 15, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 'slides', 'Trigonometry.pdf', 16, NULL, NULL, NULL, NULL, NULL, NULL),
(216, 'video', 'uploads/Quadratic Video (1).mp4', 19, NULL, NULL, NULL, NULL, NULL, NULL),
(217, 'video', 'uploads/Shapes Video (2).mp4', 20, NULL, NULL, NULL, NULL, NULL, NULL),
(218, 'video', 'uploads/Shapes Video (1).mp4', 21, NULL, NULL, NULL, NULL, NULL, NULL),
(219, 'video', 'uploads/Trigonometry Video (1).mp4', 22, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` int NOT NULL AUTO_INCREMENT,
  `option_text` text NOT NULL,
  `iscorrect` tinyint NOT NULL,
  `question_id` int NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `options_ibfk_1` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=451 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_text`, `iscorrect`, `question_id`) VALUES
(230, '-13', 1, 113),
(231, '-12', 0, 113),
(232, '13', 0, 113),
(233, '12', 0, 113),
(234, 'x = 1, x = -3', 0, 114),
(235, 'x = -1, x = 3', 1, 114),
(236, 'x = 1, x = 3', 0, 114),
(237, 'x = -1, x = -3', 0, 114),
(238, 'I', 0, 115),
(239, 'I and II', 0, 115),
(240, 'I and III', 1, 115),
(241, 'III', 0, 115),
(242, '1', 1, 116),
(243, '5', 0, 116),
(244, '-1', 0, 116),
(245, '-5', 0, 116),
(246, '0 ≤ f(x) ≤ 6 ', 0, 117),
(247, '1 ≤ f(x) ≤ 4', 0, 117),
(248, '1 ≤ f(x) ≤ 6', 1, 117),
(249, '4 ≤ f(x) ≤ 6', 0, 117),
(250, '-6', 1, 118),
(251, '-1/3', 0, 118),
(252, '6', 0, 118),
(253, '3', 0, 118),
(254, '2', 1, 119),
(255, '1', 0, 119),
(256, '3', 0, 119),
(257, '-2', 0, 119),
(258, '5', 1, 120),
(259, '7', 0, 120),
(260, '4', 0, 120),
(261, '8', 0, 120),
(262, '-1, 0, 1, 2', 0, 121),
(263, '1, 2, 5', 0, 121),
(264, '{-1, 0, 1, 2}', 1, 121),
(265, '{1, 2, 5}', 0, 121),
(266, '4', 1, 130),
(267, '6', 0, 130),
(268, '3', 0, 130),
(269, '9', 0, 130),
(270, 'It has two sides that match', 0, 131),
(271, 'It has no sides that match', 0, 131),
(272, 'It has all 3 sides that match', 1, 131),
(273, 'It has four sides that match', 0, 131),
(274, 'Right', 0, 132),
(275, 'Isosceles', 0, 132),
(276, 'Equilateral', 0, 132),
(277, 'Scalene', 1, 132),
(278, 'Right', 0, 133),
(279, 'Isosceles', 1, 133),
(280, 'Equilateral', 0, 133),
(281, 'Scalene', 0, 133),
(282, '9°', 0, 134),
(283, '48°', 1, 134),
(284, '97°', 0, 134),
(285, '79°', 0, 134),
(286, '89', 1, 135),
(287, '119', 0, 135),
(288, '211', 0, 135),
(289, '81', 0, 135),
(290, 'Octagon', 1, 136),
(291, 'Hexagon', 0, 136),
(292, 'Pentagon', 0, 136),
(293, 'Triangle', 0, 136),
(294, '140 degrees', 0, 137),
(295, '16 degrees', 0, 137),
(296, '40 degrees', 1, 137),
(297, '90 degrees', 0, 137),
(298, '21°', 0, 138),
(299, '31°', 0, 138),
(300, '50°', 0, 138),
(301, '30°', 1, 138),
(302, '110°', 0, 139),
(303, '230°', 1, 139),
(304, '120°', 0, 139),
(305, '135°', 0, 139),
(306, '50°', 0, 140),
(307, '55°', 0, 140),
(308, '65°', 0, 140),
(309, '75°', 1, 140),
(310, '50°', 0, 141),
(311, '60°', 0, 141),
(312, '70°', 0, 141),
(313, '80°', 1, 141),
(314, '115°', 0, 142),
(315, '230°', 1, 142),
(316, '180°', 0, 142),
(317, '250°', 0, 142),
(318, '24°', 1, 143),
(319, '25°', 0, 143),
(320, '26°', 0, 143),
(321, '28°', 0, 143),
(322, 'Radius', 1, 150),
(323, 'Chord', 0, 150),
(324, 'Diameter', 0, 150),
(325, 'Tangent', 0, 150),
(326, 'Radius', 0, 151),
(327, 'Chord', 0, 151),
(328, 'Diameter', 1, 151),
(329, 'Tangent', 0, 151),
(330, 'C = r^2', 0, 152),
(331, 'C = π', 0, 152),
(332, 'C = 2π x r', 1, 152),
(333, 'C = π x d', 0, 152),
(343, '90', 0, 156),
(344, '180', 0, 156),
(345, '270', 0, 156),
(346, '360', 1, 156),
(347, 'BC', 0, 157),
(348, 'BCD', 1, 157),
(349, 'CD', 0, 157),
(350, 'AB', 0, 157),
(351, '148cm2', 1, 163),
(352, '150cm2', 0, 163),
(353, '120cm2', 0, 163),
(354, '138cm2', 0, 163),
(355, 'Net', 1, 164),
(356, 'Triangle', 0, 164),
(357, 'Geometry', 0, 164),
(358, 'y = mx + b', 0, 164),
(359, '1/2 (Base) (Height)', 1, 165),
(360, '1/2 (Base) (Width)', 0, 165),
(361, 'Length x width', 0, 165),
(362, 'Base x Height', 0, 165),
(363, '468 in2', 1, 166),
(364, '540 in2', 0, 166),
(365, '576 in2', 0, 166),
(366, '700 in2', 0, 166),
(367, '140.25 cm2', 0, 167),
(368, '439.82 cm2', 1, 167),
(369, '219.91 cm2', 0, 167),
(370, '192.25 cm2', 0, 167),
(371, '144 m2', 0, 168),
(372, '36 m2', 0, 168),
(373, '864 m2', 1, 168),
(374, '216 m2', 0, 168),
(375, '179.50 cm3', 1, 182),
(376, '205.15 cm3', 0, 182),
(377, '51.29 cm3', 0, 182),
(378, '1436.03 cm3', 0, 182),
(379, '561.5 in³', 0, 183),
(380, '267.9 in³', 1, 183),
(381, '201 in³', 0, 183),
(382, '803.8 in³', 0, 183),
(383, 'V = Lw', 0, 184),
(384, 'V = Lwh', 1, 184),
(385, 'V = 1/2 (bh)', 0, 184),
(386, 'V = 2 (√r)', 0, 184),
(387, '72', 0, 185),
(388, '27', 1, 185),
(389, '1.5', 0, 185),
(390, '272', 0, 185),
(391, 'Any triangle ever', 0, 186),
(392, 'Right triangle only', 1, 186),
(393, 'Non-right triangle only', 0, 186),
(394, 'Never', 0, 186),
(395, 'Sine', 0, 187),
(396, 'Tangent', 0, 187),
(397, 'Cosine', 0, 187),
(398, 'Inverse cosine', 0, 187),
(399, 'A', 1, 188),
(400, 'B', 0, 188),
(401, 'C', 0, 188),
(402, 'D', 0, 188),
(403, 'sin(37) = x/14', 0, 189),
(404, 'tan(37) = x/14', 0, 189),
(405, 'cos(37) = x/14', 1, 189),
(406, 'sin(37) = 14/x', 0, 189),
(407, '24.40', 0, 190),
(408, '290.4', 0, 190),
(409, '4.92', 0, 190),
(410, '29.75', 1, 190),
(411, 'sin<D = 4/5 = 0.8', 1, 191),
(412, 'sin<D = 5/4 = 1.25 ', 0, 191),
(413, 'sin<D = 3/5= 0.6', 0, 191),
(414, 'sin <D = 5/3 = 1.7', 0, 191),
(415, 'tan<B = 3/2 = 1.5', 0, 192),
(416, 'tan<B = 2/3.6 = 0.55', 0, 192),
(417, 'tan<B = 2/3 = 0.6', 1, 192),
(418, 'tan<B = 3.6/3 =1.2', 0, 192),
(419, 'sin 60 = 10/AB', 0, 193),
(420, 'cos 60 = 10 / AB', 0, 193),
(421, 'sin 60 = AB /10', 1, 193),
(422, 'tan 60 = AB /10', 0, 193),
(423, 'sin 60 = h/1000', 0, 194),
(424, 'sin 60 = 1000/h', 0, 194),
(425, 'cos 60 = h/1000', 1, 194),
(426, 'tan 60 = 1000/h', 0, 194),
(427, 'Law of Sines', 1, 195),
(428, 'Laws of Cosines', 0, 195),
(429, 'Law of the Jungle', 0, 195),
(430, 'SOHCAHTOA', 0, 195),
(431, '70.7 degrees', 0, 196),
(432, '74.5 degrees', 0, 196),
(433, '77.8 degrees', 1, 196),
(434, '80.2 degrees', 0, 196),
(435, 'c2 = a2 + b2 - 4ac + cosA', 0, 197),
(436, 'c2 = a2 - b2 - 2abcosC', 0, 197),
(437, 'c2 = a2 + b2 - 2abcosC', 1, 197),
(438, '(cos A)/a = (cos B)/b', 0, 197),
(439, '2.9 miles', 0, 198),
(440, '3.2 miles', 1, 198),
(441, '3.6 miles', 0, 198),
(442, '3.9 miles', 0, 198),
(443, '38.5°', 0, 199),
(444, '41.7°', 1, 199),
(445, '45.1°', 0, 199),
(446, '51.3°', 0, 199),
(447, '6.57 cm', 0, 200),
(448, '7.05 cm', 0, 200),
(449, '7.44 cm', 1, 200),
(450, '8.33 cm', 0, 200);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `question_text` text NOT NULL,
  `level_id` int NOT NULL,
  `question_type` varchar(255) NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `level_id` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question_text`, `level_id`, `question_type`) VALUES
(102, 'Diagram below shows the relation between set M and set N in the graph form.\r\nState the range of the relation.\r\n', 58, 'fill_blank'),
(103, 'Diagram below shows the relation between set M and set N in the graph form.\r\nState the type of the relation between set M and set N.\r\n', 58, 'fill_blank'),
(104, 'Diagram below shows the relation between set P and set Q.\r\nState the object of 3.\r\n', 58, 'fill_blank'),
(105, 'Diagram below shows the relation between set P and set Q.\r\nState the range of the relation.\r\n', 58, 'fill_blank'),
(106, 'Diagram below shows the function g : x → x – 2k, where k is a constant.\r\nFind the value of k.\r\n', 58, 'fill_blank'),
(107, 'Diagram below shows the relation between set M and set N in the arrow diagram.\r\nRepresent the relation in the form of ordered pairs.\r\n', 58, 'fill_blank'),
(108, 'Diagram below shows the relation between set M and set N in the arrow diagram.\r\nState the domain of the relation.\r\n', 58, 'fill_blank'),
(109, 'It is given the functions g(x) = 3x and h(x) = m – nx, where m and n are constants.\r\nExpress m in terms of n such that hg(1) = 4.\r\n', 58, 'fill_blank'),
(110, 'Given the function g : x → 3x – 2, find the value of x when g(x) maps onto itself.', 58, 'fill_blank'),
(111, 'Given the functions f:x → px + 1, g:x → 3x – 5 and fg(x) = 3px + q.  \r\nExpress p in terms of q.\r\n', 58, 'fill_blank'),
(112, 'Given the functions h : x → 3x + 1, and gh : x → 9x2 + 6x – 4, find h-1 (x).', 59, 'fill_blank'),
(113, 'Given g(x)=5x+2, f(x) = x2−4x+1. Find gf (2).', 59, 'mcq'),
(114, 'Given g : x ⟶ 3/ (x−2), x≠2. Find the values of x if g(x) = x ', 59, 'mcq'),
(115, 'Which of the following graph(s) represents a one-to-one relation?', 59, 'mcq'),
(116, 'Given that h(x)=∣2x−7∣, find the image of 3.', 59, 'mcq'),
(117, 'Determine the range of f(x)=∣2x−3∣+1 when given 0 ≤ x ≤ 4.', 59, 'mcq'),
(118, 'Given f(x)=2x+9, find the value of f−1(−3). ', 59, 'mcq'),
(119, 'Given g(x)=12−5x , find the object which is mapped onto itself under the function of g.', 59, 'mcq'),
(120, 'The diagram shows a linear function of g. What is the value of k', 59, 'mcq'),
(121, 'Which of the following is the domain for the following diagram?', 59, 'mcq'),
(122, 'Diagram below shows a pentagon PQRST. TPU and RSV are straight lines.', 60, 'fill_blank'),
(123, 'In Diagram below, PQRSTU is a hexagon. APQ and BTS are straight lines.', 60, 'fill_blank'),
(124, 'Diagram below shows a regular hexagon PQRSTU. PUV is a straight line.', 60, 'fill_blank'),
(125, 'In the diagram below, KLMNP is a regular pentagon. LKS and MNQ are straight lines.', 60, 'fill_blank'),
(126, 'In Diagram below, PQR is an isosceles triangle and PRU is a straight line.', 60, 'fill_blank'),
(127, 'In diagram below, PQRSTU is a regular hexagon QUV is a straight line.', 60, 'fill_blank'),
(128, 'In diagram below, PQRSTU is a hexagon. UPE, PQF, QRG, RSH and UTJ are straight lines.', 60, 'fill_blank'),
(129, 'In Diagram below, A, B, C, D and E are vertices of a 9 sided regular polygon.', 60, 'fill_blank'),
(130, 'How many sides does a Quadrilateral have?', 60, 'mcq'),
(131, 'Why is a Triangle called Equilateral?', 60, 'mcq'),
(132, 'What is the name of this kind of triangle?', 61, 'mcq'),
(133, 'What is the name of this kind of triangle that has two side of equal angle?', 61, 'mcq'),
(134, 'Find the measure of angle E.', 61, 'mcq'),
(135, 'Find the measure of the indicated angle. ', 61, 'mcq'),
(136, 'A shape with 8 sides is called a __________.', 61, 'mcq'),
(137, 'Find the measure of angle D.', 61, 'mcq'),
(138, 'In the diagram, ABC is an equilateral triangle.\r\nThe value of p is\r\n', 61, 'mcq'),
(139, 'In the diagram below, CDE is a straight line.\r\nFind the value of q.\r\n', 61, 'mcq'),
(140, 'In the diagram below, HIJ is an isosceles triangle and HI = HJ and IJL is a straight line. \r\nFind the value of y.\r\n', 61, 'mcq'),
(141, 'In the diagram below, ABCD is a parallelogram.\r\nThe value of  ∠BCF∠BCF  is\r\n', 61, 'mcq'),
(142, 'In the diagram below, KLM and ILJ are straight lines\r\nCalculate the value of a + b + c + d.\r\n', 62, 'mcq'),
(143, 'In the diagram, PQRS is a rhombus.\r\nFind the value of  ∠TSQ∠TSQ\r\n\r\n', 62, 'mcq'),
(144, 'Diagram below shows a circle with centre O.\r\nThe radius of the circle is 35 cm.\r\nCalculate the length, in cm, of the major arc AB.\r\n(Use π = 22/7)\r\n\r\n', 62, 'fill_blank'),
(145, 'In diagram below, O is the centre of the circle. SPQ and POQ are straight lines.\r\nThe length of PO is 8 cm and the length of POQ is 18 cm.\r\nCalculate the length, in cm, of SPT.\r\n', 62, 'fill_blank'),
(146, 'Diagram below shows two circles. The bigger circle has a radius of 14 cm with its centre at O.\r\nThe smaller circle passes through O and touches the bigger circle.\r\nCalculate the area of the shaded region.\r\n(Use π = 22/7)\r\n', 62, 'fill_blank'),
(147, 'In diagram below, ABC is an arc of a circle centre O.\r\nThe radius of the circle is 14 cm and AD = 2 DE.\r\nCalculate the perimeter, in cm, of the whole diagram.\r\n(Use π = 22/7)\r\n', 62, 'fill_blank'),
(148, 'In diagram below, KLMN is a square and KLON is a quadrant of a circle with centre K.\r\nCalculate the area, in cm2, of the coloured region.\r\n(Use π = 22/7)\r\n', 62, 'fill_blank'),
(149, 'The Town Council plans to build an equilateral triangle platform in the middle of a roundabout. The diameter of circle RST is 24 m and the perpendicular distance from R to the line ST is 18 m. as shown in Diagram below.\r\nFind the perimeter of the platform.\r\n', 62, 'fill_blank'),
(150, 'The line segment between the center and a point on the circle is:', 62, 'mcq'),
(151, 'A line segment between two points on the circle which passes through the center is:', 62, 'mcq'),
(152, 'How do I find the circumference of a circle?', 62, 'mcq'),
(156, 'The sum of the measures of the central angles in a circle is', 67, 'mcq'),
(157, 'Name the major arc', 67, 'mcq'),
(158, 'Diagram below shows closed right cylinder.\r\nCalculate the total surface area, in cm2, of the cylinder.  \r\n(π = 227)\r\n', 67, 'fill_blank'),
(159, 'Diagram below shows a right prism with right-angled triangle ABC as its uniform cross section.\r\nCalculate the total surface area, in cm2, of the prism.\r\n', 67, 'fill_blank'),
(160, 'The diagram shows a cone. The radius of its base is 3.5 cm and its slant height is 6 cm. Find the area of its curved surface.\r\n( π = 22/7)\r\n', 67, 'fill_blank'),
(161, 'Sphere below has a surface area of 221.76 cm2.\r\nCalculate its radius.\r\n', 67, 'fill_blank'),
(162, 'Diagram below shows a right pyramid with a square base.\r\nGiven the height of the pyramid is 4 cm.\r\nCalculate the total surface area, in cm2, of the right pyramid.\r\n', 67, 'fill_blank'),
(163, 'Find the surface area of this figure.', 67, 'mcq'),
(164, 'What do we call this object below?', 67, 'mcq'),
(165, 'How do you find the AREA of a TRIANGLE?', 67, 'mcq'),
(166, 'What is the total surface area of the triangular prism shown? ', 68, 'mcq'),
(167, 'Find the surface area', 68, 'mcq'),
(168, 'Find the surface area of a cube with side length 12 m.', 68, 'mcq'),
(169, 'The diagram below shows a cone with diameter 14 cm and height 6 cm.\r\nFind the volume of the cone, in cm3.\r\n', 68, 'fill_blank'),
(170, 'In the pyramid shown, the base is a rectangle.\r\nIf the volume is 20 cm2, find the height of the pyramid, in cm.\r\n', 68, 'fill_blank'),
(171, 'Diagram below shows a composite solid consisting of a right circular cone, a right circular cylinder and a hemisphere.\r\nThe volume of the cylinder is 1650 cm3. Calculate the height, in cm, of the cone.\r\n[Use π = 22/7]\r\n', 68, 'fill_blank'),
(172, 'The cross section of the prism shown is an isosceles triangle.\r\nThe volume of the prism, in cm3, is?\r\n', 68, 'fill_blank'),
(173, 'A right circular cone has a volume of 77 cm3 and a circular base of radius 3.5 cm. Calculate its height.', 68, 'fill_blank'),
(174, 'Puan Rafidah wants to prepare a right circular cone shaped container in order to fill it with candies for her family Hari Raya Celebration. Diagram below shows the net of a right circular cone.\r\nThe circumference of the lid is 44 cm and its height is 24 cm.\r\nCalculate the volume, in cm3, of the container.\r\n[π = 22/7]', 68, 'fill_blank'),
(175, 'A barrel contains 36 l of mineral water. ⅔ of the mineral water is mixed with 6 bottles of syrup, with a volume of 1.5 l each. The drink will be poured into a number of bottles with the same volume of 300 ml.\r\nHow many number of bottles needed?\r\n', 68, 'fill_blank'),
(176, 'A spherical balloon expands such that its volume increases from 36π cm3 to 288π cm3.\r\nFind the increment of the radius, in cm, of the balloon.\r\n', 69, 'fill_blank'),
(177, 'A boy is given three pieces of cards by his teacher. The cards consist of one rectangle card and two circle cards of the same size as shown in the diagram below. The circumference of each circle is 66 cm.\r\nHe is required to combine the cards to form a right circular cylinder.\r\nCalculate the volume, in cm3, of the right circular cylinder.\r\n', 69, 'fill_blank'),
(178, 'The diagram below shows cube P and cube Q with the total surface area of 726 cm2 and 1014 cm2 respectively.\r\nFind the difference in volume between cube P and cube Q.\r\n', 69, 'fill_blank'),
(179, 'Diagram shows a composite formed by joining a quarter cylinder and a right prism at the rectangular plane EFGH. The trapezium ABGF and the quarter circle FGK are the uniform cross sections of the solid.\r\nUsing   π = [22/7], calculate the volume, in  cm3, of the composite solid.\r\n', 69, 'fill_blank'),
(180, 'Diagram shows a cylindrical tank in a residential area which have 125 houses. Each house received equal volume of water.\r\nThe diameter of the cylindrical tank is 4 m. It is given that each house has a cuboid tank with a base area of 0.8 m2.\r\nUsing  [π = 22/7], calculate the height of the water level, in m, of each tank in the house.\r\n', 69, 'fill_blank'),
(181, 'Diagram shows a right cylinder with a diameter of ( y  + 4 ) cm.\r\nGiven the volume of the cylinder is 269.5 cm3 and by using  find the value of its radius.\r\n', 69, 'fill_blank'),
(182, 'Find the volume to the nearest hundredth using 3.14 instead of Pi.', 69, 'mcq'),
(183, 'What is the volume of a sphere with the radius of 4 inches ? Use 3.14 for pi.', 69, 'mcq'),
(184, 'What is the formula used to find the volume of a rectangular prism?', 69, 'mcq'),
(185, 'What is the volume of the cube (unit3)?', 69, 'mcq'),
(186, 'We can use SOH-CAH-TOA for...', 70, 'mcq'),
(187, 'The ratio in a right triangle of adjacent to hypotenuse is _____', 70, 'mcq'),
(188, 'Choose the correct ratio.', 70, 'mcq'),
(189, 'Choose the correct ratio.', 70, 'mcq'),
(190, 'Solve for x.', 70, 'mcq'),
(191, 'What is the ratio of Sin <D?', 70, 'mcq'),
(192, 'What is the ratio of tan <B?', 70, 'mcq'),
(193, 'Which trigonometric ratio you would use to find side AB?', 70, 'mcq'),
(194, 'Which trigonometric ratio should you use to find the height(h)?', 70, 'mcq'),
(195, 'Which Law would you use to find side length BC?', 70, 'mcq'),
(196, 'What is the measure of angle A? BC = 37 cm, AB = 29 cm and m<C = 50 degrees.', 71, 'mcq'),
(197, 'Which of the following formulas shows the Law of Cosines?', 71, 'mcq'),
(198, 'What is the distance across the river? The two sides are 4.15 miles and 5.33 miles. The included angle measure is 37 degrees.', 71, 'mcq'),
(199, 'Use the Law of Cosines to find the measure of angle x. The side lengths shown are 25, 32 and 37. <x is opposite the 25 length side.', 71, 'mcq'),
(200, 'What is the length of side x? x is opposite a 28 degree angle. 14 cm is the side length given.', 71, 'mcq'),
(201, 'Find K. Round to the nearest tenth.', 71, 'fill_blank'),
(202, 'Find x. Round to the nearest tenth.', 71, 'fill_blank'),
(203, 'Find A. Round to the nearest tenth', 71, 'fill_blank'),
(204, 'Ahmad is standing far from cell tower. He also sees the top of cell tower at angle of 78°. He knows that the cell tower is 275ft tall. How long is the cable from Ahmad?', 71, 'fill_blank'),
(205, 'Find x.', 71, 'fill_blank');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_progress','closed','') NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`ticket_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `subject`, `description`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(8, 'Website Crashing', 'The website keeps crashing when I try to access certain pages. I\'ve tried refreshing and clearing my cache, but it\'s still happening.', 'in_progress', '2024-08-23 20:03:33', '2024-08-24 04:44:54', 10),
(9, 'Login Issues', 'I\'m unable to log into my account. I\'ve tried resetting my password multiple times, but it\'s not working.', 'in_progress', '2024-08-23 20:04:40', '2024-08-24 04:44:29', 10),
(10, 'Slow Performance', 'The website is loading very slowly, even on a fast internet connection. This is making it difficult to use.', 'closed', '2024-08-23 20:05:00', '2024-08-24 04:43:51', 10),
(11, 'Video Playback Problems', 'Videos are not playing at all. I have a good internet connection.', 'closed', '2024-08-23 20:13:41', '2024-08-24 04:43:27', 1),
(12, 'Image Loading Errors', 'Images are not loading properly on some pages. They appear blank or broken.', 'in_progress', '2024-08-23 20:14:10', '2024-08-24 04:42:44', 1),
(13, 'Accessibility Issues', 'The website is difficult to use with assistive technology. Some elements are not accessible.', 'in_progress', '2024-08-23 20:15:08', '2024-08-24 04:41:50', 1),
(14, 'Feature Not Working', 'The adding discussion thread feature is not working as expected. I\'ve tried following the instructions, but it\'s not functioning properly.', 'in_progress', '2024-08-23 20:17:10', '2024-08-24 04:40:54', 8),
(15, 'Error Message', 'I\'m receiving an error message that says, “Session not found.”. I\'m not sure what to do.\"', 'closed', '2024-08-23 20:23:30', '2024-08-23 20:34:39', 8),
(16, 'Forum Posting Issues', 'I\'m unable to post in the forums. I keep getting an error message.', 'closed', '2024-08-23 20:24:11', '2024-08-23 20:27:44', 8),
(17, 'Website Navigation Issues', 'The website navigation is confusing and difficult to use. It\'s hard to find the information I need.', 'closed', '2024-08-23 20:26:33', '2024-08-23 20:27:18', 8);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_responses`
--

DROP TABLE IF EXISTS `ticket_responses`;
CREATE TABLE IF NOT EXISTS `ticket_responses` (
  `response_id` int NOT NULL AUTO_INCREMENT,
  `response_text` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `ticket_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`response_id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ticket_responses`
--

INSERT INTO `ticket_responses` (`response_id`, `response_text`, `created_at`, `ticket_id`, `user_id`) VALUES
(2, 'We apologize for the navigation issues. Our team is working on improving the website\'s user interface.', '2024-08-23 20:27:18', 17, 4),
(3, 'We\'re sorry about the forum posting issues. Please try refreshing the page and submitting your post again. If the problem continues, let us know.', '2024-08-23 20:27:44', 16, 4),
(4, 'Thank you for reporting the error message. Our team is investigating the issue. Please let us know if you encounter any further problems.', '2024-08-23 20:34:39', 15, 4),
(5, 'We\'ll be happy to assist you with the adding discussion thread feature. Please provide us with a detailed description of the issue and any relevant screenshots.', '2024-08-24 04:40:54', 14, 4),
(6, 'We apologize for any accessibility issues. We\'re committed to making our website accessible to all users. Please let us know about any specific accessibility problems you encounter.', '2024-08-24 04:41:50', 13, 4),
(7, 'We\'re sorry about the image loading errors. Please try clearing your browser cache and cookies. If the problem persists, let us know.', '2024-08-24 04:42:44', 12, 4),
(8, 'We apologize for the video playback issues. Please try clearing your browser cache and cookies. If the problem continues, let us know.', '2024-08-24 04:43:12', 11, 4),
(9, 'We\'re aware of the slow performance issue. Our technical team is investigating the cause and working on a solution.', '2024-08-24 04:43:40', 10, 4),
(10, 'We can help you reset your password. Please provide your email address and we\'ll send you a link through the email.', '2024-08-24 04:44:29', 9, 4),
(11, 'We apologize for the inconvenience. Our team is currently working on a fix for this issue. Please try again later today.', '2024-08-24 04:44:54', 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_password` char(50) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `user_gender` varchar(255) NOT NULL,
  `OTP` char(6) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`, `user_email`, `user_role`, `user_gender`, `OTP`) VALUES
(1, 'teh', '12345', 'heng.teh0205@gmail.com', 'player', 'Male', '866395'),
(2, 'zq', '12345', 'ZQliewzq@gmail.com', 'content_manager', 'male', '685084'),
(4, 'helpdesk1', '12345', 'helpdesk@mail.com', 'helpdesk_support', 'female', ''),
(8, 'player1', '12345', 'player1@mail.com', 'player', 'male', ''),
(9, 'player2', '12345', 'player2@mail.com', 'player', 'female', ''),
(10, 'player3', '12345', 'player3@gmail.com', 'player', 'male', '481339'),
(11, 'helpdesk2', '12345', 'helpdesk2@mail.com', 'helpdesk_support', 'male', ''),
(12, 'contentmanager2', '12345', 'contentmanager2@mail.com', 'content_manager', 'female', ''),
(13, 'player4', '12345', 'player4@mail.com', 'player', 'male', ''),
(14, 'player5', '12345', 'player5@mail.com', 'player', 'male', ''),
(15, 'Test', 'Test', 'Test@mail.com', 'player', 'prefer not to say', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_badge`
--

DROP TABLE IF EXISTS `user_badge`;
CREATE TABLE IF NOT EXISTS `user_badge` (
  `user_id` int NOT NULL,
  `badge_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`badge_id`),
  KEY `user_badge_ibfk_2` (`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_badge`
--

INSERT INTO `user_badge` (`user_id`, `badge_id`) VALUES
(8, 10),
(9, 10),
(10, 10),
(13, 10),
(14, 10),
(1, 11),
(8, 11),
(9, 11),
(10, 11),
(11, 11),
(12, 11),
(13, 11),
(14, 11),
(15, 11),
(8, 12);

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

DROP TABLE IF EXISTS `user_levels`;
CREATE TABLE IF NOT EXISTS `user_levels` (
  `user_id` int NOT NULL,
  `level_id` int NOT NULL,
  `isdone` tinyint NOT NULL,
  `completion_time` timestamp NOT NULL,
  `expAllocated` int NOT NULL,
  PRIMARY KEY (`user_id`,`level_id`),
  KEY `level_id` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`user_id`, `level_id`, `isdone`, `completion_time`, `expAllocated`) VALUES
(8, 58, 1, '2024-08-24 09:20:31', 10),
(8, 59, 1, '2024-08-24 09:30:18', 6),
(8, 68, 1, '2024-08-26 04:43:28', 1),
(8, 70, 1, '2024-08-24 15:39:51', 1),
(9, 58, 1, '2024-08-24 09:21:28', 10),
(9, 59, 1, '2024-08-24 09:31:48', 4),
(10, 58, 1, '2024-08-24 09:22:28', 10),
(10, 59, 1, '2024-08-24 09:32:27', 5),
(13, 58, 1, '2024-08-24 09:23:54', 10),
(13, 59, 1, '2024-08-24 09:33:06', 5),
(14, 58, 1, '2024-08-24 09:25:14', 10),
(14, 59, 1, '2024-08-24 09:33:43', 5);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `game_sessions`
--
ALTER TABLE `game_sessions`
  ADD CONSTRAINT `game_sessions_ibfk_1` FOREIGN KEY (`player1_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `game_sessions_ibfk_2` FOREIGN KEY (`player2_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`faq_id`) REFERENCES `faq` (`faq_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`discussion_id`) REFERENCES `discussion` (`discussion_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_3` FOREIGN KEY (`material_id`) REFERENCES `materials` (`material_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_4` FOREIGN KEY (`badge_id`) REFERENCES `badge` (`badge_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_5` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_6` FOREIGN KEY (`response_id`) REFERENCES `ticket_responses` (`response_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_7` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  ADD CONSTRAINT `ticket_responses_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`ticket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_badge`
--
ALTER TABLE `user_badge`
  ADD CONSTRAINT `user_badge_ibfk_1` FOREIGN KEY (`badge_id`) REFERENCES `badge` (`badge_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_badge_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_levels`
--
ALTER TABLE `user_levels`
  ADD CONSTRAINT `user_levels_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `level` (`level_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_levels_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
