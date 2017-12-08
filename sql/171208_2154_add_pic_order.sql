ALTER TABLE `product_picture` 
ADD COLUMN `sort` int(11) NOT NULL DEFAULT 0 AFTER `product_id`;