/*
 Navicat Premium Data Transfer

 Source Server         : DB_IS_Project
 Source Server Type    : MariaDB
 Source Server Version : 100211
 Source Schema         : any_buy

 Target Server Type    : MariaDB
 Target Server Version : 100211
 File Encoding         : 65001

 Date: 05/12/2017 17:36:05
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
  `image_index` int(11) NOT NULL DEFAULT -1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (3, '女生衣著', 0);
INSERT INTO `category` VALUES (4, '男生衣著', 1);
INSERT INTO `category` VALUES (5, '美妝保健', 2);
INSERT INTO `category` VALUES (6, '手機平板與周邊', 3);
INSERT INTO `category` VALUES (7, '嬰幼童與母親', 4);
INSERT INTO `category` VALUES (8, '3C相關', 5);
INSERT INTO `category` VALUES (9, '居家生活', 6);
INSERT INTO `category` VALUES (10, '家電影音', 7);
INSERT INTO `category` VALUES (11, '女生配件', 8);
INSERT INTO `category` VALUES (12, '男生包包與配件', 9);
INSERT INTO `category` VALUES (13, '女鞋', 10);
INSERT INTO `category` VALUES (14, '男鞋', 11);
INSERT INTO `category` VALUES (15, '女生包包', 12);
INSERT INTO `category` VALUES (16, '戶外與運動用品', 13);
INSERT INTO `category` VALUES (17, '美食、伴手禮', 14);
INSERT INTO `category` VALUES (18, '汽機車零件百貨', 15);
INSERT INTO `category` VALUES (19, '寵物', 16);
INSERT INTO `category` VALUES (20, '娛樂、收藏', 17);
INSERT INTO `category` VALUES (21, '服務、票券', 18);
INSERT INTO `category` VALUES (22, '遊戲王', 19);
INSERT INTO `category` VALUES (23, '代買代購', 20);
INSERT INTO `category` VALUES (24, '其他類別', 21);

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
INSERT INTO `location` VALUES (18, 18, '新北市永和區保平路18巷1號', '234');
INSERT INTO `location` VALUES (19, 19, '台中市西區樂群街38號', '403');

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
) ENGINE = InnoDB AUTO_INCREMENT = 89 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of on_product
-- ----------------------------
INSERT INTO `on_product` VALUES (1, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10);
INSERT INTO `on_product` VALUES (2, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10);
INSERT INTO `on_product` VALUES (3, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10);
INSERT INTO `on_product` VALUES (4, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10);
INSERT INTO `on_product` VALUES (5, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10);
INSERT INTO `on_product` VALUES (6, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10);
INSERT INTO `on_product` VALUES (7, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (8, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (9, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10);
INSERT INTO `on_product` VALUES (10, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10);
INSERT INTO `on_product` VALUES (22, 'asd', 'asdasf\r\nasg\r\nas\r\ngas\r\ng\r\n[b]asg[/b]\r\ngfh[b]dfghd[/b]fgh', '2017-10-10 10:38:31', '2017-12-09 10:38:31', 50, 1, 3, 1, 1);
INSERT INTO `on_product` VALUES (23, 'test', '3435345er\r\n\r\ndfg\r\ndg\r\ndfg\r\ndfg', '2017-11-09 01:01:00', '2018-02-03 12:56:00', 1235, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (24, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (34, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (35, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (36, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (37, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (38, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (39, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (40, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (41, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (42, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (43, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (44, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (45, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (46, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (47, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (48, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (49, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (50, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (51, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1);
INSERT INTO `on_product` VALUES (52, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (53, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (54, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (55, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500);
INSERT INTO `on_product` VALUES (56, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500);
INSERT INTO `on_product` VALUES (57, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500);
INSERT INTO `on_product` VALUES (58, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1);
INSERT INTO `on_product` VALUES (59, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10);
INSERT INTO `on_product` VALUES (60, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10);
INSERT INTO `on_product` VALUES (61, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10);
INSERT INTO `on_product` VALUES (62, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10);
INSERT INTO `on_product` VALUES (63, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10);
INSERT INTO `on_product` VALUES (64, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10);
INSERT INTO `on_product` VALUES (65, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (66, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (67, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10);
INSERT INTO `on_product` VALUES (68, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10);
INSERT INTO `on_product` VALUES (69, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10);
INSERT INTO `on_product` VALUES (70, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10);
INSERT INTO `on_product` VALUES (71, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10);
INSERT INTO `on_product` VALUES (72, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10);
INSERT INTO `on_product` VALUES (73, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10);
INSERT INTO `on_product` VALUES (74, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10);
INSERT INTO `on_product` VALUES (75, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (76, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (77, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10);
INSERT INTO `on_product` VALUES (78, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10);
INSERT INTO `on_product` VALUES (79, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10);
INSERT INTO `on_product` VALUES (80, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10);
INSERT INTO `on_product` VALUES (81, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10);
INSERT INTO `on_product` VALUES (82, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10);
INSERT INTO `on_product` VALUES (83, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10);
INSERT INTO `on_product` VALUES (84, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10);
INSERT INTO `on_product` VALUES (85, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (86, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10);
INSERT INTO `on_product` VALUES (87, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10);
INSERT INTO `on_product` VALUES (88, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10);

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
INSERT INTO `order` VALUES (1, 18, 18, 2, '2017-12-04 23:46:54', '2017-12-06 12:01:20', '2017-12-07 17:01:20', 7230, NULL);
INSERT INTO `order` VALUES (2, 19, 19, 2, '2017-12-05 11:40:12', '2017-12-07 09:03:25', '2017-12-08 17:12:20', 597, NULL);
INSERT INTO `order` VALUES (3, 19, 19, 2, '2017-12-05 11:40:12', '2017-12-07 01:12:38', '2017-12-08 13:17:32', 380, NULL);
INSERT INTO `order` VALUES (4, 19, 19, 2, '2017-11-30 13:31:19', '2017-12-01 08:27:38', '2017-12-02 17:17:37', 6990, NULL);
INSERT INTO `order` VALUES (30, 18, 18, 2, '2017-12-04 23:48:32', '2017-11-28 12:01:20', '2017-11-28 17:01:20', 1800, NULL);

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
INSERT INTO `order_product` VALUES (1, 2, 1);
INSERT INTO `order_product` VALUES (2, 3, 3);
INSERT INTO `order_product` VALUES (3, 10, 2);
INSERT INTO `order_product` VALUES (4, 8, 1);
INSERT INTO `order_product` VALUES (30, 1, 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 92 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_picture
-- ----------------------------
INSERT INTO `product_picture` VALUES (1, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (2, 'images/2.jpg', 2);
INSERT INTO `product_picture` VALUES (3, 'images/3.jpg', 3);
INSERT INTO `product_picture` VALUES (4, 'images/4.jpg', 4);
INSERT INTO `product_picture` VALUES (5, 'images/5.jpg', 5);
INSERT INTO `product_picture` VALUES (6, 'images/6.jpeg', 6);
INSERT INTO `product_picture` VALUES (7, 'images/7.jpeg', 7);
INSERT INTO `product_picture` VALUES (8, 'images/8.jpeg', 8);
INSERT INTO `product_picture` VALUES (9, 'images/9.jpeg', 9);
INSERT INTO `product_picture` VALUES (10, 'images/10.jpeg', 10);
INSERT INTO `product_picture` VALUES (11, 'images/X4mR56YlwCG7rnJwO2WF5hQL6jP78vryxfBUnwnd.gif', 23);
INSERT INTO `product_picture` VALUES (16, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (17, 'images/2.jpg', 60);
INSERT INTO `product_picture` VALUES (18, 'images/3.jpg', 61);
INSERT INTO `product_picture` VALUES (19, 'images/4.jpg', 62);
INSERT INTO `product_picture` VALUES (20, 'images/5.jpg', 63);
INSERT INTO `product_picture` VALUES (21, 'images/6.jpeg', 64);
INSERT INTO `product_picture` VALUES (22, 'images/7.jpeg', 65);
INSERT INTO `product_picture` VALUES (23, 'images/8.jpeg', 66);
INSERT INTO `product_picture` VALUES (24, 'images/9.jpeg', 67);
INSERT INTO `product_picture` VALUES (25, 'images/10.jpeg', 68);
INSERT INTO `product_picture` VALUES (26, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (27, 'images/2.jpg', 70);
INSERT INTO `product_picture` VALUES (28, 'images/3.jpg', 71);
INSERT INTO `product_picture` VALUES (29, 'images/4.jpg', 72);
INSERT INTO `product_picture` VALUES (30, 'images/5.jpg', 73);
INSERT INTO `product_picture` VALUES (31, 'images/6.jpeg', 74);
INSERT INTO `product_picture` VALUES (32, 'images/7.jpeg', 75);
INSERT INTO `product_picture` VALUES (33, 'images/8.jpeg', 76);
INSERT INTO `product_picture` VALUES (34, 'images/9.jpeg', 77);
INSERT INTO `product_picture` VALUES (35, 'images/10.jpeg', 78);
INSERT INTO `product_picture` VALUES (36, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (37, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (38, 'images/2.jpg', 80);
INSERT INTO `product_picture` VALUES (39, 'images/2.jpg', 80);
INSERT INTO `product_picture` VALUES (40, 'images/3.jpg', 81);
INSERT INTO `product_picture` VALUES (41, 'images/3.jpg', 81);
INSERT INTO `product_picture` VALUES (42, 'images/4.jpg', 82);
INSERT INTO `product_picture` VALUES (43, 'images/4.jpg', 82);
INSERT INTO `product_picture` VALUES (44, 'images/5.jpg', 83);
INSERT INTO `product_picture` VALUES (45, 'images/5.jpg', 83);
INSERT INTO `product_picture` VALUES (46, 'images/6.jpeg', 84);
INSERT INTO `product_picture` VALUES (47, 'images/6.jpeg', 84);
INSERT INTO `product_picture` VALUES (48, 'images/7.jpeg', 85);
INSERT INTO `product_picture` VALUES (49, 'images/7.jpeg', 85);
INSERT INTO `product_picture` VALUES (50, 'images/8.jpeg', 86);
INSERT INTO `product_picture` VALUES (51, 'images/8.jpeg', 86);
INSERT INTO `product_picture` VALUES (52, 'images/9.jpeg', 87);
INSERT INTO `product_picture` VALUES (53, 'images/9.jpeg', 87);
INSERT INTO `product_picture` VALUES (54, 'images/10.jpeg', 88);
INSERT INTO `product_picture` VALUES (55, 'images/10.jpeg', 88);
INSERT INTO `product_picture` VALUES (56, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (57, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (58, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (59, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (60, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (61, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (62, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (63, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (64, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (65, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (66, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (67, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (68, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (69, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (70, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (71, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (72, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (73, 'images/1.jpg', 1);
INSERT INTO `product_picture` VALUES (74, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (75, 'images/1.jpg', 59);
INSERT INTO `product_picture` VALUES (76, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (77, 'images/1.jpg', 69);
INSERT INTO `product_picture` VALUES (78, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (79, 'images/1.jpg', 79);
INSERT INTO `product_picture` VALUES (80, 'images/2.jpg', 2);
INSERT INTO `product_picture` VALUES (81, 'images/2.jpg', 60);
INSERT INTO `product_picture` VALUES (82, 'images/2.jpg', 70);
INSERT INTO `product_picture` VALUES (83, 'images/2.jpg', 80);
INSERT INTO `product_picture` VALUES (84, 'images/2.jpg', 2);
INSERT INTO `product_picture` VALUES (85, 'images/2.jpg', 60);
INSERT INTO `product_picture` VALUES (86, 'images/2.jpg', 70);
INSERT INTO `product_picture` VALUES (87, 'images/2.jpg', 80);
INSERT INTO `product_picture` VALUES (88, 'images/2.jpg', 2);
INSERT INTO `product_picture` VALUES (89, 'images/2.jpg', 60);
INSERT INTO `product_picture` VALUES (90, 'images/2.jpg', 70);
INSERT INTO `product_picture` VALUES (91, 'images/2.jpg', 80);

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
  `avatar` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Imgur ID',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'admin', '$2y$10$kTdU0L2e.in.Efe35fCKausr/YUihzTNr0.ZlEyG8iAeTQladEOHG', 'seeder', 'A', '管理員', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, '');
INSERT INTO `user` VALUES (13, 'acco001', '$2y$10$BhMIDW9in1Ql7ML5e1sy0O97lBH7KtdIpNglU02pruYew76kAFrJ2', 'RRR', 'A', '管理員', '2017-11-14 20:04:26', '2014-09-18', '男', '122132', 0, '');
INSERT INTO `user` VALUES (14, 'acco002', '$2y$10$.7qG9G0WIGnoN.aABdK4P.NLP37mLWQHTIu8yavD53MjmTH.KT6aG', '21', 'C', '12', '2017-11-15 14:56:38', '2014-09-18', '男', '21', 0, '');
INSERT INTO `user` VALUES (15, 'b', '$2y$10$HHAJWr53LOGkb9iit1OWuOw7yde44lvrSxp7vSo/jj8BCd7A0XEhu', 'seeder', 'B', '商人', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, '');
INSERT INTO `user` VALUES (17, 'c', '$2y$10$ZNos9v1FpV.wrj/TkQKKwOzqWUFPox6xgkZEp0fTCwKCeOPP.nLdC', 'seeder', 'C', '客人', '2017-11-28 22:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, '');
INSERT INTO `user` VALUES (18, 'ff1298tw', '$2a$10$RSFV7jN4Ps3E/05BwL2QrOwJq.N3nisQOuKX66so3yw7gizWnKU0e', 'F198765123', 'C', '邱崇瑋', '2017-12-04 18:12:00', '1996-09-12', '男', 'ff1298tw@gmail.com', 1, 'a6KOLVn');
INSERT INTO `user` VALUES (19, 'JK5566', '$2a$10$oX7vtTD0S30oZIItyOyu/u0VQFrK8EOMX4xnKzK.cgMK98Ew4cqqu', 'B196116047', 'C', '吳三寶', '2017-12-04 18:25:00', '1990-03-21', '男', 'JK586610@gmail.com', 1, 'pEzMUpP');
INSERT INTO `user` VALUES (20, 'RT_Mart', '$2a$10$nRFD9MEX.Mlwq27OIG.n2OLnStCttWnSNvnmslJNkpQ1yBisezUJa', '12485671', 'B', '大潤發', '2017-12-04 18:45:00', '1977-06-09', '男女', 'RT_Mart12485671@gmail.com', 1, 'WsWJa6i');
INSERT INTO `user` VALUES (21, 'Carrefour', '$2a$10$Upeme3C6WW0CP.9Syu4Xf./OlWuRcYWza5XGv5CZbIY7U8FbSTKjq', '48716610', 'B', '家樂福', '2017-12-04 19:00:00', '1978-12-20', '男女', 'Carrefour48716610@gmail.com', 1, 'QCdBbri');
INSERT INTO `user` VALUES (23, 'GGBB6587', '$2a$10$h4yK8HCL8LM6Zdm5EZSqXO0OD3FQfS1XaehAEOszfOoeoSZewzTZu', 'C218001633', 'A', '彭怡如', '2017-12-04 19:10:00', '1991-01-11', '女', 'GGBB7788@gmail.com', 1, 'hKb0RdZ');
INSERT INTO `user` VALUES (24, 'asdf', '$2y$10$yjqP3hZAJhjM57dMi.jCJe/3GOUWert50mjbk6aL5X/4kGfgMyGvG', 'A123456780', 'C', 'Sd-f,A', '2017-12-05 14:25:13', '2034-12-31', '女', 'asdf@gmail.com', 0, '');
INSERT INTO `user` VALUES (25, 'd', '$2y$10$9slHAPEdqDWFfLwS1iav3.rHw92R6E.p89boG9LTVrv4VzhoFaV9.', 'd', 'B', 'd', '2017-12-05 16:45:52', '2017-12-05', '男', 'd@d', 0, '');

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

-- ----------------------------
-- Function structure for GetDiffUserBuyProduct
-- ----------------------------
DROP FUNCTION IF EXISTS `GetDiffUserBuyProduct`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `GetDiffUserBuyProduct`(`pid` int) RETURNS int(11)
BEGIN
	DECLARE c INT DEFAULT 0;
	SELECT Count(*) INTO c From order_product as od INNER JOIN `Order` as o ON o.id = od.order_id
Where od.product_id = pid Group By o.customer_id;
	RETURN c;
END
;;
delimiter ;

-- ----------------------------
-- Function structure for GetSellCount
-- ----------------------------
DROP FUNCTION IF EXISTS `GetSellCount`;
delimiter ;;
CREATE DEFINER=`root`@`%` FUNCTION `GetSellCount`(`pid` int) RETURNS int(11)
BEGIN
		DECLARE c INT DEFAULT 0;
		SELECT Count(*) INTO c From order_product as od Where od.product_id = pid;
		RETURN c;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
