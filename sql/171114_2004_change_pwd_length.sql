ALTER TABLE `user` 
MODIFY COLUMN `password` varchar(60) 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
NOT NULL AFTER `account`;