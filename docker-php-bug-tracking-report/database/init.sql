CREATE DATABASE IF NOT EXISTS `bug-report-testing`;
USE `bug-report-testing`;
GRANT ALL PRIVILEGES ON `bug-report-testing`.* TO 'appuser'@'%';

CREATE TABLE `reports` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `report_type` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
   `message` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
   `link` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
   `email` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
   `created_at` TIMESTAMP NULL DEFAULT NULL,
   PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;