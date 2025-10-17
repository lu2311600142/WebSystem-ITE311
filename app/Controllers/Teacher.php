<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Authorization check - only teacher can access
        if ($session->get('role') !== 'teacher') {
            return redirect()->to('/announcements')->with('error', 'Access denied.');
        }

        return view('teacher_dashboard');  
    }
}