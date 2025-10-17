<?php

namespace App\Controllers;

use App\Models\UserModel;

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
            return redirect()->to('/login')->with('error', 'Access denied. Teacher privileges required.');
        }

        // Prepare dashboard data
        $data = [
            'title' => 'Teacher Dashboard',
            'username' => $session->get('username'),
            'role' => $session->get('role'),
            'totalCourses' => 3, // Mock data
            'totalStudents' => 25, // Mock data
            'pendingAssignments' => 5, // Mock data
            'notifications' => [
                'New assignment submitted in Math 101',
                'Course "Physics Basics" needs review',
                'Student John Doe requested help'
            ]
        ];

        return view('teacher_dashboard', $data);  
    }
}