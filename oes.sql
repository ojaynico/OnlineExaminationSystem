-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2017 at 02:36 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.5.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oes`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `id` int(11) NOT NULL,
  `admname` varchar(32) NOT NULL,
  `admpassword` varchar(32) DEFAULT NULL,
  `role` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `admname`, `admpassword`, `role`) VALUES
(1, 'root', '63a9f0ea7bb98050796b649e85481845', 'admin'),
(2, 'seadmin', 'efad7abb323e3d4016284c8a6da076a1', 'SE'),
(4, 'imsadmin', '202cb962ac59075b964b07152d234b70', 'IMS'),
(5, 'vfxadmin', '202cb962ac59075b964b07152d234b70', 'VFX'),
(6, 'scholarship', '202cb962ac59075b964b07152d234b70', 'SCHOLARSHIP');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `question` varchar(500) DEFAULT NULL,
  `optiona` varchar(100) DEFAULT NULL,
  `optionb` varchar(100) DEFAULT NULL,
  `optionc` varchar(100) DEFAULT NULL,
  `optiond` varchar(100) DEFAULT NULL,
  `correctanswer` varchar(10) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`testid`, `qnid`, `question`, `optiona`, `optionb`, `optionc`, `optiond`, `correctanswer`, `marks`) VALUES
(1, 1, 'What is a string', 'a sequnce of characters', 'an array of values', 'a boolean', 'an option', 'optiona', 1),
(1, 2, 'What is an array', 'a group of related values', 'an input value', 'none of the above', 'people dont know', 'optiona', 1),
(2, 1, 'What is a string', 'as asasa', 'dfjdf dkfhdfj', 'jsids sjdhsjkds', 'sjhdkjs sdhshdb shdbsh', 'optiona', 2),
(2, 2, 'What is a boolean', 'sdksjdo sdjsndjs', 'sdjsjd sd jsdsdhsj', 'jidaojiod', 'ioajdi adjsod', 'optiona', 1),
(2, 3, 'What is the name odsd', 'sdjsd djsdsj ahdj', 'sdsjd dsjdhsjkd ddks', 'sdjsdo sdhsds sdhsod', 'aijodos dsdhsd sjdhsd jhsjds', 'optiona', 1),
(2, 4, 'Whta is as iasas', 'asansa sahsajs ajsa', 'gfpgfogkfo fgpfg', 'nfruif rufhrif ', 'sudiueudn eihdi', 'optiona', 1),
(2, 5, 'What us sds ushdsu sdns', 'jsndjs jdkfhdjfk dfjdf', 'dfd djfhdjkfd kfdkhfdjkf', 'dfdh dfhdkfd fhdkf dkfhd', 'djfd djfhdkfjdjfdhfd djfhkdkf', 'optiona', 1),
(2, 6, 'ehysd sdysds dsdusd shdsd', 'kskjdfd kfudfksf', 'fhdj fddkhfd fk', 'bfdk fdhfdfjd fjdfh', 'sdjs dsudus sdusd', 'optiona', 1),
(2, 7, 'hw duasa uhfd fduhfud fushdus', 'sidosdinsodonsd sdjs', 'sduhsd shds dsy', 'shdsui ushdui fryf', 'ebdeb fe shdhsud hjdsd', 'optiona', 1),
(2, 8, 'wht suas dhsdshsd hsdkjs', 'ushdus shdsjkausd sdhsdk', 'uhud fuirf siudhisd', 'sdhsi sudsd uhir isuds', 'sudhs udhisd iufrhifd', 'optiona', 1),
(2, 9, 'ehts dudssuds udsdhsud shdsud', 'sdus sdihsd iauhdis dsiuds', 'sudsi sds diahss fudhfiudf', 'sdsk sudhsuid dufud suhdsiud', 'siuds isudhsi ifrufhur sdhisu', 'optiona', 1),
(2, 10, 'es hsdsd skhdus ufrns jskdhsd', 'sdsu sdsndkunrfsd dsbds', 'sdus ushdus ushdsud usdus', 'ijsd ushd ffurhiuff siduhsuids', 'ushduis iuifhrifhi sudhsuid', 'optiona', 1),
(2, 11, 'uhsiid sudhsid siudshids dushdis disudhsd sudi', 'sudhsidsuds disudhsd suidsds dsuidhisd siuds', 'shdhsudbusi siudsud sudhsid', 'bf dfhdif dfdhuiuhif dufha d', 'uhisd isdus iuhufrfbuis iusdhsi', 'optiona', 1),
(2, 12, 'hsdusk suhdsd sduaodsodbso ufdf', 'uhidfdf iudfhidf diufdhfud', 'sdbusids usdisuds', 'sdsui suidshid sdiusdhis diusdhs', 'sudsid isbdsdishds dsudhisud siudhsid', 'optiona', 1),
(2, 13, 'ijosdsdjsod sodsd sodsds odsoudiaisd', 'uhfdfd fudhfduf usdfudfd udhfud', 'sds sudhsds dushdsoidoshfd fidhfdf', 'uidfd fidhfds idfhud fudihufd ', 'uhdfid idhfdfis7rhfos dfudfhdfdu', 'optiona', 1),
(2, 14, 'ehw udysvdsdusydgsd fu rybf sydsud sy', 'usids shdhsiaosds fuhfdufbdf', 'sdhsi isdshebdui uudhdfuf uhdsiuds', 'usdiu ifrvsruf sieui fuifhs diuh', 'usdi isudyrif dyfryis dsihdsuis', 'optiona', 1),
(2, 15, 'wh dsdjyyfyr bjsfbsds dsydisfdfdf', 'shds sdshfirsd sdsdhisd bi', 'sduhsidb urhurifbuisidu ifurifuir', 'sid uruhfusdu rfhruif usdiurr', 'ushdudioi irufrbsid rfhirfr', 'optiona', 1),
(2, 16, 'wht aus fhdufid ishdisd udfidf', 'dushdi sdisudhisu ffudhfudifd fshdusid', 'sduhsi sduhfr uiisudsu fduhfiue', 'sudhsui ufurifhso jfdhfue fuhsdisd', 'jsd sjdhsuhduf ushdsuidbsuihirf ushdsu', 'optiona', 1),
(2, 17, 'ushdis dsuhduisd ifurhfbusids uidebefue', 'usdisu sudhiuvbruifhiu dsidheibbyir', 'sudhisbbfr shdbsydbsbosi shfiufdf', 'uhiuf dfihursbuis ifushdusiufhdhfd ', 'sudhsibiufr uhsidsduir urfnriufnd s', 'optiona', 1),
(2, 18, 'whtas uuds ushdisu ufihirf suhdsuid uhsds', 'shsid sidiusf fdhidurfhruis', 'ushuhsi firufso sudsu', 'susfnef jfhfuenfjs jhfus', 'fjofni sudsdusd usdsds', 'optiona', 1),
(2, 19, 'sdusihd sdhisiud sdusdusd sudsudnosifidhof', 'sdhsuds sudhsuds dsuhdusd sudsdusd', 'sudhsi sidufrnf siudhsuds iush hiu', 'sudhsud sudhsu fbri siuhsuds fudhdduis', 'sudhs sdhufhurbfbui ushdsudbuifh shdsui', 'optiona', 1),
(2, 20, 'uhusds dsudhsd sudhsuds d', 'uhsduhsd usdued sudhsuids diusdsi', 'shdus uhfifhosdns ushfuen', 'sudhsu ufrfoiwndiojfeo iejdeode', 'sds sdhehfi ishdsiud ufrhfuie d', 'optiona', 1),
(3, 1, 'What is a boolean', 'none', 'true or false', 'n', 'b', 'optionb', 1),
(3, 2, 'What is a string', 'a sequence of characters', 'a loop', 's tts', 'sd sdushd', 'optiona', 1),
(3, 3, 'What is an array', 'none of the above', 'sidhsds sdshds', 'group of similar data', 'none ', 'optionc', 1),
(3, 4, 'This is the last', 'correct answer', 'right answer', 'none', 'alll', 'optiona', 1),
(4, 1, 'What is java', 'asasas', 'dsjdsd', 'sdskjdsk', 'sdhsjds', 'optiona', 1),
(4, 2, 'What is a string', 'djfdf', 'sdhshd', 'sdhsds', 'sdhsidhishd', 'optionb', 1),
(4, 3, 'What is a class', 'sjdsdjsdh', 'djfhdjfhdfls', 'dfdjfodifjdjfiid', 'opsdodfjd', 'optionc', 1),
(4, 4, 'What is an array', 'dfkjdfdopf', 'dfkjdlfsf', 'difhodfhid', 'dbfudfdk', 'optiond', 1),
(5, 1, 'What is a boolean', 'none', 'true or false', 'n', 'b', 'optionb', 1),
(5, 2, 'What is a string', 'a sequence of characters', 'a loop', 's tts', 'sd sdushd', 'optiona', 1),
(5, 3, 'What is an array', 'none of the above', 'sidhsds sdshds', 'group of similar data', 'none ', 'optionc', 1),
(5, 4, 'This is the last', 'correct answer', 'right answer', 'none', 'alll', 'optiona', 1),
(6, 1, 'What is a boolean', 'none', 'true or false', 'n', 'b', 'optionb', 1),
(6, 2, 'What is a string', 'a sequence of characters', 'a loop', 's tts', 'sd sdushd', 'optiona', 1),
(6, 3, 'What is an array', 'none of the above', 'sidhsds sdshds', 'group of similar data', 'none ', 'optionc', 1),
(6, 4, 'This is the last', 'correct answer', 'right answer', 'none', 'alll', 'optiona', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `stdid` bigint(20) NOT NULL,
  `stdname` varchar(40) DEFAULT NULL,
  `stduname` varchar(50) NOT NULL,
  `stdpassword` varchar(40) DEFAULT NULL,
  `stduidno` varchar(40) DEFAULT NULL,
  `course` varchar(20) DEFAULT NULL,
  `semester` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stdid`, `stdname`, `stduname`, `stdpassword`, `stduidno`, `course`, `semester`) VALUES
(2, 'Nico', '201', 'ÐO3/×¡Ð', '201', 'SCHOLARSHIP', ''),
(3, 'Job', 'SC02', '"é=þU‘ dŒ', 'SC02', 'SCHOLARSHIP', ''),
(4, 'Oj', '123', 'ÿ]Ÿs9ß£''', '123', 'IMS', '3'),
(5, 'Sample for Date', 'Sample1', 'ø?LKAI€J–', 'Sample1', 'SCHOLARSHIP', ''),
(6, 'Nicodemus Ojwee', 'SE111', 'zŒž‘jóZ­à', 'SE111', 'SE', '4');

-- --------------------------------------------------------

--
-- Table structure for table `studentquestion`
--

CREATE TABLE `studentquestion` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `answered` enum('answered','unanswered','review') DEFAULT NULL,
  `stdanswer` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studenttest`
--

CREATE TABLE `studenttest` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `correctlyanswered` int(11) DEFAULT NULL,
  `status` enum('over','inprogress') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subid` int(11) NOT NULL,
  `subname` varchar(40) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `tcid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subid`, `subname`, `course`, `semester`, `tcid`) VALUES
(1, 'Sample', 'SCHOLARSHIP', '', NULL),
(2, 'Test Sub', 'SCHOLARSHIP', '', NULL),
(3, 'Photo', 'IMS', '3', NULL),
(4, 'Java', 'SE', '1', NULL),
(5, 'Transfered Test', 'SE', '1', NULL),
(6, 'Photoshop SE', 'SE', '2', NULL),
(7, 'Excel', 'SE', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `testid` bigint(20) NOT NULL,
  `testname` varchar(30) NOT NULL,
  `testdesc` varchar(100) DEFAULT NULL,
  `testdate` date DEFAULT NULL,
  `testtime` time DEFAULT NULL,
  `subid` int(11) DEFAULT NULL,
  `testfrom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `testto` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) DEFAULT NULL,
  `totalquestions` int(11) DEFAULT NULL,
  `attemptedstudents` bigint(20) DEFAULT NULL,
  `testcode` varchar(40) NOT NULL,
  `tcid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`testid`, `testname`, `testdesc`, `testdate`, `testtime`, `subid`, `testfrom`, `testto`, `duration`, `totalquestions`, `attemptedstudents`, `testcode`, `tcid`) VALUES
(1, 'IT01', 'sample', '2016-10-17', '11:35:56', 1, '2016-10-17 07:35:56', '2016-10-31 20:59:59', 5, 2, 0, 'úè\\û', NULL),
(2, 'IT004', 'Testing', '2016-10-19', '22:54:57', 2, '2016-10-19 18:54:57', '2016-10-31 20:59:59', 20, 20, 0, 'úè\\û', NULL),
(3, 'IT001', 'Sample', '2016-11-07', '14:21:48', 3, '2016-11-07 09:21:48', '2016-11-08 20:59:59', 10, 4, 0, 'úè\\û', NULL),
(4, 'Intro', 'Begining java', '2016-11-13', '12:01:34', 4, '2016-11-13 07:01:34', '2016-11-15 20:59:59', 10, 4, 0, 'úè\\û', NULL),
(5, 'IT0009', 'Sample', '2016-11-07', '14:21:48', 5, '2016-11-07 07:30:59', '2016-12-16 20:59:59', 120, 4, 0, 'úèû', NULL),
(6, 'IT7876', 'Sample', '2016-11-07', '14:21:48', 6, '2016-11-07 09:21:48', '2016-11-08 20:59:59', 10, 4, 0, 'úèû', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `testconductor`
--

CREATE TABLE `testconductor` (
  `tcid` bigint(20) NOT NULL,
  `tcname` varchar(40) DEFAULT NULL,
  `tcpassword` varchar(40) DEFAULT NULL,
  `emailid` varchar(40) DEFAULT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`testid`,`qnid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`stdid`),
  ADD UNIQUE KEY `stdname` (`stdname`),
  ADD UNIQUE KEY `emailid` (`stduidno`);

--
-- Indexes for table `studentquestion`
--
ALTER TABLE `studentquestion`
  ADD PRIMARY KEY (`stdid`,`testid`,`qnid`),
  ADD KEY `testid` (`testid`,`qnid`);

--
-- Indexes for table `studenttest`
--
ALTER TABLE `studenttest`
  ADD PRIMARY KEY (`stdid`,`testid`),
  ADD KEY `testid` (`testid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subid`),
  ADD UNIQUE KEY `subname` (`subname`),
  ADD KEY `subject_fk1` (`tcid`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`testid`),
  ADD UNIQUE KEY `testname` (`testname`),
  ADD KEY `test_fk1` (`subid`),
  ADD KEY `test_fk2` (`tcid`);

--
-- Indexes for table `testconductor`
--
ALTER TABLE `testconductor`
  ADD PRIMARY KEY (`tcid`),
  ADD UNIQUE KEY `stdname` (`tcname`),
  ADD UNIQUE KEY `emailid` (`emailid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`);

--
-- Constraints for table `studentquestion`
--
ALTER TABLE `studentquestion`
  ADD CONSTRAINT `studentquestion_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  ADD CONSTRAINT `studentquestion_ibfk_2` FOREIGN KEY (`testid`,`qnid`) REFERENCES `question` (`testid`, `qnid`);

--
-- Constraints for table `studenttest`
--
ALTER TABLE `studenttest`
  ADD CONSTRAINT `studenttest_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  ADD CONSTRAINT `studenttest_ibfk_2` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_fk1` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`);

--
-- Constraints for table `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `test_fk1` FOREIGN KEY (`subid`) REFERENCES `subject` (`subid`),
  ADD CONSTRAINT `test_fk2` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
