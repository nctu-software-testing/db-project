ALTER TABLE `any_buy`.`user`
ADD COLUMN `avatar` varchar(12) NOT NULL DEFAULT '' COMMENT 'Imgur ID' AFTER `enable`;