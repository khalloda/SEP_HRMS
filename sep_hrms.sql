-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 12, 2025 at 02:38 PM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `status` enum('draft','locked','posted') NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

DROP TABLE IF EXISTS `payslips`;
CREATE TABLE IF NOT EXISTS `payslips` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payroll_run_id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `gross` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency` char(3) NOT NULL DEFAULT 'EGP',
  `pdf_document_id` bigint UNSIGNED DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_payslip_run_emp` (`payroll_run_id`,`employee_id`),
  KEY `fk_payslip_employee` (`employee_id`),
  KEY `fk_payslip_pdf` (`pdf_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_lines`
--

DROP TABLE IF EXISTS `payslip_lines`;
CREATE TABLE IF NOT EXISTS `payslip_lines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payslip_id` bigint UNSIGNED NOT NULL,
  `component_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `meta_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pl` (`payslip_id`,`component_id`),
  KEY `fk_pl_component` (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `display_name` varchar(160) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'employee.view', 'View Employees', NULL, NULL),
(2, 'employee.edit', 'Edit Employees', NULL, NULL),
(3, 'contract.view', 'View Contracts', NULL, NULL),
(4, 'contract.edit', 'Edit Contracts', NULL, NULL),
(5, 'payroll.view', 'View Payroll', NULL, NULL),
(6, 'payroll.view_net', 'View Net/Gross Payroll', NULL, NULL),
(7, 'payroll.edit', 'Edit Payroll', NULL, NULL),
(8, 'attendance.view', 'View Attendance', NULL, NULL),
(9, 'attendance.edit', 'Edit Attendance', NULL, NULL);

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
  `display_name` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'HR_Admin_Manager', 'HR and Administration Manager', NULL, NULL),
(2, 'Accounting_Manager', 'Accounting Manager', NULL, NULL),
(3, 'HR_Coordinator', 'HR Coordinator', NULL, NULL),
(4, 'Accountant', 'Accountant', NULL, NULL),
(5, 'Employee', 'Employee', NULL, NULL),
(6, 'IT_Admin', 'IT Administrator', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Constraints for table `payslips`
--
ALTER TABLE `payslips`
  ADD CONSTRAINT `fk_payslip_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payslip_pdf` FOREIGN KEY (`pdf_document_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_payslip_run` FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payslip_lines`
--
ALTER TABLE `payslip_lines`
  ADD CONSTRAINT `fk_pl_component` FOREIGN KEY (`component_id`) REFERENCES `salary_components` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_pl_payslip` FOREIGN KEY (`payslip_id`) REFERENCES `payslips` (`id`) ON DELETE CASCADE;

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
