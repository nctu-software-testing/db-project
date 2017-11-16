ALTER TABLE `on_product` 
ADD COLUMN `user_id` int NOT NULL AFTER `category_id`,
ADD CONSTRAINT `Product_Businessman` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);