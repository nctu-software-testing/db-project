/*
 Navicat Premium Data Transfer

 Source Server         : DB_IS_Project
 Source Server Type    : MariaDB
 Source Server Version : 100210
 Source Schema         : any_buy

 Target Server Type    : MariaDB
 Target Server Version : 100210
 File Encoding         : 65001
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for catlog
-- ----------------------------
DROP TABLE IF EXISTS `catlog`;
CREATE TABLE `catlog`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `discount_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `CatLog_Discount`(`discount_id`),
  INDEX `CatLog_Category`(`category_id`),
  CONSTRAINT `CatLog_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `CatLog_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for discount
-- ----------------------------
DROP TABLE IF EXISTS `discount`;
CREATE TABLE `discount`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_discount_time` timestamp NOT NULL,
  `end_discount_time` timestamp NOT NULL,
  `type` char(1) NOT NULL,
  `discount_percent` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for location
-- ----------------------------
DROP TABLE IF EXISTS `location`;
CREATE TABLE `location`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `address` varchar(80) NOT NULL,
  `zip_code` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Location_User`(`user_id`),
  CONSTRAINT `Location_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for login_log
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `result` varchar(50) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for on_product
-- ----------------------------
DROP TABLE IF EXISTS `on_product`;
CREATE TABLE `on_product`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_information` text NOT NULL,
  `expiration_date` timestamp NOT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `price` int NOT NULL,
  `state` int(1) NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Product_Category`(`category_id`),
  CONSTRAINT `Product_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `state` varchar(6) NOT NULL,
  `order_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_time` timestamp NULL DEFAULT NULL,
  `arrival_time` timestamp NULL DEFAULT NULL,
  `final_cost` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Order_Location`(`location_id`),
  INDEX `Order_Customer`(`customer_id`),
  CONSTRAINT `Order_Customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Order_Location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for order_product
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product`  (
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`order_id`, `product_id`),
  INDEX `OP_Poduct`(`product_id`),
  CONSTRAINT `OP_Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `OP_Poduct` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for private_message
-- ----------------------------
DROP TABLE IF EXISTS `private_message`;
CREATE TABLE `private_message`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `receive_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `send_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `PM_User`(`receive_id`),
  CONSTRAINT `PM_User` FOREIGN KEY (`receive_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for product_picture
-- ----------------------------
DROP TABLE IF EXISTS `product_picture`;
CREATE TABLE `product_picture`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Pic_Pro`(`product_id`),
  CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `Tag_Product`(`product_id`),
  CONSTRAINT `Tag_Product` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `sn` varchar(50) NULL DEFAULT NULL,
  `role` char(1) NOT NULL,
  `name` text NOT NULL,
  `sign_up_datatime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `birthday` date NOT NULL,
  `gender` char(1) NOT NULL,
  `email` text NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `account`(`account`),
  INDEX `id`(`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for verification
-- ----------------------------
DROP TABLE IF EXISTS `verification`;
CREATE TABLE `verification`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `front_picture` varchar(255) NOT NULL,
  `back_picture` varchar(255) NOT NULL,
  `upload_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verify_result` varchar(8) NULL DEFAULT '未驗證',
  `datetime` timestamp NULL DEFAULT NULL,
  `description` varchar(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id`(`id`),
  INDEX `Verification_User`(`user_id`),
  CONSTRAINT `Verification_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
