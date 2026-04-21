-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table pelabuhan_rr_system.assignments
CREATE TABLE IF NOT EXISTS `assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `passenger_queue_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `assigned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  `timeout_at` timestamp NULL DEFAULT NULL,
  `status` enum('assigned','completed','timeout') DEFAULT 'assigned',
  PRIMARY KEY (`id`),
  KEY `fk_assignment_queue` (`passenger_queue_id`),
  KEY `idx_assignment_status` (`status`),
  KEY `idx_assignment_driver` (`driver_id`),
  CONSTRAINT `fk_assignment_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  CONSTRAINT `fk_assignment_queue` FOREIGN KEY (`passenger_queue_id`) REFERENCES `passenger_queues` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.assignments: ~21 rows (approximately)
INSERT INTO `assignments` (`id`, `passenger_queue_id`, `driver_id`, `assigned_at`, `completed_at`, `timeout_at`, `status`) VALUES
	(35, 58, 1, '2026-04-17 07:45:23', '2026-04-16 23:46:11', NULL, 'completed'),
	(36, 59, 2, '2026-04-17 07:45:28', '2026-04-16 23:46:23', NULL, 'completed'),
	(37, 60, 3, '2026-04-17 07:45:34', '2026-04-17 01:06:41', NULL, 'completed'),
	(38, 61, 1, '2026-04-17 07:50:23', '2026-04-17 00:09:51', NULL, 'completed'),
	(39, 64, 6, '2026-04-17 08:12:27', '2026-04-17 00:14:00', NULL, 'completed'),
	(40, 67, 2, '2026-04-17 08:12:45', '2026-04-17 01:00:25', NULL, 'completed'),
	(41, 62, 4, '2026-04-17 08:13:32', '2026-04-17 01:17:57', NULL, 'completed'),
	(42, 65, 6, '2026-04-17 08:14:38', '2026-04-17 01:18:20', NULL, 'completed'),
	(43, 69, 1, '2026-04-17 01:05:50', '2026-04-17 01:06:30', NULL, 'completed'),
	(44, 70, 2, '2026-04-17 01:12:11', '2026-04-17 01:12:54', NULL, 'completed'),
	(45, 71, 3, '2026-04-17 01:12:16', '2026-04-17 01:13:22', NULL, 'completed'),
	(46, 72, 1, '2026-04-17 01:12:19', '2026-04-17 01:13:13', NULL, 'completed'),
	(47, 73, 2, '2026-04-17 01:14:16', '2026-04-17 01:19:05', NULL, 'completed'),
	(48, 74, 3, '2026-04-17 01:14:34', NULL, NULL, 'assigned'),
	(49, 75, 1, '2026-04-17 01:15:08', '2026-04-17 01:16:09', NULL, 'completed'),
	(50, 63, 5, '2026-04-17 01:17:30', '2026-04-17 01:17:44', NULL, 'completed'),
	(51, 68, 6, '2026-04-17 01:18:31', '2026-04-20 22:55:57', NULL, 'completed'),
	(52, 66, 4, '2026-04-17 01:18:41', '2026-04-20 22:56:24', NULL, 'completed'),
	(53, 76, 1, '2026-04-17 01:18:53', '2026-04-18 04:08:06', NULL, 'completed'),
	(54, 77, 2, '2026-04-17 01:19:59', '2026-04-18 05:37:29', NULL, 'completed'),
	(55, 78, 1, '2026-04-18 04:08:26', NULL, NULL, 'assigned'),
	(56, 79, 2, '2026-04-20 22:54:50', NULL, NULL, 'assigned'),
	(57, 80, 5, '2026-04-20 22:54:59', NULL, NULL, 'assigned'),
	(58, 82, 7, '2026-04-20 22:55:08', NULL, NULL, 'assigned'),
	(59, 81, 6, '2026-04-20 22:56:10', NULL, NULL, 'assigned');

-- Dumping structure for table pelabuhan_rr_system.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.cache: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.cache_locks: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.drivers
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `driver_code` varchar(20) NOT NULL,
  `vehicle_category_id` int NOT NULL,
  `plate_number` varchar(20) DEFAULT NULL,
  `photo_driver` varchar(255) DEFAULT NULL,
  `photo_vehicle` varchar(255) DEFAULT NULL,
  `status` enum('inactive','ready','on_trip','break','off_duty') NOT NULL DEFAULT 'inactive',
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `defer_once` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `driver_code` (`driver_code`),
  KEY `fk_driver_category` (`vehicle_category_id`),
  KEY `idx_driver_status` (`status`),
  CONSTRAINT `fk_driver_category` FOREIGN KEY (`vehicle_category_id`) REFERENCES `vehicle_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.drivers: ~10 rows (approximately)
INSERT INTO `drivers` (`id`, `name`, `driver_code`, `vehicle_category_id`, `plate_number`, `photo_driver`, `photo_vehicle`, `status`, `active`, `created_at`, `updated_at`, `defer_once`) VALUES
	(1, 'Wayan', 'DRV-M-001', 1, 'DK 1234 AA', NULL, NULL, 'on_trip', 1, '2026-04-15 08:48:57', '2026-04-18 04:08:26', 0),
	(2, 'Made', 'DRV-M-002', 1, 'DK 5678 BB', NULL, NULL, 'on_trip', 1, '2026-04-15 08:48:57', '2026-04-20 22:54:50', 0),
	(3, 'Nyoman', 'DRV-M-003', 1, 'DK 9012 CC', NULL, NULL, 'on_trip', 1, '2026-04-15 08:48:57', '2026-04-17 01:14:34', 0),
	(4, 'Ketut', 'DRV-C-001', 2, 'DK 2233 DD', NULL, NULL, 'ready', 1, '2026-04-15 08:49:32', '2026-04-20 22:56:24', 0),
	(5, 'Komang', 'DRV-C-002', 2, 'DK 4455 EE', NULL, NULL, 'on_trip', 1, '2026-04-15 08:49:32', '2026-04-20 22:54:59', 0),
	(6, 'Putra', 'DRV-B-001', 3, 'DK 7788 FF', NULL, NULL, 'on_trip', 1, '2026-04-15 08:50:08', '2026-04-20 22:56:10', 0),
	(7, 'Budi Santoso', 'DRV-009', 1, 'DK 1234 AB', NULL, NULL, 'on_trip', 1, '2026-04-20 20:36:33', '2026-04-20 22:55:08', 0),
	(8, 'I Wayan Astika Putra', 'DRV-001', 1, 'DK 1234 AAB', NULL, NULL, 'inactive', 1, '2026-04-20 21:00:26', '2026-04-20 21:00:26', 0),
	(9, 'sadsad', 'drv-100', 3, 'DK 12345', NULL, NULL, 'inactive', 1, '2026-04-20 21:43:35', '2026-04-20 21:43:35', 0),
	(10, 'test 1234', 'test-123', 1, 'DK 1234 AAB', NULL, NULL, 'inactive', 0, '2026-04-20 21:44:22', '2026-04-20 21:44:50', 0);

-- Dumping structure for table pelabuhan_rr_system.driver_cards
CREATE TABLE IF NOT EXISTS `driver_cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int NOT NULL,
  `card_uid` varchar(100) NOT NULL,
  `card_type` enum('RFID','BARCODE') NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_uid` (`card_uid`),
  KEY `fk_card_driver` (`driver_id`),
  CONSTRAINT `fk_card_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.driver_cards: ~8 rows (approximately)
INSERT INTO `driver_cards` (`id`, `driver_id`, `card_uid`, `card_type`, `active`, `created_at`) VALUES
	(1, 1, 'RFID-M-001', 'RFID', 1, '2026-04-15 08:49:16'),
	(2, 2, 'RFID-M-002', 'RFID', 1, '2026-04-15 08:49:16'),
	(3, 3, 'RFID-M-003', 'RFID', 0, '2026-04-15 08:49:16'),
	(4, 4, 'RFID-C-001', 'RFID', 1, '2026-04-15 08:49:51'),
	(5, 5, 'RFID-C-002', 'RFID', 1, '2026-04-15 08:49:51'),
	(6, 6, 'RFID-B-001', 'RFID', 1, '2026-04-15 08:50:20'),
	(8, 7, 'RFID-M-1000', 'RFID', 1, '2026-04-21 06:30:50'),
	(9, 7, 'RFID-M-10001', 'RFID', 1, '2026-04-21 06:31:00');

-- Dumping structure for table pelabuhan_rr_system.driver_logs
CREATE TABLE IF NOT EXISTS `driver_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int NOT NULL,
  `action` enum('tap_in','assigned','completed','break','off_duty','timeout','defer_once','defer_once_used') NOT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_log_driver` (`driver_id`),
  CONSTRAINT `fk_log_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.driver_logs: ~62 rows (approximately)
INSERT INTO `driver_logs` (`id`, `driver_id`, `action`, `log_time`, `notes`) VALUES
	(98, 1, 'tap_in', '2026-04-17 07:45:23', 'Driver tap kartu (check-in)'),
	(99, 1, 'assigned', '2026-04-17 07:45:23', 'Assigned queue A-001'),
	(100, 2, 'tap_in', '2026-04-17 07:45:28', 'Driver tap kartu (check-in)'),
	(101, 2, 'assigned', '2026-04-17 07:45:28', 'Assigned queue A-002'),
	(102, 3, 'tap_in', '2026-04-17 07:45:34', 'Driver tap kartu (check-in)'),
	(103, 3, 'assigned', '2026-04-17 07:45:34', 'Assigned queue A-003'),
	(104, 1, 'completed', '2026-04-17 07:46:11', 'Driver selesai antar penumpang'),
	(105, 2, 'completed', '2026-04-17 07:46:23', 'Driver selesai antar penumpang'),
	(106, 1, 'tap_in', '2026-04-17 07:50:23', 'Driver tap kartu (check-in)'),
	(107, 1, 'assigned', '2026-04-17 07:50:23', 'Assigned queue A-004'),
	(108, 1, 'completed', '2026-04-17 08:09:51', 'Driver selesai antar penumpang'),
	(109, 2, 'tap_in', '2026-04-17 08:10:14', 'Driver tap kartu (check-in)'),
	(110, 2, 'tap_in', '2026-04-17 08:10:33', 'Driver tap kartu (check-in)'),
	(111, 2, 'tap_in', '2026-04-17 08:11:18', 'Driver tap kartu (check-in)'),
	(112, 2, 'tap_in', '2026-04-17 08:12:02', 'Driver tap kartu (check-in)'),
	(113, 6, 'assigned', '2026-04-17 08:12:27', 'Assigned queue C-001'),
	(114, 2, 'assigned', '2026-04-17 08:12:45', 'Assigned queue A-005'),
	(115, 4, 'tap_in', '2026-04-17 08:13:32', 'Driver tap kartu (check-in)'),
	(116, 4, 'assigned', '2026-04-17 08:13:32', 'Assigned queue B-001'),
	(117, 6, 'completed', '2026-04-17 08:14:00', 'Driver selesai antar penumpang'),
	(118, 6, 'assigned', '2026-04-17 08:14:38', 'Assigned queue C-002'),
	(119, 2, 'completed', '2026-04-17 09:00:25', 'Driver selesai antar penumpang'),
	(120, 2, 'tap_in', '2026-04-17 09:00:51', 'Driver tap kartu (check-in)'),
	(121, 2, 'tap_in', '2026-04-17 09:03:13', 'Driver tap kartu (check-in)'),
	(122, 2, 'defer_once', '2026-04-17 09:05:06', 'Driver menunda giliran satu kali'),
	(123, 1, 'assigned', '2026-04-17 09:05:50', 'Assigned queue A-006'),
	(124, 1, 'completed', '2026-04-17 09:06:30', 'Driver selesai antar penumpang'),
	(125, 2, 'tap_in', '2026-04-17 09:06:36', 'Driver tap kartu (check-in)'),
	(126, 3, 'completed', '2026-04-17 09:06:41', 'Driver selesai antar penumpang'),
	(127, 3, 'defer_once', '2026-04-17 09:06:50', 'Driver menunda giliran satu kali'),
	(128, 2, 'tap_in', '2026-04-17 09:10:20', 'Driver tap kartu (check-in)'),
	(129, 2, 'defer_once', '2026-04-17 09:10:23', 'Driver menunda giliran satu kali'),
	(130, 2, 'assigned', '2026-04-17 09:12:11', 'Assigned queue A-007'),
	(131, 3, 'assigned', '2026-04-17 09:12:16', 'Assigned queue A-008'),
	(132, 1, 'assigned', '2026-04-17 09:12:19', 'Assigned queue A-009'),
	(133, 2, 'completed', '2026-04-17 09:12:54', 'Driver selesai antar penumpang'),
	(134, 1, 'completed', '2026-04-17 09:13:13', 'Driver selesai antar penumpang'),
	(135, 3, 'completed', '2026-04-17 09:13:22', 'Driver selesai antar penumpang'),
	(136, 3, 'defer_once', '2026-04-17 09:13:50', 'Driver menunda giliran satu kali'),
	(137, 2, 'assigned', '2026-04-17 09:14:16', 'Assigned queue A-010'),
	(138, 3, 'assigned', '2026-04-17 09:14:34', 'Assigned queue A-011'),
	(139, 1, 'assigned', '2026-04-17 09:15:08', 'Assigned queue A-012'),
	(140, 1, 'completed', '2026-04-17 09:16:09', 'Driver selesai antar penumpang'),
	(141, 5, 'tap_in', '2026-04-17 09:17:30', 'Driver tap kartu (check-in)'),
	(142, 5, 'assigned', '2026-04-17 09:17:30', 'Assigned queue B-002'),
	(143, 5, 'completed', '2026-04-17 09:17:44', 'Driver selesai antar penumpang'),
	(144, 4, 'completed', '2026-04-17 09:17:57', 'Driver selesai antar penumpang'),
	(145, 6, 'completed', '2026-04-17 09:18:20', 'Driver selesai antar penumpang'),
	(146, 6, 'tap_in', '2026-04-17 09:18:31', 'Driver tap kartu (check-in)'),
	(147, 6, 'assigned', '2026-04-17 09:18:31', 'Assigned queue C-003'),
	(148, 4, 'tap_in', '2026-04-17 09:18:41', 'Driver tap kartu (check-in)'),
	(149, 4, 'assigned', '2026-04-17 09:18:41', 'Assigned queue B-003'),
	(150, 1, 'tap_in', '2026-04-17 09:18:53', 'Driver tap kartu (check-in)'),
	(151, 1, 'assigned', '2026-04-17 09:18:53', 'Assigned queue A-013'),
	(152, 2, 'completed', '2026-04-17 09:19:05', 'Driver selesai antar penumpang'),
	(153, 2, 'tap_in', '2026-04-17 09:19:59', 'Driver tap kartu (check-in)'),
	(154, 2, 'assigned', '2026-04-17 09:19:59', 'Assigned queue A-014'),
	(155, 1, 'completed', '2026-04-18 12:08:06', 'Driver selesai antar penumpang'),
	(156, 1, 'tap_in', '2026-04-18 12:08:26', 'Driver tap kartu (check-in)'),
	(157, 1, 'assigned', '2026-04-18 12:08:26', 'Assigned queue A-001'),
	(158, 2, 'completed', '2026-04-18 13:37:29', 'Driver selesai antar penumpang'),
	(159, 2, 'tap_in', '2026-04-18 13:37:40', 'Driver tap kartu (check-in)'),
	(160, 7, 'tap_in', '2026-04-21 06:32:37', 'Driver tap kartu (check-in)'),
	(161, 2, 'assigned', '2026-04-21 06:54:50', 'Assigned queue A-001'),
	(162, 5, 'assigned', '2026-04-21 06:54:59', 'Assigned queue B-001'),
	(163, 7, 'assigned', '2026-04-21 06:55:08', 'Assigned queue A-002'),
	(164, 6, 'completed', '2026-04-21 06:55:57', 'Driver selesai antar penumpang'),
	(165, 6, 'tap_in', '2026-04-21 06:56:10', 'Driver tap kartu (check-in)'),
	(166, 6, 'assigned', '2026-04-21 06:56:10', 'Assigned queue C-001'),
	(167, 4, 'completed', '2026-04-21 06:56:24', 'Driver selesai antar penumpang'),
	(168, 4, 'defer_once', '2026-04-21 06:56:50', 'Driver menunda giliran satu kali');

-- Dumping structure for table pelabuhan_rr_system.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.jobs: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.job_batches: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.migrations: ~4 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_04_15_042650_create_personal_access_tokens_table', 2);

-- Dumping structure for table pelabuhan_rr_system.passenger_queues
CREATE TABLE IF NOT EXISTS `passenger_queues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `queue_number` varchar(20) NOT NULL,
  `vehicle_category_id` int NOT NULL,
  `passenger_type_id` int NOT NULL,
  `status` enum('waiting','assigned','cancelled') DEFAULT 'waiting',
  `queue_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_queue_daily` (`queue_date`,`vehicle_category_id`,`queue_number`),
  KEY `fk_queue_category` (`vehicle_category_id`),
  KEY `idx_queue_status` (`status`),
  KEY `idx_queue_passenger_type` (`passenger_type_id`),
  CONSTRAINT `fk_queue_category` FOREIGN KEY (`vehicle_category_id`) REFERENCES `vehicle_categories` (`id`),
  CONSTRAINT `fk_queue_passenger_type` FOREIGN KEY (`passenger_type_id`) REFERENCES `passenger_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.passenger_queues: ~21 rows (approximately)
INSERT INTO `passenger_queues` (`id`, `queue_number`, `vehicle_category_id`, `passenger_type_id`, `status`, `queue_date`, `created_at`, `updated_at`) VALUES
	(58, 'A-001', 1, 1, 'assigned', '2026-04-17', '2026-04-16 23:41:00', '2026-04-16 23:45:23'),
	(59, 'A-002', 1, 1, 'assigned', '2026-04-17', '2026-04-16 23:41:06', '2026-04-16 23:45:28'),
	(60, 'A-003', 1, 1, 'assigned', '2026-04-17', '2026-04-16 23:41:08', '2026-04-16 23:45:34'),
	(61, 'A-004', 1, 1, 'assigned', '2026-04-17', '2026-04-16 23:41:20', '2026-04-16 23:50:23'),
	(62, 'B-001', 2, 1, 'assigned', '2026-04-17', '2026-04-17 00:12:18', '2026-04-17 00:13:32'),
	(63, 'B-002', 2, 1, 'assigned', '2026-04-17', '2026-04-17 00:12:23', '2026-04-17 01:17:30'),
	(64, 'C-001', 3, 1, 'assigned', '2026-04-17', '2026-04-17 00:12:27', '2026-04-17 00:12:27'),
	(65, 'C-002', 3, 3, 'assigned', '2026-04-17', '2026-04-17 00:12:35', '2026-04-17 00:14:38'),
	(66, 'B-003', 2, 1, 'assigned', '2026-04-17', '2026-04-17 00:12:40', '2026-04-17 01:18:41'),
	(67, 'A-005', 1, 1, 'assigned', '2026-04-17', '2026-04-17 00:12:45', '2026-04-17 00:12:45'),
	(68, 'C-003', 3, 1, 'assigned', '2026-04-17', '2026-04-17 00:14:38', '2026-04-17 01:18:31'),
	(69, 'A-006', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:05:50', '2026-04-17 01:05:50'),
	(70, 'A-007', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:12:11', '2026-04-17 01:12:11'),
	(71, 'A-008', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:12:16', '2026-04-17 01:12:16'),
	(72, 'A-009', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:12:19', '2026-04-17 01:12:19'),
	(73, 'A-010', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:14:16', '2026-04-17 01:14:16'),
	(74, 'A-011', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:14:34', '2026-04-17 01:14:34'),
	(75, 'A-012', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:15:08', '2026-04-17 01:15:08'),
	(76, 'A-013', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:15:12', '2026-04-17 01:18:53'),
	(77, 'A-014', 1, 1, 'assigned', '2026-04-17', '2026-04-17 01:15:16', '2026-04-17 01:19:59'),
	(78, 'A-001', 1, 1, 'assigned', '2026-04-18', '2026-04-18 04:07:10', '2026-04-18 04:08:26'),
	(79, 'A-001', 1, 1, 'assigned', '2026-04-21', '2026-04-20 22:54:50', '2026-04-20 22:54:50'),
	(80, 'B-001', 2, 1, 'assigned', '2026-04-21', '2026-04-20 22:54:59', '2026-04-20 22:54:59'),
	(81, 'C-001', 3, 1, 'assigned', '2026-04-21', '2026-04-20 22:55:03', '2026-04-20 22:56:10'),
	(82, 'A-002', 1, 1, 'assigned', '2026-04-21', '2026-04-20 22:55:08', '2026-04-20 22:55:08');

-- Dumping structure for table pelabuhan_rr_system.passenger_types
CREATE TABLE IF NOT EXISTS `passenger_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `priority_level` tinyint NOT NULL COMMENT '0=Normal, 1=VIP, 2=VVIP',
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.passenger_types: ~3 rows (approximately)
INSERT INTO `passenger_types` (`id`, `name`, `priority_level`, `active`) VALUES
	(1, 'Normal', 0, 1),
	(2, 'VIP', 1, 1),
	(3, 'VVIP', 2, 1);

-- Dumping structure for table pelabuhan_rr_system.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table pelabuhan_rr_system.rr_pointers
CREATE TABLE IF NOT EXISTS `rr_pointers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vehicle_category_id` int NOT NULL,
  `last_driver_id` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_category_id` (`vehicle_category_id`),
  KEY `fk_rr_driver` (`last_driver_id`),
  CONSTRAINT `fk_rr_category` FOREIGN KEY (`vehicle_category_id`) REFERENCES `vehicle_categories` (`id`),
  CONSTRAINT `fk_rr_driver` FOREIGN KEY (`last_driver_id`) REFERENCES `drivers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.rr_pointers: ~3 rows (approximately)
INSERT INTO `rr_pointers` (`id`, `vehicle_category_id`, `last_driver_id`, `updated_at`) VALUES
	(1, 1, 7, '2026-04-21 06:55:08'),
	(2, 2, 5, '2026-04-21 06:54:59'),
	(3, 3, 6, '2026-04-16 06:25:18');

-- Dumping structure for table pelabuhan_rr_system.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.sessions: ~5 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('Bz6VLuV3Ucpzt1rMczB9TiPHKiQaOlZRn586hGDd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiIzTEVmaWJkTzJTT0VlSUtGellGNFoyQW1UN2pXTmV4R2pFVGQ1RXo2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776258009),
	('C6VyU7idyDsOLhX7ZV5IW1vtMInk5z3DUKh2lioY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJzUGlVVU1JNkhLNGVVSEtzcFZmdTlzRmNqVVpHMzc4OXVZeDBKbFUzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1776643469),
	('dyAZTWdWWmvz1A77ZtalBEFMaRX82HFAVeyhUwXw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJmSVhTbzRKNmNUWTlWS2tKcHR2ZEo3cWgxbmhOU1JxNFJQWXdlbFU0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776225207),
	('FQ6k0w8ELg7LGQtyZFu5T9uMFQV5uaithyIpS1jD', NULL, '192.168.18.210', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJQM3cyTzJJWXJJd0g2R1ppU2VtOHB3U2EweVoyZTE0MW5zQVdhaldBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjEwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776753589),
	('VYTuIBgDaDFkJcuFC4t7WkapKsslg5R1Elpv41zA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJHQ29mNjRkMUppSUJWNG1hTFN0ODBwMGpHenRwaTgzZVcwQVFwV2xqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1776406533),
	('YzgcOMSGxJqXyUrqoDCXohLIuhPOQhH5UZiGvUUD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJkU0RqSHl1cXVXUHU1NTZKTGM4S1l0NDk0NFNiWXVwT1VVckQxQ1d3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1776522146);

-- Dumping structure for table pelabuhan_rr_system.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table pelabuhan_rr_system.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `role`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'System Admin', 'admin', NULL, NULL, 'admin', '$2y$10$8cM9xXcHqPZp1n4H5aQnqe...', NULL, NULL, NULL),
	(2, 'System Admin', 'admin', NULL, NULL, 'admin', '$2y$10$8cM9xXcHqPZp1n4H5aQnqe...', NULL, NULL, NULL);

-- Dumping structure for table pelabuhan_rr_system.vehicle_categories
CREATE TABLE IF NOT EXISTS `vehicle_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table pelabuhan_rr_system.vehicle_categories: ~3 rows (approximately)
INSERT INTO `vehicle_categories` (`id`, `name`, `active`, `created_at`) VALUES
	(1, 'Motor', 1, '2026-04-15 08:40:46'),
	(2, 'Mobil', 1, '2026-04-15 08:40:46'),
	(3, 'Bus', 1, '2026-04-15 08:40:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
