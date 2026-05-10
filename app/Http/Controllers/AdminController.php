<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    // Show the Login Form
    public function showLogin()
    {
        return view('admin.login');
    }

    // Process the Login Request
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        // Check if Admin exists and password matches (using Hash check)
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Store admin in session
            Session::put('admin_id', $admin->admin_id);
            Session::put('admin_name', $admin->first_name);
            
            return redirect('/')->with('success', 'Welcome back, ' . $admin->first_name . '!');
        }

        return redirect()->back()->withErrors(['login' => 'Invalid username or password.']);
    }

    // Show Register Form (To create new staff/admin)
    public function create()
    {
        return view('admin.register');
    }

    // Store a new Admin
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admin_table,username',
            'password' => 'required|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // SECURE: Hashing the password
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
        ]);

        return redirect('/login')->with('success', 'Admin account created! Please log in.');
    }

    // Logout
    public function logout()
    {
        Session::forget(['admin_id', 'admin_name']);
        return redirect('/login')->with('success', 'You have logged out.');
    }
}