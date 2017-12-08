-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2017 at 02:14 PM
-- Server version: 10.2.11-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `any_buy`
--

DELIMITER $$
--
-- Functions
--
CREATE FUNCTION `GetDiffUserBuyProduct` (`pid` INT) RETURNS INT(11) BEGIN
	DECLARE c INT DEFAULT 0;
	SELECT Count(DISTINCT o.customer_id) INTO c From order_product as od INNER JOIN `Order` as o ON o.id = od.order_id
Where od.product_id = pid;
	RETURN c;
END$$

CREATE FUNCTION `GetSellCount` (`pid` INT) RETURNS INT(11) BEGIN
		DECLARE c INT DEFAULT 0;
		SELECT Count(*) INTO c From order_product as od Where od.product_id = pid;
		RETURN c;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `product_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_index` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `category`
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
-- Table structure for table `catlog`
--

CREATE TABLE `catlog` (
  `id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_discount_time` datetime DEFAULT NULL,
  `end_discount_time` datetime DEFAULT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percent` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `name`, `start_discount_time`, `end_discount_time`, `type`, `discount_percent`) VALUES
(1, 'asdf', '2017-11-07 18:57:23', '2018-01-26 18:57:29', 'C', 0.10);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `user_id`, `address`, `zip_code`) VALUES
(18, 18, '新北市永和區保平路18巷1號', '234'),
(19, 19, '台中市西區樂群街38號', '403'),
(20, 1, '北科大', '106');

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `on_product`
--

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
-- Dumping data for table `on_product`
--

INSERT INTO `on_product` (`id`, `product_name`, `product_information`, `start_date`, `end_date`, `price`, `state`, `category_id`, `user_id`, `amount`) VALUES
(1, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W\r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 0, 10, 1, 10),
(2, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 0, 9, 1, 10),
(3, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10),
(4, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10),
(5, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10),
(6, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10),
(7, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10),
(8, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10),
(9, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10),
(10, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10),
(22, 'asd', 'asdasf\r\nasg\r\nas\r\ngas\r\ng\r\n[b]asg[/b]\r\ngfh[b]dfghd[/b]fgh', '2017-10-10 10:38:31', '2017-12-09 10:38:31', 50, 1, 3, 1, 1),
(23, 'test', '3435345er\r\n\r\ndfg\r\ndg\r\ndfg\r\ndfg', '2017-11-09 01:01:00', '2018-02-03 12:56:00', 1235, 0, 3, 15, 1),
(24, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(34, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(35, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(36, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500),
(37, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500),
(38, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500),
(39, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500),
(40, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500),
(41, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500),
(42, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(43, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(44, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500),
(45, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500),
(46, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500),
(47, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500),
(48, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500),
(49, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500),
(50, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(51, '1', 'dfg', '2017-11-28 00:00:00', '2030-01-01 01:01:00', 43666, 0, 3, 1, 1),
(52, '蝴蝶～蝴蝶～', '蝴蝶2', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 20, 0, 4, 1, 500),
(53, '生的真美麗', '生的真美麗3', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 30, 0, 5, 1, 500),
(54, '頭戴著金絲，身穿花花衣', '頭戴著金絲4', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 40, 0, 3, 1, 500),
(55, '你愛花兒，花兒也愛你，', '身穿花花衣5', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 50, 0, 4, 1, 500),
(56, '你會跳舞', '你愛花兒6', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 60, 0, 5, 1, 500),
(57, '花兒有甜蜜～', '花兒也愛你7', '2017-01-01 00:00:00', '2019-12-31 00:00:00', 70, 0, 3, 1, 500),
(58, '1', 'gjhfghjfghj', '2017-11-17 01:01:00', '2017-12-02 02:02:00', 2, 0, 3, 15, 1),
(59, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10),
(60, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10),
(61, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10),
(62, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10),
(63, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10),
(64, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10),
(65, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10),
(66, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10),
(67, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10),
(68, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10),
(69, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10),
(70, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10),
(71, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10),
(72, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10),
(73, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10),
(74, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10),
(75, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10),
(76, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10),
(77, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10),
(78, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10),
(79, '【電視】歌林32型液晶顯示器_KLT-32ED03', 'IPS面板 / 1366*768 / 60HZ / 8.5 ms\r\n直下式/250cd/m2 / 對比:3000:1 / 台灣\r\nHDMI*3,色差端子*1,AV端子*1,USB*1,VGA端子*1，PC Audio in*1輸入\r\n耳機輸出*1，數位同軸聲音輸出Coaxial*1/ 8W+8W \r\n商品尺寸 (寬 x 高 x 深): 734 * 487 * 175mm (含底坐)\r\n保固3年/內附遙控器乙支\r\n可視角度：176度\r\n安心護眼模式\r\n數位電視錄影(須格式化USB隨身碟)', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 7230, 1, 10, 20, 10),
(80, 'Victoria防蹣抗菌羊毛被', '尺寸:180*210cm(6*7尺)\r\n表布:100%棉(添加加拿大Ultra Fresh防蹣劑)\r\n填充物:100%羊毛\r\n1.抗菌抑蹣\r\n.吸濕性強，不易產生靜電，灰塵污垢不易沾附，再將水蒸氣釋放到外部前，先將水分吸收到自身的纖維中，降低塵蹣的週期。\r\n2.吸濕排汗\r\n羊毛可吸收自身重量35%的水蒸氣而無潮濕感，而人體在睡眠時會排出大量的水分，\r\n羊毛獨特的分子結構可將水蒸氣西進多空結構中，並迅速排出體外。\r\n3.溫度調節\r\n冷時保持溫暖，熱時透氣乾爽，貼近皮膚最佳，溫度32.7度，和人體最佳表面最佳睡眠溫度33度極為相似。\r\n4.保暖力強\r\n羊毛的自然捲曲特性，製造出空間蓬', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1800, 1, 9, 20, 10),
(81, '566護髮染髮霜補充盒5號-自然深栗', '規格:(男女適用) 40g*2\r\n產地:台灣\r\n單位:1/盒\r\n\r\n\r\n商品說明：\r\n\r\n全新升級配方，不含PPD，上色自然，覆蓋完美，染髮同時護髮，染時味道溫和芬芳，男女適用，補染快速方便。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 199, 1, 5, 20, 10),
(82, 'ㄋㄟㄋㄟ補給站牛奶餅400g/盒', '淨重：405g/盒\r\n成分：麵粉、糖、奶油、果糖、食鹽、蛋、膨脹劑、碳酸鈣、維生素B1.B2、檸檬酸。\r\n保存期限：12個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 119, 1, 17, 20, 10),
(83, 'in女連帽羽絨外套 深藍#XL', 'in女連帽羽絨外套\r\n表布:尼龍100%\r\n裡布:尼龍100%\r\n填充物:羽絨90%、羽毛10%\r\n規格:W17-18601\r\n產地:中國大陸\r\n單位:1/件', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 1490, 1, 3, 20, 10),
(84, 'Men s Spirit織帶風格平口褲 XL (顏色隨機出貨)', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:中國大陸', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 69, 1, 4, 21, 10),
(85, '華碩 ZEN PAD Z170CX-1B005A', '產品規格\r\n\r\n處理器:Intel Atom x3-C3200 Quad Core\r\n作業系統:Android Lollipop 5.0\r\n附贈軟體: -\r\n顯示晶片: -\r\n網路介面:10/100/1000\r\n光碟機: -\r\n無線裝置:802.11b/g/n,Bluetooth V4.0 支援Miracast無限分享\r\n硬碟:eMCP 8GB + 1年1TB免費網路空間\r\n藍芽:Micro USB\r\n螢幕尺寸:7吋IPS\r\n輸入裝置:變壓器\r\n解析度:1024*600\r\n原廠保固:一年本地保固', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 3390, 1, 6, 21, 10),
(86, '歐風三輪式嬰兒推車689-買就送防風雨罩紅/藍二色.隨機出貨', '容量:1.00 PC件\r\n免稅:應稅\r\n保存溫層:常溫\r\n商品來源:台灣\r\n\r\n商品完整說明\r\n\r\n※ 製造日期與有效期限，商品成分與適用注意事項皆標示於包裝或產品中\r\n※ 本產品網頁因拍攝關係，圖檔略有差異，實際以廠商出貨為主\r\n※ 本產品文案若有變動敬請參照實際商品為準', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 6990, 1, 6, 21, 10),
(87, '東元 R2551HS 雙門冰箱-239 L公升', '尺寸(W*H*D): 54.5*149*63.8 CM \r\n淨內容量: 239 公升 \r\n消耗功率: 120W \r\n產地:泰國 \r\n顏色:晶鑽灰 \r\n低溫冷媒，活效濾網 \r\n多重輻射立體冷流 \r\n防霉抗茵磁條 \r\n扭轉式製冰盒\r\n\r\n主機保固(月數)  12 個月\r\n主要零件保固(月數)  36 個月', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 11900, 1, 8, 21, 10),
(88, '御茶園特上紅茶250ml/24入', '日式極致焙香技術，釋放紅茶極緻香氣與濃厚茶味。 \r\n\r\n添加日本頂級和三盆糖，用於日本高級和菓子的和三盆糖更能引出紅茶清甜，讓紅茶香甜不膩。', '2017-12-04 00:00:00', '2018-01-04 00:00:00', 190, 1, 17, 21, 10);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

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
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `location_id`, `customer_id`, `state`, `order_time`, `sent_time`, `arrival_time`, `final_cost`, `discount_id`) VALUES
(1, 18, 18, 2, '2017-12-04 15:46:54', '2017-12-06 04:01:20', '2017-12-07 09:01:20', 7230, NULL),
(2, 19, 19, 2, '2017-12-05 03:40:12', '2017-12-07 01:03:25', '2017-12-08 09:12:20', 597, NULL),
(3, 19, 19, 2, '2017-12-05 03:40:12', '2017-12-06 17:12:38', '2017-12-08 05:17:32', 380, NULL),
(4, 19, 19, 2, '2017-11-30 05:31:19', '2017-12-01 00:27:38', '2017-12-02 09:17:37', 6990, NULL),
(30, 18, 18, 2, '2017-12-04 15:48:32', '2017-11-28 04:01:20', '2017-11-28 09:01:20', 1800, NULL),
(31, 20, 1, 0, '2017-12-05 10:13:02', '2017-12-05 11:13:02', '2017-12-05 16:13:02', 23430, NULL),
(32, 20, 1, 0, '2017-12-06 07:04:41', '2017-12-06 08:04:41', '2017-12-06 13:04:41', 14460, NULL),
(33, 20, 1, 0, '2017-12-08 07:36:39', '2017-12-08 08:36:39', '2017-12-08 13:36:39', 16390, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `order_product`
--

INSERT INTO `order_product` (`order_id`, `product_id`, `amount`) VALUES
(1, 2, 1),
(2, 3, 3),
(3, 10, 2),
(4, 8, 1),
(30, 1, 1),
(31, 1, 1),
(31, 2, 9),
(32, 1, 2),
(33, 5, 11);

-- --------------------------------------------------------

--
-- Table structure for table `private_message`
--

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
-- Table structure for table `product_picture`
--

CREATE TABLE `product_picture` (
  `id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `product_picture`
--

INSERT INTO `product_picture` (`id`, `path`, `product_id`, `sort`) VALUES
(1, 'images/1.jpg', 1, 0),
(2, 'images/2.jpg', 2, 0),
(3, 'images/3.jpg', 3, 0),
(4, 'images/4.jpg', 4, 0),
(5, 'images/5.jpg', 5, 0),
(6, 'images/6.jpeg', 6, 0),
(7, 'images/7.jpeg', 7, 0),
(8, 'images/8.jpeg', 8, 0),
(9, 'images/9.jpeg', 9, 0),
(10, 'images/10.jpeg', 10, 0),
(11, 'images/X4mR56YlwCG7rnJwO2WF5hQL6jP78vryxfBUnwnd.gif', 23, 0),
(16, 'images/1.jpg', 59, 0),
(17, 'images/2.jpg', 60, 0),
(18, 'images/3.jpg', 61, 0),
(19, 'images/4.jpg', 62, 0),
(20, 'images/5.jpg', 63, 0),
(21, 'images/6.jpeg', 64, 0),
(22, 'images/7.jpeg', 65, 0),
(23, 'images/8.jpeg', 66, 0),
(24, 'images/9.jpeg', 67, 0),
(25, 'images/10.jpeg', 68, 0),
(26, 'images/1.jpg', 69, 0),
(27, 'images/2.jpg', 70, 0),
(28, 'images/3.jpg', 71, 0),
(29, 'images/4.jpg', 72, 0),
(30, 'images/5.jpg', 73, 0),
(31, 'images/6.jpeg', 74, 0),
(32, 'images/7.jpeg', 75, 0),
(33, 'images/8.jpeg', 76, 0),
(34, 'images/9.jpeg', 77, 0),
(35, 'images/10.jpeg', 78, 0),
(36, 'images/1.jpg', 79, 0),
(37, 'images/1.jpg', 79, 1),
(38, 'images/2.jpg', 80, 0),
(39, 'images/2.jpg', 80, 1),
(40, 'images/3.jpg', 81, 0),
(41, 'images/3.jpg', 81, 1),
(42, 'images/4.jpg', 82, 0),
(43, 'images/4.jpg', 82, 1),
(44, 'images/5.jpg', 83, 0),
(45, 'images/5.jpg', 83, 1),
(46, 'images/6.jpeg', 84, 0),
(47, 'images/6.jpeg', 84, 1),
(48, 'images/7.jpeg', 85, 0),
(49, 'images/7.jpeg', 85, 1),
(50, 'images/8.jpeg', 86, 0),
(51, 'images/8.jpeg', 86, 1),
(52, 'images/9.jpeg', 87, 0),
(53, 'images/9.jpeg', 87, 1),
(54, 'images/10.jpeg', 88, 0),
(55, 'images/10.jpeg', 88, 1),
(56, 'images/1.jpg', 1, 1),
(57, 'images/1.jpg', 1, 2),
(58, 'images/1.jpg', 59, 1),
(59, 'images/1.jpg', 59, 2),
(60, 'images/1.jpg', 69, 1),
(61, 'images/1.jpg', 69, 2),
(62, 'images/1.jpg', 79, 2),
(63, 'images/1.jpg', 79, 3),
(64, 'images/1.jpg', 1, 3),
(65, 'images/1.jpg', 1, 4),
(66, 'images/1.jpg', 59, 3),
(67, 'images/1.jpg', 59, 4),
(68, 'images/1.jpg', 69, 3),
(69, 'images/1.jpg', 69, 4),
(70, 'images/1.jpg', 79, 4),
(71, 'images/1.jpg', 79, 5),
(72, 'images/1.jpg', 1, 5),
(73, 'images/1.jpg', 1, 6),
(74, 'images/1.jpg', 59, 5),
(75, 'images/1.jpg', 59, 6),
(76, 'images/1.jpg', 69, 5),
(77, 'images/1.jpg', 69, 6),
(78, 'images/1.jpg', 79, 6),
(79, 'images/1.jpg', 79, 7),
(80, 'images/2.jpg', 2, 1),
(81, 'images/2.jpg', 60, 1),
(82, 'images/2.jpg', 70, 1),
(83, 'images/2.jpg', 80, 2),
(84, 'images/2.jpg', 2, 2),
(85, 'images/2.jpg', 60, 2),
(86, 'images/2.jpg', 70, 2),
(87, 'images/2.jpg', 80, 3),
(88, 'images/2.jpg', 2, 3),
(89, 'images/2.jpg', 60, 3),
(90, 'images/2.jpg', 70, 3),
(91, 'images/2.jpg', 80, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign_up_datetime` timestamp NULL DEFAULT current_timestamp(),
  `birthday` date NOT NULL,
  `gender` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Imgur ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `sn`, `role`, `name`, `sign_up_datetime`, `birthday`, `gender`, `email`, `enable`, `avatar`) VALUES
(1, 'admin', '$2y$10$kTdU0L2e.in.Efe35fCKausr/YUihzTNr0.ZlEyG8iAeTQladEOHG', 'seeder', 'A', '管理員', '2017-11-28 14:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, ''),
(13, 'acco001', '$2y$10$BhMIDW9in1Ql7ML5e1sy0O97lBH7KtdIpNglU02pruYew76kAFrJ2', 'RRR', 'A', '管理員', '2017-11-14 12:04:26', '2014-09-18', '男', '122132', 0, ''),
(14, 'acco002', '$2y$10$.7qG9G0WIGnoN.aABdK4P.NLP37mLWQHTIu8yavD53MjmTH.KT6aG', '21', 'C', '12', '2017-11-15 06:56:38', '2014-09-18', '男', '21', 0, ''),
(15, 'b', '$2y$10$HHAJWr53LOGkb9iit1OWuOw7yde44lvrSxp7vSo/jj8BCd7A0XEhu', 'seeder', 'B', '商人', '2017-11-28 14:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, ''),
(17, 'c', '$2y$10$ZNos9v1FpV.wrj/TkQKKwOzqWUFPox6xgkZEp0fTCwKCeOPP.nLdC', 'seeder', 'C', '客人', '2017-11-28 14:16:00', '2000-01-01', '男', 'asdf@qwer.zxcv', 1, ''),
(18, 'ff1298tw', '$2a$10$RSFV7jN4Ps3E/05BwL2QrOwJq.N3nisQOuKX66so3yw7gizWnKU0e', 'F198765123', 'C', '邱崇瑋', '2017-12-04 10:12:00', '1996-09-12', '男', 'ff1298tw@gmail.com', 1, 'a6KOLVn'),
(19, 'JK5566', '$2a$10$oX7vtTD0S30oZIItyOyu/u0VQFrK8EOMX4xnKzK.cgMK98Ew4cqqu', 'B196116047', 'C', '吳三寶', '2017-12-04 10:25:00', '1990-03-21', '男', 'JK586610@gmail.com', 1, 'pEzMUpP'),
(20, 'RT_Mart', '$2a$10$nRFD9MEX.Mlwq27OIG.n2OLnStCttWnSNvnmslJNkpQ1yBisezUJa', '12485671', 'B', '大潤發', '2017-12-04 10:45:00', '1977-06-09', '男女', 'RT_Mart12485671@gmail.com', 1, 'WsWJa6i'),
(21, 'Carrefour', '$2a$10$Upeme3C6WW0CP.9Syu4Xf./OlWuRcYWza5XGv5CZbIY7U8FbSTKjq', '48716610', 'B', '家樂福', '2017-12-04 11:00:00', '1978-12-20', '男女', 'Carrefour48716610@gmail.com', 1, 'QCdBbri'),
(23, 'GGBB6587', '$2a$10$h4yK8HCL8LM6Zdm5EZSqXO0OD3FQfS1XaehAEOszfOoeoSZewzTZu', 'C218001633', 'A', '彭怡如', '2017-12-04 11:10:00', '1991-01-11', '女', 'GGBB7788@gmail.com', 1, 'hKb0RdZ'),
(24, 'asdf', '$2y$10$yjqP3hZAJhjM57dMi.jCJe/3GOUWert50mjbk6aL5X/4kGfgMyGvG', 'A123456780', 'C', 'Sd-f,A', '2017-12-05 06:25:13', '2034-12-31', '女', 'asdf@gmail.com', 0, ''),
(25, 'd', '$2y$10$9slHAPEdqDWFfLwS1iav3.rHw92R6E.p89boG9LTVrv4VzhoFaV9.', 'd', 'B', 'd', '2017-12-05 08:45:52', '2017-12-05', '男', 'd@d', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

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
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`id`, `user_id`, `front_picture`, `back_picture`, `upload_datetime`, `verify_result`, `datetime`, `description`) VALUES
(6, 15, 'images/WYfNpmn1K5rU3Rqobdk0i7t2SrUqJ0rWPpWjxwXS.png', 'images/qlLpJxcD14nMq5pDpIZCnml7rZIiCE8o6pGBspWj.png', '2017-11-27 09:51:39', '驗證失敗', '2017-11-27 09:51:40', 'asdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `catlog`
--
ALTER TABLE `catlog`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `CatLog_Discount` (`discount_id`) USING BTREE,
  ADD KEY `CatLog_Category` (`category_id`) USING BTREE;

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Location_User` (`user_id`) USING BTREE;

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `on_product`
--
ALTER TABLE `on_product`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Product_Category` (`category_id`) USING BTREE,
  ADD KEY `Product_Businessman` (`user_id`) USING BTREE;

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Order_Location` (`location_id`) USING BTREE,
  ADD KEY `Order_Customer` (`customer_id`) USING BTREE,
  ADD KEY `Order_Discount` (`discount_id`) USING BTREE;

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`order_id`,`product_id`) USING BTREE,
  ADD KEY `OP_Poduct` (`product_id`) USING BTREE;

--
-- Indexes for table `private_message`
--
ALTER TABLE `private_message`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `PM_User` (`receive_id`) USING BTREE;

--
-- Indexes for table `product_picture`
--
ALTER TABLE `product_picture`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Pic_Pro` (`product_id`) USING BTREE;

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `Tag_Product` (`product_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `account` (`account`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE;

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE,
  ADD KEY `Verification_User` (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `catlog`
--
ALTER TABLE `catlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `on_product`
--
ALTER TABLE `on_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `private_message`
--
ALTER TABLE `private_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_picture`
--
ALTER TABLE `product_picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `verification`
--
ALTER TABLE `verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catlog`
--
ALTER TABLE `catlog`
  ADD CONSTRAINT `CatLog_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `CatLog_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`);

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `Location_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `on_product`
--
ALTER TABLE `on_product`
  ADD CONSTRAINT `Product_Businessman` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `Product_Category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `Order_Customer` FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `Order_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`),
  ADD CONSTRAINT `Order_Location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `OP_Order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `OP_Poduct` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`);

--
-- Constraints for table `private_message`
--
ALTER TABLE `private_message`
  ADD CONSTRAINT `PM_User` FOREIGN KEY (`receive_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `product_picture`
--
ALTER TABLE `product_picture`
  ADD CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `Tag_Product` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`);

--
-- Constraints for table `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `Verification_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
