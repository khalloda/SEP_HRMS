
-- HRMS Schema for Sarie Eldin & Partners (MySQL 8, utf8mb4)
-- Updated 2025-09-12 with: two-step approvals, document versioning, tagging, search fulltext, case link
SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- =========================
-- Core reference tables
-- =========================
CREATE TABLE IF NOT EXISTS departments (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(32) UNIQUE NOT NULL,
  name_en VARCHAR(120) NOT NULL,
  name_ar VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS positions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name_en VARCHAR(120) NOT NULL,
  name_ar VARCHAR(120) NOT NULL,
  category ENUM('lawyer','admin') NOT NULL,
  grade_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY uq_positions_name_cat (name_en, category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS employment_types (
  id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name_en VARCHAR(60) NOT NULL,
  name_ar VARCHAR(60) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY uq_employment_types_name (name_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Users & Employees
-- =========================
CREATE TABLE IF NOT EXISTS employees (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(32) UNIQUE NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  arabic_name VARCHAR(160) NULL,
  email VARCHAR(190) UNIQUE,
  phone VARCHAR(40),
  hire_date DATE,
  status ENUM('active','inactive','terminated','on_leave') DEFAULT 'active',
  department_id BIGINT UNSIGNED,
  position_id BIGINT UNSIGNED,
  employment_type_id TINYINT UNSIGNED,
  manager_id BIGINT UNSIGNED NULL,
  national_id VARBINARY(256) NULL, -- encrypted-at-rest in app
  salary_visibility_flag TINYINT(1) NOT NULL DEFAULT 0, -- 1 = can view own net/gross
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_emp_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON UPDATE CASCADE,
  CONSTRAINT fk_emp_pos FOREIGN KEY (position_id) REFERENCES positions(id) ON UPDATE CASCADE,
  CONSTRAINT fk_emp_type FOREIGN KEY (employment_type_id) REFERENCES employment_types(id) ON UPDATE CASCADE,
  CONSTRAINT fk_emp_manager FOREIGN KEY (manager_id) REFERENCES employees(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fulltext for global search (name/email/code)
ALTER TABLE employees
  ADD FULLTEXT KEY ft_employees_name_email (first_name, last_name, arabic_name, email, code);

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  employee_id BIGINT UNSIGNED NULL,
  two_factor_secret VARCHAR(255) NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_users_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(80) UNIQUE NOT NULL,   -- e.g., HR_Admin_Manager
  display_name VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permissions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) UNIQUE NOT NULL,  -- e.g., payroll.view_net
  display_name VARCHAR(160) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS role_user (
  role_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, user_id),
  CONSTRAINT fk_ru_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  CONSTRAINT fk_ru_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permission_role (
  permission_id BIGINT UNSIGNED NOT NULL,
  role_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (permission_id, role_id),
  CONSTRAINT fk_pr_perm FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
  CONSTRAINT fk_pr_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Contracts & Documents
-- =========================
CREATE TABLE IF NOT EXISTS contracts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  type ENUM('permanent','fixed_term','probation','internship','consultancy') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  terms_json JSON NULL,
  status ENUM('active','expired','terminated','pending') DEFAULT 'active',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_contract_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  INDEX idx_contract_end_date (end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS documents (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NULL,
  contract_id BIGINT UNSIGNED NULL,
  type VARCHAR(60) NOT NULL, -- id_card, bar_license, contract_pdf, payslip_pdf, hr_letter, etc.
  path VARCHAR(255) NOT NULL,
  original_name VARCHAR(190) NOT NULL,
  mime VARCHAR(80) NOT NULL,
  checksum CHAR(64) NULL,
  visibility ENUM('private','shared') DEFAULT 'private',
  expires_at DATE NULL,
  version_current INT NOT NULL DEFAULT 1,
  watermark_note VARCHAR(120) NULL, -- optional watermark text
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_doc_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL,
  CONSTRAINT fk_doc_contract FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE SET NULL,
  INDEX idx_documents_exp (expires_at, type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Document Versioning
CREATE TABLE IF NOT EXISTS document_versions (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  document_id BIGINT UNSIGNED NOT NULL,
  version_no INT NOT NULL,
  path VARCHAR(255) NOT NULL,
  checksum CHAR(64) NULL,
  created_at TIMESTAMP NULL,
  CONSTRAINT fk_docver_document FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
  UNIQUE KEY uq_docver (document_id, version_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tagging
CREATE TABLE IF NOT EXISTS tags (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(60) UNIQUE NOT NULL,
  created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS document_tag (
  document_id BIGINT UNSIGNED NOT NULL,
  tag_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (document_id, tag_id),
  CONSTRAINT fk_dt_doc FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
  CONSTRAINT fk_dt_tag FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Payroll
-- =========================
CREATE TABLE IF NOT EXISTS salary_components (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(40) UNIQUE NOT NULL,          -- BASIC, HOUSING, DED_TAX, etc.
  name_en VARCHAR(120) NOT NULL,
  name_ar VARCHAR(120) NOT NULL,
  comp_type ENUM('earning','deduction','info') NOT NULL,
  calc_mode ENUM('fixed','formula','variable_net_based') NOT NULL,
  taxable TINYINT(1) NOT NULL DEFAULT 1,
  visible_to_roles JSON NULL,                -- array of role names allowed to see this line
  priority_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS salary_structures (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'EGP',
  effective_from DATE NOT NULL,
  effective_to DATE NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_ss_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  INDEX idx_ss_employee_period (employee_id, effective_from, effective_to)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS salary_structure_components (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  structure_id BIGINT UNSIGNED NOT NULL,
  component_id BIGINT UNSIGNED NOT NULL,
  value_numeric DECIMAL(12,2) NULL,     -- for fixed
  formula_expr VARCHAR(1000) NULL,      -- for formula/net-based
  depends_on JSON NULL,                 -- list of component codes
  priority_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_ssc_structure FOREIGN KEY (structure_id) REFERENCES salary_structures(id) ON DELETE CASCADE,
  CONSTRAINT fk_ssc_component FOREIGN KEY (component_id) REFERENCES salary_components(id) ON DELETE CASCADE,
  UNIQUE KEY uq_ssc (structure_id, component_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payroll_runs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  period_start DATE NOT NULL,
  period_end DATE NOT NULL,
  status ENUM('draft','locked','posted') NOT NULL DEFAULT 'draft',
  created_by BIGINT UNSIGNED NULL,
  posted_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT chk_period CHECK (period_end >= period_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payslips (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  payroll_run_id BIGINT UNSIGNED NOT NULL,
  employee_id BIGINT UNSIGNED NOT NULL,
  gross DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  net DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) NOT NULL DEFAULT 'EGP',
  pdf_document_id BIGINT UNSIGNED NULL, -- reference documents.id
  posted_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_payslip_run FOREIGN KEY (payroll_run_id) REFERENCES payroll_runs(id) ON DELETE CASCADE,
  CONSTRAINT fk_payslip_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  CONSTRAINT fk_payslip_pdf FOREIGN KEY (pdf_document_id) REFERENCES documents(id) ON DELETE SET NULL,
  UNIQUE KEY uq_payslip_run_emp (payroll_run_id, employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payslip_lines (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  payslip_id BIGINT UNSIGNED NOT NULL,
  component_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  meta_json JSON NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_pl_payslip FOREIGN KEY (payslip_id) REFERENCES payslips(id) ON DELETE CASCADE,
  CONSTRAINT fk_pl_component FOREIGN KEY (component_id) REFERENCES salary_components(id) ON DELETE RESTRICT,
  UNIQUE KEY uq_pl (payslip_id, component_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Attendance
-- =========================
CREATE TABLE IF NOT EXISTS attendance_devices (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  serial VARCHAR(80) UNIQUE,
  location VARCHAR(120),
  last_sync_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  device_id BIGINT UNSIGNED NULL,
  punch_time DATETIME NOT NULL,
  punch_type ENUM('in','out') NOT NULL,
  source ENUM('connector','manual') NOT NULL DEFAULT 'connector',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_al_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  CONSTRAINT fk_al_device FOREIGN KEY (device_id) REFERENCES attendance_devices(id) ON DELETE SET NULL,
  INDEX idx_al_emp_time (employee_id, punch_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Templates, Notifications & Audit
-- =========================
CREATE TABLE IF NOT EXISTS templates (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(60) UNIQUE NOT NULL,
  name_en VARCHAR(160) NOT NULL,
  name_ar VARCHAR(160) NOT NULL,
  type ENUM('contract','payslip','letter','employment_proof') NOT NULL,
  engine ENUM('blade','twig') NOT NULL DEFAULT 'blade',
  body LONGTEXT NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  category VARCHAR(60) NOT NULL, -- expiry, payroll, attendance, digest
  entity_type VARCHAR(60) NOT NULL,
  entity_id BIGINT UNSIGNED NOT NULL,
  message VARCHAR(255) NOT NULL,
  due_at DATETIME NOT NULL,
  sent_at DATETIME NULL,
  channel ENUM('email','dashboard') NOT NULL DEFAULT 'dashboard',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_notifications_due (due_at, category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  actor_id BIGINT UNSIGNED NULL,  -- users.id
  action VARCHAR(80) NOT NULL,
  entity_type VARCHAR(80) NOT NULL,
  entity_id BIGINT UNSIGNED NULL,
  before_json JSON NULL,
  after_json JSON NULL,
  ip VARCHAR(64) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_audit_entity (entity_type, entity_id),
  INDEX idx_audit_actor (actor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Two-step Approvals (generic engine)
-- =========================
CREATE TABLE IF NOT EXISTS approval_requests (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  entity_type VARCHAR(80) NOT NULL,  -- e.g., 'Contract','PayrollRun'
  entity_id BIGINT UNSIGNED NOT NULL,
  action VARCHAR(80) NOT NULL,       -- 'terminate','post_payroll'
  requested_by BIGINT UNSIGNED NOT NULL, -- users.id
  required_steps TINYINT UNSIGNED NOT NULL DEFAULT 2,
  status ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_ar_entity (entity_type, entity_id),
  CONSTRAINT fk_ar_requester FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS approval_events (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  approval_request_id BIGINT UNSIGNED NOT NULL,
  step_no TINYINT UNSIGNED NOT NULL,
  approver_id BIGINT UNSIGNED NOT NULL, -- users.id
  decision ENUM('approved','rejected') NOT NULL,
  decision_note VARCHAR(255) NULL,
  decided_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  CONSTRAINT fk_ae_req FOREIGN KEY (approval_request_id) REFERENCES approval_requests(id) ON DELETE CASCADE,
  CONSTRAINT fk_ae_approver FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE RESTRICT,
  UNIQUE KEY uq_ae_step (approval_request_id, step_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- Case assignment link (future integration)
-- =========================
CREATE TABLE IF NOT EXISTS employee_cases (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  case_ref VARCHAR(120) NOT NULL, -- free text or external system key
  start_date DATE NULL,
  end_date DATE NULL,
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_ec_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  INDEX idx_ec_emp (employee_id),
  INDEX idx_ec_ref (case_ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;
