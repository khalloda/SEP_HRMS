-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 05, 2025 at 11:39 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68e25207b4229@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:09:59', '2025-10-05 08:09:59'),
(2, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-create-68e25209d0e6a@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:01', '2025-10-05 08:10:01'),
(3, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 1, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll - October 2025\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:06', '2025-10-05 08:10:06'),
(4, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-export-68e2521026019@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:08', '2025-10-05 08:10:08'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"192b4e55-f406-452f-9b1b-9ff287b1c3da@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:10', '2025-10-05 08:10:10'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"76af3b89-e86a-4c28-910a-916e80eaec96@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:12', '2025-10-05 08:10:12'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68e2521672b37@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:14', '2025-10-05 08:10:14'),
(8, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 2, NULL, NULL, '{\"attributes\": {\"title\": \"October Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:14', '2025-10-05 08:10:14'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-68e252188f1b6@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:16', '2025-10-05 08:10:16'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\": {\"name\": \"Jody Kling Sr.\", \"email\": \"mueller.christopher@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(11, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 3, NULL, NULL, '{\"attributes\": {\"title\": \"Policy Test Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-09-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-08-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-08-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\": {\"name\": \"Breana Ernser\", \"email\": \"jgreenholt@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\": {\"name\": \"Myrtle Keeling\", \"email\": \"schristiansen@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(14, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 4, NULL, NULL, '{\"attributes\": {\"title\": \"Policy Test Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-09-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-08-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-08-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\": {\"name\": \"Mr. Geovanni Torphy MD\", \"email\": \"jerald.kreiger@example.org\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\": {\"name\": \"Allan Hartmann\", \"email\": \"collier.lavinia@example.org\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(17, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 5, NULL, NULL, '{\"attributes\": {\"title\": \"Policy Test Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-09-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-08-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-08-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\": {\"name\": \"Toni Wiza\", \"email\": \"benton80@example.org\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:24', '2025-10-05 08:10:24'),
(19, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 6, NULL, NULL, '{\"attributes\": {\"title\": \"Policy Test Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-09-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-08-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-08-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:24', '2025-10-05 08:10:24'),
(20, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 6, NULL, NULL, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-05 08:10:24', '2025-10-05 08:10:24'),
(21, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\": {\"name\": \"Queue Runner\", \"email\": \"queue-runner-68e2522317187@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:27', '2025-10-05 08:10:27'),
(22, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 7, NULL, NULL, '{\"attributes\": {\"title\": \"Queued Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:27', '2025-10-05 08:10:27'),
(23, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\": {\"name\": \"Inline Runner\", \"email\": \"inline-runner-68e252252bacb@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:29', '2025-10-05 08:10:29'),
(24, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 8, NULL, NULL, '{\"attributes\": {\"title\": \"Inline Run\", \"status\": \"draft\", \"pay_date\": \"2025-10-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-09-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-09-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:29', '2025-10-05 08:10:29'),
(25, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-caa6dea9-b5e1-4816-90b0-4781268fe6b4@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:35', '2025-10-05 08:10:35'),
(26, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 9, NULL, NULL, '{\"attributes\": {\"title\": \"Demo Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"25000.00\", \"total_gross\": \"30000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 3, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:35', '2025-10-05 08:10:35'),
(27, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-5d9dd3cd-3efb-4e16-9eaf-af5a90bc66bc@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:37', '2025-10-05 08:10:37'),
(28, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 10, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:37', '2025-10-05 08:10:37'),
(29, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-7e4678c3-e955-4d8f-be0f-6cce6676324b@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:39', '2025-10-05 08:10:39'),
(30, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 11, NULL, NULL, '{\"attributes\": {\"title\": \"Correlation Test Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"42000.00\", \"total_gross\": \"50000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 5, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:39', '2025-10-05 08:10:39'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-004653b3-29d4-424c-b450-85ab8ef7566a@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(32, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 12, NULL, NULL, '{\"attributes\": {\"title\": \"Lockable Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(33, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 12, 'App\\Models\\User', 19, '{\"old\": {\"status\": \"calculated\"}, \"attributes\": {\"status\": \"locked\"}}', NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(34, 'payroll_run', 'Payroll run locked', 'App\\Models\\PayrollRun', NULL, 12, 'App\\Models\\User', 19, '[]', NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(35, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-eb68289c-e3e3-46a5-86ca-35f4a0d7c508@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(36, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 13, NULL, NULL, '{\"attributes\": {\"title\": \"Unlockable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(37, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 13, 'App\\Models\\User', 20, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"calculated\"}}', NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(38, 'payroll_run', 'Payroll run unlocked', 'App\\Models\\PayrollRun', NULL, 13, 'App\\Models\\User', 20, '[]', NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(39, 'default', 'created', 'App\\Models\\User', 'created', 21, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-37b2e32d-a94b-4fd7-b46b-8948391adad1@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(40, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 14, NULL, NULL, '{\"attributes\": {\"title\": \"Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(41, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 14, 'App\\Models\\User', 21, '{\"old\": {\"status\": \"pending_approval\"}, \"attributes\": {\"status\": \"approved\"}}', NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(42, 'payroll_run', 'Payroll run approved', 'App\\Models\\PayrollRun', NULL, 14, 'App\\Models\\User', 21, '[]', NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(43, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-ef6a8df7-b2c2-4618-a4c2-6a43cdf755b5@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(44, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 15, NULL, NULL, '{\"attributes\": {\"title\": \"Postable Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(45, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 15, 'App\\Models\\User', 22, '{\"old\": {\"status\": \"locked\"}, \"attributes\": {\"status\": \"posted\"}}', NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(46, 'payroll_run', 'Payroll run posted', 'App\\Models\\PayrollRun', NULL, 15, 'App\\Models\\User', 22, '[]', NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(47, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-a43a1703-f8c5-440b-8a30-292fba929216@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(48, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 16, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelable Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"18000.00\", \"total_gross\": \"20000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 2, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(49, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 16, 'App\\Models\\User', 23, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(50, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 16, 'App\\Models\\User', 23, '{\"cancellation_reason\": \"Testing cancellation path\"}', NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(51, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-01ed24fe-c07e-428f-9539-c9837930d8b6@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(52, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 17, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"8000.00\", \"total_gross\": \"10000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(53, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 18, NULL, NULL, '{\"attributes\": {\"title\": \"Calculated Payroll\", \"status\": \"calculated\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9000.00\", \"total_gross\": \"12000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(54, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 19, NULL, NULL, '{\"attributes\": {\"title\": \"Locked Payroll\", \"status\": \"locked\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"9500.00\", \"total_gross\": \"13000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(55, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 20, NULL, NULL, '{\"attributes\": {\"title\": \"Pending Approval Payroll\", \"status\": \"pending_approval\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"10000.00\", \"total_gross\": \"14000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(56, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 21, NULL, NULL, '{\"attributes\": {\"title\": \"Approved Payroll\", \"status\": \"approved\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"11000.00\", \"total_gross\": \"15000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(57, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-cfde593b-bc09-4fcc-bf9d-295fa6bc0a3a@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(58, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 22, NULL, NULL, '{\"attributes\": {\"title\": \"Cancelled Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"900.00\", \"total_gross\": \"1200.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(59, 'payroll_run', 'Payroll run updated', 'App\\Models\\PayrollRun', 'updated', 22, NULL, NULL, '{\"old\": {\"status\": \"draft\"}, \"attributes\": {\"status\": \"cancelled\"}}', NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(60, 'payroll_run', 'Payroll run cancelled', 'App\\Models\\PayrollRun', NULL, 22, 'App\\Models\\User', 25, '{\"cancellation_reason\": \"Budget constraints\"}', NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(61, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator\", \"email\": \"coordinator-028c5aca-ba1b-442e-a8ba-2e453dbb6bc7@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:56', '2025-10-05 08:10:56'),
(62, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 23, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"800.00\", \"total_gross\": \"1000.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 1, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:56', '2025-10-05 08:10:56'),
(63, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payroll-admin-596b6c10-0725-4978-85f6-8cd62125d817@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:10:58', '2025-10-05 08:10:58'),
(64, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 24, NULL, NULL, '{\"attributes\": {\"title\": \"Draft Payroll\", \"status\": \"draft\", \"pay_date\": \"2025-11-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2025-10-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2025-10-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:10:58', '2025-10-05 08:10:58'),
(65, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-store-68e25244af864@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:00', '2025-10-05 08:11:00'),
(66, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Creator\", \"email\": \"payroll-overlap-68e252476b5e7@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:03', '2025-10-05 08:11:03'),
(67, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 25, NULL, NULL, '{\"attributes\": {\"title\": \"February 2026 Payroll\", \"status\": \"draft\", \"pay_date\": \"2026-03-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2026-03-01T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2026-02-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:11:03', '2025-10-05 08:11:03'),
(68, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{\"attributes\": {\"name\": \"Brenden Streich\", \"email\": \"landen.rath@example.org\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:05', '2025-10-05 08:11:05'),
(69, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 26, NULL, NULL, '{\"attributes\": {\"title\": \"May 2099 Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-06-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-05-31T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-05-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:11:05', '2025-10-05 08:11:05'),
(70, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{\"attributes\": {\"name\": \"Prof. Immanuel Kiehn III\", \"email\": \"aisha.bernhard@example.net\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:07', '2025-10-05 08:11:07'),
(71, 'payroll_run', 'Payroll run created', 'App\\Models\\PayrollRun', 'created', 27, NULL, NULL, '{\"attributes\": {\"title\": \"June 2099 Payroll\", \"status\": \"draft\", \"pay_date\": \"2099-07-05T00:00:00.000000Z\", \"total_net\": \"0.00\", \"total_gross\": \"0.00\", \"pay_period_end\": \"2099-06-30T00:00:00.000000Z\", \"total_employees\": 0, \"pay_period_start\": \"2099-06-01T00:00:00.000000Z\", \"approval_required\": null}}', NULL, '2025-10-05 08:11:07', '2025-10-05 08:11:07'),
(72, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68e2524e009b0@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:10', '2025-10-05 08:11:10'),
(73, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68e2525014ad4@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:12', '2025-10-05 08:11:12'),
(74, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{\"attributes\": {\"name\": \"Payroll Admin\", \"email\": \"payslip-export-68e25252252a8@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:14', '2025-10-05 08:11:14'),
(75, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68e252543a8cd@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:16', '2025-10-05 08:11:16'),
(76, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{\"attributes\": {\"name\": \"Coordinator User\", \"email\": \"coordinator-68e252564ef78@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:18', '2025-10-05 08:11:18'),
(77, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68e25256504c2@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:18', '2025-10-05 08:11:18'),
(78, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{\"attributes\": {\"name\": \"Admin User\", \"email\": \"admin-68e2525861016@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:20', '2025-10-05 08:11:20'),
(79, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{\"attributes\": {\"name\": \"Coord User\", \"email\": \"coord-68e2525a71ceb@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:22', '2025-10-05 08:11:22'),
(80, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{\"attributes\": {\"name\": \"Creator User\", \"email\": \"creator-68e2525a72f0b@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:22', '2025-10-05 08:11:22'),
(81, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{\"attributes\": {\"name\": \"Rtl User\", \"email\": \"rtl-68e2525c82c05@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:24', '2025-10-05 08:11:24'),
(82, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{\"attributes\": {\"name\": \"HR Manager\", \"email\": \"hr-manager-68e2527185623@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:45', '2025-10-05 08:11:45'),
(83, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68e252739d873@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:47', '2025-10-05 08:11:47'),
(84, 'default', 'created', 'App\\Models\\User', 'created', 44, NULL, NULL, '{\"attributes\": {\"name\": \"HR_Admin_Manager User\", \"email\": \"HR_Admin_Manager-68e25275b01ac@example.com\", \"employee_id\": null}}', NULL, '2025-10-05 08:11:49', '2025-10-05 08:11:49');

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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(13, '2025_09_13_113922_create_payroll_runs_table', 1),
(14, '2025_09_13_113953_create_payslips_table', 1),
(15, '2025_09_13_114025_create_payslip_lines_table', 1),
(16, '2025_09_13_143628_create_attendance_records_table', 1),
(17, '2025_09_13_143656_create_attendance_summaries_table', 1),
(18, '2025_09_13_193902_create_letter_templates_table', 1),
(19, '2025_09_13_193930_create_generated_letters_table', 1);

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
(1, 'App\\Models\\User', 11),
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
(1, 'App\\Models\\User', 27),
(1, 'App\\Models\\User', 28),
(1, 'App\\Models\\User', 29),
(1, 'App\\Models\\User', 30),
(1, 'App\\Models\\User', 31),
(1, 'App\\Models\\User', 32),
(1, 'App\\Models\\User', 33),
(1, 'App\\Models\\User', 34),
(1, 'App\\Models\\User', 35),
(1, 'App\\Models\\User', 37),
(1, 'App\\Models\\User', 38),
(1, 'App\\Models\\User', 40),
(1, 'App\\Models\\User', 41),
(1, 'App\\Models\\User', 42),
(1, 'App\\Models\\User', 43),
(1, 'App\\Models\\User', 44),
(2, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 26),
(3, 'App\\Models\\User', 36),
(3, 'App\\Models\\User', 39);

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_runs`
--

INSERT INTO `payroll_runs` (`id`, `title`, `description`, `pay_period_start`, `pay_period_end`, `pay_date`, `status`, `currency`, `total_employees`, `total_gross`, `total_net`, `total_deductions`, `created_by`, `locked_by`, `locked_at`, `approved_by`, `approved_at`, `posted_by`, `posted_at`, `calculation_summary`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Demo Payroll - October 2025', 'Sample payroll run seeded for demo purposes.', '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 0, 0.00, 0.00, 0.00, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:06', '2025-10-05 08:10:06'),
(2, 'October Payroll', 'Automated test payroll run.', '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 5, 50000.00, 42000.00, 8000.00, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:14', '2025-10-05 08:10:14'),
(3, 'Policy Test Payroll', 'Policy test baseline', '2099-08-01', '2099-08-31', '2099-09-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(4, 'Policy Test Payroll', 'Policy test baseline', '2099-08-01', '2099-08-31', '2099-09-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(5, 'Policy Test Payroll', 'Policy test baseline', '2099-08-01', '2099-08-31', '2099-09-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(6, 'Policy Test Payroll', 'Policy test baseline', '2099-08-01', '2099-08-31', '2099-09-05', 'locked', 'EGP', 0, 0.00, 0.00, 0.00, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:24', '2025-10-05 08:10:24'),
(7, 'Queued Run', NULL, '2025-09-01', '2025-09-30', '2025-10-05', 'draft', 'USD', 0, 0.00, 0.00, 0.00, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:27', '2025-10-05 08:10:27'),
(8, 'Inline Run', NULL, '2025-09-01', '2025-09-30', '2025-10-05', 'draft', 'USD', 0, 0.00, 0.00, 0.00, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:29', '2025-10-05 08:10:29'),
(9, 'Demo Payroll', 'Automated test payroll run.', '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 3, 30000.00, 25000.00, 5000.00, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:35', '2025-10-05 08:10:35'),
(10, 'Draft Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'draft', 'SAR', 0, 0.00, 0.00, 0.00, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:37', '2025-10-05 08:10:37'),
(11, 'Correlation Test Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 5, 50000.00, 42000.00, 8000.00, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:39', '2025-10-05 08:10:39'),
(12, 'Lockable Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'locked', 'SAR', 2, 20000.00, 18000.00, 2000.00, 19, 19, '2025-10-05 08:10:41', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(13, 'Unlockable Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 2, 20000.00, 18000.00, 2000.00, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(14, 'Approval Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'approved', 'SAR', 2, 20000.00, 18000.00, 2000.00, 21, 21, '2025-10-05 08:10:45', 21, '2025-10-05 08:10:45', NULL, NULL, NULL, NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(15, 'Postable Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'posted', 'SAR', 2, 20000.00, 18000.00, 2000.00, 22, 22, '2025-10-05 08:10:48', NULL, NULL, 22, '2025-10-05 08:10:48', NULL, NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(16, 'Cancelable Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'cancelled', 'SAR', 2, 20000.00, 18000.00, 2000.00, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(17, 'Draft Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'draft', 'SAR', 1, 10000.00, 8000.00, 2000.00, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(18, 'Calculated Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'calculated', 'SAR', 1, 12000.00, 9000.00, 3000.00, 24, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(19, 'Locked Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'locked', 'SAR', 1, 13000.00, 9500.00, 3500.00, 24, 24, '2025-10-05 08:10:52', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(20, 'Pending Approval Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'pending_approval', 'SAR', 1, 14000.00, 10000.00, 4000.00, 24, 24, '2025-10-05 08:10:52', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(21, 'Approved Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'approved', 'SAR', 1, 15000.00, 11000.00, 4000.00, 24, NULL, NULL, 24, '2025-10-05 08:10:52', NULL, NULL, NULL, NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(22, 'Cancelled Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'cancelled', 'SAR', 1, 1200.00, 900.00, 300.00, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(23, 'Draft Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'draft', 'SAR', 1, 1000.00, 800.00, 200.00, 26, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:56', '2025-10-05 08:10:56'),
(24, 'Draft Payroll', NULL, '2025-10-01', '2025-10-31', '2025-11-05', 'draft', 'SAR', 0, 0.00, 0.00, 0.00, 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:10:58', '2025-10-05 08:10:58'),
(25, 'February 2026 Payroll', 'Existing payroll run', '2026-02-01', '2026-03-01', '2026-03-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:11:03', '2025-10-05 08:11:03'),
(26, 'May 2099 Payroll', 'Editable payroll run', '2099-05-01', '2099-05-31', '2099-06-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:11:05', '2025-10-05 08:11:05'),
(27, 'June 2099 Payroll', 'Invalid currency scenario', '2099-06-01', '2099-06-30', '2099-07-05', 'draft', 'EGP', 0, 0.00, 0.00, 0.00, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 08:11:07', '2025-10-05 08:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

DROP TABLE IF EXISTS `payslips`;
CREATE TABLE IF NOT EXISTS `payslips` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `employee_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_period_start` date NOT NULL,
  `pay_period_end` date NOT NULL,
  `pay_date` date NOT NULL,
  `gross_pay` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','calculated','generated','sent','viewed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_lines`
--

DROP TABLE IF EXISTS `payslip_lines`;
CREATE TABLE IF NOT EXISTS `payslip_lines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payslip_id` bigint UNSIGNED NOT NULL,
  `salary_component_id` bigint UNSIGNED NOT NULL,
  `component_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('earning','deduction','info') COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculation_mode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formula` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(15,4) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `priority` int NOT NULL DEFAULT '0',
  `is_taxable` tinyint(1) NOT NULL DEFAULT '0',
  `calculation_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payslip_lines_salary_component_id_foreign` (`salary_component_id`),
  KEY `payslip_lines_component_type_index` (`component_type`),
  KEY `payslip_lines_priority_index` (`priority`),
  KEY `payslip_lines_payslip_id_component_type_index` (`payslip_id`,`component_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'users.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(2, 'roles.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(3, 'permissions.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(4, 'employees.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(5, 'employees.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(6, 'contracts.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(7, 'contracts.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(8, 'documents.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(9, 'documents.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(10, 'letters.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(11, 'letters.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(12, 'letters.generate', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(13, 'reports.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(14, 'payroll.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(15, 'payroll.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(16, 'attendance.view', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(17, 'attendance.manage', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26');

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
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'HR_Admin_Manager', 'web', '2025-10-05 08:09:59', '2025-10-05 08:09:59'),
(2, 'Accounting_Manager', 'web', '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(3, 'HR_Coordinator', 'web', '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(4, 'IT_Admin', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26'),
(5, 'Employee', 'web', '2025-10-05 08:11:26', '2025-10-05 08:11:26');

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
(1, 1),
(1, 4),
(2, 1),
(2, 4),
(3, 4),
(4, 1),
(4, 3),
(4, 4),
(5, 1),
(5, 3),
(5, 4),
(6, 1),
(6, 3),
(6, 4),
(7, 1),
(7, 3),
(7, 4),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(9, 1),
(9, 3),
(9, 4),
(10, 1),
(10, 3),
(10, 4),
(11, 1),
(11, 4),
(12, 1),
(12, 3),
(12, 4),
(13, 1),
(13, 2),
(13, 3),
(13, 4),
(13, 5),
(14, 1),
(14, 2),
(14, 4),
(15, 4),
(16, 1),
(16, 3),
(16, 4),
(17, 1),
(17, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Payroll Creator', 'payroll-create-68e25207b4229@example.com', NULL, '$2y$04$flx/kGYcFFCFOocNO7dT3e9B8eY3dgR3GvkmWxDuN3iLwMalRfQaG', NULL, '2025-10-05 08:09:59', '2025-10-05 08:09:59'),
(2, 'Payroll Creator', 'payroll-create-68e25209d0e6a@example.com', NULL, '$2y$04$YOcUaz2J1qOhY1eXLRRvleakXWru.AyMFsFcnMHyZ8xpsfxrdDBp.', NULL, '2025-10-05 08:10:01', '2025-10-05 08:10:01'),
(3, 'Payroll Admin', 'payroll-export-68e2521026019@example.com', NULL, '$2y$04$PXoicCndgfvdAVDbiTcT/udAktOdyvZuKIOWsBliJeaGLc7lSzLFS', NULL, '2025-10-05 08:10:08', '2025-10-05 08:10:08'),
(4, 'Payroll Admin', '192b4e55-f406-452f-9b1b-9ff287b1c3da@example.com', NULL, '$2y$04$H3dmxHqV0XekErpRkmlKbeTWrHG9HK/xAANO2658j1Y7Q5x9.5liO', NULL, '2025-10-05 08:10:10', '2025-10-05 08:10:10'),
(5, 'Payroll Admin', '76af3b89-e86a-4c28-910a-916e80eaec96@example.com', NULL, '$2y$04$f3rbnmeZ2zIND.bFpTIFd.DaXZU9JZk88U4Z5el3f/vQ4Qh7pk.6y', NULL, '2025-10-05 08:10:12', '2025-10-05 08:10:12'),
(6, 'Payroll Admin', 'payroll-admin-68e2521672b37@example.com', NULL, '$2y$04$LvvIklzjVEPWl5RENWz7XOWy5rUcmNTSmeqAZdKZubtJwiUzTZnpi', NULL, '2025-10-05 08:10:14', '2025-10-05 08:10:14'),
(7, 'Payroll Admin', 'payroll-admin-68e252188f1b6@example.com', NULL, '$2y$04$Gj1roAJJhFUZgtHkFY9n3.jOZdRb3L38Hq0hVws4PbEjUZE3GiAUy', NULL, '2025-10-05 08:10:16', '2025-10-05 08:10:16'),
(8, 'Jody Kling Sr.', 'mueller.christopher@example.com', '2025-10-05 08:10:18', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', '1HAusA3RIl', '2025-10-05 08:10:18', '2025-10-05 08:10:18'),
(9, 'Breana Ernser', 'jgreenholt@example.com', '2025-10-05 08:10:20', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', '5ypYNitUme', '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(10, 'Myrtle Keeling', 'schristiansen@example.com', '2025-10-05 08:10:20', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', 'VneMRIgeeJ', '2025-10-05 08:10:20', '2025-10-05 08:10:20'),
(11, 'Mr. Geovanni Torphy MD', 'jerald.kreiger@example.org', '2025-10-05 08:10:22', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', '5O6dnOgeJx', '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(12, 'Allan Hartmann', 'collier.lavinia@example.org', '2025-10-05 08:10:22', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', 'B7DmsxHhHu', '2025-10-05 08:10:22', '2025-10-05 08:10:22'),
(13, 'Toni Wiza', 'benton80@example.org', '2025-10-05 08:10:24', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', 'Ac66RXpt3w', '2025-10-05 08:10:24', '2025-10-05 08:10:24'),
(14, 'Queue Runner', 'queue-runner-68e2522317187@example.com', NULL, '$2y$04$5Nw72piJXTN5i8OKIyGQJ.gxpeDMne6alL6gPANJ3fi5lGCX2YBCG', NULL, '2025-10-05 08:10:27', '2025-10-05 08:10:27'),
(15, 'Inline Runner', 'inline-runner-68e252252bacb@example.com', NULL, '$2y$04$lJ8nTZ2sF1hvtl.VKZpz9emg9dXxtvB7pP2ZRdMvbJv5LIdqAjJJe', NULL, '2025-10-05 08:10:29', '2025-10-05 08:10:29'),
(16, 'Payroll Admin', 'payroll-admin-caa6dea9-b5e1-4816-90b0-4781268fe6b4@example.com', NULL, '$2y$04$591scXif3uP0D19hud..Q.pR/f.4X4RLXgJqlkwJSpMovYzztY49W', NULL, '2025-10-05 08:10:35', '2025-10-05 08:10:35'),
(17, 'Payroll Admin', 'payroll-admin-5d9dd3cd-3efb-4e16-9eaf-af5a90bc66bc@example.com', NULL, '$2y$04$2QkzXAeQvOBSG6sAemr9TuVC4s7dOeS4kJGqUSispvG99OJWC7tDC', NULL, '2025-10-05 08:10:37', '2025-10-05 08:10:37'),
(18, 'Payroll Admin', 'payroll-admin-7e4678c3-e955-4d8f-be0f-6cce6676324b@example.com', NULL, '$2y$04$u8XKyBjtvFyR1zPHE3/GYulQ0Bsrfv1bh/TYs7QHINC/Nk4b4Pxja', NULL, '2025-10-05 08:10:39', '2025-10-05 08:10:39'),
(19, 'Payroll Admin', 'payroll-admin-004653b3-29d4-424c-b450-85ab8ef7566a@example.com', NULL, '$2y$04$JUT8uYks2amHos1DLK9KM.OU3puuKUYCxkiolwCA82.d7ch8Yz6Aq', NULL, '2025-10-05 08:10:41', '2025-10-05 08:10:41'),
(20, 'Payroll Admin', 'payroll-admin-eb68289c-e3e3-46a5-86ca-35f4a0d7c508@example.com', NULL, '$2y$04$EcMyQ1eyYcn5IxR7XoNuN./S.7iuoHmh4IEqg37iQUH76U1CXFOY6', NULL, '2025-10-05 08:10:43', '2025-10-05 08:10:43'),
(21, 'Payroll Admin', 'payroll-admin-37b2e32d-a94b-4fd7-b46b-8948391adad1@example.com', NULL, '$2y$04$kgB1sRTTeRC9rq/33XqqO.ab.pOmyd/gND8InqTGA4xblpBlOr7F6', NULL, '2025-10-05 08:10:45', '2025-10-05 08:10:45'),
(22, 'Payroll Admin', 'payroll-admin-ef6a8df7-b2c2-4618-a4c2-6a43cdf755b5@example.com', NULL, '$2y$04$aNqYN4cuX9nHumaJ0idrSuSdRV4TvOrui7Vg4GHeChux3hXHPFOa.', NULL, '2025-10-05 08:10:48', '2025-10-05 08:10:48'),
(23, 'Payroll Admin', 'payroll-admin-a43a1703-f8c5-440b-8a30-292fba929216@example.com', NULL, '$2y$04$QEgE5/nDXsdosVVodyS6uOHsX0XZ6xIEtR0bRX3RXPgECthVJhgJa', NULL, '2025-10-05 08:10:50', '2025-10-05 08:10:50'),
(24, 'Payroll Admin', 'payroll-admin-01ed24fe-c07e-428f-9539-c9837930d8b6@example.com', NULL, '$2y$04$9fuE.IqhqembjqrLQsc8CeX8T0cRowETuEaGo8J5.n7fvSgFcd3oq', NULL, '2025-10-05 08:10:52', '2025-10-05 08:10:52'),
(25, 'Payroll Admin', 'payroll-admin-cfde593b-bc09-4fcc-bf9d-295fa6bc0a3a@example.com', NULL, '$2y$04$xXSK3uL84XsQiB8uwl7b0umTbR/rTMyqthq7FW8nOw73wjFhuF6Ku', NULL, '2025-10-05 08:10:54', '2025-10-05 08:10:54'),
(26, 'Coordinator', 'coordinator-028c5aca-ba1b-442e-a8ba-2e453dbb6bc7@example.com', NULL, '$2y$04$kGuyeBWLcN261F8pjUENWOWJxNKX/.taGYGPWeC5J2GsfQ4us29i6', NULL, '2025-10-05 08:10:56', '2025-10-05 08:10:56'),
(27, 'Payroll Admin', 'payroll-admin-596b6c10-0725-4978-85f6-8cd62125d817@example.com', NULL, '$2y$04$mmaT2KAMoOnzZbChg4tg5.nUlQz3nlr0W/TnL1rWLgB8MYcgV61ii', NULL, '2025-10-05 08:10:58', '2025-10-05 08:10:58'),
(28, 'Payroll Creator', 'payroll-store-68e25244af864@example.com', NULL, '$2y$04$jHlAjWlUIOKO76lIp0SSW.LkHpNSCPtfk7PMgxGQ7du/aBb5DFa56', NULL, '2025-10-05 08:11:00', '2025-10-05 08:11:00'),
(29, 'Payroll Creator', 'payroll-overlap-68e252476b5e7@example.com', NULL, '$2y$04$fGKTmzrgl0.QHsD8EQIJieMZhORrZaZ6eQT/eurpOUGVeQD7NCNhO', NULL, '2025-10-05 08:11:03', '2025-10-05 08:11:03'),
(30, 'Brenden Streich', 'landen.rath@example.org', '2025-10-05 08:11:05', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', 'w6D0RDRDlx', '2025-10-05 08:11:05', '2025-10-05 08:11:05'),
(31, 'Prof. Immanuel Kiehn III', 'aisha.bernhard@example.net', '2025-10-05 08:11:07', '$2y$04$sYuThiA0cMr7mjGMTXGEouoZbcTui5VazVKk7h3V1vDMwqu6CWkU.', 'nGsuwEgdnn', '2025-10-05 08:11:07', '2025-10-05 08:11:07'),
(32, 'Payroll Admin', 'payslip-export-68e2524e009b0@example.com', NULL, '$2y$04$guGiNJTs9dQJJsUdT7qhF.ceMS4V32zX.h2rXmoSPlBUY/zajfD9m', NULL, '2025-10-05 08:11:10', '2025-10-05 08:11:10'),
(33, 'Payroll Admin', 'payslip-export-68e2525014ad4@example.com', NULL, '$2y$04$IpWlO1YFNd1XXY1CXu9SNOOUsaVtwhQzAF20/Uf49yN.6QdmapHX.', NULL, '2025-10-05 08:11:12', '2025-10-05 08:11:12'),
(34, 'Payroll Admin', 'payslip-export-68e25252252a8@example.com', NULL, '$2y$04$Im4B8hST02BJF7beUdD4Xeg/o7VLxkyt7XI0kRG.OIOvTX/lxNHda', NULL, '2025-10-05 08:11:14', '2025-10-05 08:11:14'),
(35, 'Admin User', 'admin-68e252543a8cd@example.com', NULL, '$2y$04$lacfuaGdA0Ucxbx7qQ5icuzUDCzLbc.DH01No7dy3s7tABD9YkpLO', NULL, '2025-10-05 08:11:16', '2025-10-05 08:11:16'),
(36, 'Coordinator User', 'coordinator-68e252564ef78@example.com', NULL, '$2y$04$jyFnDlWaDYtmeUUb5HRRlOkc9/QVvavEne/JJ1X8Apeu2BKc/AbGm', NULL, '2025-10-05 08:11:18', '2025-10-05 08:11:18'),
(37, 'Creator User', 'creator-68e25256504c2@example.com', NULL, '$2y$04$WiLVF5JBVmiG5VlDEs2xAed42xRpheD0pnaESP/FwWnCgB/m07Rd2', NULL, '2025-10-05 08:11:18', '2025-10-05 08:11:18'),
(38, 'Admin User', 'admin-68e2525861016@example.com', NULL, '$2y$04$s2DMePD2gvvCloJ2mK7Zo.pZ0H2CzzNbHlziwf/15CMuJZO8CMg7C', NULL, '2025-10-05 08:11:20', '2025-10-05 08:11:20'),
(39, 'Coord User', 'coord-68e2525a71ceb@example.com', NULL, '$2y$04$eOv3A/4xY2Ma1oX63nsYaOGdufdkaqEB9SxwiZOOerj6eGT.XO9l2', NULL, '2025-10-05 08:11:22', '2025-10-05 08:11:22'),
(40, 'Creator User', 'creator-68e2525a72f0b@example.com', NULL, '$2y$04$ulqcJlUQlL5GEM4ayQykCuMbhcAwSZgv7HV7D.N8Zoun3.9RoPdKq', NULL, '2025-10-05 08:11:22', '2025-10-05 08:11:22'),
(41, 'Rtl User', 'rtl-68e2525c82c05@example.com', NULL, '$2y$04$pVYE5Qz.p/lzYuuJfL77zeHNXp6SMk4.q1ZwrWcX6ElHpc9G9yvay', NULL, '2025-10-05 08:11:24', '2025-10-05 08:11:24'),
(42, 'HR Manager', 'hr-manager-68e2527185623@example.com', NULL, '$2y$04$EZpWjlEU8dCr.59YFISgKuIUzBBsR/Zqfqw6EgKPOJg6fuf7kO0z2', NULL, '2025-10-05 08:11:45', '2025-10-05 08:11:45'),
(43, 'HR_Admin_Manager User', 'HR_Admin_Manager-68e252739d873@example.com', NULL, '$2y$04$xKUHRhP/yhtP2pCgc7yrluMB0y4zN9WLSUGpUIg2SxyGst4Z6XMbS', NULL, '2025-10-05 08:11:47', '2025-10-05 08:11:47'),
(44, 'HR_Admin_Manager User', 'HR_Admin_Manager-68e25275b01ac@example.com', NULL, '$2y$04$i.Z0HD9ruUaKgtOk8ctPJunplEsDZ4vqceCJ3E.dRVIGvaYI5CXAa', NULL, '2025-10-05 08:11:49', '2025-10-05 08:11:49');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
