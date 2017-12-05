/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MariaDB
 Source Server Version : 100210
 Source Host           : localhost:3306
 Source Schema         : any_buy

 Target Server Type    : MariaDB
 Target Server Version : 100210
 File Encoding         : 65001

 Date: 01/12/2017 22:53:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (3, '女生衣著');
INSERT INTO `category` VALUES (4, '男生衣著');
INSERT INTO `category` VALUES (5, '美妝保健');
INSERT INTO `category` VALUES (6, '手機平板與周邊');
INSERT INTO `category` VALUES (7, '嬰幼童與母親');
INSERT INTO `category` VALUES (8, '3C相關');
INSERT INTO `category` VALUES (9, '居家生活');
INSERT INTO `category` VALUES (10, '家電影音');
INSERT INTO `category` VALUES (11, '女生配件');
INSERT INTO `category` VALUES (12, '男生包包與配件');
INSERT INTO `category` VALUES (13, '女鞋');
INSERT INTO `category` VALUES (14, '男鞋');
INSERT INTO `category` VALUES (15, '女生包包');
INSERT INTO `category` VALUES (16, '戶外與運動用品');
INSERT INTO `category` VALUES (17, '美食、伴手禮');
INSERT INTO `category` VALUES (18, '汽機車零件百貨');
INSERT INTO `category` VALUES (19, '寵物');
INSERT INTO `category` VALUES (20, '娛樂、收藏');
INSERT INTO `category` VALUES (21, '服務、票券');
INSERT INTO `category` VALUES (22, '遊戲王');
INSERT INTO `category` VALUES (23, '代買代購');
INSERT INTO `category` VALUES (24, '其他類別');

-- ----------------------------
-- Table structure for catlog
-- ----------------------------
DROP TABLE IF EXISTS `catlog`;
CREATE TABLE `catlog`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `CatLog_Discount`(`discount_id`) USING BTREE,
  INDEX `CatLog_Category`(`category_id`) USING BTREE,
  CONSTRAINT `CatLog_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `CatLog_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for discount
-- ----------------------------
DROP TABLE IF EXISTS `discount`;
CREATE TABLE `discount`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_discount_time` datetime(0) NULL DEFAULT NULL,
  `end_discount_time` datetime(0) NULL DEFAULT NULL,
  `type` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` float(11, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for location
-- ----------------------------
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Location_User`(`user_id`) USING BTREE,
  CONSTRAINT `Location_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of location
-- ----------------------------
INSERT INTO `location` VALUES (18, 14, 'dfgsdgsdfhdsfh', '100');
INSERT INTO `location` VALUES (19, 14, 'fhfgjtrret', '1');

-- ----------------------------
-- Table structure for login_log
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for on_product
-- ----------------------------
DROP TABLE IF EXISTS `on_product`;
CREATE TABLE `on_product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime(0) NULL DEFAULT NULL,
  `end_date` datetime(0) NULL DEFAULT NULL,
  `price` int(11) NOT NULL,
  `state` int(1) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Product_Category`(`category_id`) USING BTREE,
  INDEX `Product_Businessman`(`user_id`) USING BTREE,
  CONSTRAINT `Product_Businessman` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Product_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of on_product
-- ----------------------------
INSERT INTO `on_product` VALUES (22, 'asd', 'asdasf\r\nasg\r\nas\r\ngas\r\ng\r\n[b]asg[/b]\r\ngfh[b]dfghd[/b]fgh', '2017-10-10 10:38:31', '2017-12-09 10:38:31', 50, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (23, 'test', '3435345er\r\n\r\ndfg\r\ndg\r\ndfg\r\ndfg', '2017-11-09 01:01:00', '2018-02-03 12:56:00', 1235, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (24, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (26, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 1, 4, 1, 500);
INSERT INTO `on_product` VALUES (27, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 1, 5, 1, 500);
INSERT INTO `on_product` VALUES (28, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 1, 3, 1, 500);
INSERT INTO `on_product` VALUES (29, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 1, 4, 1, 500);
INSERT INTO `on_product` VALUES (30, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 1, 5, 1, 500);
INSERT INTO `on_product` VALUES (31, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 1, 3, 1, 500);
INSERT INTO `on_product` VALUES (34, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1);

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `order_time` timestamp(0) NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP(0),
  `sent_time` timestamp(0) NULL DEFAULT NULL,
  `arrival_time` timestamp(0) NULL DEFAULT NULL,
  `final_cost` int(11) NOT NULL,
  `discount_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Order_Location`(`location_id`) USING BTREE,
  INDEX `Order_Customer`(`customer_id`) USING BTREE,
  INDEX `Order_Discount`(`discount_id`) USING BTREE,
  CONSTRAINT `Order_Customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Order_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Order_Location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES (30, 18, 14, 0, '2017-11-28 11:01:17', '2017-11-28 12:01:20', '2017-11-28 17:01:20', 3850, NULL);

-- ----------------------------
-- Table structure for order_product
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product`  (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`order_id`, `product_id`) USING BTREE,
  INDEX `OP_Poduct`(`product_id`) USING BTREE,
  CONSTRAINT `OP_Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `OP_Poduct` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_product
-- ----------------------------
INSERT INTO `order_product` VALUES (30, 22, 77);

-- ----------------------------
-- Table structure for private_message
-- ----------------------------
DROP TABLE IF EXISTS `private_message`;
CREATE TABLE `private_message`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receive_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_datetime` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `PM_User`(`receive_id`) USING BTREE,
  CONSTRAINT `PM_User` FOREIGN KEY (`receive_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_picture
-- ----------------------------
DROP TABLE IF EXISTS `product_picture`;
CREATE TABLE `product_picture`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Pic_Pro`(`product_id`) USING BTREE,
  CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_picture
-- ----------------------------
INSERT INTO `product_picture` VALUES (6, 'images/1JVH5bCxlxCv6T5bWp48gyXpY2TtR9iuT6VrQJWN.jpeg', 23);
INSERT INTO `product_picture` VALUES (8, 'images/IMG_2675_02.jpg', 26);
INSERT INTO `product_picture` VALUES (9, 'images/IMG_2675_03.jpg', 27);
INSERT INTO `product_picture` VALUES (10, 'images/IMG_2675_04.jpg', 28);
INSERT INTO `product_picture` VALUES (11, 'images/IMG_2675_05.jpg', 29);
INSERT INTO `product_picture` VALUES (12, 'images/IMG_2675_06.jpg', 30);
INSERT INTO `product_picture` VALUES (13, 'images/IMG_2675_07.jpg', 31);

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Tag_Product`(`product_id`) USING BTREE,
  CONSTRAINT `Tag_Product` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `role` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign_up_datetime` timestamp(0) NULL DEFAULT current_timestamp(),
  `birthday` date NOT NULL,
  `gender` text CHARACTER SET big5 COLLATE big5_chinese_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'admin', '$2y$10$kTdU0L2e.in.Efe35fCKausr/YUihzTNr0.ZlEyG8iAeTQladEOHG', 'seeder', 'A', '管理員', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1);
INSERT INTO `user` VALUES (13, 'acco001', '$2y$10$BhMIDW9in1Ql7ML5e1sy0O97lBH7KtdIpNglU02pruYew76kAFrJ2', 'RRR', 'A', '管理員', '2017-11-14 20:04:26', '2014-09-18', '男', '122132', 0);
INSERT INTO `user` VALUES (14, 'acco002', '$2y$10$.7qG9G0WIGnoN.aABdK4P.NLP37mLWQHTIu8yavD53MjmTH.KT6aG', '21', 'C', '12', '2017-11-15 14:56:38', '2014-09-18', '男', '21', 0);
INSERT INTO `user` VALUES (15, 'b', '$2y$10$HHAJWr53LOGkb9iit1OWuOw7yde44lvrSxp7vSo/jj8BCd7A0XEhu', 'seeder', 'B', '商人', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1);
INSERT INTO `user` VALUES (17, 'c', '$2y$10$ZNos9v1FpV.wrj/TkQKKwOzqWUFPox6xgkZEp0fTCwKCeOPP.nLdC', 'seeder', 'C', '客人', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1);

-- ----------------------------
-- Table structure for verification
-- ----------------------------
DROP TABLE IF EXISTS `verification`;
CREATE TABLE `verification`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `front_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `back_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_datetime` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `verify_result` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '未驗證',
  `datetime` timestamp(0) NULL DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE,
  INDEX `Verification_User`(`user_id`) USING BTREE,
  CONSTRAINT `Verification_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of verification
-- ----------------------------
INSERT INTO `verification` VALUES (6, 15, 'images/WYfNpmn1K5rU3Rqobdk0i7t2SrUqJ0rWPpWjxwXS.png', 'images/qlLpJxcD14nMq5pDpIZCnml7rZIiCE8o6pGBspWj.png', '2017-11-27 17:51:39', '驗證失敗', '2017-11-27 17:51:40', 'asdf');

SET FOREIGN_KEY_CHECKS = 1;
