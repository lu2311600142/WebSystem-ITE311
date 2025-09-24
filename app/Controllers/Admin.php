<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Authorization check - only admin can access
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin privileges required.');
        }

        $userModel = new UserModel();
        
        // Prepare dashboard data
        $data = [
            'title' => 'Admin Dashboard',
            'username' => $session->get('username'),
            'role' => $session->get('role'),
            'totalUsers' => $userModel->countAll(),
            'totalAdmins' => $userModel->where('role', 'admin')->countAllResults(),
            'totalTeachers' => $userModel->where('role', 'teacher')->countAllResults(),
            'totalStudents' => $userModel->where('role', 'student')->countAllResults(),
            'recentUsers' => $userModel->orderBy('created_at', 'DESC')->limit(5)->find()
        ];

        return view('admin/dashboard', $data);
    }
}