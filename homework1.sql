-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 29, 2023 at 03:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homework1`
--
CREATE DATABASE IF NOT EXISTS `homework1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `homework1`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `ADD_COMMENT`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ADD_COMMENT` (IN `currentUser` VARCHAR(12), IN `inPostID` VARCHAR(13), IN `inComment` VARCHAR(255))   BEGIN
	INSERT INTO COMMENTS VALUES(null, inPostID, currentUser, inComment, CURRENT_TIMESTAMP());
END$$

DROP PROCEDURE IF EXISTS `ADD_POST`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ADD_POST` (IN `inID` VARCHAR(13), IN `currentUser` VARCHAR(12), IN `inTitle` VARCHAR(100), IN `inDesc` VARCHAR(500))   BEGIN
	INSERT INTO POST VALUES(inID, currentUser, inTitle, inDesc, 0, 0, CURRENT_TIMESTAMP());
END$$

DROP PROCEDURE IF EXISTS `ADD_REVIEW`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ADD_REVIEW` (IN `inID` VARCHAR(13), IN `currentUser` VARCHAR(12), IN `inTitle` VARCHAR(100), IN `inDesc` VARCHAR(500), IN `inArtist` VARCHAR(255), IN `inAlbum` VARCHAR(255), IN `inScore` INTEGER, IN `inCover` VARCHAR(500))   BEGIN
	CALL ADD_POST (inID, currentUser, inTitle, inDesc);
	INSERT INTO POST_REVIEW VALUES(inID, inArtist, inAlbum, inScore, inCover);
END$$

DROP PROCEDURE IF EXISTS `CHECK_UNREAD`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_UNREAD` (IN `currentUser` VARCHAR(12), IN `targetUser` VARCHAR(12))   BEGIN
	SELECT * FROM MESSAGES WHERE Isread = 0 AND Sender = targetUser AND Receiver = currentUser;
END$$

DROP PROCEDURE IF EXISTS `CHECK_UNREAD_ALL`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_UNREAD_ALL` (IN `currentUser` VARCHAR(12))   BEGIN
	SELECT * FROM MESSAGES WHERE Received = currentUser;
END$$

DROP PROCEDURE IF EXISTS `DEL_INTERACTION`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DEL_INTERACTION` (IN `currentUser` VARCHAR(13), IN `inPost` VARCHAR(13))   BEGIN
	IF EXISTS (
		SELECT *
		FROM LIKES
		WHERE Username = currentUser AND TargetPost = inPost
	)
	THEN
		DELETE
		FROM LIKES
		WHERE Username = currentUser AND TargetPost = inPost;
	END IF;
END$$

DROP PROCEDURE IF EXISTS `FOLLOW`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `FOLLOW` (IN `currentUser` VARCHAR(12), IN `toFollow` VARCHAR(12))   BEGIN
	INSERT INTO FOLLOWERS VALUES(currentUser, toFollow);
END$$

DROP PROCEDURE IF EXISTS `GET_FOLLOWEDBY_INFO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FOLLOWEDBY_INFO` (IN `currentUser` VARCHAR(12), IN `inLimit` INTEGER, IN `inOffset` INTEGER)   BEGIN
	SELECT Nome, Cognome, Username, Propic
	FROM USERS
	WHERE Username IN (
		SELECT Username FROM FOLLOWERS WHERE Follows = currentUser	
	)
	LIMIT inLimit
	OFFSET inOffset;
END$$

DROP PROCEDURE IF EXISTS `GET_FOLLOWER_INFO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GET_FOLLOWER_INFO` (IN `currentUser` VARCHAR(12), IN `inLimit` INTEGER, IN `inOffset` INTEGER)   BEGIN
	SELECT Nome, Cognome, Username, Propic
	FROM USERS
	WHERE Username IN (
		SELECT Follows FROM FOLLOWERS WHERE Username = currentUser
	) 
	LIMIT inLimit
	OFFSET inOffset;
END$$

DROP PROCEDURE IF EXISTS `REGISTRATION`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `REGISTRATION` (IN `inNome` VARCHAR(255), IN `inCognome` VARCHAR(255), `inUser` VARCHAR(12), IN `inEmail` VARCHAR(255), IN `inPwd` VARCHAR(255))   BEGIN
	INSERT INTO USERS VALUES(inNome, inCognome, inUser, inEmail, inPwd, CURDATE(), 'http://localhost/homework1/images/svgicons/user.svg', 0, 0, 'Sono entrato nel mondo di Diapason!');
END$$

DROP PROCEDURE IF EXISTS `RETRIEVE_LAST_SENT`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RETRIEVE_LAST_SENT` (IN `currentUser` VARCHAR(12), IN `targetUser` VARCHAR(12))   BEGIN
	SELECT *
	FROM MESSAGES
	WHERE Sender = currentUser
	AND Receiver = targetUser 
	ORDER BY Datesent
	LIMIT 10;
END$$

DROP PROCEDURE IF EXISTS `RETRIEVE_UNREAD`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RETRIEVE_UNREAD` (IN `currentUser` VARCHAR(12), IN `targetUser` VARCHAR(12))   BEGIN
	SELECT *
	FROM MESSAGES 
	WHERE Receiver = currentUser
	AND Sender = targetUser
	AND Isread = 0
	ORDER BY Datesent
	LIMIT 10;

	
	UPDATE MESSAGES M
	SET M.Isread = 1
	WHERE Receiver = currentUser
	AND Sender = targetUser
	LIMIT 10;
END$$

DROP PROCEDURE IF EXISTS `SEND_MSG`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SEND_MSG` (IN `currentUser` VARCHAR(12), IN `targetUser` VARCHAR(12), IN `inMessage` VARCHAR(500))   BEGIN
	IF 
		(SELECT COUNT(*) FROM MESSAGES WHERE Isread = 0 AND Sender = currentUser AND Receiver = targetUser) < 10
	THEN	
		INSERT INTO MESSAGES VALUES(null, currentUser, targetUser, inMessage, false, NOW());
	END IF;
	
END$$

DROP PROCEDURE IF EXISTS `SET_INTERACTION`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SET_INTERACTION` (IN `currentUser` VARCHAR(12), IN `inPost` VARCHAR(13), IN `inAction` VARCHAR(3))   BEGIN
	IF EXISTS (
		SELECT *
		FROM LIKES
		WHERE Username = currentUser AND TargetPost = inPost
	)
	THEN
		UPDATE  LIKES L
		SET L.Interaction = inAction
		WHERE L.Username = currentUser AND L.TargetPost = inPost;

	ELSE 
		INSERT INTO LIKES VALUES(currentUser, inPost, inAction);
	END IF;
END$$

DROP PROCEDURE IF EXISTS `UNFOLLOW`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UNFOLLOW` (IN `currentUser` VARCHAR(12), IN `toUnfollow` VARCHAR(12))   BEGIN
	DELETE FROM FOLLOWERS
	WHERE Username = currentUser AND Follows = toUnfollow;
END$$

DROP PROCEDURE IF EXISTS `UPD_MOTTO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UPD_MOTTO` (IN `currUser` VARCHAR(12), IN `newMotto` VARCHAR(200))   BEGIN
	UPDATE USERS U
	SET U.Motto = newMotto
	WHERE U.Username = currUser;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `COMMENTS`
--

DROP TABLE IF EXISTS `COMMENTS`;
CREATE TABLE `COMMENTS` (
  `comment_id` int(11) NOT NULL,
  `post_id` varchar(13) NOT NULL,
  `author` varchar(12) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COMMENTS`
--

INSERT INTO `COMMENTS` (`comment_id`, `post_id`, `author`, `comment`, `comment_date`) VALUES
(7, '647230744dac6', 'suonoilpiano', 'Sii finalmente un social per noi musicisti!', '2023-05-28 13:35:54'),
(8, '647356db39a53', 'suonoilpiano', 'Boh, capisco il fattore nostalgia ma a me non è piaciuto per niente', '2023-05-28 13:36:24'),
(9, '647359964deba', 'ruredzen', 'Sii, è stato il primo pianista (e musicista classico in generale) che ho ascoltato. Me ne parlava sempre il mio maestro! si alzava alle 3 per guardarlo in TV!', '2023-05-28 13:58:05'),
(10, '64735b0de5c4e', 'nonsosuonare', 'Non io :P', '2023-05-28 14:04:58'),
(11, '6473601ac2f7b', 'Shark1', 'Io vengo di sicuroooo', '2023-05-28 14:18:05'),
(12, '6473601ac2f7b', 'Luppoloo', 'Negli Stati Uniti? Ma non è lontano?', '2023-05-28 14:20:46'),
(13, '6473601ac2f7b', 'nonsosuonare', 'Shark1 ti contatto in chat!', '2023-05-28 14:21:49'),
(14, '6473601ac2f7b', 'ruredzen', 'Non mi piace Ed Sheeran', '2023-05-29 06:58:55'),
(15, '64745145e2141', 'suonoilpiano', 'Anche a me piacciono molto', '2023-05-29 11:27:47'),
(16, '647230744dac6', 'mr.President', ':D :D :D', '2023-05-29 12:33:51'),
(17, '647356db39a53', 'mr.President', 'L\'ho ascoltato di recente, anche a me mette molta nostalgia! Trovo un po\' buffo il ragno sulla copertina', '2023-05-29 12:34:23'),
(18, '64735b0de5c4e', 'mr.President', 'Non vedo l\'ora di venirvi a sentire!!', '2023-05-29 12:35:02');

--
-- Triggers `COMMENTS`
--
DROP TRIGGER IF EXISTS `INS_NOTIF`;
DELIMITER $$
CREATE TRIGGER `INS_NOTIF` AFTER INSERT ON `COMMENTS` FOR EACH ROW BEGIN 
	INSERT INTO NOTIFICATIONS VALUES (null, 
		(SELECT P.Author FROM POST P WHERE P.ID = NEW.post_id)
	);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `FOLLOWERS`
--

DROP TABLE IF EXISTS `FOLLOWERS`;
CREATE TABLE `FOLLOWERS` (
  `Username` varchar(12) NOT NULL,
  `Follows` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `FOLLOWERS`
--

INSERT INTO `FOLLOWERS` (`Username`, `Follows`) VALUES
('cqrl', 'aurelio___'),
('cqrl', 'DoppiaG'),
('cqrl', 'Luppoloo'),
('cqrl', 'Rebbo'),
('cqrl', 'ruredzen'),
('cqrl', '_iamema_39'),
('Luppoloo', 'nonsosuonare'),
('mr.President', 'ruredzen'),
('mr.President', 'suonoilpiano'),
('nonsosuonare', 'DoppiaG'),
('nonsosuonare', 'suonoilpiano'),
('ruredzen', 'aurelio___'),
('ruredzen', 'cqrl'),
('ruredzen', 'dariooo_:)'),
('ruredzen', 'DoppiaG'),
('ruredzen', 'EmptyGirl'),
('ruredzen', 'Luppoloo'),
('ruredzen', 'marchinho11'),
('ruredzen', 'mr.President'),
('ruredzen', 'nonsosuonare'),
('ruredzen', 'Rebbo'),
('ruredzen', 'Skugha'),
('ruredzen', 'suonoilpiano'),
('ruredzen', '_iamema_39'),
('Shark1', 'nonsosuonare'),
('suonoilpiano', 'ruredzen');

--
-- Triggers `FOLLOWERS`
--
DROP TRIGGER IF EXISTS `DECR_FOLLOWERS_AMT`;
DELIMITER $$
CREATE TRIGGER `DECR_FOLLOWERS_AMT` AFTER DELETE ON `FOLLOWERS` FOR EACH ROW BEGIN 
	UPDATE USERS U
	SET U.AmtFollows = U.AmtFollows - 1
	WHERE U.Username = OLD.Username;

	UPDATE USERS U
	SET U.AmtFollowedBy = U.AmtFollowedBy - 1
	WHERE U.Username = OLD.Follows;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `INCR_FOLLOWERS_AMT`;
DELIMITER $$
CREATE TRIGGER `INCR_FOLLOWERS_AMT` AFTER INSERT ON `FOLLOWERS` FOR EACH ROW BEGIN 
	UPDATE USERS U
	SET U.AmtFollows = U.AmtFollows + 1
	WHERE U.Username = NEW.Username;

	UPDATE USERS U
	SET U.AmtFollowedBy = U.AmtFollowedBy + 1
	WHERE U.Username = NEW.Follows;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `LIKES`
--

DROP TABLE IF EXISTS `LIKES`;
CREATE TABLE `LIKES` (
  `Username` varchar(12) NOT NULL,
  `TargetPost` varchar(13) NOT NULL,
  `Interaction` set('upv','dwn') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `LIKES`
--

INSERT INTO `LIKES` (`Username`, `TargetPost`, `Interaction`) VALUES
('cqrl', '647230744dac6', 'upv'),
('cqrl', '647356db39a53', 'upv'),
('cqrl', '64745145e2141', 'upv'),
('Luppoloo', '6473601ac2f7b', 'upv'),
('mr.President', '647230744dac6', 'upv'),
('mr.President', '647356db39a53', 'upv'),
('mr.President', '647359964deba', 'upv'),
('mr.President', '64735b0de5c4e', 'upv'),
('nonsosuonare', '647359964deba', 'upv'),
('nonsosuonare', '64735b0de5c4e', 'upv'),
('ruredzen', '647359964deba', 'upv'),
('ruredzen', '64735b0de5c4e', 'upv'),
('ruredzen', '6473601ac2f7b', 'dwn'),
('ruredzen', '64749ceb8d8dc', 'upv'),
('ruredzen', '64749f7730253', 'upv'),
('Shark1', '6473601ac2f7b', 'upv'),
('suonoilpiano', '647230744dac6', 'upv'),
('suonoilpiano', '647356db39a53', 'dwn');

--
-- Triggers `LIKES`
--
DROP TRIGGER IF EXISTS `DECR_INTERACTIONS_AMT`;
DELIMITER $$
CREATE TRIGGER `DECR_INTERACTIONS_AMT` AFTER DELETE ON `LIKES` FOR EACH ROW BEGIN
	IF OLD.Interaction = 'upv'
	THEN
		UPDATE POST P
		SET P.AmtLikes = P.AmtLikes - 1
		WHERE P.ID = OLD.TargetPost;
	ELSEIF (OLD.Interaction = 'dwn')
	THEN
		UPDATE POST P
		SET P.AmtDislikes = P.AmtDislikes - 1
		WHERE P.ID = OLD.TargetPost;
	END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `INCR_INTERACTIONS_AMT`;
DELIMITER $$
CREATE TRIGGER `INCR_INTERACTIONS_AMT` AFTER INSERT ON `LIKES` FOR EACH ROW BEGIN
	IF NEW.Interaction = 'upv'
	THEN
		UPDATE POST P
		SET P.AmtLikes = P.AmtLikes + 1
		WHERE P.ID = NEW.TargetPost;
	ELSEIF (NEW.Interaction = 'dwn')
	THEN
		UPDATE POST P
		SET P.AmtDislikes = P.AmtDislikes + 1
		WHERE P.ID = NEW.TargetPost;
	END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `UPD_INTERACTIONS_AMT`;
DELIMITER $$
CREATE TRIGGER `UPD_INTERACTIONS_AMT` AFTER UPDATE ON `LIKES` FOR EACH ROW BEGIN
	IF NEW.Interaction = 'upv'
	THEN
		UPDATE POST P
		SET P.AmtLikes = P.AmtLikes + 1, P.AmtDislikes = P.AmtDislikes - 1
		WHERE P.ID = NEW.TargetPost;
		
	ELSEIF (NEW.Interaction = 'dwn')
	THEN
		UPDATE POST P
		SET P.AmtDislikes = P.AmtDislikes + 1, P.AmtLikes = P.AmtLikes - 1
		WHERE P.ID = NEW.TargetPost;
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `MESSAGES`
--

DROP TABLE IF EXISTS `MESSAGES`;
CREATE TABLE `MESSAGES` (
  `Msg_ID` int(11) NOT NULL,
  `Sender` varchar(12) NOT NULL,
  `Receiver` varchar(12) NOT NULL,
  `Msgtext` varchar(500) NOT NULL,
  `Isread` tinyint(1) DEFAULT NULL,
  `Datesent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MESSAGES`
--

INSERT INTO `MESSAGES` (`Msg_ID`, `Sender`, `Receiver`, `Msgtext`, `Isread`, `Datesent`) VALUES
(289, 'nonsosuonare', 'Shark1', 'Ehii ho visto che andiamo allo stesso concerto di Ed Sheeran', 0, '2023-05-28 16:22:17'),
(290, 'nonsosuonare', 'Shark1', 'Possiamo organizzarci per andare insieme se ti va!', 0, '2023-05-28 16:22:31'),
(291, 'ruredzen', 'cqrl', '1', 1, '2023-05-29 09:15:40'),
(292, 'ruredzen', 'cqrl', '2', 1, '2023-05-29 09:15:41'),
(293, 'ruredzen', 'cqrl', '3', 1, '2023-05-29 09:15:41'),
(294, 'ruredzen', 'cqrl', '4', 1, '2023-05-29 09:15:42'),
(295, 'ruredzen', 'cqrl', '5', 1, '2023-05-29 09:15:42'),
(296, 'ruredzen', 'cqrl', '6', 1, '2023-05-29 09:15:43'),
(297, 'ruredzen', 'cqrl', '7', 1, '2023-05-29 09:15:44'),
(298, 'ruredzen', 'cqrl', '8', 1, '2023-05-29 09:15:44'),
(299, 'ruredzen', 'cqrl', '9', 1, '2023-05-29 09:15:45'),
(300, 'ruredzen', 'cqrl', '10', 1, '2023-05-29 09:15:47'),
(301, 'ruredzen', 'suonoilpiano', 'Ehiii come va?', 1, '2023-05-29 13:09:01'),
(302, 'suonoilpiano', 'nonsosuonare', 'aaaaaaaaa', 1, '2023-05-29 13:49:12'),
(303, 'nonsosuonare', 'suonoilpiano', 'Ciaooo', 0, '2023-05-29 14:03:37'),
(304, 'nonsosuonare', 'suonoilpiano', 'Ciaooo', 0, '2023-05-29 14:04:12'),
(305, 'nonsosuonare', 'suonoilpiano', 'asdf', 0, '2023-05-29 14:06:41'),
(306, 'mr.President', 'ruredzen', 'Ciaoo ho visto che hai cambiato la tua immagine di profilo', 0, '2023-05-29 14:33:18'),
(307, 'mr.President', 'ruredzen', 'Visto che sei iscritto da tanto tempo, mi potresti dire come si fa?', 0, '2023-05-29 14:33:31'),
(308, 'cqrl', 'ruredzen', 'bravo sai contare', 0, '2023-05-29 14:50:37');

-- --------------------------------------------------------

--
-- Table structure for table `NOTIFICATIONS`
--

DROP TABLE IF EXISTS `NOTIFICATIONS`;
CREATE TABLE `NOTIFICATIONS` (
  `notif_id` int(11) NOT NULL,
  `notified_user` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `NOTIFICATIONS`
--

INSERT INTO `NOTIFICATIONS` (`notif_id`, `notified_user`) VALUES
(3, 'ruredzen'),
(4, 'ruredzen'),
(5, 'suonoilpiano');

-- --------------------------------------------------------

--
-- Table structure for table `POST`
--

DROP TABLE IF EXISTS `POST`;
CREATE TABLE `POST` (
  `ID` varchar(13) NOT NULL,
  `Author` varchar(12) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Textcontent` varchar(500) DEFAULT NULL,
  `AmtLikes` int(11) DEFAULT NULL,
  `AmtDislikes` int(11) DEFAULT NULL,
  `PostedOn` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `POST`
--

INSERT INTO `POST` (`ID`, `Author`, `Title`, `Textcontent`, `AmtLikes`, `AmtDislikes`, `PostedOn`) VALUES
('647230744dac6', 'ruredzen', 'Il mio Primo Post', 'Ciao a tutti! Non vedo l\'ora di condividere le mie opinioni musicali con tutti!', 3, 0, '2023-05-27 16:31:48'),
('647356db39a53', 'ruredzen', 'Parliamo di Danger Days', 'Probabilmente l\'album più criticato del gruppo, che si distacca dai toni dei dischi. Io l\'ho sempre apprezzato, nonostante i tanti alti e bassi. E poi mi mette tanta nostalgia...', 2, 1, '2023-05-28 13:27:55'),
('647359964deba', 'suonoilpiano', 'Debussy <3', 'L\'impressionismo musicale è uno dei miei movimenti artistici preferiti: si perde quell\'idea di frase musicale strutturata, e i suoni non diventano altro che pennellate. Nessuno meglio di Arturo Benedetti Michelangeli è riuscito a comunicare quello che ha scritto Debussy. Un maestro della tecnica.', 3, 0, '2023-05-28 13:39:34'),
('64735b0de5c4e', 'suonoilpiano', 'AAA CERCASI VIOLINISTA', 'Ciao a tutti, io e mio fratello stiamo cercando un violinista per formare un trio. Qualche interessato?', 3, 0, '2023-05-28 13:45:49'),
('6473601ac2f7b', 'nonsosuonare', 'Concerto di Ed Sheeran il 10 giugno', 'Qualcuno che viene al concerto di Ed Sheeran il 10 giugno al MetLife Stadium? Io ho già preso i biglietti!', 2, 1, '2023-05-28 14:07:22'),
('64745145e2141', 'ruredzen', 'Whirr', 'Stamattina ho cominciato ad ascoltare i Whirr: wow!', 1, 0, '2023-05-29 07:16:21'),
('64749b16f09f7', 'mr.President', 'Recensione di Caparezza', 'Sono stato al suo concerto quest estate, e nessuno sa intrattenere come Caparezza... Sicuramente l\'esperienza più bella dell\'estate!', 0, 0, '2023-05-29 12:31:18'),
('64749ceb8d8dc', 'cqrl', 'Vorrei prendere lezioni di Chitarra', 'Ho una vecchia chitarra a casa, in un angolino a prendere polvere. Ho provato a imparare a suonarla da sola, l\'aiuto di qualcuno di esperto mi farebbe comodo. Qualcuno può darmi consigli?', 1, 0, '2023-05-29 12:39:07'),
('64749f7730253', 'cqrl', 'Recensione Ants From Up There', 'Le canzoni sono davvero emotive, ma molto lunghe. Lo ascolta sempre mio fratello, ma non ho proprio la pazienza di ascoltare canzoni così lunghe', 1, 0, '2023-05-29 12:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `POST_REVIEW`
--

DROP TABLE IF EXISTS `POST_REVIEW`;
CREATE TABLE `POST_REVIEW` (
  `PostID` varchar(13) NOT NULL,
  `Artist` varchar(255) NOT NULL,
  `Albumtitle` varchar(255) NOT NULL,
  `Score` int(11) NOT NULL,
  `Cover` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `POST_REVIEW`
--

INSERT INTO `POST_REVIEW` (`PostID`, `Artist`, `Albumtitle`, `Score`, `Cover`) VALUES
('647356db39a53', 'My Chemical Romance', 'Danger Days', 76, 'https://i.discogs.com/1Ru_uJTRN91JcxiJ9i5sdVALHHQQ1rl3fbowgIPk0aA/rs:fit/g:sm/q:90/h:599/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTI1OTk5/NzItMTQyNDE5MDMx/MC02MzczLmpwZWc.jpeg'),
('647359964deba', 'Arturo Benedetti Michelangeli', 'Debussy - Images', 100, 'https://i.discogs.com/wBMule5Eptwzq9cZk3uE23dAOIH2GoJfJxEKpErdJjg/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTE1ODAy/NzQ4LTE2NzQ0OTQw/MzMtODE5OS5qcGVn.jpeg'),
('64749b16f09f7', 'Caparezza', 'Exuvia', 87, 'https://i.discogs.com/Quk6FlGEuaVkILuCDjT4-wiCJvWHM0dSz_zjsUbVlr0/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTE5NTYx/MzA2LTE2Mjk1NzU2/NTctMzIwOS5qcGVn.jpeg'),
('64749f7730253', 'Black Country New Road', 'Ants From Up There', 55, 'https://i.discogs.com/rjR4ocVoHbpEKpInI1_07_CQdh2ByOZCFL-pK7WZNZg/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTI1MjYw/Mzc5LTE2NzE1NjEz/NzAtMjU0MS5qcGVn.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
CREATE TABLE `USERS` (
  `Nome` varchar(12) NOT NULL,
  `Cognome` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Loginkey` varchar(255) NOT NULL,
  `SignupDate` date DEFAULT NULL,
  `Propic` varchar(500) NOT NULL,
  `AmtFollows` int(11) NOT NULL,
  `AmtFollowedBy` int(11) NOT NULL,
  `Motto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `USERS`
--

INSERT INTO `USERS` (`Nome`, `Cognome`, `Username`, `Email`, `Loginkey`, `SignupDate`, `Propic`, `AmtFollows`, `AmtFollowedBy`, `Motto`) VALUES
('Aurelio', 'Nappa', 'aurelio___', 'aurelionappa00@gmail.com', '$2y$10$fX7/sxPXlVRiQvpr/kp7huAt4kcNf1tYi.a8WvtoUKlOcgCXeVsUW', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 2, 'Sono entrato nel mondo di Diapason!'),
('Carla', 'Dibartolo', 'cqrl', 'carladiba05@gmail.it', '$2y$10$2bGaF8lWmh1lcxhbrtPpsOqIa.y57OdXtKyUMppFggMeQIaUgY8Bm', '2023-05-27', './homework1/../images/propics/cqrl64749c7d1eb31.png', 6, 1, 'chi la fa, l\'aspetti.'),
('Dario', 'Giordano', 'dariooo_:)', 'dgiordano@outlook.com', '$2y$10$FnQiLKzkgqJYap02G4LjIu9ObIkGOOaOcw2C.04sS9exSUwq8OAFi', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 1, 'Sono entrato nel mondo di Diapason!'),
('Giulia', 'Guarino', 'DoppiaG', 'doppiag@live.com', '$2y$10$IlvQwKC.KekxW7L6sTiXM.XJ/.0NdGLwo2zXQWH8F4D3qlze33v9q', '2023-05-27', './homework1/../images/propics/DoppiaG6474a8a699e62.jpg', 0, 3, 'Tra una canzone e l\'altra registratevi su Ourbooks'),
('Giada', 'Mauro', 'EmptyGirl', 'giadam@outlook.com', '$2y$10$fXR51KdEaKSJXuFsi4//De63DLyBUPslxFEBVCSyYQFl3cMXCcxBi', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 1, 'Sono entrato nel mondo di Diapason!'),
('Marco', 'Monaco', 'Luppoloo', 'marcomonaco01@gmail.com', '$2y$10$Lz0XMKMs2nXQaayttFnoBePwn2bHxnt5u0EqnflW9K1vbTJ8uNWHK', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 1, 2, 'Sono entrato nel mondo di Diapason!'),
('Marco', 'Mezio', 'marchinho11', 'mmezio@outlook.com', '$2y$10$MWUkVHBvfvZ0Pa1VYjW2EuZYnFZrE6F4r4S.U3JuN9AdNAFqTk.l2', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 1, 'Sono entrato nel mondo di Diapason!'),
('Massimo', 'Ventola', 'Max_Fan', 'mvento@hotmail.com', '$2y$10$erK1MT2pHP4LI65LUb.3r.zmwm3l/Ep0oYfEbjqQTBRA9kEFwrU9W', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 0, 'Sono entrato nel mondo di Diapason!'),
('Luca', 'Valvo', 'mr.President', 'ilpresidente@outlook.com', '$2y$10$Pfoxj0BFDp.VB9sGQzM9MehxDLzc0Y19USBWAmyCBRx9zJULZycEe', '2023-05-29', 'http://localhost/homework1/images/svgicons/user.svg', 2, 1, 'Sono entrato nel mondo di Diapason!'),
('Viola', 'Antonella', 'nonsosuonare', 'violanto@live.com', '$2y$10$Qmmzq.BvEcJP79rfK5lFN.kK9eElGc4NQcSJFHiiGx2D3bCcs52bi', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 2, 3, 'Sono entrato nel mondo di Diapason!'),
('Simone', 'Interlandi', 'Rebbo', 'simax01@gmail.com', '$2y$10$ZOo5blfdMvAZJjtO6.z7e.L.YcUGDLtX/nmBJOeaC0xA2QSv.R0fe', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 2, 'Sono entrato nel mondo di Diapason!'),
('Giuseppe', 'Dibartolo', 'ruredzen', 'peppegac@live.com', '$2y$10$C6fDIUc1IvT8odTCUy80MeGbYuwjpdYTeWiEf4d1.5WD6d6iAq1um', '2023-05-27', './homework1/../images/propics/ruredzen64722ffab5474.png', 13, 3, 'Alison, I\'m lost...'),
('Matilde', 'Cuomo', 'Shark1', 'matipuo@libero.com', '$2y$10$bSabgPh9lmUl0BEHXiB8gu5nNZMtTMM2XPv99DhQ3li/EVYXm6G8W', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 1, 0, 'Sono entrato nel mondo di Diapason!'),
('Daniel', 'Ceniti', 'Skugha', 'michiamopiero@live.it', '$2y$10$Dgj1IxkvmP7ftw1odwIGLuLYHumH/h6a0lPFz5OyiCYjmaEgzvFSu', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 1, 'Sono entrato nel mondo di Diapason!'),
('Letizia', 'Eramo', 'suonoilpiano', 'leramoleramo@gmail.com', '$2y$10$TpReit0Zm1NLWiNQHrtoIuH2//heJVy0X9GVimRDmfJR5D1whIQdG', '2023-05-27', './homework1/../images/propics/suonoilpiano647357c9b1177.png', 1, 3, 'Il secolo degli aeroplani ha diritto alla sua musica'),
('Clarissa', 'Lupo', '_Cl4r155a_', 'clariwolf@libero.it', '$2y$10$uPUukRIit7B.ItiStWWzvugUXTXWdxk2QQW8.68cfNSj2CduWi6/.', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 0, 'Sono entrato nel mondo di Diapason!'),
('Emmanuel', 'Papa', '_iamema_39', 'iamema@gmail.com', '$2y$10$7kWvlYC4EUnj9n4yUheNeOhstFwThME1xZkQt.De3IXvfOypoa4Yu', '2023-05-27', 'http://localhost/homework1/images/svgicons/user.svg', 0, 2, 'Sono entrato nel mondo di Diapason!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `FOLLOWERS`
--
ALTER TABLE `FOLLOWERS`
  ADD PRIMARY KEY (`Username`,`Follows`),
  ADD KEY `Follows` (`Follows`);

--
-- Indexes for table `LIKES`
--
ALTER TABLE `LIKES`
  ADD PRIMARY KEY (`Username`,`TargetPost`),
  ADD KEY `TargetPost` (`TargetPost`);

--
-- Indexes for table `MESSAGES`
--
ALTER TABLE `MESSAGES`
  ADD PRIMARY KEY (`Msg_ID`),
  ADD KEY `Sender` (`Sender`),
  ADD KEY `Receiver` (`Receiver`);

--
-- Indexes for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `idx_user` (`notified_user`);

--
-- Indexes for table `POST`
--
ALTER TABLE `POST`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Author` (`Author`);

--
-- Indexes for table `POST_REVIEW`
--
ALTER TABLE `POST_REVIEW`
  ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `idx_email` (`Email`),
  ADD KEY `idx_cred` (`Username`,`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `MESSAGES`
--
ALTER TABLE `MESSAGES`
  MODIFY `Msg_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD CONSTRAINT `COMMENTS_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `POST` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `COMMENTS_ibfk_2` FOREIGN KEY (`author`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE;

--
-- Constraints for table `FOLLOWERS`
--
ALTER TABLE `FOLLOWERS`
  ADD CONSTRAINT `FOLLOWERS_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FOLLOWERS_ibfk_2` FOREIGN KEY (`Follows`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE;

--
-- Constraints for table `LIKES`
--
ALTER TABLE `LIKES`
  ADD CONSTRAINT `LIKES_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `LIKES_ibfk_2` FOREIGN KEY (`TargetPost`) REFERENCES `POST` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `MESSAGES`
--
ALTER TABLE `MESSAGES`
  ADD CONSTRAINT `MESSAGES_ibfk_1` FOREIGN KEY (`Sender`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `MESSAGES_ibfk_2` FOREIGN KEY (`Receiver`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE;

--
-- Constraints for table `NOTIFICATIONS`
--
ALTER TABLE `NOTIFICATIONS`
  ADD CONSTRAINT `NOTIFICATIONS_ibfk_1` FOREIGN KEY (`notified_user`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE;

--
-- Constraints for table `POST`
--
ALTER TABLE `POST`
  ADD CONSTRAINT `POST_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `USERS` (`Username`) ON UPDATE CASCADE;

--
-- Constraints for table `POST_REVIEW`
--
ALTER TABLE `POST_REVIEW`
  ADD CONSTRAINT `POST_REVIEW_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `POST` (`ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
