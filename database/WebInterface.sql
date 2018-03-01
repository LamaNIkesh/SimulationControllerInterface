-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 01, 2018 at 04:03 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `WebInterface`
--

-- --------------------------------------------------------

--
-- Table structure for table `AdminDetails`
--

CREATE TABLE `AdminDetails` (
  `Id` int(10) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `UserId` varchar(100) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `DateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `AdminDetails`
--

INSERT INTO `AdminDetails` (`Id`, `FirstName`, `LastName`, `UserId`, `Password`, `Email`, `DateCreated`) VALUES
(9, 'Nikesh', 'Lama', 'cncr2018', 'ff6a37416e93a39550cfe3640492ddbe', 'cncr@ntu.ac.uk', '2018-02-16');

-- --------------------------------------------------------

--
-- Table structure for table `InF`
--

CREATE TABLE `InF` (
  `ItemID` int(10) NOT NULL,
  `ModelID` varchar(100) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Type` int(5) NOT NULL,
  `Datatype` int(5) NOT NULL,
  `IntegerPart` int(5) NOT NULL,
  `TypicalVal` float NOT NULL,
  `InLSB` int(5) NOT NULL,
  `InMSB` int(5) NOT NULL,
  `OutLSB` int(5) NOT NULL,
  `OutMSB` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `InF`
--

INSERT INTO `InF` (`ItemID`, `ModelID`, `Name`, `Type`, `Datatype`, `IntegerPart`, `TypicalVal`, `InLSB`, `InMSB`, `OutLSB`, `OutMSB`) VALUES
(1, '3', 'absolute refractory period', 1, 16, 8, 6, 0, 31, 0, 0),
(2, '3', 'reset_voltage', 1, 16, 8, 5, 0, 31, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Izhikevich`
--

CREATE TABLE `Izhikevich` (
  `ItemID` int(10) NOT NULL,
  `ModelID` varchar(100) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Type` int(5) NOT NULL,
  `Datatype` int(5) NOT NULL,
  `IntegerPart` int(5) NOT NULL,
  `TypicalVal` float NOT NULL,
  `InLSB` int(5) NOT NULL,
  `InMSB` int(5) NOT NULL,
  `OutLSB` int(5) NOT NULL,
  `OutMSB` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Izhikevich`
--

INSERT INTO `Izhikevich` (`ItemID`, `ModelID`, `Name`, `Type`, `Datatype`, `IntegerPart`, `TypicalVal`, `InLSB`, `InMSB`, `OutLSB`, `OutMSB`) VALUES
(1, '2', 'a', 1, 16, 8, 0.02, 0, 31, 0, 0),
(2, '2', 'b', 1, 16, 8, 0.2, 32, 63, 0, 0),
(3, '2', 'c', 1, 16, 8, -65, 64, 95, 0, 0),
(4, '2', 'd', 1, 16, 8, 8, 96, 127, 0, 0),
(5, '2', 'threshold_voltage', 1, 16, 8, 35, 128, 159, 0, 0),
(6, '2', 'initial_action_potential', 1, 16, 8, -70, 160, 191, 0, 0),
(7, '2', 'initial_membrane_recovery', 1, 16, 8, -14, 192, 223, 0, 0),
(8, '2', 'action_potential', 2, 16, 8, 0, 224, 255, 0, 31),
(9, '2', 'membrane_recovery', 2, 16, 8, 0, 256, 287, 32, 63);

-- --------------------------------------------------------

--
-- Table structure for table `LIF`
--

CREATE TABLE `LIF` (
  `ItemID` int(10) NOT NULL,
  `ModelID` varchar(100) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Type` int(5) NOT NULL,
  `Datatype` int(5) NOT NULL,
  `IntegerPart` int(5) NOT NULL,
  `TypicalVal` float NOT NULL,
  `InLSB` int(5) NOT NULL,
  `InMSB` int(5) NOT NULL,
  `OutLSB` int(5) NOT NULL,
  `OutMSB` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `LIF`
--

INSERT INTO `LIF` (`ItemID`, `ModelID`, `Name`, `Type`, `Datatype`, `IntegerPart`, `TypicalVal`, `InLSB`, `InMSB`, `OutLSB`, `OutMSB`) VALUES
(1, '1', 'absolute refractory period', 1, 16, 8, 6, 0, 31, 0, 0),
(2, '1', 'threshold_voltage', 1, 16, 8, 20, 0, 31, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ModelLibrary`
--

CREATE TABLE `ModelLibrary` (
  `ModelID` varchar(100) NOT NULL,
  `ModelName` varchar(200) NOT NULL,
  `NoOfPara` int(10) NOT NULL,
  `LocationURL` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ModelLibrary`
--

INSERT INTO `ModelLibrary` (`ModelID`, `ModelName`, `NoOfPara`, `LocationURL`) VALUES
('1', 'LIF', 2, 'df'),
('2', 'Izhikevich', 9, 'izh'),
('3', 'InF', 2, 'someplace');

-- --------------------------------------------------------

--
-- Table structure for table `SImulation`
--

CREATE TABLE `SImulation` (
  `id` int(200) NOT NULL,
  `SimulationId` int(20) NOT NULL,
  `Engage` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SImulation`
--

INSERT INTO `SImulation` (`id`, `SimulationId`, `Engage`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 0),
(5, 5, 0),
(6, 6, 0),
(7, 7, 0),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 0),
(12, 12, 0),
(13, 13, 0),
(14, 14, 0),
(15, 15, 0),
(16, 16, 0),
(17, 17, 0),
(18, 18, 0),
(19, 19, 0),
(20, 20, 0),
(21, 21, 0),
(22, 22, 0),
(23, 23, 0),
(24, 24, 0),
(25, 25, 0),
(26, 26, 0),
(27, 27, 0),
(28, 28, 0),
(29, 29, 0),
(30, 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `UserDetails`
--

CREATE TABLE `UserDetails` (
  `id` int(20) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `UserId` varchar(100) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `DateCreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserDetails`
--

INSERT INTO `UserDetails` (`id`, `FirstName`, `LastName`, `UserId`, `Password`, `Email`, `DateCreated`) VALUES
(1, 'nimesh', 'lama', 'Nimesh123', '555397857d16ba7fa1485422ee2ebee7', 'nimesh@hotmail.com', '0000-00-00 00:00:00'),
(2, 'nikesh', 'lama', 'nikesh123', '555397857d16ba7fa1485422ee2ebee7', 'nikesh@yahoo.com', '0000-00-00 00:00:00'),
(4, 'abhishek', 'gurung', 'ag123456', '555397857d16ba7fa1485422ee2ebee7', 'ag@yahoo.com', '2017-10-24 18:58:44'),
(5, 'Nikesh', 'Lama', 'nikeshLama', '555397857d16ba7fa1485422ee2ebee7', 'nikesh.lama2015@my.ntu.ac.uk', '2017-10-24 19:48:04'),
(6, 'Nikesh', 'Lama', 'nikeshlama2017', '555397857d16ba7fa1485422ee2ebee7', 'nikeshlama@gmail.com', '2017-10-24 19:48:55'),
(7, 'Jason', 'Chaudhary', 'jasonMason', '555397857d16ba7fa1485422ee2ebee7', 'jason@mason.com', '2017-10-25 15:45:38'),
(8, 'Pedro', 'Machado', 'pedrombmachado', 'b6a851a842b5bc121bf98b6bb7d1fe0c', 'pedro.baptistamachado@ntu.ac.uk', '2017-10-26 12:11:33'),
(9, 'nikesh', 'lama', 'nikeshlama2018', '555397857d16ba7fa1485422ee2ebee7', 'testing@testing.com', '2017-10-26 15:35:52'),
(10, 'NIkesh', 'Lama', 'CNCR', 'dc65e03b28c263e8baa68217050be2bc', 'cncr@ntu.ac.uk', '2018-02-16 12:53:22'),
(11, 'Nikesh', 'lama', 'nikeshTests', '555397857d16ba7fa1485422ee2ebee7', 'nikesh.lama2015@my.ntu.ac.uk', '2018-02-27 16:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `UserSimulation`
--

CREATE TABLE `UserSimulation` (
  `id` int(11) NOT NULL,
  `UserId` varchar(100) NOT NULL,
  `SimulationId` int(20) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `TimeConfigured` datetime NOT NULL,
  `Engage` tinyint(1) NOT NULL,
  `NoOfNeurons` int(100) NOT NULL,
  `SimTime_ms` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserSimulation`
--

INSERT INTO `UserSimulation` (`id`, `UserId`, `SimulationId`, `Status`, `TimeConfigured`, `Engage`, `NoOfNeurons`, `SimTime_ms`) VALUES
(173, 'nikeshlama2018', 3, 'Finished', '2017-10-27 13:39:40', 0, 10, 1000),
(174, 'nikeshlama2018', 4, 'Finished', '2017-10-27 14:04:34', 0, 17, 1000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AdminDetails`
--
ALTER TABLE `AdminDetails`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `InF`
--
ALTER TABLE `InF`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ModelID` (`ModelID`);

--
-- Indexes for table `Izhikevich`
--
ALTER TABLE `Izhikevich`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ModelID` (`ModelID`);

--
-- Indexes for table `LIF`
--
ALTER TABLE `LIF`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `ModelID` (`ModelID`);

--
-- Indexes for table `ModelLibrary`
--
ALTER TABLE `ModelLibrary`
  ADD UNIQUE KEY `ModelID` (`ModelID`);

--
-- Indexes for table `SImulation`
--
ALTER TABLE `SImulation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `UserDetails`
--
ALTER TABLE `UserDetails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UserId` (`UserId`);

--
-- Indexes for table `UserSimulation`
--
ALTER TABLE `UserSimulation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `SimulationId` (`SimulationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AdminDetails`
--
ALTER TABLE `AdminDetails`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `InF`
--
ALTER TABLE `InF`
  MODIFY `ItemID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Izhikevich`
--
ALTER TABLE `Izhikevich`
  MODIFY `ItemID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `LIF`
--
ALTER TABLE `LIF`
  MODIFY `ItemID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `SImulation`
--
ALTER TABLE `SImulation`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `UserDetails`
--
ALTER TABLE `UserDetails`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `UserSimulation`
--
ALTER TABLE `UserSimulation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `InF`
--
ALTER TABLE `InF`
  ADD CONSTRAINT `InF_ibfk_1` FOREIGN KEY (`ModelID`) REFERENCES `ModelLibrary` (`ModelID`);

--
-- Constraints for table `Izhikevich`
--
ALTER TABLE `Izhikevich`
  ADD CONSTRAINT `Izhikevich_ibfk_1` FOREIGN KEY (`ModelID`) REFERENCES `ModelLibrary` (`ModelID`);

--
-- Constraints for table `LIF`
--
ALTER TABLE `LIF`
  ADD CONSTRAINT `LIF_ibfk_1` FOREIGN KEY (`ModelID`) REFERENCES `ModelLibrary` (`ModelID`);

--
-- Constraints for table `UserSimulation`
--
ALTER TABLE `UserSimulation`
  ADD CONSTRAINT `UserSimulation_ibfk_2` FOREIGN KEY (`UserId`) REFERENCES `UserDetails` (`UserId`),
  ADD CONSTRAINT `UserSimulation_ibfk_4` FOREIGN KEY (`SimulationId`) REFERENCES `SImulation` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
