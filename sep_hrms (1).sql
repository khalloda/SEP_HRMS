-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 13, 2025 at 11:42 AM
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
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `event`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'authentication', 'User logged out', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-12 17:13:27', '2025-09-12 17:13:27'),
(2, 'authentication', 'User logged in', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-12 17:14:37', '2025-09-12 17:14:37'),
(3, 'authentication', 'User logged out', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-12 18:10:49', '2025-09-12 18:10:49'),
(4, 'authentication', 'User logged in', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-12 18:10:55', '2025-09-12 18:10:55'),
(5, 'authentication', 'User logged in', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 01:58:55', '2025-09-13 01:58:55'),
(6, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"BASIC_SALARY\", \"name_ar\": \"الراتب الأساسي\", \"name_en\": \"Basic Salary\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 1}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(7, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"HOUSING_ALLOWANCE\", \"name_ar\": \"بدل السكن\", \"name_en\": \"Housing Allowance\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 2}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(8, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"TRANSPORT_ALLOWANCE\", \"name_ar\": \"بدل المواصلات\", \"name_en\": \"Transportation Allowance\", \"taxable\": true, \"calc_mode\": \"fixed\", \"comp_type\": \"earning\", \"priority_order\": 3}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(9, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"OVERTIME\", \"name_ar\": \"العمل الإضافي\", \"name_en\": \"Overtime\", \"taxable\": true, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"earning\", \"priority_order\": 4}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(10, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"BONUS\", \"name_ar\": \"المكافأة\", \"name_en\": \"Bonus\", \"taxable\": true, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"earning\", \"priority_order\": 5}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(11, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"SOCIAL_INSURANCE\", \"name_ar\": \"خصم التأمين الاجتماعي\", \"name_en\": \"Social Insurance Deduction\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 101}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(12, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"INCOME_TAX\", \"name_ar\": \"ضريبة الدخل\", \"name_en\": \"Income Tax\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"deduction\", \"priority_order\": 102}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(13, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"ADVANCE_DEDUCTION\", \"name_ar\": \"خصم السلفة\", \"name_en\": \"Advance Salary Deduction\", \"taxable\": false, \"calc_mode\": \"variable_net_based\", \"comp_type\": \"deduction\", \"priority_order\": 103}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(14, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"LOAN_DEDUCTION\", \"name_ar\": \"قسط القرض\", \"name_en\": \"Loan Deduction\", \"taxable\": false, \"calc_mode\": \"fixed\", \"comp_type\": \"deduction\", \"priority_order\": 104}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(15, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"GROSS_SALARY\", \"name_ar\": \"إجمالي الراتب\", \"name_en\": \"Gross Salary\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 201}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(16, 'default', 'Salary component created', 'App\\Models\\SalaryComponent', 11, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"NET_SALARY\", \"name_ar\": \"صافي الراتب\", \"name_en\": \"Net Salary\", \"taxable\": false, \"calc_mode\": \"formula\", \"comp_type\": \"info\", \"priority_order\": 202}}', 'created', NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(17, 'salary_component', 'Seeded 11 predefined salary components', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(18, 'employee', 'Employee created', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"EMP001\", \"email\": \"khaled.h87@gmail.com\", \"phone\": \"01007847333\", \"status\": \"active\", \"hire_date\": \"2014-01-05T00:00:00.000000Z\", \"last_name\": \"Helmy\", \"first_name\": \"Khaled\", \"manager_id\": null, \"arabic_name\": \"خالد محمد حلمي محمد يسري\", \"position_id\": 12, \"department_id\": 4, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', 'created', NULL, '2025-09-13 02:08:18', '2025-09-13 02:08:18'),
(19, 'employee', 'Employee created', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:08:18', '2025-09-13 02:08:18'),
(20, 'employee', 'Employee created', 'App\\Models\\Employee', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"code\": \"EMP002\", \"email\": \"dnassar@sarieldin.com\", \"phone\": \"0235352424\", \"status\": \"active\", \"hire_date\": \"2012-07-01T00:00:00.000000Z\", \"last_name\": \"Nassar\", \"first_name\": \"Doaa\", \"manager_id\": null, \"arabic_name\": \"دعاء عبد الدايم نصار\", \"position_id\": 9, \"department_id\": 2, \"photo_uploaded_at\": null, \"employment_type_id\": 1, \"photo_original_name\": null, \"salary_visibility_flag\": true}}', 'created', NULL, '2025-09-13 02:19:08', '2025-09-13 02:19:08'),
(21, 'employee', 'Employee created', 'App\\Models\\Employee', 2, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:19:08', '2025-09-13 02:19:08'),
(22, 'employee', 'Employee updated', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '{\"old\": {\"manager_id\": null}, \"attributes\": {\"manager_id\": 2}}', 'updated', NULL, '2025-09-13 02:19:24', '2025-09-13 02:19:24'),
(23, 'employee', 'Employee updated', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:19:24', '2025-09-13 02:19:24'),
(24, 'employee', 'Employee updated', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '{\"old\": {\"photo_uploaded_at\": null, \"photo_original_name\": null}, \"attributes\": {\"photo_uploaded_at\": \"2025-09-13T05:51:05.000000Z\", \"photo_original_name\": \"FHSign.png\"}}', 'updated', NULL, '2025-09-13 02:51:05', '2025-09-13 02:51:05'),
(25, 'employee', 'Employee photo uploaded', 'App\\Models\\Employee', 1, 'App\\Models\\User', 1, '{\"photo_name\": \"FHSign.png\"}', NULL, NULL, '2025-09-13 02:51:05', '2025-09-13 02:51:05'),
(26, 'employee', 'Employee updated', 'App\\Models\\Employee', 2, 'App\\Models\\User', 1, '{\"old\": {\"photo_uploaded_at\": null, \"photo_original_name\": null}, \"attributes\": {\"photo_uploaded_at\": \"2025-09-13T05:51:34.000000Z\", \"photo_original_name\": \"RASign.png\"}}', 'updated', NULL, '2025-09-13 02:51:34', '2025-09-13 02:51:34'),
(27, 'employee', 'Employee photo uploaded', 'App\\Models\\Employee', 2, 'App\\Models\\User', 1, '{\"photo_name\": \"RASign.png\"}', NULL, NULL, '2025-09-13 02:51:34', '2025-09-13 02:51:34'),
(28, 'default', 'Document created', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"type\": \"id_card\", \"expires_at\": \"2025-09-17T00:00:00.000000Z\", \"visibility\": \"private\", \"original_name\": \"C 1694 AW (Dissenting).pdf\"}}', 'created', NULL, '2025-09-13 02:52:59', '2025-09-13 02:52:59'),
(29, 'document', 'Document uploaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:52:59', '2025-09-13 02:52:59'),
(30, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:42', '2025-09-13 02:53:42'),
(31, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:44', '2025-09-13 02:53:44'),
(32, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:46', '2025-09-13 02:53:46'),
(33, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:47', '2025-09-13 02:53:47'),
(34, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:47', '2025-09-13 02:53:47'),
(35, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:47', '2025-09-13 02:53:47'),
(36, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:47', '2025-09-13 02:53:47'),
(37, 'document', 'Document downloaded', 'App\\Models\\Document', 1, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 02:53:47', '2025-09-13 02:53:47'),
(38, 'authentication', 'User logged in', NULL, NULL, 'App\\Models\\User', 1, '[]', NULL, NULL, '2025-09-13 08:33:19', '2025-09-13 08:33:19');

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
  PRIMARY KEY (`id`),
  KEY `fk_contract_employee` (`employee_id`),
  KEY `idx_contract_end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `employee_id`, `contract_id`, `type`, `path`, `original_name`, `mime`, `checksum`, `visibility`, `expires_at`, `version_current`, `watermark_note`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'id_card', 'documents/1757742778_5298c59a-1ae1-4a02-9219-7044bf500bb4.pdf', 'C 1694 AW (Dissenting).pdf', 'application/pdf', 'fe7333961c3697a15e2bcba06ee5d2c297f95bc57c9007e6b75894ae529c22a6', 'private', '2025-09-17', 1, 'HR Users Only', '2025-09-13 02:52:59', '2025-09-13 02:52:59');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `document_versions`
--

INSERT INTO `document_versions` (`id`, `document_id`, `version_no`, `path`, `checksum`, `created_at`) VALUES
(1, 1, 1, 'documents/1757742778_5298c59a-1ae1-4a02-9219-7044bf500bb4.pdf', 'fe7333961c3697a15e2bcba06ee5d2c297f95bc57c9007e6b75894ae529c22a6', '2025-09-13 02:52:59');

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
  `photo_path` varchar(255) DEFAULT NULL,
  `photo_original_name` varchar(255) DEFAULT NULL,
  `photo_size` int UNSIGNED DEFAULT NULL,
  `photo_mime_type` varchar(255) DEFAULT NULL,
  `photo_uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_emp_dept` (`department_id`),
  KEY `fk_emp_pos` (`position_id`),
  KEY `fk_emp_type` (`employment_type_id`),
  KEY `fk_emp_manager` (`manager_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `code`, `first_name`, `last_name`, `arabic_name`, `email`, `phone`, `hire_date`, `status`, `department_id`, `position_id`, `employment_type_id`, `manager_id`, `national_id`, `salary_visibility_flag`, `photo_path`, `photo_original_name`, `photo_size`, `photo_mime_type`, `photo_uploaded_at`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', 'Khaled', 'Helmy', 'خالد محمد حلمي محمد يسري', 'khaled.h87@gmail.com', '01007847333', '2014-01-05', 'active', 4, 12, 1, 2, 0x65794a7064694936496e5a4e5445685964437475656b704a597a4a784e7a46764d6b354e6457633950534973496e5a686248566c496a6f696447317256454e55545735755157527564326479645735455545746c647a3039496977696257466a496a6f694e7a557a4e7a686a596d55335a444d785a4752685a475a6b595449784d446733597a45314f446c6959574a6c4e5745794d6a63775a475135595463795a44526b5a4451324d446b7a597a42685a6a566b5a57457859534973496e52685a79493649694a39, 1, 'employee_photos/EMP001_1757742664.png', 'FHSign.png', 74062, 'image/png', '2025-09-13 02:51:05', '2025-09-13 02:08:18', '2025-09-13 02:51:05'),
(2, 'EMP002', 'Doaa', 'Nassar', 'دعاء عبد الدايم نصار', 'dnassar@sarieldin.com', '0235352424', '2012-07-01', 'active', 2, 9, 1, NULL, 0x65794a7064694936496b644e4e56684e563231504d6d46766556463652325a3353316b726557633950534973496e5a686248566c496a6f6956335247636d4a714e43396157555a58646d6b776256524e596c6833647a3039496977696257466a496a6f694e474a694d5449324e4749344f4745305a446c6a4d7a67354f546b325a544a684d5452694e44686b5a6a52684d574d784f54677859545a6d4f546c6b4f44677a4e5467795a5445334d446c6a5a445a6b59574a6c5a434973496e52685a79493649694a39, 1, 'employee_photos/EMP002_1757742694.png', 'RASign.png', 84658, 'image/png', '2025-09-13 02:51:34', '2025-09-13 02:19:08', '2025-09-13 02:51:34');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(9, '2025_09_12_202341_add_photo_fields_to_employees_table', 2),
(10, '2025_09_13_050239_add_guard_name_to_roles_table', 3),
(12, '2025_09_13_050331_add_guard_name_to_permissions_table', 4),
(13, '2025_09_13_062120_create_contract_expiry_notifications_table', 5),
(15, '2025_09_13_113922_create_payroll_runs_table', 6),
(16, '2025_09_13_113953_create_payslips_table', 7),
(17, '2025_09_13_114025_create_payslip_lines_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

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
-- Table structure for table `payroll_runs`
--

DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE IF NOT EXISTS `payroll_runs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `name` varchar(120) NOT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `display_name` varchar(160) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'employee.view', 'web', 'View Employees', NULL, NULL),
(2, 'employee.edit', 'web', 'Edit Employees', NULL, NULL),
(3, 'contract.view', 'web', 'View Contracts', NULL, NULL),
(4, 'contract.edit', 'web', 'Edit Contracts', NULL, NULL),
(5, 'payroll.view', 'web', 'View Payroll', NULL, NULL),
(6, 'payroll.view_net', 'web', 'View Net/Gross Payroll', NULL, NULL),
(7, 'payroll.edit', 'web', 'Edit Payroll', NULL, NULL),
(8, 'attendance.view', 'web', 'View Attendance', NULL, NULL),
(9, 'attendance.edit', 'web', 'Edit Attendance', NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
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
(1, 'HR_Admin_Manager', 'web', 'HR and Administration Manager', NULL, NULL),
(2, 'Accounting_Manager', 'web', 'Accounting Manager', NULL, NULL),
(3, 'HR_Coordinator', 'web', 'HR Coordinator', NULL, NULL),
(4, 'Accountant', 'web', 'Accountant', NULL, NULL),
(5, 'Employee', 'web', 'Employee', NULL, NULL),
(6, 'IT_Admin', 'web', 'IT Administrator', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `salary_components`
--

INSERT INTO `salary_components` (`id`, `code`, `name_en`, `name_ar`, `comp_type`, `calc_mode`, `taxable`, `visible_to_roles`, `priority_order`, `created_at`, `updated_at`) VALUES
(1, 'BASIC_SALARY', 'Basic Salary', 'الراتب الأساسي', 'earning', 'fixed', 1, NULL, 1, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(2, 'HOUSING_ALLOWANCE', 'Housing Allowance', 'بدل السكن', 'earning', 'fixed', 1, NULL, 2, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(3, 'TRANSPORT_ALLOWANCE', 'Transportation Allowance', 'بدل المواصلات', 'earning', 'fixed', 1, NULL, 3, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(4, 'OVERTIME', 'Overtime', 'العمل الإضافي', 'earning', 'variable_net_based', 1, NULL, 4, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(5, 'BONUS', 'Bonus', 'المكافأة', 'earning', 'variable_net_based', 1, NULL, 5, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(6, 'SOCIAL_INSURANCE', 'Social Insurance Deduction', 'خصم التأمين الاجتماعي', 'deduction', 'formula', 0, NULL, 101, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(7, 'INCOME_TAX', 'Income Tax', 'ضريبة الدخل', 'deduction', 'formula', 0, NULL, 102, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(8, 'ADVANCE_DEDUCTION', 'Advance Salary Deduction', 'خصم السلفة', 'deduction', 'variable_net_based', 0, NULL, 103, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(9, 'LOAN_DEDUCTION', 'Loan Deduction', 'قسط القرض', 'deduction', 'fixed', 0, NULL, 104, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(10, 'GROSS_SALARY', 'Gross Salary', 'إجمالي الراتب', 'info', 'formula', 0, '[\"HR_Admin_Manager\", \"Accounting_Manager\"]', 201, '2025-09-13 02:05:29', '2025-09-13 02:05:29'),
(11, 'NET_SALARY', 'Net Salary', 'صافي الراتب', 'info', 'formula', 0, '[\"HR_Admin_Manager\", \"Accounting_Manager\"]', 202, '2025-09-13 02:05:29', '2025-09-13 02:05:29');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `employee_id`, `two_factor_secret`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'HR Administrator', 'hr@sarieldin.com', '$2y$12$c/XKOJzvj8v0djASQNafGeRLQdGLsAU1ugVM9rhP2HomW41DallfO', NULL, NULL, 'Z8Gs4hzFi5Ny2DeBHFEKWq6Nau9Y11G2g3CSqywAL5UWNYVG0EL3QKZWeDBq', '2025-09-12 16:17:47', '2025-09-12 16:17:47');

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
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `fk_pr_perm` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

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
