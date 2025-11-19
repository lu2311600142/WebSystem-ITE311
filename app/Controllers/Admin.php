<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\NotificationModel;

class Admin extends BaseController
{
   
    public function dashboard()
    {
        $session = session();
        $userModel = new UserModel();
        $userId = $session->get('id');

        // 🔔 ADD NOTIFICATION LINES
        $notificationModel = new NotificationModel();
        $data['unreadCount'] = $notificationModel->getUnreadCount($userId);
        $data['notifications'] = $notificationModel->getNotificationsForUser($userId, 5);

        // Prepare dashboard data
        $data['title'] = 'Admin Dashboard';
        $data['username'] = $session->get('username');
        $data['email'] = $session->get('email');
        $data['role'] = $session->get('role');
        $data['totalUsers'] = $userModel->countAll();
        $data['totalAdmins'] = $userModel->where('role', 'admin')->countAllResults();
        $data['totalTeachers'] = $userModel->where('role', 'teacher')->countAllResults();
        $data['totalStudents'] = $userModel->where('role', 'student')->countAllResults();
        $data['recentUsers'] = $userModel->orderBy('created_at', 'DESC')->limit(5)->find();

        return view('admin_dashboard', $data); 
    }
}
