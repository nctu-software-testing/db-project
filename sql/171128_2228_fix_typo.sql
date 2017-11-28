ALTER TABLE `any_buy`.`user` 
CHANGE COLUMN `sign_up_datatime` `sign_up_datetime` timestamp(0) NULL DEFAULT current_timestamp() AFTER `name`;