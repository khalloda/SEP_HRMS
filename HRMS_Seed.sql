
-- HRMS Seed Data for Sarie Eldin & Partners
-- Preload departments, positions, roles, permissions

INSERT INTO departments (code, name_en, name_ar) VALUES
('HR', 'Human Resources', 'الموارد البشرية'),
('ADMIN', 'Administration', 'الإدارة'),
('ACC', 'Accounting', 'الحسابات'),
('IT', 'Information Technology', 'تكنولوجيا المعلومات'),
('LIT', 'Litigation', 'التقاضي'),
('CORP', 'Corporate', 'الشركات'),
('ARB', 'Arbitration', 'التحكيم');

INSERT INTO positions (name_en, name_ar, category, grade_order) VALUES
('Managing Partner', 'شريك إداري', 'lawyer', 1),
('Senior Partner', 'شريك أول', 'lawyer', 2),
('Partner', 'شريك', 'lawyer', 3),
('Junior Partner', 'شريك مبتدئ', 'lawyer', 4),
('Senior Associate', 'محام أول', 'lawyer', 5),
('Associate', 'محام', 'lawyer', 6),
('Junior Associate', 'محام مبتدئ', 'lawyer', 7),
('Intern', 'متدرب', 'lawyer', 8),
('HR & Admin Manager', 'مدير الموارد البشرية والإدارة', 'admin', 1),
('Accounting Manager', 'مدير الحسابات', 'admin', 2),
('Accountant', 'محاسب', 'admin', 3),
('Senior Systems Engineer', 'مهندس نظم أول', 'admin', 4),
('Messenger', 'مراسل', 'admin', 5),
('Admin Assistant', 'مساعد إداري', 'admin', 6),
('Office Boy', 'عامل خدمات', 'admin', 7);

INSERT INTO employment_types (name_en, name_ar) VALUES
('Permanent','دائم'),
('Fixed-term','محدد المدة'),
('Probationary','تجريبي'),
('Internship','تدريب'),
('Consultancy','استشاري');

INSERT INTO roles (name, display_name) VALUES
('HR_Admin_Manager','HR and Administration Manager'),
('Accounting_Manager','Accounting Manager'),
('HR_Coordinator','HR Coordinator'),
('Accountant','Accountant'),
('Employee','Employee'),
('IT_Admin','IT Administrator');

-- Permissions examples
INSERT INTO permissions (name, display_name) VALUES
('employee.view','View Employees'),
('employee.edit','Edit Employees'),
('contract.view','View Contracts'),
('contract.edit','Edit Contracts'),
('payroll.view','View Payroll'),
('payroll.view_net','View Net/Gross Payroll'),
('payroll.edit','Edit Payroll'),
('attendance.view','View Attendance'),
('attendance.edit','Edit Attendance');

-- Map key permissions to roles (simplified)
INSERT INTO permission_role (permission_id, role_id)
SELECT p.id, r.id
FROM permissions p, roles r
WHERE (r.name='HR_Admin_Manager')
  OR (r.name='Accounting_Manager' AND p.name IN ('payroll.view','payroll.view_net','payroll.edit'))
  OR (r.name='HR_Coordinator' AND p.name IN ('employee.view','employee.edit','contract.view','contract.edit','payroll.view'));
