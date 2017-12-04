-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 2017-12-04 19:42:19
-- 伺服器版本: 10.2.11-MariaDB
-- PHP 版本： 7.1.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `any_buy`
--

DELIMITER $$
--
-- 函數
--
DROP FUNCTION IF EXISTS `GetDiffUserBuyProduct`$$
CREATE FUNCTION `GetDiffUserBuyProduct` (`pid` INT) RETURNS INT(11) BEGIN
	DECLARE c INT DEFAULT 0;
	SELECT Count(*) INTO c From order_product as od INNER JOIN `Order` as o ON o.id = od.order_id
Where od.product_id = pid Group By o.customer_id;
	RETURN c;
END$$

DROP FUNCTION IF EXISTS `GetSellCount`$$
CREATE FUNCTION `GetSellCount` (`pid` INT) RETURNS INT(11) BEGIN
		DECLARE c INT DEFAULT 0;
		SELECT Count(*) INTO c From order_product as od Where od.product_id = pid;
		RETURN c;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `product_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_index` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `category`
--

INSERT INTO `category` (`id`, `product_type`, `image_index`) VALUES
(3, '女生衣著', 0),
(4, '男生衣著', 1),
(5, '美妝保健', 2),
(6, '手機平板與周邊', 3),
(7, '嬰幼童與母親', 4),
(8, '3C相關', 5),
(9, '居家生活', 6),
(10, '家電影音', 7),
(11, '女生配件', 8),
(12, '男生包包與配件', 9),
(13, '女鞋', 10),
(14, '男鞋', 11),
(15, '女生包包', 12),
(16, '戶外與運動用品', 13),
(17, '美食、伴手禮', 14),
(18, '汽機車零件百貨', 15),
(19, '寵物', 16),
(20, '娛樂、收藏', 17),
(21, '服務、票券', 18),
(22, '遊戲王', 19),
(23, '代買代購', 20),
(24, '其他類別', 21);

-- --------------------------------------------------------

--
-- 資料表結構 `catlog`
--

DROP TABLE IF EXISTS `catlog`;
CREATE TABLE `catlog` (
  `id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 資料表結構 `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_discount_time` datetime DEFAULT NULL,
  `end_discount_time` datetime DEFAULT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 資料表結構 `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `location`
--

INSERT INTO `location` (`id`, `user_id`, `address`, `zip_code`) VALUES
(18, 14, 'dfgsdgsdfhdsfh', '100'),
(19, 14, 'fhfgjtrret', '1');

-- --------------------------------------------------------

--
-- 資料表結構 `login_log`
--

DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 資料表結構 `on_product`
--

DROP TABLE IF EXISTS `on_product`;
CREATE TABLE `on_product` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_information` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `price` int(11) NOT NULL,
  `state` int(1) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `on_product`
--

INSERT INTO `on_product` (`id`, `product_name`, `product_information`, `start_date`, `end_date`, `price`, `state`, `category_id`, `user_id`, `amount`) VALUES
(22, 'asd', 'asdasf\r\nasg\r\nas\r\ngas\r\ng\r\n[b]asg[/b]\r\ngfh[b]dfghd[/b]fgh', '2017-10-10 10:38:31', '2017-12-09 10:38:31', 50, 1, 3, 1, 50),
(23, 'test', '3435345er\r\n\r\ndfg\r\ndg\r\ndfg\r\ndfg', '2017-11-09 01:01:00', '2018-02-03 12:56:00', 1235, 0, 3, 15, 1),
(24, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 1, 3, 1, 1),
(26, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 1, 4, 1, 500),
(27, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 1, 5, 1, 500),
(28, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 1, 3, 1, 500),
(29, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 1, 4, 1, 500),
(30, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 1, 5, 1, 500),
(31, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 1, 3, 1, 500),
(34, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(35, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 1, 3, 1, 1),
(36, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 1, 4, 1, 500),
(37, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 1, 5, 1, 500),
(38, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 1, 3, 1, 500),
(39, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 1, 4, 1, 500),
(40, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 1, 5, 1, 500),
(41, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 1, 3, 1, 500),
(42, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(43, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(44, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 1, 4, 1, 500),
(45, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 1, 5, 1, 500),
(46, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 1, 3, 1, 500),
(47, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 1, 4, 1, 500),
(48, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 1, 5, 1, 500),
(49, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 1, 3, 1, 500),
(50, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(51, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(52, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 1, 4, 1, 500),
(53, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 2, 5, 1, 500),
(54, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 1, 3, 1, 500),
(55, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 1, 4, 1, 500),
(56, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 1, 5, 1, 500),
(57, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 1, 3, 1, 500),
(58, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(59, '123', 'ghfghdfg\r\nhdfh\r\ndfh', '2015-01-01 01:01:00', '2020-01-01 01:01:00', 123, 1, 7, 1, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `order_time` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sent_time` timestamp NULL DEFAULT NULL,
  `arrival_time` timestamp NULL DEFAULT NULL,
  `final_cost` int(11) NOT NULL,
  `discount_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `order`
--

INSERT INTO `order` (`id`, `location_id`, `customer_id`, `state`, `order_time`, `sent_time`, `arrival_time`, `final_cost`, `discount_id`) VALUES
(30, 18, 14, 0, '2017-11-28 03:01:17', '2017-11-28 04:01:20', '2017-11-28 09:01:20', 3850, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `order_product`
--

DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `order_product`
--

INSERT INTO `order_product` (`order_id`, `product_id`, `amount`) VALUES
(30, 22, 77);

-- --------------------------------------------------------

--
-- 資料表結構 `private_message`
--

DROP TABLE IF EXISTS `private_message`;
CREATE TABLE `private_message` (
  `id` int(11) NOT NULL,
  `receive_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 資料表結構 `product_picture`
--

DROP TABLE IF EXISTS `product_picture`;
CREATE TABLE `product_picture` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `product_picture`
--

INSERT INTO `product_picture` (`id`, `path`, `product_id`) VALUES
(6, 'images/1JVH5bCxlxCv6T5bWp48gyXpY2TtR9iuT6VrQJWN.jpeg', 23),
(8, 'images/IMG_2675_02.jpg', 26),
(9, 'images/IMG_2675_03.jpg', 27),
(10, 'images/IMG_2675_04.jpg', 28),
(11, 'images/IMG_2675_05.jpg', 29),
(12, 'images/IMG_2675_06.jpg', 30),
(13, 'images/IMG_2675_07.jpg', 31);

-- --------------------------------------------------------

--
-- 資料表結構 `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign_up_datetime` timestamp NULL DEFAULT current_timestamp(),
  `birthday` date NOT NULL,
  `gender` text CHARACTER SET big5 NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Imgur ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `sn`, `role`, `name`, `sign_up_datetime`, `birthday`, `gender`, `email`, `enable`, `avatar`) VALUES
(1, 'admin', '$2y$10$ESAWzypGNqJR7sOnD.wdFOxlM6d3bEEK7PUwdX0xiZ6K4gLEqouSq', 'seeder', 'A', '管理員', '2017-11-28 14:16:00', '2000-01-01', '男', 'ghjgfhjghj@dsj.com', 1, 't2m5UtK'),
(13, 'acco001', '$2y$10$BhMIDW9in1Ql7ML5e1sy0O97lBH7KtdIpNglU02pruYew76kAFrJ2', 'RRR', 'A', '管理員', '2017-11-14 12:04:26', '2014-09-18', '男', '122132', 0, ''),
(14, 'acco002', '$2y$10$.7qG9G0WIGnoN.aABdK4P.NLP37mLWQHTIu8yavD53MjmTH.KT6aG', '21', 'C', '12', '2017-11-15 06:56:38', '2014-09-18', '男', '21', 0, ''),
(15, 'b', '$2y$10$HHAJWr53LOGkb9iit1OWuOw7yde44lvrSxp7vSo/jj8BCd7A0XEhu', 'seeder', 'B', '商人', '2017-11-28 14:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, ''),
(17, 'c', '$2y$10$ZNos9v1FpV.wrj/TkQKKwOzqWUFPox6xgkZEp0fTCwKCeOPP.nLdC', 'seeder', 'C', '客人', '2017-11-28 14:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, '');

-- --------------------------------------------------------

--
-- 資料表結構 `verification`
--

DROP TABLE IF EXISTS `verification`;
CREATE TABLE `verification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `front_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `back_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_datetime` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `verify_result` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT '未驗證',
  `datetime` timestamp NULL DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- 資料表的匯出資料 `verification`
--

INSERT INTO `verification` (`id`, `user_id`, `front_picture`, `back_picture`, `upload_datetime`, `verify_result`, `datetime`, `description`) VALUES
(6, 15, 'images/WYfNpmn1K5rU3Rqobdk0i7t2SrUqJ0rWPpWjxwXS.png', 'images/qlLpJxcD14nMq5pDpIZCnml7rZIiCE8o6pGBspWj.png', '2017-11-27 09:51:39', '驗證失敗', '2017-11-27 09:51:40', 'asdf');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 資料表索引 `catlog`
--
ALTER TABLE `catlog`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `CatLog_Discount` (`discount_id`) USING BTREE,
  ADD KEY `CatLog_Category` (`category_id`) USING BTREE;

--
-- 資料表索引 `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 資料表索引 `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Location_User` (`user_id`) USING BTREE;

--
-- 資料表索引 `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 資料表索引 `on_product`
--
ALTER TABLE `on_product`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Product_Category` (`category_id`) USING BTREE,
  ADD KEY `Product_Businessman` (`user_id`) USING BTREE;

--
-- 資料表索引 `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Order_Location` (`location_id`) USING BTREE,
  ADD KEY `Order_Customer` (`customer_id`) USING BTREE,
  ADD KEY `Order_Discount` (`discount_id`) USING BTREE;

--
-- 資料表索引 `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_id`,`product_id`) USING BTREE,
  ADD KEY `OP_Poduct` (`product_id`) USING BTREE;

--
-- 資料表索引 `private_message`
--
ALTER TABLE `private_message`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `PM_User` (`receive_id`) USING BTREE;

--
-- 資料表索引 `product_picture`
--
ALTER TABLE `product_picture`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Pic_Pro` (`product_id`) USING BTREE;

--
-- 資料表索引 `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Tag_Product` (`product_id`) USING BTREE;

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `account` (`account`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE;

--
-- 資料表索引 `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE,
  ADD KEY `Verification_User` (`user_id`) USING BTREE;

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `catlog`
--
ALTER TABLE `catlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `on_product`
--
ALTER TABLE `on_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `private_message`
--
ALTER TABLE `private_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `product_picture`
--
ALTER TABLE `product_picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表 AUTO_INCREMENT `verification`
--
ALTER TABLE `verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `catlog`
--
ALTER TABLE `catlog`
  ADD CONSTRAINT `CatLog_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `CatLog_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`);

--
-- 資料表的 Constraints `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `Location_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- 資料表的 Constraints `on_product`
--
ALTER TABLE `on_product`
  ADD CONSTRAINT `Product_Businessman` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `Product_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- 資料表的 Constraints `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `Order_Customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `Order_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`),
  ADD CONSTRAINT `Order_Location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- 資料表的 Constraints `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `OP_Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `OP_Poduct` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`);

--
-- 資料表的 Constraints `private_message`
--
ALTER TABLE `private_message`
  ADD CONSTRAINT `PM_User` FOREIGN KEY (`receive_id`) REFERENCES `user` (`id`);

--
-- 資料表的 Constraints `product_picture`
--
ALTER TABLE `product_picture`
  ADD CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的 Constraints `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `Tag_Product` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`);

--
-- 資料表的 Constraints `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `Verification_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
