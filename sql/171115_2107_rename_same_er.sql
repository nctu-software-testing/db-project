ALTER TABLE `on_product` 
CHANGE COLUMN `title` `product_name` varchar(255) 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
NOT NULL AFTER `id`;