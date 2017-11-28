ALTER TABLE `any_buy`.`on_product` 
CHANGE COLUMN `expiration_date` `start_date` datetime(0) NULL DEFAULT NULL AFTER `product_information`,
ADD COLUMN `amount` int(0) NOT NULL DEFAULT 1 AFTER `user_id`;