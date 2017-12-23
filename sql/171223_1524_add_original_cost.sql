ALTER TABLE `order` ADD `original_cost` INT NOT NULL DEFAULT '0' COMMENT '折扣前的原始價錢' AFTER `arrival_time`;
