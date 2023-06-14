-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2019 at 04:16 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qrcodeitemscan`
--

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesadmin`
--

CREATE TABLE `qrcodesadmin` (
  `qrAdminId` int(255) NOT NULL,
  `qrAdminName` varchar(535) NOT NULL,
  `qrAdminPassword` varchar(535) NOT NULL,
  `qrAdminFirstName` varchar(535) NOT NULL,
  `qrAdminLastName` varchar(535) NOT NULL,
  `qrAdminSecurityQuestion` varchar(535) NOT NULL,
  `qrAdminSecurityAnswer` varchar(535) NOT NULL,
  `qrAdminLogInAttempt` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesadmin`
--

INSERT INTO `qrcodesadmin` (`qrAdminId`, `qrAdminName`, `qrAdminPassword`, `qrAdminFirstName`, `qrAdminLastName`, `qrAdminSecurityQuestion`, `qrAdminSecurityAnswer`, `qrAdminLogInAttempt`) VALUES
(1, '', '', '', '', '', '', 0),
(2, 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'admin', 'admin', 'In what city or town was your first job?', '51f303d65bf86d108821694aaf6187584e0d9708bdda83fd3c1bb9b0931ba1045ec6ecf3589d84079c29702b07c14204c12f16cfd3b715a1662c10c2821f1fef', 0);

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesaudittrail`
--

CREATE TABLE `qrcodesaudittrail` (
  `qrAuditTrailId` int(255) NOT NULL,
  `qrUserActivity` varchar(535) NOT NULL,
  `qrAuditTrailDate` date NOT NULL,
  `qrAuditTrailTime` varchar(535) NOT NULL,
  `qrAdminId` int(255) NOT NULL,
  `qrCashierId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesaudittrail`
--


-- --------------------------------------------------------

--
-- Table structure for table `qrcodesbalance`
--

CREATE TABLE `qrcodesbalance` (
  `qrBalanceId` int(255) NOT NULL,
  `qrBalanceBegin` int(255) NOT NULL,
  `qrBalanceDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesbalance`
--

-- --------------------------------------------------------

--
-- Table structure for table `qrcodescashier`
--

CREATE TABLE `qrcodescashier` (
  `qrCashierId` int(255) NOT NULL,
  `qrCashierName` varchar(535) NOT NULL,
  `qrCashierPassword` varchar(535) NOT NULL,
  `qrCashierFirstName` varchar(535) NOT NULL,
  `qrCashierLastName` varchar(535) NOT NULL,
  `qrCashierSecurityQuestion` varchar(535) NOT NULL,
  `qrCashierSecurityAnswer` varchar(535) NOT NULL,
  `qrCashierChangePassRequest` varchar(535) NOT NULL,
  `qrCashierLogInAttempt` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodescashier`
--

INSERT INTO `qrcodescashier` (`qrCashierId`, `qrCashierName`, `qrCashierPassword`, `qrCashierFirstName`, `qrCashierLastName`, `qrCashierSecurityQuestion`, `qrCashierSecurityAnswer`, `qrCashierChangePassRequest`, `qrCashierLogInAttempt`) VALUES
(1, '', '', '', '', '', '', '', 0),
(2, 'cashier', 'e9889139719b1093cc8f717bd668cf975d8bcdf31d363c010a6248714e1ce65032fee9cca302139980c5b91a36430130f988bdd79cfe101a0cba4e92e2f9e6e7', 'cashier', 'cashier', 'What is the name of your pet?', '3fdc1cc2d77a1a89800c2750e95063fa3d1d71cbe2b9cb45fd6f611c7a12874b0e996c5d56a3aadef6d0fbc98e5c1c5d13a945c360769e07214f341bd136a411', 'Pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesorderproduct`
--

CREATE TABLE `qrcodesorderproduct` (
  `qrOrderProductId` int(255) NOT NULL,
  `qrOrderProductQtyCritical` int(255) NOT NULL,
  `qrOrderSummaryId` int(255) NOT NULL,
  `qrStockId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesordersummary`
--

CREATE TABLE `qrcodesordersummary` (
  `qrOrderSummaryId` int(255) NOT NULL,
  `qrOrderSummaryNumFormat` varchar(535) NOT NULL,
  `qrOrderSummaryDate` date NOT NULL,
  `qrOrderSummaryTime` varchar(535) NOT NULL,
  `qrOrderSummarySupplierName` varchar(535) NOT NULL,
  `qrOrderSummarySupplierAddress` varchar(535) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesproduct`
--

CREATE TABLE `qrcodesproduct` (
  `qrProductId` int(255) NOT NULL,
  `qrProductCode` varchar(535) NOT NULL,
  `qrProductCost` decimal(65,2) NOT NULL,
  `qrProductProfitMarginPercent` decimal(65,2) NOT NULL,
  `qrProductPrice` decimal(65,2) NOT NULL,
  `qrProductPromoDiscount` decimal(65,2) NOT NULL,
  `qrProductCategory` varchar(535) NOT NULL,
  `qrImg` varchar(535) NOT NULL,
  `qrlink` varchar(535) NOT NULL,
  `qrSupplierId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesproduct`
--

-- --------------------------------------------------------

--
-- Table structure for table `qrcodespurchaseitem`
--

CREATE TABLE `qrcodespurchaseitem` (
  `qrPurchaseItemId` int(255) NOT NULL,
  `qrPurchaseItemQtyAvail` int(255) NOT NULL,
  `qrSalesTransactNum` int(255) NOT NULL,
  `qrStockId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodespurchaseitem`
--



-- --------------------------------------------------------

--
-- Table structure for table `qrcodesreturnitem`
--

CREATE TABLE `qrcodesreturnitem` (
  `qrReturnItemId` int(255) NOT NULL,
  `qrReturnItemQtyAvail` int(255) NOT NULL,
  `qrSalesTransactNum` int(255) NOT NULL,
  `qrStockId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesreturnitem`
--

-- --------------------------------------------------------

--
-- Table structure for table `qrcodessales`
--

CREATE TABLE `qrcodessales` (
  `qrSalesTransactNum` int(255) NOT NULL,
  `qrSalesTransactString` varchar(535) NOT NULL,
  `qrSalesInvoiceNum` varchar(535) NOT NULL,
  `qrSalesDate` date NOT NULL,
  `qrSalesAmount` decimal(65,2) NOT NULL,
  `qrSalesTenderCash` decimal(65,2) NOT NULL,
  `qrSalesAmountChange` decimal(65,2) NOT NULL,
  `qrSalesTaxPercentage` decimal(65,2) NOT NULL,
  `qrSalesTaxAmount` decimal(65,2) NOT NULL,
  `qrCashierId` int(255) NOT NULL,
  `qrBalanceId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodessales`
--

-- --------------------------------------------------------

--
-- Table structure for table `qrcodesstock`
--

CREATE TABLE `qrcodesstock` (
  `qrStockId` int(255) NOT NULL,
  `qrStockUnitField` varchar(535) NOT NULL,
  `qrStockQtyPerBundle` decimal(65,2) NOT NULL,
  `qrStockStorageSection` varchar(535) NOT NULL,
  `qrStockQtyLeft` int(255) NOT NULL,
  `qrStockCriticalLevelSet` int(255) NOT NULL,
  `qrProductId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodesstock`
--

INSERT INTO `qrcodesstock` (`qrStockId`, `qrStockUnitField`, `qrStockQtyPerBundle`, `qrStockStorageSection`, `qrStockQtyLeft`, `qrStockCriticalLevelSet`, `qrProductId`) VALUES
(1, 'piece', '100.00', 'A', 94, 30, 1),
(2, '10(sheets)', '10.00', 'A', 102, 30, 2),
(3, '10(sheets)', '10.00', 'A', 98, 30, 3),
(4, 'piece', '50.00', 'A', 59, 30, 4),
(5, '20(sheets)', '20.00', 'A', 50, 30, 5),
(6, 'piece', '40.00', 'A', 43, 30, 6),
(7, '10(sheets)', '10.00', 'A', 48, 30, 7),
(8, '10(sheets)', '10.00', 'A', 48, 30, 8),
(9, '20(sheets)', '20.00', 'A', 27, 30, 9),
(10, '20(sheets)', '20.00', 'A', 54, 30, 10),
(11, '20(sheets)', '20.00', 'A', 23, 30, 11),
(12, '10(sheets)', '10.00', 'A', 34, 30, 12),
(13, '10(sheets)', '10.00', 'A', 99, 30, 13),
(14, '30(sheets)', '30.00', 'A', 53, 30, 14),
(15, '500(sheets)', '50.00', 'A', 5, 30, 15),
(16, '500(sheets)', '50.00', 'A', 100, 30, 16),
(17, '500(sheets)', '50.00', 'A', 40, 30, 17),
(18, '20(sheets)', '20.00', 'A', 35, 30, 18),
(19, '20(sheets)', '20.00', 'A', 25, 30, 19),
(20, '50(sheets)', '50.00', 'A', 22, 30, 20),
(21, 'piece', '20.00', 'A', 99, 30, 21),
(22, 'piece', '5.00', 'A', 81, 30, 22),
(23, 'piece', '1.00', 'A', 33, 30, 23),
(24, '16(sheets)', '16.00', 'A', 23, 30, 24),
(25, 'piece', '1.00', 'A', 48, 30, 25),
(26, 'piece', '1.00', 'A', 44, 30, 26),
(27, '80(sheets)', '80.00', 'A', 105, 30, 27);

-- --------------------------------------------------------

--
-- Table structure for table `qrcodessupplier`
--

CREATE TABLE `qrcodessupplier` (
  `qrSupplierId` int(255) NOT NULL,
  `qrSupplierName` varchar(535) NOT NULL,
  `qrSupplierBrandName` varchar(535) NOT NULL,
  `qrSupplierProductName` varchar(535) NOT NULL,
  `qrSupplierProductQtyDelivered` int(255) NOT NULL,
  `qrSupplierProductDateExpired` date NOT NULL,
  `qrSupplierAddress` varchar(535) NOT NULL,
  `qrSupplierContactNum` varchar(535) NOT NULL,
  `qrSupplierContactPerson` varchar(535) NOT NULL,
  `qrSupplierTimeDelivered` varchar(535) NOT NULL,
  `qrSupplierDateDelivered` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qrcodessupplier`
--

INSERT INTO `qrcodessupplier` (`qrSupplierId`, `qrSupplierName`, `qrSupplierBrandName`, `qrSupplierProductName`, `qrSupplierProductQtyDelivered`, `qrSupplierProductDateExpired`, `qrSupplierAddress`, `qrSupplierContactNum`, `qrSupplierContactPerson`, `qrSupplierTimeDelivered`, `qrSupplierDateDelivered`) VALUES
(1, 'Thunder Supplies', 'Best Buy', 'Embossed Board (Letter size)', 100, '2019-03-26', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '01:00 AM', '2017-01-05'),
(2, 'Thunder Supplies', 'Best Buy', 'Vellum Board', 50, '2019-03-26', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '01:15 AM', '2017-01-10'),
(3, 'Pinoy Supplies', 'Parfum', 'Parfum Pearlescent Board 200grsm', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '01:30 AM', '2017-01-15'),
(4, 'Henry and Yen Supplies', 'Best Buy', 'Embossed Board (Legal size)', 60, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '01:45 AM', '2017-01-20'),
(5, 'Steve Supplies', 'Best Buy', 'Photo paper A4 size 230grsm', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '02:00 AM', '2017-01-25'),
(6, 'Steve Supplies', 'Best Buy', 'Photo paper A4 size 210grsm', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '02:15 AM', '2017-01-30'),
(7, 'Henry and Yen Supplies', 'Best Buy', 'Sticker paper A4 size Glossy', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '02:30 AM', '2017-02-05'),
(8, 'Henry and Yen Supplies', 'Best Buy', 'Sticker paper A4 size matte', 50, '2019-03-26', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '02:45 AM', '2017-02-10'),
(9, 'Henry and Yen Supplies', 'Best Buy', 'Bond paper Long size', 25, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '03:00 AM', '2017-02-15'),
(10, 'Henry and Yen Supplies', 'Best Buy', 'Bond paper A4 size', 50, '2019-03-26', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '03:15 AM', '2017-02-20'),
(11, 'Joseph Supplies', 'Best Buy', 'Bond paper short size', 25, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '03:30 AM', '2017-02-25'),
(12, 'Thunder Supplies', 'Best Buy', 'oslo paper', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '03:45 AM', '2017-03-05'),
(13, 'Thunder Supplies', 'Best Buy', 'Graphing paper ', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '04:00 AM', '2017-03-10'),
(14, 'Pinoy Supplies', 'Best Buy', 'Bond paper A3 size', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '04:15 AM', '2017-03-15'),
(15, 'Steve Supplies', 'Hard copy', 'Hard copy letter size (1 rim)', 25, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '04:30 AM', '2017-03-20'),
(16, 'Joseph Supplies', 'Hard copy', 'Hard copy A4 size (1 rim)', 30, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '04:45 AM', '2017-03-25'),
(17, 'Steve Supplies', 'Hard copy', 'Hard copy Legal size (1 rim)', 40, '2019-03-26', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '05:00 AM', '2017-03-30'),
(18, 'Steve Supplies', 'Diamond', 'Diamond Sketch pad ', 30, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '05:15 AM', '2017-04-05'),
(19, 'Thunder Supplies', 'Focus', 'focus Sketch pad ', 30, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Paul Lopez', '05:30 AM', '2017-04-10'),
(20, 'Thunder Supplies', 'Focus', 'focus Water color book', 35, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Paul Lopez', '05:45 AM', '2017-04-15'),
(21, 'Thunder Supplies', 'Best Buy', 'Music book', 100, '2019-03-26', 'Amadeo, Cavite', '09258123976', 'Paul Lopez', '06:00 AM', '2017-04-20'),
(22, 'Thunder Supplies', 'Focus', 'focus drawing book', 100, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Paul Lopez', '06:15 AM', '2017-04-25'),
(23, 'Joseph Supplies', 'Cattleya', 'Cattleya mini fillers', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '06:30 AM', '2017-04-30'),
(24, 'Joseph Supplies', 'Focus', 'focus fillers', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '06:45 AM', '2017-05-05'),
(25, 'Joseph Supplies', 'Best Buy', 'color mood filler', 50, '2019-03-25', 'Makati City', '09276543219', 'Juan Basillo', '07:00 AM', '2017-05-10'),
(26, 'Joseph Supplies', 'Best Buy', 'Leather Jacket binder', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '07:15 AM', '2017-05-15'),
(27, 'Joseph Supplies', 'Bubbles', 'Bubbles poket notebook', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '07:30 AM', '2017-05-20'),
(28, 'Pinoy Supplies', 'Uno', 'Uno Pocket Notebook (Steno Style)', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '07:45 AM', '2017-05-25'),
(29, 'Pinoy Supplies', 'Hots', 'Hots Big Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '08:00 AM', '2017-05-30'),
(30, 'Pinoy Supplies', 'Vanda', 'Vanda Big Graphing Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '08:15 AM', '2017-06-05'),
(31, 'Pinoy Supplies', 'Vanda', 'Vanda Small Graphing Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '08:30 AM', '2017-06-10'),
(32, 'Pinoy Supplies', 'Advance', 'Advance Steno Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '08:45 AM', '2017-06-15'),
(33, 'Pinoy Supplies', 'Soppi', 'Soppi Fine Stationary Small Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '09:00 AM', '2017-06-20'),
(34, 'Pinoy Supplies', 'Hots', 'Hots Writing Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '09:15 AM', '2017-06-25'),
(35, 'Pinoy Supplies', 'Orions', 'Orions Hello Kitty Composition Notebook', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Paul Lopez', '09:30 AM', '2017-06-30'),
(36, 'Henry and Yen Supplies', 'Orions', 'Orions Composition Notebook', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '09:45 AM', '2017-07-05'),
(37, 'Henry and Yen Supplies', 'Orions', 'Orions Writing Notebook', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '10:00 AM', '2017-07-10'),
(38, 'Henry and Yen Supplies', 'Spiral', 'Spiral Composition Notebook', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '10:15 AM', '2017-07-15'),
(39, 'Henry and Yen Supplies', 'Centurian', 'Centurian Yarned Composition Notebook', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '10:30 AM', '2017-07-20'),
(40, 'Henry and Yen Supplies', 'Best Buy', 'Index Card (1/2)', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '10:45 AM', '2017-07-25'),
(41, 'Henry and Yen Supplies', 'Best Buy', 'Index Card (1/4)', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '11:00 AM', '2017-07-30'),
(42, 'Henry and Yen Supplies', 'Best Buy', 'Index Card (1/8)', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '11:15 AM', '2017-08-05'),
(43, 'Henry and Yen Supplies', 'Jinxin', 'Jinxin Sticky notes', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Renz Monal', '11:30 AM', '2017-08-10'),
(44, 'Joseph Supplies', 'SQI', 'SQI Office Mini Stapler', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '11:45 AM', '2017-08-15'),
(45, 'Joseph Supplies', 'HBW', 'HBW Office Basic Stationary Essentials', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '12:00 PM', '2017-08-20'),
(46, 'Joseph Supplies', 'HBW', 'HBW Office One Hole Punch 1/4', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '12:15 PM', '2017-08-25'),
(47, 'Joseph Supplies', 'Hi', 'Hi Crafts Medium Punch 2 Hole', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '12:30 PM', '2017-08-30'),
(48, 'Joseph Supplies', 'HBW', 'HBW Office Mini Puncher 2 Hole', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '12:45 PM', '2017-09-05'),
(49, 'Joseph Supplies', 'HBW', 'HBW Office Dual Core Tape Dispenser', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '01:00 PM', '2017-09-10'),
(50, 'Joseph Supplies', 'SCCOR', 'SCCOR File Case Legal Size', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '01:15 PM', '2017-09-15'),
(51, 'Joseph Supplies', 'Officemate', 'Officemate File Case A5 Size', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '01:30 PM', '2017-09-20'),
(52, 'Joseph Supplies', 'Adventure', 'Adventure Expanding Plastic Envelope', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '01:45 PM', '2017-09-25'),
(53, 'Joseph Supplies', 'Best Buy', 'Folder w/ Plastic Jacket Short', 50, '0000-00-00', 'Makati City', '09276543219', 'Erwin Seagal', '02:00 PM', '2017-09-30'),
(54, 'Thunder Supplies', 'Casa Ware', 'Casa Ware File Case Letter Size', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '02:15 PM', '2017-10-05'),
(55, 'Thunder Supplies', 'Fun Time', 'Fun Time Bookcase', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '02:30 PM', '2017-10-10'),
(56, 'Steve Supplies', 'Excellent', 'Excellent Writing Pad Grade 1', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '02:45 PM', '2017-10-15'),
(57, 'Steve Supplies', 'Asian', 'Asian Writing Pad Grade 2', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '03:00 PM', '2017-10-20'),
(58, 'Steve Supplies', 'Best Buy', 'Idols Writing Pad Grade 3', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '03:15 PM', '2017-10-25'),
(59, 'Steve Supplies', 'Asian', 'Asian Writing Pad Grade 3', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '03:30 PM', '2017-10-30'),
(60, 'Steve Supplies', 'Asian', 'Asian Writing Pad Grade 4', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '03:45 PM', '2017-11-05'),
(61, 'Steve Supplies', 'Vanda', 'Vanda Intermediate Spelling Tablet 4, 5 and 6', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '04:00 PM', '2017-11-10'),
(62, 'Steve Supplies', 'Vanda', 'Vanda Primary Spelling Tablet 1, 2 and 3', 50, '0000-00-00', 'Silang, Cavite', '09245792581', 'Kristina Samsom', '04:15 PM', '2017-11-15'),
(63, 'Pinoy Supplies', 'Deped', 'Deped K12 Spelling Booklet Primary 1, 2 and 3', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '04:30 PM', '2017-11-20'),
(64, 'Pinoy Supplies', 'Best Buy', 'Blue line Quiz Pad 1/2 Crosswise', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '04:45 PM', '2017-11-25'),
(65, 'Pinoy Supplies', 'Asian', 'Asian Quizpad 1/2 Lengthwise', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '05:00 PM', '2017-11-30'),
(66, 'Pinoy Supplies', 'Idols', 'Idols 1/4 Quiz Pad', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '05:15 PM', '2017-12-05'),
(67, 'Pinoy Supplies', 'IQ', 'IQ Intermediate Pad', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '05:30 PM', '2017-12-10'),
(68, 'Pinoy Supplies', 'Cattleya', 'Cattleya Yellow Ruled Pad', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '05:45 PM', '2017-12-15'),
(69, 'Pinoy Supplies', 'Atlantic', 'Atlantic Yellow Pad', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '06:00 PM', '2017-12-20'),
(70, 'Pinoy Supplies', 'Best Buy', 'Manila Paper', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '06:15 PM', '2017-12-25'),
(71, 'Pinoy Supplies', 'Best Buy', 'Expanded Envelope Organizer', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '06:30 PM', '2017-12-30'),
(72, 'Joseph Supplies', 'Go-in', 'Go-in small Expande Envelope Organizer', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '06:45 PM', '2018-01-05'),
(73, 'Joseph Supplies', 'Best Buy', 'ShortClearbook', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '07:00 PM', '2018-01-10'),
(74, 'Joseph Supplies', 'Best Buy', 'Long Clearbook', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '07:15 PM', '2018-01-15'),
(75, 'Joseph Supplies', 'Atlantic', 'Atlantic White Long Folder', 50, '0000-00-00', 'Makati City', '09276543219', 'Juan Basillo', '07:30 PM', '2018-01-20'),
(76, 'Henry and Yen Supplies', 'Ichoose', 'Ichoose White Short Folder', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '07:45 PM', '2018-01-25'),
(77, 'Henry and Yen Supplies', 'Best Buy', 'Long Brown Envelope', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '08:00 PM', '2018-01-30'),
(78, 'Henry and Yen Supplies', 'Best Buy', 'Short Brown Envelope', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '08:15 PM', '2018-02-05'),
(79, 'Henry and Yen Supplies', 'Best Buy', 'Sliding Folder Plastic', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '08:30 PM', '2018-01-10'),
(80, 'Henry and Yen Supplies', 'Best Buy', 'Sliding Folder Paper', 50, '0000-00-00', 'Calamba, Laguna', '09278526902', 'Paul Lopez', '08:45 PM', '2018-01-15'),
(81, 'Thunder Supplies', 'Best Buy', 'Sliding Folder Plastic Long', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '09:00 PM', '2018-01-20'),
(82, 'Thunder Supplies', 'Best Buy', 'Sliding Folder Paper Long', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '09:15 PM', '2018-01-25'),
(83, 'Thunder Supplies', 'Focus', 'Focus Clear Report Cover Sliding Folder', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '09:30 PM', '2018-01-30'),
(84, 'Thunder Supplies', 'Focus', 'Focus Clear Report Cover Sliding Folder (long)', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '09:45 PM', '2018-02-05'),
(85, 'Thunder Supplies', 'Best Buy', 'WhiteBoard', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '10:00 PM', '2018-02-10'),
(86, 'Thunder Supplies', 'Best Buy', 'BlackBoard', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '10:15 PM', '2018-02-15'),
(87, 'Thunder Supplies', 'Best Buy', 'CardBoard', 50, '0000-00-00', 'Amadeo, Cavite', '09258123976', 'Erick Mambao', '10:30 PM', '2018-02-20'),
(88, 'Pinoy Supplies', 'Best Buy', 'Small Whiteboard', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '10:45 PM', '2018-02-25'),
(89, 'Pinoy Supplies', 'Best Buy', 'Big Bomejia Artist Canvas', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '11:00 PM', '2018-03-05'),
(90, 'Pinoy Supplies', 'Best Buy', 'Small Bomejia Artist Canvas', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '11:15 PM', '2018-03-10'),
(91, 'Pinoy Supplies', 'Best Buy', 'Colored Folder Big', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '11:30 PM', '2018-03-15'),
(92, 'Pinoy Supplies', 'Best Buy', 'Colored Folder Small', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '11:45 PM', '2018-03-20'),
(93, 'Pinoy Supplies', 'Best Buy', 'Assorted Colored Paper ', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '12:00 AM', '2018-03-25'),
(94, 'Pinoy Supplies', 'Best Buy', 'Colored paper (1 Color)', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '12:15 AM', '2018-03-30'),
(95, 'Pinoy Supplies', 'Best Buy', 'Art Paper', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '12:30 AM', '2018-04-05'),
(96, 'Pinoy Supplies', 'Best Buy', 'Construction Paper', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '12:45 AM', '2018-04-10'),
(97, 'Pinoy Supplies', 'Best Buy', 'Chart', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '01:00 AM', '2018-04-15'),
(98, 'Pinoy Supplies', 'Best Buy', 'Yellow Pad', 50, '0000-00-00', 'Bacoor, Cavite', '09248510641', 'Steven Anderson', '01:15 AM', '2018-04-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `qrcodesadmin`
--
ALTER TABLE `qrcodesadmin`
  ADD PRIMARY KEY (`qrAdminId`);

--
-- Indexes for table `qrcodesaudittrail`
--
ALTER TABLE `qrcodesaudittrail`
  ADD PRIMARY KEY (`qrAuditTrailId`),
  ADD KEY `qrAdminIdFK` (`qrAdminId`),
  ADD KEY `qrCashierIdFK` (`qrCashierId`);

--
-- Indexes for table `qrcodesbalance`
--
ALTER TABLE `qrcodesbalance`
  ADD PRIMARY KEY (`qrBalanceId`);

--
-- Indexes for table `qrcodescashier`
--
ALTER TABLE `qrcodescashier`
  ADD PRIMARY KEY (`qrCashierId`);

--
-- Indexes for table `qrcodesorderproduct`
--
ALTER TABLE `qrcodesorderproduct`
  ADD PRIMARY KEY (`qrOrderProductId`),
  ADD KEY `qrOrderSummaryIdFK` (`qrOrderSummaryId`),
  ADD KEY `qrStockIdReuse2FK` (`qrStockId`);

--
-- Indexes for table `qrcodesordersummary`
--
ALTER TABLE `qrcodesordersummary`
  ADD PRIMARY KEY (`qrOrderSummaryId`);

--
-- Indexes for table `qrcodesproduct`
--
ALTER TABLE `qrcodesproduct`
  ADD PRIMARY KEY (`qrProductId`),
  ADD KEY `qrSupplierIdFK` (`qrSupplierId`);

--
-- Indexes for table `qrcodespurchaseitem`
--
ALTER TABLE `qrcodespurchaseitem`
  ADD PRIMARY KEY (`qrPurchaseItemId`),
  ADD KEY `qrSalesTransactNumFK` (`qrSalesTransactNum`),
  ADD KEY `qrStockIdFK` (`qrStockId`);

--
-- Indexes for table `qrcodesreturnitem`
--
ALTER TABLE `qrcodesreturnitem`
  ADD PRIMARY KEY (`qrReturnItemId`),
  ADD KEY `qrSalesTransactNumReuse1FK` (`qrSalesTransactNum`),
  ADD KEY `qrStockIdReuse1FK` (`qrStockId`);

--
-- Indexes for table `qrcodessales`
--
ALTER TABLE `qrcodessales`
  ADD PRIMARY KEY (`qrSalesTransactNum`),
  ADD KEY `qrCashierIdReuse1FK` (`qrCashierId`),
  ADD KEY `qrBalanceIdFK` (`qrBalanceId`);

--
-- Indexes for table `qrcodesstock`
--
ALTER TABLE `qrcodesstock`
  ADD PRIMARY KEY (`qrStockId`),
  ADD KEY `qrProductIdFK` (`qrProductId`);

--
-- Indexes for table `qrcodessupplier`
--
ALTER TABLE `qrcodessupplier`
  ADD PRIMARY KEY (`qrSupplierId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `qrcodesadmin`
--
ALTER TABLE `qrcodesadmin`
  MODIFY `qrAdminId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `qrcodesaudittrail`
--
ALTER TABLE `qrcodesaudittrail`
  MODIFY `qrAuditTrailId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=680;

--
-- AUTO_INCREMENT for table `qrcodesbalance`
--
ALTER TABLE `qrcodesbalance`
  MODIFY `qrBalanceId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `qrcodescashier`
--
ALTER TABLE `qrcodescashier`
  MODIFY `qrCashierId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qrcodesorderproduct`
--
ALTER TABLE `qrcodesorderproduct`
  MODIFY `qrOrderProductId` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qrcodesordersummary`
--
ALTER TABLE `qrcodesordersummary`
  MODIFY `qrOrderSummaryId` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qrcodesproduct`
--
ALTER TABLE `qrcodesproduct`
  MODIFY `qrProductId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `qrcodespurchaseitem`
--
ALTER TABLE `qrcodespurchaseitem`
  MODIFY `qrPurchaseItemId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `qrcodesreturnitem`
--
ALTER TABLE `qrcodesreturnitem`
  MODIFY `qrReturnItemId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `qrcodessales`
--
ALTER TABLE `qrcodessales`
  MODIFY `qrSalesTransactNum` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `qrcodesstock`
--
ALTER TABLE `qrcodesstock`
  MODIFY `qrStockId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `qrcodessupplier`
--
ALTER TABLE `qrcodessupplier`
  MODIFY `qrSupplierId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `qrcodesaudittrail`
--
ALTER TABLE `qrcodesaudittrail`
  ADD CONSTRAINT `qrAdminIdFK` FOREIGN KEY (`qrAdminId`) REFERENCES `qrcodesadmin` (`qrAdminId`),
  ADD CONSTRAINT `qrCashierIdFK` FOREIGN KEY (`qrCashierId`) REFERENCES `qrcodescashier` (`qrCashierId`);

--
-- Constraints for table `qrcodesorderproduct`
--
ALTER TABLE `qrcodesorderproduct`
  ADD CONSTRAINT `qrOrderSummaryIdFK` FOREIGN KEY (`qrOrderSummaryId`) REFERENCES `qrcodesordersummary` (`qrOrderSummaryId`),
  ADD CONSTRAINT `qrStockIdReuse2FK` FOREIGN KEY (`qrStockId`) REFERENCES `qrcodesstock` (`qrStockId`);

--
-- Constraints for table `qrcodesproduct`
--
ALTER TABLE `qrcodesproduct`
  ADD CONSTRAINT `qrSupplierIdFK` FOREIGN KEY (`qrSupplierId`) REFERENCES `qrcodessupplier` (`qrSupplierId`);

--
-- Constraints for table `qrcodespurchaseitem`
--
ALTER TABLE `qrcodespurchaseitem`
  ADD CONSTRAINT `qrSalesTransactNumFK` FOREIGN KEY (`qrSalesTransactNum`) REFERENCES `qrcodessales` (`qrSalesTransactNum`),
  ADD CONSTRAINT `qrStockIdFK` FOREIGN KEY (`qrStockId`) REFERENCES `qrcodesstock` (`qrStockId`);

--
-- Constraints for table `qrcodesreturnitem`
--
ALTER TABLE `qrcodesreturnitem`
  ADD CONSTRAINT `qrSalesTransactNumReuse1FK` FOREIGN KEY (`qrSalesTransactNum`) REFERENCES `qrcodessales` (`qrSalesTransactNum`),
  ADD CONSTRAINT `qrStockIdReuse1FK` FOREIGN KEY (`qrStockId`) REFERENCES `qrcodesstock` (`qrStockId`);

--
-- Constraints for table `qrcodessales`
--
ALTER TABLE `qrcodessales`
  ADD CONSTRAINT `qrBalanceIdFK` FOREIGN KEY (`qrBalanceId`) REFERENCES `qrcodesbalance` (`qrBalanceId`),
  ADD CONSTRAINT `qrCashierIdReuse1FK` FOREIGN KEY (`qrCashierId`) REFERENCES `qrcodescashier` (`qrCashierId`);

--
-- Constraints for table `qrcodesstock`
--
ALTER TABLE `qrcodesstock`
  ADD CONSTRAINT `qrProductIdFK` FOREIGN KEY (`qrProductId`) REFERENCES `qrcodesproduct` (`qrProductId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
