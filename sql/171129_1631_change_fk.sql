ALTER TABLE `product_picture` DROP FOREIGN KEY `Pic_Pro`;

ALTER TABLE `product_picture` 
ADD CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `on_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;