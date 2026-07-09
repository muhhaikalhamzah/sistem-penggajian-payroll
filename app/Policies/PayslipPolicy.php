<?php

namespace App\Policies;

use App\Models\Payslip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PayslipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin', 'finance', 'hr', 'employee']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payslip $payslip): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin', 'finance'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $payslip->employee && $payslip->employee->user ? strtolower($payslip->employee->user->role) : 'employee';
            return in_array($empUserRole, ['employee', 'finance']);
        }
        
        // Employee can only view their own AND it must be approved or paid
        return $user->employee && 
               $user->employee->id === $payslip->employee_id && 
               in_array($payslip->status, ['approved', 'paid']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin', 'hr']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payslip $payslip): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $payslip->employee && $payslip->employee->user ? strtolower($payslip->employee->user->role) : 'employee';
            return in_array($empUserRole, ['employee', 'finance']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payslip $payslip): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $payslip->employee && $payslip->employee->user ? strtolower($payslip->employee->user->role) : 'employee';
            return in_array($empUserRole, ['employee', 'finance']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payslip $payslip): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payslip $payslip): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin']);
    }
}
