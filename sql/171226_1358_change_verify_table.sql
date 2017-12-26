ALTER TABLE `verification` 
MODIFY COLUMN `upload_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `back_picture`,
MODIFY COLUMN `verify_result` enum('未驗證','驗證成功','驗證失敗') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '未驗證' AFTER `upload_datetime`;