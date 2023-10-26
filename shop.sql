-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 23-10-25 17:07
-- 서버 버전: 10.4.28-MariaDB
-- PHP 버전: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `shop`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `goods`
--

CREATE TABLE `goods` (
  `idx` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `src` varchar(255) NOT NULL DEFAULT '',
  `price` int(11) NOT NULL DEFAULT 1000,
  `content_abb` text NOT NULL DEFAULT '',
  `content` text NOT NULL DEFAULT '',
  `specifications` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `goods`
--

INSERT INTO `goods` (`idx`, `name`, `src`, `price`, `content_abb`, `content`, `specifications`) VALUES
(1, 'TOYOTA SUPRA A80', 'https://cdn.pixabay.com/photo/2015/10/01/13/36/car-967011_640.jpg', 10000000, '<h5 class=\"card-title\">\"Above\",\"To surpass\",\"Go beyond\"</h5>\r\n    <h6 class=\"card-subtitle text-muted\">Masterpiece of the <strong>Toyota Motor Corporation</strong></h6>', 'The initial four generations of the Supra were produced from 1978 to 2002. The fifth generation has been produced since March 2019 and went on sale in May 2019. The styling of the original Supra was derived from the Toyota Celica, but it was both longer and wider. Starting in mid-1986, the A70 Supra became a separate model from the Celica. In turn, Toyota also stopped using the prefix Celica and named the car Supra. Owing to the similarity and past of the Celica\'s name, it is frequently mistaken for the Supra, and vice versa. The first, second and third generations of the Supra were assembled at the Tahara plant in Tahara, Aichi, while the fourth generation was assembled at the Motomachi plant in Toyota City. The 5th generation of the Supra is assembled alongside the G29 BMW Z4 in Graz, Austria by Magna Steyr.', '<strong>Manufacturer: </strong>Toyota<br>\r\n        <strong>Also called: </strong>Toyota Celica XX<br>\r\n        <strong>Production: </strong>April 1978 ~ August 2002<br>'),
(2, 'Air Jordan', 'https://cdn.pixabay.com/photo/2019/11/27/16/47/jordan-4657349_1280.jpg', 120000, '<h5 class=\"card-title\">\"Jumpman\",\"Higher\",\"And higher\"</h5>\r\n    <h6 class=\"card-subtitle text-muted\">Masterpiece of the <strong>Nike</strong></h6>', 'Air Jordan is a line of basketball shoes produced by Nike, Inc. Related apparel and accessories are marketed under Jordan Brand.\r\nThe first Air Jordan shoe was produced for basketball player Michael Jordan during his time with the Chicago Bulls in late 1984 and released to the public on April 1, 1985', '<strong>Product type: </strong>Basketball shoes, clothing<br>\r\n        <strong>Owner: </strong>Nike<br>\r\n        <strong>Country: </strong>United States<br>'),
(3, 'AirPods', 'https://t3.ftcdn.net/jpg/02/71/15/12/240_F_271151235_5F7Y2WGW1r7Cqxd9RbNQHpaD75qPjTRe.jpg', 380000, '<h5 class=\"card-title\">\"Rebuilt from the sound up\"</h5>\r\n    <h6 class=\"card-subtitle text-muted\">Masterpiece of the <strong>Apple</strong></h6>', 'AirPods are wireless Bluetooth earbuds designed by Apple Inc. They were first announced on September 7, 2016, alongside the iPhone 7. Within two years, they became Apple\'s most popular accessory. AirPods are Apple\'s entry-level wireless headphones, sold alongside the AirPods Pro and AirPods Max.\r\n\r\nIn addition to playing audio, the AirPods contain a microphone that filters out background noise as well as built-in accelerometers and optical sensors capable of detecting taps and pinches (e.g. double-tap or pinch to pause audio) and placement within the ear, which enables automatic pausing of audio when they are taken out.', '<strong>Developer: </strong>Apple Inc.<br>\r\n        <strong>Manufacturer: </strong>Luxshare & GoerTek<br>\r\n        <strong>Type: </strong>Wireless earbuds<br>'),
(4, 'The mischievous cat', 'https://t4.ftcdn.net/jpg/03/03/62/45/240_F_303624505_u0bFT1Rnoj8CMUSs8wMCwoKlnWlh5Jiq.jpg', 1004, '<h5 class=\"card-title\">\"Troublesome\",\"mischievous\",\"want to re-home\"</h5>\r\n    <h6 class=\"card-subtitle text-muted\">My <strong>cat</strong></h6>', '“As anyone who has ever been around a cat for any length of time well knows, cats have enormous patience with the limitations of the humankind.” – Cleveland Amory', 'Just a cat');

-- --------------------------------------------------------

--
-- 테이블 구조 `orders`
--

CREATE TABLE `orders` (
  `idx` int(11) NOT NULL,
  `good_idx` int(11) NOT NULL DEFAULT 0,
  `ordr_idxx` varchar(255) NOT NULL DEFAULT 'TEST000000001',
  `good_name` varchar(255) NOT NULL DEFAULT '',
  `good_mny` int(11) NOT NULL DEFAULT 1000,
  `buyr_name` varchar(255) NOT NULL DEFAULT '',
  `buyr_tel1` varchar(255) NOT NULL DEFAULT '',
  `buyr_tel2` varchar(255) NOT NULL DEFAULT '',
  `buyr_mail` varchar(255) NOT NULL DEFAULT '',
  `discount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `payments`
--

CREATE TABLE `payments` (
  `idx` int(11) NOT NULL,
  `buyr_name` varchar(255) NOT NULL DEFAULT '',
  `pay_method` varchar(255) NOT NULL DEFAULT '',
  `tno` varchar(255) NOT NULL DEFAULT '',
  `amount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `payments`
--

INSERT INTO `payments` (`idx`, `buyr_name`, `pay_method`, `tno`, `amount`) VALUES
(1, 'Louis', '100000000000', '23929999996888', 10001004);

-- --------------------------------------------------------

--
-- 테이블 구조 `user`
--

CREATE TABLE `user` (
  `idx` int(11) NOT NULL,
  `buyr_name` varchar(255) NOT NULL DEFAULT '',
  `buyr_password` varchar(255) NOT NULL DEFAULT '',
  `buyr_tel1` varchar(255) NOT NULL DEFAULT '',
  `buyr_tel2` varchar(255) NOT NULL DEFAULT '',
  `buyr_mail` varchar(255) NOT NULL DEFAULT '',
  `discount` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `user`
--

INSERT INTO `user` (`idx`, `buyr_name`, `buyr_password`, `buyr_tel1`, `buyr_tel2`, `buyr_mail`, `discount`) VALUES
(1, 'Louis', '927522d80d6ca64f0266ebc444fe779e63b4a56643050aa8377c7fa93d507e3c', '01077777777', '027777777', 'skshieldus.tester@gmail.com', 0);

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`idx`);

--
-- 테이블의 인덱스 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idx`);

--
-- 테이블의 인덱스 `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`idx`);

--
-- 테이블의 인덱스 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `goods`
--
ALTER TABLE `goods`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 테이블의 AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `payments`
--
ALTER TABLE `payments`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 테이블의 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
