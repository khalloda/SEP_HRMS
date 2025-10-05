-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 03, 2025 at 03:58 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sep_hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"d1f764ff-1a0e-4aa8-a72a-fb4abbe936e6@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:00:55', '2025-10-01 14:00:55'),
(2, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"82e31bdf-2ef2-4464-a738-a8ede8321247@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:00:57', '2025-10-01 14:00:57'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"6f59a942-d878-4df9-b791-ef4736b88e25@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:03:22', '2025-10-01 14:03:22'),
(4, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"20f04741-f7cb-4fda-81ab-a41ad6b71d7f@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:03:24', '2025-10-01 14:03:24'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"3d968498-5cba-4fa8-883a-459195c0b442@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:04:07', '2025-10-01 14:04:07'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"a333f8ea-8e4d-44ea-9ccb-a6cf30447ec3@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:04:09', '2025-10-01 14:04:09'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"4f9d902b-9c4c-4a97-b896-d7d97071118b@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:04:32', '2025-10-01 14:04:32'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"d58d1968-9387-41b2-a90a-2297ededd3f6@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:04:35', '2025-10-01 14:04:35'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"fccb8280-f92d-48c4-b401-4c3d894c322a@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:05:07', '2025-10-01 14:05:07'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"c456e081-f451-4d90-bb47-0812d46d50a0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:05:10', '2025-10-01 14:05:10'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"a57a4495-92d6-4a44-bfd6-c207cf84edfe@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:05:43', '2025-10-01 14:05:43'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"9f6ee473-ee9f-4d50-ba71-daed4e004847@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:05:45', '2025-10-01 14:05:45'),
(13, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 1, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll - October 2025\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:26:56', '2025-10-01 14:26:56'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"4dd1a328-2c9f-4145-b46f-638c69a3d906@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:27:09', '2025-10-01 14:27:09'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"b6947464-8aad-4334-ac15-6dc63552d2de@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:27:11', '2025-10-01 14:27:11'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"160a31d7-76bd-470f-82c0-326650860d6e@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:39:19', '2025-10-01 14:39:19'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"db7d1146-642e-433e-9135-b7bd596358a7@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:39:21', '2025-10-01 14:39:21'),
(18, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 2, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll - October 2025\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:39:35', '2025-10-01 14:39:35'),
(19, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6a7e8cd06@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:53:02', '2025-10-01 14:53:02'),
(20, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 1, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:53:02', '2025-10-01 14:53:02'),
(21, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6a8134bc1@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:53:05', '2025-10-01 14:53:05'),
(22, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6ad503de5@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:54:29', '2025-10-01 14:54:29'),
(23, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 2, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:54:29', '2025-10-01 14:54:29'),
(24, 'default', 'created', 'App\\Models\\User', 'created', 21, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6ad77c3dd@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:54:31', '2025-10-01 14:54:31'),
(25, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6bde346f0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:58:54', '2025-10-01 14:58:54'),
(26, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 3, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:58:54', '2025-10-01 14:58:54'),
(27, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6be0b085b@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:58:56', '2025-10-01 14:58:56'),
(28, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6bfae98e2@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:59:22', '2025-10-01 14:59:22'),
(29, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 4, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 14:59:22', '2025-10-01 14:59:22'),
(30, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6bfd6f244@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 14:59:25', '2025-10-01 14:59:25'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6c3757d72@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:00:23', '2025-10-01 15:00:23'),
(32, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 5, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:00:23', '2025-10-01 15:00:23'),
(33, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6c39d4986@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:00:25', '2025-10-01 15:00:25'),
(34, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6c71b7175@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:01:21', '2025-10-01 15:01:21'),
(35, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 6, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:01:21', '2025-10-01 15:01:21'),
(36, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6c7442a48@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:01:24', '2025-10-01 15:01:24'),
(37, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6cca46a8e@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:02:50', '2025-10-01 15:02:50'),
(38, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 7, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:02:50', '2025-10-01 15:02:50'),
(39, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6cccc3db5@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:02:52', '2025-10-01 15:02:52'),
(40, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6cf52b927@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:03:33', '2025-10-01 15:03:33'),
(41, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 8, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:03:33', '2025-10-01 15:03:33'),
(42, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6cf7d2233@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:03:35', '2025-10-01 15:03:35'),
(43, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6d1e1cc42@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:04:14', '2025-10-01 15:04:14'),
(44, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 9, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:04:14', '2025-10-01 15:04:14'),
(45, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6d20e7a6d@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:04:16', '2025-10-01 15:04:16'),
(46, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6d382c149@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:04:40', '2025-10-01 15:04:40'),
(47, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 10, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:04:40', '2025-10-01 15:04:40'),
(48, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dd6d3a5965f@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:04:42', '2025-10-01 15:04:42'),
(49, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"38b8d067-1c8d-46d4-b89d-76d38e5c6ed3@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:05:23', '2025-10-01 15:05:23'),
(50, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"bc853495-430a-4e4e-8b2f-3a33f7064097@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:05:25', '2025-10-01 15:05:25'),
(51, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 11, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll - October 2025\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:05:41', '2025-10-01 15:05:41'),
(52, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7cfc4004-2513-44e9-97c1-41394a18b32a@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:11:08', '2025-10-01 15:11:08'),
(53, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 12, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:11:08', '2025-10-01 15:11:08'),
(54, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-bf587dd8-0908-42cc-a8a1-93d4584f4c04@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:11:10', '2025-10-01 15:11:10'),
(55, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 13, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:11:10', '2025-10-01 15:11:10'),
(56, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-df8b20e5-f16a-4564-afc6-68b0b32dd343@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:12:52', '2025-10-01 15:12:52'),
(57, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 14, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:12:52', '2025-10-01 15:12:52'),
(58, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-51c2af76-2dfc-4664-bf7c-ce49363123f0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:12:55', '2025-10-01 15:12:55'),
(59, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 15, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:12:55', '2025-10-01 15:12:55'),
(60, 'default', 'created', 'App\\Models\\User', 'created', 44, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-420e9816-4f13-4f14-8dc5-eb420e6eac70@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:13:55', '2025-10-01 15:13:55'),
(61, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 16, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:13:55', '2025-10-01 15:13:55'),
(62, 'default', 'created', 'App\\Models\\User', 'created', 45, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1650565a-7d82-4d66-b82e-ddbb08cb0843@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:13:58', '2025-10-01 15:13:58'),
(63, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 17, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:13:58', '2025-10-01 15:13:58'),
(64, 'default', 'created', 'App\\Models\\User', 'created', 46, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-42aa6544-3f1d-433e-b5c7-b1483818be2e@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:16:20', '2025-10-01 15:16:20'),
(65, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 18, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:16:20', '2025-10-01 15:16:20'),
(66, 'default', 'created', 'App\\Models\\User', 'created', 47, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-454e9ec8-3686-4e6a-be1d-a2a21f11b06c@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:16:23', '2025-10-01 15:16:23'),
(67, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 19, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:16:23', '2025-10-01 15:16:23'),
(68, 'default', 'created', 'App\\Models\\User', 'created', 48, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-d86cf7dd-3336-4636-82b2-2b0f6e9af927@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:17:38', '2025-10-01 15:17:38'),
(69, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 20, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:17:38', '2025-10-01 15:17:38'),
(70, 'default', 'created', 'App\\Models\\User', 'created', 49, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1ed30498-ef2e-450a-8b7e-38ed121149d5@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:17:41', '2025-10-01 15:17:41'),
(71, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 21, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:17:41', '2025-10-01 15:17:41'),
(72, 'default', 'created', 'App\\Models\\User', 'created', 50, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b51a1836-0866-42da-b63c-7137a1fc77f3@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:20:36', '2025-10-01 15:20:36'),
(73, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 22, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:20:36', '2025-10-01 15:20:36'),
(74, 'default', 'created', 'App\\Models\\User', 'created', 51, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-4c609059-e059-4d9d-8a9c-142bed0549ad@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:20:39', '2025-10-01 15:20:39'),
(75, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 23, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:20:39', '2025-10-01 15:20:39'),
(76, 'default', 'created', 'App\\Models\\User', 'created', 52, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5b536124-9cfd-4ae5-a551-10d3042cfac3@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:21:40', '2025-10-01 15:21:40'),
(77, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 24, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:21:40', '2025-10-01 15:21:40'),
(78, 'default', 'created', 'App\\Models\\User', 'created', 53, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-72dea4af-c7e3-47a8-b8f1-b9ffcab8e2f9@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:21:43', '2025-10-01 15:21:43'),
(79, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 25, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:21:43', '2025-10-01 15:21:43'),
(80, 'default', 'created', 'App\\Models\\User', 'created', 54, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b3ee0e9b-208b-4c09-bfbf-a9c0459162ac@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:22:55', '2025-10-01 15:22:55'),
(81, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 26, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:22:55', '2025-10-01 15:22:55'),
(82, 'default', 'created', 'App\\Models\\User', 'created', 55, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-572f0af4-808d-4f65-ab83-57d7a3c14f26@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:22:58', '2025-10-01 15:22:58'),
(83, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 27, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:22:58', '2025-10-01 15:22:58'),
(84, 'default', 'created', 'App\\Models\\User', 'created', 56, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-efb7d846-511b-430f-985f-e655d9f00b09@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:23:50', '2025-10-01 15:23:50'),
(85, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 28, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:23:50', '2025-10-01 15:23:50'),
(86, 'default', 'created', 'App\\Models\\User', 'created', 57, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ca4cc9ef-b3aa-496f-a39a-598e23636421@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:23:53', '2025-10-01 15:23:53'),
(87, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 29, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:23:53', '2025-10-01 15:23:53'),
(88, 'default', 'created', 'App\\Models\\User', 'created', 58, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1c5eb172-baa7-4846-a5c0-3a686473aade@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:26:35', '2025-10-01 15:26:35'),
(89, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 30, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:26:35', '2025-10-01 15:26:35'),
(90, 'default', 'created', 'App\\Models\\User', 'created', 59, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1373ec95-f5a5-4d9f-a483-929bddeb6a9d@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:26:38', '2025-10-01 15:26:38'),
(91, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 31, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:26:38', '2025-10-01 15:26:38'),
(92, 'default', 'created', 'App\\Models\\User', 'created', 60, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd76f3a9cc8@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:11', '2025-10-01 15:46:11'),
(93, 'default', 'created', 'App\\Models\\Department', 'created', 8, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:46:11', '2025-10-01 15:46:11'),
(94, 'default', 'created', 'App\\Models\\User', 'created', 61, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd76f5c9a2b@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:13', '2025-10-01 15:46:13'),
(95, 'default', 'created', 'App\\Models\\User', 'created', 62, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd76f5cafa0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:13', '2025-10-01 15:46:13'),
(96, 'default', 'created', 'App\\Models\\Department', 'created', 9, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:46:13', '2025-10-01 15:46:13'),
(97, 'default', 'created', 'App\\Models\\User', 'created', 63, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd76f7df7e4@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:15', '2025-10-01 15:46:15'),
(98, 'default', 'created', 'App\\Models\\Department', 'created', 10, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:46:15', '2025-10-01 15:46:15'),
(99, 'default', 'created', 'App\\Models\\User', 'created', 64, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd76fa073d0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:18', '2025-10-01 15:46:18'),
(100, 'default', 'created', 'App\\Models\\User', 'created', 65, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd76fa08b13@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:18', '2025-10-01 15:46:18'),
(101, 'default', 'created', 'App\\Models\\Department', 'created', 11, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:46:18', '2025-10-01 15:46:18'),
(102, 'default', 'created', 'App\\Models\\User', 'created', 66, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd76fc1ced1@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:46:20', '2025-10-01 15:46:20'),
(103, 'default', 'created', 'App\\Models\\Department', 'created', 12, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:46:20', '2025-10-01 15:46:20'),
(104, 'default', 'created', 'App\\Models\\User', 'created', 67, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd7724ba8ab@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:00', '2025-10-01 15:47:00'),
(105, 'default', 'created', 'App\\Models\\Department', 'created', 13, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:47:00', '2025-10-01 15:47:00'),
(106, 'default', 'created', 'App\\Models\\User', 'created', 68, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd7726d93b8@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:02', '2025-10-01 15:47:02'),
(107, 'default', 'created', 'App\\Models\\User', 'created', 69, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd7726daaca@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:02', '2025-10-01 15:47:02'),
(108, 'default', 'created', 'App\\Models\\Department', 'created', 14, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:47:02', '2025-10-01 15:47:02'),
(109, 'default', 'created', 'App\\Models\\User', 'created', 70, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd7728ed91d@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:04', '2025-10-01 15:47:04'),
(110, 'default', 'created', 'App\\Models\\Department', 'created', 15, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:47:04', '2025-10-01 15:47:04'),
(111, 'default', 'created', 'App\\Models\\User', 'created', 71, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd772b10d83@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:07', '2025-10-01 15:47:07'),
(112, 'default', 'created', 'App\\Models\\User', 'created', 72, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd772b126e6@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:07', '2025-10-01 15:47:07'),
(113, 'default', 'created', 'App\\Models\\Department', 'created', 16, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:47:07', '2025-10-01 15:47:07'),
(114, 'default', 'created', 'App\\Models\\User', 'created', 73, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd772d28e92@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:47:09', '2025-10-01 15:47:09'),
(115, 'default', 'created', 'App\\Models\\Department', 'created', 17, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:47:09', '2025-10-01 15:47:09'),
(116, 'default', 'created', 'App\\Models\\User', 'created', 74, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd7762cf3c5@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:02', '2025-10-01 15:48:02'),
(117, 'default', 'created', 'App\\Models\\Department', 'created', 18, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:02', '2025-10-01 15:48:02'),
(118, 'default', 'created', 'App\\Models\\User', 'created', 75, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd7764ef32e@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:04', '2025-10-01 15:48:04'),
(119, 'default', 'created', 'App\\Models\\User', 'created', 76, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd7764f0a8f@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:04', '2025-10-01 15:48:04'),
(120, 'default', 'created', 'App\\Models\\Department', 'created', 19, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:04', '2025-10-01 15:48:04'),
(121, 'default', 'created', 'App\\Models\\User', 'created', 77, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd77671337c@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:07', '2025-10-01 15:48:07'),
(122, 'default', 'created', 'App\\Models\\Department', 'created', 20, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:07', '2025-10-01 15:48:07'),
(123, 'default', 'created', 'App\\Models\\User', 'created', 78, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd776926b14@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:09', '2025-10-01 15:48:09'),
(124, 'default', 'created', 'App\\Models\\User', 'created', 79, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd776928173@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:09', '2025-10-01 15:48:09'),
(125, 'default', 'created', 'App\\Models\\Department', 'created', 21, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:09', '2025-10-01 15:48:09'),
(126, 'default', 'created', 'App\\Models\\User', 'created', 80, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd776b41306@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:11', '2025-10-01 15:48:11'),
(127, 'default', 'created', 'App\\Models\\Department', 'created', 22, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:11', '2025-10-01 15:48:11'),
(128, 'default', 'created', 'App\\Models\\User', 'created', 81, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd778f4bc96@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:47', '2025-10-01 15:48:47'),
(129, 'default', 'created', 'App\\Models\\Department', 'created', 23, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:47', '2025-10-01 15:48:47'),
(130, 'default', 'created', 'App\\Models\\Position', 'created', 16, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:48:47', '2025-10-01 15:48:47'),
(131, 'default', 'created', 'App\\Models\\User', 'created', 82, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd77916b02c@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:49', '2025-10-01 15:48:49'),
(132, 'default', 'created', 'App\\Models\\User', 'created', 83, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77916c64b@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:49', '2025-10-01 15:48:49'),
(133, 'default', 'created', 'App\\Models\\Department', 'created', 24, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:49', '2025-10-01 15:48:49'),
(134, 'default', 'created', 'App\\Models\\Position', 'created', 17, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:48:49', '2025-10-01 15:48:49'),
(135, 'default', 'created', 'App\\Models\\User', 'created', 84, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd779384469@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:51', '2025-10-01 15:48:51'),
(136, 'default', 'created', 'App\\Models\\Department', 'created', 25, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:51', '2025-10-01 15:48:51'),
(137, 'default', 'created', 'App\\Models\\Position', 'created', 18, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:48:51', '2025-10-01 15:48:51'),
(138, 'default', 'created', 'App\\Models\\User', 'created', 85, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd77959b0d0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:53', '2025-10-01 15:48:53'),
(139, 'default', 'created', 'App\\Models\\User', 'created', 86, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77959c4cc@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:53', '2025-10-01 15:48:53'),
(140, 'default', 'created', 'App\\Models\\Department', 'created', 26, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:53', '2025-10-01 15:48:53'),
(141, 'default', 'created', 'App\\Models\\Position', 'created', 19, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:48:53', '2025-10-01 15:48:53'),
(142, 'default', 'created', 'App\\Models\\User', 'created', 87, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd7797b1737@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:48:55', '2025-10-01 15:48:55'),
(143, 'default', 'created', 'App\\Models\\Department', 'created', 27, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:48:55', '2025-10-01 15:48:55'),
(144, 'default', 'created', 'App\\Models\\Position', 'created', 20, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:48:55', '2025-10-01 15:48:55'),
(145, 'default', 'created', 'App\\Models\\User', 'created', 88, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd77b7c66ac@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:27', '2025-10-01 15:49:27'),
(146, 'default', 'created', 'App\\Models\\Department', 'created', 28, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:49:27', '2025-10-01 15:49:27'),
(147, 'default', 'created', 'App\\Models\\Position', 'created', 21, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:49:27', '2025-10-01 15:49:27'),
(148, 'default', 'created', 'App\\Models\\User', 'created', 89, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd77b9e4fb2@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:29', '2025-10-01 15:49:29'),
(149, 'default', 'created', 'App\\Models\\User', 'created', 90, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77b9e63ae@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:29', '2025-10-01 15:49:29'),
(150, 'default', 'created', 'App\\Models\\Department', 'created', 29, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:49:29', '2025-10-01 15:49:29'),
(151, 'default', 'created', 'App\\Models\\Position', 'created', 22, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:49:29', '2025-10-01 15:49:29'),
(152, 'default', 'created', 'App\\Models\\User', 'created', 91, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd77bc0661e@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:32', '2025-10-01 15:49:32'),
(153, 'default', 'created', 'App\\Models\\Department', 'created', 30, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:49:32', '2025-10-01 15:49:32'),
(154, 'default', 'created', 'App\\Models\\Position', 'created', 23, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:49:32', '2025-10-01 15:49:32'),
(155, 'default', 'created', 'App\\Models\\User', 'created', 92, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd77be1b8ae@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:34', '2025-10-01 15:49:34'),
(156, 'default', 'created', 'App\\Models\\User', 'created', 93, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77be1ca12@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:34', '2025-10-01 15:49:34'),
(157, 'default', 'created', 'App\\Models\\Department', 'created', 31, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:49:34', '2025-10-01 15:49:34'),
(158, 'default', 'created', 'App\\Models\\Position', 'created', 24, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:49:34', '2025-10-01 15:49:34'),
(159, 'default', 'created', 'App\\Models\\User', 'created', 94, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd77c02fd62@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:49:36', '2025-10-01 15:49:36'),
(160, 'default', 'created', 'App\\Models\\Department', 'created', 32, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:49:36', '2025-10-01 15:49:36'),
(161, 'default', 'created', 'App\\Models\\Position', 'created', 25, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:49:36', '2025-10-01 15:49:36'),
(162, 'default', 'created', 'App\\Models\\User', 'created', 95, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd77dfd372a@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:07', '2025-10-01 15:50:07'),
(163, 'default', 'created', 'App\\Models\\Department', 'created', 33, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:07', '2025-10-01 15:50:07'),
(164, 'default', 'created', 'App\\Models\\Position', 'created', 26, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:07', '2025-10-01 15:50:07'),
(165, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 16, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd77dfd947a\"}}', NULL, '2025-10-01 15:50:07', '2025-10-01 15:50:07');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(166, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 1, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd77dfd9d38\", \"email\": \"employee-68dd77dfd9d39@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 26, \"department_id\": 33, \"photo_uploaded_at\": null, \"employment_type_id\": 16, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:07', '2025-10-01 15:50:07'),
(167, 'default', 'created', 'App\\Models\\User', 'created', 96, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd77e203fb1@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(168, 'default', 'created', 'App\\Models\\User', 'created', 97, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77e205627@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(169, 'default', 'created', 'App\\Models\\Department', 'created', 34, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(170, 'default', 'created', 'App\\Models\\Position', 'created', 27, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(171, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 17, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd77e2073ed\"}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(172, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 2, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd77e207939\", \"email\": \"employee-68dd77e20793b@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 27, \"department_id\": 34, \"photo_uploaded_at\": null, \"employment_type_id\": 17, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:10', '2025-10-01 15:50:10'),
(173, 'default', 'created', 'App\\Models\\User', 'created', 98, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd77e41dec2@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:12', '2025-10-01 15:50:12'),
(174, 'default', 'created', 'App\\Models\\Department', 'created', 35, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:12', '2025-10-01 15:50:12'),
(175, 'default', 'created', 'App\\Models\\Position', 'created', 28, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:12', '2025-10-01 15:50:12'),
(176, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 18, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd77e4207de\"}}', NULL, '2025-10-01 15:50:12', '2025-10-01 15:50:12'),
(177, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 3, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd77e42103d\", \"email\": \"employee-68dd77e42103f@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 28, \"department_id\": 35, \"photo_uploaded_at\": null, \"employment_type_id\": 18, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:12', '2025-10-01 15:50:12'),
(178, 'default', 'created', 'App\\Models\\User', 'created', 99, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd77e6348f0@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(179, 'default', 'created', 'App\\Models\\User', 'created', 100, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd77e635c3f@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(180, 'default', 'created', 'App\\Models\\Department', 'created', 36, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(181, 'default', 'created', 'App\\Models\\Position', 'created', 29, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(182, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 19, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd77e637b6b\"}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(183, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 4, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd77e63811a\", \"email\": \"employee-68dd77e63811b@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 29, \"department_id\": 36, \"photo_uploaded_at\": null, \"employment_type_id\": 19, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:14', '2025-10-01 15:50:14'),
(184, 'default', 'created', 'App\\Models\\User', 'created', 101, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd77e84b7a8@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:16', '2025-10-01 15:50:16'),
(185, 'default', 'created', 'App\\Models\\Department', 'created', 37, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:16', '2025-10-01 15:50:16'),
(186, 'default', 'created', 'App\\Models\\Position', 'created', 30, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:16', '2025-10-01 15:50:16'),
(187, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 20, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd77e84d5cb\"}}', NULL, '2025-10-01 15:50:16', '2025-10-01 15:50:16'),
(188, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 5, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd77e84dbbc\", \"email\": \"employee-68dd77e84dbbe@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 30, \"department_id\": 37, \"photo_uploaded_at\": null, \"employment_type_id\": 20, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:16', '2025-10-01 15:50:16'),
(189, 'default', 'created', 'App\\Models\\User', 'created', 102, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd78082a1c4@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(190, 'default', 'created', 'App\\Models\\Department', 'created', 38, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(191, 'default', 'created', 'App\\Models\\Position', 'created', 31, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(192, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 21, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd780833b3f\"}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(193, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 6, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd780834fc9\", \"email\": \"employee-68dd780834fd0@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 31, \"department_id\": 38, \"photo_uploaded_at\": null, \"employment_type_id\": 21, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(194, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 32, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:50:48', '2025-10-01 15:50:48'),
(195, 'default', 'created', 'App\\Models\\User', 'created', 103, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68dd780a4d11d@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(196, 'default', 'created', 'App\\Models\\User', 'created', 104, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd780a4e46d@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(197, 'default', 'created', 'App\\Models\\Department', 'created', 39, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(198, 'default', 'created', 'App\\Models\\Position', 'created', 32, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(199, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 22, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd780a50632\"}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(200, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 7, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd780a50cc2\", \"email\": \"employee-68dd780a50cc4@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 32, \"department_id\": 39, \"photo_uploaded_at\": null, \"employment_type_id\": 22, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(201, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 33, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:50:50', '2025-10-01 15:50:50'),
(202, 'default', 'created', 'App\\Models\\User', 'created', 105, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68dd780c5f0c2@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(203, 'default', 'created', 'App\\Models\\Department', 'created', 40, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(204, 'default', 'created', 'App\\Models\\Position', 'created', 33, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(205, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 23, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd780c61242\"}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(206, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 8, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd780c61825\", \"email\": \"employee-68dd780c61827@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 33, \"department_id\": 40, \"photo_uploaded_at\": null, \"employment_type_id\": 23, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(207, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 34, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:50:52', '2025-10-01 15:50:52'),
(208, 'default', 'created', 'App\\Models\\User', 'created', 106, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68dd780e74e11@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(209, 'default', 'created', 'App\\Models\\User', 'created', 107, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68dd780e75f26@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(210, 'default', 'created', 'App\\Models\\Department', 'created', 41, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(211, 'default', 'created', 'App\\Models\\Position', 'created', 34, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(212, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 24, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd780e7809c\"}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(213, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 9, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd780e78699\", \"email\": \"employee-68dd780e7869b@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 34, \"department_id\": 41, \"photo_uploaded_at\": null, \"employment_type_id\": 24, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(214, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 35, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:50:54', '2025-10-01 15:50:54'),
(215, 'default', 'created', 'App\\Models\\User', 'created', 108, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68dd78108877f@example.com\", \"employee_id\": null}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(216, 'default', 'created', 'App\\Models\\Department', 'created', 42, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(217, 'default', 'created', 'App\\Models\\Position', 'created', 35, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(218, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 25, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68dd78108a8a0\"}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(219, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 10, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dd78108af29\", \"email\": \"employee-68dd78108af2b@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-01T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 35, \"department_id\": 42, \"photo_uploaded_at\": null, \"employment_type_id\": 25, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(220, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 36, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-01 15:50:56', '2025-10-01 15:50:56'),
(221, 'default', 'created', 'App\\Models\\User', 'created', 109, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de2127892b9@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(222, 'default', 'created', 'App\\Models\\Department', 'created', 43, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(223, 'default', 'created', 'App\\Models\\Position', 'created', 36, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(224, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 26, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21289b33b\"}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(225, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 11, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2128a4ef6\", \"email\": \"employee-68de2128a4ef8@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 36, \"department_id\": 43, \"photo_uploaded_at\": null, \"employment_type_id\": 26, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(226, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 37, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:52:24', '2025-10-02 03:52:24'),
(227, 'default', 'created', 'App\\Models\\User', 'created', 110, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de212b6ef02@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(228, 'default', 'created', 'App\\Models\\User', 'created', 111, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de212b70406@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(229, 'default', 'created', 'App\\Models\\Department', 'created', 44, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(230, 'default', 'created', 'App\\Models\\Position', 'created', 37, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(231, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 27, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de212b7226b\"}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(232, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 12, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de212b7280e\", \"email\": \"employee-68de212b72810@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 37, \"department_id\": 44, \"photo_uploaded_at\": null, \"employment_type_id\": 27, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(233, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 38, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:52:27', '2025-10-02 03:52:27'),
(234, 'default', 'created', 'App\\Models\\User', 'created', 112, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de212d8a5dc@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(235, 'default', 'created', 'App\\Models\\Department', 'created', 45, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(236, 'default', 'created', 'App\\Models\\Position', 'created', 38, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(237, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 28, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de212d8d1cd\"}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(238, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 13, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de212d8db5b\", \"email\": \"employee-68de212d8db5d@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 38, \"department_id\": 45, \"photo_uploaded_at\": null, \"employment_type_id\": 28, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(239, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 39, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:52:29', '2025-10-02 03:52:29'),
(240, 'default', 'created', 'App\\Models\\User', 'created', 113, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de212fa24c5@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(241, 'default', 'created', 'App\\Models\\User', 'created', 114, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de212fa5b6a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(242, 'default', 'created', 'App\\Models\\Department', 'created', 46, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(243, 'default', 'created', 'App\\Models\\Position', 'created', 39, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(244, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 29, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de212fa7881\"}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(245, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 14, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de212fa7e7a\", \"email\": \"employee-68de212fa7e7d@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 39, \"department_id\": 46, \"photo_uploaded_at\": null, \"employment_type_id\": 29, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(246, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 40, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:52:31', '2025-10-02 03:52:31'),
(247, 'default', 'created', 'App\\Models\\User', 'created', 115, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de2131cc1f3@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(248, 'default', 'created', 'App\\Models\\Department', 'created', 47, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(249, 'default', 'created', 'App\\Models\\Position', 'created', 40, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(250, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 30, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2131cf03f\"}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(251, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 15, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2131cf8af\", \"email\": \"employee-68de2131cf8b1@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 40, \"department_id\": 47, \"photo_uploaded_at\": null, \"employment_type_id\": 30, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(252, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 41, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:52:33', '2025-10-02 03:52:33'),
(253, 'default', 'created', 'App\\Models\\User', 'created', 116, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de21c4217a1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(254, 'default', 'created', 'App\\Models\\Department', 'created', 48, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(255, 'default', 'created', 'App\\Models\\Position', 'created', 41, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(256, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 31, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21c4280a1\"}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(257, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 16, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de21c428bcc\", \"email\": \"employee-68de21c428bce@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 41, \"department_id\": 48, \"photo_uploaded_at\": null, \"employment_type_id\": 31, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(258, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 42, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:55:00', '2025-10-02 03:55:00'),
(259, 'default', 'created', 'App\\Models\\User', 'created', 117, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de21c64149f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(260, 'default', 'created', 'App\\Models\\User', 'created', 118, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de21c642b95@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(261, 'default', 'created', 'App\\Models\\Department', 'created', 49, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(262, 'default', 'created', 'App\\Models\\Position', 'created', 42, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(263, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 32, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21c644b84\"}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(264, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 17, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de21c64536e\", \"email\": \"employee-68de21c645370@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 42, \"department_id\": 49, \"photo_uploaded_at\": null, \"employment_type_id\": 32, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(265, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 43, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:55:02', '2025-10-02 03:55:02'),
(266, 'default', 'created', 'App\\Models\\User', 'created', 119, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de21c851fdb@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(267, 'default', 'created', 'App\\Models\\Department', 'created', 50, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(268, 'default', 'created', 'App\\Models\\Position', 'created', 43, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(269, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 33, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21c8544a5\"}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(270, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 18, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de21c854b3a\", \"email\": \"employee-68de21c854b3c@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 43, \"department_id\": 50, \"photo_uploaded_at\": null, \"employment_type_id\": 33, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(271, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 44, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:55:04', '2025-10-02 03:55:04'),
(272, 'default', 'created', 'App\\Models\\User', 'created', 120, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de21ca63ed7@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(273, 'default', 'created', 'App\\Models\\User', 'created', 121, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de21ca6558a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(274, 'default', 'created', 'App\\Models\\Department', 'created', 51, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(275, 'default', 'created', 'App\\Models\\Position', 'created', 44, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(276, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 34, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21ca67ff0\"}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(277, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 19, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de21ca68a3f\", \"email\": \"employee-68de21ca68a41@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 44, \"department_id\": 51, \"photo_uploaded_at\": null, \"employment_type_id\": 34, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(278, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 45, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:55:06', '2025-10-02 03:55:06'),
(279, 'default', 'created', 'App\\Models\\User', 'created', 122, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de21cc7800e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(280, 'default', 'created', 'App\\Models\\Department', 'created', 52, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(281, 'default', 'created', 'App\\Models\\Position', 'created', 45, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(282, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 35, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de21cc7ac0f\"}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(283, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 20, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de21cc7b34f\", \"email\": \"employee-68de21cc7b351@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 45, \"department_id\": 52, \"photo_uploaded_at\": null, \"employment_type_id\": 35, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(284, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 46, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:55:08', '2025-10-02 03:55:08'),
(285, 'default', 'created', 'App\\Models\\User', 'created', 123, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de22af54a4a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(286, 'default', 'created', 'App\\Models\\Department', 'created', 53, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(287, 'default', 'created', 'App\\Models\\Position', 'created', 46, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(288, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 36, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de22af5b3e6\"}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(289, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 21, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de22af5bdac\", \"email\": \"employee-68de22af5bdad@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 46, \"department_id\": 53, \"photo_uploaded_at\": null, \"employment_type_id\": 36, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(290, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 47, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(291, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 1, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de22af5bdac\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 03:58:55', '2025-10-02 03:58:55'),
(292, 'default', 'created', 'App\\Models\\User', 'created', 124, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de22b53c0b1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(293, 'default', 'created', 'App\\Models\\User', 'created', 125, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de22b53d873@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(294, 'default', 'created', 'App\\Models\\Department', 'created', 54, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(295, 'default', 'created', 'App\\Models\\Position', 'created', 47, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(296, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 37, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de22b53f315\"}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(297, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 22, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de22b53f9cf\", \"email\": \"employee-68de22b53f9d0@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 47, \"department_id\": 54, \"photo_uploaded_at\": null, \"employment_type_id\": 37, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(298, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 48, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(299, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 2, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de22b53f9cf\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 03:59:01', '2025-10-02 03:59:01'),
(300, 'default', 'created', 'App\\Models\\User', 'created', 126, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de22b7734cb@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(301, 'default', 'created', 'App\\Models\\Department', 'created', 55, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(302, 'default', 'created', 'App\\Models\\Position', 'created', 48, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(303, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 38, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de22b77550f\"}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(304, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 23, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de22b775a87\", \"email\": \"employee-68de22b775a89@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 48, \"department_id\": 55, \"photo_uploaded_at\": null, \"employment_type_id\": 38, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(305, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 49, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(306, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 3, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de22b775a87\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(307, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 1, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(308, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 2, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 03:59:03', '2025-10-02 03:59:03'),
(309, 'default', 'created', 'App\\Models\\User', 'created', 127, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de22b994d6d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(310, 'default', 'created', 'App\\Models\\User', 'created', 128, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de22b995f75@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(311, 'default', 'created', 'App\\Models\\Department', 'created', 56, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(312, 'default', 'created', 'App\\Models\\Position', 'created', 49, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(313, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 39, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de22b998350\"}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(314, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 24, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de22b99896a\", \"email\": \"employee-68de22b99896b@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 49, \"department_id\": 56, \"photo_uploaded_at\": null, \"employment_type_id\": 39, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(315, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 50, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(316, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 4, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de22b99896a\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(317, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 3, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(318, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 4, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 03:59:05', '2025-10-02 03:59:05'),
(319, 'default', 'created', 'App\\Models\\User', 'created', 129, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de22bbabe61@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(320, 'default', 'created', 'App\\Models\\Department', 'created', 57, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(321, 'default', 'created', 'App\\Models\\Position', 'created', 50, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(322, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 40, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de22bbadcb4\"}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(323, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 25, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de22bbae42f\", \"email\": \"employee-68de22bbae431@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 50, \"department_id\": 57, \"photo_uploaded_at\": null, \"employment_type_id\": 40, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(324, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 51, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(325, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 5, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de22bbae42f\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(326, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 5, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(327, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 6, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 03:59:07', '2025-10-02 03:59:07'),
(328, 'default', 'created', 'App\\Models\\User', 'created', 130, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de2301662f4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(329, 'default', 'created', 'App\\Models\\Department', 'created', 58, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(330, 'default', 'created', 'App\\Models\\Position', 'created', 51, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(331, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 41, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de23016c90a\"}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(332, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 26, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de23016d413\", \"email\": \"employee-68de23016d415@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 51, \"department_id\": 58, \"photo_uploaded_at\": null, \"employment_type_id\": 41, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(333, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 52, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(334, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 6, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de23016d413\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:17', '2025-10-02 04:00:17'),
(335, 'default', 'created', 'App\\Models\\User', 'created', 131, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de230396091@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(336, 'default', 'created', 'App\\Models\\User', 'created', 132, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de230397e08@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(337, 'default', 'created', 'App\\Models\\Department', 'created', 59, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(338, 'default', 'created', 'App\\Models\\Position', 'created', 52, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(339, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 42, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de23039a2fc\"}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(340, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 27, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de23039a95a\", \"email\": \"employee-68de23039a95c@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 52, \"department_id\": 59, \"photo_uploaded_at\": null, \"employment_type_id\": 42, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(341, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 53, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(342, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 7, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de23039a95a\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:19', '2025-10-02 04:00:19'),
(343, 'default', 'created', 'App\\Models\\User', 'created', 133, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de2305b2550@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(344, 'default', 'created', 'App\\Models\\Department', 'created', 60, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(345, 'default', 'created', 'App\\Models\\Position', 'created', 53, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(346, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 43, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2305b4637\"}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(347, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 28, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2305b4d0c\", \"email\": \"employee-68de2305b4d0e@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 53, \"department_id\": 60, \"photo_uploaded_at\": null, \"employment_type_id\": 43, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(348, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 54, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(349, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 8, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2305b4d0c\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(350, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 7, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(351, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 8, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(352, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 9, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:00:21', '2025-10-02 04:00:21'),
(353, 'default', 'created', 'App\\Models\\User', 'created', 134, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de2307c5049@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(354, 'default', 'created', 'App\\Models\\User', 'created', 135, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de2307c61ea@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(355, 'default', 'created', 'App\\Models\\Department', 'created', 61, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(356, 'default', 'created', 'App\\Models\\Position', 'created', 54, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(357, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 44, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2307c7f3e\"}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(358, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 29, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2307c8556\", \"email\": \"employee-68de2307c8557@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 54, \"department_id\": 61, \"photo_uploaded_at\": null, \"employment_type_id\": 44, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(359, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 55, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(360, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 9, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2307c8556\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(361, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 10, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(362, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 11, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(363, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 12, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:00:23', '2025-10-02 04:00:23'),
(364, 'default', 'created', 'App\\Models\\User', 'created', 136, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de2309d94da@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(365, 'default', 'created', 'App\\Models\\Department', 'created', 62, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(366, 'default', 'created', 'App\\Models\\Position', 'created', 55, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(367, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 45, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2309db683\"}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(368, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 30, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2309dbcbb\", \"email\": \"employee-68de2309dbcbd@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 55, \"department_id\": 62, \"photo_uploaded_at\": null, \"employment_type_id\": 45, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(369, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 56, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(370, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 10, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2309dbcbb\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(371, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 13, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(372, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 14, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(373, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 15, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:00:25', '2025-10-02 04:00:25'),
(374, 'default', 'created', 'App\\Models\\User', 'created', 137, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de2325d8dbc@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(375, 'default', 'created', 'App\\Models\\Department', 'created', 63, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(376, 'default', 'created', 'App\\Models\\Position', 'created', 56, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(377, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 46, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2325de7fc\"}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(378, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 31, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2325df0e9\", \"email\": \"employee-68de2325df0eb@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 56, \"department_id\": 63, \"photo_uploaded_at\": null, \"employment_type_id\": 46, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(379, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 57, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(380, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 11, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2325df0e9\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:53', '2025-10-02 04:00:53'),
(381, 'default', 'created', 'App\\Models\\User', 'created', 138, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de23280f5d0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(382, 'default', 'created', 'App\\Models\\User', 'created', 139, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de232810bde@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(383, 'default', 'created', 'App\\Models\\Department', 'created', 64, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(384, 'default', 'created', 'App\\Models\\Position', 'created', 57, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(385, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 47, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de232812a45\"}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(386, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 32, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de23281306b\", \"email\": \"employee-68de23281306d@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 57, \"department_id\": 64, \"photo_uploaded_at\": null, \"employment_type_id\": 47, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(387, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 58, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(388, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 12, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de23281306b\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:56', '2025-10-02 04:00:56'),
(389, 'default', 'created', 'App\\Models\\User', 'created', 140, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de232a2615b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(390, 'default', 'created', 'App\\Models\\Department', 'created', 65, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(391, 'default', 'created', 'App\\Models\\Position', 'created', 58, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(392, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 48, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de232a2a5b9\"}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(393, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 33, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de232a2abcb\", \"email\": \"employee-68de232a2abcc@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 58, \"department_id\": 65, \"photo_uploaded_at\": null, \"employment_type_id\": 48, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(394, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 59, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(395, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 13, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de232a2abcb\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(396, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 16, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(397, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 17, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(398, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 18, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:00:58', '2025-10-02 04:00:58'),
(399, 'default', 'created', 'App\\Models\\User', 'created', 141, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de232c94d17@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(400, 'default', 'created', 'App\\Models\\User', 'created', 142, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de232c95f50@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(401, 'default', 'created', 'App\\Models\\Department', 'created', 66, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(402, 'default', 'created', 'App\\Models\\Position', 'created', 59, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(403, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 49, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de232c97dd7\"}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(404, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 34, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de232c98420\", \"email\": \"employee-68de232c98422@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 59, \"department_id\": 66, \"photo_uploaded_at\": null, \"employment_type_id\": 49, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(405, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 60, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(406, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 14, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de232c98420\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(407, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 19, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(408, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 20, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(409, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 21, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:01:00', '2025-10-02 04:01:00'),
(410, 'default', 'created', 'App\\Models\\User', 'created', 143, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de232eb2b16@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(411, 'default', 'created', 'App\\Models\\Department', 'created', 67, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(412, 'default', 'created', 'App\\Models\\Position', 'created', 60, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(413, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 50, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de232eb4d1b\"}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(414, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 35, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de232eb5341\", \"email\": \"employee-68de232eb5343@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 60, \"department_id\": 67, \"photo_uploaded_at\": null, \"employment_type_id\": 50, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(415, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 61, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(416, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 15, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de232eb5341\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(417, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 22, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(418, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 23, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(419, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 24, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:01:02', '2025-10-02 04:01:02'),
(420, 'default', 'created', 'App\\Models\\User', 'created', 144, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-export-68de29127267c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:10', '2025-10-02 04:26:10'),
(421, 'default', 'created', 'App\\Models\\Department', 'created', 68, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de2912797c8\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:26:10', '2025-10-02 04:26:10'),
(422, 'default', 'created', 'App\\Models\\User', 'created', 145, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-export-68de292aad73e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(423, 'default', 'created', 'App\\Models\\Department', 'created', 69, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de292ab36ce\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(424, 'default', 'created', 'App\\Models\\Position', 'created', 61, NULL, NULL, '{\"attributes\": {\"name_ar\": \"?????\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(425, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 36, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de292ab5967\", \"email\": \"employee-68de292ab5969@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"??? ??\", \"position_id\": 61, \"department_id\": 69, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(426, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 62, NULL, NULL, '{\"attributes\": {\"title\": \"Monthly Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(427, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 16, NULL, NULL, '{\"attributes\": {\"status\": \"generated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de292ab5967\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:26:34', '2025-10-02 04:26:34'),
(428, 'default', 'created', 'App\\Models\\User', 'created', 146, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de293e8186a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(429, 'default', 'created', 'App\\Models\\Department', 'created', 70, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(430, 'default', 'created', 'App\\Models\\Position', 'created', 62, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(431, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 51, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de293e87c10\"}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(432, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 37, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de293e88725\", \"email\": \"employee-68de293e88727@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 62, \"department_id\": 70, \"photo_uploaded_at\": null, \"employment_type_id\": 51, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(433, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 63, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(434, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 17, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de293e88725\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:26:54', '2025-10-02 04:26:54'),
(435, 'default', 'created', 'App\\Models\\User', 'created', 147, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68de2940b202d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(436, 'default', 'created', 'App\\Models\\User', 'created', 148, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de2940b359f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(437, 'default', 'created', 'App\\Models\\Department', 'created', 71, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(438, 'default', 'created', 'App\\Models\\Position', 'created', 63, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(439, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 52, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2940b51b0\"}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(440, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 38, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2940b59f1\", \"email\": \"employee-68de2940b59f4@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 63, \"department_id\": 71, \"photo_uploaded_at\": null, \"employment_type_id\": 52, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(441, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 64, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(442, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 18, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2940b59f1\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:26:56', '2025-10-02 04:26:56'),
(443, 'default', 'created', 'App\\Models\\User', 'created', 149, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68de2942cec1d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(444, 'default', 'created', 'App\\Models\\Department', 'created', 72, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(445, 'default', 'created', 'App\\Models\\Position', 'created', 64, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(446, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 53, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de2942d27cb\"}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(447, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 39, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2942d2e6d\", \"email\": \"employee-68de2942d2e70@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 64, \"department_id\": 72, \"photo_uploaded_at\": null, \"employment_type_id\": 53, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(448, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 65, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(449, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 19, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2942d2e6d\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(450, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 25, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(451, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 26, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(452, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 27, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:26:58', '2025-10-02 04:26:58'),
(453, 'default', 'created', 'App\\Models\\User', 'created', 150, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68de2944f1176@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:27:00', '2025-10-02 04:27:00'),
(454, 'default', 'created', 'App\\Models\\User', 'created', 151, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68de2944f2c99@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:27:00', '2025-10-02 04:27:00'),
(455, 'default', 'created', 'App\\Models\\Department', 'created', 73, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(456, 'default', 'created', 'App\\Models\\Position', 'created', 65, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(457, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 54, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de294500dfd\"}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(458, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 40, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de294501503\", \"email\": \"employee-68de294501504@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 65, \"department_id\": 73, \"photo_uploaded_at\": null, \"employment_type_id\": 54, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(459, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 66, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(460, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 20, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de294501503\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(461, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 28, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(462, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 29, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(463, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 30, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:27:01', '2025-10-02 04:27:01'),
(464, 'default', 'created', 'App\\Models\\User', 'created', 152, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68de29471b3b9@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(465, 'default', 'created', 'App\\Models\\Department', 'created', 74, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(466, 'default', 'created', 'App\\Models\\Position', 'created', 66, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(467, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 55, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68de29471dd74\"}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(468, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 41, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de29471e4e8\", \"email\": \"employee-68de29471e4ec@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 66, \"department_id\": 74, \"photo_uploaded_at\": null, \"employment_type_id\": 55, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(469, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 67, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(470, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 21, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de29471e4e8\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(471, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 31, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(472, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 32, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(473, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 33, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 04:27:03', '2025-10-02 04:27:03'),
(474, 'default', 'created', 'App\\Models\\User', 'created', 153, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-export-68de2bd08774f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(475, 'default', 'created', 'App\\Models\\Department', 'created', 75, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de2bd08cb52\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(476, 'default', 'created', 'App\\Models\\Position', 'created', 67, NULL, NULL, '{\"attributes\": {\"name_ar\": \"?????\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(477, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 42, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68de2bd08e0d2\", \"email\": \"employee-68de2bd08e0d4@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"??? ??\", \"position_id\": 67, \"department_id\": 75, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(478, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 68, NULL, NULL, '{\"attributes\": {\"title\": \"Monthly Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(479, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 22, NULL, NULL, '{\"attributes\": {\"status\": \"generated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68de2bd08e0d2\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:37:52', '2025-10-02 04:37:52'),
(480, 'default', 'created', 'App\\Models\\User', 'created', 154, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68de2bdebddd0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(481, 'default', 'created', 'App\\Models\\Department', 'created', 76, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de2bdec3391\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(482, 'default', 'created', 'App\\Models\\Position', 'created', 68, NULL, NULL, '{\"attributes\": {\"name_ar\": \"?????\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(483, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 43, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-TEST\", \"email\": \"employee-68de2bdec4ae4@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"??? ??\", \"position_id\": 68, \"department_id\": 76, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(484, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 69, NULL, NULL, '{\"attributes\": {\"title\": \"Monthly Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(485, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 23, NULL, NULL, '{\"attributes\": {\"status\": \"generated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-TEST\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:38:06', '2025-10-02 04:38:06'),
(486, 'default', 'created', 'App\\Models\\User', 'created', 155, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68de2be43e7d8@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(487, 'default', 'created', 'App\\Models\\Department', 'created', 77, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de2be43fc99\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(488, 'default', 'created', 'App\\Models\\Position', 'created', 69, NULL, NULL, '{\"attributes\": {\"name_ar\": \"?????\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(489, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 44, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-TEST\", \"email\": \"employee-68de2be440cab@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"??? ??\", \"position_id\": 69, \"department_id\": 77, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(490, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 70, NULL, NULL, '{\"attributes\": {\"title\": \"Monthly Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(491, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 24, NULL, NULL, '{\"attributes\": {\"status\": \"generated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-TEST\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:38:12', '2025-10-02 04:38:12'),
(492, 'default', 'created', 'App\\Models\\User', 'created', 156, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68de2be68edc6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(493, 'default', 'created', 'App\\Models\\Department', 'created', 78, NULL, NULL, '{\"attributes\": {\"code\": \"FIN-68de2be6909c5\", \"name_ar\": \"???????\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(494, 'default', 'created', 'App\\Models\\Position', 'created', 70, NULL, NULL, '{\"attributes\": {\"name_ar\": \"?????\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(495, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 45, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-TEST\", \"email\": \"employee-68de2be6920cc@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"??? ??\", \"position_id\": 70, \"department_id\": 78, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(496, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 71, NULL, NULL, '{\"attributes\": {\"title\": \"Monthly Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(497, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 25, NULL, NULL, '{\"attributes\": {\"status\": \"generated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-TEST\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 04:38:14', '2025-10-02 04:38:14'),
(498, 'default', 'created', 'App\\Models\\User', 'created', 157, NULL, NULL, '{\"attributes\": {\"name\": \"Chunk Owner\", \"email\": \"chunk-owner-68de60b36c9a9@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:23:31', '2025-10-02 08:23:31'),
(499, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 72, NULL, NULL, '{\"attributes\": {\"title\": \"Chunking Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:23:31', '2025-10-02 08:23:31'),
(500, 'default', 'created', 'App\\Models\\User', 'created', 158, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68de60c45a5ac@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:23:48', '2025-10-02 08:23:48'),
(501, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 73, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:23:48', '2025-10-02 08:23:48'),
(502, 'default', 'created', 'App\\Models\\User', 'created', 159, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68de613ca4c4f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:25:48', '2025-10-02 08:25:48'),
(503, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 74, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:25:48', '2025-10-02 08:25:48'),
(504, 'default', 'created', 'App\\Models\\User', 'created', 160, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68de615b50ee8@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:26:19', '2025-10-02 08:26:19'),
(505, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 75, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:26:19', '2025-10-02 08:26:19'),
(506, 'default', 'created', 'App\\Models\\User', 'created', 161, NULL, NULL, '{\"attributes\": {\"name\": \"Chunk Owner\", \"email\": \"chunk-owner-68de65b351d0d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:44:51', '2025-10-02 08:44:51'),
(507, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 76, NULL, NULL, '{\"attributes\": {\"title\": \"Chunking Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:44:51', '2025-10-02 08:44:51'),
(508, 'default', 'created', 'App\\Models\\User', 'created', 162, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68de65c1a543a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:45:05', '2025-10-02 08:45:05'),
(509, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 77, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:45:05', '2025-10-02 08:45:05'),
(510, 'default', 'created', 'App\\Models\\User', 'created', 163, NULL, NULL, '{\"attributes\": {\"name\": \"Inline Runner\", \"email\": \"inline-runner-68de65c400175@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:45:08', '2025-10-02 08:45:08'),
(511, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 78, NULL, NULL, '{\"attributes\": {\"title\": \"Inline Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:45:08', '2025-10-02 08:45:08'),
(512, 'default', 'created', 'App\\Models\\User', 'created', 164, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68de65ea380b6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:45:46', '2025-10-02 08:45:46'),
(513, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 79, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:45:46', '2025-10-02 08:45:46'),
(514, 'default', 'created', 'App\\Models\\User', 'created', 165, NULL, NULL, '{\"attributes\": {\"name\": \"Inline Runner\", \"email\": \"inline-runner-68de65ec94c69@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 08:45:48', '2025-10-02 08:45:48'),
(515, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 80, NULL, NULL, '{\"attributes\": {\"title\": \"Inline Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 08:45:48', '2025-10-02 08:45:48'),
(516, 'default', 'created', 'App\\Models\\User', 'created', 166, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-32c52a62-6a36-4944-9b5e-85c096baff4c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:26:55', '2025-10-02 09:26:55'),
(517, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 81, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:26:55', '2025-10-02 09:26:55'),
(518, 'default', 'created', 'App\\Models\\User', 'created', 167, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1165e27a-30a1-4f0a-9aa6-d36d80003b4e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:26:58', '2025-10-02 09:26:58'),
(519, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 82, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:26:58', '2025-10-02 09:26:58'),
(520, 'default', 'created', 'App\\Models\\User', 'created', 168, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-c829269b-e3c1-4217-bb7f-c057f0e99f39@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:01', '2025-10-02 09:27:01'),
(521, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 83, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:01', '2025-10-02 09:27:01'),
(522, 'default', 'created', 'App\\Models\\User', 'created', 169, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-4a49db34-ebb2-432b-95af-fffb2bf252a5@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:03', '2025-10-02 09:27:03'),
(523, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 84, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:03', '2025-10-02 09:27:03'),
(524, 'default', 'created', 'App\\Models\\User', 'created', 170, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e51e46d0-4e3c-4dea-9ecf-caa461032b66@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:45', '2025-10-02 09:27:45'),
(525, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 85, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:45', '2025-10-02 09:27:45'),
(526, 'default', 'created', 'App\\Models\\User', 'created', 171, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-542500c0-94d8-4343-8cfa-8eb74d6a1417@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:48', '2025-10-02 09:27:48'),
(527, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 86, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:48', '2025-10-02 09:27:48'),
(528, 'default', 'created', 'App\\Models\\User', 'created', 172, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-44864d3e-0b58-4ea3-bcd5-41540f6c65c6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:50', '2025-10-02 09:27:50'),
(529, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 87, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:50', '2025-10-02 09:27:50'),
(530, 'default', 'created', 'App\\Models\\User', 'created', 173, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8077e30b-06d3-4649-9fc5-92e91f44df26@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:27:53', '2025-10-02 09:27:53'),
(531, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 88, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:27:53', '2025-10-02 09:27:53'),
(532, 'default', 'created', 'App\\Models\\User', 'created', 174, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ab3cf900-20d1-4cc2-9384-b61d05631f38@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:29:41', '2025-10-02 09:29:41'),
(533, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 89, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:29:41', '2025-10-02 09:29:41'),
(534, 'default', 'created', 'App\\Models\\User', 'created', 175, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-bcc29533-c3d3-4058-be33-e7d9a057ec74@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:29:44', '2025-10-02 09:29:44'),
(535, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 90, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:29:44', '2025-10-02 09:29:44'),
(536, 'default', 'created', 'App\\Models\\User', 'created', 176, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8bbe9ec5-fc46-42b5-9f3b-29d130401177@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:29:46', '2025-10-02 09:29:46'),
(537, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 91, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:29:46', '2025-10-02 09:29:46'),
(538, 'default', 'created', 'App\\Models\\User', 'created', 177, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-da591a30-98fb-4082-af63-727cca97f186@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:29:48', '2025-10-02 09:29:48'),
(539, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 92, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:29:48', '2025-10-02 09:29:48'),
(540, 'default', 'created', 'App\\Models\\User', 'created', 178, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e062382c-98cb-449c-a59d-4a2312d5e6ba@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:31:37', '2025-10-02 09:31:37'),
(541, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 93, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:31:37', '2025-10-02 09:31:37'),
(542, 'default', 'created', 'App\\Models\\User', 'created', 179, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-9e8461af-5e34-4165-ac90-ed7719848660@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:31:39', '2025-10-02 09:31:39'),
(543, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 94, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:31:39', '2025-10-02 09:31:39'),
(544, 'default', 'created', 'App\\Models\\User', 'created', 180, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-dc8dbfcc-66b1-4099-83d2-c2956b82cf8a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:31:42', '2025-10-02 09:31:42'),
(545, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 95, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:31:42', '2025-10-02 09:31:42'),
(546, 'default', 'created', 'App\\Models\\User', 'created', 181, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8849899a-8279-4f2e-95e8-385ac28e743d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:31:44', '2025-10-02 09:31:44'),
(547, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 96, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:31:44', '2025-10-02 09:31:44'),
(548, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 97, NULL, NULL, '{\"attributes\": {\"title\": \"Debug Run\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:32:07', '2025-10-02 09:32:07'),
(549, 'default', 'created', 'App\\Models\\User', 'created', 182, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1755c477-34c7-4178-93f9-f66f4c047991@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:32:55', '2025-10-02 09:32:55'),
(550, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 98, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:32:55', '2025-10-02 09:32:55'),
(551, 'default', 'created', 'App\\Models\\User', 'created', 183, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-aa84745c-99cd-4699-b540-48daecabd95f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:32:58', '2025-10-02 09:32:58'),
(552, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 99, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:32:58', '2025-10-02 09:32:58'),
(553, 'default', 'created', 'App\\Models\\User', 'created', 184, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-243514e0-cb6c-485f-8f00-b8dd245ce51c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:33:00', '2025-10-02 09:33:00'),
(554, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 100, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:33:00', '2025-10-02 09:33:00'),
(555, 'default', 'created', 'App\\Models\\User', 'created', 185, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0dec46c7-1f3c-4463-a206-52b13cc95c00@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:33:02', '2025-10-02 09:33:02'),
(556, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 101, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:33:02', '2025-10-02 09:33:02'),
(557, 'default', 'created', 'App\\Models\\User', 'created', 186, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-c070c656-f818-45e0-b9f9-3fe1d383c81d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:35:50', '2025-10-02 09:35:50'),
(558, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 102, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:35:50', '2025-10-02 09:35:50'),
(559, 'default', 'created', 'App\\Models\\User', 'created', 187, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-762e57c7-d6a9-4029-8e5f-bd5674f7faa4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:37:40', '2025-10-02 09:37:40'),
(560, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 103, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:37:40', '2025-10-02 09:37:40'),
(561, 'default', 'created', 'App\\Models\\User', 'created', 188, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e770ddd8-22e6-4e29-b413-aaa38b14c071@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:38:28', '2025-10-02 09:38:28'),
(562, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 104, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:38:28', '2025-10-02 09:38:28'),
(563, 'default', 'created', 'App\\Models\\User', 'created', 189, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0e000d76-5caa-4587-993f-cfc210610289@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:44:59', '2025-10-02 09:44:59'),
(564, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 105, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:44:59', '2025-10-02 09:44:59'),
(565, 'default', 'created', 'App\\Models\\User', 'created', 190, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-cd8ba4bc-fa24-4bd1-83c3-f0058474fef4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:45:01', '2025-10-02 09:45:01'),
(566, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 106, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:45:01', '2025-10-02 09:45:01'),
(567, 'default', 'created', 'App\\Models\\User', 'created', 191, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e349c2d5-8352-4ce4-9985-66bf822a12f6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:45:03', '2025-10-02 09:45:03'),
(568, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 107, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:45:03', '2025-10-02 09:45:03'),
(569, 'default', 'created', 'App\\Models\\User', 'created', 192, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-85aca201-2e79-4462-b088-7a282efe8c9e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:45:06', '2025-10-02 09:45:06'),
(570, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 108, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:45:06', '2025-10-02 09:45:06'),
(571, 'default', 'created', 'App\\Models\\User', 'created', 193, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-d5913c02-c99c-4008-949a-1d3bc7cd1a3e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:49:53', '2025-10-02 09:49:53'),
(572, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 109, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:49:53', '2025-10-02 09:49:53'),
(573, 'default', 'created', 'App\\Models\\User', 'created', 194, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-bfe34cae-38f3-4f3c-97f9-30b25d31d115@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:49:56', '2025-10-02 09:49:56'),
(574, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 110, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:49:56', '2025-10-02 09:49:56'),
(575, 'default', 'created', 'App\\Models\\User', 'created', 195, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-072bf539-1673-48b2-99da-f483ddb44e2d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:49:58', '2025-10-02 09:49:58'),
(576, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 111, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:49:58', '2025-10-02 09:49:58'),
(577, 'default', 'created', 'App\\Models\\User', 'created', 196, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b939607d-f2e1-4569-a911-c9c2c1ad0470@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 09:50:00', '2025-10-02 09:50:00'),
(578, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 112, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 09:50:00', '2025-10-02 09:50:00'),
(579, 'default', 'created', 'App\\Models\\User', 'created', 197, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-47256919-bf13-442a-9700-416695e173cf@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:05:50', '2025-10-02 13:05:50'),
(580, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 113, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:05:50', '2025-10-02 13:05:50');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(581, 'default', 'created', 'App\\Models\\User', 'created', 198, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-10320278-65b8-4d53-87f2-980899b5b4db@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:05:53', '2025-10-02 13:05:53'),
(582, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 114, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:05:53', '2025-10-02 13:05:53'),
(583, 'default', 'created', 'App\\Models\\User', 'created', 199, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0f8e6a3f-f2fc-4b8d-b6b3-d5532ed3f06f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:05:55', '2025-10-02 13:05:55'),
(584, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 115, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:05:55', '2025-10-02 13:05:55'),
(585, 'default', 'created', 'App\\Models\\User', 'created', 200, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e738ce06-f28a-44c9-8dbf-6fb673db076f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:05:57', '2025-10-02 13:05:57'),
(586, 'default', 'created', 'App\\Models\\User', 'created', 201, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-dd3cc72e-1002-42f9-b912-934f8ea058c4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:05:59', '2025-10-02 13:05:59'),
(587, 'default', 'created', 'App\\Models\\User', 'created', 202, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-412fb33b-18ae-49da-a361-f8d2ae871f41@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:06:01', '2025-10-02 13:06:01'),
(588, 'default', 'created', 'App\\Models\\User', 'created', 203, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-03cd74dc-b1c9-4bd3-b8a0-01f877398459@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:06:03', '2025-10-02 13:06:03'),
(589, 'default', 'created', 'App\\Models\\User', 'created', 204, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5cc141e8-9baf-4e8d-bd84-3c3ea893fba6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:06:05', '2025-10-02 13:06:05'),
(590, 'default', 'created', 'App\\Models\\User', 'created', 205, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-c75fbc54-4dad-4ac0-bd65-27301f43b43e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:06:07', '2025-10-02 13:06:07'),
(591, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 116, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:06:07', '2025-10-02 13:06:07'),
(592, 'default', 'created', 'App\\Models\\User', 'created', 206, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-71e4367f-dfba-45de-9637-80e997feaef0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:41', '2025-10-02 13:07:41'),
(593, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 117, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:41', '2025-10-02 13:07:41'),
(594, 'default', 'created', 'App\\Models\\User', 'created', 207, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7086a968-f8bc-4e68-ac79-2715f474f5d2@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:43', '2025-10-02 13:07:43'),
(595, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 118, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:43', '2025-10-02 13:07:43'),
(596, 'default', 'created', 'App\\Models\\User', 'created', 208, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e646540c-653a-4f5a-a8e9-db91df31e097@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:45', '2025-10-02 13:07:45'),
(597, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 119, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:45', '2025-10-02 13:07:45'),
(598, 'default', 'created', 'App\\Models\\User', 'created', 209, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e0e915e7-8dfc-4c64-a3d2-63053ecbfa31@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:47', '2025-10-02 13:07:47'),
(599, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 120, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:47', '2025-10-02 13:07:47'),
(600, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 120, 'App\\Models\\User', 209, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:07:47', '2025-10-02 13:07:47'),
(601, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 120, 'App\\Models\\User', 209, '[]', NULL, '2025-10-02 13:07:47', '2025-10-02 13:07:47'),
(602, 'default', 'created', 'App\\Models\\User', 'created', 210, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-57862e33-3481-49d3-9933-57dbff2857a4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:49', '2025-10-02 13:07:49'),
(603, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 121, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:49', '2025-10-02 13:07:49'),
(604, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 121, 'App\\Models\\User', 210, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:07:49', '2025-10-02 13:07:49'),
(605, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 121, 'App\\Models\\User', 210, '[]', NULL, '2025-10-02 13:07:49', '2025-10-02 13:07:49'),
(606, 'default', 'created', 'App\\Models\\User', 'created', 211, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-869901c8-0316-478e-bf96-8f3b7b126a46@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:51', '2025-10-02 13:07:51'),
(607, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 122, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:51', '2025-10-02 13:07:51'),
(608, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 122, 'App\\Models\\User', 211, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:07:51', '2025-10-02 13:07:51'),
(609, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 122, 'App\\Models\\User', 211, '[]', NULL, '2025-10-02 13:07:51', '2025-10-02 13:07:51'),
(610, 'default', 'created', 'App\\Models\\User', 'created', 212, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-731708ab-c50a-4e66-a73f-968c09f298c5@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:53', '2025-10-02 13:07:53'),
(611, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 123, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:53', '2025-10-02 13:07:53'),
(612, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 123, 'App\\Models\\User', 212, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:07:53', '2025-10-02 13:07:53'),
(613, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 123, 'App\\Models\\User', 212, '[]', NULL, '2025-10-02 13:07:53', '2025-10-02 13:07:53'),
(614, 'default', 'created', 'App\\Models\\User', 'created', 213, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-d5752f43-75c0-4404-a23f-71c495fd9559@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:55', '2025-10-02 13:07:55'),
(615, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 124, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:55', '2025-10-02 13:07:55'),
(616, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 124, 'App\\Models\\User', 213, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:07:56', '2025-10-02 13:07:56'),
(617, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 124, 'App\\Models\\User', 213, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:07:56', '2025-10-02 13:07:56'),
(618, 'default', 'created', 'App\\Models\\User', 'created', 214, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-32c5a13f-3e6c-478b-a9b0-315b1f6f24f9@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:07:58', '2025-10-02 13:07:58'),
(619, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 125, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:07:58', '2025-10-02 13:07:58'),
(620, 'default', 'created', 'App\\Models\\User', 'created', 215, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-93e24aca-3d22-49a9-aab9-0da24f7698ea@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:12', '2025-10-02 13:15:12'),
(621, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 126, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:12', '2025-10-02 13:15:12'),
(622, 'default', 'created', 'App\\Models\\User', 'created', 216, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0841b6ff-4b53-4926-9cc3-e292885e1994@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:14', '2025-10-02 13:15:14'),
(623, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 127, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:14', '2025-10-02 13:15:14'),
(624, 'default', 'created', 'App\\Models\\User', 'created', 217, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-2e572b5b-c89f-45dc-9c1d-2e5efea0d3d4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:16', '2025-10-02 13:15:16'),
(625, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 128, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:16', '2025-10-02 13:15:16'),
(626, 'default', 'created', 'App\\Models\\User', 'created', 218, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b4fcf925-5d41-4db3-93a1-41bc03204eb0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:18', '2025-10-02 13:15:18'),
(627, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 129, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:18', '2025-10-02 13:15:18'),
(628, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 129, 'App\\Models\\User', 218, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:15:18', '2025-10-02 13:15:18'),
(629, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 129, 'App\\Models\\User', 218, '[]', NULL, '2025-10-02 13:15:18', '2025-10-02 13:15:18'),
(630, 'default', 'created', 'App\\Models\\User', 'created', 219, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-adac8b23-e5c7-4e37-9598-6257878afa74@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:20', '2025-10-02 13:15:20'),
(631, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 130, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:20', '2025-10-02 13:15:20'),
(632, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 130, 'App\\Models\\User', 219, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:15:20', '2025-10-02 13:15:20'),
(633, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 130, 'App\\Models\\User', 219, '[]', NULL, '2025-10-02 13:15:20', '2025-10-02 13:15:20'),
(634, 'default', 'created', 'App\\Models\\User', 'created', 220, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ea054b08-1f58-4c3d-abfa-a0f84cbd15ba@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:22', '2025-10-02 13:15:22'),
(635, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 131, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:22', '2025-10-02 13:15:22'),
(636, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 131, 'App\\Models\\User', 220, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:15:22', '2025-10-02 13:15:22'),
(637, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 131, 'App\\Models\\User', 220, '[]', NULL, '2025-10-02 13:15:22', '2025-10-02 13:15:22'),
(638, 'default', 'created', 'App\\Models\\User', 'created', 221, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-bde61a59-51de-4f44-ab79-e7fae5648646@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:25', '2025-10-02 13:15:25'),
(639, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 132, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:25', '2025-10-02 13:15:25'),
(640, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 132, 'App\\Models\\User', 221, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:15:25', '2025-10-02 13:15:25'),
(641, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 132, 'App\\Models\\User', 221, '[]', NULL, '2025-10-02 13:15:25', '2025-10-02 13:15:25'),
(642, 'default', 'created', 'App\\Models\\User', 'created', 222, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-af42c00e-6d95-481e-b06b-2c5f9e47b502@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:27', '2025-10-02 13:15:27'),
(643, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 133, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:27', '2025-10-02 13:15:27'),
(644, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 133, 'App\\Models\\User', 222, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:15:27', '2025-10-02 13:15:27'),
(645, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 133, 'App\\Models\\User', 222, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:15:27', '2025-10-02 13:15:27'),
(646, 'default', 'created', 'App\\Models\\User', 'created', 223, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-c5b080dc-062a-4242-8d3e-46a4ae1829a1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:15:29', '2025-10-02 13:15:29'),
(647, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 134, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:15:29', '2025-10-02 13:15:29'),
(648, 'default', 'created', 'App\\Models\\User', 'created', 224, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-123f7fda-57e0-467e-b799-7179d6073c9b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:16', '2025-10-02 13:27:16'),
(649, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 135, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:16', '2025-10-02 13:27:16'),
(650, 'default', 'created', 'App\\Models\\User', 'created', 225, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ca2f562b-d936-4d90-99f2-b6774d462f68@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:18', '2025-10-02 13:27:18'),
(651, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 136, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:18', '2025-10-02 13:27:18'),
(652, 'default', 'created', 'App\\Models\\User', 'created', 226, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ccb93db2-7b02-44a3-8ef7-846dc1ed1424@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:20', '2025-10-02 13:27:20'),
(653, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 137, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:20', '2025-10-02 13:27:20'),
(654, 'default', 'created', 'App\\Models\\User', 'created', 227, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8b2c5915-523e-4af9-9710-d190b88dcbd9@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:22', '2025-10-02 13:27:22'),
(655, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 138, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:22', '2025-10-02 13:27:22'),
(656, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 138, 'App\\Models\\User', 227, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:27:22', '2025-10-02 13:27:22'),
(657, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 138, 'App\\Models\\User', 227, '[]', NULL, '2025-10-02 13:27:22', '2025-10-02 13:27:22'),
(658, 'default', 'created', 'App\\Models\\User', 'created', 228, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-64993ac1-7576-4e84-9b7b-86d8e1ef82c1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:25', '2025-10-02 13:27:25'),
(659, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 139, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:25', '2025-10-02 13:27:25'),
(660, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 139, 'App\\Models\\User', 228, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:27:25', '2025-10-02 13:27:25'),
(661, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 139, 'App\\Models\\User', 228, '[]', NULL, '2025-10-02 13:27:25', '2025-10-02 13:27:25'),
(662, 'default', 'created', 'App\\Models\\User', 'created', 229, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-99c8ee7d-5630-4ad8-8eea-ece743ff0f6c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:27', '2025-10-02 13:27:27'),
(663, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 140, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:27', '2025-10-02 13:27:27'),
(664, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 140, 'App\\Models\\User', 229, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:27:27', '2025-10-02 13:27:27'),
(665, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 140, 'App\\Models\\User', 229, '[]', NULL, '2025-10-02 13:27:27', '2025-10-02 13:27:27'),
(666, 'default', 'created', 'App\\Models\\User', 'created', 230, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7875f746-8077-4120-a3f2-ef09388a85e2@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:29', '2025-10-02 13:27:29'),
(667, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 141, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:29', '2025-10-02 13:27:29'),
(668, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 141, 'App\\Models\\User', 230, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:27:29', '2025-10-02 13:27:29'),
(669, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 141, 'App\\Models\\User', 230, '[]', NULL, '2025-10-02 13:27:29', '2025-10-02 13:27:29'),
(670, 'default', 'created', 'App\\Models\\User', 'created', 231, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b6bd85e5-5729-47da-9c5f-5d1116253883@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:31', '2025-10-02 13:27:31'),
(671, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 142, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:31', '2025-10-02 13:27:31'),
(672, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 142, 'App\\Models\\User', 231, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:27:31', '2025-10-02 13:27:31'),
(673, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 142, 'App\\Models\\User', 231, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:27:31', '2025-10-02 13:27:31'),
(674, 'default', 'created', 'App\\Models\\User', 'created', 232, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-04db90e8-cfc9-4122-85e8-239ee7110b43@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(675, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 143, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(676, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 144, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(677, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 145, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(678, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 146, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(679, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 147, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:33', '2025-10-02 13:27:33'),
(680, 'default', 'created', 'App\\Models\\User', 'created', 233, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-6904e699-7bd0-478a-8dca-988215b33a5c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:27:35', '2025-10-02 13:27:35'),
(681, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 148, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:27:35', '2025-10-02 13:27:35'),
(682, 'default', 'created', 'App\\Models\\User', 'created', 234, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-835478fe-a03b-459c-94ed-2e4481069d1e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:10', '2025-10-02 13:28:10'),
(683, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 149, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:10', '2025-10-02 13:28:10'),
(684, 'default', 'created', 'App\\Models\\User', 'created', 235, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-a20bcc8d-cd9d-48b0-92cd-aa8dc4f07c0f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:13', '2025-10-02 13:28:13'),
(685, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 150, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:13', '2025-10-02 13:28:13'),
(686, 'default', 'created', 'App\\Models\\User', 'created', 236, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5a2704b7-d2a5-427a-9c9b-a80463190b05@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:15', '2025-10-02 13:28:15'),
(687, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 151, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:15', '2025-10-02 13:28:15'),
(688, 'default', 'created', 'App\\Models\\User', 'created', 237, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-69e3e210-398f-416b-a022-f08b2287a923@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:17', '2025-10-02 13:28:17'),
(689, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 152, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:17', '2025-10-02 13:28:17'),
(690, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 152, 'App\\Models\\User', 237, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:28:17', '2025-10-02 13:28:17'),
(691, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 152, 'App\\Models\\User', 237, '[]', NULL, '2025-10-02 13:28:17', '2025-10-02 13:28:17'),
(692, 'default', 'created', 'App\\Models\\User', 'created', 238, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8e27ff03-9d29-43d9-b191-c477768d905c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:19', '2025-10-02 13:28:19'),
(693, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 153, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:19', '2025-10-02 13:28:19'),
(694, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 153, 'App\\Models\\User', 238, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:28:19', '2025-10-02 13:28:19'),
(695, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 153, 'App\\Models\\User', 238, '[]', NULL, '2025-10-02 13:28:19', '2025-10-02 13:28:19'),
(696, 'default', 'created', 'App\\Models\\User', 'created', 239, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-fb8d7700-1423-4f82-af36-617a03b6447e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:21', '2025-10-02 13:28:21'),
(697, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 154, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:21', '2025-10-02 13:28:21'),
(698, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 154, 'App\\Models\\User', 239, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:28:21', '2025-10-02 13:28:21'),
(699, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 154, 'App\\Models\\User', 239, '[]', NULL, '2025-10-02 13:28:21', '2025-10-02 13:28:21'),
(700, 'default', 'created', 'App\\Models\\User', 'created', 240, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-6ba96f6a-7d35-4f77-85a1-c3b3d15db45e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:23', '2025-10-02 13:28:23'),
(701, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 155, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:23', '2025-10-02 13:28:23'),
(702, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 155, 'App\\Models\\User', 240, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:28:23', '2025-10-02 13:28:23'),
(703, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 155, 'App\\Models\\User', 240, '[]', NULL, '2025-10-02 13:28:23', '2025-10-02 13:28:23'),
(704, 'default', 'created', 'App\\Models\\User', 'created', 241, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-d627a65f-53e6-4171-896f-36d217f4df5b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:25', '2025-10-02 13:28:25'),
(705, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 156, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:25', '2025-10-02 13:28:25'),
(706, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 156, 'App\\Models\\User', 241, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:28:25', '2025-10-02 13:28:25'),
(707, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 156, 'App\\Models\\User', 241, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:28:25', '2025-10-02 13:28:25'),
(708, 'default', 'created', 'App\\Models\\User', 'created', 242, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-976dee73-e1a9-4f22-b6fd-d283615fe91f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(709, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 157, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(710, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 158, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(711, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 159, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(712, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 160, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(713, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 161, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:27', '2025-10-02 13:28:27'),
(714, 'default', 'created', 'App\\Models\\User', 'created', 243, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-bfdb73b9-5f90-4594-98c1-5033f5d2781f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:28:29', '2025-10-02 13:28:29'),
(715, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 162, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:28:29', '2025-10-02 13:28:29'),
(716, 'default', 'created', 'App\\Models\\User', 'created', 244, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-4566fb20-b4a4-4ce0-9cda-932ccfee887f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:34', '2025-10-02 13:29:34'),
(717, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 163, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:34', '2025-10-02 13:29:34'),
(718, 'default', 'created', 'App\\Models\\User', 'created', 245, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0d329c7b-62da-418c-abc1-821c749fedda@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:36', '2025-10-02 13:29:36'),
(719, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 164, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:36', '2025-10-02 13:29:36'),
(720, 'default', 'created', 'App\\Models\\User', 'created', 246, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-9b9f11af-4d5f-4098-ac97-6ec7b0a226e0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:38', '2025-10-02 13:29:38'),
(721, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 165, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:38', '2025-10-02 13:29:38'),
(722, 'default', 'created', 'App\\Models\\User', 'created', 247, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-286c6e15-78e2-4426-b196-4c002d07214b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:41', '2025-10-02 13:29:41'),
(723, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 166, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:41', '2025-10-02 13:29:41'),
(724, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 166, 'App\\Models\\User', 247, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:29:41', '2025-10-02 13:29:41'),
(725, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 166, 'App\\Models\\User', 247, '[]', NULL, '2025-10-02 13:29:41', '2025-10-02 13:29:41'),
(726, 'default', 'created', 'App\\Models\\User', 'created', 248, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-31a07033-df1a-47de-ba08-49fb965d143a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:43', '2025-10-02 13:29:43');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(727, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 167, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:43', '2025-10-02 13:29:43'),
(728, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 167, 'App\\Models\\User', 248, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:29:43', '2025-10-02 13:29:43'),
(729, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 167, 'App\\Models\\User', 248, '[]', NULL, '2025-10-02 13:29:43', '2025-10-02 13:29:43'),
(730, 'default', 'created', 'App\\Models\\User', 'created', 249, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-17679a1e-84c9-4f88-9d79-1adcfd54008a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:45', '2025-10-02 13:29:45'),
(731, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 168, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:45', '2025-10-02 13:29:45'),
(732, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 168, 'App\\Models\\User', 249, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:29:45', '2025-10-02 13:29:45'),
(733, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 168, 'App\\Models\\User', 249, '[]', NULL, '2025-10-02 13:29:45', '2025-10-02 13:29:45'),
(734, 'default', 'created', 'App\\Models\\User', 'created', 250, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-2bd5539a-2d0f-4cae-aa91-420100c0fed3@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:47', '2025-10-02 13:29:47'),
(735, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 169, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:47', '2025-10-02 13:29:47'),
(736, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 169, 'App\\Models\\User', 250, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:29:47', '2025-10-02 13:29:47'),
(737, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 169, 'App\\Models\\User', 250, '[]', NULL, '2025-10-02 13:29:47', '2025-10-02 13:29:47'),
(738, 'default', 'created', 'App\\Models\\User', 'created', 251, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-faa70161-c726-4b67-97cc-6ef6aeb5e87e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:49', '2025-10-02 13:29:49'),
(739, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 170, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:49', '2025-10-02 13:29:49'),
(740, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 170, 'App\\Models\\User', 251, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:29:49', '2025-10-02 13:29:49'),
(741, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 170, 'App\\Models\\User', 251, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:29:49', '2025-10-02 13:29:49'),
(742, 'default', 'created', 'App\\Models\\User', 'created', 252, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-e5bcac82-3dfc-48c7-91b5-b50ecb743ea0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(743, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 171, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(744, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 172, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(745, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 173, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(746, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 174, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(747, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 175, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:51', '2025-10-02 13:29:51'),
(748, 'default', 'created', 'App\\Models\\User', 'created', 253, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-c30659d5-21ba-4b1c-9d52-d3ffb9a63449@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:29:53', '2025-10-02 13:29:53'),
(749, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 176, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:29:53', '2025-10-02 13:29:53'),
(750, 'default', 'created', 'App\\Models\\User', 'created', 254, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-45b8df19-f15b-4630-b9d8-b31c4c3c9254@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:35', '2025-10-02 13:30:35'),
(751, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 177, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:35', '2025-10-02 13:30:35'),
(752, 'default', 'created', 'App\\Models\\User', 'created', 255, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-510451bf-f670-4056-81e6-347c06386145@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:37', '2025-10-02 13:30:37'),
(753, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 178, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:37', '2025-10-02 13:30:37'),
(754, 'default', 'created', 'App\\Models\\User', 'created', 256, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5a4d84e1-0e2b-4b25-97d5-dd4ee8a1d6ff@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:39', '2025-10-02 13:30:39'),
(755, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 179, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:39', '2025-10-02 13:30:39'),
(756, 'default', 'created', 'App\\Models\\User', 'created', 257, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1f5ac5d6-30b3-4638-b301-a3e4c5ee1c10@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:41', '2025-10-02 13:30:41'),
(757, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 180, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:41', '2025-10-02 13:30:41'),
(758, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 180, 'App\\Models\\User', 257, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 13:30:41', '2025-10-02 13:30:41'),
(759, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 180, 'App\\Models\\User', 257, '[]', NULL, '2025-10-02 13:30:41', '2025-10-02 13:30:41'),
(760, 'default', 'created', 'App\\Models\\User', 'created', 258, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-f807623e-af06-4790-a04c-c1c51de3a803@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:44', '2025-10-02 13:30:44'),
(761, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 181, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:44', '2025-10-02 13:30:44'),
(762, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 181, 'App\\Models\\User', 258, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 13:30:44', '2025-10-02 13:30:44'),
(763, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 181, 'App\\Models\\User', 258, '[]', NULL, '2025-10-02 13:30:44', '2025-10-02 13:30:44'),
(764, 'default', 'created', 'App\\Models\\User', 'created', 259, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-b0bd50e1-99d1-43e4-b434-a34919dd9d6c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:46', '2025-10-02 13:30:46'),
(765, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 182, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:46', '2025-10-02 13:30:46'),
(766, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 182, 'App\\Models\\User', 259, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 13:30:46', '2025-10-02 13:30:46'),
(767, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 182, 'App\\Models\\User', 259, '[]', NULL, '2025-10-02 13:30:46', '2025-10-02 13:30:46'),
(768, 'default', 'created', 'App\\Models\\User', 'created', 260, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-83577938-7972-4bf9-8e2f-8d7465d1ff58@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:48', '2025-10-02 13:30:48'),
(769, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 183, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:48', '2025-10-02 13:30:48'),
(770, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 183, 'App\\Models\\User', 260, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 13:30:48', '2025-10-02 13:30:48'),
(771, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 183, 'App\\Models\\User', 260, '[]', NULL, '2025-10-02 13:30:48', '2025-10-02 13:30:48'),
(772, 'default', 'created', 'App\\Models\\User', 'created', 261, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5043eade-5bb5-4bd2-96f1-aee0eed9e9d1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:50', '2025-10-02 13:30:50'),
(773, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 184, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:50', '2025-10-02 13:30:50'),
(774, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 184, 'App\\Models\\User', 261, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 13:30:50', '2025-10-02 13:30:50'),
(775, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 184, 'App\\Models\\User', 261, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 13:30:50', '2025-10-02 13:30:50'),
(776, 'default', 'created', 'App\\Models\\User', 'created', 262, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-a7ff9b11-c5f0-4542-81d5-cac8c88d060e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(777, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 185, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(778, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 186, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(779, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 187, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(780, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 188, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(781, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 189, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:52', '2025-10-02 13:30:52'),
(782, 'default', 'created', 'App\\Models\\User', 'created', 263, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-261b23fe-242e-45b8-92c3-a59201877c61@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 13:30:54', '2025-10-02 13:30:54'),
(783, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 190, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 13:30:54', '2025-10-02 13:30:54'),
(784, 'default', 'created', 'App\\Models\\User', 'created', 264, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68deb205b19ec@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:30', '2025-10-02 14:10:30'),
(785, 'default', 'created', 'App\\Models\\Department', 'created', 79, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 14:10:30', '2025-10-02 14:10:30'),
(786, 'default', 'created', 'App\\Models\\Position', 'created', 71, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 14:10:30', '2025-10-02 14:10:30'),
(787, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 56, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68deb206e6a01\"}}', NULL, '2025-10-02 14:10:30', '2025-10-02 14:10:30'),
(788, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 46, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68deb206f0082\", \"email\": \"employee-68deb206f0084@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 71, \"department_id\": 79, \"photo_uploaded_at\": null, \"employment_type_id\": 56, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(789, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 191, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(790, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 26, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68deb206f0082\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(791, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 34, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(792, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 35, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(793, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 36, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 14:10:31', '2025-10-02 14:10:31'),
(794, 'default', 'created', 'App\\Models\\User', 'created', 265, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68deb20eb39ef@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(795, 'default', 'created', 'App\\Models\\User', 'created', 266, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68deb20eb695c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(796, 'default', 'created', 'App\\Models\\Department', 'created', 80, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(797, 'default', 'created', 'App\\Models\\Position', 'created', 72, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(798, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 57, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68deb20eb8920\"}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(799, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 47, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68deb20eb8ffa\", \"email\": \"employee-68deb20eb8ffc@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 72, \"department_id\": 80, \"photo_uploaded_at\": null, \"employment_type_id\": 57, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(800, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 192, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(801, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 27, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68deb20eb8ffa\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(802, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 37, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(803, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 38, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(804, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 39, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 14:10:38', '2025-10-02 14:10:38'),
(805, 'default', 'created', 'App\\Models\\User', 'created', 267, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68deb210ef838@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(806, 'default', 'created', 'App\\Models\\Department', 'created', 81, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(807, 'default', 'created', 'App\\Models\\Position', 'created', 73, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(808, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 58, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68deb210f1cd9\"}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(809, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 48, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68deb210f2211\", \"email\": \"employee-68deb210f2213@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 73, \"department_id\": 81, \"photo_uploaded_at\": null, \"employment_type_id\": 58, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(810, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 193, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(811, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 28, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68deb210f2211\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(812, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 40, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC\", \"name_ar\": \"Basic Salary AR\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-02 14:10:40', '2025-10-02 14:10:40'),
(813, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 41, NULL, NULL, '{\"attributes\": {\"code\": \"TAX\", \"name_ar\": \"Tax Deduction AR\", \"name_en\": \"Tax Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-02 14:10:41', '2025-10-02 14:10:41'),
(814, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 42, NULL, NULL, '{\"attributes\": {\"code\": \"INFO\", \"name_ar\": \"Information Note AR\", \"name_en\": \"Information Note\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 3}}', NULL, '2025-10-02 14:10:41', '2025-10-02 14:10:41'),
(815, 'default', 'created', 'App\\Models\\User', 'created', 268, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68deb21c94579@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(816, 'default', 'created', 'App\\Models\\Department', 'created', 82, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(817, 'default', 'created', 'App\\Models\\Position', 'created', 74, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(818, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 59, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68deb21c9a831\"}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(819, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 49, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68deb21c9b158\", \"email\": \"employee-68deb21c9b159@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 74, \"department_id\": 82, \"photo_uploaded_at\": null, \"employment_type_id\": 59, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(820, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 194, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(821, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 29, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68deb21c9b158\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 14:10:52', '2025-10-02 14:10:52'),
(822, 'default', 'created', 'App\\Models\\User', 'created', 269, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68deb21f3625d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(823, 'default', 'created', 'App\\Models\\User', 'created', 270, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68deb21f374c0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(824, 'default', 'created', 'App\\Models\\Department', 'created', 83, NULL, NULL, '{\"attributes\": {\"code\": \"FIN\", \"name_ar\": \"Finance AR\", \"name_en\": \"Finance\"}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(825, 'default', 'created', 'App\\Models\\Position', 'created', 75, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Accountant AR\", \"name_en\": \"Accountant\", \"category\": \"lawyer\", \"grade_order\": 1}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(826, 'default', 'created', 'App\\Models\\EmploymentType', 'created', 60, NULL, NULL, '{\"attributes\": {\"name_ar\": \"Permanent AR\", \"name_en\": \"Permanent 68deb21f392f2\"}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(827, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 50, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68deb21f398d7\", \"email\": \"employee-68deb21f398d9@example.com\", \"phone\": \"0100000000\", \"status\": \"active\", \"hire_date\": \"2024-10-02T00:00:00.000000Z\", \"last_name\": \"Doe\", \"first_name\": \"Jane\", \"manager_id\": null, \"arabic_name\": \"Jane Doe AR\", \"position_id\": 75, \"department_id\": 83, \"photo_uploaded_at\": null, \"employment_type_id\": 60, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(828, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 195, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"4500.00\", \"total_gross\": \"5000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(829, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 30, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"4500.00\", \"gross_pay\": \"5000.00\", \"employee_code\": \"EMP-68deb21f398d7\", \"employee_name\": \"Jane Doe\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"total_deductions\": \"500.00\"}}', NULL, '2025-10-02 14:10:55', '2025-10-02 14:10:55'),
(830, 'default', 'created', 'App\\Models\\User', 'created', 271, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7d1efa89-82cb-4bd2-af51-73ccdafee1f6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:21', '2025-10-02 14:43:21'),
(831, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 196, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:21', '2025-10-02 14:43:21'),
(832, 'default', 'created', 'App\\Models\\User', 'created', 272, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-60fd0daa-be5d-485b-9407-dd5b419c661a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:24', '2025-10-02 14:43:24'),
(833, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 197, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:24', '2025-10-02 14:43:24'),
(834, 'default', 'created', 'App\\Models\\User', 'created', 273, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-2effafe8-fd5b-4434-b376-9073bb68eb0e@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:26', '2025-10-02 14:43:26'),
(835, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 198, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:26', '2025-10-02 14:43:26'),
(836, 'default', 'created', 'App\\Models\\User', 'created', 274, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5405f968-1298-4298-a501-0c241985d967@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:28', '2025-10-02 14:43:28'),
(837, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 199, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:28', '2025-10-02 14:43:28'),
(838, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 199, 'App\\Models\\User', 274, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 14:43:28', '2025-10-02 14:43:28'),
(839, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 199, 'App\\Models\\User', 274, '[]', NULL, '2025-10-02 14:43:28', '2025-10-02 14:43:28'),
(840, 'default', 'created', 'App\\Models\\User', 'created', 275, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-595b751d-b409-459e-bbe1-4e3cf7f30f0b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:30', '2025-10-02 14:43:30'),
(841, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 200, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:30', '2025-10-02 14:43:30'),
(842, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 200, 'App\\Models\\User', 275, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 14:43:30', '2025-10-02 14:43:30'),
(843, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 200, 'App\\Models\\User', 275, '[]', NULL, '2025-10-02 14:43:30', '2025-10-02 14:43:30'),
(844, 'default', 'created', 'App\\Models\\User', 'created', 276, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-f5c74f95-61b6-4d3e-a154-a92fd0ae801c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:32', '2025-10-02 14:43:32'),
(845, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 201, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:32', '2025-10-02 14:43:32'),
(846, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 201, 'App\\Models\\User', 276, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 14:43:32', '2025-10-02 14:43:32'),
(847, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 201, 'App\\Models\\User', 276, '[]', NULL, '2025-10-02 14:43:32', '2025-10-02 14:43:32'),
(848, 'default', 'created', 'App\\Models\\User', 'created', 277, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8efad1e3-61fe-4268-8346-9a672c1c2935@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:34', '2025-10-02 14:43:34'),
(849, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 202, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:34', '2025-10-02 14:43:34'),
(850, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 202, 'App\\Models\\User', 277, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 14:43:34', '2025-10-02 14:43:34'),
(851, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 202, 'App\\Models\\User', 277, '[]', NULL, '2025-10-02 14:43:34', '2025-10-02 14:43:34'),
(852, 'default', 'created', 'App\\Models\\User', 'created', 278, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-dad47b31-132e-4b55-86d3-5854b54f5a2c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:37', '2025-10-02 14:43:37'),
(853, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 203, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:37', '2025-10-02 14:43:37'),
(854, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 203, 'App\\Models\\User', 278, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 14:43:37', '2025-10-02 14:43:37'),
(855, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 203, 'App\\Models\\User', 278, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 14:43:37', '2025-10-02 14:43:37'),
(856, 'default', 'created', 'App\\Models\\User', 'created', 279, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-1ae4b692-7fff-412e-a8fa-8ee7e317aca7@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(857, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 204, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(858, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 205, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(859, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 206, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(860, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 207, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(861, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 208, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:39', '2025-10-02 14:43:39'),
(862, 'default', 'created', 'App\\Models\\User', 'created', 280, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator\", \"email\": \"coordinator-9bb47f43-9c24-4bdc-b97e-6e59d96978b5@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:41', '2025-10-02 14:43:41'),
(863, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 209, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"800.00\", \"total_gross\": \"1000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:41', '2025-10-02 14:43:41'),
(864, 'default', 'created', 'App\\Models\\User', 'created', 281, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-9466acd0-30da-4f29-bb5b-86e632eade83@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 14:43:44', '2025-10-02 14:43:44'),
(865, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 210, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 14:43:44', '2025-10-02 14:43:44'),
(866, 'authentication', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-02 14:50:16', '2025-10-02 14:50:16'),
(867, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-02 14:50:22', '2025-10-02 14:50:22'),
(868, 'authentication', 'User logged out', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-02 14:52:32', '2025-10-02 14:52:32'),
(869, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-02 14:53:32', '2025-10-02 14:53:32');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(870, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-02 14:54:29', '2025-10-02 14:54:29'),
(871, 'default', 'created', 'App\\Models\\User', 'created', 282, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-361ea345-85d4-4df8-87dc-573cd66d8aaa@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:40', '2025-10-02 15:20:40'),
(872, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 211, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:40', '2025-10-02 15:20:40'),
(873, 'default', 'created', 'App\\Models\\User', 'created', 283, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-f8642567-9063-4f8f-b243-d9ca019f2683@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:42', '2025-10-02 15:20:42'),
(874, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 212, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:42', '2025-10-02 15:20:42'),
(875, 'default', 'created', 'App\\Models\\User', 'created', 284, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-eca5a3c2-e325-4487-863e-da69347dc0bd@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:44', '2025-10-02 15:20:44'),
(876, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 213, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:44', '2025-10-02 15:20:44'),
(877, 'default', 'created', 'App\\Models\\User', 'created', 285, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ce7370cd-1c7a-4ac8-b7d5-cb769d1dfee4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:47', '2025-10-02 15:20:47'),
(878, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 214, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:47', '2025-10-02 15:20:47'),
(879, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 214, 'App\\Models\\User', 285, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-02 15:20:47', '2025-10-02 15:20:47'),
(880, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 214, 'App\\Models\\User', 285, '[]', NULL, '2025-10-02 15:20:47', '2025-10-02 15:20:47'),
(881, 'default', 'created', 'App\\Models\\User', 'created', 286, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7ff7fedf-de0a-454f-b119-1bb888c9337c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:49', '2025-10-02 15:20:49'),
(882, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 215, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:49', '2025-10-02 15:20:49'),
(883, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 215, 'App\\Models\\User', 286, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-02 15:20:49', '2025-10-02 15:20:49'),
(884, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 215, 'App\\Models\\User', 286, '[]', NULL, '2025-10-02 15:20:49', '2025-10-02 15:20:49'),
(885, 'default', 'created', 'App\\Models\\User', 'created', 287, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ce2681fa-7d65-405e-8aaa-fc35e0885db0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:51', '2025-10-02 15:20:51'),
(886, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 216, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:51', '2025-10-02 15:20:51'),
(887, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 216, 'App\\Models\\User', 287, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-02 15:20:51', '2025-10-02 15:20:51'),
(888, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 216, 'App\\Models\\User', 287, '[]', NULL, '2025-10-02 15:20:51', '2025-10-02 15:20:51'),
(889, 'default', 'created', 'App\\Models\\User', 'created', 288, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-a94d43b2-9554-4b55-a0e6-3bb45dd19d31@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:53', '2025-10-02 15:20:53'),
(890, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 217, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:53', '2025-10-02 15:20:53'),
(891, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 217, 'App\\Models\\User', 288, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-02 15:20:53', '2025-10-02 15:20:53'),
(892, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 217, 'App\\Models\\User', 288, '[]', NULL, '2025-10-02 15:20:53', '2025-10-02 15:20:53'),
(893, 'default', 'created', 'App\\Models\\User', 'created', 289, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-0676a5fb-106f-435a-80f7-d920bb6c53cc@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:55', '2025-10-02 15:20:55'),
(894, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 218, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:55', '2025-10-02 15:20:55'),
(895, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 218, 'App\\Models\\User', 289, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 15:20:55', '2025-10-02 15:20:55'),
(896, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 218, 'App\\Models\\User', 289, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-02 15:20:55', '2025-10-02 15:20:55'),
(897, 'default', 'created', 'App\\Models\\User', 'created', 290, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-a994303a-4d1e-4576-9950-191dfe5e0d81@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(898, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 219, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(899, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 220, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(900, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 221, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(901, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 222, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(902, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 223, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:57', '2025-10-02 15:20:57'),
(903, 'default', 'created', 'App\\Models\\User', 'created', 291, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-4361ee4b-781c-46ce-810c-e06a1b9a1d00@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:20:59', '2025-10-02 15:20:59'),
(904, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 224, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelled Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"900.00\", \"total_gross\": \"1200.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:20:59', '2025-10-02 15:20:59'),
(905, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 224, NULL, NULL, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-02 15:20:59', '2025-10-02 15:20:59'),
(906, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 224, 'App\\Models\\User', 291, '{\"cancellation_reason\": \"Budget constraints\"}', NULL, '2025-10-02 15:20:59', '2025-10-02 15:20:59'),
(907, 'default', 'created', 'App\\Models\\User', 'created', 292, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator\", \"email\": \"coordinator-27734b5a-ec7c-4e9a-940f-1a823a6166a1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:21:02', '2025-10-02 15:21:02'),
(908, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 225, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"800.00\", \"total_gross\": \"1000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:21:02', '2025-10-02 15:21:02'),
(909, 'default', 'created', 'App\\Models\\User', 'created', 293, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-8c269d44-4e5b-42cd-8f70-944177086c90@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:21:04', '2025-10-02 15:21:04'),
(910, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 226, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:21:04', '2025-10-02 15:21:04'),
(911, 'default', 'created', 'App\\Models\\User', 'created', 294, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec7936cbb5@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:42:27', '2025-10-02 15:42:27'),
(912, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 227, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:42:27', '2025-10-02 15:42:27'),
(913, 'default', 'created', 'App\\Models\\User', 'created', 295, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec795a0b2d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:42:29', '2025-10-02 15:42:29'),
(914, 'default', 'created', 'App\\Models\\User', 'created', 296, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec9265a175@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:49:10', '2025-10-02 15:49:10'),
(915, 'default', 'created', 'App\\Models\\User', 'created', 297, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec929779e0@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:49:13', '2025-10-02 15:49:13'),
(916, 'default', 'created', 'App\\Models\\User', 'created', 298, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec9587c98a@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:50:00', '2025-10-02 15:50:00'),
(917, 'default', 'created', 'App\\Models\\User', 'created', 299, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec95b58647@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:50:03', '2025-10-02 15:50:03'),
(918, 'default', 'created', 'App\\Models\\User', 'created', 300, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec9632abe2@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:50:11', '2025-10-02 15:50:11'),
(919, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 228, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:50:11', '2025-10-02 15:50:11'),
(920, 'default', 'created', 'App\\Models\\User', 'created', 301, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec9658e8e4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:50:13', '2025-10-02 15:50:13'),
(921, 'default', 'created', 'App\\Models\\User', 'created', 302, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec9e4f20e1@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:52:21', '2025-10-02 15:52:21'),
(922, 'default', 'created', 'App\\Models\\User', 'created', 303, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dec9e776bd6@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:52:23', '2025-10-02 15:52:23'),
(923, 'default', 'created', 'App\\Models\\User', 'created', 304, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec9ecc38cf@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:52:28', '2025-10-02 15:52:28'),
(924, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 229, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:52:28', '2025-10-02 15:52:28'),
(925, 'default', 'created', 'App\\Models\\User', 'created', 305, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68dec9eeef31f@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:52:30', '2025-10-02 15:52:30'),
(926, 'default', 'created', 'App\\Models\\User', 'created', 306, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68decaf97b7e3@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:56:57', '2025-10-02 15:56:57'),
(927, 'default', 'created', 'App\\Models\\User', 'created', 307, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68decafc17b94@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:57:00', '2025-10-02 15:57:00'),
(928, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 230, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-02 15:57:00', '2025-10-02 15:57:00'),
(929, 'default', 'created', 'App\\Models\\User', 'created', 308, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68decb458c99c@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:58:13', '2025-10-02 15:58:13'),
(930, 'default', 'created', 'App\\Models\\User', 'created', 309, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68decb47d5dc4@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 15:58:15', '2025-10-02 15:58:15'),
(931, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 231, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-02 15:58:15', '2025-10-02 15:58:15'),
(932, 'default', 'created', 'App\\Models\\User', 'created', 310, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68decbd55769d@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:00:37', '2025-10-02 16:00:37'),
(933, 'default', 'created', 'App\\Models\\User', 'created', 311, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68decbd78be71@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:00:39', '2025-10-02 16:00:39'),
(934, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 232, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-02 16:00:39', '2025-10-02 16:00:39'),
(935, 'default', 'created', 'App\\Models\\User', 'created', 312, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68decbfe01d42@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:01:18', '2025-10-02 16:01:18'),
(936, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 233, 'App\\Models\\User', 312, '{\"attributes\": {\"title\": \"January 2099 Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-02-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-01-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-01-01T00:00:00.000000Z\", \"approval_required\": true}}', NULL, '2025-10-02 16:01:18', '2025-10-02 16:01:18'),
(937, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 233, 'App\\Models\\User', 312, '[]', NULL, '2025-10-02 16:01:18', '2025-10-02 16:01:18'),
(938, 'default', 'created', 'App\\Models\\User', 'created', 313, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68decc002f8c3@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:01:20', '2025-10-02 16:01:20'),
(939, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 234, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-02 16:01:20', '2025-10-02 16:01:20'),
(940, 'default', 'created', 'App\\Models\\User', 'created', 314, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68decda789310@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:08:24', '2025-10-02 16:08:24'),
(941, 'default', 'created', 'App\\Models\\User', 'created', 315, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68decdb048e43@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:08:32', '2025-10-02 16:08:32'),
(942, 'default', 'created', 'App\\Models\\User', 'created', 316, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dece0acc46b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:02', '2025-10-02 16:10:02'),
(943, 'default', 'created', 'App\\Models\\User', 'created', 317, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dece0fd1cee@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:07', '2025-10-02 16:10:07'),
(944, 'default', 'created', 'App\\Models\\User', 'created', 318, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dece228d973@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:26', '2025-10-02 16:10:26'),
(945, 'default', 'created', 'App\\Models\\User', 'created', 319, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68dece24b4f9b@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:28', '2025-10-02 16:10:28'),
(946, 'default', 'created', 'App\\Models\\User', 'created', 320, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68dece2c99138@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:36', '2025-10-02 16:10:36'),
(947, 'default', 'created', 'App\\Models\\User', 'created', 321, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68dece2f362fb@example.com\", \"employee_id\": null}}', NULL, '2025-10-02 16:10:39', '2025-10-02 16:10:39'),
(948, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 235, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-02 16:10:39', '2025-10-02 16:10:39'),
(949, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 03:03:12', '2025-10-03 03:03:12'),
(950, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 51, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"EMP001\", \"email\": \"khaled.h87@gmail.com\", \"phone\": \"01007847333\", \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Helmy\", \"first_name\": \"Khaled\", \"manager_id\": null, \"arabic_name\": \"خالد محمد حلمي محمد يسري\", \"position_id\": 12, \"department_id\": 4, \"photo_uploaded_at\": null, \"employment_type_id\": 2, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 03:05:42', '2025-10-03 03:05:42'),
(951, 'employee', 'Employee created', 'App\\Models\\Employee', NULL, 51, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 03:05:42', '2025-10-03 03:05:42'),
(952, 'employee', 'Employee updated', 'App\\Models\\Employee', 'updated', 51, 'App\\Models\\User', 1, '{\"old\": {\"hire_date\": null}, \"attributes\": {\"hire_date\": \"2013-01-20T00:00:00.000000Z\"}}', NULL, '2025-10-03 03:06:12', '2025-10-03 03:06:12'),
(953, 'employee_critical', 'Employee critical data updated', 'App\\Models\\Employee', NULL, 51, 'App\\Models\\User', 1, '{\"new_values\": {\"hire_date\": \"2013-01-20 00:00:00\"}, \"employee_code\": \"EMP001\", \"previous_values\": {\"hire_date\": null}, \"critical_changes\": {\"hire_date\": {\"new\": \"2013-01-20 00:00:00\", \"old\": null}}}', NULL, '2025-10-03 03:06:12', '2025-10-03 03:06:12'),
(954, 'employee', 'Employee updated', 'App\\Models\\Employee', NULL, 51, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 03:06:12', '2025-10-03 03:06:12'),
(955, 'default', 'created', 'App\\Models\\User', 'created', 322, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68df75a8a14c2@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 04:05:12', '2025-10-03 04:05:12'),
(956, 'default', 'created', 'App\\Models\\User', 'created', 323, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68df75ab8ade3@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 04:05:15', '2025-10-03 04:05:15'),
(957, 'default', 'created', 'App\\Models\\User', 'created', 324, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df83c6f09bb@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:05:27', '2025-10-03 05:05:27'),
(958, 'default', 'created', 'App\\Models\\User', 'created', 325, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df83c9574d2@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:05:29', '2025-10-03 05:05:29'),
(959, 'default', 'created', 'App\\Models\\User', 'created', 326, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df848f9c80e@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:08:47', '2025-10-03 05:08:47'),
(960, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 52, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-OQHMPQ\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:08:47', '2025-10-03 05:08:47'),
(961, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 1, NULL, NULL, '{\"attributes\": {\"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"employee_id\": 52, \"effective_to\": null, \"effective_from\": \"2099-01-01T00:00:00.000000Z\"}}', NULL, '2025-10-03 05:08:47', '2025-10-03 05:08:47'),
(962, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 1, NULL, NULL, '{\"changes\": {\"id\": 1, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:08:47\", \"updated_at\": \"2025-10-03 08:08:47\", \"employee_id\": 52, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"new_values\": {\"id\": 1, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:08:47\", \"updated_at\": \"2025-10-03 08:08:47\", \"employee_id\": 52, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"employee_id\": 52, \"effective_from\": \"2099-01-01T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 05:08:47', '2025-10-03 05:08:47'),
(963, 'default', 'created', 'App\\Models\\User', 'created', 327, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df849298041@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:08:50', '2025-10-03 05:08:50'),
(964, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 53, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-AZ02ID\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:08:50', '2025-10-03 05:08:50'),
(965, 'default', 'created', 'App\\Models\\User', 'created', 328, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df85118d251@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:10:57', '2025-10-03 05:10:57'),
(966, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 54, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-NITA0U\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:10:57', '2025-10-03 05:10:57'),
(967, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 2, NULL, NULL, '{\"attributes\": {\"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"employee_id\": 54, \"effective_to\": null, \"effective_from\": \"2099-01-01T00:00:00.000000Z\"}}', NULL, '2025-10-03 05:10:57', '2025-10-03 05:10:57'),
(968, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 2, NULL, NULL, '{\"changes\": {\"id\": 2, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:10:57\", \"updated_at\": \"2025-10-03 08:10:57\", \"employee_id\": 54, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"new_values\": {\"id\": 2, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:10:57\", \"updated_at\": \"2025-10-03 08:10:57\", \"employee_id\": 54, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"employee_id\": 54, \"effective_from\": \"2099-01-01T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 05:10:57', '2025-10-03 05:10:57'),
(969, 'default', 'created', 'App\\Models\\User', 'created', 329, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df8513d8d02@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:10:59', '2025-10-03 05:10:59'),
(970, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 55, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-PRHL04\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:10:59', '2025-10-03 05:10:59'),
(971, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 43, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"BASIC_SALARY\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(972, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 44, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"HOUSING_ALLOWANCE\", \"name_ar\": \"بدل السكن\", \"name_en\": \"Housing Allowance\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 2}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(973, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 45, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"TRANSPORT_ALLOWANCE\", \"name_ar\": \"بدل المواصلات\", \"name_en\": \"Transportation Allowance\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 3}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(974, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 46, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"OVERTIME\", \"name_ar\": \"ساعات إضافية\", \"name_en\": \"Overtime\", \"taxable\": true, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"earning\", \"priority_order\": 4}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(975, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 47, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"BONUS\", \"name_ar\": \"مكافأة\", \"name_en\": \"Bonus\", \"taxable\": true, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"earning\", \"priority_order\": 5}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(976, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 48, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"SOCIAL_INSURANCE\", \"name_ar\": \"خصم التأمين الاجتماعي\", \"name_en\": \"Social Insurance Deduction\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 101}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(977, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 49, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"INCOME_TAX\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 102}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(978, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 50, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"ADVANCE_DEDUCTION\", \"name_ar\": \"خصم السلفة\", \"name_en\": \"Advance Salary Deduction\", \"taxable\": false, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"deduction\", \"priority_order\": 103}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(979, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 51, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"LOAN_DEDUCTION\", \"name_ar\": \"خصم القرض\", \"name_en\": \"Loan Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 104}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(980, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 52, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"GROSS_SALARY\", \"name_ar\": \"إجمالي الراتب\", \"name_en\": \"Gross Salary\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 201}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(981, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 53, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"NET_SALARY\", \"name_ar\": \"صافي الراتب\", \"name_en\": \"Net Salary\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 202}}', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(982, 'salary_component', 'Seeded 11 predefined salary components', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(983, 'default', 'created', 'App\\Models\\User', 'created', 330, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df8d0b216cb@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:44:59', '2025-10-03 05:44:59'),
(984, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 56, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-X4U9VO\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:44:59', '2025-10-03 05:44:59'),
(985, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 3, NULL, NULL, '{\"attributes\": {\"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"employee_id\": 56, \"effective_to\": null, \"effective_from\": \"2099-01-01T00:00:00.000000Z\"}}', NULL, '2025-10-03 05:44:59', '2025-10-03 05:44:59'),
(986, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 3, NULL, NULL, '{\"changes\": {\"id\": 3, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:44:59\", \"updated_at\": \"2025-10-03 08:44:59\", \"employee_id\": 56, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"new_values\": {\"id\": 3, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:44:59\", \"updated_at\": \"2025-10-03 08:44:59\", \"employee_id\": 56, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"employee_id\": 56, \"effective_from\": \"2099-01-01T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 05:44:59', '2025-10-03 05:44:59'),
(987, 'default', 'created', 'App\\Models\\User', 'created', 331, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df8d0d64391@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:45:01', '2025-10-03 05:45:01'),
(988, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 57, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-DKDIPU\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:45:01', '2025-10-03 05:45:01'),
(989, 'default', 'created', 'App\\Models\\User', 'created', 332, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df8d1feae0b@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:45:19', '2025-10-03 05:45:19'),
(990, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 58, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-RPLURL\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:45:19', '2025-10-03 05:45:19'),
(991, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 4, NULL, NULL, '{\"attributes\": {\"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"employee_id\": 58, \"effective_to\": null, \"effective_from\": \"2099-01-01T00:00:00.000000Z\"}}', NULL, '2025-10-03 05:45:19', '2025-10-03 05:45:19'),
(992, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 4, NULL, NULL, '{\"changes\": {\"id\": 4, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:45:19\", \"updated_at\": \"2025-10-03 08:45:19\", \"employee_id\": 58, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"new_values\": {\"id\": 4, \"notes\": \"Baseline structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 08:45:19\", \"updated_at\": \"2025-10-03 08:45:19\", \"employee_id\": 58, \"effective_to\": null, \"effective_from\": \"2099-01-01 00:00:00\"}, \"employee_id\": 58, \"effective_from\": \"2099-01-01T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 05:45:19', '2025-10-03 05:45:19'),
(993, 'default', 'created', 'App\\Models\\User', 'created', 333, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68df8d221d49c@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 05:45:22', '2025-10-03 05:45:22'),
(994, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 59, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-QQF2ZB\", \"email\": null, \"phone\": null, \"status\": \"active\", \"hire_date\": null, \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": false}}', NULL, '2025-10-03 05:45:22', '2025-10-03 05:45:22'),
(995, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:12:31', '2025-10-03 06:12:31'),
(996, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:14:49', '2025-10-03 06:14:49'),
(997, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:23:48', '2025-10-03 06:23:48'),
(998, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:28:28', '2025-10-03 06:28:28'),
(999, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:35:27', '2025-10-03 06:35:27'),
(1000, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:40:12', '2025-10-03 06:40:12'),
(1001, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:43:46', '2025-10-03 06:43:46'),
(1002, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 06:46:59', '2025-10-03 06:46:59'),
(1003, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:01:46', '2025-10-03 07:01:46'),
(1004, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:04:14', '2025-10-03 07:04:14'),
(1005, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:05:39', '2025-10-03 07:05:39'),
(1006, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:07:04', '2025-10-03 07:07:04'),
(1007, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:11:48', '2025-10-03 07:11:48'),
(1008, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:16:46', '2025-10-03 07:16:46'),
(1009, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:18:06', '2025-10-03 07:18:06'),
(1010, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:23:20', '2025-10-03 07:23:20'),
(1011, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:25:49', '2025-10-03 07:25:49'),
(1012, 'authentication', 'User logged in', NULL, NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:28:08', '2025-10-03 07:28:08'),
(1013, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"notes\": \"Playwright automated structure\", \"currency\": \"EGP\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(1014, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 5, 'App\\Models\\User', 1, '{\"changes\": {\"id\": 5, \"notes\": \"Playwright automated structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 10:28:16\", \"updated_at\": \"2025-10-03 10:28:16\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03 00:00:00\"}, \"new_values\": {\"id\": 5, \"notes\": \"Playwright automated structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 10:28:16\", \"updated_at\": \"2025-10-03 10:28:16\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03 00:00:00\"}, \"employee_id\": 51, \"effective_from\": \"2025-10-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(1015, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 5, 'App\\Models\\User', 1, '{\"employee_id\": 51, \"employee_name\": \"Khaled Helmy\", \"effective_from\": \"2025-10-03\", \"components_count\": 3}', NULL, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(1016, 'default', 'Salary structure updated', 'App\\Models\\SalaryStructure', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"effective_to\": null}, \"attributes\": {\"effective_to\": \"2025-10-02T00:00:00.000000Z\"}}', NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13'),
(1017, 'salary_structure', 'Salary structure updated', 'App\\Models\\SalaryStructure', NULL, 5, 'App\\Models\\User', 1, '{\"changes\": {\"updated_at\": {\"new\": \"2025-10-03 10:30:13\", \"old\": \"2025-10-03T10:28:16.000000Z\"}, \"effective_to\": {\"new\": \"2025-10-02 00:00:00\", \"old\": null}}, \"new_values\": {\"id\": 5, \"notes\": \"Playwright automated structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 10:28:16\", \"updated_at\": \"2025-10-03 10:30:13\", \"employee_id\": 51, \"effective_to\": \"2025-10-02 00:00:00\", \"effective_from\": \"2025-10-03\"}, \"employee_id\": 51, \"effective_from\": \"2025-10-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": {\"id\": 5, \"notes\": \"Playwright automated structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03T10:28:16.000000Z\", \"updated_at\": \"2025-10-03T10:28:16.000000Z\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1018, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"notes\": null, \"currency\": \"EGP\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13'),
(1019, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 6, 'App\\Models\\User', 1, '{\"changes\": {\"id\": 6, \"notes\": null, \"currency\": \"EGP\", \"created_at\": \"2025-10-03 10:30:13\", \"updated_at\": \"2025-10-03 10:30:13\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03 00:00:00\"}, \"new_values\": {\"id\": 6, \"notes\": null, \"currency\": \"EGP\", \"created_at\": \"2025-10-03 10:30:13\", \"updated_at\": \"2025-10-03 10:30:13\", \"employee_id\": 51, \"effective_to\": null, \"effective_from\": \"2025-10-03 00:00:00\"}, \"employee_id\": 51, \"effective_from\": \"2025-10-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13'),
(1020, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 6, 'App\\Models\\User', 1, '{\"employee_id\": 51, \"employee_name\": \"Khaled Helmy\", \"effective_from\": \"2025-10-03\", \"components_count\": 3}', NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13'),
(1021, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 236, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"Test by KH\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 07:32:29', '2025-10-03 07:32:29'),
(1022, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 236, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 07:32:29', '2025-10-03 07:32:29'),
(1023, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 31, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 07:32:40', '2025-10-03 07:32:40'),
(1024, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 237, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"test 5\", \"status\": \"draft\", \"pay_date\": \"2025-12-31T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-12-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 08:31:31', '2025-10-03 08:31:31'),
(1025, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 237, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 08:31:31', '2025-10-03 08:31:31'),
(1026, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 32, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-12-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 08:31:40', '2025-10-03 08:31:40'),
(1027, 'default', 'created', 'App\\Models\\User', 'created', 334, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfb986b31ec@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 08:54:46', '2025-10-03 08:54:46'),
(1028, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 60, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfb986f0e13\", \"email\": \"employee-68dfb986f0e15@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 08:54:46', '2025-10-03 08:54:46'),
(1029, 'default', 'created', 'App\\Models\\User', 'created', 335, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfb9a5a63ef@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1030, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 61, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfb9a5e25ec\", \"email\": \"employee-68dfb9a5e25ee@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1031, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 55, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_ZSADY7\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1032, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 56, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_B17SBW\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1033, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 7, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 61, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1034, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 7, NULL, NULL, '{\"changes\": {\"id\": 7, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 11:55:17\", \"updated_at\": \"2025-10-03 11:55:17\", \"employee_id\": 61, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 7, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 11:55:17\", \"updated_at\": \"2025-10-03 11:55:17\", \"employee_id\": 61, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 61, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1035, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 238, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 08:55:17', '2025-10-03 08:55:17'),
(1036, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 33, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 08:55:18', '2025-10-03 08:55:18'),
(1037, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 33, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2200.00\", \"gross_pay\": \"2200.00\"}}', NULL, '2025-10-03 08:55:18', '2025-10-03 08:55:18'),
(1038, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 34, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfb9a5e25ec\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 08:55:18', '2025-10-03 08:55:18'),
(1039, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 34, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 08:55:18', '2025-10-03 08:55:18'),
(1040, 'default', 'created', 'App\\Models\\User', 'created', 336, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfb9dd1c8bb@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1041, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 62, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfb9dd5541d\", \"email\": \"employee-68dfb9dd5541f@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1042, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 57, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_OGCYWJ\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1043, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 58, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_LGVNCM\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1044, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 8, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 62, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1045, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 8, NULL, NULL, '{\"changes\": {\"id\": 8, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 11:56:13\", \"updated_at\": \"2025-10-03 11:56:13\", \"employee_id\": 62, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 8, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 11:56:13\", \"updated_at\": \"2025-10-03 11:56:13\", \"employee_id\": 62, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 62, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1046, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 239, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1047, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 35, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfb9dd5541d\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1048, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 35, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 08:56:13', '2025-10-03 08:56:13'),
(1049, 'default', 'created', 'App\\Models\\User', 'created', 337, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfbd2d2a1b3@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1050, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 63, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfbd2d65253\", \"email\": \"employee-68dfbd2d65255@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1051, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 59, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_ASEVLO\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1052, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 60, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_AMP1FA\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1053, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 9, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 63, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1054, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 9, NULL, NULL, '{\"changes\": {\"id\": 9, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:10:21\", \"updated_at\": \"2025-10-03 12:10:21\", \"employee_id\": 63, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 9, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:10:21\", \"updated_at\": \"2025-10-03 12:10:21\", \"employee_id\": 63, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 63, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1055, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 240, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 09:10:21', '2025-10-03 09:10:21'),
(1056, 'default', 'created', 'App\\Models\\User', 'created', 338, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfbd599bf64@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1057, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 64, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfbd59d4a09\", \"email\": \"employee-68dfbd59d4a0b@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1058, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 61, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_KKCWAO\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1059, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 62, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_FZYSHO\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1060, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 10, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 64, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1061, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 10, NULL, NULL, '{\"changes\": {\"id\": 10, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:11:05\", \"updated_at\": \"2025-10-03 12:11:05\", \"employee_id\": 64, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 10, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:11:05\", \"updated_at\": \"2025-10-03 12:11:05\", \"employee_id\": 64, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 64, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1062, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 241, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 09:11:05', '2025-10-03 09:11:05'),
(1063, 'default', 'created', 'App\\Models\\User', 'created', 339, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfc827656ae@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1064, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 65, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfc827a971c\", \"email\": \"employee-68dfc827a971e@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1065, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 63, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_5BZ4LI\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1066, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 64, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_C0ESYL\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1067, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 11, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 65, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1068, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 11, NULL, NULL, '{\"changes\": {\"id\": 11, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:57:11\", \"updated_at\": \"2025-10-03 12:57:11\", \"employee_id\": 65, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 11, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:57:11\", \"updated_at\": \"2025-10-03 12:57:11\", \"employee_id\": 65, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 65, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1069, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 242, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1070, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 36, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1071, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 36, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2200.00\", \"gross_pay\": \"2200.00\"}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1072, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 37, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfc827a971c\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1073, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 37, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(1074, 'default', 'created', 'App\\Models\\User', 'created', 340, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfc84a158b5@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1075, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 66, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfc84a4d7be\", \"email\": \"employee-68dfc84a4d7c1@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1076, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 65, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_DCSCUW\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1077, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 66, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_VC6ZMT\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1078, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 12, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 66, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1079, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 12, NULL, NULL, '{\"changes\": {\"id\": 12, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:57:46\", \"updated_at\": \"2025-10-03 12:57:46\", \"employee_id\": 66, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 12, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 12:57:46\", \"updated_at\": \"2025-10-03 12:57:46\", \"employee_id\": 66, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 66, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1080, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 243, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1081, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 38, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfc84a4d7be\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1082, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 38, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(1083, 'default', 'created', 'App\\Models\\User', 'created', 341, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfc8d3cc131@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1084, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 67, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfc8d41125f\", \"email\": \"employee-68dfc8d411262@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1085, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 67, NULL, NULL, '{\"attributes\": {\"code\": \"BASIC_F6PY6U\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1086, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 'created', 68, NULL, NULL, '{\"attributes\": {\"code\": \"TAX_SQS3K1\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 2}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1087, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 13, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 67, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1088, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 13, NULL, NULL, '{\"changes\": {\"id\": 13, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:00:04\", \"updated_at\": \"2025-10-03 13:00:04\", \"employee_id\": 67, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 13, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:00:04\", \"updated_at\": \"2025-10-03 13:00:04\", \"employee_id\": 67, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 67, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1089, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 244, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1090, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 39, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfc8d41125f\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1091, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 39, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(1092, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 241, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-03 10:06:41', '2025-10-03 10:06:41'),
(1093, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 241, 'App\\Models\\User', 1, '{\"cancellation_reason\": \"failed test\"}', NULL, '2025-10-03 10:06:41', '2025-10-03 10:06:41'),
(1094, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 245, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"Test Dec2025\", \"status\": \"draft\", \"pay_date\": \"2025-12-31T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:08:52', '2025-10-03 10:08:52'),
(1095, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 245, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 10:08:52', '2025-10-03 10:08:52'),
(1096, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 40, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:08:58', '2025-10-03 10:08:58'),
(1097, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 40, 'App\\Models\\User', 1, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2200.00\", \"gross_pay\": \"2200.00\"}}', NULL, '2025-10-03 10:08:59', '2025-10-03 10:08:59'),
(1098, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 245, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-03 10:09:21', '2025-10-03 10:09:21'),
(1099, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 245, 'App\\Models\\User', 1, '{\"cancellation_reason\": \"not complete\"}', NULL, '2025-10-03 10:09:21', '2025-10-03 10:09:21'),
(1100, 'salary_structure', 'Salary structure updated', 'App\\Models\\SalaryStructure', NULL, 6, 'App\\Models\\User', 1, '{\"employee_id\": 51, \"components_count\": 3}', NULL, '2025-10-03 10:10:18', '2025-10-03 10:10:18'),
(1101, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 246, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"test dec 2026\", \"status\": \"draft\", \"pay_date\": \"2025-12-31T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:11:19', '2025-10-03 10:11:19'),
(1102, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 246, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 10:11:19', '2025-10-03 10:11:19'),
(1103, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 41, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(1104, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 41, 'App\\Models\\User', 1, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2200.00\", \"gross_pay\": \"2200.00\"}}', NULL, '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(1105, 'default', 'created', 'App\\Models\\User', 'created', 342, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfcc03de7e7@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1106, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 68, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfcc0423aca\", \"email\": \"employee-68dfcc0423acc@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1107, 'default', 'Salary component updated', 'App\\Models\\SalaryComponent', 'updated', 49, NULL, NULL, '{\"old\": {\"priority_order\": 102}, \"attributes\": {\"priority_order\": 2}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1108, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 14, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 68, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1109, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 14, NULL, NULL, '{\"changes\": {\"id\": 14, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:13:40\", \"updated_at\": \"2025-10-03 13:13:40\", \"employee_id\": 68, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 14, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:13:40\", \"updated_at\": \"2025-10-03 13:13:40\", \"employee_id\": 68, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 68, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1110, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 247, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1111, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 42, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfcc0423aca\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1112, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 42, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(1113, 'default', 'created', 'App\\Models\\User', 'created', 343, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfcd0c5518d@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1114, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 69, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfcd0c8c006\", \"email\": \"employee-68dfcd0c8c00a@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1115, 'default', 'Salary component updated', 'App\\Models\\SalaryComponent', 'updated', 49, NULL, NULL, '{\"old\": {\"priority_order\": 102}, \"attributes\": {\"priority_order\": 2}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1116, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 15, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 69, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1117, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 15, NULL, NULL, '{\"changes\": {\"id\": 15, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:18:04\", \"updated_at\": \"2025-10-03 13:18:04\", \"employee_id\": 69, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 15, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:18:04\", \"updated_at\": \"2025-10-03 13:18:04\", \"employee_id\": 69, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 69, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1118, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 248, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1119, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 43, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfcd0c8c006\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1120, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 43, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(1121, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 246, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-03 10:20:55', '2025-10-03 10:20:55'),
(1122, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 246, 'App\\Models\\User', 1, '{\"cancellation_reason\": \"faild\"}', NULL, '2025-10-03 10:20:55', '2025-10-03 10:20:55'),
(1123, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 249, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"test dec 2025 2\", \"status\": \"draft\", \"pay_date\": \"2025-12-31T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:21:35', '2025-10-03 10:21:35'),
(1124, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 249, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 10:21:35', '2025-10-03 10:21:35'),
(1125, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 44, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2025-12-31T00:00:00.000000Z\", \"pay_period_start\": \"2025-12-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(1126, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 44, 'App\\Models\\User', 1, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2100.00\", \"gross_pay\": \"2200.00\", \"total_deductions\": \"100.00\"}}', NULL, '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(1127, 'default', 'created', 'App\\Models\\User', 'created', 344, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfcf0294757@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1128, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 70, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfcf02cbda7\", \"email\": \"employee-68dfcf02cbda9@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1129, 'default', 'Salary component updated', 'App\\Models\\SalaryComponent', 'updated', 49, NULL, NULL, '{\"old\": {\"priority_order\": 102}, \"attributes\": {\"priority_order\": 2}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1130, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 16, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 70, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1131, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 16, NULL, NULL, '{\"changes\": {\"id\": 16, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:26:26\", \"updated_at\": \"2025-10-03 13:26:26\", \"employee_id\": 70, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 16, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:26:26\", \"updated_at\": \"2025-10-03 13:26:26\", \"employee_id\": 70, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 70, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1132, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 250, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1133, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 45, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfcf02cbda7\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1134, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 45, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(1135, 'default', 'created', 'App\\Models\\User', 'created', 345, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-creator-68dfd1fa30782@example.com\", \"employee_id\": null}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06');
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1136, 'employee', 'Employee created', 'App\\Models\\Employee', 'created', 71, NULL, NULL, '{\"attributes\": {\"code\": \"EMP-68dfd1fa69f70\", \"email\": \"employee-68dfd1fa69f73@example.com\", \"phone\": null, \"status\": \"active\", \"hire_date\": \"2024-10-03T00:00:00.000000Z\", \"last_name\": \"Employee\", \"first_name\": \"Test\", \"manager_id\": null, \"arabic_name\": null, \"position_id\": null, \"department_id\": null, \"photo_uploaded_at\": null, \"employment_type_id\": null, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1137, 'default', 'Salary component updated', 'App\\Models\\SalaryComponent', 'updated', 49, NULL, NULL, '{\"old\": {\"priority_order\": 102}, \"attributes\": {\"priority_order\": 2}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1138, 'default', 'Salary structure created', 'App\\Models\\SalaryStructure', 'created', 17, NULL, NULL, '{\"attributes\": {\"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"employee_id\": 71, \"effective_to\": null, \"effective_from\": \"2025-09-03T00:00:00.000000Z\"}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1139, 'salary_structure', 'Salary structure created', 'App\\Models\\SalaryStructure', NULL, 17, NULL, NULL, '{\"changes\": {\"id\": 17, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:39:06\", \"updated_at\": \"2025-10-03 13:39:06\", \"employee_id\": 71, \"effective_from\": \"2025-09-03 00:00:00\"}, \"new_values\": {\"id\": 17, \"notes\": \"Automated payroll regression structure\", \"currency\": \"EGP\", \"created_at\": \"2025-10-03 13:39:06\", \"updated_at\": \"2025-10-03 13:39:06\", \"employee_id\": 71, \"effective_from\": \"2025-09-03 00:00:00\"}, \"employee_id\": 71, \"effective_from\": \"2025-09-03T00:00:00.000000Z\", \"structure_name\": null, \"previous_values\": []}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1140, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 251, NULL, NULL, '{\"attributes\": {\"title\": \"November Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-30T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1141, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 46, NULL, NULL, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP-68dfd1fa69f70\", \"employee_name\": \"Test Employee\", \"pay_period_end\": \"2025-11-30T00:00:00.000000Z\", \"pay_period_start\": \"2025-11-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1142, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 46, NULL, NULL, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"9000.00\", \"gross_pay\": \"10000.00\", \"total_deductions\": \"1000.00\"}}', NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(1143, 'salary_structure', 'Salary structure updated', 'App\\Models\\SalaryStructure', NULL, 6, 'App\\Models\\User', 1, '{\"employee_id\": 51, \"components_count\": 3}', NULL, '2025-10-03 12:37:52', '2025-10-03 12:37:52'),
(1144, 'salary_structure', 'Salary structure updated', 'App\\Models\\SalaryStructure', NULL, 6, 'App\\Models\\User', 1, '{\"employee_id\": 51, \"components_count\": 3}', NULL, '2025-10-03 12:39:18', '2025-10-03 12:39:18'),
(1145, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 249, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-03 12:39:37', '2025-10-03 12:39:37'),
(1146, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 249, 'App\\Models\\User', 1, '{\"cancellation_reason\": \"ASFG\"}', NULL, '2025-10-03 12:39:37', '2025-10-03 12:39:37'),
(1147, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 252, 'App\\Models\\User', 1, '{\"attributes\": {\"title\": \"Jan 2025\", \"status\": \"draft\", \"pay_date\": \"2026-01-31T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-01-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-01-01T00:00:00.000000Z\", \"approval_required\": false}}', NULL, '2025-10-03 12:40:11', '2025-10-03 12:40:11'),
(1148, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', NULL, 252, 'App\\Models\\User', 1, '[]', NULL, '2025-10-03 12:40:11', '2025-10-03 12:40:11'),
(1149, 'payslip', 'Payslip created', 'App\\Models\\Payslip', 'created', 47, 'App\\Models\\User', 1, '{\"attributes\": {\"status\": \"calculated\", \"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"employee_code\": \"EMP001\", \"employee_name\": \"Khaled Helmy\", \"pay_period_end\": \"2026-01-31T00:00:00.000000Z\", \"pay_period_start\": \"2026-01-01T00:00:00.000000Z\", \"total_deductions\": \"0.00\"}}', NULL, '2025-10-03 12:40:17', '2025-10-03 12:40:17'),
(1150, 'payslip', 'Payslip updated', 'App\\Models\\Payslip', 'updated', 47, 'App\\Models\\User', 1, '{\"old\": {\"net_pay\": \"0.00\", \"gross_pay\": \"0.00\", \"total_deductions\": \"0.00\"}, \"attributes\": {\"net_pay\": \"2000.00\", \"gross_pay\": \"2200.00\", \"total_deductions\": \"200.00\"}}', NULL, '2025-10-03 12:40:17', '2025-10-03 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `approval_events`
--

DROP TABLE IF EXISTS `approval_events`;
CREATE TABLE IF NOT EXISTS `approval_events` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `approval_request_id` bigint UNSIGNED NOT NULL,
  `step_no` tinyint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED NOT NULL,
  `decision` enum('approved','rejected') NOT NULL,
  `decision_note` varchar(255) DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ae_step` (`approval_request_id`,`step_no`),
  KEY `fk_ae_approver` (`approver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval_requests`
--

DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE IF NOT EXISTS `approval_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(80) NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `action` varchar(80) NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `required_steps` tinyint UNSIGNED NOT NULL DEFAULT '2',
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ar_entity` (`entity_type`,`entity_id`),
  KEY `fk_ar_requester` (`requested_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_devices`
--

DROP TABLE IF EXISTS `attendance_devices`;
CREATE TABLE IF NOT EXISTS `attendance_devices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `serial` varchar(80) DEFAULT NULL,
  `location` varchar(120) DEFAULT NULL,
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial` (`serial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_logs`
--

DROP TABLE IF EXISTS `attendance_logs`;
CREATE TABLE IF NOT EXISTS `attendance_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `device_id` bigint UNSIGNED DEFAULT NULL,
  `punch_time` datetime NOT NULL,
  `punch_type` enum('in','out') NOT NULL,
  `source` enum('connector','manual') NOT NULL DEFAULT 'connector',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_al_device` (`device_id`),
  KEY `idx_al_emp_time` (`employee_id`,`punch_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  `type` enum('check_in','check_out','break_start','break_end') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'check_in',
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_data` json DEFAULT NULL,
  `status` enum('valid','invalid','duplicate','anomaly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_records_employee_id_timestamp_type_unique` (`employee_id`,`timestamp`,`type`),
  KEY `attendance_records_employee_id_timestamp_index` (`employee_id`,`timestamp`),
  KEY `attendance_records_employee_code_index` (`employee_code`),
  KEY `attendance_records_timestamp_index` (`timestamp`),
  KEY `attendance_records_type_index` (`type`),
  KEY `attendance_records_status_index` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_summaries`
--

DROP TABLE IF EXISTS `attendance_summaries`;
CREATE TABLE IF NOT EXISTS `attendance_summaries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `expected_start_time` time DEFAULT NULL,
  `expected_end_time` time DEFAULT NULL,
  `first_check_in` datetime DEFAULT NULL,
  `last_check_out` datetime DEFAULT NULL,
  `total_work_minutes` int NOT NULL DEFAULT '0',
  `break_minutes` int NOT NULL DEFAULT '0',
  `late_minutes` int NOT NULL DEFAULT '0',
  `early_departure_minutes` int NOT NULL DEFAULT '0',
  `overtime_minutes` int NOT NULL DEFAULT '0',
  `is_absent` tinyint(1) NOT NULL DEFAULT '0',
  `is_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('present','absent','partial','holiday','leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `anomalies` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_summaries_employee_id_date_unique` (`employee_id`,`date`),
  KEY `attendance_summaries_employee_id_date_index` (`employee_id`,`date`),
  KEY `attendance_summaries_employee_code_index` (`employee_code`),
  KEY `attendance_summaries_date_index` (`date`),
  KEY `attendance_summaries_status_index` (`status`),
  KEY `attendance_summaries_is_absent_index` (`is_absent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `actor_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(80) NOT NULL,
  `entity_type` varchar(80) NOT NULL,
  `entity_id` bigint UNSIGNED DEFAULT NULL,
  `before_json` json DEFAULT NULL,
  `after_json` json DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_audit_entity` (`entity_type`,`entity_id`),
  KEY `idx_audit_actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `type` enum('permanent','fixed_term','probation','internship','consultancy') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `terms_json` json DEFAULT NULL,
  `status` enum('active','expired','terminated','pending') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signed_by` bigint UNSIGNED DEFAULT NULL,
  `termination_reason` varchar(191) DEFAULT NULL,
  `termination_note` text,
  `termination_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contract_employee` (`employee_id`),
  KEY `idx_contract_end_date` (`end_date`),
  KEY `contracts_approved_by_index` (`approved_by`),
  KEY `contracts_signed_by_index` (`signed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_attachments`
--

DROP TABLE IF EXISTS `contract_attachments`;
CREATE TABLE IF NOT EXISTS `contract_attachments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` bigint UNSIGNED NOT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contract_attachments_contract_id_index` (`contract_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_expiry_notifications`
--

DROP TABLE IF EXISTS `contract_expiry_notifications`;
CREATE TABLE IF NOT EXISTS `contract_expiry_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` bigint UNSIGNED NOT NULL,
  `notification_type` enum('urgent','critical','soon') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_date` date NOT NULL,
  `recipients` json NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `message` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_contract_notification` (`contract_id`,`notification_type`,`notification_date`),
  KEY `idx_notification_date` (`notification_date`),
  KEY `idx_status` (`status`),
  KEY `idx_contract_type` (`contract_id`,`notification_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_workflow_logs`
--

DROP TABLE IF EXISTS `contract_workflow_logs`;
CREATE TABLE IF NOT EXISTS `contract_workflow_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contract_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `by_user_id` bigint UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contract_workflow_logs_contract_id_index` (`contract_id`),
  KEY `contract_workflow_logs_by_user_id_index` (`by_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `name_en` varchar(120) NOT NULL,
  `name_ar` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name_en`, `name_ar`, `created_at`, `updated_at`) VALUES
(1, 'HR', 'Human Resources', 'الموارد البشرية', NULL, NULL),
(2, 'ADMIN', 'Administration', 'الإدارة', NULL, NULL),
(3, 'ACC', 'Accounting', 'الحسابات', NULL, NULL),
(4, 'IT', 'Information Technology', 'تكنولوجيا المعلومات', NULL, NULL),
(5, 'LIT', 'Litigation', 'التقاضي', NULL, NULL),
(6, 'CORP', 'Corporate', 'الشركات', NULL, NULL),
(7, 'ARB', 'Arbitration', 'التحكيم', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `contract_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(60) NOT NULL,
  `path` varchar(255) NOT NULL,
  `original_name` varchar(190) NOT NULL,
  `mime` varchar(80) NOT NULL,
  `checksum` char(64) DEFAULT NULL,
  `visibility` enum('private','shared') DEFAULT 'private',
  `expires_at` date DEFAULT NULL,
  `version_current` int NOT NULL DEFAULT '1',
  `watermark_note` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_doc_employee` (`employee_id`),
  KEY `fk_doc_contract` (`contract_id`),
  KEY `idx_documents_exp` (`expires_at`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_tag`
--

DROP TABLE IF EXISTS `document_tag`;
CREATE TABLE IF NOT EXISTS `document_tag` (
  `document_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`document_id`,`tag_id`),
  KEY `fk_dt_tag` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_versions`
--

DROP TABLE IF EXISTS `document_versions`;
CREATE TABLE IF NOT EXISTS `document_versions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_id` bigint UNSIGNED NOT NULL,
  `version_no` int NOT NULL,
  `path` varchar(255) NOT NULL,
  `checksum` char(64) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_docver` (`document_id`,`version_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `arabic_name` varchar(160) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('active','inactive','terminated','on_leave') DEFAULT 'active',
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `employment_type_id` tinyint UNSIGNED DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `national_id` varbinary(256) DEFAULT NULL,
  `salary_visibility_flag` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_emp_dept` (`department_id`),
  KEY `fk_emp_pos` (`position_id`),
  KEY `fk_emp_type` (`employment_type_id`),
  KEY `fk_emp_manager` (`manager_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `code`, `first_name`, `last_name`, `arabic_name`, `email`, `phone`, `hire_date`, `status`, `department_id`, `position_id`, `employment_type_id`, `manager_id`, `national_id`, `salary_visibility_flag`, `created_at`, `updated_at`) VALUES
(51, 'EMP001', 'Khaled', 'Helmy', 'خالد محمد حلمي محمد يسري', 'khaled.h87@gmail.com', '01007847333', '2013-01-20', 'active', 4, 12, 2, NULL, 0x65794a7064694936496a5a484e4755794f53747062314a485458686b63315275554538345130453950534973496e5a686248566c496a6f696431525854464250656c4e56596b4a754d7a6b30624731474b32677751543039496977696257466a496a6f694e3249325a5755354d324d304d6a49324f5451785a6d566a4e6d49305a5749304d6d466a4e445135593255335a57526b5a6a4e6d4e7a4d334d5759794d5751354d47493359546c6b4d7a59334f475a6c4f5463784f534973496e52685a79493649694a39, 1, '2025-10-03 03:05:41', '2025-10-03 03:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `employee_cases`
--

DROP TABLE IF EXISTS `employee_cases`;
CREATE TABLE IF NOT EXISTS `employee_cases` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `case_ref` varchar(120) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ec_emp` (`employee_id`),
  KEY `idx_ec_ref` (`case_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employment_types`
--

DROP TABLE IF EXISTS `employment_types`;
CREATE TABLE IF NOT EXISTS `employment_types` (
  `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_en` varchar(60) NOT NULL,
  `name_ar` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_employment_types_name` (`name_en`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employment_types`
--

INSERT INTO `employment_types` (`id`, `name_en`, `name_ar`, `created_at`, `updated_at`) VALUES
(1, 'Permanent', 'دائم', NULL, NULL),
(2, 'Fixed-term', 'محدد المدة', NULL, NULL),
(3, 'Probationary', 'تجريبي', NULL, NULL),
(4, 'Internship', 'تدريب', NULL, NULL),
(5, 'Consultancy', 'استشاري', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generated_letters`
--

DROP TABLE IF EXISTS `generated_letters`;
CREATE TABLE IF NOT EXISTS `generated_letters` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `letter_template_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `reference_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','pending_approval','approved','sent','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `generated_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `additional_data` json DEFAULT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `generated_letters_reference_number_unique` (`reference_number`),
  KEY `generated_letters_letter_template_id_foreign` (`letter_template_id`),
  KEY `generated_letters_generated_by_foreign` (`generated_by`),
  KEY `generated_letters_approved_by_foreign` (`approved_by`),
  KEY `generated_letters_status_created_at_index` (`status`,`created_at`),
  KEY `generated_letters_employee_id_status_index` (`employee_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_balances`
--

DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE IF NOT EXISTS `leave_balances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `policy_id` bigint UNSIGNED NOT NULL,
  `period` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening` decimal(6,2) NOT NULL DEFAULT '0.00',
  `accrued` decimal(6,2) NOT NULL DEFAULT '0.00',
  `taken` decimal(6,2) NOT NULL DEFAULT '0.00',
  `closing` decimal(6,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_balances_user_id_policy_id_period_unique` (`user_id`,`policy_id`,`period`),
  KEY `leave_balances_user_id_index` (`user_id`),
  KEY `leave_balances_policy_id_index` (`policy_id`),
  KEY `leave_balances_period_index` (`period`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_policies`
--

DROP TABLE IF EXISTS `leave_policies`;
CREATE TABLE IF NOT EXISTS `leave_policies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `accrual_rule` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `days_per_year` decimal(5,2) NOT NULL DEFAULT '0.00',
  `carry_over` tinyint(1) NOT NULL DEFAULT '0',
  `max_carry_over` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_policies_code_unique` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `policy_id` bigint UNSIGNED NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `days` decimal(6,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_user_id_index` (`user_id`),
  KEY `leave_requests_policy_id_index` (`policy_id`),
  KEY `leave_requests_status_index` (`status`),
  KEY `leave_requests_approver_id_index` (`approver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

DROP TABLE IF EXISTS `letter_templates`;
CREATE TABLE IF NOT EXISTS `letter_templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` enum('en','ar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `variables` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `letter_templates_created_by_foreign` (`created_by`),
  KEY `letter_templates_updated_by_foreign` (`updated_by`),
  KEY `letter_templates_type_language_is_active_index` (`type`,`language`,`is_active`),
  KEY `letter_templates_category_is_active_index` (`category`,`is_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_12_145713_create_permission_tables', 1),
(6, '2025_09_12_195810_create_activity_log_table', 1),
(7, '2025_09_12_195811_add_event_column_to_activity_log_table', 1),
(8, '2025_09_12_195812_add_batch_uuid_column_to_activity_log_table', 1),
(9, '2025_09_12_202341_add_photo_fields_to_employees_table', 1),
(10, '2025_09_13_050239_add_guard_name_to_roles_table', 1),
(11, '2025_09_13_050331_add_guard_name_to_permissions_table', 1),
(12, '2025_09_13_062120_create_contract_expiry_notifications_table', 1),
(28, '2025_09_13_114025_create_payslip_lines_table', 5),
(27, '2025_09_13_113953_create_payslips_table', 4),
(26, '2025_09_13_113922_create_payroll_runs_table', 3),
(16, '2025_09_13_143628_create_attendance_records_table', 1),
(17, '2025_09_13_143656_create_attendance_summaries_table', 1),
(18, '2025_09_13_193902_create_letter_templates_table', 1),
(19, '2025_09_13_193930_create_generated_letters_table', 1),
(20, '2025_09_19_114800_add_lifecycle_columns_to_contracts', 2),
(21, '2025_09_19_114810_create_contract_attachments_and_logs', 2),
(22, '2025_09_19_114820_create_leave_tables', 2),
(23, '2025_09_19_114830_create_saved_reports_table', 2),
(29, '2025_10_01_200100_add_component_metadata_to_payslip_lines', 6),
(30, '2025_10_01_205500_add_metadata_columns_to_payslips_table', 7),
(31, '2025_10_02_220500_add_approval_required_to_payroll_runs_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 3),
(1, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 5),
(1, 'App\\Models\\User', 6),
(1, 'App\\Models\\User', 7),
(1, 'App\\Models\\User', 8),
(1, 'App\\Models\\User', 9),
(1, 'App\\Models\\User', 10),
(1, 'App\\Models\\User', 11),
(1, 'App\\Models\\User', 12),
(1, 'App\\Models\\User', 13),
(1, 'App\\Models\\User', 14),
(1, 'App\\Models\\User', 15),
(1, 'App\\Models\\User', 16),
(1, 'App\\Models\\User', 17),
(1, 'App\\Models\\User', 18),
(1, 'App\\Models\\User', 19),
(1, 'App\\Models\\User', 20),
(1, 'App\\Models\\User', 21),
(1, 'App\\Models\\User', 22),
(1, 'App\\Models\\User', 23),
(1, 'App\\Models\\User', 24),
(1, 'App\\Models\\User', 25),
(1, 'App\\Models\\User', 26),
(1, 'App\\Models\\User', 27),
(1, 'App\\Models\\User', 28),
(1, 'App\\Models\\User', 29),
(1, 'App\\Models\\User', 30),
(1, 'App\\Models\\User', 31),
(1, 'App\\Models\\User', 32),
(1, 'App\\Models\\User', 33),
(1, 'App\\Models\\User', 34),
(1, 'App\\Models\\User', 35),
(1, 'App\\Models\\User', 36),
(1, 'App\\Models\\User', 37),
(1, 'App\\Models\\User', 38),
(1, 'App\\Models\\User', 39),
(1, 'App\\Models\\User', 40),
(1, 'App\\Models\\User', 41),
(1, 'App\\Models\\User', 42),
(1, 'App\\Models\\User', 43),
(1, 'App\\Models\\User', 44),
(1, 'App\\Models\\User', 45),
(1, 'App\\Models\\User', 46),
(1, 'App\\Models\\User', 47),
(1, 'App\\Models\\User', 48),
(1, 'App\\Models\\User', 49),
(1, 'App\\Models\\User', 50),
(1, 'App\\Models\\User', 51),
(1, 'App\\Models\\User', 52),
(1, 'App\\Models\\User', 53),
(1, 'App\\Models\\User', 54),
(1, 'App\\Models\\User', 55),
(1, 'App\\Models\\User', 56),
(1, 'App\\Models\\User', 57),
(1, 'App\\Models\\User', 58),
(1, 'App\\Models\\User', 59),
(1, 'App\\Models\\User', 60),
(1, 'App\\Models\\User', 62),
(1, 'App\\Models\\User', 63),
(1, 'App\\Models\\User', 65),
(1, 'App\\Models\\User', 66),
(1, 'App\\Models\\User', 67),
(1, 'App\\Models\\User', 69),
(1, 'App\\Models\\User', 70),
(1, 'App\\Models\\User', 72),
(1, 'App\\Models\\User', 73),
(1, 'App\\Models\\User', 74),
(1, 'App\\Models\\User', 76),
(1, 'App\\Models\\User', 77),
(1, 'App\\Models\\User', 79),
(1, 'App\\Models\\User', 80),
(1, 'App\\Models\\User', 81),
(1, 'App\\Models\\User', 83),
(1, 'App\\Models\\User', 84),
(1, 'App\\Models\\User', 86),
(1, 'App\\Models\\User', 87),
(1, 'App\\Models\\User', 88),
(1, 'App\\Models\\User', 90),
(1, 'App\\Models\\User', 91),
(1, 'App\\Models\\User', 93),
(1, 'App\\Models\\User', 94),
(1, 'App\\Models\\User', 95),
(1, 'App\\Models\\User', 97),
(1, 'App\\Models\\User', 98),
(1, 'App\\Models\\User', 100),
(1, 'App\\Models\\User', 101),
(1, 'App\\Models\\User', 102),
(1, 'App\\Models\\User', 104),
(1, 'App\\Models\\User', 105),
(1, 'App\\Models\\User', 107),
(1, 'App\\Models\\User', 108),
(1, 'App\\Models\\User', 109),
(1, 'App\\Models\\User', 111),
(1, 'App\\Models\\User', 112),
(1, 'App\\Models\\User', 114),
(1, 'App\\Models\\User', 115),
(1, 'App\\Models\\User', 116),
(1, 'App\\Models\\User', 118),
(1, 'App\\Models\\User', 119),
(1, 'App\\Models\\User', 121),
(1, 'App\\Models\\User', 122),
(1, 'App\\Models\\User', 123),
(1, 'App\\Models\\User', 125),
(1, 'App\\Models\\User', 126),
(1, 'App\\Models\\User', 128),
(1, 'App\\Models\\User', 129),
(1, 'App\\Models\\User', 130),
(1, 'App\\Models\\User', 132),
(1, 'App\\Models\\User', 133),
(1, 'App\\Models\\User', 135),
(1, 'App\\Models\\User', 136),
(1, 'App\\Models\\User', 137),
(1, 'App\\Models\\User', 139),
(1, 'App\\Models\\User', 140),
(1, 'App\\Models\\User', 142),
(1, 'App\\Models\\User', 143),
(1, 'App\\Models\\User', 144),
(1, 'App\\Models\\User', 145),
(1, 'App\\Models\\User', 146),
(1, 'App\\Models\\User', 148),
(1, 'App\\Models\\User', 149),
(1, 'App\\Models\\User', 151),
(1, 'App\\Models\\User', 152),
(1, 'App\\Models\\User', 153),
(1, 'App\\Models\\User', 154),
(1, 'App\\Models\\User', 155),
(1, 'App\\Models\\User', 156),
(1, 'App\\Models\\User', 158),
(1, 'App\\Models\\User', 159),
(1, 'App\\Models\\User', 160),
(1, 'App\\Models\\User', 162),
(1, 'App\\Models\\User', 163),
(1, 'App\\Models\\User', 164),
(1, 'App\\Models\\User', 165),
(1, 'App\\Models\\User', 166),
(1, 'App\\Models\\User', 167),
(1, 'App\\Models\\User', 168),
(1, 'App\\Models\\User', 169),
(1, 'App\\Models\\User', 170),
(1, 'App\\Models\\User', 171),
(1, 'App\\Models\\User', 172),
(1, 'App\\Models\\User', 173),
(1, 'App\\Models\\User', 174),
(1, 'App\\Models\\User', 175),
(1, 'App\\Models\\User', 176),
(1, 'App\\Models\\User', 177),
(1, 'App\\Models\\User', 178),
(1, 'App\\Models\\User', 179),
(1, 'App\\Models\\User', 180),
(1, 'App\\Models\\User', 181),
(1, 'App\\Models\\User', 182),
(1, 'App\\Models\\User', 183),
(1, 'App\\Models\\User', 184),
(1, 'App\\Models\\User', 185),
(1, 'App\\Models\\User', 186),
(1, 'App\\Models\\User', 187),
(1, 'App\\Models\\User', 188),
(1, 'App\\Models\\User', 189),
(1, 'App\\Models\\User', 190),
(1, 'App\\Models\\User', 191),
(1, 'App\\Models\\User', 192),
(1, 'App\\Models\\User', 193),
(1, 'App\\Models\\User', 194),
(1, 'App\\Models\\User', 195),
(1, 'App\\Models\\User', 196),
(1, 'App\\Models\\User', 197),
(1, 'App\\Models\\User', 198),
(1, 'App\\Models\\User', 199),
(1, 'App\\Models\\User', 200),
(1, 'App\\Models\\User', 201),
(1, 'App\\Models\\User', 202),
(1, 'App\\Models\\User', 203),
(1, 'App\\Models\\User', 204),
(1, 'App\\Models\\User', 205),
(1, 'App\\Models\\User', 206),
(1, 'App\\Models\\User', 207),
(1, 'App\\Models\\User', 208),
(1, 'App\\Models\\User', 209),
(1, 'App\\Models\\User', 210),
(1, 'App\\Models\\User', 211),
(1, 'App\\Models\\User', 212),
(1, 'App\\Models\\User', 213),
(1, 'App\\Models\\User', 214),
(1, 'App\\Models\\User', 215),
(1, 'App\\Models\\User', 216),
(1, 'App\\Models\\User', 217),
(1, 'App\\Models\\User', 218),
(1, 'App\\Models\\User', 219),
(1, 'App\\Models\\User', 220),
(1, 'App\\Models\\User', 221),
(1, 'App\\Models\\User', 222),
(1, 'App\\Models\\User', 223),
(1, 'App\\Models\\User', 224),
(1, 'App\\Models\\User', 225),
(1, 'App\\Models\\User', 226),
(1, 'App\\Models\\User', 227),
(1, 'App\\Models\\User', 228),
(1, 'App\\Models\\User', 229),
(1, 'App\\Models\\User', 230),
(1, 'App\\Models\\User', 231),
(1, 'App\\Models\\User', 232),
(1, 'App\\Models\\User', 233),
(1, 'App\\Models\\User', 234),
(1, 'App\\Models\\User', 235),
(1, 'App\\Models\\User', 236),
(1, 'App\\Models\\User', 237),
(1, 'App\\Models\\User', 238),
(1, 'App\\Models\\User', 239),
(1, 'App\\Models\\User', 240),
(1, 'App\\Models\\User', 241),
(1, 'App\\Models\\User', 242),
(1, 'App\\Models\\User', 243),
(1, 'App\\Models\\User', 244),
(1, 'App\\Models\\User', 245),
(1, 'App\\Models\\User', 246),
(1, 'App\\Models\\User', 247),
(1, 'App\\Models\\User', 248),
(1, 'App\\Models\\User', 249),
(1, 'App\\Models\\User', 250),
(1, 'App\\Models\\User', 251),
(1, 'App\\Models\\User', 252),
(1, 'App\\Models\\User', 253),
(1, 'App\\Models\\User', 254),
(1, 'App\\Models\\User', 255),
(1, 'App\\Models\\User', 256),
(1, 'App\\Models\\User', 257),
(1, 'App\\Models\\User', 258),
(1, 'App\\Models\\User', 259),
(1, 'App\\Models\\User', 260),
(1, 'App\\Models\\User', 261),
(1, 'App\\Models\\User', 262),
(1, 'App\\Models\\User', 263),
(1, 'App\\Models\\User', 264),
(1, 'App\\Models\\User', 266),
(1, 'App\\Models\\User', 267),
(1, 'App\\Models\\User', 268),
(1, 'App\\Models\\User', 270),
(1, 'App\\Models\\User', 271),
(1, 'App\\Models\\User', 272),
(1, 'App\\Models\\User', 273),
(1, 'App\\Models\\User', 274),
(1, 'App\\Models\\User', 275),
(1, 'App\\Models\\User', 276),
(1, 'App\\Models\\User', 277),
(1, 'App\\Models\\User', 278),
(1, 'App\\Models\\User', 279),
(1, 'App\\Models\\User', 281),
(1, 'App\\Models\\User', 282),
(1, 'App\\Models\\User', 283),
(1, 'App\\Models\\User', 284),
(1, 'App\\Models\\User', 285),
(1, 'App\\Models\\User', 286),
(1, 'App\\Models\\User', 287),
(1, 'App\\Models\\User', 288),
(1, 'App\\Models\\User', 289),
(1, 'App\\Models\\User', 290),
(1, 'App\\Models\\User', 291),
(1, 'App\\Models\\User', 293),
(1, 'App\\Models\\User', 294),
(1, 'App\\Models\\User', 295),
(1, 'App\\Models\\User', 296),
(1, 'App\\Models\\User', 297),
(1, 'App\\Models\\User', 298),
(1, 'App\\Models\\User', 299),
(1, 'App\\Models\\User', 300),
(1, 'App\\Models\\User', 301),
(1, 'App\\Models\\User', 302),
(1, 'App\\Models\\User', 303),
(1, 'App\\Models\\User', 304),
(1, 'App\\Models\\User', 305),
(1, 'App\\Models\\User', 306),
(1, 'App\\Models\\User', 307),
(1, 'App\\Models\\User', 308),
(1, 'App\\Models\\User', 309),
(1, 'App\\Models\\User', 310),
(1, 'App\\Models\\User', 311),
(1, 'App\\Models\\User', 312),
(1, 'App\\Models\\User', 313),
(1, 'App\\Models\\User', 314),
(1, 'App\\Models\\User', 315),
(1, 'App\\Models\\User', 316),
(1, 'App\\Models\\User', 317),
(1, 'App\\Models\\User', 318),
(1, 'App\\Models\\User', 319),
(1, 'App\\Models\\User', 320),
(1, 'App\\Models\\User', 321),
(1, 'App\\Models\\User', 322),
(1, 'App\\Models\\User', 323),
(1, 'App\\Models\\User', 324),
(1, 'App\\Models\\User', 325),
(1, 'App\\Models\\User', 326),
(1, 'App\\Models\\User', 327),
(1, 'App\\Models\\User', 328),
(1, 'App\\Models\\User', 329),
(1, 'App\\Models\\User', 330),
(1, 'App\\Models\\User', 331),
(1, 'App\\Models\\User', 332),
(1, 'App\\Models\\User', 333),
(3, 'App\\Models\\User', 61),
(3, 'App\\Models\\User', 64),
(3, 'App\\Models\\User', 68),
(3, 'App\\Models\\User', 71),
(3, 'App\\Models\\User', 75),
(3, 'App\\Models\\User', 78),
(3, 'App\\Models\\User', 82),
(3, 'App\\Models\\User', 85),
(3, 'App\\Models\\User', 89),
(3, 'App\\Models\\User', 92),
(3, 'App\\Models\\User', 96),
(3, 'App\\Models\\User', 99),
(3, 'App\\Models\\User', 103),
(3, 'App\\Models\\User', 106),
(3, 'App\\Models\\User', 110),
(3, 'App\\Models\\User', 113),
(3, 'App\\Models\\User', 117),
(3, 'App\\Models\\User', 120),
(3, 'App\\Models\\User', 124),
(3, 'App\\Models\\User', 127),
(3, 'App\\Models\\User', 131),
(3, 'App\\Models\\User', 134),
(3, 'App\\Models\\User', 138),
(3, 'App\\Models\\User', 141),
(3, 'App\\Models\\User', 147),
(3, 'App\\Models\\User', 150),
(3, 'App\\Models\\User', 265),
(3, 'App\\Models\\User', 269),
(3, 'App\\Models\\User', 280),
(3, 'App\\Models\\User', 292);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(60) NOT NULL,
  `entity_type` varchar(60) NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `due_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `channel` enum('email','dashboard') NOT NULL DEFAULT 'dashboard',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_notifications_due` (`due_at`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_runs`
--

DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE IF NOT EXISTS `payroll_runs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `status` enum('draft','calculating','calculated','locked','pending_approval','approved','posted','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approval_required` tinyint(1) NOT NULL DEFAULT '0',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SAR',
  `total_employees` int NOT NULL DEFAULT '0',
  `total_gross` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_net` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED NOT NULL,
  `locked_by` bigint UNSIGNED DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint UNSIGNED DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `calculation_summary` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payroll_runs_created_by_foreign` (`created_by`),
  KEY `payroll_runs_locked_by_foreign` (`locked_by`),
  KEY `payroll_runs_approved_by_foreign` (`approved_by`),
  KEY `payroll_runs_posted_by_foreign` (`posted_by`),
  KEY `payroll_runs_status_index` (`status`),
  KEY `payroll_runs_pay_period_start_index` (`pay_period_start`),
  KEY `payroll_runs_pay_period_end_index` (`pay_period_end`),
  KEY `payroll_runs_pay_date_index` (`pay_date`),
  KEY `payroll_runs_status_pay_date_index` (`status`,`pay_date`)
) ENGINE=MyISAM AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_runs`
--

INSERT INTO `payroll_runs` (`id`, `title`, `description`, `pay_period_start`, `pay_period_end`, `pay_date`, `status`, `approval_required`, `currency`, `total_employees`, `total_gross`, `total_net`, `total_deductions`, `created_by`, `locked_by`, `locked_at`, `approved_by`, `approved_at`, `posted_by`, `posted_at`, `calculation_summary`, `notes`, `created_at`, `updated_at`) VALUES
(241, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'cancelled', 0, 'EGP', 0, 0.00, 0.00, 0.00, 338, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:11:05', '2025-10-03 10:06:41'),
(242, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 2, 12200.00, 11200.00, 1000.00, 339, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(243, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 340, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(244, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 341, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(245, 'Test Dec2025', NULL, '2025-12-01', '2025-12-31', '2025-12-31', 'cancelled', 0, 'EGP', 1, 2200.00, 2200.00, 0.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:08:52', '2025-10-03 10:09:21'),
(246, 'test dec 2026', NULL, '2025-12-01', '2025-12-31', '2025-12-31', 'cancelled', 0, 'EGP', 1, 2200.00, 2200.00, 0.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:11:19', '2025-10-03 10:20:55'),
(247, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 342, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(248, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 343, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(249, 'test dec 2025 2', NULL, '2025-12-01', '2025-12-31', '2025-12-31', 'cancelled', 0, 'EGP', 1, 2200.00, 2100.00, 100.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:21:35', '2025-10-03 12:39:37'),
(250, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 344, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(251, 'November Payroll', NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'calculated', 0, 'EGP', 1, 10000.00, 9000.00, 1000.00, 345, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(252, 'Jan 2025', NULL, '2026-01-01', '2026-01-31', '2026-01-31', 'calculated', 0, 'EGP', 1, 2200.00, 2000.00, 200.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 12:40:11', '2025-10-03 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

DROP TABLE IF EXISTS `payslips`;
CREATE TABLE IF NOT EXISTS `payslips` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_structure_id` bigint UNSIGNED DEFAULT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_arabic_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SAR',
  `gross_pay` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `basic_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','calculated','generated','sent','viewed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `generated_at` timestamp NULL DEFAULT NULL,
  `pdf_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_generated_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `calculation_breakdown` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payslips_payroll_run_id_employee_id_unique` (`payroll_run_id`,`employee_id`),
  KEY `payslips_employee_id_foreign` (`employee_id`),
  KEY `payslips_employee_code_index` (`employee_code`),
  KEY `payslips_status_index` (`status`),
  KEY `payslips_pay_date_index` (`pay_date`),
  KEY `payslips_payroll_run_id_employee_id_index` (`payroll_run_id`,`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payslips`
--

INSERT INTO `payslips` (`id`, `payroll_run_id`, `employee_id`, `salary_structure_id`, `employee_code`, `employee_name`, `employee_arabic_name`, `department_name`, `position_name`, `pay_period_start`, `pay_period_end`, `pay_date`, `currency`, `gross_pay`, `net_pay`, `total_deductions`, `basic_salary`, `status`, `generated_at`, `pdf_path`, `pdf_generated_at`, `sent_at`, `viewed_at`, `calculation_breakdown`, `notes`, `created_at`, `updated_at`) VALUES
(36, 242, 51, 6, 'EMP001', 'Khaled Helmy', 'خالد محمد حلمي محمد يسري', 'Information Technology', 'Senior Systems Engineer', '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 2200.00, 2200.00, 0.00, 1000.00, 'calculated', '2025-10-03 09:57:11', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(37, 242, 65, 11, 'EMP-68dfc827a971c', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 0.00, 'calculated', '2025-10-03 09:57:11', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(38, 243, 66, 12, 'EMP-68dfc84a4d7be', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 0.00, 'calculated', '2025-10-03 09:57:46', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(39, 244, 67, 13, 'EMP-68dfc8d41125f', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 0.00, 'calculated', '2025-10-03 10:00:04', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(40, 245, 51, 6, 'EMP001', 'Khaled Helmy', 'خالد محمد حلمي محمد يسري', 'Information Technology', 'Senior Systems Engineer', '2025-12-01', '2025-12-31', '2025-12-31', 'EGP', 2200.00, 2200.00, 0.00, 1000.00, 'calculated', '2025-10-03 10:08:58', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:08:58', '2025-10-03 10:08:59'),
(41, 246, 51, 6, 'EMP001', 'Khaled Helmy', 'خالد محمد حلمي محمد يسري', 'Information Technology', 'Senior Systems Engineer', '2025-12-01', '2025-12-31', '2025-12-31', 'EGP', 2200.00, 2200.00, 0.00, 1000.00, 'calculated', '2025-10-03 10:11:25', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(42, 247, 68, 14, 'EMP-68dfcc0423aca', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 10000.00, 'calculated', '2025-10-03 10:13:40', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(43, 248, 69, 15, 'EMP-68dfcd0c8c006', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 10000.00, 'calculated', '2025-10-03 10:18:04', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(44, 249, 51, 6, 'EMP001', 'Khaled Helmy', 'خالد محمد حلمي محمد يسري', 'Information Technology', 'Senior Systems Engineer', '2025-12-01', '2025-12-31', '2025-12-31', 'EGP', 2200.00, 2100.00, 100.00, 1000.00, 'calculated', '2025-10-03 10:21:41', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(45, 250, 70, 16, 'EMP-68dfcf02cbda7', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 10000.00, 'calculated', '2025-10-03 10:26:26', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(46, 251, 71, 17, 'EMP-68dfd1fa69f70', 'Test Employee', NULL, NULL, NULL, '2025-11-01', '2025-11-30', '2025-11-30', 'EGP', 10000.00, 9000.00, 1000.00, 10000.00, 'calculated', '2025-10-03 10:39:06', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(47, 252, 51, 6, 'EMP001', 'Khaled Helmy', 'خالد محمد حلمي محمد يسري', 'Information Technology', 'Senior Systems Engineer', '2026-01-01', '2026-01-31', '2026-01-31', 'EGP', 2200.00, 2000.00, 200.00, 1000.00, 'calculated', '2025-10-03 12:40:17', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-03 12:40:17', '2025-10-03 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `payslip_lines`
--

DROP TABLE IF EXISTS `payslip_lines`;
CREATE TABLE IF NOT EXISTS `payslip_lines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payslip_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED NOT NULL,
  `component_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_name_en` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_name_ar` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('earning','deduction','info') COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_mode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formula` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formula_used` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(15,4) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `priority` int NOT NULL DEFAULT '0',
  `priority_order` int NOT NULL DEFAULT '0',
  `include_in_gross` tinyint(1) NOT NULL DEFAULT '0',
  `taxable` tinyint(1) NOT NULL DEFAULT '0',
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  `calculation_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payslip_lines_salary_component_id_foreign` (`salary_component_id`),
  KEY `payslip_lines_component_type_index` (`component_type`),
  KEY `payslip_lines_priority_index` (`priority`),
  KEY `payslip_lines_payslip_id_component_type_index` (`payslip_id`,`component_type`),
  KEY `payslip_lines_component_code_index` (`component_code`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payslip_lines`
--

INSERT INTO `payslip_lines` (`id`, `payslip_id`, `salary_component_id`, `component_code`, `component_name_en`, `component_name_ar`, `component_name`, `component_type`, `calculation_mode`, `formula`, `formula_used`, `rate`, `amount`, `priority`, `priority_order`, `include_in_gross`, `taxable`, `is_taxable`, `calculation_notes`, `created_at`, `updated_at`) VALUES
(35, 36, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(36, 36, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', NULL, NULL, NULL, 0.00, 2, 2, 0, 0, 0, 'Computed via formula: n/a', '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(37, 36, 45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'Transportation Allowance', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1200.00, 3, 3, 1, 1, 1, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(38, 37, 63, 'BASIC_5BZ4LI', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(39, 37, 64, 'TAX_C0ESYL', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_5BZ4LI * 0.1', 'BASIC_5BZ4LI * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_5BZ4LI * 0.1', '2025-10-03 09:57:11', '2025-10-03 09:57:11'),
(40, 38, 65, 'BASIC_DCSCUW', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(41, 38, 66, 'TAX_VC6ZMT', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_DCSCUW * 0.1', 'BASIC_DCSCUW * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_DCSCUW * 0.1', '2025-10-03 09:57:46', '2025-10-03 09:57:46'),
(42, 39, 67, 'BASIC_F6PY6U', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(43, 39, 68, 'TAX_SQS3K1', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_F6PY6U * 0.1', 'BASIC_F6PY6U * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_F6PY6U * 0.1', '2025-10-03 10:00:04', '2025-10-03 10:00:04'),
(44, 40, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:08:59', '2025-10-03 10:08:59'),
(45, 40, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', NULL, NULL, NULL, 0.00, 2, 2, 0, 0, 0, 'Computed via formula: n/a', '2025-10-03 10:08:59', '2025-10-03 10:08:59'),
(46, 40, 45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'Transportation Allowance', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1200.00, 3, 3, 1, 1, 1, NULL, '2025-10-03 10:08:59', '2025-10-03 10:08:59'),
(47, 41, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(48, 41, 45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'Transportation Allowance', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1200.00, 2, 2, 1, 1, 1, NULL, '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(49, 41, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', NULL, NULL, NULL, 0.00, 3, 3, 0, 0, 0, 'Computed via formula: n/a', '2025-10-03 10:11:25', '2025-10-03 10:11:25'),
(50, 42, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(51, 42, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_SALARY * 0.1', 'BASIC_SALARY * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_SALARY * 0.1', '2025-10-03 10:13:40', '2025-10-03 10:13:40'),
(52, 43, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(53, 43, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_SALARY * 0.1', 'BASIC_SALARY * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_SALARY * 0.1', '2025-10-03 10:18:04', '2025-10-03 10:18:04'),
(54, 44, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(55, 44, 45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'Transportation Allowance', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1200.00, 2, 2, 1, 1, 1, NULL, '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(56, 44, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', NULL, NULL, NULL, 100.00, 3, 3, 0, 0, 0, 'Computed via formula: n/a', '2025-10-03 10:21:41', '2025-10-03 10:21:41'),
(57, 45, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(58, 45, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_SALARY * 0.1', 'BASIC_SALARY * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_SALARY * 0.1', '2025-10-03 10:26:26', '2025-10-03 10:26:26'),
(59, 46, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 10000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(60, 46, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_SALARY * 0.1', 'BASIC_SALARY * 0.1', NULL, 1000.00, 2, 2, 0, 0, 0, 'Computed via formula: BASIC_SALARY * 0.1', '2025-10-03 10:39:06', '2025-10-03 10:39:06'),
(61, 47, 43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'Basic Salary', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1000.00, 1, 1, 1, 1, 1, NULL, '2025-10-03 12:40:17', '2025-10-03 12:40:17'),
(62, 47, 45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'Transportation Allowance', 'earning', 'fixed', NULL, 'Fixed amount', NULL, 1200.00, 2, 2, 1, 1, 1, NULL, '2025-10-03 12:40:17', '2025-10-03 12:40:17'),
(63, 47, 49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'Income Tax', 'deduction', 'formula', 'BASIC_SALARY*0.2', 'BASIC_SALARY*0.2', NULL, 200.00, 3, 3, 0, 0, 0, 'Computed via formula: BASIC_SALARY*0.2', '2025-10-03 12:40:17', '2025-10-03 12:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `guard_name` varchar(50) NOT NULL DEFAULT 'web',
  `display_name` varchar(160) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'employee.view', 'web', 'View Employees', NULL, NULL),
(2, 'employee.edit', 'web', 'Edit Employees', NULL, NULL),
(3, 'contract.view', 'web', 'View Contracts', NULL, NULL),
(4, 'contract.edit', 'web', 'Edit Contracts', NULL, NULL),
(5, 'payroll.view', 'web', 'Payroll View', NULL, '2025-10-02 14:56:03'),
(6, 'payroll.view_net', 'web', 'View Net/Gross Payroll', NULL, NULL),
(7, 'payroll.edit', 'web', 'Edit Payroll', NULL, NULL),
(8, 'attendance.view', 'web', 'Attendance View', NULL, '2025-10-02 14:56:03'),
(9, 'attendance.edit', 'web', 'Edit Attendance', NULL, NULL),
(10, 'users.manage', 'web', 'Users Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(11, 'roles.manage', 'web', 'Roles Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(12, 'permissions.manage', 'web', 'Permissions Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(13, 'employees.view', 'web', 'Employees View', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(14, 'employees.manage', 'web', 'Employees Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(15, 'contracts.view', 'web', 'Contracts View', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(16, 'contracts.manage', 'web', 'Contracts Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(17, 'documents.view', 'web', 'Documents View', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(18, 'documents.manage', 'web', 'Documents Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(19, 'letters.view', 'web', 'Letters View', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(20, 'letters.manage', 'web', 'Letters Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(21, 'letters.generate', 'web', 'Letters Generate', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(22, 'reports.view', 'web', 'Reports View', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(23, 'payroll.manage', 'web', 'Payroll Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03'),
(24, 'attendance.manage', 'web', 'Attendance Manage', '2025-10-02 14:56:03', '2025-10-02 14:56:03');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `fk_pr_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(5, 2),
(6, 2),
(7, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_en` varchar(120) NOT NULL,
  `name_ar` varchar(120) NOT NULL,
  `category` enum('lawyer','admin') NOT NULL,
  `grade_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_positions_name_cat` (`name_en`,`category`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name_en`, `name_ar`, `category`, `grade_order`, `created_at`, `updated_at`) VALUES
(1, 'Managing Partner', 'شريك إداري', 'lawyer', 1, NULL, NULL),
(2, 'Senior Partner', 'شريك أول', 'lawyer', 2, NULL, NULL),
(3, 'Partner', 'شريك', 'lawyer', 3, NULL, NULL),
(4, 'Junior Partner', 'شريك مبتدئ', 'lawyer', 4, NULL, NULL),
(5, 'Senior Associate', 'محام أول', 'lawyer', 5, NULL, NULL),
(6, 'Associate', 'محام', 'lawyer', 6, NULL, NULL),
(7, 'Junior Associate', 'محام مبتدئ', 'lawyer', 7, NULL, NULL),
(8, 'Intern', 'متدرب', 'lawyer', 8, NULL, NULL),
(9, 'HR & Admin Manager', 'مدير الموارد البشرية والإدارة', 'admin', 1, NULL, NULL),
(10, 'Accounting Manager', 'مدير الحسابات', 'admin', 2, NULL, NULL),
(11, 'Accountant', 'محاسب', 'admin', 3, NULL, NULL),
(12, 'Senior Systems Engineer', 'مهندس نظم أول', 'admin', 4, NULL, NULL),
(13, 'Messenger', 'مراسل', 'admin', 5, NULL, NULL),
(14, 'Admin Assistant', 'مساعد إداري', 'admin', 6, NULL, NULL),
(15, 'Office Boy', 'عامل خدمات', 'admin', 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `guard_name` varchar(50) NOT NULL DEFAULT 'web',
  `display_name` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'HR_Admin_Manager', 'web', 'HR Admin Manager', NULL, '2025-10-02 14:56:03'),
(2, 'Accounting_Manager', 'web', 'Accounting Manager', NULL, NULL),
(3, 'HR_Coordinator', 'web', 'HR Coordinator', NULL, NULL),
(4, 'Accountant', 'web', 'Accountant', NULL, NULL),
(5, 'Employee', 'web', 'Employee', NULL, NULL),
(6, 'IT_Admin', 'web', 'IT Admin', NULL, '2025-10-02 14:56:03');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(5, 1),
(5, 2),
(5, 6),
(8, 1),
(8, 3),
(8, 6),
(10, 1),
(10, 6),
(11, 1),
(11, 6),
(12, 6),
(13, 1),
(13, 3),
(13, 6),
(14, 1),
(14, 3),
(14, 6),
(15, 1),
(15, 3),
(15, 6),
(16, 1),
(16, 3),
(16, 6),
(17, 1),
(17, 2),
(17, 3),
(17, 6),
(18, 1),
(18, 3),
(18, 6),
(19, 1),
(19, 3),
(19, 6),
(20, 1),
(20, 6),
(21, 1),
(21, 3),
(21, 6),
(22, 1),
(22, 2),
(22, 3),
(22, 5),
(22, 6),
(23, 6),
(24, 1),
(24, 6);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `role_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `fk_ru_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_components`
--

DROP TABLE IF EXISTS `salary_components`;
CREATE TABLE IF NOT EXISTS `salary_components` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL,
  `name_en` varchar(120) NOT NULL,
  `name_ar` varchar(120) NOT NULL,
  `comp_type` enum('earning','deduction','info') NOT NULL,
  `calc_mode` enum('fixed','formula','variable_net_based') NOT NULL,
  `taxable` tinyint(1) NOT NULL DEFAULT '1',
  `visible_to_roles` json DEFAULT NULL,
  `priority_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `salary_components`
--

INSERT INTO `salary_components` (`id`, `code`, `name_en`, `name_ar`, `comp_type`, `calc_mode`, `taxable`, `visible_to_roles`, `priority_order`, `created_at`, `updated_at`) VALUES
(43, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'earning', 'fixed', 1, NULL, 1, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(44, 'HOUSING_ALLOWANCE', 'Housing Allowance', 'بدل السكن', 'earning', 'fixed', 1, NULL, 2, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(45, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'earning', 'fixed', 1, NULL, 3, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(46, 'OVERTIME', 'Overtime', 'ساعات إضافية', 'earning', 'variable_net_based', 1, NULL, 4, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(47, 'BONUS', 'Bonus', 'مكافأة', 'earning', 'variable_net_based', 1, NULL, 5, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(48, 'SOCIAL_INSURANCE', 'Social Insurance Deduction', 'خصم التأمين الاجتماعي', 'deduction', 'formula', 0, NULL, 101, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(49, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'deduction', 'formula', 0, NULL, 102, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(50, 'ADVANCE_DEDUCTION', 'Advance Salary Deduction', 'خصم السلفة', 'deduction', 'variable_net_based', 0, NULL, 103, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(51, 'LOAN_DEDUCTION', 'Loan Deduction', 'خصم القرض', 'deduction', 'fixed', 0, NULL, 104, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(52, 'GROSS_SALARY', 'Gross Salary', 'إجمالي الراتب', 'info', 'formula', 0, '[\"HR_Admin_Manager\", \"Accounting_Manager\"]', 201, '2025-10-03 05:23:30', '2025-10-03 05:23:30'),
(53, 'NET_SALARY', 'Net Salary', 'صافي الراتب', 'info', 'formula', 0, '[\"HR_Admin_Manager\", \"Accounting_Manager\"]', 202, '2025-10-03 05:23:30', '2025-10-03 05:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `salary_structures`
--

DROP TABLE IF EXISTS `salary_structures`;
CREATE TABLE IF NOT EXISTS `salary_structures` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'EGP',
  `effective_from` date NOT NULL,
  `effective_to` date DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ss_employee_period` (`employee_id`,`effective_from`,`effective_to`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `salary_structures`
--

INSERT INTO `salary_structures` (`id`, `employee_id`, `currency`, `effective_from`, `effective_to`, `notes`, `created_at`, `updated_at`) VALUES
(5, 51, 'EGP', '2025-10-03', '2025-10-02', 'Playwright automated structure', '2025-10-03 07:28:16', '2025-10-03 07:30:13'),
(6, 51, 'EGP', '2025-10-03', NULL, NULL, '2025-10-03 07:30:13', '2025-10-03 07:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `salary_structure_components`
--

DROP TABLE IF EXISTS `salary_structure_components`;
CREATE TABLE IF NOT EXISTS `salary_structure_components` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `structure_id` bigint UNSIGNED NOT NULL,
  `component_id` bigint UNSIGNED NOT NULL,
  `value_numeric` decimal(12,2) DEFAULT NULL,
  `formula_expr` varchar(1000) DEFAULT NULL,
  `depends_on` json DEFAULT NULL,
  `priority_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ssc` (`structure_id`,`component_id`),
  KEY `fk_ssc_component` (`component_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `salary_structure_components`
--

INSERT INTO `salary_structure_components` (`id`, `structure_id`, `component_id`, `value_numeric`, `formula_expr`, `depends_on`, `priority_order`, `created_at`, `updated_at`) VALUES
(1, 5, 43, 1000.00, NULL, NULL, 1, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(2, 5, 44, 1100.00, NULL, NULL, 2, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(3, 5, 45, 1200.00, NULL, NULL, 3, '2025-10-03 07:28:16', '2025-10-03 07:28:16'),
(37, 6, 43, 1000.00, NULL, NULL, 1, '2025-10-03 12:39:18', '2025-10-03 12:39:18'),
(38, 6, 45, 1200.00, NULL, NULL, 2, '2025-10-03 12:39:18', '2025-10-03 12:39:18'),
(39, 6, 49, NULL, 'BASIC_SALARY*0.2', NULL, 3, '2025-10-03 12:39:18', '2025-10-03 12:39:18');

-- --------------------------------------------------------

--
-- Table structure for table `saved_reports`
--

DROP TABLE IF EXISTS `saved_reports`;
CREATE TABLE IF NOT EXISTS `saved_reports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` json DEFAULT NULL,
  `format` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'xlsx',
  `schedule` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipients` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saved_reports_user_id_index` (`user_id`),
  KEY `saved_reports_report_key_index` (`report_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(60) NOT NULL,
  `name_en` varchar(160) NOT NULL,
  `name_ar` varchar(160) NOT NULL,
  `type` enum('contract','payslip','letter','employment_proof') NOT NULL,
  `engine` enum('blade','twig') NOT NULL DEFAULT 'blade',
  `body` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_employee` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=346 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `employee_id`, `two_factor_secret`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'HR Administrator', 'hr@sarieldin.com', '$2y$12$c/XKOJzvj8v0djASQNafGeRLQdGLsAU1ugVM9rhP2HomW41DallfO', NULL, NULL, 'XcNocphV5vtPfRQH7Evs79vbmXpdqS3cFKFCdeDVT23HXoZ1NRLQv7YOExkS', '2025-09-12 13:17:47', '2025-09-12 13:17:47'),
(4, 'Payroll Admin', '6f59a942-d878-4df9-b791-ef4736b88e25@example.com', '$2y$04$DJehjYaE.AeyKLUftiD5TuZxF28V.N.G4MDQDcIUokYZni/k9pamm', NULL, NULL, NULL, '2025-10-01 14:03:21', '2025-10-01 14:03:21'),
(8, 'Payroll Admin', '4f9d902b-9c4c-4a97-b896-d7d97071118b@example.com', '$2y$04$Lg1gGSvEfUAH0WhgT1stqOFb2iTsl0bwNqnPPo44tDQhRfM10TxSq', NULL, NULL, NULL, '2025-10-01 14:04:32', '2025-10-01 14:04:32'),
(10, 'Payroll Admin', 'fccb8280-f92d-48c4-b401-4c3d894c322a@example.com', '$2y$04$jCj9jC4ap7NcasbnWNwCVOSPIcH4bOIT65w03iR1tqDao1FTcLPme', NULL, NULL, NULL, '2025-10-01 14:05:07', '2025-10-01 14:05:07'),
(12, 'Payroll Admin', 'a57a4495-92d6-4a44-bfd6-c207cf84edfe@example.com', '$2y$04$CO2OBgbr6T30oK5Yw7jMuuavSX7ERDiY5ThwYiATsTrTxw8EsR0JC', NULL, NULL, NULL, '2025-10-01 14:05:43', '2025-10-01 14:05:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees` ADD FULLTEXT KEY `ft_employees_name_email` (`first_name`,`last_name`,`arabic_name`,`email`,`code`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_events`
--
ALTER TABLE `approval_events`
  ADD CONSTRAINT `fk_ae_approver` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_ae_req` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `approval_requests`
--
ALTER TABLE `approval_requests`
  ADD CONSTRAINT `fk_ar_requester` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `attendance_logs`
--
ALTER TABLE `attendance_logs`
  ADD CONSTRAINT `fk_al_device` FOREIGN KEY (`device_id`) REFERENCES `attendance_devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_al_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contract_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_doc_contract` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_doc_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `document_tag`
--
ALTER TABLE `document_tag`
  ADD CONSTRAINT `fk_dt_doc` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dt_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_versions`
--
ALTER TABLE `document_versions`
  ADD CONSTRAINT `fk_docver_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_emp_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emp_manager` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_emp_pos` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_emp_type` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `employee_cases`
--
ALTER TABLE `employee_cases`
  ADD CONSTRAINT `fk_ec_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `fk_pr_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `fk_ru_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ru_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_structures`
--
ALTER TABLE `salary_structures`
  ADD CONSTRAINT `fk_ss_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_structure_components`
--
ALTER TABLE `salary_structure_components`
  ADD CONSTRAINT `fk_ssc_component` FOREIGN KEY (`component_id`) REFERENCES `salary_components` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ssc_structure` FOREIGN KEY (`structure_id`) REFERENCES `salary_structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
