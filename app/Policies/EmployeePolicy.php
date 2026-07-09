<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin', 'hr']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $employee->user ? strtolower($employee->user->role) : 'employee';
            return !in_array($empUserRole, ['superadmin', 'admin', 'hr']);
        }
        
        return $user->id === $employee->user_id;
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
    public function update(User $user, Employee $employee): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $employee->user ? strtolower($employee->user->role) : 'employee';
            return !in_array($empUserRole, ['superadmin', 'admin', 'hr']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employee $employee): bool
    {
        if (in_array(strtolower($user->role), ['superadmin', 'admin'])) return true;
        
        if (strtolower($user->role) === 'hr') {
            $empUserRole = $employee->user ? strtolower($employee->user->role) : 'employee';
            return !in_array($empUserRole, ['superadmin', 'admin', 'hr']);
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        return in_array(strtolower($user->role), ['superadmin', 'admin']);
    }
}
