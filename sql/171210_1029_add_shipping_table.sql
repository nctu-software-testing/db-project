CREATE TABLE `shipping`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lower_bound` int(11) NOT NULL COMMENT '總價下限',
  `upper_bound` int(11) NOT NULL COMMENT '總價上限',
  `price` int(11) NOT NULL COMMENT '運費',
  PRIMARY KEY (`id`),
  INDEX `ship_bound_idx`(`lower_bound`, `upper_bound`)
);