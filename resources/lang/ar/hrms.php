<?php

return [
    /*
    |--------------------------------------------------------------------------
    | HRMS Language Lines (Arabic)
    |--------------------------------------------------------------------------
    |
    | The following language lines are used throughout the HRMS application.
    | These are specific to the Human Resource Management System features.
    |
    */

    // Common
    'dashboard' => 'لوحة القيادة',
    'search' => 'بحث',
    'filter' => 'تصفية',
    'clear' => 'مسح',
    'actions' => 'الإجراءات',
    'view' => 'عرض',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'save' => 'حفظ',
    'cancel' => 'إلغاء',
    'create' => 'إنشاء',
    'add' => 'إضافة',
    'update' => 'تحديث',
    'created_successfully' => 'تم الإنشاء بنجاح',
    'updated_successfully' => 'تم التحديث بنجاح',
    'deleted_successfully' => 'تم الحذف بنجاح',

    // Navigation
    'employees' => 'الموظفون',
    'departments' => 'الأقسام',
    'positions' => 'المناصب',
    'contracts' => 'العقود',
    'payroll' => 'الرواتب',
    'attendance' => 'الحضور',
    'documents' => 'المستندات',
    'reports' => 'التقارير',
    'settings' => 'الإعدادات',

    // Employee Fields
    'employee_code' => 'كود الموظف',
    'first_name' => 'الاسم الأول',
    'last_name' => 'الاسم الأخير',
    'arabic_name' => 'الاسم بالعربية',
    'email' => 'البريد الإلكتروني',
    'phone' => 'الهاتف',
    'hire_date' => 'تاريخ التوظيف',
    'department' => 'القسم',
    'position' => 'المنصب',
    'manager' => 'المدير',
    'status' => 'الحالة',
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'terminated' => 'منتهي الخدمة',
    'on_leave' => 'في إجازة',

    // Contract Fields
    'contract_type' => 'نوع العقد',
    'start_date' => 'تاريخ البداية',
    'end_date' => 'تاريخ النهاية',
    'permanent' => 'دائم',
    'fixed_term' => 'محدد المدة',
    'probation' => 'تجريبي',
    'internship' => 'تدريب',
    'consultancy' => 'استشاري',

    // Payroll
    'salary' => 'الراتب',
    'basic_salary' => 'الراتب الأساسي',
    'allowances' => 'البدلات',
    'deductions' => 'الاستقطاعات',
    'gross_salary' => 'إجمالي الراتب',
    'net_salary' => 'صافي الراتب',
    'payslip' => 'مفردة الراتب',
    'payroll_run' => 'دورة الرواتب',

    // Departments
    'all_departments' => 'جميع الأقسام',
    'hr' => 'الموارد البشرية',
    'admin' => 'الإدارة',
    'accounting' => 'الحسابات',
    'it' => 'تكنولوجيا المعلومات',
    'litigation' => 'التقاضي',
    'corporate' => 'الشركات',
    'arbitration' => 'التحكيم',

    // Positions - Lawyers
    'managing_partner' => 'شريك إداري',
    'senior_partner' => 'شريك أول',
    'partner' => 'شريك',
    'junior_partner' => 'شريك مبتدئ',
    'senior_associate' => 'محام أول',
    'associate' => 'محام',
    'junior_associate' => 'محام مبتدئ',
    'intern' => 'متدرب',

    // Positions - Admin
    'hr_admin_manager' => 'مدير الموارد البشرية والإدارة',
    'accounting_manager' => 'مدير الحسابات',
    'accountant' => 'محاسب',
    'senior_systems_engineer' => 'مهندس نظم أول',
    'messenger' => 'مراسل',
    'admin_assistant' => 'مساعد إداري',
    'office_boy' => 'عامل خدمات',

    // Filters
    'expiring_contracts' => 'العقود المنتهية الصلاحية',
    'missing_documents' => 'المستندات المفقودة',
    'search_employees' => 'البحث في الموظفين...',

    // Messages
    'no_results' => 'لا توجد نتائج',
    'loading' => 'جاري التحميل...',
    'confirm_delete' => 'هل أنت متأكد من حذف هذا العنصر؟',
    'recent_activity' => 'النشاط الأخير',
    
    // Employee Management
    'add_employee' => 'إضافة موظف',
    'employee_created' => 'تم إنشاء الموظف بنجاح',
    'employee_updated' => 'تم تحديث الموظف بنجاح',
    'employee_details' => 'تفاصيل الموظف',
    'employment_information' => 'معلومات التوظيف',
    'personal_information' => 'المعلومات الشخصية',
    'contact_information' => 'معلومات الاتصال',

    // Document Types
    'national_id' => 'الهوية الوطنية',
    'bar_license' => 'ترخيص النقابة',
    'contract_document' => 'مستند العقد',
    'employment_proof' => 'إثبات العمل',
    'hr_letter' => 'خطاب الموارد البشرية',

    // Status translations (nested for backward compatibility)
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'terminated' => 'منتهي الخدمة',
        'on_leave' => 'في إجازة',
    ],

    // Activity Log Messages
    'activity' => [
        'Employee created' => 'تم إنشاء الموظف',
        'Employee updated' => 'تم تحديث الموظف',
        'Employee deleted' => 'تم حذف الموظف',
        'Employee terminated' => 'تم إنهاء خدمة الموظف',
        'Employee reactivated' => 'تم إعادة تفعيل الموظف',
        'Employee photo uploaded' => 'تم رفع صورة الموظف',
        'Employee photo deleted' => 'تم حذف صورة الموظف',
        'Document uploaded' => 'تم رفع المستند',
        'Document updated' => 'تم تحديث المستند',
        'Document deleted' => 'تم حذف المستند',
        'Document downloaded' => 'تم تحميل المستند',
        'Salary component created' => 'تم إنشاء مكون الراتب',
        'Salary component updated' => 'تم تحديث مكون الراتب',
        'Salary component deleted' => 'تم حذف مكون الراتب',
        'User logged in' => 'تم تسجيل دخول المستخدم',
        'User logged out' => 'تم تسجيل خروج المستخدم',
        'User registered' => 'تم تسجيل المستخدم',
        'User profile updated' => 'تم تحديث ملف المستخدم',
        'User account linked to employee' => 'تم ربط حساب المستخدم بالموظف',
    ],

    // Language Toggle
    'language' => 'اللغة',
    'english' => 'English',
    'arabic' => 'العربية',

    // Employee specific translations
    'employee' => [
        'created_successfully' => 'تم إنشاء الموظف بنجاح',
        'updated_successfully' => 'تم تحديث الموظف بنجاح',
        'deleted_successfully' => 'تم حذف الموظف بنجاح',
        'terminated_successfully' => 'تم إنهاء خدمة الموظف بنجاح',
        'reactivated_successfully' => 'تم إعادة تفعيل الموظف بنجاح',
        'cannot_delete_has_dependencies' => 'لا يمكن حذف الموظف: لديه عقود أو مفردات راتب أو تابعين',
        'cannot_be_own_manager' => 'لا يمكن للموظف أن يكون مديرًا لنفسه',
        'not_terminated' => 'الموظف ليس منتهي الخدمة',
        'termination_reason' => 'سبب إنهاء الخدمة',
        'termination_date' => 'تاريخ إنهاء الخدمة',
        'terminate' => 'إنهاء الخدمة',
        'reactivate' => 'إعادة التفعيل',
        'export' => 'تصدير',
        'statistics' => 'الإحصائيات',
        'years_of_service' => 'سنوات الخدمة',
        'direct_reports' => 'التابعون المباشرون',
        'direct_report' => 'تابع مباشر',
        'has_user_account' => 'لديه حساب مستخدم',
        'salary_visibility' => 'رؤية الراتب',
        'employment_type' => 'نوع التوظيف',
        'full_name' => 'الاسم الكامل',
        'display_name' => 'اسم العرض',
        'hierarchy_level' => 'مستوى التسلسل الهرمي',
        'is_overtime_eligible' => 'مؤهل للعمل الإضافي',
        'total_employees' => 'إجمالي الموظفين',
        'active_employees' => 'الموظفون النشطون',
        'new_hires_this_month' => 'التوظيفات الجديدة هذا الشهر',
        'by_department' => 'حسب القسم',
        'by_position' => 'حسب المنصب',
        'by_employment_type' => 'حسب نوع التوظيف',
        // Photo Management
        'photo' => 'الصورة',
        'upload_photo' => 'رفع صورة',
        'change_photo' => 'تغيير الصورة',
        'delete_photo' => 'حذف الصورة',
        'photo_uploaded_successfully' => 'تم رفع الصورة بنجاح',
        'photo_deleted_successfully' => 'تم حذف الصورة بنجاح',
        'no_photo_to_delete' => 'لا توجد صورة للحذف',
        'photo_requirements' => 'يجب أن تكون الصورة بصيغة JPEG أو PNG أو JPG أو GIF وأقل من 5 ميجابايت',
        'default_photo' => 'الصورة الافتراضية',
        'photo_info' => 'معلومات الصورة',
        'original_filename' => 'اسم الملف الأصلي',
        'file_size' => 'حجم الملف',
        'uploaded_date' => 'تاريخ الرفع',
        'photo_actions' => 'إجراءات الصورة',
    ],

];