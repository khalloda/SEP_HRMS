<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employee;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Log successful login
            activity('authentication')
                ->causedBy(Auth::user())
                ->log('User logged in');

            return redirect()->intended('/')->with('success', __('Welcome back!'));
        }

        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ])->onlyInput('email');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        // Log logout
        if (Auth::check()) {
            activity('authentication')
                ->causedBy(Auth::user())
                ->log('User logged out');
        }

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', __('You have been logged out successfully.'));
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        // Get employees without user accounts for linking
        $employees = Employee::whereDoesntHave('user')
            ->active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code', 'email']);
            
        return view('auth.register', compact('employees'));
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $validated['employee_id'],
        ]);

        // Assign default employee role if linked to employee
        if ($validated['employee_id']) {
            $user->assignRole('Employee');
        }

        // Log registration
        activity('authentication')
            ->causedBy($user)
            ->log('User registered');

        // Auto-login after registration
        Auth::login($user);

        return redirect('/')->with('success', __('Registration successful! Welcome to HRMS.'));
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['employee.department', 'employee.position', 'roles']);
        
        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|current_password',
            'password' => 'nullable|string|min:8|confirmed|required_with:current_password',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Log profile update
        activity('authentication')
            ->causedBy($user)
            ->log('User profile updated');

        return back()->with('success', __('Profile updated successfully.'));
    }

    /**
     * Link user account to employee.
     */
    public function linkEmployee(Request $request)
    {
        $user = Auth::user();
        
        // Only allow if not already linked
        if ($user->employee_id) {
            return back()->with('error', __('Account is already linked to an employee.'));
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:users,employee_id',
        ]);

        $employee = Employee::find($validated['employee_id']);
        
        $user->update(['employee_id' => $validated['employee_id']]);
        
        // Assign employee role if not already assigned
        if (!$user->hasRole('Employee')) {
            $user->assignRole('Employee');
        }

        activity('authentication')
            ->causedBy($user)
            ->withProperties(['employee_code' => $employee->code])
            ->log('User account linked to employee');

        return back()->with('success', __('Account successfully linked to employee: :name', [
            'name' => $employee->display_name
        ]));
    }
}