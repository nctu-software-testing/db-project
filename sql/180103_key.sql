ALTER TABLE `user` ADD COLUMN `private_key` text NOT NULL AFTER `avatar`,ADD COLUMN `public_key` text NOT NULL AFTER `private_key`;
