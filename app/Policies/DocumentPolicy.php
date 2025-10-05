<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    /**
     * Determine if the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager', 
            'HR_Coordinator', 
            'Accounting_Manager', 
            'Accountant', 
            'IT_Admin',
            'Employee'
        ]);
    }

    /**
     * Determine if the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        // HR and IT admins can view all documents
        if ($user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            return true;
        }

        // HR coordinators can view all except others' payslips
        if ($user->hasRole('HR_Coordinator')) {
            if ($document->type === 'payslip_pdf' && $document->employee_id !== $user->employee_id) {
                return false;
            }
            return true;
        }

        // Accounting can view payslips and financial documents
        if ($user->hasAnyRole(['Accounting_Manager', 'Accountant'])) {
            return in_array($document->type, ['payslip_pdf', 'contract_pdf']);
        }

        // Employees can only view their own documents
        return $document->employee_id === $user->employee_id;
    }

    /**
     * Determine if the user can create documents.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            'HR_Admin_Manager', 
            'HR_Coordinator', 
            'IT_Admin'
        ]);
    }

    /**
     * Determine if the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        // HR and IT admins can update all documents
        if ($user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            return true;
        }

        // HR coordinators can update all except payslips
        if ($user->hasRole('HR_Coordinator')) {
            return $document->type !== 'payslip_pdf';
        }

        return false;
    }

    /**
     * Determine if the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Only HR and IT admins can delete documents
        return $user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin']);
    }

    /**
     * Determine if the user can download the document.
     */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    /**
     * Determine if the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin']);
    }

    /**
     * Determine if the user can permanently delete the document.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasRole('IT_Admin');
    }
}