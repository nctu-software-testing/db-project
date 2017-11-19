ALTER TABLE `discount` 
ADD COLUMN `name` varchar(100) NOT NULL AFTER `id`,
ADD COLUMN `description` varchar(255) NOT NULL DEFAULT '' AFTER `name`;