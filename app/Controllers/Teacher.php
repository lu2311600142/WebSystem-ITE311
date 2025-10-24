<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    /**
     * dashboard() - Teacher Dashboard
     * 
     * No need for manual role checks here because:
     * 1. AuthFilter checks if user is logged in
     * 2. RoleAuth filter checks if user has 'teacher' or 'admin' role
     * If they reach this method, they're already authorized!
     */
    public function dashboard()
    {
        $session = session();

        // Prepare dashboard data
        $data = [
            'title' => 'Teacher Dashboard',
            'username' => $session->get('username'),
            'email' => $session->get('email'),
            'role' => $session->get('role'),
        ];

        return view('teacher_dashboard', $data);  
    }
}