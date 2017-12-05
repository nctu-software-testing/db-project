ALTER TABLE `order` 
ADD COLUMN `discount_id` int NULL DEFAULT NULL AFTER `final_cost`,
ADD CONSTRAINT `Order_Discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`id`);