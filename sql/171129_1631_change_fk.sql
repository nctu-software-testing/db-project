ALTER TABLE `any_buy`.`product_picture` DROP FOREIGN KEY `Pic_Pro`;

ALTER TABLE `any_buy`.`product_picture` 
ADD CONSTRAINT `Pic_Pro` FOREIGN KEY (`product_id`) REFERENCES `any_buy`.`on_product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;